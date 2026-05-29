<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Login';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">

                <div class="card-header text-center bg-danger text-white">
                    <h4>🩸 Blood Donation System</h4>
                    <p class="mb-0">Sign In to Your Account</p>
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

                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                        <?= $form->field($model, 'username')
                            ->textInput(['placeholder' => 'Username or Email'])
                            ->label('Username or Email') ?>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" id="login-password" name="LoginForm[password]"
                                    class="form-control" placeholder="Password">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePassword('login-password', 'eye-login')">
                                    <i id="eye-login">👁</i>
                                </button>
                            </div>
                        </div>
                            
                            

                        <?= $form->field($model, 'rememberMe')
                            ->checkbox()
                            ->label('Remember Me') ?>

                        <div class="d-grid mt-3">
                            <?= Html::submitButton('Sign In', [
                                'class' => 'btn btn-danger btn-lg',
                            ]) ?>
                        </div>

                    <?php ActiveForm::end(); ?>

                </div>

                <div class="card-footer text-center">
                    <p class="mb-1">Don't have an account?
                        <?= Html::a('Register as Donor', ['auth/register']) ?>
                    </p>
                    <p class="mb-0">Are you a hospital?
                        <?= Html::a('Register Hospital', ['auth/register-hospital']) ?>
                    </p>
                </div>

            </div>
        </div>
    </div>
    
    <?php $this->registerJsVar('dummy', ''); ?>
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
</div>