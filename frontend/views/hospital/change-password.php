<?php

use yii\helpers\Html;

$this->title = 'Change Password';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">

                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">🔐 Change Password</h4>
                    <p class="mb-0 small">Update your account password</p>
                </div>

                <div class="card-body p-4">

                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                        <div class="alert alert-success">
                            <?= Yii::$app->session->getFlash('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger">
                            <?= Yii::$app->session->getFlash('error') ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
                            value="<?= Yii::$app->request->csrfToken ?>">

                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <div class="input-group">
                                <input type="password" id="current-password" name="current_password"
                                    class="form-control" placeholder="Enter current password" required>
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePassword('current-password', 'eye1')">
                                    <i id="eye1">👁</i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" id="new-password" name="new_password"
                                    class="form-control" placeholder="Enter new password" required>
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePassword('new-password', 'eye2')">
                                    <i id="eye2">👁</i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" id="confirm-password" name="confirm_password"
                                    class="form-control" placeholder="Confirm new password" required>
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePassword('confirm-password', 'eye3')">
                                    <i id="eye3">👁</i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Change Password
                            </button>
                        </div>

                    </form>

                </div>

                <div class="card-footer text-center">
                    <?= Html::a('← Back to Dashboard', ['hospital/dashboard'], [
                        'class' => 'btn btn-secondary'
                    ]) ?>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId, eyeId) {
    var field = document.getElementById(fieldId);
    var eye   = document.getElementById(eyeId);
    if (field.type === 'password') {
        field.type      = 'text';
        eye.textContent = '🙈';
    } else {
        field.type      = 'password';
        eye.textContent = '👁';
    }
}
</script>