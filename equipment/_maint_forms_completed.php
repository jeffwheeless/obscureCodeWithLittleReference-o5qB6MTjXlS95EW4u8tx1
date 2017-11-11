<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php
if ($model->rev != 0)
$body_text = '<h5>' . date('d M Y H:i:s', $model->date) . ' </h5><p>Revision: ' . $model->rev . ' on ' . date('d M Y H:i:s', $model->rev_date) . '</p>';
else
$body_text = '<h5>' . date('d M Y H:i:s', $model->date) . ' </h5><p>Never Revised</p>';
?>

<?= Html::a('
    <div class="col-md-4 col-sm-6 col-xs-12 margin-bottom-20">
        <div class="products-grid">
            <div class="products-img-border">
                <div class="products-grid-wrapper thumbnail">
                <!-- <h5>' . $model->form['title'] . ' on '.date('d M Y', $model->date).'</h5> -->
                '.$body_text.'
                </div>

            </div>
        </div>
    </div>
', ['maintenance-completed/view', 'id' => (string) $model->_id], ['class'=>'', 'title' => Html::encode($model->form['title'])]);
?>
