<?php

namespace frontend\controllers;

use Yii;
use common\models\User;
use common\models\Donor;
use common\models\Hospital;
use common\models\Donation;
use common\models\BloodRequest;
use common\models\BloodStock;
use common\models\Appointment;
use yii\web\Controller;
use yii\filters\AccessControl;

class AdminController extends Controller
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
                            return Yii::$app->user->identity->isAdmin();
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
        $totalDonors      = Donor::find()->count();
        $totalHospitals   = Hospital::find()->count();
        $totalDonations   = Donation::find()->count();
        $pendingRequests  = BloodRequest::find()->where(['status' => 'pending'])->count();
        $pendingHospitals = Hospital::find()->where(['is_verified' => 0])->count();

        $recentDonations = Donation::find()
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();

        $recentRequests = BloodRequest::find()
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();

        return $this->render('dashboard', [
            'totalDonors'      => $totalDonors,
            'totalHospitals'   => $totalHospitals,
            'totalDonations'   => $totalDonations,
            'pendingRequests'  => $pendingRequests,
            'pendingHospitals' => $pendingHospitals,
            'recentDonations'  => $recentDonations,
            'recentRequests'   => $recentRequests,
        ]);
    }

    // =====================
    // MANAGE DONORS
    // =====================
    public function actionDonors()
    {
        $donors = Donor::find()->with('user')->all();
        return $this->render('donors', ['donors' => $donors]);
    }

    public function actionDeleteDonor($id)
    {
        $donor = Donor::findOne($id);
        if ($donor) {
            $donor->user->delete();
            $donor->delete();
            Yii::$app->session->setFlash('success', 'Donor deleted successfully.');
        }
        return $this->redirect(['admin/donors']);
    }

    // =====================
    // MANAGE HOSPITALS
    // =====================
    public function actionHospitals()
    {
        $hospitals = Hospital::find()->with('user')->all();
        return $this->render('hospitals', ['hospitals' => $hospitals]);
    }

    public function actionVerifyHospital($id)
    {
        $hospital = Hospital::findOne($id);
        if ($hospital) {
            $hospital->is_verified = 1;
            $hospital->save();
            Yii::$app->session->setFlash('success', 'Hospital verified successfully.');
        }
        return $this->redirect(['admin/hospitals']);
    }

    public function actionDeleteHospital($id)
    {
        $hospital = Hospital::findOne($id);
        if ($hospital) {
            $hospital->user->delete();
            $hospital->delete();
            Yii::$app->session->setFlash('success', 'Hospital deleted successfully.');
        }
        return $this->redirect(['admin/hospitals']);
    }

    // =====================
    // MANAGE BLOOD REQUESTS
    // =====================
    public function actionBloodRequests()
    {
        $requests = BloodRequest::find()->with('hospital')->all();
        return $this->render('blood-requests', ['requests' => $requests]);
    }

    public function actionApproveRequest($id)
    {
        $request = BloodRequest::findOne($id);
        if ($request) {
            $request->status = 'approved';
            $request->save();
            Yii::$app->session->setFlash('success', 'Request approved successfully.');
        }
        return $this->redirect(['admin/blood-requests']);
    }

    public function actionRejectRequest($id)
    {
        $request = BloodRequest::findOne($id);
        if ($request) {
            $request->status = 'cancelled';
            $request->save();
            Yii::$app->session->setFlash('success', 'Request rejected successfully.');
        }
        return $this->redirect(['admin/blood-requests']);
    }

    // =====================
    // MANAGE BLOOD STOCK
    // =====================
    public function actionBloodStock()
    {
        $stocks = BloodStock::find()->with('hospital')->all();
        return $this->render('blood-stock', ['stocks' => $stocks]);
    }

    // =====================
    // REPORTS
    // =====================
    public function actionReports()
    {
        $donationsByBloodType = [];
        foreach (Donor::BLOOD_TYPES as $type) {
            $donationsByBloodType[$type] = Donation::find()
                ->where(['blood_type' => $type])
                ->count();
        }

        $monthlyDonations = Yii::$app->db->createCommand("
            SELECT DATE_FORMAT(FROM_UNIXTIME(created_at), '%Y-%m') as month,
            COUNT(*) as total
            FROM donations
            GROUP BY month
            ORDER BY month DESC
            LIMIT 12
        ")->queryAll();

        return $this->render('reports', [
            'donationsByBloodType' => $donationsByBloodType,
            'monthlyDonations'     => $monthlyDonations,
        ]);
    }
}