<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\MaintenanceCompletedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('maintenancecompleted', 'Maintenance Completeds');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="maintenance-completed-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('maintenancecompleted', 'Create Maintenance Completed'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model->_id), ['view', 'id' => (string)$model->_id]);
        },
    ]) ?>

</div>
