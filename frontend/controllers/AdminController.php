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
use common\models\Notification;
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
        $unreadNotifications = Notification::find()
            ->where(['user_id' => Yii::$app->user->id, 'is_read' => 0])
            ->count();

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
            'unreadNotifications' => $unreadNotifications,
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

             // Tuma notification kwa Hospital
            Notification::createNotification(
                $hospital->user_id,
                'Account Verified',
                'Your hospital account has been verified! You can now use all features.',
                'success'
            );

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

            // Tuma notification kwa Hospital
            Notification::createNotification(
                $request->hospital->user_id,
                'Blood Request Approved',
                'Your blood request for ' . $request->units_needed . ' units of ' . $request->blood_type . ' has been approved!',
                'success'
            );

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

            // Tuma notification kwa Hospital
            Notification::createNotification(
                $request->hospital->user_id,
                'Blood Request Rejected',
                'Your blood request for ' . $request->units_needed . ' units of ' . $request->blood_type . ' has been rejected.',
                'danger'
            );

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
                return $this->redirect(['admin/dashboard']);
            }
        }

        return $this->render('change-password');
    }
}