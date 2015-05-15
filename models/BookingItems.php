<?php

/**
 * This is the model class for table "{{booking_items}}".
 *
 * The followings are the available columns in table '{{booking_items}}':
 * @property integer $id
 * @property string $title
 * @property string $alias
 * @property string $image
 * @property string $full_text
 * @property integer $left
 * @property integer $left_qty
 * @property integer $right
 * @property integer $right_qty
 * @property integer $top
 * @property integer $top_qty
 * @property integer $bottom
 * @property integer $bottom_qty
 * @property integer $user_id
 * @property integer $status
 * @property integer $is_protected
 * @property double $width
 * @property double $lenght
 */
class BookingItems extends YModel
{

    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_MODERATION = 2;
    const PROTECTED_NO = 0;
    const PROTECTED_YES = 1;
    const NO = 0;
    const YES = 1;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BookingItems the static model class
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
        return '{{booking_items}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, alias, full_text, color, border_color, border_radius', 'filter', 'filter' => 'trim'),
            array('title, alias, color, border_color, border_radius', 'filter', 'filter' => 'strip_tags'),
            array('title, alias, lang, price', 'required'),
            array('seats, whole_item, left, left_qty, right, right_qty, top, top_qty, bottom, bottom_qty, user_id, status, is_protected, transparent, hide_number', 'numerical', 'integerOnly' => true),
            array('price, price_per_seats, width, lenght', 'numerical'),
            array('lang', 'length', 'max' => 2),
            array('lang', 'default', 'value' => Yii::app()->sourceLanguage),
            array('lang', 'in', 'range' => array_keys(Yii::app()->getModule('yupe')->getLanguagesList())),
            array('status', 'in', 'range' => array_keys($this->statusList)),
            array('alias', 'YUniqueSlugValidator'),
            array('alias', 'YSLugValidator', 'message' => Yii::t('BookingModule.booking', 'Запрещенные символы в поле {attribute}')),
            array('title, alias, image', 'length', 'max' => 255),
            array('border_radius', 'length', 'max' => 40),
            array('color, border_color', 'length', 'max' => 16),
            array('full_text', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, title, alias, image, full_text, left, left_qty, right, right_qty, top, top_qty, bottom, bottom_qty, user_id, status, is_protected, width, lenght', 'safe', 'on' => 'search'),
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
                    'quality' => 75,
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
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'booking_items' => array(self::HAS_MANY, 'BookingItemsPosition', 'item_id'),
        );
    }
    
    public function generateFileName()
    {
        return md5($this->title . time());
    }
    
    public function defaultScope()
    {
        parent::defaultScope();
        if (!Yii::app()->user->isSuperUser() && Yii::app()->user->IsOwnContent() && isset(Yii::app()->controller->is_backend)) {
            return array(
                'condition' => $this->getTableAlias(false, false) . ".user_id=" . Yii::app()->user->id,
            );
        } else {
            return array();
        }
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
            'order' => 'date DESC',
            'limit' => $num,
        ));
        return $this;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('BookingModule.booking', 'Id'),
            'title' => Yii::t('BookingModule.booking', 'Заголовок'),
            'alias' => Yii::t('BookingModule.booking', 'Алиас'),
            'seats' => Yii::t('BookingModule.booking', 'Количество мест'),
            'price' => Yii::t('BookingModule.booking', 'Общая цена'),
            'price_per_seats' => Yii::t('BookingModule.booking', 'Цена за место'),
            'whole_item' => Yii::t('BookingModule.booking', 'Бронирование всего объекта'),
            'image' => Yii::t('BookingModule.booking', 'Изображение'),
            'lang' => Yii::t('BookingModule.booking', 'Язык'),
            'full_text' => Yii::t('BookingModule.booking', 'Описание'),
            'user_id' => Yii::t('BookingModule.booking', 'Автор'),
            'status' => Yii::t('BookingModule.booking', 'Статус'),
            'is_protected' => Yii::t('BookingModule.booking', 'Доступ: * только для авторизованных пользователей'),
            'left' => Yii::t('BookingModule.booking', 'Стулья слева'),
            'left_qty' => Yii::t('BookingModule.booking', 'Количество стульев слева'),
            'right' => Yii::t('BookingModule.booking', 'Стулья справа'),
            'right_qty' => Yii::t('BookingModule.booking', 'Количество стульев '),
            'top' => Yii::t('BookingModule.booking', 'Стулья спереди(сверху)'),
            'top_qty' => Yii::t('BookingModule.booking', 'Количество стульев спереди(сверху)'),
            'bottom' => Yii::t('BookingModule.booking', 'Стулья сзади(снизу)'),
            'bottom_qty' => Yii::t('BookingModule.booking', 'Количество стульев сзади(снизу)'),
            'lenght' => Yii::t('BookingModule.booking', 'Объект в длину (px)'),
            'width' => Yii::t('BookingModule.booking', 'Объект в ширину (px)'),
            'date' => Yii::t('BookingModule.booking', 'Дата создания'),
            'color' => Yii::t('BookingModule.booking', 'Цвет заливки'),
            'border_color' => Yii::t('BookingModule.booking', 'Цвет границы'),
            'transparent' => Yii::t('BookingModule.booking', 'Прозрачность'),
            'border_radius' => Yii::t('BookingModule.booking', 'Радиус скругления углов'),
            'hide_number' =>  Yii::t('BookingModule.booking', 'Скрыть номер объекта'),
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
            'hide_number' =>  Yii::t('BookingModule.booking', 'Скрывает порядковый номер объекта на изображении конфигурации.'),
        );
    }
    
    public function beforeValidate()
    {
        if (!$this->alias)
            $this->alias = YText::translit($this->title);

        if (!$this->lang)
            $this->lang = Yii::app()->language;

        return parent::beforeValidate();
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->date = new CDbExpression('NOW()');
            $this->user_id = Yii::app()->user->getId();
        }

        return parent::beforeSave();
    }

    public function afterFind()
    {
        parent::afterFind();

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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('alias', $this->alias, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('full_text', $this->full_text, true);
        $criteria->compare('left', $this->left);
        $criteria->compare('left_qty', $this->left_qty);
        $criteria->compare('right', $this->right);
        $criteria->compare('right_qty', $this->right_qty);
        $criteria->compare('top', $this->top);
        $criteria->compare('top_qty', $this->top_qty);
        $criteria->compare('bottom', $this->bottom);
        $criteria->compare('bottom_qty', $this->bottom_qty);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('status', $this->status);
        $criteria->compare('is_protected', $this->is_protected);
        $criteria->compare('width', $this->width);
        $criteria->compare('lenght', $this->lenght);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getPermaLink()
    {
        return Yii::app()->createAbsoluteUrl('/booking/show/', array('title' => $this->alias));
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
    
    public function getYesNoList()
    {
        return array(
            self::NO => Yii::t('BookingModule.booking', 'нет'),
            self::YES => Yii::t('BookingModule.booking', 'да'),
        );
    }

    public function getYesNo($check)
    {
        return isset($check) ? $check : Yii::t('BookingModule.booking', '*неизвестно*');
    }

    public function getImageUrl()
    {
        if ($this->image)
            return Yii::app()->baseUrl . '/' . Yii::app()->getModule('yupe')->uploadPath . '/' .
                    Yii::app()->getModule('booking')->uploadPath . '/' . $this->image;
        return false;
    }
    
}