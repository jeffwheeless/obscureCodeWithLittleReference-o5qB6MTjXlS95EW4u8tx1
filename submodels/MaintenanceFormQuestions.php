<?php



namespace frontend\submodels;

use common\components\MongoActiveRecord;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

class MaintenanceFormQuestions extends MongoActiveRecord {

        public $_id;
        public $position;
        public $label;
        public $type;
        public $tooltip;
        public $helptext;
        public $dropdowns;
        public $class;
        public $required;
        public $min;
        public $max;

    /*
     * @inheritdoc
     */

    public function rules() {
        return [
        //     // ['_id', 'position']
            [['position', 'label', 'tooltip', 'helptext', 'class'], 'trim'],
            [['position', 'required', 'label', 'type'], 'required', 'message' => '{attribute} can\'t be blank'],
            [['position'], 'integer', 'min' => 0],
            [['label'], 'string', 'max' => 40],
            [['type'], 'string', 'max' => 9],
            [['tooltip'], 'string', 'max' => 50],
            [['helptext'], 'string', 'max' => 75],
            ['required', 'boolean'],
            [['min', 'max'], 'integer', 'max'=>750, 'min'=>0],
            [['position','label', 'type', 'tooltip', 'helptext'], 'filter', 'filter' => function ($value) {
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
            'position',
            'label',
            'type',
            'tooltip',
            'helptext',
            'dropdowns',
            'class',
            'required',
            'min',
            'max',
        ];
    }

    /*
     * @inheritdoc
     */

    public function attributeLabels() {
        return [
            '_id' => 'ID',
            'position' => 'Position in Form',
            'label' => 'Label',
            'type' => 'Type of Question',
            'tooltip' => 'Informative Hint',
            'helptext' => 'Informative Text',
            'dropdowns' => 'Dropdowns',
            'class' => 'Box Size',
            'required' => 'Is Question Required',
            'min' => 'Min. Characters',
            'max' => 'Max Characters',
        ];
    }
}
