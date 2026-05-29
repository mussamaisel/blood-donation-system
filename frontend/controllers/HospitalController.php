<?php

namespace frontend\controllers;

use Yii;
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
            $appointment->status = 'approved';
            $appointment->save();
            Yii::$app->session->setFlash('success', 'Appointment approved successfully!');
        }
        return $this->redirect(['hospital/appointments']);
    }

    public function actionRejectAppointment($id)
    {
        $appointment = Appointment::findOne($id);
        if ($appointment) {
            $appointment->status = 'cancelled';
            $appointment->save();
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
}