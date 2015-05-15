<?php
$this->breadcrumbs = array(
    Yii::app()->getModule('booking')->getCategory() => array(),
    Yii::t('BookingModule.booking', 'Заказы бронирования') => array('/booking/bookingorders/index'),
    Yii::t('BookingModule.booking', 'Управление'),
);

$this->pageTitle = Yii::t('BookingModule.booking', 'Заказы бронирования - управление');

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
        <small><?php echo Yii::t('BookingModule.booking', 'управление'); ?></small>
    </h1>
</div>

<button class="btn btn-small dropdown-toggle" data-toggle="collapse" data-target="#search-toggle">
    <i class="icon-search">&nbsp;</i>
    <?php echo CHtml::link(Yii::t('BookingModule.booking', 'Поиск заказов бронирования'), '#', array('class' => 'search-button')); ?>
    <span class="caret">&nbsp;</span>
</button>

<div id="search-toggle" class="collapse out search-form">
    <?php
    Yii::app()->clientScript->registerScript('search', "
    $('.search-form form').submit(function() {
        $.fn.yiiGridView.update('bookingitem-grid', {
            data: $(this).serialize()
        });
        return false;
    });
");
    $this->renderPartial('_search', array('model' => $model));
    ?>
</div>

<br/>

<p><?php echo Yii::t('BookingModule.booking', 'В данном разделе представлены средства управления заказами бронирования'); ?></p>

<?php
$this->widget('application.modules.yupe.components.YCustomGridView', array(
    'id' => 'bookingorder-grid',
    'type' => 'condensed',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'id',
            'htmlOptions' => array('style' => 'width:20px'),
            'type' => 'raw',
            'value' => 'CHtml::link($data->id, array("/booking/bookingorders/update", "id" => $data->id))',
        ),
        array(
            'name' => 'booking',
            'type' => 'raw',
            'value' => '$data->booking->title',
        ),
        array(
            'name' => 'variant',
            'type' => 'raw',
            'value' => '$data->variant->title',
        ),
        array(
            'name' => 'item',
            'type' => 'raw',
            'value' => '$data->item->title',
        ),
        array(
            'name' => 'item_position',
            'type' => 'raw',
            'value' => '$data->item_position->title',
        ),
        array(
            'name' => 'booking_date',
            'type' => 'raw',
            'value' => '$data->booking_date',
        ),
        array(
            'name' => 'status',
            'type' => 'raw',
            'value' => '$this->grid->returnBootstrapStatusHtml($data, "status", "Status", array("pencil", "ok-sign"))',
            'filter' => $model->getStatusList(),
            'htmlOptions' => array('style' => 'width: 140px;')
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'htmlOptions' => array('style' => 'white-space:nowrap')
        ),
    ),
));
?>