<?php

class BookingController extends YFrontController
{

    const NEWS_PER_PAGE = 10;

    public function actionShow($alias)
    {
        $company_id = Company::getCompanyId($alias);
        if (!$company_id) {
            throw new CHttpException(404, Yii::t('BookingModule.booking', 'Запрошенная страница не найдена!'));
        } else {
            $company_data = Company::model()->findByPk($company_id);
        }

        if (isset($_GET['order_date'])) {
            $bufer = explode('.', $_GET['order_date']);
            $order_date = count($bufer) == 3 ? $bufer[2] . '-' . $bufer[1] . '-' . $bufer[0] : date("Y-m-d");
        } else {
            $order_date = date("Y-m-d");
        }

        $criteria = new CDbCriteria(array(
            'condition' =>
            't.status = :status AND 
                 booking.status = :status2 AND 
                 t.start_date <= :start_date AND
                 (t.finish_date >= :finish_date OR t.no_finish_date = 1) AND 
                 t.is_default = 1 AND 
                 booking.company_id = :company_id',
            'params' => array(
                ':status' => BookingVariants::STATUS_PUBLISHED,
                ':status2' => Booking::STATUS_PUBLISHED,
                ':start_date' => $order_date . ' 00:00:00',
                ':finish_date' => $order_date . '23:00:00',
                ':company_id' => $company_id,
            ),
            'order' => 't.creation_date DESC',
//            'join' => 'INNER JOIN {{booking}} booking ON booking.id=t.booking_id INNER JOIN {{company_company}} company ON company.id=booking.company_id',
            'with' => array('booking' => array('with' => 'company'))
        ));

        if ($this->isMultilang()) {
            $criteria->mergeWith(array(
                'condition' => 't.lang = :lang',
                'params' => array(':lang' => Yii::app()->language),
            ));
        }

        $model = BookingVariants::model()->find($criteria);

        if ($model) {

            $model->today = isset($_GET['order_date']) ? $_GET['order_date'] : date("d.m.Y");

            // проверим что пользователь может просматривать бронирование
            if ($model->is_protected == Booking::PROTECTED_YES && !Yii::app()->user->isAuthenticated()) {
                $this->redirect(array(Yii::app()->getModule('user')->accountActivationSuccess));
            }

            $model->image = empty($model->image) ? Booking::bookingImage($model->booking_id) : $model->image;

            $imageSize = getimagesize('http://' . $_SERVER['HTTP_HOST'] . Yii::app()->baseUrl . '/' . Yii::app()->getModule('yupe')->uploadPath . '/' .
                    Yii::app()->getModule('booking')->uploadPath . '/' . $model->image);

            $image_params = new stdClass();
            $image_params->width = $imageSize[0];
            $image_params->height = $imageSize[1];

            $items = $this->getItemsPositioning($model->id);

            $this->render('variant', array('model' => $model, 'items' => $items, 'image_params' => $image_params, 'booking_order' => new BookingOrders));
        } else {
            $criteria = new CDbCriteria(array(
                'condition' =>
                't.status = :status AND 
                 booking_variants.status = :status2 AND 
                 booking_variants.start_date <= :start_date AND
                 (booking_variants.finish_date >= :finish_date  OR booking_variants.no_finish_date = 1) AND 
                 t.company_id = :company_id',
                'params' => array(
                    ':status' => Booking::STATUS_PUBLISHED,
                    ':status2' => BookingVariants::STATUS_PUBLISHED,
                    ':start_date' => date("Y-m-d 00:00:00"),
                    ':finish_date' => date("Y-m-d 23:00:00"),
                    ':company_id' => $company_id,
                ),
                'order' => 't.creation_date DESC',
                'join' => 'INNER JOIN {{booking_variants}} booking_variants ON booking_variants.booking_id=t.id',
            ));

            if ($this->isMultilang()) {
                $criteria->mergeWith(array(
                    'condition' => 't.lang = :lang',
                    'params' => array(':lang' => Yii::app()->language),
                ));
            }

            $model = Booking::model()->findAll($criteria);

            $this->render('booking', array('model' => $model, 'company_data' => $company_data));
        }
    }

    public function actionIndex($alias)
    {
        throw new CHttpException(404, Yii::t('BookingModule.booking', 'Запрошенная страница не найдена!'));
    }

    public function actionVariants($alias)
    {
        $booking_id = Booking::getBookingId($alias);
        if (intval($booking_id) == 0) {
            throw new CHttpException(404, Yii::t('BookingModule.booking', 'Запрошенная страница не найдена!'));
        }
        /*
        if (isset($_GET['order_date'])) {
            $bufer = explode('.', $_GET['order_date']);
            $order_date = count($bufer) == 3 ? $bufer[2] . '-' . $bufer[1] . '-' . $bufer[0] : date("Y-m-d");
        } else {
            $order_date = date("Y-m-d");
        }
        
        $criteria = new CDbCriteria(array(
            'condition' =>
            't.status = :status AND 
                 booking.status = :status2 AND 
                 t.start_date <= :start_date AND
                 (t.finish_date >= :finish_date OR t.no_finish_date = 1) AND 
                 booking.id = :booking_id',
            'params' => array(
                ':status' => BookingVariants::STATUS_PUBLISHED,
                ':status2' => Booking::STATUS_PUBLISHED,
                ':start_date' => $order_date . ' 00:00:00',
                ':finish_date' => $order_date . '23:00:00',
                ':booking_id' => $booking_id,
            ),
            'order' => 't.creation_date DESC',
            'join' =>
            'INNER JOIN {{booking}} booking ON booking.id=t.booking_id',
        ));
        */


        $criteria = new CDbCriteria(array(
            'condition' =>
            't.status = :status AND 
                 booking.status = :status2 AND 
                 booking.id = :booking_id',
            'params' => array(
                ':status' => BookingVariants::STATUS_PUBLISHED,
                ':status2' => Booking::STATUS_PUBLISHED,
                ':booking_id' => $booking_id,
            ),
            'order' => 't.creation_date DESC',
            'join' =>
            'INNER JOIN {{booking}} booking ON booking.id=t.booking_id',
        ));

        if ($this->isMultilang()) {
            $criteria->mergeWith(array(
                'condition' => 't.lang = :lang',
                'params' => array(':lang' => Yii::app()->language),
            ));
        }

        $model = BookingVariants::model()->findAll($criteria);
        $this->render('variants', array('model' => $model, 'company_data' => $company_data));
    }

    public function actionVariant($alias)
    {
        $variant_id = BookingVariants::getVariantId($alias);
        if (intval($variant_id) == 0) {
            throw new CHttpException(404, Yii::t('BookingModule.booking', 'Запрошенная страница не найдена!'));
        }

        if (isset($_GET['order_date'])) {
            $bufer = explode('.', $_GET['order_date']);
            $order_date = count($bufer) == 3 ? $bufer[2] . '-' . $bufer[1] . '-' . $bufer[0] : date("Y-m-d");
        }

        $criteria = new CDbCriteria(array(
            'condition' => 't.status = :status AND t.start_date <= :start_date AND (t.finish_date >= :finish_date OR t.no_finish_date = 1)',
            'params' => array(
                ':status' => Booking::STATUS_PUBLISHED,
                ':start_date' => (isset($_GET['order_date']) ? date("Y-m-d h:i:s", strtotime($_GET['order_date'])) : date("Y-m-d 00:00:00")),
                ':finish_date' => (isset($_GET['order_date']) ? date("Y-m-d h:i:s", strtotime($_GET['order_date'])) : date("Y-m-d 23:00:00")),
            ),
        ));

        if ($this->isMultilang()) {
            $criteria->mergeWith(array(
                'condition' => 't.lang = :lang',
                'params' => array(':lang' => Yii::app()->language),
            ));
        }

        $model = BookingVariants::model()->findByPk($variant_id, $criteria);
        if($model === false) {
            throw new CHttpException(404, Yii::t('BookingModule.booking', 'Запрошенная страница не найдена!'));
        }
        $model->today = isset($_GET['order_date']) ? $_GET['order_date'] : date("d.m.Y");

        // проверим что пользователь может просматривать бронирование
        if ($model->is_protected == Booking::PROTECTED_YES && !Yii::app()->user->isAuthenticated()) {
            $this->redirect(array(Yii::app()->getModule('user')->accountActivationSuccess));
        }

        $model->image = empty($model->image) ? Booking::bookingImage($model->booking_id) : $model->image;
        if($model->image === false) {
            throw new CHttpException(404, Yii::t('BookingModule.booking', 'Запрошенная страница не найдена!'));
        }
        $imageSize = getimagesize('http://' . $_SERVER['HTTP_HOST'] . Yii::app()->baseUrl . '/' . Yii::app()->getModule('yupe')->uploadPath . '/' .
                Yii::app()->getModule('booking')->uploadPath . '/' . $model->image);

        $image_params = new stdClass();
        $image_params->width = $imageSize[0];
        $image_params->height = $imageSize[1];

        $items = $this->getItemsPositioning($variant_id);
        
        $this->render('variant', array('model' => $model, 'items' => $items, 'image_params' => $image_params, 'booking_order' => new BookingOrders));
    }

    // save booking order
    public function actionOrder()
    {
        if(!Yii::app()->user->isAuthenticated()) {
            echo Yii::t('BookingModule.booking', 'Пожалуйста, войдите на сайт под своим именем, чтобы оформить заказ!');
            Yii::app()->end();
        }
        
        $model = new BookingOrders;
        
        $item_position = new BookingItemsPosition;

        $this->performAjaxValidation($model);

        if (Yii::app()->request->isPostRequest && isset($_POST['BookingOrders'])) {
            $model->setAttributes(Yii::app()->request->getPost('BookingOrders'));
            
            $bufer = explode('/', $_POST['BookingOrders']['booking_date']);
            $order_date = count($bufer) == 3 ? $bufer[2] . '-' . $bufer[1] . '-' . $bufer[0] : date("Y-m-d");
            
            $order_qty = $model->getPositionItemOrdersQty($_POST['BookingOrders']['position_id'], $order_date);
            
            $whole_item = $item_position->getWholeItem($_POST['BookingOrders']['position_id']);
            
            if($whole_item == 1) {
                if($order_date > 0) {
                    echo Yii::t('BookingModule.booking', 'Этот объект уже забронирован!');
                    Yii::app()->end();
                }
            } else {
                $total_seats = $item_position->getSeats($_POST['BookingOrders']['position_id']);
                if($total_seats + 1 < $order_qty) {
                    echo Yii::t('BookingModule.booking', 'Этот объект уже забронирован!');
                    Yii::app()->end();
                }
            }

            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    echo Yii::t('BookingModule.booking', 'Ваш заказ на бронирование добавлен!');
                    Yii::app()->end();
                } else {
                    Yii::app()->user->setFlash(
                            YFlashMessages::NOTICE_MESSAGE, Yii::t('BookingModule.booking', 'Ваш заказ на бронирование добавлен!')
                    );

                    if (!isset($_POST['submit-type']))
                        $this->redirect(array('update', 'id' => $model->id));
                    else
                        $this->redirect(array($_POST['submit-type']));

                    $this->redirect(array('view', 'id' => $model->id));
                }
            } else {
                echo Yii::t('BookingModule.booking', 'Ваш заказ на бронирование не добавлен. Попробуйте позже');
                Yii::app()->end();
            }
        }

        $model->creation_date = date('d.m.Y');
        $model->change_date = date('d.m.Y');

        $this->render('create', array('model' => $model, 'languages' => $languages));
    }

    public function getItemsPositioning($variant_id)
    {
        $criteria = new CDbCriteria(array(
            'condition' => 't.status = :status AND booking_items.status = :status2 AND t.variant_id = :variant_id',
            'params' => array(
                ':status' => Booking::STATUS_PUBLISHED,
                ':status2' => BookingItems::STATUS_PUBLISHED,
                ':variant_id' => $variant_id,
            ),
            'join' =>
            'INNER JOIN {{booking_items}} booking_items ON booking_items.id=t.item_id 
                 INNER JOIN {{booking_variants}} booking_variants ON booking_variants.id=t.variant_id'
        ));

        if ($this->isMultilang()) {
            $criteria->mergeWith(array(
                'condition' => 'booking_items.lang = :lang',
                'params' => array(':lang' => Yii::app()->language),
            ));
        }

        return BookingItemsPosition::model()->findAll($criteria);
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax'])) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}