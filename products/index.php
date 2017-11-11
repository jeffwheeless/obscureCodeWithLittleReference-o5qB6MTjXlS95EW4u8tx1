<?php

 
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CompaniesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products');
?>

<div class="col-lg-12">
    <?= Html::button('Add New Product', ['value' => Url::to(['products/create']), 'title' => 'Adding New Product', 'class' => 'showModalButton btn btn-success']); ?>
    <?= Html::button('Add New Part', ['value' => Url::to(['parts/create']), 'title' => 'Adding New Part', 'class' => 'showModalButton btn btn-success']); ?>
    <div class="row margin-bottom-20">
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <?php
    $noProductsButton = Html::a('<div class="col-lg-12 margin-bottom-15"><div class="row"><div class="col-lg-4 col-lg-offset-4 col-sm-offset-3 col-sm-6 col-xs-12"><div class="companies-grid"><div class="companies-grid-wrapper"><small>There aren\'t any companies in the system.</small> <i>Click to add your first company</i></div></div></div></div></div>', FALSE, ['value' => Url::to(['companies/create']), 'title' => 'Creating New Products', 'class' => 'showModalButton']);
    $newProductsButton = Html::a('<div class="col-lg-12 margin-bottom-15"><div class="row"><div class="col-lg-4 col-lg-offset-4 col-sm-offset-3 col-sm-6 col-xs-12"><div class="companies-grid"><div class="companies-grid-wrapper" style="top:0"><small>Click to add</small> <i>' . $newcompanyname . '</i><div style="line-height:10px"><small>To the companies list, you can also add and edit details on the next screen</small></div></div></div></div></div>', '#', ['data-toggle' => 'modal', 'data-target' => '#createNewProducts']);
    echo
    ListView::widget([
        'dataProvider' => $dataProvider,
        'emptyText' => $newcompanyname ? $newProductsButton : $noProductsButton,
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