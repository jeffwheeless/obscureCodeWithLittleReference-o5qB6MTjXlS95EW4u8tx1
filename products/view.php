<?php

 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use frontend\models\PartsSearch;
use frontend\models\Parts;
use frontend\models\Status;
use frontend\models\Categories;

/* @var $this yii\web\View */
/* @var $model frontend\models\Companies */

$this->title = $model->model;
$this->params['breadcrumbs'][] = ['label' => Yii::t('products', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row full-height no-margin">
    <div class="col-md-9  b-r b-grey full-height">
        <h1><?= Html::encode($this->title) ?><div class="btn-group dropdown-default pull-right">
                <?= Html::button(Yii::t('products', 'Product Actions' . '<span class="caret"></span>'), ['data-toggle' => 'dropdown', 'title' => 'Updating ' . $model->model, 'class' => 'btn btn-default btn-lg dropdown-toggle']); ?>
                <ul class="dropdown-menu" role="menu">
                    <li><?= Html::a(Yii::t('products', 'Add to Equipment'), FALSE, ['style' => 'cursor:pointer', 'value' => Url::to(['equipment/create', 'product' => (string) $model->_id]), 'title' => 'Add Parts for ' . $model->model, 'class' => 'showModalButton', 'id' => 'modalButton']); ?></li>
                    <li class="divider"></li>
                    <li><?= Html::a(Yii::t('products', 'Add Parts'), FALSE, ['style' => 'cursor:pointer', 'value' => Url::to(['products/parts', 'id' => (string) $model->_id]), 'title' => 'Add Parts for ' . $model->model, 'class' => 'showModalButton', 'id' => 'modalButton']); ?></li>
                    <li><?= Html::a(Yii::t('products', 'Add Category'), FALSE, ['style' => 'cursor:pointer', 'value' => Url::to(['products/category', 'id' => (string) $model->_id]), 'title' => 'Add Categories for ' . $model->model, 'class' => 'showModalButton', 'id' => 'modalButton']); ?></li>
                    <li><?= Html::a(Yii::t('products', 'Add File'), FALSE, ['style' => 'cursor:pointer', 'value' => Url::to(['products/create-file', 'id' => (string) $model->_id]), 'title' => 'Add File for ' . $model->model, 'class' => 'showModalButton', 'id' => 'modalButton']); ?></li>
                    <li><?= Html::a(Yii::t('products', 'Add Specifications'), FALSE, ['style' => 'cursor:pointer', 'value' => Url::to(['products/create-specifications', 'id' => (string) $model->_id]), 'title' => 'Add Specifications for ' . $model->model, 'class' => 'showModalButton', 'id' => 'modalButton']); ?></li>
                    <li><?= Html::a(Yii::t('products', 'Add Firmware'), FALSE, ['style' => 'cursor:pointer', 'value' => Url::to(['products/create-firmware', 'id' => (string) $model->_id]), 'title' => 'Add Firmware for ' . $model->model, 'class' => 'showModalButton', 'id' => 'modalButton']); ?></li>
                    <li><?= Html::a(Yii::t('products', 'Add Maintenance Form'), FALSE, ['style' => 'cursor:pointer', 'value' => Url::to(['products/create-maintenance', 'id' => (string) $model->_id]), 'title' => 'Add Maintenance Form for ' . $model->model, 'class' => 'showModalButton', 'id' => 'modalButton']); ?></li>
                    <li class="divider"></li>
                    <li><?= Html::a(Yii::t('products', 'Update Product'), FALSE, ['style' => 'cursor:pointer', 'value' => Url::to(['products/update', 'id' => (string) $model->_id]), 'title' => 'Updating ' . $model->model, 'class' => 'showModalButton', 'id' => 'modalButton']); ?></li>
                    <li><?= Html::a(Yii::t('products', 'Delete Product'), ['delete', 'id' => (string) $model->_id], ['style' => 'cursor:pointer', 'data' => ['confirm' => Yii::t('app', 'Are you sure you want to delete this company? <br><br> Also, if there are any other products, products, parts, contacts, or other records associated with this item the system will not delete it. You will be notified if this happens.'), 'method' => 'post']]) ?></li>
                </ul>
            </div></h1>
        <ul class="nav nav-tabs nav-tabs-fillup" role="tablist">
            <li class="active"><a href="#productsTab" data-toggle="tab" role="tab">Parts</a>
            </li>
            <li class=""><a href="#specsTab" data-toggle="tab" role="tab">Specs & Firmware</a>
            </li>
            <li class=""><a href="#tab2Inspire" data-toggle="tab" role="tab">Files</a>
            </li>
            <li class=""><a href="#maintenanceforms" data-toggle="tab" role="tab">Maintenance</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active slide-left" id="productsTab">
                <div class="row">
                    <div class="col-md-12">
                        <h3>Required Parts & Accessories</h3>
                        <p><i>(Parts & Accessories Required for <?php
                                $status = Status::find()->where(['severity' => '1'])->one();
                                echo $status->name_long;
                                ?> Status)</i></p>
                    </div>
                    <?php if (isset($parts) && !empty($parts) && is_array($parts)): ?>
                        <?php
                        $new_list = [];
                        foreach ($parts as $part) {
                            $new_list[$part['req']][(string) $part['_id']] = $part;
                        }
                        $parts = $new_list;
                        $part_list = [];
                        $parts_all = Parts::find()->all();
                        foreach ($parts_all as $k) {
                            $part_list[(string) $k['_id']] = $k['name'];
                        }
                        ?>
                        <?php if (isset($parts[0]) && !empty($parts[0])): ?>
                            <?php foreach ($parts[0] as $key => $part): ?>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 margin-bottom-20">
                                    <div class="products-grid">
                                        <div class="products-img-border">
                                            <div class="products-grid-wrapper">

                                                <div data-pages="portlet" class="panel panel-default" id="portlet-basic">
                                                    <div class="panel-heading ">
                                                        <div class="panel-title">Part Name: <?php echo $part_list[(string) $part['parts']]; ?>
                                                        </div>
                                                        <div class="panel-controls">
                                                            <ul>
                                                                <li><a data-toggle="collapse" class="portlet-collapse" href="#"><i class="pg-arrow_maximize" style="margin:0 4px 2px 0"></i></a>
                                                                </li>
                                                                <li><?=
                                                                    Html::a('<i class="fa fa-edit"></i>', false, ['value' => Url::to(['products/update-part', 'id' => (string) $part['_id']]),
                                                                        'style' => 'margin-top:-5px; margin-right:5px; cursor:pointer;',
                                                                        'title' => 'Update part for ' . $model->model,
                                                                        'class' => 'showModalButton pull-right',
                                                                        'id' => 'modalButton'
                                                                    ]);
                                                                    ?>
                                                                </li>
                                                                <li><?=
                                                                    Html::a('<i class="fa fa-times"></i>', ['delete-part', 'id' => (string) $part['_id']], [
                                                                        'style' => 'margin-top:-5px;',
                                                                        'class' => 'pull-right',
                                                                        'data' => [
                                                                            'confirm' => Yii::t('app', '<br>Are you sure you want to remove this part from this product?'),
                                                                            'method' => 'post',
                                                                        ],
                                                                    ])
                                                                    ?>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div style="display: block;" class="panel-body">
                                                        <h5>
                                                            <span class="semi-bold">Suggested Quantity:</span> <?= $part['quan']; ?></h5>
                                                        <p><b>Description:</b><br> <?= (!empty($part['desc'])) ? $part['desc'] : '(No description)'; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Recommended Parts</h3>
                        <p><i>(Parts & Accessories Recommended for Optimal Usage)</i></p>
                    </div>
                    <?php if (isset($parts) && !empty($parts) && is_array($parts)): ?>
                        <?php if (isset($parts[1]) && !empty($parts[1])): ?>
                            <?php foreach ($parts[1] as $key => $part): ?>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 margin-bottom-20">
                                    <div class="products-grid">
                                        <div class="products-img-border">
                                            <div class="products-grid-wrapper">

                                                <div data-pages="portlet" class="panel panel-default" id="portlet-basic">
                                                    <div class="panel-heading ">
                                                        <div class="panel-title">Part Name: <?php echo $part_list[(string) $part['parts']]; ?>
                                                        </div>
                                                        <div class="panel-controls">
                                                            <ul>
                                                                <li><a data-toggle="collapse" class="portlet-collapse" href="#"><i class="pg-arrow_maximize" style="margin:0 4px 2px 0"></i></a>
                                                                </li>
                                                                <li><?=
                                                                    Html::a('<i class="fa fa-edit"></i>', false, ['value' => Url::to(['products/update-part', 'id' => (string) $part['_id']]),
                                                                        'style' => 'margin-top:-5px; margin-right:5px; cursor:pointer;',
                                                                        'title' => 'Update part for ' . $model->model,
                                                                        'class' => 'showModalButton pull-right',
                                                                        'id' => 'modalButton'
                                                                    ]);
                                                                    ?>
                                                                </li>
                                                                <li><?=
                                                                    Html::a('<i class="fa fa-times"></i>', ['delete-part', 'id' => (string) $part['_id']], [
                                                                        'style' => 'margin-top:-5px;',
                                                                        'class' => 'pull-right',
                                                                        'data' => [
                                                                            'confirm' => Yii::t('app', '<br>Are you sure you want to remove this part from this product?'),
                                                                            'method' => 'post',
                                                                        ],
                                                                    ])
                                                                    ?>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div style="display: block;" class="panel-body">
                                                        <h4>
                                                            <span class="semi-bold">Suggested Quantity:</span> <?= $part['quan']; ?></h4>
                                                        <p><b>Description:</b><br> <?= (isset($part['desc'])) ? $part['desc'] : '(No description)'; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="tab-pane slide-left" id="specsTab">
                <div class="row column-seperation">
                    <div class="col-md-6">
                        <h3>
                            <span class="semi-bold">Specifications:</span>
                        </h3>
                        <?php if ($specs != NULL && $specs != ''): ?>
                            <?php foreach ($specs as $key => $spec): ?><div class="col-xs-12">
                                    <div class="specifications-grid">
                                        <div class="specifications-img-border">
                                            <div class="specifications-grid-wrapper">
                                                <div data-pages="portlet" class="panel panel-default" id="portlet-basic">
                                                    <div class="panel-heading ">
                                                        <div class="panel-title"><?php echo $spec['name'] . ': ' . $spec['value']; ?>
                                                        </div>
                                                        <div class="panel-controls">
                                                            <ul>
                                                                <?php if (isset($spec['notes']) && !empty($spec['notes']) && $spec['notes'] != ""): ?>
                                                                    <li><a data-toggle="collapse" class="portlet-collapse" href="#"><i class="pg-arrow_maximize" style="margin:0 4px 2px 0"></i></a>
                                                                    </li>
                                                                <?php endif; ?>

                                                                <li><?=
                                                                    Html::a('<i class="fa fa-times"></i>', ['delete-specifications', 'id' => (string) $spec['_id']], [
                                                                        'style' => 'margin-top:-5px;',
                                                                        'class' => 'pull-right',
                                                                        'data' => [
                                                                            'confirm' => Yii::t('app', '<br>Are you sure you want to delete this specification?'),
                                                                            'method' => 'post',
                                                                        ],
                                                                    ])
                                                                    ?></li>
                                                                <li><?=
                                                                    Html::a('<i class="fa fa-edit"></i>', false, ['value' => Url::to(['products/update-specifications', 'id' => (string) $spec['_id']]),
                                                                        'style' => 'margin-top:-5px; margin-right:5px; cursor:pointer;',
                                                                        'title' => 'Update specification for ' . $model->model,
                                                                        'class' => 'showModalButton pull-right',
                                                                        'id' => 'modalButton'
                                                                    ]);
                                                                    ?></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <?php if (isset($spec['notes']) && !empty($spec['notes']) && $spec['notes'] != ""): ?>
                                                        <div style="display: none;" class="panel-body">
                                                            <p>Notes:<br> <?= $spec['notes']; ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <h3 class="semi-bold">Firmware</h3>
                        <?php if ($firmware != NULL && $firmware != ''): ?>
                            <?php foreach ($firmware as $key => $firms): ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading separator">

                                        <?=
                                        Html::a('<i class="fa fa-times"></i>', ['delete-firmware', 'id' => (string) $firms['_id']], [
                                            'style' => 'margin-top:-5px;',
                                            'class' => 'pull-right',
                                            'data' => [
                                                'confirm' => Yii::t('app', '<br>Are you sure you want to delete this firmware?'),
                                                'method' => 'post',
                                            ],
                                        ])
                                        ?>
                                        <?=
                                        Html::a('<i class="fa fa-edit"></i>', false, ['value' => Url::to(['products/update-firmware', 'id' => (string) $firms['_id']]),
                                            'style' => 'margin-top:-5px; margin-right:5px; cursor:pointer;',
                                            'title' => 'Update firmware for ' . $model->model,
                                            'class' => 'showModalButton pull-right',
                                            'id' => 'modalButton'
                                        ]);
                                        ?>

                                        <div class="panel-title">
                                            Type: <?= $firms['type'] . ' (Ver. ' . $firms['version'] . ')' ?>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <h3>
                                            <span class="semi-bold">Release Date:: </span> <?= $firms['date']; ?></h3>
                                        <div class="row">

                                            <div class="col-md-12">
                                                <p>
                                                <p><b>Notes: </b> <?= ($firms['notes'] != '') ? $firms['notes'] : '(No Notes)'; ?> </p>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
            <div class="tab-pane slide-left" id="tab2Inspire">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="semi-bold">Files</h3>
                        <?php if ($files != NULL && $files != ''): ?>
                            <?php foreach ($files as $key => $file_list): ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading separator">

                                        <?=
                                        Html::a('<i class="fa fa-times"></i>', ['delete-file', 'id' => (string) $file_list['_id']], [
                                            'style' => 'margin-top:-5px;',
                                            'class' => 'pull-right',
                                            'data' => [
                                                'confirm' => Yii::t('app', '<br>Are you sure you want to delete this firmware?'),
                                                'method' => 'post',
                                            ],
                                        ])
                                        ?>
                                        <?=
                                        Html::a('<i class="fa fa-edit"></i>', false, ['value' => Url::to(['products/update-file', 'id' => (string) $file_list['_id']]),
                                            'style' => 'margin-top:-5px; margin-right:5px; cursor:pointer;',
                                            'title' => 'Update firmware for ' . $model->model,
                                            'class' => 'showModalButton pull-right',
                                            'id' => 'modalButton'
                                        ]);
                                        ?>

                                        <div class="panel-title">

                                            File Name:  <?= Html::a($file_list['file_name'], ['file/file', 'id' => (string) $file_list['_id']], ['alt' => $file_list['desc'], 'target' => '_blank']); ?>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <h3>
                                            <span class="semi-bold">Release Date:: </span> <?= $file_list['date']; ?></h3>
                                        <div class="row">

                                            <div class="col-md-12">
                                                <p>
                                                <p><b>Notes: </b> <?= ($file_list['desc'] != '') ? $file_list['desc'] : '(No Description)'; ?> </p>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane slide-left" id="maintenanceforms">
                <?php
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
                    'itemView' => '_display_form',
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-3 full-height">
        <div class="full-height">
            <?= Html::img(Url::to(['file/file', 'id' => (string) $model->_id]), ['class' => 'img-thumbnail bg-white', 'alt' => $model->model]); ?>
            <hr/>
            <h2>Categories</h2>

            <?php if (is_array($categories)): ?>
                <?php
                $product_cat = [];
                foreach ($model->categories as $key => $category) {
                    $product_cat[$category['name']] = $category['_id'];
                }
                $initial = Categories::find()->all();

                function parentChildSort_r($idField, $parentField, $els, $parentID = 0, &$result = array(), &$depth = 0) {
                    foreach ($els as $key => $value):
                        if ($value[$parentField] == $parentID) {
                            $value['root'] = $depth;
                            array_push($result, $value);
                            unset($els[$key]);
                            $oldParent = $parentID;
                            $parentID = $value[$idField];
                            $depth++;
                            parentChildSort_r($idField, $parentField, $els, $parentID, $result, $depth);
                            $parentID = $oldParent;
                            $depth--;
                        }
                    endforeach;
                    return $result;
                }

                $result = parentChildSort_r('ID', 'parent_cat', $initial);

                $full_name = array();
                foreach ($result as $key => $category) {
                    $full_name[$category['root']] = $category['name'];
                    if (isset($product_cat[(string) $category['_id']])) {
                        $cat_name = $full_name[0];
                        for ($x = $category['root'] == 1 ? $category['root'] : $category['root'] - 1; $x <= $category['root'] && $category['root'] >= 1; $x++) {
                            $cat_name = $cat_name . ' - ' . $full_name[$x];
                        }
                        $cat_name = '<span style="color:green"><i class="fa fa-check"></i> ' . $cat_name . '</span>'
                                . Html::a('<i class="fa fa-times"></i>', ['delete-category', 'id' => (string) $product_cat[(string) $category['_id']]], [
                                    'style' => 'margin-top:-5px;',
                                    'class' => 'pull-right',
                                    'title' => 'Delete Category ' . $category['name'],
                                    'data' => [
                                        'confirm' => Yii::t('app', '<br>Are you sure you want to delete this subcategory?'),
                                        'method' => 'post',
                                    ],
                        ]);
                        echo '<li class="dd-item" data-id="' . (string) $category['_id'] . '">'
                        . '<div class="dd-handle">'
                        . $cat_name
                        . '</div>'
                        . '</li>';
                    }
                }
                ?>
            <?php endif; ?>
        </div>
    </div>
