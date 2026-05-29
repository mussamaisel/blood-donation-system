<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Register Hospital';
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">

                <div class="card-header text-center bg-danger text-white">
                    <h4>🏥 Register Hospital</h4>
                    <p class="mb-0">Join our blood donation network!</p>
                </div>

                <div class="card-body p-4">

                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger">
                            <?= Yii::$app->session->getFlash('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php $form = ActiveForm::begin(['id' => 'register-hospital-form']); ?>

                        <h5 class="text-danger mb-3">Account Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($user, 'username')
                                    ->textInput(['placeholder' => 'Username'])
                                    ->label('Username') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($user, 'email')
                                    ->textInput(['placeholder' => 'Email'])
                                    ->label('Email') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <div class="input-group">
                                        <input type="password" id="hosp-password" name="User[password]"
                                            class="form-control" placeholder="Password">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('hosp-password', 'eye-hosp')">
                                            <i id="eye-hosp">👁</i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" id="hosp-confirm" name="User[confirm_password]"
                                            class="form-control" placeholder="Confirm Password">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('hosp-confirm', 'eye-hosp-confirm')">
                                            <i id="eye-hosp-confirm">👁</i>
                                        </button>
                                    </div>
                                    <div id="password-error" class="text-danger small mt-1"></div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="text-danger mb-3">Hospital Information</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($hospital, 'name')
                                    ->textInput(['placeholder' => 'Hospital Name'])
                                    ->label('Hospital Name') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($hospital, 'email')
                                    ->textInput(['placeholder' => 'Hospital Email'])
                                    ->label('Hospital Email') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($hospital, 'phone')
                                    ->textInput(['placeholder' => 'Phone Number'])
                                    ->label('Phone Number') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($hospital, 'region')
                                    ->textInput(['placeholder' => 'Region'])
                                    ->label('Region') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($hospital, 'city')
                                    ->textInput(['placeholder' => 'City'])
                                    ->label('City') ?>
                            </div>
                        </div>

                        <?= $form->field($hospital, 'address')
                            ->textarea(['rows' => 3, 'placeholder' => 'Full Address'])
                            ->label('Address') ?>

                        <div class="d-grid mt-3">
                            <?= Html::submitButton('Register Hospital', [
                                'class' => 'btn btn-danger btn-lg',
                            ]) ?>
                        </div>

                    <?php ActiveForm::end(); ?>

                </div>

                <div class="card-footer text-center">
                    <p class="mb-1">Already have an account?
                        <?= Html::a('Sign In', ['auth/login']) ?>
                    </p>
                    <p class="mb-0">Want to register as donor?
                        <?= Html::a('Register as Donor', ['auth/register']) ?>
                    </p>
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

document.querySelector('#register-hospital-form').addEventListener('submit', function(e) {
    var password = document.getElementById('hosp-password').value;
    var confirm  = document.getElementById('hosp-confirm').value;
    var errorDiv = document.getElementById('password-error');

    if (password !== confirm) {
        e.preventDefault();
        errorDiv.textContent = 'Passwords do not match.';
        document.getElementById('hosp-confirm').classList.add('is-invalid');
    } else {
        errorDiv.textContent = '';
        document.getElementById('hosp-confirm').classList.remove('is-invalid');
    }
});
</script>