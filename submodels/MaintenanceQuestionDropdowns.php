<?php



namespace frontend\submodels;

use common\components\MongoActiveRecord;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

class MaintenanceQuestionDropdowns extends MongoActiveRecord {

        public $_id;
        public $status;
        public $value;
        public $position;

    /*
     * @inheritdoc
     */

    public function rules() {
        return [
            // ['_id', ['status','value','position']
        //     [['value'], 'required', 'message' => '{attribute} can\'t be blank'],
        //     [['value','position'], 'trim'],
        //     [['position'], 'integer', 'min' => 1],
        //     [['status'], 'string', 'min' => 24, 'max' => 24],
        //     [['value'], 'string', 'min' => 1, 'max' => 50],
        //     [['value','position'], 'filter', 'filter' => function ($value) {
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
            'status',
            'value',
            'position',
        ];
    }

    /*
     * @inheritdoc
     */

    public function attributeLabels() {
        return [
            '_id' => 'ID',
            'status' => 'Status of Equipment if Selected',
            'value' => 'Text to Appear in Dropdown',
            'position' => 'Position in Option List',
        ];
    }
}
