<?php

namespace app\controllers;

use Yii;
use app\models\Sorteio;
use app\models\Jogo;
use app\models\JogoTime;
use app\models\Time;
use app\models\Numero;
use app\models\Sorteado;
use app\models\TimeSorteado;
use app\models\SorteioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class SorteioController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($id) {
        $searchModel = new SorteioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'id' => $id
        ]);
    }

    public function actionView($id) {
        $model = $this->findModel($id);
        return $this->render('view', [
                    'model' => $model,
                    'modelSorteado' => new Sorteado(),
                    'modelsJogo' => $model->jogos,
        ]);
    }

    public function actionCreate($id) {
        $model = new Sorteio();
        $model->automatico = true;
        $model->categoria_id = $id;
        $model->status = 1;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->data = date('Y-m-d', strtotime(str_replace('/', '-', $model->data)));
            $model->save();

            $postJogo = $_POST['Jogo'];
            if ($postJogo) {
                foreach ($postJogo as $i => $postNumero) {
                    foreach ($postNumero as $j => $postN) {

                        if (split('-', $j)[1] == 0) {
                            $modelJogo = new Jogo();
                            $modelJogo->sorteio_id = $model->sorteio_id;
                            $modelJogo->status = 1;
                            $modelJogo->save();
                        }

                        if ($model->categoria_id == 6) {
                            $postJogoTime = $_POST['JogoTime'];
                            foreach ($postJogoTime as $k => $postJT) {
                                if (split('-', $j)[0] == $k) {
                                    $modelJogoTime = new JogoTime();
                                    $modelJogoTime->jogo_id = $modelJogo->jogo_id;
                                    $modelJogoTime->time_id = $postJT;
                                    $modelJogoTime->save();
                                }
                            }
                        }

                        $modelNumero = new Numero();
                        $modelNumero->jogo_id = $modelJogo->jogo_id;
                        $modelNumero->numero = $postN;
                        $modelNumero->save();
                    }
                }
            }

            return $this->redirect(['view', 'id' => $model->sorteio_id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'modelsTime' => Time::find()->orderBy(['descricao' => SORT_ASC])->asArray()->all(),
                        'id' => $id,
            ]);
        }
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $model->automatico = true;
        $model->data = date('d/m/Y', strtotime($model->data));

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->data = date('Y-m-d', strtotime(str_replace('/', '-', $model->data)));
            $model->save();

            Jogo::deleteAll(['sorteio_id' => $model->sorteio_id]);

            $postJogo = $_POST['Jogo'];
            if ($postJogo) {
                foreach ($postJogo as $i => $postNumero) {
                    foreach ($postNumero as $j => $postN) {

                        if (split('-', $j)[1] == 0) {
                            $modelJogo = new Jogo();
                            $modelJogo->sorteio_id = $model->sorteio_id;
                            $modelJogo->status = 1;
                            $modelJogo->save();
                        }

                        if ($model->categoria_id == 6) {
                            $postJogoTime = $_POST['JogoTime'];
                            foreach ($postJogoTime as $k => $postJT) {
                                if (split('-', $j)[0] == $k) {
                                    $modelJogoTime = new JogoTime();
                                    $modelJogoTime->jogo_id = $modelJogo->jogo_id;
                                    $modelJogoTime->time_id = $postJT;
                                    $modelJogoTime->save();
                                }
                            }
                        }

                        $modelNumero = new Numero();
                        $modelNumero->jogo_id = $modelJogo->jogo_id;
                        $modelNumero->numero = $postN;
                        $modelNumero->save();
                    }
                }
            }

            return $this->redirect(['view', 'id' => $model->sorteio_id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
                        'modelsJogo' => $model->jogos,
                        'modelsTime' => Time::find()->orderBy(['descricao' => SORT_ASC])->asArray()->all(),
            ]);
        }
    }

    public function actionSorteado($id) {
        $model = $this->findModel($id);

        TimeSorteado::deleteAll(['sorteio_id' => $id]);
        Sorteado::deleteAll(['sorteio_id' => $id]);

        if ($model->categoria_id == 6) {
            $postTimeSorteado = $_POST['TimeSorteado'];

            $modelTimeSorteado = new TimeSorteado();
            $modelTimeSorteado->time_id = $postTimeSorteado;
            $modelTimeSorteado->sorteio_id = $model->sorteio_id;
            $modelTimeSorteado->save();
        }

        if ($model->categoria_id = 3) {
            $postSorteado = $_POST['Sorteado'];
            foreach ($postSorteado as $post) {
                foreach ($post as $index => $p) {
                    $modelSorteado = new Sorteado();
                    $modelSorteado->sorteio_id = $id;
                    $modelSorteado->numero = $p;
                    $modelSorteado->indice = split('-', $index)[1];
                    $modelSorteado->save();
                }
            }
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionPdf($id) {
        $model = $this->findModel($id);
        return $this->render('pdf', ['model' => $model]);
    }

    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['index', 'id' => $model->categoria_id]);
    }

    protected function findModel($id) {
        if (($model = Sorteio::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
