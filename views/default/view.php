<?php
    $this->breadcrumbs = array(
        $this->getModule('booking')->getCategory() => array(''),
        Yii::t('BookingModule.booking', 'Бронирование') => array('/booking/default/index'),
        $model->title,
    );

    $this->pageTitle = Yii::t('BookingModule.booking', 'Бронирование - просмотр');

    $this->menu = array(
        array('icon' => 'list-alt', 'label' => Yii::t('BookingModule.booking', 'Управление объектами бронирования'), 'url' => array('/booking/default/index')),
        array('icon' => 'plus-sign', 'label' => Yii::t('BookingModule.booking', 'Добавить объект бронирования'), 'url' => array('/booking/default/create')),
        array('label' => Yii::t('BookingModule.booking', 'Бронирование объекта') . ' «' . mb_substr($model->title, 0, 32) . '»'),
        array('icon' => 'pencil', 'label' => Yii::t('BookingModule.booking', 'Редактирование объекта бронирования'), 'url' => array(
            '/booking/default/update',
            'id' => $model->id
        )),
        array('icon' => 'eye-open', 'label' => Yii::t('BookingModule.booking', 'Просмотреть объект бронирования'), 'url' => array(
            '/booking/default/view',
            'id' => $model->id
        )),
        array('icon' => 'eye-open', 'label' => Yii::t('BookingModule.booking', 'Перетаскивание на объекта бронирования'), 'url' => array(
            '/booking/default/draggable',
            'id' => $model->id
        )),
        array('icon' => 'trash', 'label' => Yii::t('BookingModule.booking', 'Удалить объект бронирования'), 'url' => '#', 'linkOptions' => array(
            'submit' => array('/booking/default/delete', 'id' => $model->id),
            'confirm' => Yii::t('BookingModule.booking', 'Вы уверены, что хотите удалить объект бронирования?'),
            'csrf' => true,
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
    <li class="active"><a href="#anounce" data-toggle="tab"><?php echo Yii::t('BookingModule.booking', 'Пример краткой версии объекта бронирования'); ?></a></li>
    <li><a href="#full" data-toggle="tab"><?php echo Yii::t('BookingModule.booking', 'Пример полной версии объекта бронирования'); ?></a></li>
</ul>
<div class="tab-content">
    <div id="anounce" class="tab-pane fade active in">
        <div style="margin-bottom: 20px;">
            <h6>
                <span class="label"><?php echo $model->creation_date; ?></span> 
                <?php echo CHtml::link($model->title, array('/booking/show', 'title' => $model->alias)); ?>
            </h6>
            <p>
                <?php echo $model->short_text; ?>
            </p>
            <i class="icon-globe"></i> <?php echo $model->getPermaLink(); ?>
        </div>
    </div>
    <div id="full"  class="tab-pane fade">
        <div style="margin-bottom: 20px;">
            <h3><?php echo CHtml::link($model->title, array('/booking/show', 'title' => $model->alias)); ?></h3>
            <p><?php echo $model->full_text; ?></p>
            <span class="label"><?php echo $model->creation_date; ?></span>
            <i class="icon-user"></i><?php echo CHtml::link($model->user->fullName, array('/user/people/' . $model->user->nick_name)); ?>
            <i class="icon-globe"></i> <?php echo $model->getPermaLink(); ?>
        </div>
    </div>
</div>