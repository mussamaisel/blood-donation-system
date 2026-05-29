<?php

use yii\helpers\Html;

$this->title = 'Blood Stock';
?>

<div class="container-fluid mt-4">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>📦 Blood Stock</h3>
            <p class="text-muted">Blood stock available across all hospitals</p>
        </div>
    </div>

    <!-- Blood Type Summary Cards -->
    <div class="row mb-4">
        <?php
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        foreach ($bloodTypes as $type):
            $total = 0;
            foreach ($stocks as $stock) {
                if ($stock->blood_type === $type) {
                    $total += $stock->units;
                }
            }
        ?>
        <div class="col-md-3 col-sm-6">
            <div class="card text-white mb-3 <?= $total > 0 ? 'bg-danger' : 'bg-secondary' ?>">
                <div class="card-body text-center py-2">
                    <h4 class="mb-0"><?= $type ?></h4>
                    <p class="mb-0"><?= $total ?> Units</p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Stock Table -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Detailed Blood Stock</h5>
        </div>
        <div class="card-body">
            <?php if (empty($stocks)): ?>
                <p class="text-muted">No blood stock available yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-success">
                            <tr>
                                <th>#</th>
                                <th>Hospital</th>
                                <th>Blood Type</th>
                                <th>Units</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stocks as $i => $stock): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $stock->hospital->name ?></td>
                                <td>
                                    <span class="badge bg-danger">
                                        <?= $stock->blood_type ?>
                                    </span>
                                </td>
                                <td><?= $stock->units ?></td>
                                <td><?= $stock->expiry_date ?></td>
                                <td>
                                    <span class="badge bg-<?= $stock->status == 'available' ? 'success' : ($stock->status == 'expired' ? 'danger' : 'secondary') ?>">
                                        <?= ucfirst($stock->status) ?>
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
        <?= Html::a('← Back to Dashboard', ['admin/dashboard'], ['class' => 'btn btn-secondary']) ?>
    </div>

</div>