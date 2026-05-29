<?php

use yii\helpers\Html;

$this->title = 'My Appointments';
?>

<div class="container-fluid mt-4">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h3>📅 My Appointments</h3>
            <p class="text-muted">All your donation appointments</p>
        </div>
        <div class="col-md-4 text-end">
            <?= Html::a('+ Book Appointment', ['donor/book-appointment'], [
                'class' => 'btn btn-danger'
            ]) ?>
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
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Appointments List</h5>
        </div>
        <div class="card-body">
            <?php if (empty($appointments)): ?>
                <div class="text-center py-4">
                    <h5 class="text-muted">No appointments yet.</h5>
                    <p class="text-muted">Book your first appointment to donate blood!</p>
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
                                <td><?= $appointment->hospital->name ?></td>
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
                                        <?= Html::a('Cancel', ['donor/cancel-appointment', 'id' => $appointment->id], [
                                            'class'        => 'btn btn-sm btn-danger',
                                            'data-confirm' => 'Are you sure you want to cancel this appointment?',
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
        <?= Html::a('← Back to Dashboard', ['donor/dashboard'], [
            'class' => 'btn btn-secondary'
        ]) ?>
    </div>

</div>