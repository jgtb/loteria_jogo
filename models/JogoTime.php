<?php

namespace app\models;

use Yii;

class JogoTime extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'jogo_time';
    }

    public function rules()
    {
        return [
            [['jogo_id', 'time_id'], 'required'],
            [['jogo_id', 'time_id'], 'integer'],
            [['jogo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jogo::className(), 'targetAttribute' => ['jogo_id' => 'jogo_id']],
            [['time_id'], 'exist', 'skipOnError' => true, 'targetClass' => Time::className(), 'targetAttribute' => ['time_id' => 'time_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'jogo_time_id' => 'Jogo Time ID',
            'jogo_id' => 'Jogo ID',
            'time_id' => 'Time ID',
        ];
    }

    public function getJogo()
    {
        return $this->hasOne(Jogo::className(), ['jogo_id' => 'jogo_id']);
    }

    public function getTime()
    {
        return $this->hasOne(Time::className(), ['time_id' => 'time_id']);
    }
}
