<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jogo_time".
 *
 * @property integer $jogo_time_id
 * @property integer $jogo_id
 * @property integer $time_id
 *
 * @property Jogo $jogo
 * @property Time $time
 */
class JogoTime extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jogo_time';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jogo_id', 'time_id'], 'required'],
            [['jogo_id', 'time_id'], 'integer'],
            [['jogo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jogo::className(), 'targetAttribute' => ['jogo_id' => 'jogo_id']],
            [['time_id'], 'exist', 'skipOnError' => true, 'targetClass' => Time::className(), 'targetAttribute' => ['time_id' => 'time_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jogo_time_id' => 'Jogo Time ID',
            'jogo_id' => 'Jogo ID',
            'time_id' => 'Time ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJogo()
    {
        return $this->hasOne(Jogo::className(), ['jogo_id' => 'jogo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTime()
    {
        return $this->hasOne(Time::className(), ['time_id' => 'time_id']);
    }
}
