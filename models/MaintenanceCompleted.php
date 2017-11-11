<?php


namespace frontend\models;

use common\components\MongoActiveRecord;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use frontend\models\Images;
use frontend\models\Companies;
use frontend\models\Locations;
use frontend\models\Products;

/**
 * This is the model class for collection "equipment".
 *
 * @property integer $_id
 * @property integer $parent_equipment
 * @property string $name
 * @property integer $staus
 */
class MaintenanceCompleted extends MongoActiveRecord {
    /*
     * @return string the name of the index associated with this ActiveRecord class.
     */

    public static function collectionName() {
        return 'maint_completed';
    }

    /*
     * @inheritdoc
     */

    public function rules() {
        return [
            // ['_id', ['form', 'date', 'rev', 'answer']
            [['date', 'equipment_id'], 'required', 'message' => '{attribute} can\'t be blank'],
            // [['equipment_id'], 'string', 'min' => 24, 'max' => 24],
            // [['date'], 'date'],
        //     [['form'], 'filter', 'filter' => function ($value) {
        //     return HtmlPurifier::process($value);
        // }],
        ];
    }

    /*
     * @inheritdoc
     */

    public function attributes() {
        return [
            '_id',
            'form',
            'date',
            'rev',
            'rev_date',
            'answer',
            'equipment_id',
        ];
    }

    /*
     * @inheritdoc
     */

    public function attributeLabels() {
        return [
            '_id' => 'ID',
            'equipment_id' => 'Equipment',
            'form' => 'Form Information',
            'date' => 'Date of Completion',
            'rev_date' => 'Date of Last Revision',
            'rev' => 'Revisions',
            'answer' => 'Anaswers',
        ];
    }



    /*
     * @relation
     * used to get the equipment
     * each equipment has just one Product
     */

    public function getEquipmentInfo() {
        return $this->hasOne(Products::className(), ['_id' => 'equipment_id']);
    }
}
