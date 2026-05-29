<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Donor;

$this->title = 'Add Blood Stock';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">

                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">📦 Add Blood Stock</h4>
                    <p class="mb-0 small"><?= $hospital->name ?></p>
                </div>

                <div class="card-body p-4">

                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger">
                            <?= Yii::$app->session->getFlash('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php $form = ActiveForm::begin(['id' => 'add-stock-form']); ?>

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
                                <?= $form->field($model, 'units')
                                    ->textInput(['type' => 'number', 'min' => 1, 'placeholder' => 'Number of units'])
                                    ->label('Units') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'expiry_date')
                                    ->textInput(['type' => 'date'])
                                    ->label('Expiry Date') ?>
                            </div>
                        </div>

                        <div class="d-grid mt-3">
                            <?= Html::submitButton('Add Stock', [
                                'class' => 'btn btn-success btn-lg',
                            ]) ?>
                        </div>

                    <?php ActiveForm::end(); ?>

                </div>

                <div class="card-footer text-center">
                    <?= Html::a('← Back to Blood Stock', ['hospital/blood-stock'], [
                        'class' => 'btn btn-secondary'
                    ]) ?>
                </div>

            </div>
        </div>
    </div>
</div>