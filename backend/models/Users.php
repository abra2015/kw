<?php

 namespace backend\models;

 use Yii;

 /**
  * This is the model class for table "users".
  *
  * @property integer $id
  * @property string $name
  * @property string $phone
  * @property string $email
  * @property integer $status
  * @property string $adress
  * @property integer $date_reg
  * @property string $comment
  * @property string $datetime
  */
 class Users extends \yii\db\ActiveRecord
 {
  /**
   * @inheritdoc
   */
  public static function tableName()
   {
    return 'users';
   }

  /**
   * @inheritdoc
   */
  public function rules()
   {
    return [
        [['name', 'phone',], 'required'],
        [['status', 'datetime', 'catalog'], 'integer'],
        [['adress', 'comment'], 'string'],
        [['datetime', 'date_updated', 'date_reg'], 'safe'],
        [['name', 'phone'], 'string', 'max' => 100],
        [['email'], 'string', 'max' => 50],
        [['phone'], 'unique'],
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
        'phone' => 'Phone',
        'email' => 'Email',
        'status' => 'Status',
        'adress' => 'Adress',
        'date_reg' => 'Date Reg',
        'comment' => 'Comment',
        'catalog' => 'Catalog',
        'datetime' => 'Datetime',
    ];
   }
 }