<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\MaintenanceFormsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('maintenanceforms', 'Maintenance Forms');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="maintenance-forms-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
<?= Html::button('Add New Form', ['value' => Url::to(['maintenance-forms/create']), 'title' => 'Create Maintenance Form', 'class' => 'showModalButton btn btn-success']); ?>
    </p>

    <?php
    // $noProductButton = Html::a('<div class="col-lg-12 margin-bottom-15"><div class="row"><div class="col-lg-4 col-lg-offset-4 col-sm-offset-3 col-sm-6 col-xs-12"><div class="products-grid"><div class="products-grid-wrapper"><small>There aren\'t any products in the system.</small> <i>Click to add your first product</i></div></div></div></div></div>', FALSE, ['value' => Url::to(['products/create']), 'title' => 'Creating New Product', 'class' => 'showModalButton']);
    // $newProductButton = Html::a('<div class="col-lg-12 margin-bottom-15"><div class="row"><div class="col-lg-4 col-lg-offset-4 col-sm-offset-3 col-sm-6 col-xs-12"><div class="products-grid"><div class="products-grid-wrapper" style="top:0"><small>Click to add</small> <i>' . $newproductname . '</i><div style="line-height:10px"><small>To the products list, you can also add and edit details on the next screen</small></div></div></div></div></div>', '#', ['data-toggle' => 'modal', 'data-target' => '#createNewProduct']);
    echo
    ListView::widget([
        'dataProvider' => $dataProvider,
        // 'emptyText' => $newproductname ? $newProductsButton : $noProductsButton,
        'options' => [
            'class' => 'products-list  ',
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
