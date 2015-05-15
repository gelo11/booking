<?php

/**
 * This is the model class for table "{{booking_variants}}".
 *
 * The followings are the available columns in table '{{booking_variants}}':
 * @property integer $id
 * @property integer $booking_id
 * @property string $title
 * @property string $alias
 * @property string $image
 * @property string $short_text
 * @property string $full_text
 * @property string $date
 * @property string $start_date
 * @property string $last_buy_date
 * @property string $finish_date
 * @property string $creation_date
 * @property string $change_date
 * @property integer $user_id
 * @property integer $status
 * @property integer $is_protected
 *
 * The followings are the available model relations:
 * @property Booking $booking
 * @property UserUser $user
 */
class BookingVariants extends YModel
{

    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_MODERATION = 2;
    const PROTECTED_NO = 0;
    const PROTECTED_YES = 1;
    const DEFAULT_NO = 0;
    const DEFAULT_YES = 1;
    const DEADLINE_1 = 1;
    const DEADLINE_2 = 2;
    const DEADLINE_3 = 3;
    const DEADLINE_4 = 4;
    const DEADLINE_5 = 5;
    const DEADLINE_6 = 6;
    const DEADLINE_7 = 7;
    
    public $today;
    public $colorpicker;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BookingVariants the static model class
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
        return '{{booking_variants}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, alias, short_text, full_text', 'filter', 'filter' => 'trim'),
            array('title, alias', 'filter', 'filter' => 'strip_tags'),
            array('booking_id, title, alias, date, start_date, last_buy_date, finish_date, creation_date, change_date', 'required'),
            array('booking_id, user_id, status, is_protected, no_finish_date, deadline, is_default', 'numerical', 'integerOnly' => true),
            array('title, alias, image', 'length', 'max' => 255),
            array('lang', 'length', 'max' => 2),
            array('lang', 'default', 'value' => Yii::app()->sourceLanguage),
            array('lang', 'in', 'range' => array_keys(Yii::app()->getModule('yupe')->getLanguagesList())),
            array('status', 'in', 'range' => array_keys($this->statusList)),
            array('alias', 'YUniqueSlugValidator'),
            array('short_text', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, booking_id, lang, title, alias, image, short_text, full_text, date, start_date, last_buy_date, finish_date, creation_date, change_date, user_id, status, is_protected, no_finish_date, deadline, is_default', 'safe', 'on' => 'search'),
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
            'withRelated' => array(
                'class' => 'ext.withRelated.WithRelatedBehavior',
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
            'booking' => array(self::BELONGS_TO, 'Booking', 'booking_id'),
            'items' => array(self::MANY_MANY, 'BookingItemsPosition',
                '{{booking_items_position}}(variant_id, item_id)'),
            'dates' => array(self::HAS_MANY, 'BookingVariantsDates', 'variant_id', 'together' => true),
            'user' => array(self::BELONGS_TO, 'UserUser', 'user_id'),
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
            'id' => Yii::t('booking', 'Id'),
            'booking_id' => Yii::t('booking', 'Зона бронирования'),
            'lang' => Yii::t('booking', 'Язык'),
            'title' => Yii::t('booking', 'Заголовок'),
            'alias' => Yii::t('booking', 'Алиас'),
            'image' => Yii::t('booking', 'Изображение'),
            'short_text' => Yii::t('booking', 'Короткий текст'),
            'full_text' => Yii::t('booking', 'Полный текст'),
            'date' => Yii::t('booking', 'Дата'),
            'start_date' => Yii::t('booking', 'Дата начала отображения'),
            'last_buy_date' => Yii::t('booking', 'Крайняя дата внесения оплаты'),
            'finish_date' => Yii::t('booking', 'Дата завершения бронирования'),
            'creation_date' => Yii::t('booking', 'Дата создания'),
            'change_date' => Yii::t('booking', 'Дата изменения'),
            'user_id' => Yii::t('booking', 'Автор'),
            'status' => Yii::t('booking', 'Статус'),
            'is_protected' => Yii::t('booking', 'Доступ: * только для авторизованных пользователей'),
            'no_finish_date' => Yii::t('booking', 'Без даты завершения'),
            'deadline' => Yii::t('booking', 'Срок окончания бронирования'),
            'is_default' => Yii::t('booking', 'Основная расстановка (по-умолчанию)'),
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
        $this->change_date = new CDbExpression('NOW()');
        $this->date = date('Y-m-d', strtotime($this->date));
        $this->creation_date = date('Y-m-d', strtotime($this->creation_date));
        $this->start_date = date('Y-m-d', strtotime($this->start_date));
        $this->last_buy_date = date('Y-m-d', strtotime($this->last_buy_date));
        $this->finish_date = date('Y-m-d', strtotime($this->finish_date));

        if ($this->isNewRecord) {
            $this->creation_date = $this->change_date;
            $this->user_id = Yii::app()->user->getId();
        }

        return parent::beforeSave();
    }

    public function afterFind()
    {
        parent::afterFind();
        
        $this->today = date("d.m.Y");

        $this->date = date('d.m.Y', strtotime($this->date));
        $this->creation_date = date('d.m.Y', strtotime($this->creation_date));
        $this->change_date = date('d.m.Y', strtotime($this->change_date));
        $this->start_date = date('d.m.Y', strtotime($this->start_date));
        $this->last_buy_date = date('d.m.Y', strtotime($this->last_buy_date));
        $this->finish_date = date('d.m.Y', strtotime($this->finish_date));
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
        $criteria->compare('booking_id', $this->booking_id);
        $criteria->compare('lang', $this->lang, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('alias', $this->alias, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('short_text', $this->short_text, true);
        $criteria->compare('full_text', $this->full_text, true);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('start_date', $this->start_date, true);
        $criteria->compare('last_buy_date', $this->last_buy_date, true);
        $criteria->compare('finish_date', $this->finish_date, true);
        $criteria->compare('creation_date', $this->creation_date, true);
        $criteria->compare('change_date', $this->change_date, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('status', $this->status);
        $criteria->compare('is_protected', $this->is_protected);

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
            self::STATUS_DRAFT => Yii::t('booking', 'Черновик'),
            self::STATUS_PUBLISHED => Yii::t('booking', 'Опубликовано'),
            self::STATUS_MODERATION => Yii::t('booking', 'На модерации'),
        );
    }

    public function getStatus()
    {
        $data = $this->statusList;
        return isset($data[$this->status]) ? $data[$this->status] : Yii::t('booking', '*неизвестно*');
    }

    public function getProtectedStatusList()
    {
        return array(
            self::PROTECTED_NO => Yii::t('booking', 'нет'),
            self::PROTECTED_YES => Yii::t('booking', 'да'),
        );
    }

    public function getProtectedStatus()
    {
        $data = $this->protectedStatusList;
        return isset($data[$this->is_protected]) ? $data[$this->is_protected] : Yii::t('booking', '*неизвестно*');
    }
    
    public function getDeadlineList()
    {
        return array(
            self::DEADLINE_1 => Yii::t('booking', '1 день'),
            self::DEADLINE_2 => Yii::t('booking', '2 день'),
            self::DEADLINE_3 => Yii::t('booking', '3 день'),
            self::DEADLINE_4 => Yii::t('booking', '4 день'),
            self::DEADLINE_5 => Yii::t('booking', '5 день'),
            self::DEADLINE_6 => Yii::t('booking', '6 день'),
            self::DEADLINE_7 => Yii::t('booking', 'неделя'),
        );
    }

    public function getDeadline()
    {
        $data = $this->deadlineList;
        return isset($data[$this->deadline]) ? $data[$this->deadline] : Yii::t('booking', '*до даты заказа*');
    }

    public function getDefaultStatusList()
    {
        return array(
            self::DEFAULT_NO => Yii::t('booking', 'нет'),
            self::DEFAULT_YES => Yii::t('booking', 'да'),
        );
    }

    public function getDefaultStatus()
    {
        $data = $this->defaultStatusList;
        return isset($data[$this->is_default]) ? $data[$this->is_default] : Yii::t('booking', '*неизвестно*');
    }

    public function getBookingTitle()
    {
        return ($this->booking === null) ? '' : $this->booking->title;
    }

    public function getVariantId($alias)
    {
        if (!empty($alias)) {
            $model = BookingVariants::model()->findByAttributes(array('alias' => $alias));
            if ($model)
                return $model->id;
        }
        return false;
    }

    public function getImageUrl()
    {
        if ($this->image)
            return Yii::app()->baseUrl . '/' . Yii::app()->getModule('yupe')->uploadPath . '/' .
                    Yii::app()->getModule('booking')->uploadPath . '/' . $this->image;
        return false;
    }

}