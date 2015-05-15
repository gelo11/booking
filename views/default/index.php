<?php
$this->breadcrumbs = array(
    Yii::app()->getModule('booking')->getCategory() => array(),
    Yii::t('BookingModule.booking', 'Зоны бронирования') => array('/booking/default/index'),
    Yii::t('BookingModule.booking', 'Управление'),
);

$this->pageTitle = Yii::t('BookingModule.booking', 'Зоны бронирования - управление');

$this->menu = array(
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
    array('label' => Yii::t('BookingModule.booking', 'Заказы бронирования'), 'items' => array(
            array('icon' => 'list-alt', 'label' => Yii::t('BookingModule.booking', 'Управление заказами бронирования'), 'url' => array('/booking/bookingorders/index')),
            array('icon' => 'plus-sign', 'label' => Yii::t('BookingModule.booking', 'Добавить заказ бронирования'), 'url' => array('/booking/bookingorders/create')),
        )),
);
?>
<div class="page-header">
    <h1>
        <?php echo Yii::t('BookingModule.booking', 'Зоны бронирования'); ?>
        <small><?php echo Yii::t('BookingModule.booking', 'управление'); ?></small>
    </h1>
</div>

<button class="btn btn-small dropdown-toggle" data-toggle="collapse" data-target="#search-toggle">
    <i class="icon-search">&nbsp;</i>
    <?php echo CHtml::link(Yii::t('BookingModule.booking', 'Поиск зон бронирования'), '#', array('class' => 'search-button')); ?>
    <span class="caret">&nbsp;</span>
</button>

<div id="search-toggle" class="collapse out search-form">
    <?php
    Yii::app()->clientScript->registerScript('search', "
    $('.search-form form').submit(function() {
        $.fn.yiiGridView.update('booking-grid', {
            data: $(this).serialize()
        });
        return false;
    });
");
    $this->renderPartial('_search', array('model' => $model));
    ?>
</div>

<br/>

<p><?php echo Yii::t('BookingModule.booking', 'В данном разделе представлены средства управления зонами бронирования'); ?></p>

<?php
$this->widget('application.modules.yupe.components.YCustomGridView', array(
    'id' => 'booking-grid',
    'type' => 'condensed',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'id',
            'type' => 'raw',
            'value' => 'CHtml::link($data->id, array("/booking/default/update", "id" => $data->id))',
            'htmlOptions' => array('style' => 'width:20px'),
        ),
        array(
            'name' => 'title',
            'type' => 'raw',
            'value' => 'CHtml::link($data->title, array("/booking/default/update", "id" => $data->id))',
        ),
        array(
                'header' => Yii::t('BookingModule.booking', 'Конфигураций'),
                'type'   => 'raw',
                'value'  => 'CHtml::link(count($data->variant), Yii::app()->createUrl("/booking/bookingvariants/index", array("BookingVariants[booking_id]" => $data->id)))',
            ),
        array(
            'name' => 'company_id',
            'value' => '$data->companyName',
        ),
        array(
            'name' => 'status',
            'type' => 'raw',
            'value' => '$this->grid->returnBootstrapStatusHtml($data, "status", "Status", array("pencil", "ok-sign", "time"))',
            'filter' => $model->getStatusList(),
            'htmlOptions' => array('style' => 'width:110px'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn'
        ),
    ),
));
?>