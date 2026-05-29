<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Dashboard');
?>

<div class="container mt-4">

    <div class="row mb-4">
        <div class="col-md-12">
            <h3><?= Yii::t('app', 'Welcome') ?>, <?= $donor->full_name ?>! 🩸</h3>
            <p class="text-muted"><?= Yii::t('app', 'Blood Type') ?>: <strong><?= $donor->blood_type ?></strong></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body text-center">
                    <h1><?= $totalDonations ?></h1>
                    <p class="mb-0"><?= Yii::t('app', 'Total Donations') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body text-center">
                    <h1><?= $upcomingAppointments ?></h1>
                    <p class="mb-0"><?= Yii::t('app', 'Upcoming Appointments') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-body text-center">
                    <h1><?= $unreadNotifications ?></h1>
                    <p class="mb-0"><?= Yii::t('app', 'Notifications') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><?= Yii::t('app', 'Quick Actions') ?></h5>
                </div>
                <div class="card-body">
                    <?= Html::a('📅 ' . Yii::t('app', 'Book Appointment'), ['donor/book-appointment'], [
                        'class' => 'btn btn-danger me-2'
                    ]) ?>
                    <?= Html::a('📋 ' . Yii::t('app', 'My Appointments'), ['donor/appointments'], [
                        'class' => 'btn btn-warning me-2'
                    ]) ?>    
                    <?= Html::a('🩸 My Donations', ['donor/donations'], [
                        'class' => 'btn btn-info me-2'
                    ]) ?>
                    <?= Html::a('🔔 Notifications', ['donor/notifications'], [
                        'class' => 'btn btn-secondary'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- My Profile -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><?= Yii::t('app', 'My Profile') ?></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><?= Yii::t('app', 'Full Name') ?>:</strong> <?= $donor->full_name ?></p>
                            <p><strong><?= Yii::t('app', 'Blood Type') ?>:</strong> <?= $donor->blood_type ?></p>
                            <p><strong><?= Yii::t('app', 'Gender') ?>:</strong> <?= $donor->gender ?></p>
                            <p><strong><?= Yii::t('app', 'Phone') ?>:</strong> <?= $donor->phone ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><?= Yii::t('app', 'City') ?>:</strong> <?= $donor->city ?></p>
                            <p><strong><?= Yii::t('app', 'Address') ?>:</strong> <?= $donor->address ?></p>
                            <p><strong><?= Yii::t('app', 'Weight') ?>:</strong> <?= $donor->weight ?> kg</p>
                            <p><strong><?= Yii::t('app', 'Last Donation') ?>:</strong>
                                <?= $donor->last_donation ?? Yii::t('app', 'No donations yet') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>