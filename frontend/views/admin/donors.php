<?php

use yii\helpers\Html;

$this->title = 'Manage Donors';
?>

<div class="container-fluid mt-4">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>👤 Manage Donors</h3>
            <p class="text-muted">All registered donors in the system</p>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <!-- Donors Table -->
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Donors List</h5>
        </div>
        <div class="card-body">
            <?php if (empty($donors)): ?>
                <p class="text-muted">No donors registered yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-danger">
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Blood Type</th>
                                <th>Gender</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>Weight</th>
                                <th>Available</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($donors as $i => $donor): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $donor->full_name ?></td>
                                <td><?= $donor->user->username ?></td>
                                <td><?= $donor->user->email ?></td>
                                <td>
                                    <span class="badge bg-danger">
                                        <?= $donor->blood_type ?>
                                    </span>
                                </td>
                                <td><?= ucfirst($donor->gender) ?></td>
                                <td><?= $donor->phone ?></td>
                                <td><?= $donor->city ?></td>
                                <td><?= $donor->weight ?> kg</td>
                                <td>
                                    <?php if ($donor->is_available): ?>
                                        <span class="badge bg-success">Available</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Not Available</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= Html::a('Delete', ['admin/delete-donor', 'id' => $donor->id], [
                                        'class' => 'btn btn-sm btn-danger',
                                        'data-confirm' => 'Are you sure you want to delete this donor?',
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