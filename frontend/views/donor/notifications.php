<?php

use yii\helpers\Html;

$this->title = 'Notifications';
?>

<div class="container-fluid mt-4">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h3>🔔 Notifications</h3>
            <p class="text-muted">All your notifications</p>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <!-- Notifications List -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">All Notifications</h5>
        </div>
        <div class="card-body">
            <?php if (empty($notifications)): ?>
                <div class="text-center py-4">
                    <h5 class="text-muted">No notifications yet.</h5>
                    <p class="text-muted">You will receive notifications about your appointments and donations here.</p>
                </div>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($notifications as $notification): ?>
                    <div class="list-group-item list-group-item-action <?= !$notification->isRead() ? 'list-group-item-light border-start border-4 border-danger' : '' ?>">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">
                                <?php if ($notification->type == 'success'): ?>
                                    ✅
                                <?php elseif ($notification->type == 'warning'): ?>
                                    ⚠️
                                <?php elseif ($notification->type == 'danger'): ?>
                                    ❌
                                <?php else: ?>
                                    ℹ️
                                <?php endif; ?>
                                <?= $notification->title ?>
                                <?php if (!$notification->isRead()): ?>
                                    <span class="badge bg-danger ms-2">New</span>
                                <?php endif; ?>
                            </h6>
                            <small class="text-muted">
                                <?= date('d M Y H:i', $notification->created_at) ?>
                            </small>
                        </div>
                        <p class="mb-1"><?= $notification->message ?></p>
                    </div>
                    <?php endforeach; ?>
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