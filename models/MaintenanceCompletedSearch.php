<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\MaintenanceCompleted;

/**
 * MaintenanceCompletedSearch represents the model behind the search form about `frontend\models\MaintenanceCompleted`.
 */
class MaintenanceCompletedSearch extends MaintenanceCompleted
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['_id', 'form', 'date', 'rev', 'rev_date', 'answer', 'equipment_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = MaintenanceCompleted::find()->orderBy('date DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'equipment_id', $_GET['id']]);
        // $query->andFilterWhere(['like', '_id', $this->_id])
        //     ->andFilterWhere(['like', 'form', $this->form])
        //     ->andFilterWhere(['like', 'date', $this->date])
        //     ->andFilterWhere(['like', 'rev', $this->rev])
        //     ->andFilterWhere(['like', 'answer', $this->answer])
        //     ->andFilterWhere(['like', 'equipment_id', $this->equipment_id]);

        return $dataProvider;
    }
   public function searchbyequipment($e_id)
   {
       $query = MaintenanceCompleted::find()->where(['equipment_id' => e_id]);

       $dataProvider = new ActiveDataProvider([
           'query' => $query,
       ]);

       $this->load($params);

       if (!$this->validate()) {
           // uncomment the following line if you do not want to return any records when validation fails
           // $query->where('0=1');
           return $dataProvider;
       }

       return $dataProvider;
   }
}
