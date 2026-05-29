<?php

use yii\helpers\Html;

$this->title = 'Blood Requests';
?>

<div class="container-fluid mt-4">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>🩸 Blood Requests</h3>
            <p class="text-muted">All blood requests from hospitals</p>
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
            <h5 class="mb-0">Blood Requests List</h5>
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
                                <th>Hospital</th>
                                <th>Blood Type</th>
                                <th>Units Needed</th>
                                <th>Units Fulfilled</th>
                                <th>Priority</th>
                                <th>Needed By</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $i => $request): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $request->hospital->name ?></td>
                                <td>
                                    <span class="badge bg-danger">
                                        <?= $request->blood_type ?>
                                    </span>
                                </td>
                                <td><?= $request->units_needed ?></td>
                                <td><?= $request->units_fulfilled ?></td>
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
                                <td>
                                    <?php if ($request->status == 'pending'): ?>
                                        <?= Html::a('Approve', ['admin/approve-request', 'id' => $request->id], [
                                            'class' => 'btn btn-sm btn-success me-1',
                                            'data-confirm' => 'Are you sure you want to approve this request?',
                                            'data-method'  => 'post',
                                        ]) ?>
                                        <?= Html::a('Reject', ['admin/reject-request', 'id' => $request->id], [
                                            'class' => 'btn btn-sm btn-danger',
                                            'data-confirm' => 'Are you sure you want to reject this request?',
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
        <?= Html::a('← Back to Dashboard', ['admin/dashboard'], ['class' => 'btn btn-secondary']) ?>
    </div>

</div>