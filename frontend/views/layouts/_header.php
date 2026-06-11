<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Html;

// Menu kulingana na role
if (Yii::$app->user->isGuest) {
    // Guest menu
    $items = [
        ['label' => 'Home', 'url' => ['/auth/login']],
        ['label' => 'Register as Donor', 'url' => ['/auth/register']],
        ['label' => 'Register Hospital', 'url' => ['/auth/register-hospital']],
        ['label' => 'Login', 'url' => ['/auth/login']],
    ];
} elseif (Yii::$app->user->identity->isAdmin()) {
    // Admin menu
    $items = [
        ['label' => '🏠 Dashboard', 'url' => ['/admin/dashboard']],
        ['label' => '👤 Donors', 'url' => ['/admin/donors']],
        ['label' => '🏥 Hospitals', 'url' => ['/admin/hospitals']],
        ['label' => '🩸 Blood Requests', 'url' => ['/admin/blood-requests']],
        ['label' => '📦 Blood Stock', 'url' => ['/admin/blood-stock']],
        ['label' => '📊 Reports', 'url' => ['/admin/reports']],
        ['label' => '🔔 Notifications', 'url' => ['/admin/notifications']],
        ['label' => '🔐 Change Password', 'url' => ['/admin/change-password']],
        [
            'label' => 'Logout (' . Html::encode(Yii::$app->user->identity->username) . ')',
            'url'   => ['/auth/logout'],
            'linkOptions' => ['data-method' => 'post'],
        ],
    ];
} elseif (Yii::$app->user->identity->isDonor()) {
    // Donor menu
    $items = [
        ['label' => '🏠 Dashboard', 'url' => ['/donor/dashboard']],
        ['label' => '📅 Appointments', 'url' => ['/donor/appointments']],
        ['label' => '📅 Book Appointment', 'url' => ['/donor/book-appointment']],
        ['label' => '🔔 Notifications', 'url' => ['/donor/notifications']],
        ['label' => '🔐 Change Password', 'url' => ['/donor/change-password']],
        [
            'label' => 'Logout (' . Html::encode(Yii::$app->user->identity->username) . ')',
            'url'   => ['/auth/logout'],
            'linkOptions' => ['data-method' => 'post'],
        ],
    ];
} elseif (Yii::$app->user->identity->isHospital()) {
    // Hospital menu
    $items = [
        ['label' => '🏠 Dashboard', 'url' => ['/hospital/dashboard']],
        ['label' => '🩸 Blood Requests', 'url' => ['/hospital/blood-requests']],
        ['label' => '📦 Blood Stock', 'url' => ['/hospital/blood-stock']],
        ['label' => '📅 Appointments', 'url' => ['/hospital/appointments']],
        ['label' => '🔔 Notifications', 'url' => ['/hospital/notifications']],
        ['label' => '🔐 Change Password', 'url' => ['/hospital/change-password']],
        [
            'label' => 'Logout (' . Html::encode(Yii::$app->user->identity->username) . ')',
            'url'   => ['/auth/logout'],
            'linkOptions' => ['data-method' => 'post'],
        ],
    ];
} else {
    $items = [];
}
?>

<header id="header">
    <?php NavBar::begin([
        'brandLabel' => '🩸 Blood Donation System',
        'brandUrl'   => Yii::$app->user->isGuest ? ['/auth/login'] : ['/auth/dashboard'],
        'options'    => ['class' => 'navbar-expand-md navbar-dark bg-danger fixed-top'],
    ]) ?>
    <?= Nav::widget([
        'options'      => ['class' => 'navbar-nav me-auto'],
        'encodeLabels' => false,
        'items'        => $items,
    ]) ?>
    
    <!-- Language Switcher -->
    <div class="d-flex align-items-center me-2">
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

    <?php NavBar::end() ?>
</header>
