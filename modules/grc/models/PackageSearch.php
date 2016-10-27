<?php

namespace app\modules\grc\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\grc\models\GrcPackage;

/**
 * PackageSearch represents the model behind the search form about `app\modules\grc\models\GrcPackage`.
 */
class PackageSearch extends GrcPackage
{
    public $mealPlan;
    public $room;
    /**
     * @inheritdoc
     */
    
    public function attributes()
    {
    // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['mealPlan.name', 'room.name']);
    }

    public function rules()
    {
        return [
            [['id', 'room_id', 'meal_plan_id', 'active', 'created_by'], 'integer'],
            [['price'], 'number'],
            [['created_at', 'updated_at', 'mealPlan', 'room'], 'safe'],
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
        $query = GrcPackage::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $query->joinWith(['mealPlan']);
        $query->joinWith(['room']);
        
        $dataProvider->sort->attributes['mealPlan'] = [
            'asc' => ['grc_meal_plan.name' => SORT_ASC],
            'desc' => ['grc_meal_plan.name' => SORT_DESC],
        ];
        
        $dataProvider->sort->attributes['room'] = [
            'asc' => ['rooms.name' => SORT_ASC],
            'desc' => ['rooms.name' => SORT_DESC],
        ];


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'room_id' => $this->room_id,
            'meal_plan_id' => $this->meal_plan_id,
            'price' => $this->price,
            'active' => $this->active,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
   

        $query->andFilterWhere(['like', 'grc_meal_plan.name', $this->mealPlan]);
        $query->andFilterWhere(['like', 'rooms.name', $this->room]);

        //var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);
        
        return $dataProvider;
    }
}
