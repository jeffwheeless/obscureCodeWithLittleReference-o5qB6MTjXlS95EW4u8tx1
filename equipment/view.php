<?php

 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use frontend\models\PartsSearch;
use frontend\models\Inventory;
use frontend\models\Categories;
use yii\mongodb\Query;
use frontend\models\MaintenanceFormsSearch;
use frontend\models\MaintenanceCompletedSearch;

/* @var $this yii\web\View */
/* @var $model frontend\models\Companies */

// $this->title = $model->model;
// $this->params['breadcrumbs'][] = ['label' => Yii::t('products', 'Products'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="row full-height no-margin">
    <div class="col-md-9  b-r b-grey full-height">
        <div class="full-height placeholder">
            <h1><?= Html::encode($this->title) ?><div class="btn-group dropdown-default pull-right">
                    <?= Html::button(Yii::t('equipment', 'Equipment Actions' . '<span class="caret"></span>'), ['data-toggle' => 'dropdown', 'title' => 'Updating ' . $model->sn, 'class' => 'btn btn-default btn-lg dropdown-toggle']); ?>
                    <ul class="dropdown-menu" role="menu">
                        <li><?= Html::a(Yii::t('equipment', 'Add Existing Inventory'), FALSE, ['style' => 'cursor:pointer', 'value' => Url::to(['equipment/inventory', 'id' => (string) $model->_id]), 'title' => 'Add Existing Inventory for ' . $model->sn, 'class' => 'showModalButton', 'id' => 'modalButton']); ?></li>
                        <li><?= Html::a(Yii::t('equipment', 'Add New Inventory'), FALSE, ['style' => 'cursor:pointer', 'value' => Url::to(['equipment/inventory-new', 'id' => (string) $model->_id]), 'title' => 'Add New Inventory for ' . $model->sn, 'class' => 'showModalButton', 'id' => 'modalButton']); ?></li>
                        <li><?= Html::a(Yii::t('equipment', 'Add Firmware'), FALSE, ['style' => 'cursor:pointer', 'value' => Url::to(['equipment/firmware', 'id' => (string) $model->_id]), 'title' => 'Add Firmware for ' . $model->sn, 'class' => 'showModalButton', 'id' => 'modalButton']); ?></li>
                        <!--<li><?php //echo Html::a(Yii::t('equipment', 'Add to Case'), FALSE, ['style' => 'cursor:pointer', 'value' => Url::to(['equipment/case', 'id' => (string) $model->_id]), 'title' => 'Add ' . $model->sn . ' to Case', 'class' => 'showModalButton', 'id' => 'modalButton']);                     ?></li>-->
                        <li class="divider"></li>
                        <li><?= Html::a(Yii::t('equipment', 'Update'), FALSE, ['style' => 'cursor:pointer', 'value' => Url::to(['equipment/update', 'id' => (string) $model->_id]), 'title' => 'Updating ' . $model->sn, 'class' => 'showModalButton', 'id' => 'modalButton']); ?></li>
                        <li><?= Html::a(Yii::t('equipment', 'Delete'), ['delete', 'id' => (string) $model->_id], ['style' => 'cursor:pointer', 'data' => [ 'confirm' => Yii::t('app', 'Are you sure you want to delete this equipment? <br><br> Also, if there are any other products, products, parts, contacts, or other records associated with this item the system will not delete it. You will be notified if this happens.'), 'method' => 'post']]) ?></li>
                    </ul>
                </div></h1>
            <ul class="nav nav-tabs nav-tabs-fillup" role="tablist">
                <li class="active"><a href="#inventoryTab" data-toggle="tab" role="tab">Inventory</a>
                </li>
                <li class=""><a href="#specsTab" data-toggle="tab" role="tab">Firmware</a>
                </li>
                <li class=""><a href="#tab2Inspire" data-toggle="tab" role="tab">Files</a>
                </li>
                <li class=""><a href="#complete_maint" data-toggle="tab" role="tab">Completed Maintenance</a>
                </li>
                <li class=""><a href="#blank_maint" data-toggle="tab" role="tab">Maintenance Forms</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active slide-left" id="inventoryTab">
                    <div class="row">
                        <?php
                        yii\widgets\Pjax::begin(['id' => 'inventory-list']);
                        $inventory = Inventory::find()->where(['equipment_id' => (string) $model->_id])->all();
                        ?>
                        <?php if (isset($inventory) && !empty($inventory) && is_array($inventory)): ?>
                            <?php foreach ($inventory as $key => $part): ?>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 margin-bottom-20">
                                    <div class="products-grid">
                                        <div class="products-img-border">
                                            <div class="products-grid-wrapper">

                                                <div data-pages="portlet" class="panel panel-default" id="portlet-basic">
                                                    <div class="panel-heading ">
                                                        <div class="panel-title">
                                                            Inv. Name: <?php echo $part->ref_name != '' ? $part->ref_name : 'Not Set'; ?><br/>
                                                            Part Name: <?php echo $part->partInfo->name; ?><br/>
                                                            Return Location: <?php echo $part->locationInfo->name != '' ? $part->locationInfo->name : 'Not Set'; ?><br/>
                                                        </div>
                                                        <div class="panel-controls">
                                                            <ul>
                                                                <li><a data-toggle="collapse" class="portlet-collapse" href="#"><i class="pg-arrow_maximize" style="margin:0 4px 2px 0"></i></a>
                                                                </li>
                                                                <li><?php
                                                                    echo
                                                                    Html::a('<i class="fa fa-edit"></i>', false, ['value' => Url::to(['inventory/update', 'id' => (string) $part['_id']]),
                                                                        'style' => 'margin-top:-5px; margin-right:5px; cursor:pointer;',
                                                                        'title' => 'Update inventory item(s) for ' . $model->sn,
                                                                        'class' => 'showModalButton pull-right',
                                                                        'id' => 'modalButton'
                                                                    ]);
                                                                    ?>
                                                                </li>
                                                                <li><?php
                                                                    echo
                                                                    Html::a('<i class="fa fa-times"></i>', ['remove-inventory', 'id' => (string) $part['_id']], [
                                                                        'style' => 'margin-top:-5px;',
                                                                        'class' => 'pull-right',
                                                                        'data' => [
                                                                            'confirm' => Yii::t('app', '<br>Are you sure you want to return ' . $part->quan . ' (' . $part->partInfo->name . ') item(s) to ' . $part->locationInfo->name . '?'),
                                                                            'method' => 'post',
                                                                        ],
                                                                    ])
                                                                    ?>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div style="display: block;" class="panel-body">
                                                        <p><b>Quantity:</b> <?= $part->quan; ?> <?= ($part->quan > 1) ? Html::a('(Consume One)', ['consume-one', 'id' => (string) $part['_id']]) : '' ?> <?= Html::a(Yii::t('equipment', '(Add From Inventory)'), FALSE, ['style' => 'cursor:pointer', 'value' => Url::to(['equipment/inventory', 'id' => (string) $model->_id, 'part' => (string) $part['part_id']]), 'title' => 'Add Existing Inventory for ' . $model->sn, 'class' => 'showModalButton', 'id' => 'modalButton']); ?></p>
                                                        <p><b>Expiration Date:</b> <?= (!empty($part->exp_date)) ? $part->exp_date : '(No date given))'; ?></p>
                                                        <p><b>Description:</b><br> <?= (!empty($part->partInfo->desc)) ? $part->partInfo->desc : '(No description)'; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            endforeach;
                        endif;
                        yii\widgets\Pjax::end();
                        ?>

                    </div>
                </div>
                <div class="tab-pane slide-left" id="specsTab">
                    <div class="row column-seperation">
                        <div class="col-md-12">
                            <h3 class="semi-bold">Firmware</h3>
                            <?php if ($firmware != NULL && $firmware != ''): ?>
                                <?php foreach ($firmware as $key => $firms): ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading separator">
                                            <?php
                                            $firmp = [];
                                            foreach ($model->productInfo['firmware'] as $k => $v) {
                                                $firmp[(string) $v['_id']] = $v;
                                            }
                                            ?>
                                            <?php
                                            echo Html::a('<i class="fa fa-times"></i>', ['delete-firmware', 'id' => (string) $firms['_id']], [
                                                'style' => 'margin-top:-5px;',
                                                'class' => 'pull-right',
                                                'data' => [
                                                    'confirm' => Yii::t('app', '<br>Are you sure you want to delete this firmware?'),
                                                    'method' => 'post',
                                                ],
                                            ])
                                            ?>
                                            <?php
                                            echo Html::a('<i class="fa fa-edit"></i>', false, ['value' => Url::to(['equipment/update-firmware', 'id' => (string) $firms['_id']]),
                                                'style' => 'margin-top:-5px; margin-right:5px; cursor:pointer;',
                                                'title' => 'Update firmware for ' . $model->name,
                                                'class' => 'showModalButton pull-right',
                                                'id' => 'modalButton'
                                            ]);
                                            ?>

                                            <div class="panel-title">
                                                Type: <?php echo $firmp[(string) $firms['_id']]['type'] . ' (Ver. ' . $firmp[(string) $firms['_id']]['version'] . ')' ?>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <h5><b>Release Date: </b> <?= $firmp[(string) $firms['_id']]['date'] ?></h5>
                                            <h5><b>Install Date: </b> <?= $firms['install'] ?></h5>
                                            <h5><b>Expiration Date: </b> <?= $firms['expiration'] ?></h5>
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <p>
                                                    <p><b>Notes: </b> <?php echo ($firmp[(string) $firms['_id']]['notes'] != '') ? $firmp[(string) $firms['_id']]['notes'] : '(No Notes)'; ?> </p>
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

                                            <?php
                                            // echo Html::a('<i class="fa fa-times"></i>', ['delete-file', 'id' => (string) $file_list['_id']], [
                                            //     'style' => 'margin-top:-5px;',
                                            //     'class' => 'pull-right',
                                            //     'data' => [
                                            //         'confirm' => Yii::t('app', '<br>Are you sure you want to delete this firmware?'),
                                            //         'method' => 'post',
                                            //     ],
                                            // ]);
                                            ?>
                                            <?php
                                            // echo Html::a('<i class="fa fa-edit"></i>', false, ['value' => Url::to(['products/update-file', 'id' => (string) $file_list['_id']]),
                                            //     'style' => 'margin-top:-5px; margin-right:5px; cursor:pointer;',
                                            //     'title' => 'Update firmware for ' . $model->model,
                                            //     'class' => 'showModalButton pull-right',
                                            //     'id' => 'modalButton'
                                            // ]);
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
                <div class="tab-pane slide-left" id="tab2Inspire">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="semi-bold">Files</h3>
                            <?php if ($files != NULL && $files != ''): ?>
                                <?php foreach ($files as $key => $file_list): ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading separator">

                                            <?php
                                            // echo Html::a('<i class="fa fa-times"></i>', ['delete-file', 'id' => (string) $file_list['_id']], [
                                            //     'style' => 'margin-top:-5px;',
                                            //     'class' => 'pull-right',
                                            //     'data' => [
                                            //         'confirm' => Yii::t('app', '<br>Are you sure you want to delete this firmware?'),
                                            //         'method' => 'post',
                                            //     ],
                                            // ]);
                                            ?>
                                            <?php
                                            // echo Html::a('<i class="fa fa-edit"></i>', false, ['value' => Url::to(['products/update-file', 'id' => (string) $file_list['_id']]),
                                            //     'style' => 'margin-top:-5px; margin-right:5px; cursor:pointer;',
                                            //     'title' => 'Update firmware for ' . $model->model,
                                            //     'class' => 'showModalButton pull-right',
                                            //     'id' => 'modalButton'
                                            // ]);
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
                <div class="tab-pane slide-left" id="complete_maint">
                    <h3 class="semi-bold">Completed Maintenance Forms</h3>
                    <?php
                        $searchModel = new MaintenanceCompletedSearch();
                        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                        echo ListView::widget([
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
                            'itemView' => '_maint_forms_completed',
                        ]);
                    ?>
                </div>
                <div class="tab-pane slide-left" id="blank_maint">
                    <h3 class="semi-bold">Blank Maintenance Forms</h3>
                    <?php
                        $searchModel2 = new MaintenanceFormsSearch();
                        $dataProvider2 = $searchModel2->searchbyproduct((string) $model->product);
                        echo ListView::widget([
                            'dataProvider' => $dataProvider2,
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
                            'itemView' => '_maint_forms',
                        ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 full-height">
        <div class="full-height">
            <?= Html::img(Url::to(['file/file', 'id' => ($model->hasphoto == 1) ? (string) $model->_id : (string) $model->product]), ['class' => 'img-thumbnail bg-white', 'alt' => $model->sn]); ?>
            <h2>Serial Number: <?= $model->sn ?></h2>
            <div class="col-md-3 col-sm-12">
                <b>Status: </b>
            </div>
            <div class="col-md-9 col-sm-12">
                <?= $model->statusInfo->name_long ?>
            </div>
            <div class="col-md-3 col-sm-12">
                <b>Model: </b>
            </div>
            <div class="col-md-9 col-sm-12">
                <?= $model->productInfo->model ?>
            </div>
            <div class="col-md-3 col-sm-12">
                <b>Manu.: </b>
            </div>
            <div class="col-md-9 col-sm-12">
                <?= $model->manufacturerInfo->name ?>
            </div>
            <div class="col-md-3 col-sm-12">
                <b>Location: </b>
            </div>
            <div class="col-md-9 col-sm-12">
                <?= $model->locationName->name ?>
            </div>
            <div class="col-md-3 col-sm-12">
                <b>Name: </b>
            </div>
            <div class="col-md-9 col-sm-12">
                <?= $model->name ?>
            </div>
            <?php if (isset($model->purchase) && !empty($model->purchase)): ?>
                <div class="col-md-3 col-sm-12">
                    <b>Purchase Date: </b>
                </div>
                <div class="col-md-9 col-sm-12">
                    <?= $model->purchase ?>
                </div>
            <?php endif; ?>
            <?php if (isset($model->war_start) && !empty($model->war_start)): ?>
                <div class="col-md-3 col-sm-12">
                    <b>Warranty Start: </b>
                </div>
                <div class="col-md-9 col-sm-12">
                    <?= $model->war_start ?>
                </div>
            <?php endif; ?>
            <?php if (isset($model->war_end) && !empty($model->war_end)): ?>
                <div class="col-md-3 col-sm-12">
                    <b>Warranty End: </b>
                </div>
                <div class="col-md-9 col-sm-12">
                    <?= $model->war_end ?>
                </div>
            <?php endif; ?>
            <br/><hr/>
            <h2>Categories</h2>

            <?php
            if (!empty($model->productInfo->categories)) {
                $product_cat = [];
                foreach ($model->productInfo->categories as $key => $category) {
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
                        echo '<li class="dd-item" data-id="' . (string) $category['_id'] . '">'
                        . '<div class="dd-handle">'
                        . $cat_name
                        . '</div>'
                        . '</li>';
                    }
                }
            }
            ?>
        </div>
    </div>
