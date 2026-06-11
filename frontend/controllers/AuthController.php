<?php

namespace frontend\controllers;

use Yii;
use common\models\User;
use common\models\Donor;
use common\models\Hospital;
use common\models\Notification;
use yii\web\Controller;
use yii\filters\AccessControl;

class AuthController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['logout', 'dashboard'],
                'rules' => [
                    [
                        'actions' => ['logout', 'dashboard'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    // =====================
    // LOGIN
    // =====================
    public function actionLogin()
    {
        // Kama mtumiaji ameshaingia — mpeleke dashboard
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['auth/dashboard']);
        }

        $model = new \common\models\LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['auth/dashboard']);
        }

        return $this->render('login', ['model' => $model]);
    }

    // =====================
    // REGISTER
    // =====================
    public function actionRegister()
    {
        // Kama mtumiaji ameshaingia — mpeleke dashboard
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['auth/dashboard']);
        }

        $user   = new User();
        $donor  = new Donor();


    
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            if ($user->load($post) && $donor->load($post)) {
                // Weka role ya donor
                $user->role = User::ROLE_DONOR;
                $user->status = User::STATUS_ACTIVE;
                $user->setPassword($post['User']['password']);
                $user->generateAuthKey();

                // Hifadhi kwenye database kwa transaction
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($user->save()) {
                        $donor->user_id = $user->id;
                        if ($donor->save()) {
                            $transaction->commit();

                            // Tuma notification kwa Admin
                            $admin = User::findOne(['role' => User::ROLE_ADMIN]);
                            if ($admin) {
                                Notification::createNotification(
                                    $admin->id,
                                    'New Donor Registered',
                                    'A new donor ' . $user->username . ' has registered and is waiting for approval.',
                                    'info'
                                );
                            }

                            Yii::$app->session->setFlash('success', 'Registration successful! Please login.');
                            return $this->redirect(['auth/login']);
                        }
                    }
                    $transaction->rollBack();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Error occured: ' . $e->getMessage());
                }
            }
        }

        return $this->render('register', [
            'user'  => $user,
            'donor' => $donor,
        ]);
    }

    // =====================
    // REGISTER HOSPITAL
    // =====================
    public function actionRegisterHospital()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['auth/dashboard']);
        }

        $user     = new User();
        $hospital = new Hospital();

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            if ($user->load($post) && $hospital->load($post)) {
                $user->role   = User::ROLE_HOSPITAL;
                $user->status = User::STATUS_ACTIVE;
                $user->setPassword($post['User']['password']);
                $user->generateAuthKey();

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($user->save()) {
                        $hospital->user_id = $user->id;
                        if ($hospital->save()) {
                            $transaction->commit();

                            // Tuma notification kwa Admin
                            $admin = User::findOne(['role' => User::ROLE_ADMIN]);
                            if ($admin) {
                                 Notification::createNotification(
                                    $admin->id,
                                    'New Hospital Registered',
                                    'A new hospital ' . $hospital->name . ' has registered and is waiting for verification.',
                                    'warning'
                                );
                            }

                            Yii::$app->session->setFlash('success', 'Hospital registered successfully! Please wait for Admin verification.');
                            return $this->redirect(['auth/login']);
                        }
                    }
                    $transaction->rollBack();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Error occured: ' . $e->getMessage());
                }
            }
        }

        return $this->render('register-hospital', [
            'user'     => $user,
            'hospital' => $hospital,
        ]);
    }
    // =====================
    // DASHBOARD
    // =====================
    public function actionDashboard()
    {
        $user = Yii::$app->user->identity;

        // Mpeleke dashboard yake kulingana na role
        if ($user->isAdmin()) {
            return $this->redirect(['/admin/dashboard']);
        } elseif ($user->isDonor()) {
            return $this->redirect(['/donor/dashboard']);
        } elseif ($user->isHospital()) {
            return $this->redirect(['/hospital/dashboard']);
        }

        return $this->render('dashboard');
    }

    // =====================
    // LOGOUT
    // =====================
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['auth/login']);
    }

    // =====================
    // FORGOT PASSWORD
    // =====================
    public function actionForgotPassword()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['auth/dashboard']);
        }

        $email = '';
        $step  = 1;

        if (Yii::$app->request->isPost) {
            $post  = Yii::$app->request->post();
            $email = $post['email'] ?? '';
            $step  = (int)($post['step'] ?? 1);

            // Step 1 — Verify email
            if ($step == 1) {
                $user = User::findByEmail($email);
                if ($user) {
                    $step = 2;
                } else {
                    Yii::$app->session->setFlash('error', 'No account found with this email address.');
                    $step = 1;
                }
            }

            // Step 2 — Reset password
            if ($step == 2 && isset($post['new_password'])) {
                $user = User::findByEmail($email);
                if ($user) {
                    $newPassword     = $post['new_password'];
                    $confirmPassword = $post['confirm_password'] ?? '';

                    if (strlen($newPassword) < 6) {
                        Yii::$app->session->setFlash('error', 'Password must be at least 6 characters.');
                    } elseif ($newPassword !== $confirmPassword) {
                        Yii::$app->session->setFlash('error', 'Passwords do not match.');
                    } else {
                        $user->setPassword($newPassword);
                        $user->save(false);
                        Yii::$app->session->setFlash('success', 'Password reset successfully! Please login with your new password.');
                        return $this->redirect(['auth/login']);
                    }
                }
            }
        }

        return $this->render('forgot-password', [
            'email' => $email,
            'step'  => $step,
        ]);
    }
}