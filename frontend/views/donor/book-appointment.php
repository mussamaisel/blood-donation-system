<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Hospital;

$this->title = 'Book Appointment';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">

                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">📅 Book Appointment</h4>
                    <p class="mb-0 small">Schedule your blood donation</p>
                </div>

                <div class="card-body p-4">

                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger">
                            <?= Yii::$app->session->getFlash('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php $form = ActiveForm::begin(['id' => 'book-appointment-form']); ?>

                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model, 'hospital_id')
                                    ->dropDownList(
                                        ArrayHelper::map($hospitals, 'id', 'name'),
                                        ['prompt' => 'Select Hospital']
                                    )
                                    ->label('Hospital') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'appointment_date')
                                    ->textInput([
                                        'type' => 'date',
                                        'min'  => date('Y-m-d'),
                                    ])
                                    ->label('Appointment Date') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'appointment_time')
                                    ->textInput(['type' => 'time'])
                                    ->label('Appointment Time') ?>
                            </div>
                        </div>

                        <?= $form->field($model, 'notes')
                            ->textarea(['rows' => 3, 'placeholder' => 'Any additional notes...'])
                            ->label('Notes (Optional)') ?>

                        <!-- Donor Info -->
                        <div class="alert alert-info mt-3">
                            <strong>Your Blood Type:</strong> <?= $donor->blood_type ?> &nbsp;|&nbsp;
                            <strong>Last Donation:</strong> <?= $donor->last_donation ?? 'No donations yet' ?>
                            <?php if (!$donor->canDonate()): ?>
                                <br><span class="text-danger">
                                    ⚠️ You cannot donate yet — please wait 3 months since your last donation.
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid mt-3">
                            <?= Html::submitButton('Book Appointment', [
                                'class' => 'btn btn-danger btn-lg',
                            ]) ?>
                        </div>

                    <?php ActiveForm::end(); ?>

                </div>

                <div class="card-footer text-center">
                    <?= Html::a('← Back to Appointments', ['donor/appointments'], [
                        'class' => 'btn btn-secondary'
                    ]) ?>
                </div>

            </div>
        </div>
    </div>
</div>