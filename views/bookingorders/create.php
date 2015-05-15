<?php
$this->breadcrumbs = array(
    Yii::app()->getModule('booking')->getCategory() => array(),
    Yii::t('BookingModule.booking', 'Заказы бронирования') => array('/booking/bookingitemitem/index'),
    Yii::t('BookingModule.booking', 'Добавление'),
);

$this->pageTitle = Yii::t('BookingModule.booking', 'Заказы бронирования - добавление');

$this->menu = array(
    array('label' => Yii::t('BookingModule.booking', 'Заказы бронирования'), 'items' => array(
            array('icon' => 'list-alt', 'label' => Yii::t('BookingModule.booking', 'Управление заказами бронирования'), 'url' => array('/booking/bookingorders/index')),
            array('icon' => 'plus-sign', 'label' => Yii::t('BookingModule.booking', 'Добавить заказ бронирования'), 'url' => array('/booking/bookingorders/create')),
        )),
    array('label' => Yii::t('BookingModule.booking', 'Зоны бронирования'), 'items' => array(
            array('icon' => 'list-alt', 'label' => Yii::t('BookingModule.booking', 'Управление зонами бронирования'), 'url' => array('/booking/default/index')),
            array('icon' => 'plus-sign', 'label' => Yii::t('BookingModule.booking', 'Добавить зону бронирования'), 'url' => array('/booking/default/create')),
        )),
    array('label' => Yii::t('BookingModule.booking', 'Конфигурации зон бронирования'), 'items' => array(
            array('icon' => 'list-alt', 'label' => Yii::t('BookingModule.booking', 'Управление конфигурациями зон бронирования'), 'url' => array('/booking/bookingvariants/index')),
            array('icon' => 'plus-sign', 'label' => Yii::t('BookingModule.booking', 'Добавить конфигурацию зоны бронирования'), 'url' => array('/booking/bookingvariants/create')),
        )),
    array('label' => Yii::t('BookingModule.booking', 'Объекты бронирования'), 'items' => array(
            array('icon' => 'list-alt', 'label' => Yii::t('BookingModule.booking', 'Управление объектами бронирования'), 'url' => array('/booking/bookingitem/index')),
            array('icon' => 'plus-sign', 'label' => Yii::t('BookingModule.booking', 'Добавить объект бронирования'), 'url' => array('/booking/bookingitem/create')),
        )),
);
?>
<div class="page-header">
    <h1>
        <?php echo Yii::t('BookingModule.booking', 'Заказы бронирования'); ?>
        <small><?php echo Yii::t('BookingModule.booking', 'добавление'); ?></small>
    </h1>
</div>

<?php echo $this->renderPartial('_form', array('model' => $model, 'languages' => $languages)); ?>