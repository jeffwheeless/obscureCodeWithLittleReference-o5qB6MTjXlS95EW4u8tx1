<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Products;
use frontend\models\Equipment;
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
    <?php if (empty($firmware)) : ?>
        <?php
        $unfiltered_firmware = Products::find()->select(['firmware'])->where(['_id' => $equipment->product])->one();
        $possible_firmware = array();
        if (is_array($unfiltered_firmware['firmware']) && !empty($unfiltered_firmware['firmware'])) {
            foreach ($unfiltered_firmware['firmware'] as $firmware) {
                $possible_firmware = [(string) $firmware['_id'] => $firmware['type'] . ' (ver. ' . $firmware['version'] . ')'];
            }
        }
        ?>

        <div class="row">
            <div class="col-md-12">
                <?=
                $form->field($model, '_id', $template)->widget(kartik\widgets\Select2::classname(), [
                    'data' => array_merge(["" => ""], $possible_firmware),
                    'options' => ['placeholder' => 'Select product'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
        </div>

    <?php endif ?>

    <div class="row">
        <div class="col-md-6 col-sm-12">
            <?=
            $form->field($model, 'install', $template)->widget(DatePicker::classname(), [
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
        <div class="col-md-6 col-sm-12">
            <?=
            $form->field($model, 'expiration', $template)->widget(DatePicker::classname(), [
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
            ]);
            ?>
        </div>
    </div>



    <div class="row">
        <div class="col-md-6" id="add-equipment-button">
            <?= Html::submitButton(Yii::t('app', 'Save & Create Equipment'), ['class' => 'margin-bottom-20 btn btn-lg btn-block btn-raised btn-success']) ?>
        </div>
        <div class="col-md-6">
            <?= Html::button(Yii::t('app', 'Cancel & Close'), ['class' => 'btn btn-lg  btn-block btn-raised btn-danger margin-bottom-20 ', 'data-dismiss' => 'modal']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $("#p_mfg").on("change", function () {
        var id = $(this).val();
        if (id != "") {
            $("#add-manufacturer-wrapper,#add-manufacturer-button").hide();
            $("#product-fields,#add-product-button").show();
        }
        else {
            $("#p_mfg").val("test");
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
