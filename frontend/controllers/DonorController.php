<?php

namespace frontend\controllers;

use Yii;
use common\models\Donor;
use common\models\Donation;
use common\models\Appointment;
use common\models\Notification;
use yii\web\Controller;
use yii\filters\AccessControl;

class DonorController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionDashboard()
    {
        $user  = Yii::$app->user->identity;
        $donor = Donor::findOne(['user_id' => $user->id]);

        if (!$donor) {
            return $this->redirect(['auth/login']);
        }

        $totalDonations    = Donation::find()->where(['donor_id' => $donor->id])->count();
        $upcomingAppointments = Appointment::find()
            ->where(['donor_id' => $donor->id, 'status' => 'approved'])
            ->andWhere(['>=', 'appointment_date', date('Y-m-d')])
            ->count();
        $unreadNotifications = Notification::find()
            ->where(['user_id' => $user->id, 'is_read' => 0])
            ->count();

        return $this->render('dashboard', [
            'donor'               => $donor,
            'totalDonations'      => $totalDonations,
            'upcomingAppointments'=> $upcomingAppointments,
            'unreadNotifications' => $unreadNotifications,
        ]);
    }
    // =====================
    // APPOINTMENTS
    // =====================
    public function actionAppointments()
    {
        $user  = Yii::$app->user->identity;
        $donor = Donor::findOne(['user_id' => $user->id]);

        $appointments = Appointment::find()
            ->where(['donor_id' => $donor->id])
            ->orderBy(['appointment_date' => SORT_DESC])
            ->all();

        return $this->render('appointments', [
            'donor'        => $donor,
            'appointments' => $appointments,
        ]);
    }

    public function actionBookAppointment()
    {
        $user  = Yii::$app->user->identity;
        $donor = Donor::findOne(['user_id' => $user->id]);

        $model     = new Appointment();
        $hospitals = \common\models\Hospital::find()
            ->where(['is_verified' => 1])
            ->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->donor_id = $donor->id;
            $model->status   = Appointment::STATUS_PENDING;
            if ($model->save()) {
                // Tuma notification kwa Hospital
                $hospital = \common\models\Hospital::findOne($model->hospital_id);
                if ($hospital) {
                    Notification::createNotification(
                        $hospital->user_id,
                        'New Appointment Booked',
                        $donor->full_name . ' has booked an appointment on ' . $model->appointment_date . ' at ' . $model->appointment_time,
                        'info'
                    );
                }

                Yii::$app->session->setFlash('success', 'Appointment booked successfully!');
                return $this->redirect(['donor/appointments']);
            }
        }

        return $this->render('book-appointment', [
            'model'     => $model,
            'donor'     => $donor,
            'hospitals' => $hospitals,
        ]);
    }

    public function actionCancelAppointment($id)
    {
        $appointment = Appointment::findOne($id);
        if ($appointment) {
            $appointment->delete();
            Yii::$app->session->setFlash('success', 'Appointment cancelled successfully!');
        }
        return $this->redirect(['donor/appointment']);
    }

    // =====================
    // MY DONATIONS
    // =====================
    public function actionDonations()
    {
        $user  = Yii::$app->user->identity;
        $donor = Donor::findOne(['user_id' => $user->id]);

        $donations = Donation::find()
            ->where(['donor_id' => $donor->id])
            ->orderBy(['donated_at' => SORT_DESC])
            ->all();

        $totalUnits = Donation::find()
            ->where([
                'donor_id' => $donor->id,
                'status'   => Donation::STATUS_COMPLETED,
            ])
            ->sum('units') ?? 0;

        return $this->render('donations', [
            'donor'      => $donor,
            'donations'  => $donations,
            'totalUnits' => $totalUnits,
        ]);
    }
    // =====================
    // NOTIFICATIONS
    // =====================
    public function actionNotifications()
    {
        $user = Yii::$app->user->identity;

        $notifications = Notification::find()
            ->where(['user_id' => $user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        // Mark zote kuwa zimesomwa
        Notification::markAllAsRead($user->id);

        return $this->render('notifications', [
            'notifications' => $notifications,
        ]);
    }

    // =====================
    // CHANGE PASSWORD
    // =====================
    public function actionChangePassword()
    {
        $user = Yii::$app->user->identity;

        if (Yii::$app->request->isPost) {
            $post            = Yii::$app->request->post();
            $currentPassword = $post['current_password'] ?? '';
            $newPassword     = $post['new_password'] ?? '';
            $confirmPassword = $post['confirm_password'] ?? '';

            // Angalia current password
            if (!$user->validatePassword($currentPassword)) {
                Yii::$app->session->setFlash('error', 'Current password is incorrect.');
            } elseif (strlen($newPassword) < 6) {
                Yii::$app->session->setFlash('error', 'New password must be at least 6 characters.');
            } elseif ($newPassword !== $confirmPassword) {
                Yii::$app->session->setFlash('error', 'New passwords do not match.');
            } else {
                $user->setPassword($newPassword);
                $user->save(false);
                Yii::$app->session->setFlash('success', 'Password changed successfully!');
                return $this->redirect(['donor/dashboard']);
            }
        }

        return $this->render('change-password');
    }
}