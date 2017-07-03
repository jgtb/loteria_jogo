<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sorteio;

class SorteioSearch extends Sorteio
{

    public function rules()
    {
        return [
            [['numero', 'quantidade_jogos'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params, $cID)
    {
        $query = Sorteio::find()->where(['categoria_id' => $cID, 'status' => 1])->orderBy(['numero' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => false,
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
