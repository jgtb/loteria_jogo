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
        $modelTimeSorteado = TimeSorteado::findOne(['sorteio_id' => $this->sorteio_id]);

        $modelsVence = $this->categoria->vences;
        foreach ($modelsVence as $i => $modelVence) {
            $arrVence[$i] = $modelVence->quantidade;
        }

        $n = 0;
        $count = $this->categoria_id != 3 ? 1 : 2;

        $modelsJogo = $this->jogos;
        foreach ($modelsJogo as $i => $modelJogo) {

            $modelsNumero = $modelJogo->numeros;
            $modelJogoTime = JogoTime::findOne(['jogo_id' => $modelJogo->jogo_id]);

            for ($j = 0; $j < $count; $j++) {
                $qt = 0;
                foreach ($modelsNumero as $modelNumero) {
                    if (Sorteado::findOne(['sorteio_id' => $this->sorteio_id, 'numero' => $modelNumero->numero, 'indice' => $j]))
                        $qt++;
                }

                if (in_array($qt, $arrVence) || ($modelTimeSorteado->time_id == $modelJogoTime->time_id && $this->categoria_id == 6)) {
                    $arrJogosVencedores[$n] = '<span class="badge badge-default">#' . ($i + 1) . '';

                    if (in_array($qt, $arrVence)) {
                        if ($this->categoria_id == 3) {
                            $arrJogosVencedores[$n] .= ' - Jogo ' . ($j + 1) . ' --> Venceu com ' . $qt . ' Números';
                        } else {
                            $arrJogosVencedores[$n] .= ' --> Venceu com ' . $qt . ' Números';
                        }
                    }
                    
                    if ($modelTimeSorteado->time_id == $modelJogoTime->time_id && $this->categoria_id == 6) {
                        if (in_array($qt, $arrVence)) {
                            $arrJogosVencedores[$n] .= ' e Time Sorteado';
                        } else {
                            $arrJogosVencedores[$n] .= ' --> Time Sorteado</span>';
                        }
                    } else {
                        $arrJogosVencedores[$n] .= '</span>';
                    }
                    
                    $arrJogosVencedores[$n] .= '<br/>';

                    $n++;
                }
            }
        }

        return $arrJogosVencedores;
    }

}
