<?php

use yii\helpers\Html;

$this->title = 'Appointments';
?>

<div class="container-fluid mt-4">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>📅 Appointments</h3>
            <p class="text-muted">All donor appointments at <?= $hospital->name ?></p>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <!-- Appointments Table -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Appointments List</h5>
        </div>
        <div class="card-body">
            <?php if (empty($appointments)): ?>
                <p class="text-muted">No appointments yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-info">
                            <tr>
                                <th>#</th>
                                <th>Donor Name</th>
                                <th>Blood Type</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $i => $appointment): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $appointment->donor->full_name ?></td>
                                <td>
                                    <span class="badge bg-danger">
                                        <?= $appointment->donor->blood_type ?>
                                    </span>
                                </td>
                                <td><?= $appointment->appointment_date ?></td>
                                <td><?= $appointment->appointment_time ?></td>
                                <td>
                                    <span class="badge bg-<?= $appointment->status == 'pending' ? 'warning' : ($appointment->status == 'approved' ? 'success' : ($appointment->status == 'completed' ? 'info' : 'danger')) ?>">
                                        <?= ucfirst($appointment->status) ?>
                                    </span>
                                </td>
                                <td><?= $appointment->notes ?? '-' ?></td>
                                <td>
                                    <?php if ($appointment->status == 'pending'): ?>
                                        <?= Html::a('Approve', ['hospital/approve-appointment', 'id' => $appointment->id], [
                                            'class' => 'btn btn-sm btn-success me-1',
                                            'data-confirm' => 'Are you sure you want to approve this appointment?',
                                            'data-method'  => 'post',
                                        ]) ?>
                                        <?= Html::a('Reject', ['hospital/reject-appointment', 'id' => $appointment->id], [
                                            'class' => 'btn btn-sm btn-danger',
                                            'data-confirm' => 'Are you sure you want to reject this appointment?',
                                            'data-method'  => 'post',
                                        ]) ?>
                                    <?php else: ?>
                                        <span class="text-muted">No actions</span>
                                    <?php endif; ?>
                                </td>
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
        <?= Html::a('← Back to Dashboard', ['hospital/dashboard'], [
            'class' => 'btn btn-secondary'
        ]) ?>
    </div>

</div>