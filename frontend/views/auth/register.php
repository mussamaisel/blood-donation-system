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

                    <?php if ($user->hasErrors() || $donor->hasErrors()): ?>
                        <div class="alert alert-danger">
                            <strong>❌ Please fix the following errors:</strong>
                            <ul class="mb-0 mt-1">
                                <?php foreach ($user->errors as $field => $errors): ?>
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                                <?php foreach ($donor->errors as $field => $errors): ?>
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                        
                            
                    

                    <?php $form = ActiveForm::begin([
                        'id' => 'register-form',
                        'enableClientValidation' => false,
                        'enableAjaxValidation'   => false,
                    ]); ?>

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
                                <?= $form->field($user, 'password')
                                    ->passwordInput(['placeholder' => 'Password', 'id' => 'reg-password'])
                                    ->label('Password') ?>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-1"
                                    onclick="togglePassword('reg-password', 'eye-reg')">
                                    <i id="eye-reg">👁</i> Show Password
                                </button>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($user, 'confirm_password')
                                    ->passwordInput(['placeholder' => 'Confirm Password', 'id' => 'reg-confirm'])
                                    ->label('Confirm Password') ?>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-1"
                                    onclick="togglePassword('reg-confirm', 'eye-confirm')">
                                    <i id="eye-confirm">👁</i> Show Password
                                </button>
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
</script>