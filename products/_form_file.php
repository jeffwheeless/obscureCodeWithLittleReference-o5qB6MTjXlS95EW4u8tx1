<?php

 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\components\widgets\MaskedInputType;
use frontend\models\Companies;
use kartik\widgets\FileInput;
use yii\widgets\Pjax;
use \kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model frontend\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form">
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
                    'id' => 'create-product-form',
                    'enableClentValidtaion' => true,
                    'enctype' => 'multipart/form-data'
                ]
    ]);
    ?>


    <?= $form->field($model, 'file_name', $template); ?>

    <?php if (isset($model->file_name)) : ?>
        <p style="text-align: center; color: red"><i>You may not overwrite the uploaded file, to upload a different file please add a new file.</i></p>
    <?php else : ?>
        <?=
        $form->field($document, 'file', $template)->widget(FileInput::classname(), [
            'options' => [
                'id' => 'company-logo',
                'multiple' => false,
                'class' => 'form-control',
                'disabled' => (isset($model->file_name)) ? true : false,
            ],
            'pluginOptions' => [
                'browseClass' => '',
                'browseLabel' => (isset($model->file_name)) ? $model->file_name : 'Select Product File',
                'browseIcon' => '<i class="glyphicon glyphicon-picture"></i> ',
                'showCaption' => false,
                'showUpload' => false,
                'previewFileType' => true,
                'showUpload' => false,
                'showPreview' => true,
                'showRemove' => true,
                'allowedFileExtensions' => ['pdf', 'doc', 'docx', 'txt'],
                'createSearchChoice' => true
            ]
        ]);
        ?>
    <?php endif ?>
    <div class="row">
        <div class="col-md-6">
            <?=
            $form->field($model, 'date', $template)->widget(DatePicker::classname(), [
                'options' => [
                    'readonly' => 'true',
                    'placeholder' => 'Click to Select',
                    'style' => 'cursor: pointer;'
                ],
                'pluginOptions' => [
                    'autoclose' => true,
                    'convertFormat' => true,
                    'format' => 'MM dd, yyyy',
                    'todayBtn' => true,
                    'removeButton' => false,
                    'calendarButton' => false,
                    'todayHighlight' => true,
                    'endDate' => date('M jS, Y', strtotime(date('M jS, Y') . '+ 1 years')),
                ],
            ])->label('Release Date');
            ?>

        </div>
    </div>
    <?= $form->field($model, 'desc', $template)->textarea(['style' => 'resize:vertical; min-height:100px;max-height:300px;']); ?>
    <div class="row">
        <div class="col-md-6">
            <?= Html::submitButton(Yii::t('products', 'Save & Update'), ['class' => 'margin-bottom-20 btn btn-lg btn-block btn-raised btn-success']) ?>
        </div>
        <div class="col-md-6">
            <?= Html::button(Yii::t('products', 'Cancel & Close'), ['class' => 'btn btn-lg  btn-block btn-raised btn-danger margin-bottom-20 ', 'data-dismiss' => 'modal']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
