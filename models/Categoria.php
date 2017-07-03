<?php

namespace app\models;

use Yii;

class Categoria extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'categoria';
    }

    public function rules() {
        return [
            [['descricao', 'variacao'], 'required'],
            [['variacao', 'duplo'], 'integer'],
            [['descricao'], 'string', 'max' => 45],
        ];
    }

    public function attributeLabels() {
        return [
            'categoria_id' => 'Categoria ID',
            'descricao' => 'Descricao',
            'variacao' => 'Variacao',
        ];
    }

    public function getSorteios() {
        return $this->hasMany(Sorteio::className(), ['categoria_id' => 'categoria_id']);
    }

    public function getVences() {
        return $this->hasMany(Vence::className(), ['categoria_id' => 'categoria_id']);
    }

    public function getNavBarItems() {
        $modelsCategoria = Categoria::find()->orderBy(['descricao' => SORT_ASC])->all();

        foreach ($modelsCategoria as $index => $modelCategoria) {
            $items[$index] = ['label' => $modelCategoria->descricao, 'url' => ['/sorteio', 'cID' => $modelCategoria->categoria_id], 'options' => ['class' => ($_GET['cID'] == $modelCategoria->categoria_id) || (Sorteio::findOne($_GET['id'])->categoria_id == $modelCategoria->categoria_id) ? 'active' : '']];
        }
        
        $items[$index + 1] = [
            'label' => 'LOT Jogos',
            'items' => [
                ['label' => 'LOT', 'url' => '/loteria/web'],
                ['label' => 'CR.CP', 'url' => '/crcp/web'],
            ],
        ];

        return $items;
    }

    public function getTitle($id) {
        return Categoria::findOne(['categoria_id' => $id])->descricao;
    }

}
