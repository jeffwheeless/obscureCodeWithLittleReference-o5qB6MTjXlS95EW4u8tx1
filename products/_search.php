<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Categories;

/* @var $this yii\web\View */
/* @var $model frontend\models\ProductsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="quickview-wrapper open" id="filters">
    <div class="padding-25 ">
        <a class="builder-close quickview-toggle pg-close" data-toggle="quickview" data-toggle-element="#filters" href="#"></a>
        <?php
        $template = [
            'template' => '{label}{input}{hint}{error}',
            'options' => ['class' => 'form-group form-group-default'],
            'labelOptions' => [
                'class' => 'control-label label-lg'
            ],
        ];
        $form = ActiveForm::begin([
                    'method' => 'get',
                    'options' => [
                        'id' => 'create-product-form',
                        'enableClentValidtaion' => true,
                        'enctype' => 'multipart/form-data'
                    ]
        ]);
        ?>
        <?php
        $query = Categories::find()->asArray()->all();
        $new = [];
        if (!empty($query)) {
            foreach ($query as $k => $v) {
                if ($v['parent_cat'] == '')
                    $new[(string) $v['_id']] = $v['name'];
                elseif (isset($new[(string) $v['parent_cat']]))
                    $new[(string) $v['_id']] = $new[(string) $v['parent_cat']] . ' - ' . $v['name'];
            }
        }
        asort($new);
        if (!empty($product->categories) && is_array($new) && is_array($product->categories)) {
            foreach ($product->categories as $key => $cat) {
                if (isset($new[(string) $cat['name']])) {
                    unset($new[(string) $cat['name']]);
                }
            }
        }
        ?>
        <?php
        echo $form->field($model, 'categories', $template)->widget(kartik\widgets\Select2::classname(), [
            'data' => array_merge(["" => ""], $new),
            'options' => ['placeholder' => 'Select a Categories', 'multiple' => false],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
        <?= $form->field($model, 'model', $template) ?>
        <?= $form->field($model, 'nsn', $template) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
