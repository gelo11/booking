<?php

class DefaultController extends YBackController
{

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array('model' => $this->loadModel($id)));
    }

    public function actionDraggable($id)
    {
        $model = $this->loadModel($id);

        if (Yii::app()->request->isPostRequest && isset($_POST['BookingItemsPosition'])) {
            $sql = '';
            $position_model = new BookingItemsPosition;
            foreach ($_POST['BookingItemsPosition'] as $item_id => $items) {
                foreach ($items as $item) {
                    $sql .= "( $item_id, $id, " . $item['pos_x'] . ", " . $item['pos_y'] . ", '" . date("Y-m-d h:i:s") . "', " . Yii::app()->user->getId() . ", " . Booking::STATUS_PUBLISHED . " ),";
                }
            }
            $sql = rtrim($sql, ',');
            $command = Yii::app()->db->createCommand('INSERT INTO {{booking_items_position}} (`item_id`, `booking_id`, `pos_x`, `pos_y`, `date`, `user_id`, `status`) VALUES ' . $sql)->execute();
//            print_r($_POST['BookingItemsPosition']);
        }
        // all avilable items
        $items = $this->bookingItems();
        // items at booking area
        $booking_item_condition = new CDbCriteria();
        $booking_item_condition->distinct = true;
        $booking_item_condition->select = 't.item_id, t.booking_id, t.pos_x, t.pos_y, t.image, t.date, t.user_id, t.status, t.is_protected, 
                                            booking_items.title, booking_items.alias, booking_items.lang, booking_items.image AS main_image, 
                                            booking_items.full_text, booking_items.left, booking_items.left_qty, booking_items.right, 
                                            booking_items.right_qty, booking_items.top, booking_items.top_qty, booking_items.bottom, 
                                            booking_items.bottom_qty, booking_items.width, booking_items.lenght';

        $booking_item_condition->join = 'INNER JOIN {{booking_items}} AS booking_items ON t.`item_id` = `booking_items`.`id`';
//        $booking_item_condition->with = array('booking_items' => array('joinType' => 'INNER JOIN'));
        $booking_item_condition->together = true;
        $booking_item_condition->condition = 'booking_items.status = :status AND t.booking_id = :booking_id';
        $booking_item_condition->params = array(':status' => Booking::STATUS_PUBLISHED, ':booking_id' => $id);
        $booking_item_condition->order = 't.booking_id ASC';
        $booking_items = BookingItemsPosition::model()->findAll($booking_item_condition);
        $this->render('draggable', array('model' => $model, 'items' => $items, 'booking_items' => $booking_items));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Booking;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (Yii::app()->request->isPostRequest && isset($_POST['Booking'])) {
            $model->setAttributes(Yii::app()->request->getPost('Booking'));

            if ($model->save()) {
                Yii::app()->user->setFlash(
                        YFlashMessages::NOTICE_MESSAGE, Yii::t('BookingModule.booking', 'Зона бронирования добавлена!')
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
        $id = (int) Yii::app()->request->getQuery('id');
        $lang = Yii::app()->request->getQuery('lang');

        if (!empty($id) && !empty($lang)) {
            $booking_variants = Booking::model()->findByPk($id);
            if (null === $booking_variants) {
                Yii::app()->user->setFlash(YFlashMessages::ERROR_MESSAGE, Yii::t('BookingModule.booking', 'Целевая зона бронирования не найдена!'));
                $this->redirect(array('/booking/default/create'));
            }
            if (!array_key_exists($lang, $languages)) {
                Yii::app()->user->setFlash(YFlashMessages::ERROR_MESSAGE, Yii::t('BookingModule.booking', 'Язык не найден!'));
                $this->redirect(array('/booking/default/create'));
            }
            Yii::app()->user->setFlash(YFlashMessages::NOTICE_MESSAGE, Yii::t('BookingModule.booking', 'Вы добавляете перевод на {lang} язык!', array(
                        '{lang}' => $languages[$lang]
            )));
            $model->lang = $lang;
            $model->alias = $booking_variants->alias;
            $model->company_id = $booking_variants->company_id;
            $model->title = $booking_variants->title;
            $model->creation_date = date('d.m.Y');
            $model->change_date = date('d.m.Y');
        } else {
            $model->lang = Yii::app()->language;
            $model->creation_date = date('d.m.Y');
            $model->change_date = date('d.m.Y');
        }

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
        $model = $this->loadModel((int) $id);

        if (Yii::app()->request->isPostRequest && isset($_POST['Booking'])) {
            $model->setAttributes(Yii::app()->request->getPost('Booking'));

            if ($model->save()) {
                Yii::app()->user->setFlash(
                        YFlashMessages::NOTICE_MESSAGE, Yii::t('BookingModule.booking', 'Зона бронирования обновлена!')
                );

                if (!isset($_POST['submit-type']))
                    $this->redirect(array('update', 'id' => $model->id));
                else
                    $this->redirect(array($_POST['submit-type']));

                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        // найти по alias страницы на других языках
        $langModels = Booking::model()->findAll('alias = :alias AND id != :id', array(
            ':alias' => $model->alias,
            ':id' => $model->id
        ));

        $this->render('update', array(
            'langModels' => CHtml::listData($langModels, 'lang', 'id'),
            'model' => $model,
            'languages' => $this->yupe->getLanguagesList()
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
        $model = new Booking('search');
        $model->unsetAttributes(); // clear any booking values
        if (isset($_GET['Booking']))
            $model->attributes = $_GET['Booking'];
        $this->render('index', array('model' => $model));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Booking::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('catalog', 'Запрошенная страница не найдена!'));
        return $model;
    }

    /**
     * Return all booking items
     */
    public function bookingItems()
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'status = :status';
        $criteria->params = array(':status' => Booking::STATUS_PUBLISHED);
        $criteria->order = 'title ASC';
        return BookingItems::model()->findAll($criteria);
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'booking-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}