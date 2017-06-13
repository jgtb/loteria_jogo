<?php

namespace app\models;

use Yii;

class Sorteado extends \yii\db\ActiveRecord
{    
    public static function tableName()
    {
        return 'sorteado';
    }

    public function rules()
    {
        return [
            [['sorteio_id', 'numero', 'indice'], 'required'],
            [['sorteio_id', 'numero'], 'integer'],
            [['sorteio_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sorteio::className(), 'targetAttribute' => ['sorteio_id' => 'sorteio_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'sorteado_id' => 'Sorteado ID',
            'sorteio_id' => 'Sorteio ID',
            'numero' => 'Numero',
            'indice' => 'Índice'
        ];
    }

    public function getSorteio()
    {
        return $this->hasOne(Sorteio::className(), ['sorteio_id' => 'sorteio_id']);
    }
    
    
    
}
