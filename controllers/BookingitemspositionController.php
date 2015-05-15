<?php

class BookingItemsPositionController extends YBackController
{

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new BookingItemsPosition;

        $this->performAjaxValidation($model);

        if (Yii::app()->request->isPostRequest && isset($_POST['BookingItemsPosition'])) {

            $model->setAttributes(Yii::app()->request->getPost('BookingItemsPosition'));
            if ($model->save())
                if (Yii::app()->request->isAjaxRequest) {
                    $this->renderPartial('_form_ajax', array(
                        'model' => $model,
                    ));
                    Yii::app()->end();
                } else {
                    Yii::app()->user->setFlash(
                            YFlashMessages::NOTICE_MESSAGE, Yii::t('BookingModule.booking', 'Объект бронирования добавлен!')
                    );

                    if (!isset($_POST['submit-type']))
                        $this->redirect(array('update', 'id' => $model->id));
                    else
                        $this->redirect(array($_POST['submit-type']));

                    $this->redirect(array('view', 'id' => $model->id));
                }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (Yii::app()->request->isPostRequest && isset($_POST['BookingItemsPosition'])) {
            $model->setAttributes(Yii::app()->request->getPost('BookingItemsPosition'));

            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    echo Yii::t('BookingModule.booking', 'Объект бронирования обновлен!');
                    Yii::app()->end();
                } else {
                    Yii::app()->user->setFlash(
                            YFlashMessages::NOTICE_MESSAGE, Yii::t('BookingModule.booking', 'Объект бронирования обновлен!')
                    );
                    if (!isset($_POST['submit-type']))
                        $this->redirect(array('update', 'id' => $model->id));
                    else
                        $this->redirect(array($_POST['submit-type']));

                    $this->redirect(array('view', 'id' => $model->id));
                }
            } else {
                if (Yii::app()->request->isAjaxRequest) {
                    var_dump($model->getError());
                    Yii::app()->end();
                } else {
                    $this->render('update', array(
                        'model' => $model,
                    ));
                }
            }
        }

        $model->date = date('d.m.Y');

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            $this->loadModel($id)->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!Yii::app()->request->isAjaxRequest) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
            } else {
                echo Yii::t('BookingModule.booking', 'Объект бронирования удален!');
                Yii::app()->end();
            }
        }
        else
            throw new CHttpException(400, Yii::t('BookingModule.booking', 'Неверный запрос. Пожалуйста, больше не повторяйте такие запросы!'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('BookingItemsPosition');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return BookingItemsPosition the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = BookingItemsPosition::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('catalog', 'Запрошенная страница не найдена!'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param BookingItemsPosition $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'booking-items-position-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
