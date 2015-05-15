<?php
$this->breadcrumbs = array(
    Yii::app()->getModule('booking')->getCategory() => array(),
    Yii::t('BookingModule.booking', 'Объекты бронирования') => array('/booking/bookingitem/index'),
    $model->title => array('/booking/bookingitem/view', 'id' => $model->id),
    Yii::t('BookingModule.booking', 'Редактирование'),
);

$this->pageTitle = Yii::t('BookingModule.booking', 'Объекты бронирования - редактирование');

$this->menu = array(
    array('icon' => 'list-alt', 'label' => Yii::t('BookingModule.booking', 'Управление предметами бронирования'), 'url' => array('/booking/bookingitem/index')),
    array('icon' => 'plus-sign', 'label' => Yii::t('BookingModule.booking', 'Добавить предмет бронирования'), 'url' => array('/booking/bookingitem/create')),
    array('label' => Yii::t('BookingModule.booking', 'Бронирование') . ' «' . mb_substr($model->title, 0, 32) . '»'),
    array('icon' => 'pencil', 'label' => Yii::t('BookingModule.booking', 'Редактирование предмета бронирования'), 'url' => array(
            '/booking/bookingitem/update/',
            'id' => $model->id
        )),
    array('icon' => 'eye-open', 'label' => Yii::t('BookingModule.booking', 'Просмотреть предмет бронирования'), 'url' => array(
            '/booking/bookingitem/view',
            'id' => $model->id
        )),
    array('icon' => 'trash', 'label' => Yii::t('BookingModule.booking', 'Удалить предмет бронирования'), 'url' => '#', 'linkOptions' => array(
            'submit' => array('/booking/bookingitem/delete', 'id' => $model->id),
            'confirm' => Yii::t('BookingModule.booking', 'Вы уверены, что хотите удалить предмет бронирования?'),
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
        <?php echo Yii::t('BookingModule.booking', 'Редактирование предмета бронирования'); ?><br />
        <small>&laquo;<?php echo $model->title; ?>&raquo;</small>
    </h1>
</div>

<?php echo $this->renderPartial('_form', array('model' => $model, 'languages' => $languages, 'langModels' => $langModels)); ?>