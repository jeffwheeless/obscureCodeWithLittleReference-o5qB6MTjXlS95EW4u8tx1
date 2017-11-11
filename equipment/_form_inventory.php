<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Inventory;

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

    $form = ActiveForm::begin([ 'id' => 'add-inventory-form', 'enableAjaxValidation' => true]);
    ?>

    <?php
    if (isset($equipment->productInfo->parts) && !empty($equipment->productInfo->parts)) {
        $search_array = [];
        $all_inv = Inventory::find();
        $first_done = false;
        foreach ($equipment->productInfo->parts as $part) {
            if ($first_done == false) {
                $all_inv->where(['part_id' => (string) $part['parts']]);
                $first_done = true;
            } else {
                $all_inv->orWhere(['part_id' => (string) $part['parts']]);
            }
        }
        $all_inv = $all_inv->andWhere(['equipment_id' => ''])->orderBy('ref_name')->all();
        $display_array = [];
        foreach ($all_inv as $item) {
            $display_array[(string) $item->_id] = $item->partInfo->name;
            if (isset($item->locationInfo->parentLocation->name) && !empty($item->locationInfo->parentLocation->name)) {
                $display_array[(string) $item->_id] = $display_array[(string) $item->_id] . ' (Location: ' . $item->locationInfo->parentLocation->name . ' - ';
            } elseif ((isset($item->locationInfo->name) && !empty($item->locationInfo->name)) && (!isset($item->locationInfo->parentLocation->name) || empty($item->locationInfo->parentLocation->name))) {
                $display_array[(string) $item->_id] = $display_array[(string) $item->_id] . '(Location: ';
            }
            if (isset($item->locationInfo->name) && !empty($item->locationInfo->name) && empty($item->ref_name)) {
                $display_array[(string) $item->_id] = $display_array[(string) $item->_id] . $item->locationInfo->name . ') Quantity: ' . $item->quan;
            } elseif (isset($item->locationInfo->name) && !empty($item->locationInfo->name)) {
                $display_array[(string) $item->_id] = $display_array[(string) $item->_id] . $item->locationInfo->name . ')';
            }
            if (isset($item->ref_name) && !empty($item->ref_name)) {
                $display_array[(string) $item->_id] = $display_array[(string) $item->_id] . ' (Ref. ID: ' . $item->ref_name . ') Quantity: ' . $item->quan;
            }
        }
    } else
        $display_array = [];
    echo
    $form->field($model, 'inv', $template, $enableAjaxValidation = false)->widget(kartik\widgets\Select2::classname(), [
        'data' => array_merge(["" => ""], $display_array),
        'options' => ['placeholder' => 'Select Associated Equipment', 'multiple' => false],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]);
    ?>
    <div class="row">
        <div class="col-md-6">
            <?=
            $form->field($model, 'quan', $template)->input('number', [
                'maxlength' => 50,
                'autocomplete' => 'off',
                'autocapitalize' => 'on',
                'autocorrect' => 'off'
            ])
            ?>
        </div>
    </div>

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
