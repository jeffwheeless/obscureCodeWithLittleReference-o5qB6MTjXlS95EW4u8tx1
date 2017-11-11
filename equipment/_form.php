<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Products;
use frontend\models\Locations;
use frontend\models\Status;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use \kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model frontend\models\Equipment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="equipment-form">

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
                    'id' => 'create-equipment-form',
                    'enableClentValidtaion' => true,
                    'enctype' => 'multipart/form-data'
                ]
    ]);
    ?>
    <div class="row">
        <div class="col-md-12">
            <?=
            $form->field($model, 'product', $template)->widget(kartik\widgets\Select2::classname(), [
                'data' => array_merge(["" => ""], ArrayHelper::map(Products::find()->orderBy('model')->all(), function ($model) {
                            return (string) $model->_id;
                        }, 'model')),
                'options' => ['placeholder' => 'Select product'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
    </div>

    <div id="add-manufacturer-wrapper" style="text-align: justify;">
        <hr/>
        <h4>If the product isn't listed, you can click the button below to add it to the catalog.</h4>
        <hr/>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'sn', $template) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'name', $template) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php
            $query = Locations::find()->asArray()->all();
            $new = [];
            if (!empty($query)) {
                foreach ($query as $k => $v) {
                    if ((string) $v['parent_location'] == $id)
                        $new[(string) $v['_id']] = $v['name'];
                    if (isset($new[(string) $v['parent_location']]))
                        $new[(string) $v['_id']] = $new[(string) $v['parent_location']] . ' (' . $v['name'] . ')';
                }
            }
            asort($new);
            echo $form->field($model, 'location', $template)->widget(kartik\widgets\Select2::classname(), [
                'data' => array_merge(["" => ""], $new),
                'options' => ['placeholder' => 'Select Owning/Parent Location'],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]);
            ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <?=
            $form->field($modelimage, 'file', $template)->widget(FileInput::classname(), [
                'options' => [
                    'id' => 'image',
                    'multiple' => false,
                    'accept' => 'image/*',
                    'class' => 'form-control',
                ],
                'pluginOptions' => [
                    'browseClass' => '',
                    'browseLabel' => 'Select Product Photo',
                    'browseIcon' => '<i class="glyphicon glyphicon-picture"></i> ',
                    'showCaption' => false,
                    'showUpload' => false,
                    'previewFileType' => true,
                    'showUpload' => false,
                    'showPreview' => true,
                    'showRemove' => true,
                    'allowedFileExtensions' => ['png', 'jpg', 'jpng'],
                    'createSearchChoice' => true,
                    'initialPreview' => $model->hasphoto == 0 ? FALSE : [Html::img(Url::to(['file/file', 'id' => (string) $model->_id]), ['class' => 'file-preview-image', 'alt' => $model->_id, 'title' => $model->_id])],
                    'overwriteInitial' => true
                ]
            ]);
            ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-4 col-sm-12">
            <?=
            $form->field($model, 'purchase', $template)->widget(DatePicker::classname(), [
                'options' => [
                    'readonly' => 'true',
                    'placeholder' => 'Click to Select',
                    'style' => 'cursor: pointer;'
                ],
                'type' => 1,
                'pluginOptions' => [
                    'autoclose' => true,
                    'convertFormat' => true,
                    'format' => 'MM dd, yyyy',
                    'todayBtn' => true,
                    'todayHighlight' => true,
                    'endDate' => date('M jS, Y', strtotime(date('M jS, Y') . '+ 1 years')),
                ],
            ]);
            ?>
        </div>
        <div class="col-md-4 col-sm-6">
            <?=
            $form->field($model, 'war_start', $template)->widget(DatePicker::classname(), [
                'options' => [
                    'readonly' => 'true',
                    'placeholder' => 'Click to Select',
                    'style' => 'cursor: pointer;'
                ],
                'type' => 1,
                'pluginOptions' => [
                    'autoclose' => true,
                    'convertFormat' => true,
                    'format' => 'MM dd, yyyy',
                    'todayBtn' => true,
                    'todayHighlight' => true,
                    'endDate' => date('M jS, Y', strtotime(date('M jS, Y') . '+ 1 years')),
                ],
            ]);
            ?>
        </div>
        <div class="col-md-4 col-sm-6">
            <?=
            $form->field($model, 'war_end', $template)->widget(DatePicker::classname(), [
                'options' => [
                    'readonly' => 'true',
                    'placeholder' => 'Click to Select',
                    'style' => 'cursor: pointer;'
                ],
                'type' => 1,
                'pluginOptions' => [
                    'autoclose' => true,
                    'convertFormat' => true,
                    'format' => 'MM dd, yyyy',
                    'todayBtn' => true,
                    'todayHighlight' => true,
                    'endDate' => date('M jS, Y', strtotime(date('M jS, Y') . '+ 1 years')),
                ],
            ]);
            ?>
        </div>
    </div>




    <div class="row">
        <div class="col-md-12">
            <?=
            $form->field($model, 'status', $template)->widget(kartik\widgets\Select2::classname(), [
                'data' => array_merge(["" => ""], ArrayHelper::map(Status::find()->orderBy('severity')->all(), function ($model) {
                            return (string) $model->_id;
                        }, 'name_long')),
                'options' => ['placeholder' => 'Select Current Status'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6" id="add-equipment-button">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save & Create Equipment') : Yii::t('app', 'Save & Update'), ['class' => 'margin-bottom-20 btn btn-lg btn-block btn-raised btn-success']) ?>
        </div>
        <div class="col-md-6">
            <?= Html::button(Yii::t('app', 'Cancel & Close'), ['class' => 'btn btn-lg  btn-block btn-raised btn-danger margin-bottom-20 ', 'data-dismiss' => 'modal']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $("#equipment-product").on("change", function() {
        var id = $(this).val();
        if (id != "") {
            $("#add-manufacturer-wrapper,#add-manufacturer-button").hide();
            $("#product-fields,#add-product-button").show();
        }
        else {
            $("#equipment-product").val("test");
            $("#select2-chosen-1").val("test");
            $("#add-manufacturer-wrapper,#add-manufacturer-button").show();
            $("#product-fields,#add-product-button").hide();
        }
    });

    function removeDisabled() {
        $("input").removeAttr("disabled");
        $(".disabled").removeClass("disabled");
    }
</script>
