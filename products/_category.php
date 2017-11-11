<?php

 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Categories;

//$this->title = $model->isNewRecord ? 'Adding New Company' : 'Updating ' . Html::encode($model->name);

/* @var $this yii\web\View */
/* @var $model frontend\models\Companies */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="create-category-form">
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
                    'id' => 'create-company-form',
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
    <div class="row">
        <div class="col-md-12">
            <?php
            echo $form->field($model, 'name', $template)->widget(kartik\widgets\Select2::classname(), [
                'data' => array_merge(["" => ""], $new),
                'options' => ['placeholder' => 'Select Product\'s Categories', 'multiple' => false],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
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
</div>
<input type="hidden" id="last-parent-id" value="">
<?php ActiveForm::end(); ?>
