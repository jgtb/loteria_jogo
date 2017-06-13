<?php

namespace app\models;

use Yii;

class Jogo extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'jogo';
    }

    public function rules() {
        return [
            [['sorteio_id', 'status'], 'required'],
            [['sorteio_id', 'status'], 'integer'],
            [['sorteio_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sorteio::className(), 'targetAttribute' => ['sorteio_id' => 'sorteio_id']],
        ];
    }

    public function attributeLabels() {
        return [
            'jogo_id' => 'Jogo ID',
            'sorteio_id' => 'Sorteio ID',
            'status' => 'Status',
        ];
    }

    public function getSorteio() {
        return $this->hasOne(Sorteio::className(), ['sorteio_id' => 'sorteio_id']);
    }
    
    public function getJogoTime() {
        return $this->hasOne(JogoTime::className(), ['jogo_id' => 'jogo_id']);
    }


    public function getNumeros() {
        return $this->hasMany(Numero::className(), ['jogo_id' => 'jogo_id']);
    }
    

}
