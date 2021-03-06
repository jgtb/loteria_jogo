<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use app\models\Categoria;
use kartik\icons\Icon;

Icon::map($this);

$modelCategoria = new Categoria();

$this->title = $modelCategoria->getTitle($cID);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sorteio-index">
    
    <h3><?= $this->title ?></h3>
    
    <?= Html::a('Nova ' . $this->title, ['create', 'cID' => $cID], ['class' => 'btn btn-warning']) ?>
    
    <?php Pjax::begin(['id' => 'pjax-sorteio', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'emptyText' => 'Nenhum resultado encontrado.',
        'tableOptions' => ['class' => 'table table-striped table-bordered'],
        'columns' => [
            ['attribute' => 'data', 'value' => function ($model) {
                    return $model->data != NULL ? date('d/m/Y', strtotime($model->data)) : 'Não inserido';
                }],
            ['attribute' => 'numero', 'value' => function ($model) {
                    return $model->numero != NULL ? $model->numero : 'Não inserido';
                }],
            ['attribute' => 'qt_jogos', 'label' => 'Número de Jogos', 'value' => function ($model) {
                    return count($model->jogos);
                }],
            ['class' => 'yii\grid\ActionColumn',
                'visible' => $dataProvider->getModels() != NULL,
                'options' => ['style' => 'width: 9%;'],
                'header' => 'Opcões',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['class' => 'btn btn-xs btn-info m-r-5', 'data-pjax' => 0, 'title' => 'Visualizar']);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-edit"></span>', $url, ['class' => 'btn btn-xs btn-primary m-r-5', 'data-pjax' => 0, 'title' => 'Alterar']);
                    },
                    'delete' => function ($url, $model, $key) use ($id) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['class' => 'btn btn-xs btn-danger', 'title' => 'Excluír', 'data-pjax' => 0, 'data-confirm' => 'Você tem certeza que deseja excluír este item?', 'data-method' => 'post']);
                    }
                ]
            ],
        ],
    ]);
    ?>
    
    <?php Pjax::end(); ?>

</div>
