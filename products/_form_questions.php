<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
// use frontend\models\MaintenanceQuestions;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\components\widgets\MaskedInputType;
use frontend\models\Companies;
use kartik\widgets\FileInput;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model frontend\models\MaintenanceQuestions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="maintenance-questions-form">

    <?php
    $template = [
        'template' => '{label}{input}{hint}{error}',
        'options' => ['class' => 'form-group form-group-default'],
        'labelOptions' => [
            'class' => 'control-label label-lg'
        ],
    ];
    $form = ActiveForm::begin([
                'options' => [
                    'data-pjax' => '',
                    'id' => 'create-case-form',
                    'enableClientValidtaion' => true,
                    'enctype' => 'multipart/form-data'
                ]
    ]);
    ?>


    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'label', $template) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'class', $template)->radioList(['col-lg-12' => 'Full Screen', 'col-lg-6' => 'Half Screen']); //(['style' => 'resize:vertical; min-height:100px;max-height:300px;']); ?>
        </div>
        <div class="col-md-6">
            <?php $possible_types = ['textarea' => 'Paragraph', 'text' => 'Short Text', 'dropdown' => 'Dropdown', 'date' => 'Date Field', 'todaydate' => 'Today\'s Date']; asort($possible_types); ?>
            <?= $form->field($model, 'type', $template)->widget(kartik\widgets\Select2::classname(), [
                'data' => array_merge(["" => ""], $possible_types),
                // 'options' => ['placeholder' => 'Select mfg'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'tooltip', $template)->textarea(['style' => 'resize:vertical; min-height:100px;max-height:300px;']); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'helptext', $template)->textarea(['style' => 'resize:vertical; min-height:100px;max-height:300px;']); ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <?php
            echo $form->field($model, 'position', $template)->widget(kartik\widgets\Select2::classname(), [
                'data' =>  $position_list,
                'options' => ['placeholder' => 'Select Position'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'required', $template)->dropDownList(['0'=>'Not Required', '1'=>'Is Required']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'min', $template) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'max', $template) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('maintenancequestions', 'Create') : Yii::t('maintenancequestions', 'Update'), ['class' => 'margin-bottom-20 btn btn-lg btn-block btn-raised btn-success']) ?>
        </div>
        <div class="col-md-6">
            <?= Html::button(Yii::t('maintenancequestions', 'Cancel & Close'), ['class' => 'btn btn-lg  btn-block btn-raised btn-danger margin-bottom-20 ', 'data-dismiss' => 'modal']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
