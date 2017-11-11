<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php

$color = isset($model->statusInfo->hexcolor) ? $model->statusInfo->hexcolor : 'F0F0F0';
if (!empty($model->case_id)) {
    $case = '</h3><br/><p>Assigned to Case: ' . $model->caseName->name . '</p>';
} else {
    $case = '</h3><br/><p>Standalone</p>';
}
echo Html::a('
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 margin-bottom-15">
        <div class="products-grid">
            <div class="products-img-border">
                <div class="products-grid-wrapper thumbnail" style="text-align:center; border: #' . $color . ' 5px solid;">
               ' . Html::img(Url::to(['file/file', 'id' => ($model->hasphoto == 1) ? (string) $model->_id : (string) $model->product]), ['class' => ' bg-white', 'alt' => $model->sn]) . '
                <br/>
                <h3>' . $model->productInfo->model . ': ' . $model->sn . '' . $case . '</div>
            </div>
        </div>
    </div>
', ['equipment/view', 'id' => (string) $model->_id], ['title' => Html::encode($model->sn)]);
?>
