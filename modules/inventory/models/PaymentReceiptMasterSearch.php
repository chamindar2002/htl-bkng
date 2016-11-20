<?php

namespace app\modules\inventory\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\inventory\models\PaymentReceiptMaster;

/**
 * PaymentReceiptMasterSearch represents the model behind the search form about `app\modules\inventory\models\PaymentReceiptMaster`.
 */
class PaymentReceiptMasterSearch extends PaymentReceiptMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'booking_id', 'is_cancelled', 'deleted', 'cancelled_by', 'created_by'], 'integer'],
            [['receipt_date', 'created_at', 'updated_at'], 'safe'],
            [['amount_paid'], 'number'],
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
        $query = PaymentReceiptMaster::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'receipt_date' => $this->receipt_date,
            'amount_paid' => $this->amount_paid,
            'is_cancelled' => $this->is_cancelled,
            'deleted' => $this->deleted,
            'cancelled_by' => $this->cancelled_by,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
