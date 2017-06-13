<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "time".
 *
 * @property integer $time_id
 * @property string $descricao
 *
 * @property JogoTime[] $jogoTimes
 * @property TimeSorteado[] $timeSorteados
 */
class Time extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'time';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descricao'], 'required'],
            [['descricao'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'time_id' => 'Time ID',
            'descricao' => 'Descricao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJogoTimes()
    {
        return $this->hasMany(JogoTime::className(), ['time_id' => 'time_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeSorteados()
    {
        return $this->hasMany(TimeSorteado::className(), ['time_id' => 'time_id']);
    }
}
