<?php

use yii\helpers\Html;

$this->title = 'Reports';
?>

<div class="container-fluid mt-4">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>📊 Reports</h3>
            <p class="text-muted">Blood donation statistics and reports</p>
        </div>
    </div>

    <!-- Donations by Blood Type -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Donations by Blood Type</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="table-danger">
                            <tr>
                                <th>Blood Type</th>
                                <th>Total Donations</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $maxDonations = max(array_values($donationsByBloodType)) ?: 1;
                            foreach ($donationsByBloodType as $type => $count):
                            ?>
                            <tr>
                                <td>
                                    <span class="badge bg-danger"><?= $type ?></span>
                                </td>
                                <td><?= $count ?></td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger"
                                            style="width: <?= ($count / $maxDonations) * 100 ?>%">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Monthly Donations -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Monthly Donations</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($monthlyDonations)): ?>
                        <p class="text-muted">No donation data available yet.</p>
                    <?php else: ?>
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Month</th>
                                    <th>Total Donations</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $maxMonthly = max(array_column($monthlyDonations, 'total')) ?: 1;
                                foreach ($monthlyDonations as $row):
                                ?>
                                <tr>
                                    <td><?= $row['month'] ?></td>
                                    <td><?= $row['total'] ?></td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-primary"
                                                style="width: <?= ($row['total'] / $maxMonthly) * 100 ?>%">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Summary Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h4 class="text-danger">
                                <?= array_sum(array_values($donationsByBloodType)) ?>
                            </h4>
                            <p class="text-muted">Total Donations</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-primary">
                                <?= count(array_filter($donationsByBloodType)) ?>
                            </h4>
                            <p class="text-muted">Blood Types Active</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-success">
                                <?= count($monthlyDonations) ?>
                            </h4>
                            <p class="text-muted">Active Months</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-warning">
                                <?= !empty($monthlyDonations) ? max(array_column($monthlyDonations, 'total')) : 0 ?>
                            </h4>
                            <p class="text-muted">Best Month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-3">
        <?= Html::a('← Back to Dashboard', ['admin/dashboard'], ['class' => 'btn btn-secondary']) ?>
    </div>

</div>