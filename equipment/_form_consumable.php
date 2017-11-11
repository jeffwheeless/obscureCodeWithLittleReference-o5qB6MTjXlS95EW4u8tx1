<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Inventory;
use frontend\models\Parts;
use common\components\widgets\MaskedInputType;
use \kartik\widgets\DatePicker;

$this->title = $model->isNewRecord ? 'Adding New Inventory' : 'Updating ' . Html::encode($model->ref_name);

/* @var $this yii\web\View */
/* @var $model frontend\models\Inventory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inventory-form">

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
                    'id' => 'create-company-form',
                    'enableClentValidtaion' => true,
                    'enctype' => 'multipart/form-data'
                ]
    ]);
    ?>

    <?php
    if (isset($equipment->productInfo->parts) && !empty($equipment->productInfo->parts)) {
        $all_inv = Inventory::find();
        $product_parts = Parts::find();
        foreach ($equipment->productInfo->parts as $part) {
            $product_parts->orWhere(['_id' => (string) $part['parts']]);
        }
        $parts = $product_parts->all();
        foreach ($parts as $part) {
            if ($part->consum == '1')
                $display_array[(string) $part->_id] = $part->name;
        }
    } else
        $display_array = [];
    echo
    $form->field($model, 'part_id', $template)->widget(kartik\widgets\Select2::classname(), [
        'data' => array_merge(["" => ""], $display_array),
        'options' => ['placeholder' => 'Select Associated Equipment', 'multiple' => true],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]);
    ?>

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
