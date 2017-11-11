<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\MaintenanceFormsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="maintenance-forms-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, '_id') ?>

    <?= $form->field($model, 'form_title') ?>

    <?= $form->field($model, 'desc') ?>

    <?= $form->field($model, 'question') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('maintenanceforms', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('maintenanceforms', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
