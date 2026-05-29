<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Donor;

$this->title = 'Request Blood';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">

                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">🩸 Request Blood</h4>
                    <p class="mb-0 small"><?= $hospital->name ?></p>
                </div>

                <div class="card-body p-4">

                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger">
                            <?= Yii::$app->session->getFlash('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php $form = ActiveForm::begin(['id' => 'create-request-form']); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'blood_type')
                                    ->dropDownList(
                                        array_combine(Donor::BLOOD_TYPES, Donor::BLOOD_TYPES),
                                        ['prompt' => 'Select Blood Type']
                                    )
                                    ->label('Blood Type') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'units_needed')
                                    ->textInput(['type' => 'number', 'min' => 1, 'placeholder' => 'Units needed'])
                                    ->label('Units Needed') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'priority')
                                    ->dropDownList([
                                        'low'    => 'Low',
                                        'normal' => 'Normal',
                                        'high'   => 'High',
                                        'urgent' => 'Urgent',
                                    ])
                                    ->label('Priority') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'needed_by')
                                    ->textInput(['type' => 'date'])
                                    ->label('Needed By') ?>
                            </div>
                        </div>

                        <?= $form->field($model, 'reason')
                            ->textarea(['rows' => 4, 'placeholder' => 'Explain why you need this blood...'])
                            ->label('Reason') ?>

                        <div class="d-grid mt-3">
                            <?= Html::submitButton('Submit Request', [
                                'class' => 'btn btn-danger btn-lg',
                            ]) ?>
                        </div>

                    <?php ActiveForm::end(); ?>

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