<?php

use yii\helpers\Html;

$this->title = 'Nova ' . $model->categoria->descricao;
$this->params['breadcrumbs'][] = ['label' => $model->categoria->descricao, 'url' => ['index', 'cID' => $cID]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sorteio-create">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading panel-md">
                    <div class="panel-title display-inline"><?= $this->title ?></div>
                    <?= Html::button($model->isNewRecord ? 'Salvar' : 'Salvar', ['id' => 'submitButton', 'class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-success pull-right']) ?>
                </div>
                <div class="panel-body">
                    <?=
                    $this->render('_form', [
                        'model' => $model,
                        'modelsJogo' => $modelsJogo,
                        'modelsTime' => $modelsTime,
                        'cID' => $cID
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>
