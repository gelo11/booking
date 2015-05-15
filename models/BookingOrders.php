<?php

/**
 * This is the model class for table "{{booking_orders}}".
 *
 * The followings are the available columns in table '{{booking_orders}}':
 * @property integer $id
 * @property integer $booking_id
 * @property integer $variant_id
 * @property integer $item_id
 * @property integer $position_id
 * @property double $sum
 * @property integer $qty
 * @property string $comments
 * @property integer $user_id
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Booking $booking
 * @property BookingVariants $variant
 * @property BookingItems $item
 * @property BookingItemsPosition $position
 * @property UserUser $user
 */
class BookingOrders extends YModel
{

    const STATUS_NOT_PAID = 0;
    const STATUS_PAID = 1;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BookingOrders the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{booking_orders}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('booking_id, variant_id, item_id, position_id, creation_date, change_date, booking_date, sum', 'required'),
            array('booking_id, variant_id, item_id, position_id, qty, user_id, status', 'numerical', 'integerOnly' => true),
            array('sum', 'numerical'),
            array('comments', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, booking_id, variant_id, item_id, position_id, booking_date, creation_date, change_date, sum, qty, comments, user_id, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'booking' => array(self::BELONGS_TO, 'Booking', 'booking_id'),
            'variant' => array(self::BELONGS_TO, 'BookingVariants', 'variant_id'),
            'item' => array(self::BELONGS_TO, 'BookingItems', 'item_id'),
            'item_position' => array(self::BELONGS_TO, 'BookingItemsPosition', 'position_id'),
            'user' => array(self::BELONGS_TO, 'UserUser', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'booking_id' => Yii::t('BookingModule.booking', 'Зона бронирования'),
            'variant_id' => Yii::t('BookingModule.booking', 'Конфигурация зоны бронирования'),
            'item_id' => Yii::t('BookingModule.booking', 'Объект бронирования из библиотеки объектов'),
            'position_id' => Yii::t('BookingModule.booking', 'Объект бронирования'),
            'booking' => Yii::t('BookingModule.booking', 'Зона бронирования'),
            'variant' => Yii::t('BookingModule.booking', 'Конфигурация зоны бронирования'),
            'item' => Yii::t('BookingModule.booking', 'Объект бронирования из библиотеки объектов'),
            'item_position' => Yii::t('BookingModule.booking', 'Объект бронирования'),
            'creation_date' => Yii::t('BookingModule.booking', 'Дата создания'),
            'change_date' => Yii::t('BookingModule.booking', 'Дата изменения'),
            'booking_date' => Yii::t('BookingModule.booking', 'Дата бронирования'),
            'sum' => Yii::t('BookingModule.booking', 'Сумма заказа'),
            'qty' => Yii::t('BookingModule.booking', 'Количество билетов'),
            'comments' => Yii::t('BookingModule.booking', 'Комментарии к заказу'),
            'user_id' => Yii::t('BookingModule.booking', 'Заказчик'),
            'status' => Yii::t('BookingModule.booking', 'Статус заказа'),
        );
    }
    
    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
//        $criteria->compare('booking_id', $this->booking_id);
//        $criteria->compare('variant_id', $this->variant_id);
//        $criteria->compare('item_id', $this->item_id);
//        $criteria->compare('position_id', $this->position_id);
        $criteria->with = array('booking', 'variant', 'item', 'item_position');
        $criteria->compare('booking.title', $this->booking, true);
        $criteria->compare('variant.title', $this->variant, true);
        $criteria->compare('item.title', $this->item, true);
        $criteria->compare('item_position.title', $this->item_position, true);
        $criteria->compare('t.sum', $this->sum);
        $criteria->compare('t.creation_date', $this->creation_date);
        $criteria->compare('t.change_date', $this->change_date);
        $criteria->compare('t.booking_date', $this->booking_date);
        $criteria->compare('t.qty', $this->qty);
        $criteria->compare('t.comments', $this->comments, true);
        $criteria->compare('t.user_id', $this->user_id);
        $criteria->compare('t.status', $this->status);
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('attributes' => array(
                    'id',
                    'booking_date',
                    'booking.title',
                    'variant.title',
                    'item.title',
                    'item_position.title',
                )),
        ));
    }

    public function beforeValidate()
    {
        $this->sum = floatval(str_replace(',', '', $this->sum));
        if ($this->isNewRecord) {
            $this->change_date = new CDbExpression('NOW()');
            $this->creation_date = $this->change_date;
        }

        return parent::beforeValidate();
    }

    public function beforeSave()
    {
        $this->change_date = new CDbExpression('NOW()');
        $booking_date = DateTime::createFromFormat('d.m.Y', $this->booking_date);
        $this->booking_date = $booking_date->format('Y-m-d H:i:s');

        if ($this->isNewRecord) {
            $this->creation_date = $this->change_date;
        }

        return parent::beforeSave();
    }

    public function getBookingTitle()
    {
        return ($this->booking === null) ? '' : $this->booking->title;
    }

    public function getVariantTitle()
    {
        return ($this->variant === null) ? '' : $this->variant->title;
    }

    public function getItemTitle()
    {
        return ($this->item === null) ? '' : $this->item->title;
    }

    public function getPositionTitle()
    {
        return ($this->item_position === null) ? '' : $this->item_position->title;
    }

    public function getStatusList()
    {
        return array(
            self::STATUS_PAID => Yii::t('BookingModule.booking', 'Оплачен'),
            self::STATUS_NOT_PAID => Yii::t('BookingModule.booking', 'Не оплачено'),
        );
    }

    public function getPositionItemOrdersQty($position_id, $booking_date)
    {
        $bufer = explode('/', $booking_date);
        $order_date = count($bufer) == 3 ? $bufer[2] . '-' . $bufer[1] . '-' . $bufer[0] : date("Y-m-d");
        $result = BookingOrders::model()->find(array(
            'select' => 'SUM(qty) AS qty',
            'condition' => 'position_id = :position_id AND DATE_FORMAT(`booking_date`,\'%Y-%m-%d\') = :booking_date',
            'params' => array(':position_id' => (int) $position_id, ':booking_date' => $order_date),
        ));
        return (int) $result[qty];
    }

    public function getStatus()
    {
        $data = $this->statusList;
        return isset($data[$this->status]) ? $data[$this->status] : Yii::t('BookingModule.booking', '*неизвестно*');
    }

}