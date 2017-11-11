<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<?= Html::a('
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 margin-bottom-20">
        <div class="products-grid">
            <div class="products-img-border">
                <div class="products-grid-wrapper thumbnail">
                <h3>' . $model->form_title . '</h3>
                <p>' . $model->desc . '</p>
                </div>

            </div>
        </div>
    </div>
', ['maintenance-forms/view', 'id' => (string) $model->_id], ['title' => Html::encode($model->form_title)]);
?>
