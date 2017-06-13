<?php

namespace app\models;

use Yii;

class Sorteio extends \yii\db\ActiveRecord {

    public $quantidade_jogos, $quantidade_numeros, $jogo_time, $automatico;

    public static function tableName() {
        return 'sorteio';
    }

    public function rules() {
        return [
            [['categoria_id', 'numero', 'data', 'status'], 'required', 'message' => 'Campo Obrigatório'],
            [['categoria_id', 'status'], 'integer'],
            [['data'], 'safe'],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::className(), 'targetAttribute' => ['categoria_id' => 'categoria_id']],
        ];
    }

    public function attributeLabels() {
        return [
            'sorteio_id' => 'Sorteio ID',
            'categoria_id' => 'Categoria ID',
            'numero' => 'Número do Sorteio',
            'data' => 'Data do Sorteio',
            'status' => 'Status',
        ];
    }

    public function getJogos() {
        return $this->hasMany(Jogo::className(), ['sorteio_id' => 'sorteio_id']);
    }

    public function getSorteados() {
        return $this->hasMany(Sorteado::className(), ['sorteio_id' => 'sorteio_id']);
    }

    public function getCategoria() {
        return $this->hasOne(Categoria::className(), ['categoria_id' => 'categoria_id']);
    }

    public function quantidadeNumeros() {
        for ($i = $this->categoria->escolha_min; $i < $this->categoria->escolha_max + 1; $i++) {
            $items[$i] = $i;
        }

        return $items;
    }

    public function numeroSorteado($n, $i) {
        $modelsSorteado = Sorteado::findAll(['sorteio_id' => $this->sorteio_id, 'indice' => $i]);

        if ($modelsSorteado) {
            foreach ($modelsSorteado as $modelSorteado) {
                if ($modelSorteado->numero == $n)
                    return true;
            }
        }

        return false;
    }

    public function getSorteado($i, $indice) {
        return Sorteado::find()->where(['sorteio_id' => $this->sorteio_id, 'indice' => $indice])->orderBy(['numero' => SORT_ASC])->limit($i + 1)->offset($i)->one()->numero;
    }

    public function hasSorteado() {
        return !Sorteado::find()->where(['sorteio_id' => $this->sorteio_id])->one();
    }

    public function getTimeSorteado() {
        return TimeSorteado::find()->where(['sorteio_id' => $this->sorteio_id])->one()->time_id;
    }
   
    public function getJogosVencedores() {
        $modelsNumeroSorteado = $this->sorteados;
        $modelsVence = $this->categoria->vences;
        $modelTimeSorteado = TimeSorteado::findOne(['sorteio_id' => $this->sorteio_id]);

    }
    
    public function getJogosVencedores1() {
        $modelsNumeroSorteado = $this->sorteados;
        foreach ($modelsNumeroSorteado as $index => $modelNumeroSorteado) {
            $arrNumeroSortado[$index] = $modelNumeroSorteado->numero;
        }

        $modelsVence = Vence::find()->where(['categoria_id' => $this->categoria_id])->all();
        foreach ($modelsVence as $index => $modelVence) {
            $arrVence[$index] = $modelVence->quantidade;
        }

        $modelTimeSorteado = TimeSorteado::findOne(['sorteio_id' => $this->sorteio_id]);

        $arrJogosVencidos = [];
        $n = 0;

        $modelsJogo = $this->jogos;
        foreach ($modelsJogo as $index => $modelJogo) {
            $modelsNumero = $modelJogo->numeros;
            $count = 0;
            foreach ($modelsNumero as $modelNumero) {
                if (in_array($modelNumero->numero, $arrNumeroSortado)) {
                    $count++;
                }
            }

            $modelJogoTime = JogoTime::findOne(['jogo_id' => $modelJogo->jogo_id]);

            if (in_array($count, $arrVence) || ($modelTimeSorteado != NULL && $modelTimeSorteado->time_id == $modelJogoTime->time_id)) {
                $arrJogosVencidos[$n] = '<span class="badge badge-default">#' . ($index + 1) . '</span> --> ';
            }

            if (in_array($count, $arrVence))
                $arrJogosVencidos[$n] .= 'Venceu com ' . $count . ' Números<br/>';

            if ($modelTimeSorteado != NULL && $modelTimeSorteado->time_id == $modelJogoTime->time_id)
                $arrJogosVencidos[$n] .= 'Time Sorteado<br/>';

            if (in_array($count, $arrVence) || ($modelTimeSorteado != NULL && $modelTimeSorteado->time_id == $modelJogoTime->time_id)) {
                $arrJogosVencidos[$n] .= '<br/>';
            }

            $n++;
        }

        return $arrJogosVencidos;
    }

}
