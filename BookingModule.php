<?php

class BookingModule extends YWebModule
{

    public $defaultController = 'default';
    public $bookingCache = 'booking.cache';
    public $uploadPath = 'booking';
    public $allowedExtensions = 'jpg,jpeg,png,gif';
    public $minSize = 0;
    public $maxSize;
    public $maxFiles = 1;

    public function getDependencies()
    {
        return array(
            'user',
            'company',
            'booking',
        );
    }

    public function getUploadPath()
    {
        return Yii::getPathOfAlias('webroot') . '/' . Yii::app()->getModule('yupe')->uploadPath . '/' . $this->uploadPath . '/';
    }

    public function getInstall()
    {
        if (parent::getInstall())
            @mkdir($this->getUploadPath(), 0755);

        return false;
    }

    public function checkSelf()
    {
        $messages = array();

        $uploadPath = $this->getUploadPath();

        if (!is_writable($uploadPath))
            $messages[YWebModule::CHECK_ERROR][] = array(
                'type' => YWebModule::CHECK_ERROR,
                'message' => Yii::t('booking', 'Директория "{dir}" не доступна для записи! {link}', array(
                    '{dir}' => $uploadPath,
                    '{link}' => CHtml::link(Yii::t('booking', 'Изменить настройки'), array(
                        '/yupe/backend/modulesettings/',
                        'module' => 'booking',
                    )),
                )),
            );

        return (isset($messages[YWebModule::CHECK_ERROR])) ? $messages : true;
    }

    public function getParamsLabels()
    {
        return array(
            'adminMenuOrder' => Yii::t('booking', 'Порядок следования в меню'),
            'editor' => Yii::t('booking', 'Визуальный редактор'),
            'uploadPath' => Yii::t('booking', 'Каталог для загрузки файлов (относительно {path})', array('{path}' => Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . Yii::app()->getModule("yupe")->uploadPath)),
            'allowedExtensions' => Yii::t('booking', 'Разрешенные расширения (перечислите через запятую)'),
            'minSize' => Yii::t('booking', 'Минимальный размер (в байтах)'),
            'maxSize' => Yii::t('booking', 'Максимальный размер (в байтах)'),
        );
    }

    public function getEditableParams()
    {
        return array(
            'adminMenuOrder',
            'editor' => Yii::app()->getModule('yupe')->getEditors(),
            'uploadPath',
            'allowedExtensions',
            'minSize',
            'maxSize',
        );
    }

    public function getVersion()
    {
        return Yii::t('booking', '0.1');
    }

    public function getIsInstallDefault()
    {
        return true;
    }

    public function getCategory()
    {
        return Yii::t('booking', 'Компании');
    }

    public function getName()
    {
        return Yii::t('booking', 'Бронирование');
    }

    public function getDescription()
    {
        return Yii::t('booking', 'Модуль для управлеления бронированием в ресторанах, кафе,...');
    }

    public function getAuthor()
    {
        return Yii::t('companyaction', 'inform.kg');
    }

    public function getAuthorEmail()
    {
        return Yii::t('companyaction', 'office@inform.kg');
    }

    public function getUrl()
    {
        return Yii::t('companyaction', 'http://inform.kg');
    }

    public function getIcon()
    {
        return "leaf";
    }

    public function getNavigation()
    {
        return array(
            array('label' => Yii::t('booking', 'Зоны бронирования')),
            array('icon' => 'list-alt', 'label' => Yii::t('booking', 'Список зон'), 'url' => array('/booking/default/index')),
            array('icon' => 'plus-sign', 'label' => Yii::t('booking', 'Добавить зону'), 'url' => array('/booking/default/create')),
            array('label' => Yii::t('booking', 'Объекты бронирования')),
            array('icon' => 'list-alt', 'label' => Yii::t('booking', 'Список объектов'), 'url' => array('/booking/bookingitem/index')),
            array('icon' => 'plus-sign', 'label' => Yii::t('booking', 'Добавить объект'), 'url' => array('/booking/bookingitem/create')),
            array('label' => Yii::t('booking', 'Конфигурация зоны бронирования')),
            array('icon' => 'list-alt', 'label' => Yii::t('booking', 'Список конфигураций'), 'url' => array('/booking/bookingvariants/index')),
            array('icon' => 'plus-sign', 'label' => Yii::t('booking', 'Добавить конфигурацию'), 'url' => array('/booking/bookingvariants/create')),
            array('label' => Yii::t('booking', 'Заказы бронирования')),
            array('icon' => 'list-alt', 'label' => Yii::t('booking', 'Список заказов'), 'url' => array('/booking/bookingorders/index')),
        );
    }

    public function getCompanyList()
    {
        $connection = Yii::app()->db;
        $items = $connection->createCommand('SELECT id, title FROM {{company_company}} WHERE status = ' . Company::STATUS_PUBLISHED . ' ORDER BY title')->query()->readAll();
        return $items;
    }

    public function getBookingList($criteria = '')
    {
        return Booking::model()->findAll($criteria);
    }

    public function isMultiLang()
    {
        return true;
    }

    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'booking.models.*',
            'booking.components.*',
        ));
    }

    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        }
        else
            return false;
    }

}
