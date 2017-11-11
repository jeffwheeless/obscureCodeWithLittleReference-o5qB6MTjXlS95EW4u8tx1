<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\EquipmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="equipment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, '_id') ?>

    <?= $form->field($model, 'product') ?>

    <?= $form->field($model, 'sn') ?>

    <?= $form->field($model, 'mfg') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'checkout') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'purchase') ?>

    <?php // echo $form->field($model, 'war_start') ?>

    <?php // echo $form->field($model, 'war_end') ?>

    <?php // echo $form->field($model, 'firmware') ?>

    <?php // echo $form->field($model, 'photo') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
