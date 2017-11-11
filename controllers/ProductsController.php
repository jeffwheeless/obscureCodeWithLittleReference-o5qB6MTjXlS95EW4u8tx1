<?php

 

namespace frontend\controllers;

use Yii;
use frontend\models\Products;
use frontend\models\ProductsSearch;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\base\Model;
use frontend\models\Files;
use frontend\models\Specs;
use frontend\models\Equipment;
use frontend\submodels\Firmware;
use frontend\submodels\Categories;
use frontend\submodels\PartsSub;
use frontend\submodels\Specifications;
use frontend\submodels\Productfiles;
use common\components\Controller;
use yii\mongodb\Query;
use yii\mongodb\Collection;
use frontend\models\MaintenanceForms;
use frontend\models\MaintenanceFormsSearch;
use frontend\submodels\MaintenanceFormQuestions;
use frontend\submodels\MaintenanceQuestionDropdowns;
use frontend\models\MaintenanceCompletedSearch;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller {

    /**
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id, $slug = NULL) {
        $model = $this->findModel($id);
        $query = new Query;
        $query->from('categories');
        if (!empty($model->categories)) {
            foreach ($model->categories as $category)
                $query->orWhere(['_id' => (string) $category['name']]);
            $categories = $query->all();
        }
        $parts = $model->parts;
        $firmware = $model->firmware;
        $specs = $model->specs;
        $files = $model->files;
        $searchModel = new MaintenanceFormsSearch();
        $dataProvider = $searchModel->searchbyproduct($id);
        return $this->render('view', [
            'model' => $model,
            'parts' => $parts,
            'categories' => $categories,
            'firmware' => $firmware,
            'specs' => $specs,
            'files' => $files,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($mfg = NULL) {
        $model = new Products();
        $model->mfg = $mfg;
        $modelimage = new Files();
        if ($modelimage->load(Yii::$app->request->post()) && $model->load(Yii::$app->request->post()) && Model::validateMultiple([$model, $modelimage])) {
            $file = UploadedFile::getInstance($modelimage, 'file');
            $model->mfg_name = $model->manufacturer->name;
            $model->pmfg = $model->manufacturer->parentCompany->_id;
            $model->pmfg_name = $model->manufacturer->parentCompany->name;
            if (empty($file)) {
                $model->uploaded_image = 0;
            }
            $model->save(false); // skip validation as model is already validated
            if (empty($file)) {
                $createFile = Yii::$app->TextToImage->createImage($model->model, 600, 600, TRUE, TRUE);
                $file = realpath(dirname(dirname(__FILE__))) . '/web/' . $createFile;
                $unlinkfile = TRUE;
            }
            $modelimage->filename = BaseInflector::slug(Html::encode($model->model) . ' Company Logo', '_') . '.' . $file->extension;
            $modelimage->description = Html::encode($model->model) . ' Company Logo';
            $modelimage->_id = $model->_id;
            $modelimage->contentType = $file->type;
            $modelimage->file = $file;
            $modelimage->save(false);
            if ($unlinkfile == TRUE && $model->model != $oldName) {
                unlink($file);
            }
            $this->createFlashMessage($model->model);
            return $this->redirect(['view', 'id' => (string) $model->_id]);
        } else {
            return $this->render('_form', [
                        'model' => $model,
                        'modelimage' => $modelimage
            ]);
        }
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $modelimage = new Files();
//set old name so we can check if it changed
        $oldName = $model->model;
//set old MFG so we don't query if it wasn't changed
        $oldMfg = $model->mfg;
        if ($modelimage->load(Yii::$app->request->post()) && $model->load(Yii::$app->request->post()) && Model::validateMultiple([$model, $modelimage])) {
//if the MFG changed call relation
            if ($oldMfg != $model->mfg) {
                $model->mfg_name = $model->manufacturer->name;
                $model->pmfg = $model->manufacturer->parentCompany->_id;
                $model->pmfg_name = $model->manufacturer->parentCompany->name;
            }
//get uploaded image
            $file = UploadedFile::getInstance($modelimage, 'file');
            if (isset($file)) {
//if user uploaded image then set flag so we don't create one
                $model->uploaded_image = 1;
            }
//save model and skip validation since we already validated
            $model->save(false);
//check if a user has uploaded a new product image.
//file is not empty & model name hasnt changed. 2. upload flag is true & name hasnt changed
            if (!empty($file) && isset($file)) {
//search for an old image
                $oldFile = Files::findOne($id);
//if found delete the old image since they use the same _id we do it first
                !$oldFile? : $oldFile->delete();
//if user has never uploaded a image and the name changed create a new one
                if ($model->model != $oldName) {

                }
//set and save other gridfs image attributes
                $modelimage->filename = BaseInflector::slug(Html::encode($model->model) . ' Product Image', '_') . '.' . $file->extension;
                $modelimage->description = Html::encode($model->model) . ' Product Image';
                $modelimage->_id = $model->_id;
                $modelimage->contentType = $file->type;
                $modelimage->file = $file;
                $modelimage->save(false);
            } elseif ($model->model != $oldName && $model->uploaded_image == 0) {
                $oldFile = Files::findOne($id);
//if found delete the old image since they use the same _id we do it first
                if (isset($oldFile)) {
                    $oldFile->delete();
                }
                $createFile = Yii::$app->TextToImage->createImage($model->model, 300, 600, TRUE, TRUE);
                $file = realpath(dirname(dirname(__FILE__))) . '/web/' . $createFile;
//if system generated image delete temp imgage from directory since it's been saved in gridfs
                $modelimage->filename = BaseInflector::slug(Html::encode($model->model) . ' Product Image', '_') . '.' . $file->extension;
                $modelimage->description = Html::encode($model->model) . ' Product Image';
                $modelimage->_id = $model->_id;
                $modelimage->contentType = $file->type;
                $modelimage->file = $file;
                $modelimage->save(false);
                unlink($file);
                $equipment = Equipment::find()->where(['p_id' => (string) $model->_id])->all();
                $equipmentCount = 0;
                if ($equipment != NULL) {
                    foreach ($equipment as $equipment) {
                        if ($equipment->mfg == (string) $model->_id) {
                            $equipment->mfg_name = $model->model;
                            $equipmentCount++;
                        }
                        $product->update(FALSE);
                    }
                }
            }
            Yii::$app->getSession()->setFlash('updatedproducts', ['type' => 'info', 'delay' => 3, 'duration' => 14000, 'icon' => 'fa fa-edit', 'title' => 'Company Updated!',
                'message' => 'You have successfully updated ' . $productCount . '  pieces of equipment product information".'
            ]);
            return $this->redirect(['view', 'id' => (string) $model->_id]);
        } else {
            return $this->render('_form', [
                        'model' => $model,
                        'modelimage' => $modelimage
            ]);
        }
    }

    public function actionParts($id) {
        $model = new PartsSub(['scenario' => PartsSub::TYPE_CREATE]);
        $product = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!isset($model->alt_group) || $model->alt_group === '') {
                $highest = 0;
                if (isset($product->parts)) {
                    foreach ($product->parts as $part) {
                        if ($highest < $part['alt_group']) {
                            $highest = $part['alt_group'];
                        }
                    }
                }
                $highest++;
            } else {
                $highest = $model->alt_group;
            }
            if ($this->createSubDoc($id, 'products', 'parts', '_id', array_merge(['_id' => new \MongoId()], Yii::$app->request->post('PartsSub'), ['alt_group' => new \MongoInt32($highest)]), 'parts')) {
                return $this->redirect(['view', 'id' => (string) $id]);
            }
        } else {
            return $this->render('_form_parts', [
                        'product' => $product,
                        'model' => $model,
            ]);
        }
    }

    public function actionUpdatePart($id) {

        $id = ($id instanceof MongoId) ? $id : new \MongoId($id);
        $product = Products::find()->where(['parts._id' => $id])->one();
        $part = $this->findSubDocOne($id, 'products', 'parts');
        $sub_id = $part['parts'][0]['parts'];

        $model = new PartsSub($part['parts'][0], ['scenario' => PartsSub::TYPE_UPDATE]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!isset($model->alt_group) || empty($model->alt_group)) { //if (!isset($model->alt_group) || $model->alt_group == '') {
                unset($model->alt_group);
                $highest = 0;
                foreach ($product->parts as $part) {
                    if ($highest <= $part['alt_group']) {
                        $highest = $part['alt_group'];
                    }
                }
                $highest = $highest + 1;
                $part_array[] = []; // were sorry
                $key_giver = 0;
                foreach ($product->parts as $key => $part_a) {
                    if ((string) $part_a['_id'] != $id) {
                        $part_array[$key] = $part_a;
                    } else {
                        $key_giver = $key;
                    }
                }
                $part_array[$key_giver] = $_POST['PartsSub'];
                $part_array[$key_giver]['_id'] = new \MongoId($id);
                $part_array[$key_giver]['parts'] = $sub_id;
                $part_array[$key_giver]['alt_group'] = $highest;
                ksort($part_array);
                $collection = Yii::$app->mongodb->getCollection('products');
                $collection->update(array('_id' => new \MongoId($product->_id)), array('parts' => $part_array));
            } else {
                $model->alt_group;
                Yii::$app->mongodb->getCollection('products')->update(['_id' => $part['_id'], 'parts._id' => $id], ['$set' => ['parts.$' => array_merge(Yii::$app->request->post('PartsSub'), ['_id' => $id,
                            'parts' => $sub_id, 'alt_group' => $model->alt_group])]]);
            }

            $this->updateFlashMessage('this part');
            return $this->redirect(['view', 'id' => (string) $product->_id]);
        }
        return $this->render('_form_parts', [
                    'model' => $model,
                    'product' => new Products($part),
        ]);
    }

    public function actionDeletePart($id) {
        $model = $this->deleteSubDocById($id, 'products', 'parts', '_id');
        return $this->redirect(['view', 'id' => $model]);
    }

    /**
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $this->deleteFlashMessage($model->model);
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Creates a new File Sub Document.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateFile($id) {
        $model = new Productfiles();
        $document = new Files();
        $id = ($id instanceof MongoId) ? $id : new \MongoId($id);
        if ($document->load(Yii::$app->request->post()) && $model->load(Yii::$app->request->post()) && Model::validateMultiple([$model, $document])) {
            $file = UploadedFile::getInstance($document, 'file');
            $document->filename = BaseInflector::slug(Html::encode($model->file_name), '_') . '.' . $file->extension;
            $document->description = Html::encode($model->file_name) . ' Company Logo';
            $document->_id = new \MongoId();
            $document->contentType = $file->type;
            $document->file = $file;
            $document->save(false);
            if ($this->createSubDocByIdImage($id, $document->_id, 'products', 'files', '_id', Yii::$app->request->post('Productfiles'), 'file'))
                return $this->redirect(['view', 'id' => (string) $id]);
        } else {
            return $this->render('_form_file', [
                        'model' => $model,
                        'id' => $id,
                        'document' => $document
            ]);
        }
    }

    /**
     * Update a single existing  product subdocument.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpdateFile($id) {
        $id = ($id instanceof MongoId) ? $id : new \MongoId($id);
        $file = $this->findSubDocOne($id, 'products', 'files');
        $document = new Files();
        $model = new Productfiles($file['files'][0]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (Yii::$app->mongodb->getCollection('products')->update(['_id' => $file[_id], 'files._id' => $id], ['$set' => ['files.$' => array_merge(Yii::$app->request->post('Productfiles'), ['_id' => $id])]]))
                $this->updateFlashMessage($model->file_name);
            return $this->redirect(['view', 'id' => (string) $file[_id]]);
        }
        return $this->render('_form_file', [
                    'model' => $model,
                    'product' => new Products($file),
                    'document' => $document
        ]);
    }

    /**
     * Delets a single File Subdocument.
     * If deletion  is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDeleteFile($id) {
        $model = $this->deleteSubDocById($id, 'products', 'files', '_id', 'name');
        return $this->redirect(['view', 'id' => $model]);
    }

    public function actionCategory($id) {
        $model = new Categories;
        $product = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($this->createSubDoc($id, 'products', 'categories', '_id', array_merge(['_id' => new \MongoId()], Yii::$app->request->post('Categories')), 'categories')) {
                return $this->redirect(['view', 'id' => (string) $id]);
            }
        } else {
            return $this->render('_category', [
                        'id' => $id,
                        'model' => $model,
                        'product' => $product,
            ]);
        }
    }

    /**
     * Delets a single Contact Subdocument.
     * If deletion  is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDeleteCategory($id) {
        $model = $this->deleteSubDocById($id, 'products', 'categories', '_id', 'name');
        return $this->redirect(['view', 'id' => (string) $model]);
    }

    /**
     * Creates a new Contact Sub Document.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateFirmware($id) {
        $model = new Firmware();
        $id = ($id instanceof MongoId) ? $id : new \MongoId($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($this->createSubDoc($id, 'products', 'firmware', '_id', array_merge(['_id' => new \MongoId()], Yii::$app->request->post('Firmware')), 'firmware')) {
                return $this->redirect(['view', 'id' => (string) $id]);
            }
        } else {
            return $this->render('_form_firmware', [
                        'model' => $model,
                        'id' => $id
            ]);
        }
    }

    /**
     * Update a single existing  product subdocument.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpdateFirmware($id) {
        $id = ($id instanceof MongoId) ? $id : new \MongoId($id);
        $firmware = $this->findSubDocOne($id, 'products', 'firmware');
        $model = new Firmware($firmware['firmware'][0]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (Yii::$app->mongodb->getCollection('products')->update(['_id' => $firmware[_id], 'firmware._id' => $id], ['$set' => ['firmware.$' => array_merge(Yii::$app->request->post('Firmware'), ['_id' => $id])]]))
                $this->updateFlashMessage($model->type);
            return $this->redirect(['view', 'id' => (string) $firmware[_id]]);
        }
        return $this->render('_form_firmware', [
                    'model' => $model,
                    'product' => new Products($firmware),
        ]);
    }

    /**
     * Delets a single Firmware Subdocument.
     * If deletion  is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDeleteFirmware($id) {
        $model = $this->deleteSubDocById($id, 'products', 'firmware', '_id', 'name');
        return $this->redirect(['view', 'id' => $model]);
    }

    /**
     * Creates a new Specification Sub Document.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateSpecifications($id) {
        $model = new Specifications();
        $id = ($id instanceof MongoId) ? $id : new \MongoId($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $existing = Specs::find()->where(['name' => $model->name])->one();
            if (!isset($existing) || empty($existing)) {
                $not_existing = new Specs;
                $not_existing->name = $model->name;
                $not_existing->freq = 1;
                $not_existing->save();
                if ($this->createSubDoc($id, 'products', 'specs', '_id', array_merge(['_id' => new \MongoId()], Yii::$app->request->post('Specifications')), 'specs')) {
                    return $this->redirect(['view', 'id' => (string) $id]);
                }
            } else {
                $existing->freq+=1;
                $existing->save();
                if ($this->createSubDoc($id, 'products', 'specs', '_id', array_merge(['_id' => new \MongoId()], Yii::$app->request->post('Specifications')), 'specs')) {
                    return $this->redirect(['view', 'id' => (string) $id]);
                }
            }
        } else {
            return $this->render('_form_specs', [
                        'model' => $model,
                        'id' => $id
            ]);
        }
    }

    /**
     * Update a single existing  product subdocument.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpdateSpecifications($id) {
        $id = ($id instanceof MongoId) ? $id : new \MongoId($id);
        $spec = $this->findSubDocOne($id, 'products', 'specs');
        $model = new Specifications($spec['specs'][0]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $existing = Specs::find()->where(['name' => $model->name])->one();
            if ($spec['specs'][0]['name'] != $model->name && (!isset($existing) || empty($existing))) { //changed to new spec and needs to save new data
                $current = Specs::find()->where(['name' => $spec['specs'][0]['name']])->one();
                $current->freq -= 1;
                $current->save();
                $not_existing = new Specs;
                $not_existing->name = $model->name;
                $not_existing->freq = 1;
                $not_existing->save();
                if ($current->freq <= 0) {
                    $current->delete();
                }
            } elseif ($spec['name'] != $model->name && (isset($existing) && !empty($existing))) { //changed to new spec but is existing in collection somwhere
                $current = Specs::find()->where(['name' => $spec['specs'][0]['name']])->one();
                $current->freq -= 1;
                $current->save();
                $existing->freq += 1;
                $existing->save();
                if ($current->freq <= 0) {
                    $current->delete();
                }
            } elseif ($spec['specs'][0]['name'] == $model->name) { //didnt do shit
            }
            if (Yii::$app->mongodb->getCollection('products')->update(['_id' => $spec[_id], 'specs._id' => $id], ['$set' => ['specs.$' => array_merge(Yii::$app->request->post('Specifications'), ['_id' => $id])]]))
                $this->updateFlashMessage($model->value);
            return $this->redirect(['view', 'id' => (string) $spec[_id]]);
        }
        return $this->render('_form_specs', [
                    'model' => $model,
                    'product' => new Products($spec),
        ]);
    }

    /**
     * Delets a single Firmware Subdocument.
     * If deletion  is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDeleteSpecifications($id) {
        $id = ($id instanceof MongoId) ? $id : new \MongoId($id);
        $spec = $this->findSubDocOne($id, 'products', 'specs');
        $marked_for_death = new Specifications($spec['specs'][0]);
        $model = $this->deleteSubDocById($id, 'products', 'specs', '_id', 'value');
        $current = Specs::find()->where(['name' => $marked_for_death->name])->one();
        $current->freq -= 1;
        $current->save();
        if ($current->freq <= 0) {
            $current->delete();
        } else {
            $current->save();
        }
        return $this->redirect(['view', 'id' => $model]);
    }

    /**
     * Creates a new MaintenanceForms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateMaintenance($id) {
        $model = new MaintenanceForms();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->product = $id;
            if ($model->save()) {
                $this->createFlashMessage($model->form_title);
                return $this->redirect(['view', 'id' => (string) $id]);
            }
        } else {
            return $this->render('_form_maintenance', [
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
        $form = MaintenanceForms::find()->where(['_id' => $id])->one();
        $model = new MaintenanceFormQuestions();

        $position_list = array();
        $last = 0;
        $key_to_bump = -1;
        if (isset($form->question) && !empty($form->question) && is_array($form->question)) {
            foreach ($form->question as $key => $question) {
                $position_list[$question['position']] = ($question['position']+1).': Before Question "'.$question['label'].'"';
                $last = $question['position'];
            }
            $last++;
            ksort($position_list);
            $position_list[$last] = 'Last Avaliable';
            krsort($position_list);
        } else {
            $position_list[$last] = 'Last Avaliable';
        }

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
            return $this->redirect(['view', 'id' => (string) $form['product']]);
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
        $form = MaintenanceForms::find()->where(['_id' => (string) $question_obj['_id']])->one();
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
        krsort($position_list);

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
            return $this->redirect(['view', 'id' => (string) $form['product']]);
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
        $form = MaintenanceForms::find()->where(['_id' => (string) $question_obj['_id']])->one();
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
            return $this->redirect(['view', 'id' => (string) $form['product']]);
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
                        return $this->redirect(['view', 'id' => (string) $form['product']]);
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
                    return $this->redirect(['view', 'id' => (string) $form['product']]);
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
    public function actionUpdateMaintenance($id) {
        $model = MaintenanceForms::find()->where(['_id' => $id])->one();

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
    public function actionDeleteMaintenance($id) {
        $model = MaintenanceForms::find()->where(['_id' => $id])->one();
        $model->delete();
        $this->deleteFlashMessage($model->form_title);
        return $this->redirect(['view', 'id' => (string) $model->product]);
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
