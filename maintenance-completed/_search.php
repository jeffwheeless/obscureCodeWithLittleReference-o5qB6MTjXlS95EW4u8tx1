<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\MaintenanceCompletedSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="maintenance-completed-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, '_id') ?>

    <?= $form->field($model, 'form') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'rev') ?>

    <?= $form->field($model, 'answer') ?>

    <?php // echo $form->field($model, 'equipment_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('maintenancecompleted', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('maintenancecompleted', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
