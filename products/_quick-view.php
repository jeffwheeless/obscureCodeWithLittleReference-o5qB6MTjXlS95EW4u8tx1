<?php


use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\Companies */

$this->title = $model->model;
?>
<div class="row">
    <div class="col-md-9  b-r b-grey ">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-md-3">
        <div class="full-height">
            <?= Html::img(Url::to(['file/file', 'id' => (string) $model->_id]), ['class' => 'img-thumbnail bg-white', 'alt' => $model->model]); ?>
            <hr/>
        </div>
    </div>
</div>