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
use frontend\models\Status;

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
            <?= $form->field($model, 'value', $template); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'status', $template)->widget(kartik\widgets\Select2::classname(), [
                'data' => array_merge(["" => ""], ArrayHelper::map(Status::find()->orderBy('severity')->all(), function ($model) {
                            return (string) $model->_id;
                        }, 'name_long')),
                'options' => ['placeholder' => 'Select a Status for Equipment if Selected'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
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
