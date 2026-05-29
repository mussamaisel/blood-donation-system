<?php
$this->title = 'Admin Dashboard';
?>

<div class="container-fluid mt-4">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>⚙️ Admin Dashboard</h3>
            <p class="text-muted">Blood Donation Management System</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body text-center">
                    <h1><?= $totalDonors ?></h1>
                    <p class="mb-0">Total Donors</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body text-center">
                    <h1><?= $totalHospitals ?></h1>
                    <p class="mb-0">Total Hospitals</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body text-center">
                    <h1><?= $totalDonations ?></h1>
                    <p class="mb-0">Total Donations</p>
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
    </div>

    <!-- Pending Hospitals Alert -->
    <?php if ($pendingHospitals > 0): ?>
    <div class="alert alert-warning">
        ⚠️ There are <strong><?= $pendingHospitals ?></strong> hospital(s) waiting for verification!
        <a href="/blood-donation/frontend/web/admin/hospitals" class="alert-link">Verify Now</a>
    </div>
    <?php endif; ?>

    <!-- Quick Links -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="/blood-donation/frontend/web/admin/donors" class="btn btn-danger me-2">
                        👤 Manage Donors
                    </a>
                    <a href="/blood-donation/frontend/web/admin/hospitals" class="btn btn-primary me-2">
                        🏥 Manage Hospitals
                    </a>
                    <a href="/blood-donation/frontend/web/admin/blood-requests" class="btn btn-warning me-2">
                        🩸 Blood Requests
                    </a>
                    <a href="/blood-donation/frontend/web/admin/blood-stock" class="btn btn-success me-2">
                        📦 Blood Stock
                    </a>
                    <a href="/blood-donation/frontend/web/admin/reports" class="btn btn-info">
                        📊 Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Donations -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Recent Donations</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recentDonations)): ?>
                        <p class="text-muted">No donations yet.</p>
                    <?php else: ?>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Donor</th>
                                    <th>Blood Type</th>
                                    <th>Units</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentDonations as $donation): ?>
                                <tr>
                                    <td><?= $donation->donor->full_name ?></td>
                                    <td><?= $donation->blood_type ?></td>
                                    <td><?= $donation->units ?></td>
                                    <td><?= $donation->donated_at ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Blood Requests -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Recent Blood Requests</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recentRequests)): ?>
                        <p class="text-muted">No blood requests yet.</p>
                    <?php else: ?>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Hospital</th>
                                    <th>Blood Type</th>
                                    <th>Units</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentRequests as $request): ?>
                                <tr>
                                    <td><?= $request->hospital->name ?></td>
                                    <td><?= $request->blood_type ?></td>
                                    <td><?= $request->units_needed ?></td>
                                    <td>
                                        <span class="badge bg-<?= $request->status == 'pending' ? 'warning' : ($request->status == 'approved' ? 'success' : 'danger') ?>">
                                            <?= ucfirst($request->status) ?>
                                        </span>
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

</div>