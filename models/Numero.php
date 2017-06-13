<?php

namespace app\models;

use Yii;

class Numero extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'numero';
    }

    public function rules()
    {
        return [
            [['jogo_id', 'numero'], 'required'],
            [['jogo_id', 'numero'], 'integer'],
            [['jogo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jogo::className(), 'targetAttribute' => ['jogo_id' => 'jogo_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'numero_id' => 'Numero ID',
            'jogo_id' => 'Jogo ID',
            'numero' => 'Numero',
        ];
    }

    public function getJogo()
    {
        return $this->hasOne(Jogo::className(), ['jogo_id' => 'jogo_id']);
    }
}
