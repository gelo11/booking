<?php
    $this->breadcrumbs = array(
        Yii::app()->getModule('booking')->getCategory() => array(),
        Yii::t('BookingModule.booking', 'Предметы бронирования') => array('/booking/bookingitem/index'),
        Yii::t('BookingModule.booking', 'Управление'),
    );

    $this->pageTitle = Yii::t('BookingModule.booking', 'Предметы бронирования - управление');

    $this->menu = array(
        array('icon' => 'list-alt', 'label' => Yii::t('BookingModule.booking', 'Управление предметами бронирования'), 'url' => array('/booking/bookingitem/index')),
        array('icon' => 'plus-sign', 'label' => Yii::t('BookingModule.booking', 'Добавить предмет бронирования'), 'url' => array('/booking/bookingitem/create')),
    );
?>
<div class="page-header">
    <h1>
        <?php echo Yii::t('BookingModule.booking', 'Предметы бронирования'); ?>
        <small><?php echo Yii::t('BookingModule.booking', 'управление'); ?></small>
    </h1>
</div>

<button class="btn btn-small dropdown-toggle" data-toggle="collapse" data-target="#search-toggle">
    <i class="icon-search">&nbsp;</i>
    <?php echo CHtml::link(Yii::t('BookingModule.booking', 'Поиск предметов бронирования'), '#', array('class' => 'search-button')); ?>
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

<p><?php echo Yii::t('BookingModule.booking', 'В данном разделе представлены средства управления предметами бронирования'); ?></p>

<?php $this->widget('application.modules.yupe.components.YCustomGridView', array(
    'id'           => 'bookingitem-grid',
    'type'         => 'condensed',
    'dataProvider' => $model->search(),
    'filter'       => $model,
    'columns'      => array(
        array(
            'name'        => 'id',
            'htmlOptions' => array('style' => 'width:20px'),
            'type'  => 'raw',
            'value' => 'CHtml::link($data->id, array("/booking/bookingitem/update", "id" => $data->id))',
        ),
        array(
            'name'  => 'title',
            'type'  => 'raw',
            'value' => 'CHtml::link($data->title, array("/booking/bookingitem/update", "id" => $data->id))',
        ),
        array(
            'name'  => 'alias',
            'type'  => 'raw',
            'value' => 'CHtml::link($data->alias, array("/booking/bookingitem/update", "id" => $data->id))',
        ),
        array(
            'name'  => 'lang',
            'value'  => '$data->lang',
            'filter' => Yii::app()->getModule('yupe')->getLanguagesList()
        ),
        array(
            'name'   => 'status',
            'type'   => 'raw',
            'value'  => '$this->grid->returnBootstrapStatusHtml($data, "status", "Status", array("pencil", "ok-sign", "time"))',
            'filter' => $model->getStatusList()
        ),
        array(
            'class'   => 'bootstrap.widgets.TbButtonColumn'
        ),
    ),
)); ?>