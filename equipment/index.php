<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\EquipmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Equipments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-12">
    <?= Html::button('Add New Equipment', ['value' => Url::to(['equipment/create']), 'title' => 'Adding New Equipment', 'class' => 'showModalButton btn btn-success']); ?>

    <div class="row margin-bottom-20">
        <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <?php
    $noEquipmentButton = Html::a('<div class="col-lg-12 margin-bottom-15"><div class="row"><div class="col-lg-4 col-lg-offset-4 col-sm-offset-3 col-sm-6 col-xs-12" style="cursor:pointer;"><div class="companies-grid"><div class="companies-grid-wrapper"><small>There is no equipment in the system.</small> <i>Click to add your equipment</i></div></div></div></div></div>', FALSE, ['value' => Url::to(['equipment/create']), 'title' => 'Creating New Equipment', 'class' => 'showModalButton']);
    $newEquipmentButton = Html::a('<div class="col-lg-12 margin-bottom-15"><div class="row"><div class="col-lg-4 col-lg-offset-4 col-sm-offset-3 col-sm-6 col-xs-12"><div class="companies-grid"><div class="companies-grid-wrapper" style="top:0"><small>Click to add</small> <i>' . $newcompanyname . '</i><div style="line-height:10px"><small>To the companies list, you can also add and edit details on the next screen</small></div></div></div></div></div>', '#', ['data-toggle' => 'modal', 'data-target' => '#createNewEquipment']);
    echo
    ListView::widget([
        'dataProvider' => $dataProvider,
        'emptyText' => $newequipmentname ? $newEquipmentButton : $noEquipmentButton,
        'options' => [
            'class' => 'companies-list  ',
        ],
        'layout' => '{items}{pager}',
        'emptyTextOptions' => ['class' => 'test'],
        'summaryOptions' => ['class' => 'summary'],
        'pager' => [
            'options' => ['class' => 'pagination'],
            'maxButtonCount' => '8',
            'firstPageLabel' => FALSE,
            'lastPageLabel' => FALSE,
            'nextPageLabel' => '<i class="fa fa-chevron-right"></i> ',
            'prevPageLabel' => '<i class="fa fa-chevron-left"></i>',
        ],
        'itemOptions' => ['class' => 'product-list-item'],
        'itemView' => '_grid',
    ]);
    ?>
</div>
