<?php

use yii\helpers\Html;

$this->title = 'Forgot Password';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">

                <div class="card-header text-center bg-danger text-white">
                    <h4>🔐 Forgot Password</h4>
                    <p class="mb-0">Reset your account password</p>
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

                    <?php if ($step == 1): ?>

                        <!-- Step 1: Enter Email -->
                        <p class="text-muted">Enter your email address to reset your password.</p>

                        <form method="POST">
                            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
                                value="<?= Yii::$app->request->csrfToken ?>">
                            <input type="hidden" name="step" value="1">

                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control"
                                    placeholder="Enter your email" required
                                    value="<?= Html::encode($email) ?>">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger btn-lg">
                                    Find My Account
                                </button>
                            </div>
                        </form>

                    <?php elseif ($step == 2): ?>

                        <!-- Step 2: Enter New Password -->
                        <div class="alert alert-success">
                            ✅ Account found! Enter your new password below.
                        </div>

                        <form method="POST">
                            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
                                value="<?= Yii::$app->request->csrfToken ?>">
                            <input type="hidden" name="step" value="2">
                            <input type="hidden" name="email" value="<?= Html::encode($email) ?>">

                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" id="new-password" name="new_password"
                                        class="form-control" placeholder="Enter new password" required>
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('new-password', 'eye1')">
                                        <i id="eye1">👁</i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" id="confirm-password" name="confirm_password"
                                        class="form-control" placeholder="Confirm new password" required>
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('confirm-password', 'eye2')">
                                        <i id="eye2">👁</i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger btn-lg">
                                    Reset Password
                                </button>
                            </div>
                        </form>

                    <?php endif; ?>

                </div>

                <div class="card-footer text-center">
                    <?= Html::a('← Back to Login', ['auth/login'], [
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