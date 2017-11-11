<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Equipment;
use frontend\models\Locations;
use frontend\models\Parts;
use common\components\widgets\MaskedInputType;
use \kartik\widgets\DatePicker;

// $this->title = $model->isNewRecord ? 'Adding New Inventory' : 'Updating ' . Html::encode($model->ref_name);

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
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'reason', $template)->textarea(['style' => 'resize:vertical; min-height:100px;max-height:300px;']); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'note', $template)->textarea(['style' => 'resize:vertical; min-height:100px;max-height:300px;']); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= Html::submitButton('Save & Update', ['class' => 'margin-bottom-20 btn btn-lg btn-block btn-raised btn-success']) ?>
        </div>
        <div class="col-md-6">
            <?= Html::button('Cancel & Close', ['class' => 'btn btn-lg  btn-block btn-raised btn-danger margin-bottom-20 ', 'data-dismiss' => 'modal']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
