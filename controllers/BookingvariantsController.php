<?php

class BookingVariantsController extends YBackController
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
        $model = new BookingVariants;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (Yii::app()->request->isPostRequest && isset($_POST['BookingVariants'])) {
            $model->setAttributes(Yii::app()->request->getPost('BookingVariants'));

            if ($model->save()) {
                Yii::app()->user->setFlash(
                        YFlashMessages::NOTICE_MESSAGE, Yii::t('booking', 'Конфигурация зоны бронирования добавлена!')
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
            $booking_variants = BookingVariants::model()->findByPk($id);
            if (null === $booking_variants) {
                Yii::app()->user->setFlash(YFlashMessages::ERROR_MESSAGE, Yii::t('booking', 'Целевая конфигурация зоны бронирования не найдена!'));
                $this->redirect(array('/booking/bookingvariants/create'));
            }
            if (!array_key_exists($lang, $languages)) {
                Yii::app()->user->setFlash(YFlashMessages::ERROR_MESSAGE, Yii::t('booking', 'Язык не найден!'));
                $this->redirect(array('/booking/bookingvariants/create'));
            }
            Yii::app()->user->setFlash(YFlashMessages::NOTICE_MESSAGE, Yii::t('booking', 'Вы добавляете перевод на {lang} язык!', array(
                        '{lang}' => $languages[$lang]
            )));
            $model->lang = $lang;
            $model->alias = $booking_variants->alias;
            $model->date = $booking_variants->date;
            $model->creation_date = $booking_variants->creation_date;
            $model->change_date = $booking_variants->creation_date;
            $model->start_date = $booking_variants->start_date;
            $model->last_buy_date = $booking_variants->last_buy_date;
            $model->finish_date = $booking_variants->finish_date;
            $model->booking_id = $booking_variants->booking_id;
            $model->title = $booking_variants->title;
        } else {
            $model->date = date('d.m.Y');
            $model->creation_date = date('d.m.Y');
            $model->change_date = date('d.m.Y');
            $model->start_date = date('d.m.Y', mktime() + 1 * 24 * 60 * 60);
            $model->last_buy_date = date('d.m.Y', mktime() + 10 * 24 * 60 * 60);
            $model->finish_date = date('d.m.Y', mktime() + 30 * 24 * 60 * 60);
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
        $model = $this->loadModel((int) $id);

        if (Yii::app()->request->isPostRequest && isset($_POST['BookingVariants'])) {
            $model->setAttributes(Yii::app()->request->getPost('BookingVariants'));

            if ($model->save()) {
                if (isset($_POST['BookingVariantsDates']) && is_array($_POST['BookingVariantsDates'])) {
//                    BookingVariantsDates::model()->deleteAllByAttributes(array('variant_id' => (int) $id));
                    $dates_arr = array();
                    foreach ($_POST['BookingVariantsDates'] as $dates) {
                        $booking_variant_date = new BookingVariantsDates;
                        if (!empty($dates['event_date'])) {
//                            print_r($dates);
                            $dates_arr['variant_id'] = (int) $dates['variant_id'];
                            $dates_arr['event_date'] = $dates['event_date'];
                            $booking_variant_date->setAttributes($dates_arr);
                            $booking_variant_date->save();
                        }
                    }
                }
                Yii::app()->user->setFlash(
                        YFlashMessages::NOTICE_MESSAGE, Yii::t('booking', 'Конфигурация зоны бронирования обновлена!')
                );

                if (!isset($_POST['submit-type']))
                    $this->redirect(array('update', 'id' => $model->id));
                else
                    $this->redirect(array($_POST['submit-type']));

                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $model->image = empty($model->image) ? Booking::bookingImage($model->booking_id) : $model->image;

        $imageSize = getimagesize('http://' . $_SERVER['HTTP_HOST'] . Yii::app()->baseUrl . '/' . Yii::app()->getModule('yupe')->uploadPath . '/' .
                Yii::app()->getModule('booking')->uploadPath . '/' . $model->image);

        $image_params = new stdClass();
        $image_params->width = $imageSize[0];
        $image_params->height = $imageSize[1];

        // найти по alias страницы на других языках
        $langModels = BookingVariants::model()->findAll('alias = :alias AND id != :id', array(
            ':alias' => $model->alias,
            ':id' => $model->id
        ));

        // all avilable items
        $items = $this->bookingItems();

        $positioning_items = $this->bookingPositioningItems($id);

        $this->render('update', array(
            'langModels' => CHtml::listData($langModels, 'lang', 'id'),
            'model' => $model,
            'languages' => $this->yupe->getLanguagesList(),
            'items' => $items,
            'positioning_items' => $positioning_items,
            'image_params' => $image_params,
            'dates' => new BookingVariantsDates,
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
            throw new CHttpException(400, Yii::t('booking', 'Неверный запрос. Пожалуйста, больше не повторяйте такие запросы!'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new BookingVariants('search');
        $model->unsetAttributes(); // clear any booking values
        if (isset($_GET['BookingVariants']))
            $model->attributes = $_GET['BookingVariants'];
        $this->render('index', array('model' => $model));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return BookingVariants the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = BookingVariants::model()->findByPk($id);
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
     * Return positioning at booking area items
     */
    public function bookingPositioningItems($variant_id)
    {
        $criteria = new CDbCriteria();
        $criteria->distinct = true;
        $criteria->with = array('booking_items');
        $criteria->condition = 'booking_items.status = :status AND t.booking_id = :booking_id';
        $criteria->params = array(':status' => Booking::STATUS_PUBLISHED, ':booking_id' => $variant_id);
        $criteria->order = 't.id ASC';
        $positioning_items = BookingItemsPosition::model()->findAll($criteria);
        return $positioning_items === null ? false : $positioning_items;
    }

    /**
     * Performs the AJAX validation.
     * @param BookingVariants $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'booking-variants-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
