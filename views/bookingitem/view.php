<?php
$this->breadcrumbs = array(
    $this->getModule('booking')->getCategory() => array(''),
    Yii::t('BookingModule.booking', 'Объекты бронирования') => array('/booking/bookingitem/index'),
    $model->title,
);

$this->pageTitle = Yii::t('BookingModule.booking', 'Объекты бронирования - просмотр');

$this->menu = array(
    array('icon' => 'list-alt', 'label' => Yii::t('BookingModule.booking', 'Управление объектами бронирования'), 'url' => array('/booking/bookingitem/index')),
    array('icon' => 'plus-sign', 'label' => Yii::t('BookingModule.booking', 'Добавить объект бронирования'), 'url' => array('/booking/bookingitem/create')),
    array('label' => Yii::t('BookingModule.booking', 'Бронирование объекта') . ' «' . mb_substr($model->title, 0, 32) . '»'),
    array('icon' => 'pencil', 'label' => Yii::t('BookingModule.booking', 'Редактирование объекта бронирования'), 'url' => array(
            '/booking/bookingitem/update',
            'id' => $model->id
        )),
    array('icon' => 'eye-open', 'label' => Yii::t('BookingModule.booking', 'Просмотреть объект бронирования'), 'url' => array(
            '/booking/bookingitem/view',
            'id' => $model->id
        )),
    array('icon' => 'trash', 'label' => Yii::t('BookingModule.booking', 'Удалить объект бронирования'), 'url' => '#', 'linkOptions' => array(
            'submit' => array('/booking/bookingitem/delete', 'id' => $model->id),
            'confirm' => Yii::t('BookingModule.booking', 'Вы уверены, что хотите удалить объект бронирования?'),
            'csrf' => true,
        )),
    array('label' => Yii::t('BookingModule.booking', 'Зоны бронирования'), 'items' => array(
            array('icon' => 'list-alt', 'label' => Yii::t('BookingModule.booking', 'Управление зонами бронирования'), 'url' => array('/booking/default/index')),
            array('icon' => 'plus-sign', 'label' => Yii::t('BookingModule.booking', 'Добавить зону бронирования'), 'url' => array('/booking/default/create')),
        )),
    array('label' => Yii::t('BookingModule.booking', 'Конфигурации зон бронирования'), 'items' => array(
            array('icon' => 'list-alt', 'label' => Yii::t('BookingModule.booking', 'Управление конфигурациями зон бронирования'), 'url' => array('/booking/bookingvariants/index')),
            array('icon' => 'plus-sign', 'label' => Yii::t('BookingModule.booking', 'Добавить конфигурацию зоны бронирования'), 'url' => array('/booking/bookingvariants/create')),
        )),
    array('label' => Yii::t('BookingModule.booking', 'Заказы бронирования'), 'items' => array(
            array('icon' => 'list-alt', 'label' => Yii::t('BookingModule.booking', 'Управление заказами бронирования'), 'url' => array('/booking/bookingorders/index')),
            array('icon' => 'plus-sign', 'label' => Yii::t('BookingModule.booking', 'Добавить заказ бронирования'), 'url' => array('/booking/bookingorders/create')),
        )),
);
?>

<div class="page-header">
    <h1>
        <?php echo Yii::t('BookingModule.booking', 'Просмотр объекта бронирования'); ?><br />
        <small>&laquo;<?php echo $model->title; ?>&raquo;</small>
    </h1>
</div>

<ul class="nav nav-tabs">
    <li class="active"><a href="#full" data-toggle="tab"><?php echo Yii::t('BookingModule.booking', 'Пример полной версии объекта бронирования'); ?></a></li>
</ul>
<div class="tab-content">
    <div id="full"  class="tab-pane fade active in">
        <div style="margin-bottom: 20px;">
            <h3><?php echo CHtml::link($model->title, array('/bookingitem/show', 'title' => $model->alias)); ?></h3>
            <p><?php echo $model->full_text; ?></p>
            <span class="label"><?php echo $model->date; ?></span>
            <i class="icon-user"></i><?php echo CHtml::link($model->user->fullName, array('/user/people/' . $model->user->nick_name)); ?>
        </div>
    </div>
</div>