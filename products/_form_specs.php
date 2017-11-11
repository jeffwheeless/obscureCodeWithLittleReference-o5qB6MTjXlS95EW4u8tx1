<?php

 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Companies;
use frontend\models\Specs;
use yii\helpers\Url;
use \kartik\widgets\DatePicker;

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
        <div class="col-md-6"><?=
            $form->field($model, 'name', $template)->widget(\yii\jui\AutoComplete::classname(), [
                'options' => ['class' => 'form-control', 'maxlength' => '50'],
                'clientOptions' => [
                    'source' => array_keys(Specs::find()->orderBy('freq DESC, name ASC')->indexBy('name')->asArray()->all()),
                ],
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'value', $template); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'notes', $template)->textarea(['style' => 'resize:vertical; min-height:100px;max-height:300px;']); ?>
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
