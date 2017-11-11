<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\MaintenanceCompleted */

// $this->title = $model->_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('maintenancecompleted', 'Maintenance Completeds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="maintenance-completed-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <p>
        <?= Html::a(Yii::t('maintenancecompleted', 'Update'), ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('maintenancecompleted', 'Delete'), ['delete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('maintenancecompleted', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p> -->

    <div class="row">
        <div class="col-md-offset-1 col-md-10" style="">
            <div class="panel panel-default">
                <div class="panel-heading separator">
                    <div class="panel-title">
                        <?= $model->form['title']; ?>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span style="font-style: italic; font-size:85%;"><?= $model->form['desc']; ?></span>
                        </div>
                    </div>
                    <hr/>
                    <?php foreach ($model->answer as $key => $answer) : ?>
                        <!-- <div class="row"> -->
                            <div class="<?= $answer['question']['class'] != '' ? $answer['question']['class'] : 'col-md-12'; ?>">
                                <?php
                                $error = is_array($answer['notes']) ? 'has-error' : '';
                                $req = $answer['question']['required'] == '1' ? '' : 'required';
                                echo '<div class="form-group form-group-default '.$required.' '.$error.'">';
                                    echo '<label class="control-label label-lg">';
                                        echo $answer['question']['label'];
                                        echo $answer['question']['max'] != '' ? ' <span style="font-size:70%;">('.strlen($answer['answer']).' of '.$answer['question']['max'].' characters)</span>' : '';
                                    echo '</label>';
                                    echo '<p class="form-control">';
                                        if ($answer['question']['type'] != 'dropdown')
                                            echo $answer['answer'];
                                        else {
                                            echo $answer['question']['dropdowns'][$answer['answer']]['value'];
                                        }
                                    echo '</p>';
                                    echo '<div class="help-block">';
                                        if ($answer['question']['helptext'] != '') {
                                            echo 'Help Text: '.$answer['question']['helptext'].'<br/>';
                                        }
                                        if (is_array($answer['notes'])) {
                                            foreach ($answer['notes'] as $key => $note) {
                                                echo '<hr/>';
                                                echo 'Issue: '.$note;
                                            }
                                        }
                                        if (is_array($answer['revision'])) {
                                            foreach ($answer['revision'] as $key => $revision) {
                                                echo '<hr/>';
                                                echo '<b>Revision on '.$revision['date'].'</b><br/><b>Reason: </b>'.$revision['reason'];
                                                if ($revision['note'] != '')
                                                    echo '<br/><b>Note: </b>'.$revision['note'];
                                            }
                                        }
                                        echo Html::a('Revise Answer', false, ['value' => Url::to(['maintenance-completed/update', 'id' => (string) $model['_id'], 'a_id' => (string) $answer['_id']]),
                                            'style' => 'margin-top:-5px; margin-right:5px; cursor:pointer;',
                                            'title' => "Revising ".$answer['question']['label'],
                                            'class' => 'showModalButton pull-right',
                                            'id' => 'modalButton'
                                        ]);
                                    echo '</div>';
                                echo '</div>';
                                ?>
                            </div>
                        <!-- </div> -->
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
