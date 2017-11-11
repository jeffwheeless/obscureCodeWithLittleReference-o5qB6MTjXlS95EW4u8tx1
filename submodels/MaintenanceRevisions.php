<?php



namespace frontend\submodels;

use common\components\MongoActiveRecord;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

class MaintenanceRevisions extends MongoActiveRecord {

    public $_id;
    public $date;
    public $reason;
    public $note;

    /*
     * @inheritdoc
     */

    public function rules() {
        return [
            // ['_id', ['date','reason','note']
            [['reason'], 'required', 'message' => '{attribute} can\'t be blank'],
            [['reason','note'], 'trim'],
            [['date'], 'date'],
            [['reason','note'], 'filter', 'filter' => function ($value) {
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
            'reason',
            'date',
            'note',
        ];
    }

    /*
     * @inheritdoc
     */

    public function attributeLabels() {
        return [
            '_id' => 'ID',
            'reason' => 'Reason for Alteration',
            'date' => 'Date of Alteration',
            'note' => 'Notes',
        ];
    }
}
