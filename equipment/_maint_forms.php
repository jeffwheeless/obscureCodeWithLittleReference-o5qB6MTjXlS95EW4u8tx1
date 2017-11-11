<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

    <div class="col-sm-6 col-xs-12 margin-bottom-20">
        <div class="products-grid">
            <div class="products-img-border">
            <div class="col-md-8">
                <div class="products-grid-wrapper thumbnail">
                <h5><?= $model->form_title; ?></h5>
                <p><?= $model->desc; ?>
                    <br/><br/><?= Html::a(Yii::t('maintenancecompleted', 'Use This Form'), FALSE, ['style' => 'cursor:pointer', 'value' => Url::to(['maintenance-completed/create', 'id' => (string) $model->_id, 'e_id' => $_GET['id']]), 'title'=>$model->form_title, 'class' => 'showModalButton btn btn-info', 'id' => 'modalButton']); ?>
            </p></div>
            <div class="col-md-4">
            </div>
            </div>
            </div>
        </div>
    </div>
