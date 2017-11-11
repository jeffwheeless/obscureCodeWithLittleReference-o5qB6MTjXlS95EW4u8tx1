<?php


namespace frontend\submodels;

use common\components\MongoActiveRecord;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

class MaintenanceAnswers extends MongoActiveRecord {

    public $_id;
    public $question;
    public $answer;
    public $status;
    public $notes;

    /*
     * @inheritdoc
     */

    public function rules() {
        return [
            // ['_id', ['question','answer','status','notes']
            [['answer'], 'required', 'message' => '{attribute} can\'t be blank'],
            // ['answer', 'string', 'max'=>2],
            [['question','answer','status','notes'], 'trim'],
            [['status'], 'string', 'min' => 24, 'max' => 24],
            [['question','answer','notes'], 'filter', 'filter' => function ($value) {
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
            'question',
            'answer',
            'status',
            'notes',
        ];
    }

    /*
     * @inheritdoc
     */

    public function attributeLabels() {
        return [
            '_id' => 'ID',
            'question' => 'Question',
            'answer' => 'Answer',
            'status' => 'Affective Status',
            'notes' => 'Notes',
        ];
    }
}
