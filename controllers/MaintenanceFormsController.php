<?php

namespace frontend\controllers;

use Yii;
use frontend\models\MaintenanceForms;
use frontend\models\MaintenanceFormsSearch;
use frontend\submodels\MaintenanceFormQuestions;
use frontend\submodels\MaintenanceQuestionDropdowns;
use frontend\models\MaintenanceCompletedSearch;
use common\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MaintenanceFormsController implements the CRUD actions for MaintenanceForms model.
 */
class MaintenanceFormsController extends Controller {

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
     * Lists all MaintenanceForms models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new MaintenanceFormsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MaintenanceForms model.
     * @param integer $_id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new MaintenanceCompletedSearch();
        $dataProvider = $searchModel->searchbyproduct($id);
        return $this->render('view', [
            'question' => $question,
            'model' => $this->findModel($id),
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new MaintenanceForms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MaintenanceForms();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string) $model->_id]);
        } else {
            return $this->render('_form', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new MaintenanceForms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAddQuestion($id) {
        $form = $this->findModel($id);
        $model = new MaintenanceFormQuestions();

        $position_list = array();
        $last = 0;
        $key_to_bump = -1;
        foreach ($form->question as $key => $question) {
            $position_list[$question['position']] = ($question['position']+1).': Before Question "'.$question['label'].'"';
            $last = $question['position'];
        }
        $last++;
        ksort($position_list);
        $position_list[$last] = 'Last Avaliable';

        if ($_POST['MaintenanceFormQuestions']) {
            $model = new MaintenanceFormQuestions($_POST['MaintenanceFormQuestions']);
            if ($model->validate()) {
                if ($model->position == $last)
                    $this->createSubDoc($id, 'maint_forms', 'question', '_id', array_merge(['_id' => new \MongoId()], $_POST['MaintenanceFormQuestions']), 'maintenanceforms');
                else {
                    $collection = Yii::$app->mongodb->getCollection('maint_forms');
                    $form = $collection->findOne(array('_id' => $id));
                    $new_questions = [($model->position) => array_merge(['_id'=>new \MongoId()], $_POST['MaintenanceFormQuestions'])];
                    foreach ($form['question'] as $key => $question) {
                        if ($question['position']>=($model->position)) {
                            $question['position']+=1;
                            $new_questions[$key+1] = $question;
                        }
                        else {
                            $new_questions[$key] = $question;
                        }
                    }
                    unset($form['question']);
                    $form['question'] = $new_questions;
                    ksort($form['question']);
                    $collection->update(array('_id' =>$id), $form); // update that sucka
                }
            }
            return $this->redirect(['view', 'id' => (string) $id]);
        } else {
            return $this->render('_form_questions', [
                        'model' => $model,
                        'maint_form' => $form,
                        'position_list' => $position_list,
            ]);
        }
    }



    /**
     * Creates a new MaintenanceForms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpdateQuestion($id) {
        $id = ($id instanceof MongoId) ? $id : new \MongoId($id);
        $question_obj = $this->findSubDocOne($id, 'maint_forms', 'question');
        $model = new MaintenanceFormQuestions($question_obj['question'][0]);
        $form = $this->findModel((string) $question_obj['_id']);
        $form_id = $form->_id;
        $dropdowns = $question_obj['question'][0]['dropdowns'];

        $position_list = array();
        $last = 0;
        $current = $model->position;
        foreach ($form->question as $key => $question) {
            $position_list[$question['position']] = ($question['position']+1).': Before Question "'.$question['label'].'"';
            $last = $question['position'];
        }
        ksort($position_list);
        if ($model->position != $last) {
            $last++;
            $position_list[$last] = 'Last Avaliable';
        }

        if ($_POST['MaintenanceFormQuestions']) {
            $model = new MaintenanceFormQuestions($_POST['MaintenanceFormQuestions']);
            if ($model->validate()) {
                if ($model->position == $current) {
                    if (!empty($dropdowns)) {
                        if (Yii::$app->mongodb->getCollection('maint_forms')->update(['_id' => $form_id, 'question._id' => $id], ['$set' => ['question.$' => array_merge($_POST['MaintenanceFormQuestions'], ['_id' => $id], ['dropdowns' => $dropdowns])]]))
                            $this->updateFlashMessage($model->label);
                    } else {
                        if (Yii::$app->mongodb->getCollection('maint_forms')->update(['_id' => $form_id, 'question._id' => $id], ['$set' => ['question.$' => array_merge($_POST['MaintenanceFormQuestions'], ['_id' => $id])]]))
                            $this->updateFlashMessage($model->label);
                    }
                }
                else {
                    $collection = Yii::$app->mongodb->getCollection('maint_forms');
                    $form = $collection->findOne(array('_id' => (string) $form_id));

                    //remove the subdoc from the document
                    $new_questions = array();
                    foreach ($form['question'] as $key => $question) {
                        if ($current == $question['position']) {
                            unset($question);
                        }
                        else {
                            $question['position'] = count($new_questions);
                            $new_questions[] = $question;
                        }
                    }

                    //add the sub back in just like how it was created
                    $new_questions_add = array();
                    if($model->position == $last) {
                        $model->position = $model->position-1;
                        $_POST['MaintenanceFormQuestions']['position'] = $model->position;
                    }
                    // $new_questions_add = [($model->position) => array_merge(['_id'=>new \MongoId($id)], $_POST['MaintenanceFormQuestions'])];
                    if (!empty($dropdowns)) {
                        // $new_questions_add[$model->position];
                        $new_questions_add = [($model->position) => array_merge(['_id'=>new \MongoId($id)], $_POST['MaintenanceFormQuestions'], ['dropdowns' => $dropdowns])];
                    } else {
                        $new_questions_add = [($model->position) => array_merge(['_id'=>new \MongoId($id)], $_POST['MaintenanceFormQuestions'])];
                    }
                    foreach ($new_questions as $key => $question) {
                        if ($question['position']>=($model->position)) {
                            $question['position']+=1;
                            $new_questions_add[$key+1] = $question;
                        }
                        else {
                            $new_questions_add[$key] = $question;
                        }
                    }
                    unset($form['question']);
                    $form['question'] = $new_questions_add;
                    ksort($form['question']);
                    $collection->update(array('_id' => (string) $form_id), $form);
                }
            }
            return $this->redirect(['view', 'id' => (string) $form_id]);
        }

        return $this->render('_form_questions', [
                    'model' => $model,
                    'maint_form' => $form,
                    'position_list' => $position_list,
        ]);
    }

    public function actionDeleteQuestion($id) {
        $id = ($id instanceof MongoId) ? $id : new \MongoId($id);
        $question_obj = $this->findSubDocOne($id, 'maint_forms', 'question');
        $model = new MaintenanceFormQuestions($question_obj['question'][0]);
        $form = $this->findModel((string) $question_obj['_id']);
        $form_id = $form->_id;
        $collection = Yii::$app->mongodb->getCollection('maint_forms');
        $form = $collection->findOne(array('_id' => (string) $form_id));
        $new_questions = array();
        foreach ($form['question'] as $key => $question) {
            if ((string) $id == (string) $question['_id']) {
                unset($question);
            }
            else {
                $question['position'] = count($new_questions);
                $new_questions[] = $question;
            }
        }
        unset($form['question']);
        $form['question'] = $new_questions;
        ksort($form['question']);
        $collection->update(array('_id' =>$id), $form); // update that sucka
        if($collection->update(array('_id' => (string) $form_id), $form)) {// update that sucka
            return $this->redirect(['view', 'id' => (string) $form_id]);
        }
    }



    /**
     * Creates a new MaintenanceForms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAddDropdown($id) {
        $id = ($id instanceof MongoId) ? $id : new \MongoId($id);
        $question_obj = $this->findSubDocOne($id, 'maint_forms', 'question');
        $form = MaintenanceForms::find()->where(['_id' => (string) $question_obj['_id']])->asArray()->one();


        if ($question_obj['question'][0]['type'] == 'dropdown') {
            $model = new MaintenanceQuestionDropdowns();
            if ($_POST['MaintenanceQuestionDropdowns']) {
                $model = new MaintenanceQuestionDropdowns($_POST['MaintenanceQuestionDropdowns']);
                if ($model->validate()) {
                    $collection = Yii::$app->mongodb->getCollection('maint_forms');
                    foreach ($form['question'] as $key => $testervar) {
                        if ((string) $testervar['_id'] == (string) $id) {
                            $form['question'][$key]['dropdowns'][] = $_POST['MaintenanceQuestionDropdowns'];
                        }
                    }

                    if($collection->update(array('_id' => $question_obj['_id']), $form)) {
                        return $this->redirect(['view', 'id' => (string) $form['_id']]);
                    }
                }
            } else {
                return $this->render('_form_dropdowns', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * Creates a new MaintenanceForms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionRemoveDropdown($id, $d_id) {
        $_POST['debug'] = [$id, $d_id];
        $id = ($id instanceof MongoId) ? $id : new \MongoId($id);
        $question_obj = $this->findSubDocOne($id, 'maint_forms', 'question');
        $form = MaintenanceForms::find()->where(['_id' => (string) $question_obj['_id']])->asArray()->one();
        foreach ($form['question'] as $key => $testervar) {
            if ((string) $testervar['_id'] == (string) $id) {
                unset($form['question'][$key]['dropdowns'][$d_id]);
                $new_drops = array();
                foreach ($form['question'][$key]['dropdowns'] as $downs) {
                    $new_drops[] = $downs;
                }
                $form['question'][$key]['dropdowns'] = $new_drops;
                $collection = Yii::$app->mongodb->getCollection('maint_forms');
                if($collection->update(array('_id' => $question_obj['_id']), $form)) {
                    return $this->redirect(['view', 'id' => (string) $form['_id']]);
                }
            }
        }
    }

    /**
     * Updates an existing MaintenanceForms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string) $model->_id]);
        } else {
            return $this->render('_form', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MaintenanceForms model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MaintenanceForms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return MaintenanceForms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MaintenanceForms::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
