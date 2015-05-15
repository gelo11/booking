<?php

class BookingItemController extends YBackController
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
        $model = new BookingItems;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (Yii::app()->request->isPostRequest && isset($_POST['BookingItems']))
        {
            $model->setAttributes(Yii::app()->request->getPost('BookingItems'));

            if ($model->save())
            {
                Yii::app()->user->setFlash(
                    YFlashMessages::NOTICE_MESSAGE,
                    Yii::t('BookingModule.booking', 'Объект бронирования добавлен!')
                );

                if (!isset($_POST['submit-type']))
                    $this->redirect(array('update', 'id' => $model->id));
                else
                    $this->redirect(array($_POST['submit-type']));

                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $languages = $this->yupe->getLanguagesList();

        //если добавляем перевод
        $id = (int)Yii::app()->request->getQuery('id');
        $lang = Yii::app()->request->getQuery('lang');

        if(!empty($id) && !empty($lang)){
            $bookingitems = BookingItems::model()->findByPk($id);
            if(null === $bookingitems){
                Yii::app()->user->setFlash(YFlashMessages::ERROR_MESSAGE,Yii::t('BookingModule.booking','Целевой объект бронирования не найден!'));
                $this->redirect(array('/booking/bookingitem/create'));
            }
            if(!array_key_exists($lang,$languages)){
                Yii::app()->user->setFlash(YFlashMessages::ERROR_MESSAGE,Yii::t('BookingModule.booking','Язык не найден!'));
                $this->redirect(array('/booking/bookingitem/create'));
            }
            Yii::app()->user->setFlash(YFlashMessages::NOTICE_MESSAGE,Yii::t('BookingModule.booking','Вы добавляете перевод на {lang} язык!',array(
                        '{lang}' => $languages[$lang]
                    )));
            $model->lang = $lang;
            $model->alias = $bookingitems->alias;
            $model->title = $bookingitems->title;
        }else{
            $model->lang = Yii::app()->language;
        }

        $this->render('create', array('model' => $model, 'languages' => $languages));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel((int)$id);

        if (Yii::app()->request->isPostRequest && isset($_POST['BookingItems']))
        {
            $model->setAttributes(Yii::app()->request->getPost('BookingItems'));

            if ($model->save())
            {
                Yii::app()->user->setFlash(
                    YFlashMessages::NOTICE_MESSAGE,
                    Yii::t('BookingModule.booking', 'Объект бронирования обновлен!')
                );

                if (!isset($_POST['submit-type']))
                    $this->redirect(array('update', 'id' => $model->id));
                else
                    $this->redirect(array($_POST['submit-type']));

                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        // найти по alias страницы на других языках
        $langModels = BookingItems::model()->findAll('alias = :alias AND id != :id',array(
            ':alias' => $model->alias,
            ':id' => $model->id
        ));

        $this->render('update',array(
            'langModels' => CHtml::listData($langModels,'lang','id'),
            'model' => $model,
            'languages' => $this->yupe->getLanguagesList()
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
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
        else
            throw new CHttpException(400, Yii::t('BookingModule.booking', 'Неверный запрос. Пожалуйста, больше не повторяйте такие запросы!'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new BookingItems('search');
        $model->unsetAttributes(); // clear any booking values
        if (isset($_GET['BookingItems']))
            $model->attributes = $_GET['BookingItems'];
        $this->render('index', array('model' => $model));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new BookingItems('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['BookingItems']))
            $model->attributes = $_GET['BookingItems'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return BookingItems the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = BookingItems::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('catalog', 'Запрошенная страница не найдена!'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param BookingItems $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'booking-items-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
