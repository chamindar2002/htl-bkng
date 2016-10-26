<?php

namespace app\modules\grc\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
/**
 * This is the model class for table "grc_guests".
 *
 * @property integer $id
 * @property string $title
 * @property string $first_name
 * @property string $last_name
 * @property string $address
 * @property string $post_code
 * @property string $city
 * @property string $country
 * @property string $phone
 * @property string $email
 * @property string $nationality
 * @property string $identification
 * @property integer $deleted
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 */
class GrcGuest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grc_guests';
    }
    
    public function behaviors()
    {
         return [
               [
                    'class' => TimestampBehavior::className(),
                    'createdAtAttribute' => 'created_at',
                    'updatedAtAttribute' => 'updated_at',
                    'value' => new Expression('NOW()'),
               ],
               [
                   'class' => BlameableBehavior::className(),
                   'createdByAttribute' => 'created_by',
                   'updatedByAttribute' => false,
                   'value'=>Yii::$app->user->identity->id,
               ] 
               
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'first_name', 'last_name', 'identification'], 'required'],
            [['deleted', 'created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 20],
            [['first_name', 'last_name', 'post_code', 'city', 'country', 'phone', 'email', 'nationality', 'identification'], 'string', 'max' => 64],
            [['address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'address' => 'Address',
            'post_code' => 'Post/Zip Code',
            'city' => 'City',
            'country' => 'Country',
            'phone' => 'Phone',
            'email' => 'Email',
            'nationality' => 'Nationality',
            'identification' => 'Identification: NIC/Passport',
            'deleted' => 'Deleted',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
