<?php


namespace frontend\models;

use common\components\MongoActiveRecord;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

class MaintenanceForms extends MongoActiveRecord {
    /*
     * @return string the name of the index associated with this ActiveRecord class.
     */

    public static function collectionName() {
        return 'maint_forms';
    }

    /*
     * @inheritdoc
     */

    public function rules() {
        return [
            // ['_id','product_id','form_title','desc','question']
            [['form_title','desc'], 'trim'],
            [['form_title'], 'required', 'message' => '{attribute} can\'t be blank'],
            // [['product_id'], 'string', 'min' => 24, 'max' => 24],
            [['form_title'], 'string', 'max' => 50],
            [['form_title','desc'], 'filter', 'filter' => function ($value) {
            return HtmlPurifier::process($value);
        }],
        ];
    }

    /*
     * @inheritdoc
     */

    public function attributes() {
        return [
            '_id',
            'form_title',
            'desc',
            'question',
            'product',
        ];
    }

    /*
     * @inheritdoc
     */

    public function attributeLabels() {
        return [
            '_id' => 'ID',
            // 'product_id' => 'Product ID',
            'form_title' => 'Form Title',
            'desc' => 'Description',
            'question' => 'Questions',
            'product' => 'Product That Uses This Form',
        ];
    }
}
