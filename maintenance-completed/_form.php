<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Maintenance;
use \kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model frontend\models\MaintenanceCompleted */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="maintenance-completed-form">

    <?php
    // echo '<h3>'.$form_obj->form_title.'</h3>';
    if ($form_obj->desc != '')
        echo '<p>Form Description: '.$form_obj->desc.'</p><hr>';
    ?>
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
            'id' => 'create-case-form',
            'enableClientValidtaion' => true,
            'enctype' => 'multipart/form-data'
        ]
    ]);
    ?>


    <?php foreach($questions as $key => $question) : ?>
        <?php $label = (!isset($question['helptext']) || empty($question['helptext'])) ? $question['label'] : $question['label'].' - <span style="font-style:italic; font-size: 80%;">'.$question['helptext'].'</span>'; ?>
        <!-- <div class="row"> -->
            <div class="<?= $question['class']!='' ? $question['class'] : 'col-md-12'; ?>">
                <?php
                $element = $form->field($answers, 'answer['.(string) $question['_id'].']', $template)->label($label);
                switch (strtolower($question['type'])) {
                    case 'dropdown':
                        $dropdown_list = array();
                        foreach($question['dropdowns'] as $key => $dropdown) {
                            $dropdown_list[$key] = $dropdown['value'];
                        }
                        $element->widget(kartik\widgets\Select2::classname(), [
                            'data' => array_merge(["" => ""], $dropdown_list),
                            'options' => ['placeholder' => '--Select an Option--'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        break;

                    case 'textarea':
                        $element->textarea(['style' => 'resize:vertical; min-height:100px;max-height:300px;']);
                        break;

                    case 'todaydate':
                        $element = '<div class="form-group form-group-default field-maintenanceanswers-answer-'.(string) $question['_id'].' has-success"><label class="control-label label-lg" for="maintenanceanswers-answer-'.(string) $question['_id'].'">'.$label.'</label><input id="maintenanceanswers-answer-'.(string) $question['_id'].'" class="form-control" name="MaintenanceAnswers[answer]['.(string) $question['_id'].']" type="text" value="'.date('Y/m/d', time()).'" disabled=""><div class="help-block"></div></div>';
                        break;

                    case 'date':
                        $element->widget(DatePicker::classname(), [
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
                        ]);
                        break;

                    default:
                        // NOTHING
                        break;
                }
                echo $element;
                ?>
            </div>
        <!-- </div> -->
    <?php endforeach; ?>

<div class="row">
    <div class="row">
        <div class="col-md-6">
            <?= Html::submitButton('Save & Update', ['class' => 'margin-bottom-20 btn btn-lg btn-block btn-raised btn-success']) ?>
        </div>
        <div class="col-md-6">
            <?= Html::button('Cancel & Close', ['class' => 'btn btn-lg  btn-block btn-raised btn-danger margin-bottom-20 ', 'data-dismiss' => 'modal']) ?>
        </div>
    </div>
</div>

    <?php ActiveForm::end(); ?>

</div>
