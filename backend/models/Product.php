<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $article
 * @property string $product
 * @property string $size
 * @property integer $id_category
 * @property double $price
 *
 * @property Orders[] $orders
 * @property PriceHistory[] $priceHistories
 * @property Category $idCategory
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article', 'id_category'], 'required'],
            [['id_category'], 'integer'],
            [['price'], 'number'],
            [['article', 'product'], 'string', 'max' => 50],
            [['size'], 'string', 'max' => 200],
            [['article', 'product'], 'unique', 'targetAttribute' => ['article', 'product'], 'message' => 'The combination of Article and Product has already been taken.'],
            [['id_category'], 'exist', 'skipOnError' => true, 'targetClass' => Cat::className(), 'targetAttribute' => ['id_category' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article' => 'Article',
            'product' => 'Product',
            'size' => 'Size',
            'id_category' => 'Id Category',
            'price' => 'Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriceHistories()
    {
        return $this->hasMany(PriceHistory::className(), ['id_product' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCategory()
    {
        return $this->hasOne(Cat::className(), ['id' => 'id_category']);
    }
    
   public function getCategoryName()
    {
     $category = Cat::findOne(['id' => $this->id_category]);
     return $category['category'];
    }
    
}
