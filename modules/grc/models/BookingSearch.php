<?php

namespace app\modules\grc\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\grc\models\GrcBooking;

/**
 * BookingSearch represents the model behind the search form about `app\modules\grc\models\GrcBooking`.
 */
class BookingSearch extends GrcBooking
{
    public $guest;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'reservation_id', 'guest_id', 'agent_id', 'no_of_children', 'created_by'], 'integer'],
            [['no_of_adults'], 'number'],
            [['status', 'created_at', 'updated_at', 'guest'], 'safe'],
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
        $query = GrcBooking::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $query->joinWith(['guest']);
        
        $dataProvider->sort->attributes['guest'] = [
            'asc' => ['grc_guests.first_name' => SORT_ASC],
            'desc' => ['grc_guests.first_name' => SORT_DESC],
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
            'reservation_id' => $this->reservation_id,
            'guest_id' => $this->guest_id,
            'agent_id' => $this->agent_id,
            'no_of_adults' => $this->no_of_adults,
            'no_of_children' => $this->no_of_children,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status]);
        $query->andFilterWhere(['like', 'grc_guests.first_name', $this->guest]);
        
        return $dataProvider;
    }
}
