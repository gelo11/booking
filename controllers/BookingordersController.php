<?php

class BookingOrdersController extends YBackController
{

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array('model' => $this->loadModel($id)));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new BookingOrders;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (Yii::app()->request->isPostRequest && isset($_POST['BookingOrders'])) {
            $model->setAttributes(Yii::app()->request->getPost('BookingOrders'));

            if ($model->save()) {
                Yii::app()->user->setFlash(
                        YFlashMessages::NOTICE_MESSAGE, Yii::t('BookingModule.booking', 'Заказ бронирования добавлен!')
                );

                if (!isset($_POST['submit-type']))
                    $this->redirect(array('update', 'id' => $model->id));
                else
                    $this->redirect(array($_POST['submit-type']));

                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $model->creation_date = date('d.m.Y');
        $model->change_date = date('d.m.Y');

        $this->render('create', array('model' => $model, 'languages' => $languages));
    }
    
    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param null $alias
     * @param integer $id the ID of the model to be updated
     * @throws CHttpException
     * @return void
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (Yii::app()->request->isPostRequest && isset($_POST['BookingOrders'])) {
            $model->setAttributes(Yii::app()->request->getPost('BookingOrders'));

            if ($model->save()) {
                Yii::app()->user->setFlash(
                        YFlashMessages::NOTICE_MESSAGE, Yii::t('BookingModule.booking', 'Заказ бронирования обновлен!')
                );

                if (!isset($_POST['submit-type']))
                    $this->redirect(array('update', 'id' => $model->id));
                else
                    $this->redirect(array($_POST['submit-type']));

                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param null $alias
     * @param integer $id the ID of the model to be deleted
     * @throws CHttpException
     * @return void
     */
    public function actionDelete($id = null)
    {
        if (Yii::app()->request->isPostRequest) {
            $this->loadModel($id)->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
        else
            throw new CHttpException(400, Yii::t('BookingModule.booking', 'Неверный запрос. Пожалуйста, больше не повторяйте такие запросы!'));
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new BookingOrders('search');
        $model->unsetAttributes(); // clear any booking values
        if (isset($_GET['BookingOrders']))
            $model->attributes = $_GET['BookingOrders'];
        $this->render('index', array('model' => $model));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = BookingOrders::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('catalog', 'Запрошенная страница не найдена!'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'booking-order-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}