<?php

/**
 * This is the model class for table "{{booking_variants_dates}}".
 *
 * The followings are the available columns in table '{{booking_variants_dates}}':
 * @property integer $variant_id
 * @property string $event_date
 * @property string $start_event_date
 * @property string $finish_event_date
 * @property string $start_date
 * @property string $finish_date
 * @property string $lang
 *
 * The followings are the available model relations:
 * @property BookingVariants $variant
 */
class BookingVariantsDates extends YModel
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BookingVariantsDates the static model class
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
        return '{{booking_variants_dates}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('variant_id, event_date, start_event_date, finish_event_date', 'required'),
            array('variant_id', 'numerical', 'integerOnly' => true),
            array('lang', 'length', 'max' => 2),
            array('lang', 'default', 'value' => Yii::app()->sourceLanguage),
            array('lang', 'in', 'range' => array_keys(Yii::app()->getModule('yupe')->getLanguagesList())),
            array('event_date', 'length', 'max' => 25),
            array('start_date, finish_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('variant_id, event_date, start_date, finish_date, lang', 'safe', 'on' => 'search'),
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
            'variant' => array(self::BELONGS_TO, 'BookingVariants', 'variant_id'),
        );
    }
    
    public function language($lang)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'lang = :lang',
            'params' => array(':lang' => $lang),
        ));
        return $this;
    }
    
    public function beforeValidate()
    {
        if (!$this->lang)
            $this->lang = Yii::app()->language;
        
        if(!empty($this->event_date) && strpos($this->event_date, ' - ')) {
            $bufer = explode(' - ', $this->event_date);
            $this->start_event_date = $bufer[0];
            $this->finish_event_date = $bufer[1];
        }
        
        return parent::beforeValidate();
    }
    
    public function beforeSave()
    {
        $this->start_event_date = date('Y-m-d', strtotime($this->start_event_date));
        $this->finish_event_date = date('Y-m-d', strtotime($this->finish_event_date));
        $this->start_date = date('Y-m-d', strtotime($this->start_date));
        $this->finish_date = date('Y-m-d', strtotime($this->finish_date));


        return parent::beforeSave();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'variant_id' => Yii::t('booking', 'Конфигурация зоны бронирования'),
            'event_date' => Yii::t('booking', 'Даты заказа'),
            'start_event_date' => Yii::t('booking', 'Даты начала мероприятия'),
            'finish_event_date' => Yii::t('booking', 'Даты завершения мероприятия'),
            'start_date' => Yii::t('booking', 'Дата начала отображения'),
            'finish_date' => Yii::t('booking', 'Id'),
            'lang' => Yii::t('booking', 'Id'),
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

        $criteria->compare('variant_id', $this->variant_id);
        $criteria->compare('event_date', $this->event_date, true);
        $criteria->compare('start_event_date', $this->start_event_date, true);
        $criteria->compare('finish_event_date', $this->finish_event_date, true);
        $criteria->compare('start_date', $this->start_date, true);
        $criteria->compare('finish_date', $this->finish_date, true);
        $criteria->compare('lang', $this->lang, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}