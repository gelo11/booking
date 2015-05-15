<?php

/**
 * This is the model class for table "{{booking_items_position}}".
 *
 * The followings are the available columns in table '{{booking_items_position}}':
 * @property integer $item_id
 * @property integer $booking_id
 * @property integer $pos_x
 * @property integer $pos_y
 * @property string $image
 * @property string $date
 * @property integer $user_id
 * @property integer $status
 * @property integer $is_protected
 *
 * The followings are the available model relations:
 * @property UserUser $user
 * @property Booking $booking
 */
class BookingItemsPosition extends YModel
{

    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_MODERATION = 2;
    const PROTECTED_NO = 0;
    const PROTECTED_YES = 1;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BookingItemsPosition the static model class
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
        return '{{booking_items_position}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, full_text, color, border_color, border_radius', 'filter', 'filter' => 'trim'),
            array('title, color, border_color, border_radius', 'filter', 'filter' => 'strip_tags'),
            array('item_id, booking_id, variant_id, pos_x, pos_y', 'required'),
            array('item_id, booking_id, variant_id, width, height, user_id, status, is_protected, seats, price_per_seats, whole_item, transparent', 'numerical', 'integerOnly' => true),
            array('pos_x, pos_y, price', 'numerical'),
            array('title, image', 'length', 'max' => 255),
            array('border_radius', 'length', 'max' => 40),
            array('color, border_color', 'length', 'max' => 16),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('item_id, booking_id, variant_id, pos_x, pos_y, title, image, date, user_id, status, is_protected, seats, price, price_per_seats, whole_item', 'safe', 'on' => 'search'),
        );
    }

    public function behaviors()
    {
        $module = Yii::app()->getModule('booking');
        return array(
            'imageUpload' => array(
                'class' => 'application.modules.yupe.models.ImageUploadBehavior',
                'scenarios' => array('insert', 'update'),
                'attributeName' => 'image',
                'minSize' => $module->minSize,
                'maxSize' => $module->maxSize,
                'types' => $module->allowedExtensions,
                'uploadPath' => $module->getUploadPath(),
                'imageNameCallback' => array($this, 'generateFileName'),
                'resize' => array(
                    'quality' => 95,
                    'width' => 800,
                )
            ),
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
            'user' => array(self::BELONGS_TO, 'UserUser', 'user_id'),
            'booking' => array(self::BELONGS_TO, 'Booking', 'booking_id'),
            'variant' => array(self::MANY_MANY, 'BookingVariants',
                '{{booking_items_position}}(item_id, variant_id)'),
            'booking_items' => array(self::BELONGS_TO, 'BookingItems', 'item_id'),
        );
    }

    public function generateFileName()
    {
        return md5($this->title . time());
    }

    public function scopes()
    {
        return array(
            'published' => array(
                'condition' => 'status = :status',
                'params' => array(':status' => self::STATUS_PUBLISHED),
            ),
            'protected' => array(
                'condition' => 'is_protected = :is_protected',
                'params' => array(':is_prtected' => self::PROTECTED_YES),
            ),
            'public' => array(
                'condition' => 'is_protected = :is_protected',
                'params' => array(':is_protected' => self::PROTECTED_NO),
            ),
            'recent' => array(
                'order' => 'creation_date DESC',
                'limit' => 5,
            )
        );
    }

    public function last($num)
    {
        $this->getDbCriteria()->mergeWith(array(
            'order' => 'date_creation DESC',
            'limit' => $num,
        ));
        return $this;
    }

    public function language($lang)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'lang = :lang',
            'params' => array(':lang' => $lang),
        ));
        return $this;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'item_id' => Yii::t('BookingModule.booking', 'Объект бронирования'),
            'booking_id' => Yii::t('BookingModule.booking', 'Зона бронирования'),
            'seats' => Yii::t('BookingModule.booking', 'Количество мест'),
            'price' => Yii::t('BookingModule.booking', 'Цена'),
            'price_per_seats' => Yii::t('BookingModule.booking', 'Цена за место'),
            'pos_x' => Yii::t('BookingModule.booking', 'Координаты X'),
            'pos_y' => Yii::t('BookingModule.booking', 'Координаты Y'),
            'whole_item' => Yii::t('BookingModule.booking', 'Бронирование всего объекта'),
            'width' => Yii::t('BookingModule.booking', 'Ширина'),
            'height' => Yii::t('BookingModule.booking', 'Высота'),
            'title' => Yii::t('BookingModule.booking', 'Заголовок'),
            'full_text' => Yii::t('BookingModule.booking', 'Полный текст'),
            'image' => Yii::t('BookingModule.booking', 'Изображение'),
            'date' => Yii::t('BookingModule.booking', 'Дата создания'),
            'user_id' => Yii::t('BookingModule.booking', 'Автор'),
            'status' => Yii::t('BookingModule.booking', 'Статус'),
            'is_protected' => Yii::t('BookingModule.booking', 'Доступ: * только для авторизованных пользователей'),
            'color' => Yii::t('BookingModule.booking', 'Цвет заливки'),
            'border_color' => Yii::t('BookingModule.booking', 'Цвет границы'),
            'transparent' => Yii::t('BookingModule.booking', 'Прозрачность'),
            'border_radius' => Yii::t('BookingModule.booking', 'Радиус скругления углов'),
        );
    }

    /**
     * @return array customized attribute descriptions (name=>description)
     */
    public function attributeDescriptions()
    {
        return array(
            'item_id' => Yii::t('BookingModule.booking', 'Объект бронирования'),
            'booking_id' => Yii::t('BookingModule.booking', 'Зона бронирования'),
            'seats' => Yii::t('BookingModule.booking', 'Количество мест'),
            'price' => Yii::t('BookingModule.booking', 'Цена'),
            'price_per_seats' => Yii::t('BookingModule.booking', 'Цена за место'),
            'pos_x' => Yii::t('BookingModule.booking', 'Координаты X'),
            'pos_y' => Yii::t('BookingModule.booking', 'Координаты Y'),
            'whole_item' => Yii::t('BookingModule.booking', 'Бронирование всего объекта'),
            'width' => Yii::t('BookingModule.booking', 'Ширина'),
            'height' => Yii::t('BookingModule.booking', 'Высота'),
            'title' => Yii::t('BookingModule.booking', 'Заголовок'),
            'full_text' => Yii::t('BookingModule.booking', 'Полный текст'),
            'image' => Yii::t('BookingModule.booking', 'Изображение'),
            'date' => Yii::t('BookingModule.booking', 'Дата создания'),
            'user_id' => Yii::t('BookingModule.booking', 'Автор'),
            'status' => Yii::t('BookingModule.booking', 'Статус'),
            'is_protected' => Yii::t('BookingModule.booking', 'Доступ: * только для авторизованных пользователей'),
            'color' => Yii::t('BookingModule.booking', 'Цвет заливки'),
            'border_color' => Yii::t('BookingModule.booking', 'Цвет границы'),
            'transparent' => Yii::t('BookingModule.booking', 'Прозрачность. Значение в от 1 до 10.'),
            'border_radius' => Yii::t('BookingModule.booking', 'Радиус скругления углов. Формат: либо 5px либо 5px 15px 6px 5px'),
        );
    }

    public function beforeSave()
    {
        $this->date = date('Y-m-d', strtotime($this->date));

        if ($this->isNewRecord) {
            $this->date = date('Y-m-d h:i:s');
            $this->user_id = Yii::app()->user->getId();
        }

        return parent::beforeSave();
    }

    public function afterFind()
    {
        $this->date = date('d.m.Y', strtotime($this->date));

        return parent::afterFind();
        ;
    }

    public function getStatusList()
    {
        return array(
            self::STATUS_DRAFT => Yii::t('BookingModule.booking', 'Черновик'),
            self::STATUS_PUBLISHED => Yii::t('BookingModule.booking', 'Опубликовано'),
            self::STATUS_MODERATION => Yii::t('BookingModule.booking', 'На модерации'),
        );
    }

    public function getStatus()
    {
        $data = $this->statusList;
        return isset($data[$this->status]) ? $data[$this->status] : Yii::t('BookingModule.booking', '*неизвестно*');
    }

    public function getProtectedStatusList()
    {
        return array(
            self::PROTECTED_NO => Yii::t('BookingModule.booking', 'нет'),
            self::PROTECTED_YES => Yii::t('BookingModule.booking', 'да'),
        );
    }

    public function getProtectedStatus()
    {
        $data = $this->protectedStatusList;
        return isset($data[$this->is_protected]) ? $data[$this->is_protected] : Yii::t('BookingModule.booking', '*неизвестно*');
    }

    public function getImageUrl()
    {
        if ($this->image)
            return Yii::app()->baseUrl . '/' . Yii::app()->getModule('yupe')->uploadPath . '/' .
                    Yii::app()->getModule('news')->uploadPath . '/' . $this->image;
        return false;
    }

    public function getSeats($id)
    {
        $result = self::model()->find(array(
            'select' => 'seats',
            'condition' => 'id = :id',
            'params' => array(':id' => (int) $id),
        ));
        return $result['seats'] ? (int) $result['seats'] : false;
    }

    public function getWholeItem($id)
    {
        $result = self::model()->find(array(
            'select' => 'whole_item',
            'condition' => 'id = :id',
            'params' => array(':id' => (int) $id),
        ));
        return $result['whole_item'] ? (int) $result['whole_item'] : false;
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

        $criteria->compare('item_id', $this->item_id);
        $criteria->compare('title', $this->title);
        $criteria->compare('full_text', $this->full_text);
        $criteria->compare('booking_id', $this->booking_id);
        $criteria->compare('pos_x', $this->pos_x);
        $criteria->compare('pos_y', $this->pos_y);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('status', $this->status);
        $criteria->compare('is_protected', $this->is_protected);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}