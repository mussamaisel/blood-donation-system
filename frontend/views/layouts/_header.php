<?php

declare(strict_types=1);

use yii\helpers\Html;

if (Yii::$app->user->isGuest) {
    $items = [
        ['label' => '🏠 Home', 'url' => ['/auth/login']],
        ['label' => '👤 Register as Donor', 'url' => ['/auth/register']],
        ['label' => '🏥 Register Hospital', 'url' => ['/auth/register-hospital']],
        ['label' => '🔑 Login', 'url' => ['/auth/login']],
    ];
} elseif (Yii::$app->user->identity->isAdmin()) {
    $items = [
        ['label' => '🏠 Dashboard', 'url' => ['/admin/dashboard']],
        ['label' => '👤 Donors', 'url' => ['/admin/donors']],
        ['label' => '🏥 Hospitals', 'url' => ['/admin/hospitals']],
        ['label' => '🩸 Blood Requests', 'url' => ['/admin/blood-requests']],
        ['label' => '📦 Blood Stock', 'url' => ['/admin/blood-stock']],
        ['label' => '📊 Reports', 'url' => ['/admin/reports']],
        ['label' => '🔔 Notifications', 'url' => ['/admin/notifications']],
        ['label' => '🔐 Change Password', 'url' => ['/admin/change-password']],
        ['label' => '🚪 Logout (' . Html::encode(Yii::$app->user->identity->username) . ')', 'url' => ['/auth/logout'], 'linkOptions' => ['data-method' => 'post']],
    ];
} elseif (Yii::$app->user->identity->isDonor()) {
    $items = [
        ['label' => '🏠 Dashboard', 'url' => ['/donor/dashboard']],
        ['label' => '📅 Appointments', 'url' => ['/donor/appointments']],
        ['label' => '📅 Book Appointment', 'url' => ['/donor/book-appointment']],
        ['label' => '🩸 My Donations', 'url' => ['/donor/donations']],
        ['label' => '🔔 Notifications', 'url' => ['/donor/notifications']],
        ['label' => '🔐 Change Password', 'url' => ['/donor/change-password']],
        ['label' => '🚪 Logout (' . Html::encode(Yii::$app->user->identity->username) . ')', 'url' => ['/auth/logout'], 'linkOptions' => ['data-method' => 'post']],
    ];
} elseif (Yii::$app->user->identity->isHospital()) {
    $items = [
        ['label' => '🏠 Dashboard', 'url' => ['/hospital/dashboard']],
        ['label' => '🩸 Blood Requests', 'url' => ['/hospital/blood-requests']],
        ['label' => '📦 Blood Stock', 'url' => ['/hospital/blood-stock']],
        ['label' => '📅 Appointments', 'url' => ['/hospital/appointments']],
        ['label' => '🔔 Notifications', 'url' => ['/hospital/notifications']],
        ['label' => '🔐 Change Password', 'url' => ['/hospital/change-password']],
        ['label' => '🚪 Logout (' . Html::encode(Yii::$app->user->identity->username) . ')', 'url' => ['/auth/logout'], 'linkOptions' => ['data-method' => 'post']],
    ];
} else {
    $items = [];
}
?>

<!-- Navbar -->
<header id="header">
<nav class="navbar navbar-dark bg-danger fixed-top px-3">
    <div class="d-flex align-items-center">
        <!-- Hamburger Button -->
        <button class="btn btn-outline-light me-3" id="sidebarToggle">☰</button>
        <!-- Brand -->
        <?= Html::a('🩸 Blood Donation System', ['/auth/dashboard'], [
            'class' => 'navbar-brand mb-0 h1'
        ]) ?>
    </div>

    <!-- Language Switcher -->
    <div>
        <?php if (Yii::$app->language == 'en'): ?>
            <?= Html::a('🇹🇿 Swahili', ['site/language', 'lang' => 'sw'], [
                'class' => 'btn btn-outline-light btn-sm'
            ]) ?>
        <?php else: ?>
            <?= Html::a('🇬🇧 English', ['site/language', 'lang' => 'en'], [
                'class' => 'btn btn-outline-light btn-sm'
            ]) ?>
        <?php endif; ?>
    </div>
</nav>
</header>

<!-- Sidebar -->
<div id="sidebar" style="
    position: fixed;
    top: 56px;
    left: -250px;
    width: 250px;
    height: 100%;
    background: #1a1a2e;
    z-index: 1000;
    transition: left 0.3s ease;
    overflow-y: auto;
    padding-top: 10px;
">
    <ul class="nav flex-column px-2">
        <?php foreach ($items as $item): ?>
        <li class="nav-item">
            <?= Html::a(
                $item['label'],
                $item['url'],
                array_merge(
                    ['class' => 'nav-link text-white py-2'],
                    $item['linkOptions'] ?? []
                )
            ) ?>
        </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Overlay -->
<div id="sidebarOverlay" style="
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 999;
"></div>

<script>
document.getElementById('sidebarToggle').addEventListener('click', function() {
    var sidebar  = document.getElementById('sidebar');
    var overlay  = document.getElementById('sidebarOverlay');
    var isOpen   = sidebar.style.left === '0px';

    sidebar.style.left  = isOpen ? '-250px' : '0px';
    overlay.style.display = isOpen ? 'none' : 'block';
});

document.getElementById('sidebarOverlay').addEventListener('click', function() {
    document.getElementById('sidebar').style.left = '-250px';
    this.style.display = 'none';
});
</script>