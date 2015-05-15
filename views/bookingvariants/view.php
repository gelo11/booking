<?php
    $this->breadcrumbs = array(
        $this->getModule('booking')->getCategory() => array(''),
        Yii::t('BookingModule.booking', 'Варианты бронирования') => array('/booking/bookingvariants/index'),
        $model->title,
    );

    $this->pageTitle = Yii::t('BookingModule.booking', 'Варианты бронирования - просмотр');

    $this->menu = array(
        array('icon' => 'list-alt', 'label' => Yii::t('BookingModule.booking', 'Управление вариантами бронирования'), 'url' => array('/booking/bookingvariants/index')),
        array('icon' => 'plus-sign', 'label' => Yii::t('BookingModule.booking', 'Добавить вариант бронирования'), 'url' => array('/booking/bookingvariants/create')),
        array('label' => Yii::t('BookingModule.booking', 'Варианты бронирования варианта') . ' «' . mb_substr($model->title, 0, 32) . '»'),
        array('icon' => 'pencil', 'label' => Yii::t('BookingModule.booking', 'Редактирование варианта бронирования'), 'url' => array(
            '/booking/bookingvariants/update',
            'id' => $model->id
        )),
        array('icon' => 'eye-open', 'label' => Yii::t('BookingModule.booking', 'Просмотреть вариант бронирования'), 'url' => array(
            '/booking/bookingvariants/view',
            'id' => $model->id
        )),
        array('icon' => 'trash', 'label' => Yii::t('BookingModule.booking', 'Удалить вариант бронирования'), 'url' => '#', 'linkOptions' => array(
            'submit' => array('/booking/bookingvariants/delete', 'id' => $model->id),
            'confirm' => Yii::t('BookingModule.booking', 'Вы уверены, что хотите удалить вариант бронирования?'),
            'csrf' => true,
        )),
    );
?>

<div class="page-header">
     <h1>
         <?php echo Yii::t('BookingModule.booking', 'Просмотр варианта бронирования'); ?><br />
        <small>&laquo;<?php echo $model->title; ?>&raquo;</small>
     </h1>
</div>

<ul class="nav nav-tabs">
    <li class="active"><a href="#anounce" data-toggle="tab"><?php echo Yii::t('BookingModule.booking', 'Пример краткой версии варианта бронирования'); ?></a></li>
    <li><a href="#full" data-toggle="tab"><?php echo Yii::t('BookingModule.booking', 'Пример полной версии варианта бронирования'); ?></a></li>
</ul>
<div class="tab-content">
    <div id="anounce" class="tab-pane fade active in">
        <div style="margin-bottom: 20px;">
            <h6>
                <span class="label"><?php echo $model->creation_date; ?></span> 
                <?php echo CHtml::link($model->title, array('/bookingvariants/show', 'title' => $model->alias)); ?>
            </h6>
            <p>
                <?php echo $model->short_text; ?>
            </p>
            <i class="icon-globe"></i> <?php echo $model->getPermaLink(); ?>
        </div>
    </div>
    <div id="full"  class="tab-pane fade">
        <div style="margin-bottom: 20px;">
            <h3><?php echo CHtml::link($model->title, array('/bookingvariants/show', 'title' => $model->alias)); ?></h3>
            <p><?php echo $model->full_text; ?></p>
            <span class="label"><?php echo $model->creation_date; ?></span>
            <i class="icon-user"></i><?php echo CHtml::link($model->user->fullName, array('/user/people/' . $model->user->nick_name)); ?>
            <i class="icon-globe"></i> <?php echo $model->getPermaLink(); ?>
        </div>
    </div>
</div>