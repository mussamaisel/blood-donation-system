<?php

use yii\helpers\Html;

$this->title = 'Manage Hospitals';
?>

<div class="container-fluid mt-4">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>🏥 Manage Hospitals</h3>
            <p class="text-muted">All registered hospitals in the system</p>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <!-- Hospitals Table -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Hospitals List</h5>
        </div>
        <div class="card-body">
            <?php if (empty($hospitals)): ?>
                <p class="text-muted">No hospitals registered yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Hospital Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>Region</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hospitals as $i => $hospital): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $hospital->name ?></td>
                                <td><?= $hospital->email ?></td>
                                <td><?= $hospital->phone ?></td>
                                <td><?= $hospital->city ?></td>
                                <td><?= $hospital->region ?></td>
                                <td>
                                    <?php if ($hospital->is_verified): ?>
                                        <span class="badge bg-success">Verified</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!$hospital->is_verified): ?>
                                        <?= Html::a('Verify', ['admin/verify-hospital', 'id' => $hospital->id], [
                                            'class' => 'btn btn-sm btn-success me-1',
                                            'data-confirm' => 'Are you sure you want to verify this hospital?',
                                            'data-method'  => 'post',
                                        ]) ?>
                                    <?php endif; ?>
                                    <?= Html::a('Delete', ['admin/delete-hospital', 'id' => $hospital->id], [
                                        'class' => 'btn btn-sm btn-danger',
                                        'data-confirm' => 'Are you sure you want to delete this hospital?',
                                        'data-method'  => 'post',
                                    ]) ?>
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