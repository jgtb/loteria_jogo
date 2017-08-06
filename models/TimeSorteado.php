<?php

namespace app\models;

use Yii;

class TimeSorteado extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'time_sorteado';
    }

    public function rules()
    {
        return [
            [['time_id', 'sorteio_id'], 'required'],
            [['time_id', 'sorteio_id'], 'integer'],
            [['time_id'], 'exist', 'skipOnError' => true, 'targetClass' => Time::className(), 'targetAttribute' => ['time_id' => 'time_id']],
            [['sorteio_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sorteio::className(), 'targetAttribute' => ['sorteio_id' => 'sorteio_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'time_sorteado_id' => 'Time Sorteado ID',
            'time_id' => 'Time ID',
            'sorteio_id' => 'Sorteio ID',
        ];
    }

    public function getSorteio()
    {
        return $this->hasOne(Sorteio::className(), ['sorteio_id' => 'sorteio_id']);
    }

    public function getTime()
    {
        return $this->hasOne(Time::className(), ['time_id' => 'time_id']);
    }
}
