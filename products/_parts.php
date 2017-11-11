<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<?= Html::a('
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 margin-bottom-20">
        <div class="products-grid">
            <div class="products-img-border">
                <div class="products-grid-wrapper">
               Part Name: ' . $model->name . ' <br> Suggested Quantity:' . $model->quan . '
                </div>
            </div>
        </div>
    </div>
', ['parts/view', 'id' => (string) $model->_id], ['title' => Html::encode($model->name)]);
?>
