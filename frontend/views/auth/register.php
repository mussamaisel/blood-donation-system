<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Donor;

$this->title = 'Register as Donor';
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">

                <div class="card-header text-center bg-danger text-white">
                    <h4>🩸 Register as Donor</h4>
                    <p class="mb-0">Join us and save lives!</p>
                </div>

                <div class="card-body p-4">

                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger">
                            <?= Yii::$app->session->getFlash('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php $form = ActiveForm::begin(['id' => 'register-form']); ?>

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
                                       <input type="password" id="reg-password" name="User[password]"
                                           class="form-control" placeholder="Password">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('reg-password', 'eye-reg')">
                                            <i id="eye-reg">👁</i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" id="reg-confirm" name="User[confirm_password]"
                                            class="form-control" placeholder="Confirm Password">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('reg-confirm', 'eye-confirm')">
                                            <i id="eye-confirm">👁</i>
                                        </button>
                                    </div>
                                    <div id="password-error" class="text-danger small mt-1"></div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="text-danger mb-3">Personal Information</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($donor, 'full_name')
                                    ->textInput(['placeholder' => 'Full Name'])
                                    ->label('Full Name') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($donor, 'phone')
                                    ->textInput(['placeholder' => 'Phone Number'])
                                    ->label('Phone Number') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($donor, 'blood_type')
                                    ->dropDownList(
                                        array_combine(Donor::BLOOD_TYPES, Donor::BLOOD_TYPES),
                                        ['prompt' => 'Select Blood Type']
                                    )
                                    ->label('Blood Type') ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($donor, 'gender')
                                    ->dropDownList([
                                        'male'   => 'Male',
                                        'female' => 'Female',
                                    ], ['prompt' => 'Select Gender'])
                                    ->label('Gender') ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($donor, 'weight')
                                    ->textInput(['placeholder' => 'Weight in kg'])
                                    ->label('Weight (kg)') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($donor, 'date_of_birth')
                                    ->textInput(['type' => 'date'])
                                    ->label('Date of Birth') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($donor, 'city')
                                    ->textInput(['placeholder' => 'City'])
                                    ->label('City') ?>
                            </div>
                        </div>

                        <?= $form->field($donor, 'address')
                            ->textarea(['rows' => 3, 'placeholder' => 'Full Address'])
                            ->label('Address') ?>

                        <div class="d-grid mt-3">
                            <?= Html::submitButton('Register Now', [
                                'class' => 'btn btn-danger btn-lg',
                            ]) ?>
                        </div>

                    <?php ActiveForm::end(); ?>

                </div>

                <div class="card-footer text-center">
                    <p class="mb-0">Already have an account?
                        <?= Html::a('Sign In', ['auth/login']) ?>
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

document.querySelector('#register-form').addEventListener('submit', function(e) {
    var password = document.getElementById('reg-password').value;
    var confirm  = document.getElementById('reg-confirm').value;
    var errorDiv = document.getElementById('password-error');

    if (password !== confirm) {
        e.preventDefault();
        errorDiv.textContent = 'Passwords do not match.';
        document.getElementById('reg-confirm').classList.add('is-invalid');
    } else {
        errorDiv.textContent = '';
        document.getElementById('reg-confirm').classList.remove('is-invalid');
    }
});
</script>