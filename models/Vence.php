<?php

namespace app\models;

use Yii;

class Vence extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'vence';
    }

    public function rules()
    {
        return [
            [['categoria_id', 'quantidade'], 'required'],
            [['categoria_id', 'quantidade'], 'integer'],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::className(), 'targetAttribute' => ['categoria_id' => 'categoria_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'vence_id' => 'Vence ID',
            'categoria_id' => 'Categoria ID',
            'quantidade' => 'Quantidade',
        ];
    }

    public function getCategoria()
    {
        return $this->hasOne(Categoria::className(), ['categoria_id' => 'categoria_id']);
    }
}
