<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\icons\Icon;
use app\models\Time;
use app\models\JogoTime;
use app\models\Numero;

Icon::map($this);
?>

<div class="sorteio-form">

    <?php $form = ActiveForm::begin(['id' => 'form']); ?>

    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'numero')->textInput(); ?>

        </div>
        <div class="col-lg-6">
            <?=
            $form->field($model, 'data')->widget(DatePicker::className(), [
                'language' => 'pt-BR',
                'removeButton' => ['icon' => 'trash'],
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'format' => 'dd/mm/yyyy',
                    'autoclose' => true,
                ],
            ])
            ?>
        </div>
    </div> 

    <div id="variacao" class="hidden"><?= $model->categoria->variacao ?></div>
    <div id="categoria-id" class="hidden"><?= $model->categoria_id ?></div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-lg-2">
                    <span id="count" class="badge badge-default m-t-25"><?= $model->isNewRecord ? 0 : count($modelsJogo) ?></span>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'quantidade_jogos')->textInput(['value' => 1])->label('Quantidade de Jogos'); ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'quantidade_numeros')->dropDownList($model->quantidadeNumeros())->label('Quantidade de Números'); ?>
                </div>
                <div class="col-lg-2">
                    <label>Automático?</label><br>
                    <label class="switch">
                        <input id="automatico" type="checkbox" checked>
                        <div class="slider round"></div>
                    </label>
                </div>
                <div class="col-lg-1">
                    <?= Html::a('<span class="fa fa-plus"></span>', '#', ['class' => 'btn btn-md btn-success btn-add']) ?>
                </div>
            </div>
        </div>
    </div>

    <div id="error" class="hidden">
        <div class="alert alert-error alert-danger">
            <div class="panel-title">
                <div class="row">
                    <div class="col-lg-4 col-lg-offset-4">
                        <div>Possíveis Erros:</div>
                        <li>Número Igual</li>
                        <li>Número em Branco</li>
                        <li>Número com Valor 0</li>
                        <li>Número Fora da Variação</li>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="jogos" class="row">
        <?php if (!$model->isNewRecord) : ?>
            <?php foreach ($modelsJogo as $i => $modelJogo) : ?>
                <?php $modelsNumero = Numero::find()->where(['jogo_id' => $modelJogo->jogo_id])->orderBy(['numero' => SORT_ASC])->all() ?>
                <div id="panel-<?= $i ?>" class="col-lg-4">
                    <div class="panel panel-default panel-jogo panel-small">
                        <div class="panel-heading">
                            <div class="panel-title display-inline">
                                <span class="badge badge-default m-r-15">#<?= $i + 1 ?></span>
                                <span><?= count($modelsNumero) ?> Números</span>
                            </div>
                            <a id="<?= $i ?>" class="btn btn-delete btn-xs btn-danger fa fa-trash pull-right"></a>
                            <a id="<?= $i ?>" class="btn btn-reset btn-xs btn-primary fa fa-eraser pull-right m-r-5"></a>
                            <a id="<?= $i ?>" class="btn btn-reorder btn-xs btn-info fa fa-reorder pull-right m-r-5"></a>
                        </div>
                        <div class="panel-body">
                            <?php if ($model->categoria_id == 6) : ?>
                                <label class="display-block">Time</label>
                                <?= Html::dropDownList('JogoTime[' . $i . ']', JogoTime::findOne(['jogo_id' => $modelJogo->jogo_id])->time_id, ArrayHelper::map(Time::find()->all(), 'time_id', 'descricao'), ['class' => 'form-control m-b-15']) ?>
                            <?php endif; ?>
                            <label class="display-block">Números</label>
                            <?php foreach ($modelsNumero as $j => $modelNumero) : ?>
                                <input type="number" id="<?= $i ?>" class="form-control text-center input-small jogo jogo-<?= $i ?> jogo-<?= $i ?>-<?= $j ?>" name="Jogo[<?= $i ?>][<?= $i ?>-<?= $j ?>]" value="<?= $modelNumero->numero ?>" min="1" max="<?= $model->categoria->variacao ?>">
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript" src="<?= Yii::$app->request->baseUrl . '/js/jquery-1.9.1.min.js' ?>"></script>
<script type="text/javascript">
    $(window).load(function () {

        //VARIAVEIS
        var times = <?= json_encode($modelsTime); ?>;
        var categoriaID = parseInt($('#categoria-id').html());
        var variacao = parseInt($('#variacao').html());

        function randomArr(quantidadeNumeros) {
            var arr = [];
            while (arr.length < quantidadeNumeros) {
                var numero = Math.ceil(Math.random() * variacao);
                if (arr.indexOf(numero) > -1)
                    continue;
                arr[arr.length] = numero;
            }

            return order(arr);
        }

        function order(arr) {
            for (var i = 0; i < arr.length; i++) {
                var target = arr[i];
                for (var j = i - 1; j >= 0 && (arr[j] > target); j--) {
                    arr[j + 1] = arr[j];
                }
                arr[j + 1] = target;
            }
            return arr;
        }

        $('.btn-add').on('click', function (e) {
            e.preventDefault();

            //CONTADOR PRINCIPAL
            var count = parseInt($('#count').html());

            //VALORES DA CATEGORIA
            var quantidadeJogos = $('#sorteio-quantidade_jogos').val() !== '' ? $('#sorteio-quantidade_jogos').val() : 0;
            var quantidadeNumeros = $('#sorteio-quantidade_numeros').val();

            //CONTADOR DINÂMICO
            var k = parseInt(quantidadeJogos) + count;

            //DIV PRINCIPAL
            var divJogos = document.getElementById('jogos');

            for (var i = count; i < k; i++) {

                var id = i;

                //COL
                var divCol = document.createElement('div');
                divCol.setAttribute('id', 'panel-' + id);
                divCol.setAttribute('class', 'panel-jogo col-lg-4');

                //PAINEL JOGO
                var panelJogo = document.createElement('div');
                panelJogo.setAttribute('class', 'panel panel-default panel-small');
                var inputPanelJogo = document.createElement('input');
                inputPanelJogo.setAttribute('type', 'hidden');
                inputPanelJogo.setAttribute('name', 'Jogo[' + id + ']');
                panelJogo.appendChild(inputPanelJogo);

                //PAINEL HEADING JOGO
                var panelHeading = document.createElement('div');
                panelHeading.setAttribute('class', 'panel-heading');

                //PAINEL HEADING TITLE JOGO
                var panelTitle = document.createElement('div');
                panelTitle.setAttribute('class', 'panel-title display-inline');

                //PAINEL HEADING TITLE JOGO CONTADOR
                var panelTitleContador = document.createElement('span');
                panelTitleContador.setAttribute('class', 'badge badge-default panel-title-contador m-r-15');
                panelTitleContador.textContent = '#' + (i + 1);

                //PAINEL HEADING TITLE JOGO NÚMEROS
                var panelTitleNumeros = document.createElement('span');
                panelTitleNumeros.textContent = quantidadeNumeros + ' Números';

                panelHeading.appendChild(panelTitleContador);
                panelHeading.appendChild(panelTitleNumeros);

                //PAINEL HEADING DELETE REORDER
                var btnReorder = document.createElement('a');
                btnReorder.setAttribute('id', id);
                btnReorder.setAttribute('class', 'btn btn-reorder btn-xs btn-info fa fa-reorder pull-right m-r-5');

                //PAINEL HEADING DELETE RESET
                var btnReset = document.createElement('a');
                btnReset.setAttribute('id', id);
                btnReset.setAttribute('class', 'btn btn-reset btn-xs btn-primary fa fa-eraser pull-right m-r-5');

                //PAINEL HEADING DELETE BTN
                var btnDelete = document.createElement('a');
                btnDelete.setAttribute('id', id);
                btnDelete.setAttribute('class', 'btn btn-delete btn-xs btn-danger fa fa-trash pull-right');

                panelHeading.appendChild(panelTitle);
                panelHeading.appendChild(btnDelete);
                panelHeading.appendChild(btnReset);
                panelHeading.appendChild(btnReorder);

                //PAINEL BODY JOGO
                var panelBody = document.createElement('div');
                panelBody.setAttribute('class', 'panel-body');

                //TIME SELECT
                if (categoriaID === 6) {

                    var labelTime = document.createElement('label');
                    labelTime.setAttribute('class', 'display-block')
                    labelTime.textContent = 'Time';

                    panelBody.appendChild(labelTime);

                    var selectTime = document.createElement('select');
                    var selected = Math.floor((Math.random() * times.length) + 1);
                    selectTime.setAttribute('class', 'form-control m-b-15');
                    selectTime.setAttribute('name', 'JogoTime[' + i + ']');

                    for (var key in times) {
                        var timeID = times[key]['time_id'];
                        var timeDescricao = times[key]['descricao'];

                        var optionTime = document.createElement('option');
                        optionTime.setAttribute('value', timeID);
                        if (parseInt(timeID) === parseInt(selected)) {
                            optionTime.setAttribute('selected', 'selected');
                        }

                        optionTime.textContent = timeDescricao;
                        selectTime.appendChild(optionTime);
                    }

                    panelBody.appendChild(selectTime);
                }

                var labelNumero = document.createElement('label');
                labelNumero.setAttribute('class', 'display-block');
                labelNumero.textContent = 'Números';

                panelBody.appendChild(labelNumero);

                //NÚMEROS
                var arr = randomArr(quantidadeNumeros);
                for (var j = 0; j < arr.length; j++) {

                    var inputNumero = document.createElement('input');
                    inputNumero.setAttribute('type', 'number');
                    inputNumero.setAttribute('id', id);
                    inputNumero.setAttribute('class', 'form-control text-center input-small jogo jogo-' + id + ' jogo-' + id + '-' + j + '');
                    inputNumero.setAttribute('name', 'Jogo[' + id + '][' + id + '-' + j + ']');
                    inputNumero.setAttribute('min', 1);
                    inputNumero.setAttribute('max', variacao);
                    inputNumero.setAttribute('value', $('#automatico').is(':checked') ? arr[j] : '');

                    panelBody.appendChild(inputNumero);
                }

                panelJogo.appendChild(panelHeading);
                panelJogo.appendChild(panelBody);

                divCol.appendChild(panelJogo);

                divJogos.appendChild(divCol);

                count++;
            }
            $('#count').html(count);
        });

        $(document).on("click", ".btn-reset", function () {
            var id = $(this).attr('id');
            $('.jogo-' + id).each(function () {
                $(this).val('');
            });
        });

        $(document).on("click", ".btn-delete", function () {
            var id = $(this).attr('id');
            $('#panel-' + id).remove();
            $('#count').html($('.panel-jogo').size());
            $('.panel-jogo').each(function (index) {
                $(this).find('.panel-title-contador').html('#' + (index + 1));
            });
        });

        $(document).on("click", ".btn-reorder", function () {
            var id = $(this).attr('id');
            var quantidadeNumeros = parseInt($('.jogo-' + id).size());
            var newArr = randomArr(quantidadeNumeros);
            for (var j = 0; j < newArr.length; j++) {
                $('.jogo-' + id + '-' + j).val(newArr[j]);
            }
            $('.jogo-' + id).each(function () {
                $(this).removeClass('jogo-error');
            });
        });

        $(document).on("blur", ".jogo", function () {
            var id = $(this).attr('id');
            var flag = true;

            var arr = [];
            $('.jogo-' + id).each(function (index) {
                var cNumero = $(this).val();
                arr[index] = cNumero;
            });

            $('.jogo-' + id).each(function () {
                var cNumero = $(this).val();
                if (checaEqual(arr, cNumero) >= 2 && cNumero !== '') {
                    error();
                    $(this).addClass('jogo-error');
                } else {
                    $(this).removeClass('jogo-error');
                }
            });
                        
        });

        function checaEqual(arr, numero) {
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

        function error()
        {
            $('#error').removeClass('hidden');
            window.scrollTo(0, 0);
        }

        function submitForm()
        {
            $("#form").submit();
            $('#error').addClass('hidden');
        }

        $(document).on("click", "#submitButton", function (e) {
            e.preventDefault();

            checaJogos() ? submitForm() : error();
        });

    });

</script>
