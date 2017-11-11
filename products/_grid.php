<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 m-b-20">
    <div class="opready-grid">
        <div class="opready-grid-wrapper">
            <h3 class="opready-grid-author">
                by
                <?php
                $currentPath = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
                echo $currentPath === 'companies/view' ? $model->mfg_name : Html::a('MFG: ' . $model->mfg_name, ['companies/view', 'id' => (string) $model->mfg], ['title' => $model->model]);
                ?>
            </h3>
            <?= Html::a(Html::img(Url::to(['file/file', 'id' => (string) $model->_id]), ['alt' => $model->model, 'class' => 'img-responsive']) . '
            <h4>' . $model->model . '</h4>', ['products/view', 'id' => (string) $model->_id]); ?>
        </div>
        <?=
        Html::a('
            <div class="opready-grid-btn-col">
                <i class="fa pg-suitcase"></i>
                <p>Add to Equipment</p>
            </div>'
                , FALSE, ['title' => 'Add to Equipment',
            'value' => Url::to(['equipment/create', 'product' => (string) $model->_id]),
            'title' => 'Addding a ' . $model->model . ' to equipment', 'class' => 'showModalButton', 'id' => 'modalButton']);
        ?>
        <?=
        Html::a('
                <div class = "opready-grid-btn-col">
                <i class = "fa"> ' . Yii::$app->formatter->asInteger('525') . ' </i>
                <p>Total Amount Owned</p>
                </div>  '
                , FALSE, ['title' => 'Total ' . $model->model . ' owned']);
        ?>
        <?=
        Html::a('
                <div class = "opready-grid-btn-col">
                <i class = "fa  pg-search"></i>
                <p>Quick Overview</p>
                </div>'
                , FALSE, ['title' => 'Quick Overview',
            'value' => Url::to(['products/quick-view', 'id' => (string) $model->_id]),
            'title' => "<button type='button' class='close pull-right' data-dismiss='modal' style='margin-top: -40px; color:#000000;opacity: 1;' aria-hidden='true'>×</button>", 'class' => 'showFullModalButton', 'id' => 'fullModalButton']);
        ?>
    </div>
</div>
