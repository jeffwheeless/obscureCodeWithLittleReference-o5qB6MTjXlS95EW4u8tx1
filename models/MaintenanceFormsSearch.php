<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\MaintenanceForms;

/**
 * MaintenanceFormsSearch represents the model behind the search form about `frontend\models\MaintenanceForms`.
 */
class MaintenanceFormsSearch extends MaintenanceForms
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['_id', 'form_title', 'desc', 'question'], 'safe'],
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
        $query = MaintenanceForms::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'form_title', $this->form_title])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'question', $this->question]);

        return $dataProvider;
    }
   public function searchbyproduct($product_id)
   {
       $query = MaintenanceForms::find()->where(['product' => $product_id]);

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
