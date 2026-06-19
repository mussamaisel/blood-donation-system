<?php

namespace frontend\controllers;

use Yii;
use common\models\User;
use common\models\Hospital;
use common\models\BloodStock;
use common\models\BloodRequest;
use common\models\Appointment;
use common\models\Notification;
use common\models\Donor;
use yii\web\Controller;
use yii\filters\AccessControl;

class HospitalController extends Controller
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
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isHospital();
                        },
                    ],
                ],
            ],
        ];
    }

    // =====================
    // DASHBOARD
    // =====================
    public function actionDashboard()
    {
        $user     = Yii::$app->user->identity;
        $hospital = Hospital::findOne(['user_id' => $user->id]);
        $unreadNotifications = Notification::find()
            ->where(['user_id' => $user->id, 'is_read' => 0])
            ->count();

        if (!$hospital) {
            return $this->redirect(['auth/login']);
        }

        $totalStock = BloodStock::find()
            ->where(['hospital_id' => $hospital->id])
            ->sum('units') ?? 0;

        $pendingRequests = BloodRequest::find()
            ->where(['hospital_id' => $hospital->id, 'status' => 'pending'])
            ->count();

        $approvedRequests = BloodRequest::find()
            ->where(['hospital_id' => $hospital->id, 'status' => 'approved'])
            ->count();

        $todayAppointments = Appointment::find()
            ->where([
                'hospital_id'      => $hospital->id,
                'appointment_date' => date('Y-m-d'),
                'status'           => 'approved',
            ])
            ->count();

        $stockByType = [];
        foreach (\common\models\Donor::BLOOD_TYPES as $type) {
            $stockByType[$type] = BloodStock::find()
                ->where([
                    'hospital_id' => $hospital->id,
                    'blood_type'  => $type,
                    'status'      => 'available',
                ])
                ->sum('units') ?? 0;
        }

        return $this->render('dashboard', [
            'hospital'         => $hospital,
            'totalStock'       => $totalStock,
            'pendingRequests'  => $pendingRequests,
            'approvedRequests' => $approvedRequests,
            'todayAppointments'=> $todayAppointments,
            'stockByType'      => $stockByType,
            'unreadNotifications' => $unreadNotifications,
        ]);
    }

    // =====================
    // BLOOD REQUESTS
    // =====================
    public function actionBloodRequests()
    {
        $user     = Yii::$app->user->identity;
        $hospital = Hospital::findOne(['user_id' => $user->id]);

        $requests = BloodRequest::find()
            ->where(['hospital_id' => $hospital->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('blood-requests', [
            'hospital' => $hospital,
            'requests' => $requests,
        ]);
    }

    public function actionCreateRequest()
    {
        $user     = Yii::$app->user->identity;
        $hospital = Hospital::findOne(['user_id' => $user->id]);

        $model              = new BloodRequest();
        $model->hospital_id = $hospital->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Tuma notification kwa Admin
            $admin = \common\models\User::findOne(['role' => 'admin']);
            if ($admin) {
                Notification::createNotification(
                    $admin->id,
                    'New Blood Request',
                    $hospital->name . ' has requested ' . $model->units_needed . ' units of ' . $model->blood_type . ' blood. Priority: ' . ucfirst($model->priority),
                    'warning'
                );
            }

            Yii::$app->session->setFlash('success', 'Blood request submitted successfully!');
            return $this->redirect(['hospital/blood-requests']);
        }

        return $this->render('create-request', [
            'model'    => $model,
            'hospital' => $hospital,
        ]);
    }

    // =====================
    // BLOOD STOCK
    // =====================
    public function actionBloodStock()
    {
        $user     = Yii::$app->user->identity;
        $hospital = Hospital::findOne(['user_id' => $user->id]);

        $stocks = BloodStock::find()
            ->where(['hospital_id' => $hospital->id])
            ->orderBy(['blood_type' => SORT_ASC])
            ->all();

        return $this->render('blood-stock', [
            'hospital' => $hospital,
            'stocks'   => $stocks,
        ]);
    }

    public function actionAddStock()
    {
        $user     = Yii::$app->user->identity;
        $hospital = Hospital::findOne(['user_id' => $user->id]);

        $model              = new BloodStock();
        $model->hospital_id = $hospital->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Blood stock added successfully!');
            return $this->redirect(['hospital/blood-stock']);
        }

        return $this->render('add-stock', [
            'model'    => $model,
            'hospital' => $hospital,
        ]);
    }

    // =====================
    // APPOINTMENTS
    // =====================
    public function actionAppointments()
    {
        $user     = Yii::$app->user->identity;
        $hospital = Hospital::findOne(['user_id' => $user->id]);

        $appointments = Appointment::find()
            ->where(['hospital_id' => $hospital->id])
            ->orderBy(['appointment_date' => SORT_DESC])
            ->all();

        return $this->render('appointments', [
            'hospital'     => $hospital,
            'appointments' => $appointments,
        ]);
    }

    public function actionApproveAppointment($id)
    {
        $appointment = Appointment::findOne($id);
        if ($appointment) {
            $appointment->status =  Appointment::STATUS_APPROVED;
            if (!$appointment->save(false)) {
                Yii::$app->session->setFlash('error', 'Failed to approve: ' . json_encode($appointment->errors));
                return $this->redirect(['hospital/appointments']);
            }
            // Tuma notification kwa Donor
            Notification::createNotification(
                $appointment->donor->user_id,
                'Appointment Approved',
                'Your appointment at ' . $appointment->hospital->name . ' on ' . $appointment->appointment_date . ' at ' . $appointment->appointment_time . ' has been approved!',
                'success'
            );

            Yii::$app->session->setFlash('success', 'Appointment approved successfully!');
        }
        return $this->redirect(['hospital/appointments']);
    }

    public function actionRejectAppointment($id)
    {
        $appointment = Appointment::findOne($id);
        if ($appointment) {

            // Tuma notification kwa Donor
            Notification::createNotification(
                $appointment->donor->user_id,
                'Appointment Cancelled',
                'Your appointment at ' . $appointment->hospital->name . ' on ' . $appointment->appointment_date . ' has been cancelled.',
                'danger'
            );

            // Futa appointment kabisa
            $appointment->delete();

            Yii::$app->session->setFlash('success', 'Appointment rejected successfully!');
        }
        return $this->redirect(['hospital/appointments']);
    }

    // =====================
    // PROFILE
    // =====================
    public function actionProfile()
    {
        $user     = Yii::$app->user->identity;
        $hospital = Hospital::findOne(['user_id' => $user->id]);

        if ($hospital->load(Yii::$app->request->post()) && $hospital->save()) {
            Yii::$app->session->setFlash('success', 'Profile updated successfully!');
            return $this->refresh();
        }

        return $this->render('profile', [
            'hospital' => $hospital,
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
                return $this->redirect(['hospital/dashboard']);
            }
        }

        return $this->render('change-password');
    }

    // =====================
    // MARK AS DONATED
    // =====================
    public function actionMarkAsDonated($id)
    {
        $appointment = Appointment::findOne($id);

        if (!$appointment) {
            Yii::$app->session->setFlash('error', 'Appointment not found.');
            return $this->redirect(['hospital/appointments']);
        }

        // Angalia kama appointment imeshakuwa completed
        if ($appointment->status === Appointment::STATUS_COMPLETED) {
            Yii::$app->session->setFlash('error', 'This appointment has already been marked as donated!');
            return $this->redirect(['hospital/appointments']);
        }

        // Angalia kama appointment imeidhinishwa kwanza
        if ($appointment->status !== Appointment::STATUS_APPROVED) {
            Yii::$app->session->setFlash('error', 'This appointment must be approved before marking as donated!');
            return $this->redirect(['hospital/appointments']);
        }

        // Angalia kama donation ipo tayari kwa appointment hii
        $existingDonation = \common\models\Donation::findOne([
            'donor_id'    => $appointment->donor_id,
            'donated_at'  => date('Y-m-d'),
            'hospital_id' => $appointment->hospital_id,
        ]);

        if ($existingDonation) {
            Yii::$app->session->setFlash('error', 'A donation has already been recorded for this donor today!');
            return $this->redirect(['hospital/appointments']);
        }
   
        $user     = Yii::$app->user->identity;
        $hospital = Hospital::findOne(['user_id' => $user->id]);

        // Tengeneza donation record
        $donation              = new \common\models\Donation();
        $donation->donor_id    = $appointment->donor_id;
        $donation->hospital_id = $hospital->id;
        $donation->blood_type  = $appointment->donor->blood_type;
        $donation->units       = 1;
        $donation->status      = \common\models\Donation::STATUS_COMPLETED;
        $donation->donated_at  = date('Y-m-d');
        $donation->notes       = $appointment->notes;

        if ($donation->save()) {
            // Badilisha status ya appointment
            $appointment->status = Appointment::STATUS_COMPLETED;
            $appointment->save();
            // Ongeza units_fulfilled kwenye blood request inayohusiana
            $bloodRequest = \common\models\BloodRequest::findOne([
                'hospital_id' => $hospital->id,
                'blood_type'  => $appointment->donor->blood_type,
                'status'      => 'approved',
            ]);

            if ($bloodRequest) {
                $bloodRequest->units_fulfilled += 1;
                // Angalia kama imekamilika
                if ($bloodRequest->units_fulfilled >= $bloodRequest->units_needed) {
                    $bloodRequest->status = \common\models\BloodRequest::STATUS_FULFILLED;
                }
                $bloodRequest->save();
            }

            // Badilisha last_donation ya donor
            $donor = $appointment->donor;
            $donor->last_donation = date('Y-m-d');
            $donor->save(false);

            // Ongeza damu kwenye blood stock
            $stock = \common\models\BloodStock::findOne([
                'hospital_id' => $hospital->id,
                'blood_type'  => $appointment->donor->blood_type,
                'status'      => 'available',
            ]);

            if ($stock) {
                $stock->units += 1;
                $stock->save();
            } else {
                // Tengeneza stock mpya
                $newStock              = new \common\models\BloodStock();
                $newStock->hospital_id = $hospital->id;
                $newStock->blood_type  = $appointment->donor->blood_type;
                $newStock->units       = 1;
                $newStock->expiry_date = date('Y-m-d', strtotime('+42 days'));
                $newStock->status      = 'available';
                $newStock->save();
            }

            // Tuma notification kwa Donor
            Notification::createNotification(
                $appointment->donor->user_id,
                'Donation Completed',
                'Thank you! Your blood donation at ' . $hospital->name . ' on ' . date('d M Y') . ' has been recorded. You have saved lives!',
                'success'
            );

            Yii::$app->session->setFlash('success', 'Donation recorded successfully! Blood stock updated.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to record donation.');
        }

        return $this->redirect(['hospital/appointments']);
    }
}