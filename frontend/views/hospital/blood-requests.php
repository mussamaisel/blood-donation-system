<?php

use yii\helpers\Html;

$this->title = 'Blood Requests';
?>

<div class="container-fluid mt-4">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h3>🩸 Blood Requests</h3>
            <p class="text-muted">All blood requests from <?= $hospital->name ?></p>
        </div>
        <div class="col-md-4 text-end">
            <?= Html::a('+ New Request', ['hospital/create-request'], [
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

    <!-- Requests Table -->
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Requests List</h5>
        </div>
        <div class="card-body">
            <?php if (empty($requests)): ?>
                <p class="text-muted">No blood requests yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-warning">
                            <tr>
                                <th>#</th>
                                <th>Blood Type</th>
                                <th>Units Needed</th>
                                <th>Units Fulfilled</th>
                                <th>Progress</th>
                                <th>Priority</th>
                                <th>Needed By</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $i => $request): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <span class="badge bg-danger">
                                        <?= $request->blood_type ?>
                                    </span>
                                </td>
                                <td><?= $request->units_needed ?></td>
                                <td><?= $request->units_fulfilled ?></td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger"
                                            style="width: <?= $request->getProgressPercentage() ?>%">
                                            <?= $request->getProgressPercentage() ?>%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $request->priority == 'urgent' ? 'danger' : ($request->priority == 'high' ? 'warning' : 'secondary') ?>">
                                        <?= ucfirst($request->priority) ?>
                                    </span>
                                </td>
                                <td><?= $request->needed_by ?></td>
                                <td>
                                    <span class="badge bg-<?= $request->status == 'pending' ? 'warning' : ($request->status == 'approved' ? 'success' : ($request->status == 'fulfilled' ? 'info' : 'danger')) ?>">
                                        <?= ucfirst($request->status) ?>
                                    </span>
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