<?php

namespace app\models;

use Yii;

class Time extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'time';
    }

    public function rules()
    {
        return [
            [['descricao'], 'required'],
            [['descricao'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'time_id' => 'Time ID',
            'descricao' => 'Descricao',
        ];
    }

    public function getJogoTimes()
    {
        return $this->hasMany(JogoTime::className(), ['time_id' => 'time_id']);
    }

    public function getTimeSorteados()
    {
        return $this->hasMany(TimeSorteado::className(), ['time_id' => 'time_id']);
    }
}
