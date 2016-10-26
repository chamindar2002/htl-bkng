<?php

namespace app\modules\grc\models;

use Yii;

/**
 * This is the model class for table "grc_meal_plan".
 *
 * @property integer $id
 * @property string $name
 * @property integer $deleted
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property GrcPackage[] $grcPackages
 */
class GrcMealPlan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grc_meal_plan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['deleted','created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 64],
            
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
            'deleted' => 'Deleted',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrcPackages()
    {
        return $this->hasMany(GrcPackage::className(), ['meal_plan_id' => 'id']);
    }
}
