<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\icons\Icon;
use yii\widgets\ActiveForm;
use app\models\JogoTime;
use app\models\Time;
use app\models\Numero;

Icon::map($this);

$this->title = $model->categoria->descricao;
$this->params['breadcrumbs'][] = ['label' => $model->categoria->descricao, 'url' => ['index', 'cID' => $model->categoria_id]];
$this->params['breadcrumbs'][] = date('d/m/Y', strtotime($model->data));

$this->title = $model->categoria->descricao;
$model->categoria_id != 3 ? $count = 1 : $count = 2;
?>
<div class="sorteio-view">

    <div id="variacao" class="hidden"><?= $model->categoria->variacao ?></div>

    <div class="panel panel-default">
        <div class="panel-heading panel-md">
            <div class="panel-title pull-left"><?= $this->title ?></div>
            <div class="btn-group pull-right">
                <?=
                Html::a('Excluír', ['delete', 'id' => $model->sorteio_id], [
                    'class' => 'btn btn-danger pull-right',
                    'data' => [
                        'confirm' => 'Você tem certeza que deseja excluír este item?',
                        'method' => 'post',
                    ],
                ])
                ?>
                <?= Html::a('Imprimir', ['pdf', 'id' => $model->sorteio_id], ['class' => 'btn btn-warning pull-right m-r-15', 'target' => '_blank']) ?>
                <?= Html::a('Alterar', ['update', 'id' => $model->sorteio_id], ['class' => 'btn btn-primary pull-right m-r-15']) ?>

            </div>
        </div>
        <div class="panel-body">
            <div class="row">

                <div class="col-lg-8">
                    <h3 class="panel-title">Data do Sorteio: <?= $model->data != NULL ? date('d/m/Y', strtotime($model->data)) : 'Não inserido'; ?></h3>
                    <br>
                    <h3 class="panel-title">Número do Sorteio: <?= $model->numero != NULL ? $model->numero : 'Não inserido' ?></h3>
                    <br>
                    <h3 class="panel-title">Número de Jogos: <?= count($modelsJogo) ?></h3>
                    <br>
                    
                    <div class="row">
                        <?php foreach ($modelsJogo as $index => $modelJogo) : ?>
                            <?php $modelsNumero = Numero::find()->where(['jogo_id' => $modelJogo->jogo_id])->orderBy(['numero' => SORT_ASC])->all() ?>
                            <div class="col-lg-4">
                                <div class="panel panel-default panel-small">
                                    <div class="panel-heading">
                                        <div class="panel-title pull-right"><?= count($modelsNumero) ?> Números</div>
                                        <span class="badge badge-default">#<?= $index + 1 ?></span>
                                    </div>
                                    <div class="panel-body text-center">
                                        <?php if ($model->categoria_id == 6) : ?>
                                            <label class="display-block">Time</label>
                                            <span class="badge <?= $model->getTimeSorteado() == JogoTime::findOne(['jogo_id' => $modelJogo->jogo_id])->time_id ? 'badge-success' : 'badge-default' ?> m-b-5"><?= JogoTime::findOne(['jogo_id' => $modelJogo->jogo_id])->time->descricao ?></span>
                                        <?php endif; ?>

                                        <?php for ($i = 0; $i < $count; $i++) : ?>
                                            <label class="display-block <?= $i == 1 ? 'm-t-10' : '' ?>"><?= $model->categoria_id != 3 ? 'Jogo' : 'Jogo # ' . ($i + 1) . '' ?></label>          
                                            <?php foreach ($modelsNumero as $modelNumero) : ?>
                                                <span class="badge <?= $model->numeroSorteado($modelNumero->numero, $i) ? 'badge-success' : 'badge-default' ?>"><?= $modelNumero->numero != NULL ? $modelNumero->numero : '?' ?></span>
                                            <?php endforeach; ?>
                                        <?php endfor; ?>
                                    </div>  
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>

                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="panel-title">Número Sorteado</div>
                        </div>
                        <div class="panel-body">
                            <?php $form = ActiveForm::begin(['id' => 'form', 'action' => ['sorteado', 'id' => $model->sorteio_id], 'fieldConfig' => ['options' => ['tag' => false]]]); ?>

                            <?php if ($model->categoria_id == 6) : ?>
                                <label class="display-block">Time</label>
                                <?= Html::dropDownList('TimeSorteado', $model->getTimeSorteado(), ArrayHelper::map(Time::find()->orderBy(['descricao' => SORT_ASC])->all(), 'time_id', 'descricao'), ['class' => 'form-control m-b-15', 'prompt' => 'Selecione o Time']) ?>
                            <?php endif; ?>

                            <?php for ($i = 0; $i < $count; $i++) : ?>
                                <label class="display-block m-b-15"><?= $model->categoria_id != 3 ? 'Jogo' : 'Jogo # ' . ($i + 1) . '' ?></label>
                                <div>
                                    <?php for ($j = 0; $j < $model->categoria->numero_sorteio; $j++) : ?>
                                        <?= $form->field($modelSorteado, 'numero[' . $j . '-' . $i . ']', ['options' => ['class' => ''], 'template' => '{input}'])->textInput(['type' => 'text', 'id' => $i, 'class' => $model->getSorteado($j, $i) != NULL ? 'form-control text-center jogo jogo-' . $i . '' : 'form-control text-center jogo jogo-' . $i . ' jogo-error', 'value' => $model->getSorteado($j, $i), 'min' => 1, 'max' => $model->categoria->variacao, 'style' => 'margin-bottom: 10px; margin-right: 5px; width: 20%; display: inline;'])->label(false); ?>
                                    <?php endfor; ?>
                                </div>
                            <?php endfor; ?>

                            <div class="form-group m-t-10">
                                <?= Html::button($model->hasSorteado() ? 'Salvar' : 'Alterar', ['id' => 'submitButton', 'class' => $model->hasSorteado() ? 'btn btn-success' : 'btn btn-primary']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                    <?php if (!$model->hasSorteado()) : ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="panel-title">Resultado</div>
                            </div>
                            <div class="panel-body text-center">
                                <?php if (count($model->getJogosVencedores()) > 0) : ?>
                                    <?php foreach ($model->getJogosVencedores() as $modelJogoVencedor) : ?>
                                        <?= $modelJogoVencedor ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php if (count($model->getJogosVencedores()) == 0) : ?>
                                    <div class="text-uppercase">Nenhum Jogo Venceu</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

        </div>
    </div>

</div>

<script type="text/javascript" src="<?= Yii::$app->request->baseUrl . '/js/jquery-1.9.1.min.js' ?>"></script>
<script type="text/javascript">
    $(window).load(function () {

        var variacao = parseInt($('#variacao').html());

        $(document).on("blur", ".jogo", function () {
            var id = $(this).attr('id');

            var arr = [];
            $('.jogo-' + id).each(function (index) {
                var cNumero = $(this).val();
                arr[index] = cNumero;
            });

            $('.jogo-' + id).each(function () {
                var cNumero = $(this).val();
                if ((equalArr(arr, cNumero) >= 2 && cNumero !== '') || cNumero === '' || cNumero === '0' || $(this).val() > variacao) {
                    $(this).addClass('jogo-error');
                } else {
                    $(this).removeClass('jogo-error');
                }
            });

        });

        function equalArr(arr, numero) {
            var count = 0;
            for (var i = 0; i < arr.length; i++) {
                if (arr[i] === numero && arr[i] !== '') {
                    count++;
                }
            }

            return count;
        }

        function checaJogos()
        {
            var flag = true;

            $('.jogo').each(function () {
                if (parseInt($(this).val()) === 0 || $(this).val() === '' || $(this).hasClass('jogo-error') || $(this).val() > variacao)
                    flag = false;
            });

            return flag;
        }

        function submitForm()
        {
            $("#form").submit();
            $('#error').addClass('hidden');
        }

        $(document).on("click", "#submitButton", function (e) {
            e.preventDefault();

            checaJogos() ? submitForm() : '';
        });

    });

</script>