<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Cases;
use frontend\models\Locations;
use frontend\models\Status;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model frontend\models\MaintenanceForms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="maintenance-forms-form">

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
            <?= $form->field($model, 'form_title', $template) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'desc', $template)->textarea(['style' => 'resize:vertical; min-height:100px;max-height:300px;']); ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('maintenanceforms', 'Create') : Yii::t('maintenanceforms', 'Update'), ['class' => 'margin-bottom-20 btn btn-lg btn-block btn-raised btn-success']) ?>
        </div>
        <div class="col-md-6">
            <?= Html::button(Yii::t('maintenanceforms', 'Cancel & Close'), ['class' => 'btn btn-lg  btn-block btn-raised btn-danger margin-bottom-20 ', 'data-dismiss' => 'modal']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
