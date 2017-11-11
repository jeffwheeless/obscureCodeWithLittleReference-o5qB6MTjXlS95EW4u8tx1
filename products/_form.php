<?php

 
use yii\helpers\Html;
use yii\helpers\Url;
use common\components\widgets\Form;
use yii\helpers\ArrayHelper;
use common\components\widgets\MaskedInputType;
use frontend\models\Companies;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model frontend\models\Products */
/* @var $form yii\widgets\ActiveForm */
$formId = time() . rand(0, 9999);
?>

<div class="products-form">
    <?php
    $form = Form::begin([
                'options' => [
                    'id' => $formId,
                    'enctype' => 'multipart/form-data'
                ]
    ]);
    ?>
    <?=
    $form->field($model, 'mfg', Yii::$app->params['template'])->widget(kartik\widgets\Select2::classname(), [
        'data' => array_merge(["" => ""], ArrayHelper::map(Companies::find()->orderBy('name')->all(), function ($model) {
                    return (string) $model->_id;
                }, 'name')),
        'options' => ['placeholder' => 'Select mfg'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    <div id="add-mfg-wrapper" style="text-align: justify;">
        <hr/>
        <h4>If the manufacturer isn't listed, you can click the button below to add it to the list.</h4>
        <hr/>
    </div>
    <div id="product-fields" style="display: none;">
        <?= $form->field($model, 'model', Yii::$app->params['template']); ?>

        <?=
        $form->field($modelimage, 'file', Yii::$app->params['template'])->widget(FileInput::classname(), [
            'options' => [
                'id' => 'company-logo',
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
                'createSearchChoice' => true
            ]
        ]);
        ?>
        <div class="row">
            <div class="col-md-6">
                <?=
                $form->field($model, 'nsn', Yii::$app->params['template'])->widget(MaskedInputType::classname(), [
                    'type' => 'tel',
                    'mask' => '9999-99-999-9999', 'options' => ['class' => 'form-control'], 'clientOptions' => [
                        'removeMaskOnSubmit' => false,
                        'clearMaskOnLostFocus' => true,
                    ]
                ]);
                ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'status', Yii::$app->params['template'])->dropDownList([$model::STATUS_ACTIVE => 'Active', $model::STATUS_INACTIVE => 'Inactive']); ?>
            </div>
        </div>
        <?= $form->field($model, 'desc', Yii::$app->params['template'])->textarea(['style' => 'resize:vertical; min-height:100px;max-height:300px;']); ?>
    </div>
    <div class="row">
        <div class="col-md-6" style="display: none;" id="add-product-button">
            <?= Html::submitButton(Yii::t('products', $model->isNewRecord ? 'Save & Close' : 'Update & Close'), ['class' => 'margin-bottom-20 btn btn-lg btn-block btn-raised btn-success']) ?>
        </div>
        <div class="col-md-6" id="add-mfg-button">
            <?= Html::button(Yii::t('products', 'Click to add a mfg to list'), ['value' => Url::to(['companies/create']), 'title' => 'Adding New Manufacturer ', 'class' => 'showRelatedModalButton margin-bottom-20 btn btn-lg btn-block btn-raised btn-success']); ?>
        </div>
        <div class="col-md-6">
            <?= Html::button(Yii::t('products', 'Cancel & Close'), ['class' => 'btn btn-lg cancel-btn btn-block btn-raised btn-danger margin-bottom-20']) ?>
        </div>
    </div>
    <?php Form::end(); ?>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        if ($("#products-mfg").val() != "") {
            $("#add-mfg-wrapper,#add-mfg-button").hide();
            $("#product-fields,#add-product-button").show();
        }
    });
    $("#products-mfg").on("change", function () {
        var id = $(this).val();
        if (id != "") {
            $("#add-mfg-wrapper,#add-mfg-button").hide();
            $("#product-fields,#add-product-button").show();
        }
        else {
            $("#products-mfg").val("test");
            $("#select2-chosen-1").val("test");
            $("#add-mfg-wrapper,#add-mfg-button").show();
            $("#product-fields,#add-product-button").hide();
        }
    });
</script>