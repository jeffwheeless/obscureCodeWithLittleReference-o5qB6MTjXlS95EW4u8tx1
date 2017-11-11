<?php

 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Companies;
use yii\helpers\Url;
use \kartik\widgets\DatePicker;

//$this->title = $model->isNewRecord ? 'Adding New Company' : 'Updating ' . Html::encode($model->name);

/* @var $this yii\web\View */
/* @var $model frontend\models\Companies */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="create-company-form">
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
                    'id' => 'create-company-form',
                    'enableClentValidtaion' => true,
                    'enctype' => 'multipart/form-data'
                ]
    ]);
    ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'type', $template)->label('Type'); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'version', $template)->label('Version'); ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'date', $template)->widget(DatePicker::classname(), [
                'options' => [
                    'class' => 'form-control',
                    'readonly' => 'true',
                    'placeholder' => 'Click to Select',
                    'style' => 'cursor: pointer;'
                ],
                'pluginOptions' => [
                    'autoclose' => true,
                    'convertFormat' => true,
                    'format' => 'MM dd, yyyy',
                    'todayBtn' => true,
                    'todayHighlight' => true,
                    'endDate' => date('M jS, Y', strtotime(date('M jS, Y') . '+ 1 years')),
                ],
            ])->label('Release Date');
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'notes', $template)->textarea(['style' => 'resize:vertical; min-height:100px;max-height:300px;'])->label('Notes'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?=
            $form->field($model, 'can_delete', $template)->dropDownList([0 => 'No', 1 => 'Yes'], ['placeholder' => 'Select Yes or No'])
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