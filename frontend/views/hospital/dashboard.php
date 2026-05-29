<?php

use yii\helpers\Html;

$this->title = 'Hospital Dashboard';
?>

<div class="container-fluid mt-4">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>🏥 <?= $hospital->name ?></h3>
            <p class="text-muted">
                <?= $hospital->city ?>, <?= $hospital->region ?>
                <?php if (!$hospital->isVerified()): ?>
                    <span class="badge bg-warning text-dark ms-2">Pending Verification</span>
                <?php else: ?>
                    <span class="badge bg-success ms-2">Verified</span>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body text-center">
                    <h1><?= $totalStock ?></h1>
                    <p class="mb-0">Total Blood Units</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body text-center">
                    <h1><?= $pendingRequests ?></h1>
                    <p class="mb-0">Pending Requests</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body text-center">
                    <h1><?= $approvedRequests ?></h1>
                    <p class="mb-0">Approved Requests</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body text-center">
                    <h1><?= $todayAppointments ?></h1>
                    <p class="mb-0">Today's Appointments</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Blood Stock by Type -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Blood Stock by Type</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($stockByType as $type => $units): ?>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card <?= $units > 0 ? 'border-danger' : 'border-secondary' ?>">
                                <div class="card-body text-center py-2">
                                    <h4 class="<?= $units > 0 ? 'text-danger' : 'text-secondary' ?>">
                                        <?= $type ?>
                                    </h4>
                                    <p class="mb-0"><?= $units ?> Units</p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <?= Html::a('🩸 Request Blood', ['hospital/create-request'], [
                        'class' => 'btn btn-danger me-2'
                    ]) ?>
                    <?= Html::a('📦 View Blood Stock', ['hospital/blood-stock'], [
                        'class' => 'btn btn-success me-2'
                    ]) ?>
                    <?= Html::a('📋 View Requests', ['hospital/blood-requests'], [
                        'class' => 'btn btn-warning me-2'
                    ]) ?>
                    <?= Html::a('📅 Appointments', ['hospital/appointments'], [
                        'class' => 'btn btn-info'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

</div>