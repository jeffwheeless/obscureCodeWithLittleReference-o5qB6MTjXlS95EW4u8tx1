<?php

 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Parts;
use frontend\models\Products;
use yii\helpers\Url;
use kartik\widgets\Select2;

//$this->title = $model->isNewRecord ? 'Adding New Company' : 'Updating ' . Html::encode($model->name);

/* @var $this yii\web\View */
/* @var $model frontend\models\Companies */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="create-specs-form">
    <?php
    $template = [
        'template' => '{label}{input}{hint}{error}',
        'options' => ['class' => 'form-group form-group-default'],
        'labelOptions' => [
            'class' => 'control-label label-lg'
        ],
    ];
    $disabledTemplate = $template;

    $form = ActiveForm::begin([
                'options' => [
                    'id' => 'create-specs-form',
                    'enableClentValidtaion' => true,
                    'enctype' => 'multipart/form-data'
                ]
    ]);
    ?>
    <div class="row">
        <div class="col-md-6">
            <?php
// echo $form->field($product, 'parts', $template)->widget(kartik\widgets\Select2::classname(), [
// 'data' => array_merge(["" => ""], ArrayHelper::map(Parts::find()->orderBy('name')->all(), function ($model) {
// return (string) $model->_id;
// }, 'name')),
// 'options' => ['placeholder' => 'Select Associated Products', 'multiple' => true],
// 'pluginOptions' => [
// 'allowClear' => true
// ],
// ]);
            ?>



            <?php
            $parts_all = Parts::find()->all();
            $parts_selected = Products::find()->where(['_id' => $product->_id])->one()['parts'];
            $remover = [];
            if ($parts_selected != NULL && is_array($parts_selected)) {
                foreach ($parts_selected as $part) {
                    $remover[(string) $part['_id']] = $part['_id'];
                }
                unset($parts_selected);
            }

            $partArray = [];
            if ($parts_all != NULL && is_array($parts_all)) {
                foreach ($parts_all as $k => $part) {
                    if (isset($remover[(string) $part['_id']])) {
                        $partArray[(string) $part['_id']] = $part['name'];
                    }
                }
                unset($parts_all);
                unset($remover);
            }
            ?>

            <?=
            $form->field($model, 'parts', $template)->widget(kartik\widgets\Select2::classname(), [
                'data' => array_merge(["" => ""], $partArray),
                'options' => ['placeholder' => 'Select Associated Parts', 'multiple' => false, 'disabled' => true],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'quan', $template); ?>
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
</div>
<input type="hidden" id="last-parent-id" value="">
<?php ActiveForm::end(); ?>
