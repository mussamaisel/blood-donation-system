<?php

use yii\helpers\Html;

$this->title = 'Notifications';
?>

<div class="container-fluid mt-4">

    <div class="row mb-4">
        <div class="col-md-12">
            <h3>🔔 Notifications</h3>
            <p class="text-muted">All system notifications</p>
        </div>
    </div>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">All Notifications</h5>
        </div>
        <div class="card-body">
            <?php if (empty($notifications)): ?>
                <div class="text-center py-4">
                    <h5 class="text-muted">No notifications yet.</h5>
                    <p class="text-muted">You will receive notifications about new registrations and requests here.</p>
                </div>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($notifications as $notification): ?>
                    <div class="list-group-item <?= !$notification->isRead() ? 'list-group-item-light border-start border-4 border-danger' : '' ?>">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">
                                <?php if ($notification->type == 'success'): ?>✅
                                <?php elseif ($notification->type == 'warning'): ?>⚠️
                                <?php elseif ($notification->type == 'danger'): ?>❌
                                <?php else: ?>ℹ️
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

    <div class="mt-3">
        <?= Html::a('← Back to Dashboard', ['admin/dashboard'], [
            'class' => 'btn btn-secondary'
        ]) ?>
    </div>

</div>