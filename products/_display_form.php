<?php

 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use frontend\models\PartsSearch;
use frontend\models\Inventory;
use frontend\models\Status;
use frontend\models\MaintenanceQuestions;
use yii\mongodb\Query;

/* @var $this yii\web\View */
/* @var $model frontend\models\Companies */

// $this->form_title = $model->model;
// $this->params['breadcrumbs'][] = ['label' => Yii::t('products', 'Products'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->form_title;
?>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">
                    <h5><?= $model->form_title; ?></h5>
                </div>
                <div class="panel-controls">
                    <ul>
                        <li>
                            <?= Html::button('Add Question', ['value' => Url::to(['products/add-question', 'id'=>(string) $model->_id]), 'title' => 'Create Form Question', 'class' => 'showModalButton btn btn-success']); ?>
                        </li>
                        <li>
                            <?php
                            echo Html::a('Delete Form', ['products/delete-maintenance', 'id' => (string) $model->_id], [
                                // 'style' => 'margin-top:-5px;',
                                'class' => 'btn btn-danger',
                                'title' => "Delete Entire Form",
                                'data' => [
                                    'confirm' => Yii::t('app', "<br>Are you sure you want to delete the form \"{$model->form_title}\"?"),
                                    'method' => 'post',
                                ],
                            ]);
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <?php
                if (isset($model->question) && !empty($model->question) && is_array($model->question)) {
                    foreach ($model->question as $question) : ?>
                        <div class="<?= $question['class']; ?>">
                            <div class="row">
                                <div style="text-align:right;">
                                    <?php
                                    echo Html::a('Update Question', false, ['value' => Url::to(['products/update-question', 'id' => (string) $question['_id']]),
                                        'style' => 'margin-top:-5px; margin-right:5px; cursor:pointer;',
                                        'title' => "Updating Question \"{$question['label']}\"",
                                        'class' => 'showModalButton pull-right',
                                        'id' => 'modalButton'
                                    ]);
                                    ?>
                                    <?php
                                    echo Html::a('Remove Question&nbsp;|&nbsp;', ['products/delete-question', 'id' => (string) $question['_id']], [
                                        'style' => 'margin-top:-5px;',
                                        'class' => 'pull-right',
                                        'title' => "Deleting Question \"{$question['label']}\"",
                                        'data' => [
                                            'confirm' => Yii::t('app', "<br>Are you sure you want to delete the question \"{$question['label']}\"?"),
                                            'method' => 'post',
                                        ],
                                    ]);
                                    ?>
                                    <?php
                                    if ($question['type'] == 'dropdown') {
                                        echo Html::a('Add Dropdown Option | ', false, ['value' => Url::to(['products/add-dropdown', 'id' => (string) $question['_id']]),
                                            'style' => 'margin-top:-5px; margin-right:5px; cursor:pointer;',
                                            'title' => "Adding Dropdowns to \"{$question['label']}\"",
                                            'class' => 'showModalButton pull-right',
                                            'id' => 'modalButton'
                                        ]);
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <?php
                                $height = 75;
                                if(is_array($question['dropdowns']))
                                    $height = (count($question['dropdowns'])*15)+$height;
                                    ?>
                                <div style="height: <?= $height; ?>px;" class="form-group form-group-default <?= $question['required'] == 1 ? 'required' : ''; ?>">
                                    <label class="control-label label-lg">
                                        <?php echo $question['label'].((!empty($question['max'])) ? ' <span style="font-size:70%;">(0 of '.$question['max'].' characters)</span>' : ''); ?>
                                    </label>
                                    <p class="form-control">
                                        <?php
                                        switch ($question['type']) {
                                            case 'dropdown':
                                                if (isset($question['dropdowns']) && !empty($question['dropdowns']) && is_array($question['dropdowns'])) {
                                                    $options = array();
                                                    foreach ($question['dropdowns'] as $dropdown)
                                                        $options[] = $dropdown;
                                                    asort($options);
                                                    if(!isset($status) || empty($status)) {
                                                        $status = Status::find()->all();
                                                        $stat_list = array();
                                                        foreach ($status as $stat) {
                                                            $stat_list[(string) $stat->_id] = $stat->name_long;
                                                        }
                                                        $status = $stat_list;
                                                    }
                                                    foreach ($options as $key => $option) {
                                                        echo Html::a('<i class="fa fa-remove" ></i>', ['remove-dropdown', 'id' => (string) $question['_id'], 'd_id' => (string) $key], [
                                                            'style' => 'margin-right:5px;',
                                                            'title' => "Deleting Option \"".$option['value']."\"",
                                                            'data' => [
                                                                'confirm' => Yii::t('app', "<br>Are you sure you want to delete the question \"{$question['label']}\"?"),
                                                                'method' => 'post',
                                                            ],
                                                        ]);
                                                        echo '<b>'.$option['value'].'</b> if selected equipment is '.$status[$option['status']];
                                                        echo '<br/>';
                                                    }
                                                }
                                                break;

                                            case 'date':
                                                echo 'DD Month YYYY';
                                                break;

                                            case 'todaydate':
                                                echo date("d M Y", time()); //'DD Month YYYY';
                                                break;

                                            default:
                                                break;
                                        }
                                        ?>
                                    </p>
                                    <!-- <div style="text-align:right; margin-top:-25px;"><span data-toggle="tooltip" data-placement="left" data-original-title="123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789"><i class="fa fa-question-circle"></i></span></div> -->
                                    <?php echo '<div class="help-block">'.$question['helptext'].'</div>'; ?>

                                </div>
                            </div>
                        </div>
                    <?php endforeach;
                } else {
                    unset($question_query);
                }
                ?>
            </div>
        </div>
    </div>
