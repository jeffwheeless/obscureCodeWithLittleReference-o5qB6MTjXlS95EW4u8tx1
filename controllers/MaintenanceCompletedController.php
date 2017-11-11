<?php

namespace frontend\controllers;

use Yii;
use frontend\models\MaintenanceForms;
use frontend\models\MaintenanceCompleted;
use frontend\models\MaintenanceCompletedSearch;
use common\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\submodels\MaintenanceAnswers;
use frontend\models\MaintenanceQuestions;
use frontend\submodels\MaintenanceRevisions;
use frontend\models\Debug;
use yii\base\DynamicModel;

use yii\helpers\HtmlPurifier;
use yii\helpers\Html;


/**
 * MaintenanceCompletedController implements the CRUD actions for MaintenanceCompleted model.
 */
class MaintenanceCompletedController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all MaintenanceCompleted models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new MaintenanceCompletedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MaintenanceCompleted model.
     * @param integer $_id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MaintenanceCompleted model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id, $e_id) {
        $form = MaintenanceForms::find()->where(['_id' => (string) $id])->one();
        $question_list = array();
        $form_questions = array();
        if (is_array($form->question)) {
            foreach ($form->question as $key => $question) {
                $question_list[(string) $question['_id']] = $question;
                $form_questions[$question['question_id']] = $key;
            }
            // $question_query = MaintenanceQuestions::find()->where(['_id' => $question_list])->asArray()->all();
            // unset($question_list);
            // foreach ($question_query as $question) {
            //     $question_list[$form_questions[(string) $question['_id']]] = $question;
            // }
            $model = new MaintenanceCompleted();
            $answers = new MaintenanceAnswers();

            if (Yii::$app->request->post('MaintenanceAnswers')) {
                $model->_id = new \MongoId();
                $model->form = ['title' => $form->form_title, 'desc' => $form->desc];
                $model->date = time();
                $model->rev = 0;
                $answer_list = array();
                foreach ($_POST['MaintenanceAnswers']['answer'] as $id => $answer) {
                    // $_POST['debug'][$id] = $question_list[$id];
                    switch ($question_list[$id]['type']) {
                        case 'todaydate':
                            $validator['type'] = ['answer', 'date'];
                            break;
                        case 'date':
                            $validator['type'] = ['answer', 'date'];
                            break;
                        case 'textarea':
                            $validator['type'] = ['answer', 'string'];
                            break;
                        case 'text':
                            $validator['type'] = ['answer', 'string'];
                            break;
                        // case 'checkbox':
                            // $validator['type'] = ['answer', 'boolean'];
                            // break;
                        case 'dropdown':
                            $validator['type'] = ['answer', 'integer'];
                            break;
                    }
                    if (isset($question_list[$id]['min']))
                        $validator['min'] = ['answer', 'string', 'min'=>$question_list[$id]['min']];
                    else
                        $validator['min'] = ['answer', 'safe'];
                    if (isset($question_list[$id]['max']))
                        $validator['max'] = ['answer', 'string', 'max'=>$question_list[$id]['max']];
                    else
                        $validator['max'] = ['answer', 'safe'];
                    if (isset($question_list[$id]['required']))
                        $validator['max'] = ['answer', 'required'];
                    else
                        $validator['max'] = ['answer', 'safe'];

                    $validator['filter'] = ['answer', 'filter', 'filter' => function ($value) {
                    return Html::encode($value);
                    }];

                    $answers = DynamicModel::validateData(compact('answer'), [
                        $validator['type'],
                        $validator['min'],
                        $validator['max'],
                        $validator['filter'],
                    ]);

                    if ($answers->hasErrors() == true) {
                        // return $this->render('_form', [
                        //             // 'model' => $model,
                        //             'answers' => $answers,
                        //             'form_obj' => $form,
                        //             'questions' => $form->question,
                        // ]);
                        $answer_list[] = ['_id' => new \MongoId(), 'question' => $question_list[$id], 'answer' => $answer, 'status' => '', 'notes' => $answers->getErrors()['answer']];
                    }
                    else {
                        $answer_list[] = ['_id' => new \MongoId(), 'question' => $question_list[$id], 'answer' => $answer, 'status' => '', 'notes' => ''];
                    }
                }
                $model->answer = $answer_list;
                $model->equipment_id = $e_id;
                if ($model->save()) {
                    $this->createFlashMessage('done');
                    return $this->redirect(['//equipment/view', 'id' => (string) $e_id]);
                }
            } else {
                return $this->render('_form', [
                            'answers' => $answers,
                            'form_obj' => $form,
                            'questions' => $form->question,
                ]);
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Updates an existing MaintenanceCompleted model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     */
    public function actionUpdate($id, $a_id) {
        $model = MaintenanceCompleted::find()->where(['_id' => (string) $id])->asArray()->one();
        $revision = new MaintenanceRevisions();

        if ($revision->load(Yii::$app->request->post()) && $revision->validate()) {
            $key_to_change = -1;
            foreach ($model['answer'] as $key => $answer) {
                if ((string) $answer['_id'] == $a_id)
                    $key_to_change = $key;
            }
            $_POST['key'] = $key_to_change;
            $id = new \MongoId($id);
            $collection = Yii::$app->mongodb->getCollection('maint_completed');
            $model['rev'] += 1;
            $model['rev_date'] = time();
            ksort($model);
            $model['answer'][$key_to_change]['revision'][] = array_merge(['_id' => new \MongoId(), 'date' => date('d M Y')], $_POST['MaintenanceRevisions']);
            if ($collection->update(array('_id' =>$id), $model)) {
                return $this->redirect(['view', 'id' => (string) $model['_id']]);
            }
        } else {
            return $this->render('_revise', [
                        'model' => $revision,
            ]);
        }
    }

    /**
     * Deletes an existing MaintenanceCompleted model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MaintenanceCompleted model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return MaintenanceCompleted the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MaintenanceCompleted::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
