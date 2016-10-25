<?php

namespace app\modules\grc\models;

use Yii;

/**
 * This is the model class for table "grc_agents".
 *
 * @property integer $id
 * @property string $name
 * @property string $agent_type
 * @property integer $active
 * @property integer $deleted
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property GrcBooking[] $grcBookings
 */
class GrcAgents extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grc_agents';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'agent_type'], 'required'],
            [['active', 'deleted'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 64],
            [['agent_type', 'created_by'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'agent_type' => 'Agent Type',
            'active' => 'Active',
            'deleted' => 'Deleted',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrcBookings()
    {
        return $this->hasMany(GrcBooking::className(), ['agent_id' => 'id']);
    }
}
