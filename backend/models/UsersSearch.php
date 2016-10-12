<?php

 namespace backend\models;


 use Yii;
 use yii\base\Model;
 use yii\data\ActiveDataProvider;
 use backend\models\Users;

 /**
  * UsersSearch represents the model behind the search form about `app\models\Users`.
  */
 class UsersSearch extends Users
  {
  /**
   * @inheritdoc
   */
   public function rules()
    {
     return [
         [['id', 'catalog'], 'integer'],
         [['name', 'phone', 'email', 'adress'], 'safe'],
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
     $query = Users::find();

     $dataProvider = new ActiveDataProvider([
         'query' => $query,
     ]);

     $this->load($params);

     if (!$this->validate())
      {
       // uncomment the following line if you do not want to return any records when validation fails
       // $query->where('0=1');
       return $dataProvider;
      }

     $query->andFilterWhere([
         'id' => $this->id,
     ]);

     $query->andFilterWhere(['like', 'name', $this->name])
         ->andFilterWhere(['like', 'phone', str_replace([' ','-','/','.',',',], '', trim($this->phone))])
         ->andFilterWhere(['like', 'email', trim($this->email)])
         ->andFilterWhere(['=', 'catalog', $this->catalog])
         ->andFilterWhere(['like', 'adress', $this->adress]);

     return $dataProvider;
    }
  }
