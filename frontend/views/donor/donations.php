<?php

use yii\helpers\Html;

$this->title = 'My Donations';
?>

<div class="container-fluid mt-4">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>🩸 My Donations</h3>
            <p class="text-muted">Your blood donation history</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body text-center">
                    <h1><?= count($donations) ?></h1>
                    <p class="mb-0">Total Donations</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body text-center">
                    <h1><?= $totalUnits ?></h1>
                    <p class="mb-0">Total Units Donated</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-body text-center">
                    <h1><?= $donor->blood_type ?></h1>
                    <p class="mb-0">My Blood Type</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Can Donate Alert -->
    <?php if ($donor->canDonate()): ?>
        <div class="alert alert-success">
            ✅ You are eligible to donate blood!
            <?= Html::a('Book Appointment', ['donor/book-appointment'], [
                'class' => 'btn btn-success btn-sm ms-2'
            ]) ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            ⚠️ You cannot donate yet — please wait 3 months since your last donation.
            <strong>Last Donation: <?= $donor->last_donation ?></strong>
        </div>
    <?php endif; ?>

    <!-- Donations Table -->
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Donation History</h5>
        </div>
        <div class="card-body">
            <?php if (empty($donations)): ?>
                <div class="text-center py-4">
                    <h5 class="text-muted">No donations yet.</h5>
                    <p class="text-muted">Book an appointment to make your first donation!</p>
                    <?= Html::a('Book Appointment', ['donor/book-appointment'], [
                        'class' => 'btn btn-danger'
                    ]) ?>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-danger">
                            <tr>
                                <th>#</th>
                                <th>Hospital</th>
                                <th>Blood Type</th>
                                <th>Units</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($donations as $i => $donation): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $donation->hospital->name ?></td>
                                <td>
                                    <span class="badge bg-danger">
                                        <?= $donation->blood_type ?>
                                    </span>
                                </td>
                                <td><?= $donation->units ?></td>
                                <td><?= $donation->donated_at ?></td>
                                <td>
                                    <span class="badge bg-<?= $donation->status == 'completed' ? 'success' : ($donation->status == 'pending' ? 'warning' : 'danger') ?>">
                                        <?= ucfirst($donation->status) ?>
                                    </span>
                                </td>
                                <td><?= $donation->notes ?? '-' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-3">
        <?= Html::a('← Back to Dashboard', ['donor/dashboard'], [
            'class' => 'btn btn-secondary'
        ]) ?>
    </div>

</div>