<?php

use yii\helpers\Html;

$this->title = $model->categoria->descricao;
$this->params['breadcrumbs'][] = ['label' => $model->categoria->descricao, 'url' => ['index', 'id' => $model->categoria_id]];
$this->params['breadcrumbs'][] = 'Alterar';
?>
<div class="sorteio-update">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading panel-md">
                    <div class="panel-title pull-left"><?= $this->title ?></div>
                    <div class="btn-group pull-right">
                        <?= Html::button($model->isNewRecord ? 'Salvar' : 'Salvar', ['id' => 'submitButton', 'class' => $model->isNewRecord ? 'btn btn-success pull-right m-r-15' : 'btn btn-success pull-right m-r-15']) ?>
                    </div>
                </div>
                <div class="panel-body">
                    <?=
                    $this->render('_form', [
                        'model' => $model,
                        'modelsJogo' => $modelsJogo,
                        'modelsTime' => $modelsTime
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>
