<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>


<?= Html::button(Yii::t('app', 'Update'), ['value' => Url::to(['products/update', 'id' => (string) $model->_id]), 'title' => 'Updating ' . $model->model, 'class' => 'showModalButton btn btn-success', 'id' => 'modalButton']); ?>
<?= Html::img(Url::to(['file/file', 'id' => (string) $model->_id]), ['class' => 'img-thumbnail bg-white', 'alt' => $model->model]); ?>
<?=

Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => (string) $model->_id], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => Yii::t('app', 'Are you sure you want to delete this product? <br><br> Also, if there are any equipment, parts, or other records associated with this item the system will not delete it. You will be notified if this happens.'),
        'method' => 'post',
    ],
])
?>


<?=

DetailView::widget([
    'model' => $model,
    'attributes' => [
        '_id',
        'mfg',
        'mfg_name',
        'model',
        'status',
    ],
])
?>
<?php

$searchParts = new PartsSearch();
$dataProvider = $searchParts->searchByProduct($model->_id);
echo ListView::widget([
    'dataProvider' => $dataProvider,
    'options' => [
        'class' => 'parts-list  ',
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
    'itemView' => '_parts',
]);
?>