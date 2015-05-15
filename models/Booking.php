<?php

/**
 * This is the model class for table "{{booking}}".
 *
 * The followings are the available columns in table '{{booking}}':
 * @property integer $id
 * @property integer $company_id
 * @property string $lang
 * @property string $title
 * @property string $alias
 * @property string $short_text
 * @property string $full_text
 * @property string $image
 * @property string $link
 * @property string $start_date
 * @property string $last_buy_date
 * @property string $finish_date
 * @property string $creation_date
 * @property string $change_date
 * @property integer $user_id
 * @property integer $status
 * @property integer $is_protected
 * @property integer $keywords
 * @property integer $description
 * @property double $width
 * @property double $lenght
 *
 * The followings are the available model relations:
 * @property CompanyCompany $company
 * @property UserUser $user
 */
class Booking extends YModel
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_MODERATION = 2;
    const PROTECTED_NO = 0;
    const PROTECTED_YES = 1;
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Booking the static model class
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
        return '{{booking}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, alias, short_text, full_text, keywords, description', 'filter', 'filter' => 'trim'),
            array('title, alias, keywords, description', 'filter', 'filter' => 'strip_tags'),
            array('company_id, lang, title, alias', 'required'),
            array('company_id, user_id, status, is_protected', 'numerical', 'integerOnly' => true),
            array('width, lenght', 'numerical'),
            array('lang', 'length', 'max' => 2),
            array('lang', 'default', 'value' => Yii::app()->sourceLanguage),
            array('lang', 'in', 'range' => array_keys(Yii::app()->getModule('yupe')->getLanguagesList())),
            array('status', 'in', 'range' => array_keys($this->statusList)),
            array('alias', 'YUniqueSlugValidator'),
            array('title, alias, image, link', 'length', 'max' => 255),
            array('link', 'url'),
            array('short_text', 'safe'),
            array('alias', 'YSLugValidator', 'message' => Yii::t('BookingModule.booking', 'Запрещенные символы в поле {attribute}')),
            array('company_id', 'default', 'setOnEmpty' => true, 'value' => null),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, lang, title, alias, short_text, full_text, image, link, start_date, last_buy_date, finish_date, creation_date, change_date, user_id, status, is_protected, keywords, description, width, lenght', 'safe', 'on' => 'search'),
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
                    'width' => 1900,
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
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
            'booking_items_position' => array(self::HAS_MANY, 'BookingItemsPosition', 'booking_id'),
            'variant' => array(self::HAS_MANY, 'BookingVariants', 'booking_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
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
            'id' => Yii::t('BookingModule.booking', 'Id'),
            'company_id' => Yii::t('BookingModule.booking', 'Компания'),
            'creation_date' => Yii::t('BookingModule.booking', 'Дата создания'),
            'change_date' => Yii::t('BookingModule.booking', 'Дата изменения'),
            'start_date' => Yii::t('BookingModule.booking', 'Дата начала бронирования'),
            'last_buy_date' => Yii::t('BookingModule.booking', 'Крайняя дата внесения оплаты'),
            'finish_date' => Yii::t('BookingModule.booking', 'Дата завершения бронирования'),
            'title' => Yii::t('BookingModule.booking', 'Заголовок'),
            'alias' => Yii::t('BookingModule.booking', 'Алиас'),
            'image' => Yii::t('BookingModule.booking', 'Изображение'),
            'link' => Yii::t('BookingModule.booking', 'Ссылка'),
            'lang' => Yii::t('BookingModule.booking', 'Язык'),
            'short_text' => Yii::t('BookingModule.booking', 'Короткий текст'),
            'full_text' => Yii::t('BookingModule.booking', 'Полный текст'),
            'user_id' => Yii::t('BookingModule.booking', 'Автор'),
            'status' => Yii::t('BookingModule.booking', 'Статус'),
            'is_protected' => Yii::t('BookingModule.booking', 'Доступ: * только для авторизованных пользователей'),
            'keywords' => Yii::t('BookingModule.booking', 'Ключевые слова (SEO)'),
            'description' => Yii::t('BookingModule.booking', 'Описание (SEO)'),
            'lenght' => Yii::t('BookingModule.booking', 'Помещение в длину (м)'),
            'width' => Yii::t('BookingModule.booking', 'Помещение в ширину (м)'),
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
        $this->creation_date = date('Y-m-d', strtotime($this->creation_date));

        if ($this->isNewRecord) {
            $this->creation_date = $this->change_date;
            $this->user_id = Yii::app()->user->getId();
        }

        return parent::beforeSave();
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->creation_date = date('d.m.Y', strtotime($this->creation_date));
        $this->change_date = date('d.m.Y', strtotime($this->change_date));
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
        $criteria->compare('company_id', $this->company_id);
        $criteria->compare('lang', $this->lang, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('alias', $this->alias, true);
        $criteria->compare('short_text', $this->short_text, true);
        $criteria->compare('full_text', $this->full_text, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('link', $this->link, true);
        $criteria->compare('start_date', $this->start_date, true);
        $criteria->compare('last_buy_date', $this->last_buy_date, true);
        $criteria->compare('finish_date', $this->finish_date, true);
        $criteria->compare('creation_date', $this->creation_date, true);
        $criteria->compare('change_date', $this->change_date, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('status', $this->status);
        $criteria->compare('is_protected', $this->is_protected);
        $criteria->compare('keywords', $this->keywords);
        $criteria->compare('description', $this->description);
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

    public function getCompanyName()
    {
        return ($this->company === null) ? '' : $this->company->title;
    }
    
    public function getBookingId($alias)
    {
        if (!empty($alias)) {
            $model = Booking::model()->findByAttributes(array('alias' => $alias));
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
    
    /**
     * return Booking Image
     */
    public function bookingImage($booking_id)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'image';
        $criteria->condition = 'status = :status AND id = :id';
        $criteria->params = array(':status' => Booking::STATUS_PUBLISHED, ':id' => (int)$booking_id);
        $model = Booking::model()->find($criteria);
        if ($model !== null)
            return $model->image;
        else
            return false;
    }
    
}