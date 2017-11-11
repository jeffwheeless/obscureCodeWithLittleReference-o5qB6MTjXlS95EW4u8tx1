<?php

 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Parts;
use frontend\models\Products;
use yii\helpers\Url;
use kartik\widgets\Select2;
use frontend\submodels\PartsSub;

//$this->title = $model->isNewRecord ? 'Adding New Company' : 'Updating ' . Html::encode($model->name);

/* @var $this yii\web\View */
/* @var $model frontend\models\Companies */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="create-specs-form">
    <?php
    $template = [
        'template' => '{label}{input}{hint}{error}',
        'options' => ['class' => 'form-group form-group-default'],
        'labelOptions' => [
            'class' => 'control-label label-lg'
        ],
    ];
    $disabledTemplate = $template;

    $form = ActiveForm::begin([
                'options' => [
                    'id' => 'create-specs-form',
                    'enableClentValidtaion' => true,
                    'enctype' => 'multipart/form-data'
                ]
    ]);
    ?>
    <?php if ($model->scenario == PartsSub::TYPE_CREATE):; ?>
        <div class="row">
            <div class="col-md-12">
                <?php
                $parts_all = Parts::find();
                $parts_selected = Products::find()->where(['_id' => $product->_id])->one()['parts'];
                $remover = [];
                $first_gone = false;
                if ($parts_selected != NULL && is_array($parts_selected)) {
                    foreach ($parts_selected as $part) {
                        if ($first_gone == false) {
                            $first_gone = true;
                            $parts_all->where(['not', '_id', (string) $part['parts']]);
                        } else {
                            $parts_all->andWhere(['not', '_id', (string) $part['parts']]);
                        }
                    }
                }
                $parts_all = $parts_all->all();

                $partArray = [];
                if ($parts_all != NULL && is_array($parts_all)) {
                    foreach ($parts_all as $k => $part) {
                        if (!isset($remover[(string) $part['_id']])) {
                            $partArray[(string) $part['_id']] = $part['name'];
                        }
                    }
                }
                ?>

                <?=
                $form->field($model, 'parts', $template)->widget(kartik\widgets\Select2::classname(), [
                    'data' => array_merge(["" => ""], $partArray),
                    'options' => ['placeholder' => 'Select Associated Parts', 'multiple' => false],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
        </div>

    <?php endif; ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'quan', $template)->input('number'); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'req', $template)->dropDownList([0 => 'Required', 1 => 'Reccomended'], ['prompt' => '--Click to Select--']); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            $parts_all2 = Parts::find();
            $parts_selected2 = Products::find()->where(['_id' => $product->_id])->one()['parts'];
            $remover2 = [];
            $complicate2 = [];
            $first_gone2 = false;
            if ($parts_selected2 != NULL && is_array($parts_selected2)) {
                foreach ($parts_selected2 as $part2) {
                    if ($first_gone2 == false) {
                        $first_gone2 = true;
                        $parts_all2->where(['_id' => (string) $part2['parts']]);
                    } else {
                        $parts_all2->orWhere(['_id' => (string) $part2['parts']]);
                    }
                    $complicate2[$part2['parts']] = $part2;
                }
            }
            $parts_all2 = $parts_all2->all();

            $partArray2 = [];
            if ($parts_all2 != NULL && is_array($parts_all2)) {
                foreach ($parts_all2 as $k => $part) {
                    if (!isset($remover2[(string) $part['_id']])) {
                        $partArray2[(string) $part['_id']] = $part['name'];
                    }
                }
            }

            $complicate = [];
            if (isset($complicate2)) {
                foreach ($partArray2 as $key => $part_obj) {
                    if (isset($complicate[$complicate2[$key]['alt_group']]))
                        $complicate[$complicate2[$key]['alt_group']] = $complicate[$complicate2[$key]['alt_group']] . ' - ';
                    $complicate[$complicate2[$key]['alt_group']] = $complicate[$complicate2[$key]['alt_group']] . $part_obj;
                }
            }
            asort($complicate);
            ?>

            <?=
            $form->field($model, 'alt_group', $template)->widget(kartik\widgets\Select2::classname(), [
                'data' => $complicate,
                'options' => ['placeholder' => 'Select Associated Parts', 'multiple' => false],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'desc', $template)->textarea(['style' => 'resize:vertical; min-height:100px;max-height:300px;']); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= Html::submitButton(Yii::t('products', 'Save & Update'), ['class' => 'margin-bottom-20 btn btn-lg btn-block btn-raised btn-success']) ?>
        </div>
        <div class="col-md-6">
            <?= Html::button(Yii::t('products', 'Cancel & Close'), ['class' => 'btn btn-lg  btn-block btn-raised btn-danger margin-bottom-20 ', 'data-dismiss' => 'modal']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= Html::button('Add Part Not Listed in Catalog', ['value' => Url::to(['parts/create']), 'title' => 'Adding New Part to Catalog ', 'class' => 'showRelatedModalButton margin-bottom-20 btn btn-lg btn-block btn-raised btn-info']); ?>
        </div>
    </div>
</div>
<input type="hidden" id="last-parent-id" value="">
<?php ActiveForm::end(); ?>
