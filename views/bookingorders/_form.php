
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'bookingitem-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'type' => 'horizontal',
    'htmlOptions' => array('class' => 'well', 'enctype' => 'multipart/form-data'),
    'inlineErrors' => true,
        ));
?>
<div class="alert alert-info">
    <?php echo Yii::t('BookingModule.booking', 'Поля, отмеченные'); ?>
    <span class="required">*</span>
    <?php echo Yii::t('BookingModule.booking', 'обязательны.'); ?>
</div>

<?php echo $form->errorSummary($model); ?>

<div class="control-group">

    <div class="row-fluid">
        <div class="span8 popover-help <?php echo $model->hasErrors('booking_date') ? 'error' : ''; ?>" data-original-title='<?php echo $model->getAttributeLabel('booking_date'); ?>' data-content='<?php echo $model->getAttributeDescription('booking_date'); ?>'>
            <?php echo $form->labelEx($model, 'booking_date', array('class' => 'control-label')); ?>
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-calendar"></i></span>
                    <?php echo $form->textField($model, 'booking_date', array('id' => 'BookingOrders_booking_date_' . $item->id, 'class' => 'span9', 'readonly' => 'readonly')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row-fluid <?php echo $model->hasErrors('booking_id') ? 'error' : ''; ?>">
        <?php echo $form->labelEx($model, 'booking_id', array('class' => 'control-label')); ?>
        <div class="controls">
            <div class="control-text"><?php echo $model->bookingTitle; ?></div>
        </div>
        <?php echo $form->hiddenField($model, 'booking_id') ?>
    </div>

    <div class="row-fluid <?php echo $model->hasErrors('variant_id') ? 'error' : ''; ?>">
        <?php echo $form->labelEx($model, 'variant_id', array('class' => 'control-label')); ?>
        <div class="controls">
            <div class="control-text"><?php echo $model->variantTitle ?></div>
        </div>
        <?php echo $form->hiddenField($model, 'variant_id') ?>
    </div>

    <div class="row-fluid <?php echo $model->hasErrors('item_id') ? 'error' : ''; ?>">
        <?php echo $form->labelEx($model, 'item_id', array('class' => 'control-label')); ?>
        <div class="controls">
            <div class="control-text"><?php echo $model->item->title ?></div>
        </div>
        <?php echo $form->hiddenField($model, 'item_id') ?>
    </div>

    <div class="row-fluid <?php echo $model->hasErrors('position_id') ? 'error' : ''; ?>">
        <?php echo $form->labelEx($model, 'position_id', array('class' => 'control-label')); ?>
        <div class="controls">
            <div class="control-text"><?php echo $model->item_position->title ?></div>
        </div>
        <?php echo $form->hiddenField($model, 'position_id') ?>
    </div>
    <?php echo $form->hiddenField($model, 'user_id') ?>

    <div class="row-fluid">
        <label class="control-label"><?php echo Yii::t('BookingModule.booking', 'Имя пользователя'); ?></label>
        <div class="controls">
            <div class="control-text"><?php echo Yii::app()->user->profile->first_name . ' ' . Yii::app()->user->profile->last_name ?></div>
        </div>
    </div>
    <div class="row-fluid">
        <label class="control-label"><?php echo Yii::t('BookingModule.booking', 'Логин пользователя'); ?></label>
        <div class="controls">
            <div class="control-text"><?php echo Yii::app()->user->nick_name ?></div>
        </div>
    </div>
    <div class="row-fluid">
        <label class="control-label"><?php echo Yii::t('BookingModule.booking', 'Email пользователя'); ?></label>
        <div class="controls">
            <div class="control-text"><?php echo Yii::app()->user->profile->email ?></div>
        </div>
    </div>
    <br/>

    <div class="row-fluid <?php echo $model->hasErrors('sum') ? 'error' : ''; ?>">
        <?php echo $form->textFieldRow($model, 'sum', array('id' => 'BookingOrders_sum_' . $item->id, 'class' => 'span4', 'readonly' => 'readonly')); ?>
    </div>

    <div class="row-fluid <?php echo $model->hasErrors('qty') ? 'error' : ''; ?>" <?php echo $item->whole_item == 1 ? 'style="display:none"' : '' ?>>
        <?php echo $form->labelEx($model, 'qty', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php if ($model->isNewRecord) : ?>
                <?php echo $form->textField($model, 'qty', array('id' => 'BookingOrders_qty_' . $item->id, 'class' => 'span2')); ?>
            <?php else : ?>
                <div class="control-text"><?php echo $model->qty ?></div>
            <?php endif; ?>
        </div>
    </div>
    <br/>
    
    <div class="row-fluid control-group <?php echo $model->hasErrors('comments') ? 'error' : ''; ?>">

        <?php echo $form->labelEx($model, 'comments', array('class' => 'control-label')); ?>
        <?php if ($model->isNewRecord) : ?>
            <div class="span10">
                <?php
                $this->widget($this->module->editor, array(
                    'model' => $model,
                    'attribute' => 'comments',
                    'options' => $this->module->editorOptions,
                ));
                ?>
                <?php echo $form->error($model, 'comments'); ?>
            </div>
        <?php else : ?>
            <div class="controls">
                <div class="control-text"><?php echo $model->comments; ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>
<br/>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'label' => $model->isNewRecord ? Yii::t('BookingModule.booking', 'Добавить заказ и продолжить') : Yii::t('BookingModule.booking', 'Сохранить заказ и продолжить'),
));
?>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'htmlOptions' => array('name' => 'submit-type', 'value' => 'index'),
    'label' => $model->isNewRecord ? Yii::t('BookingModule.booking', 'Добавить заказ и закрыть') : Yii::t('BookingModule.booking', 'Сохранить заказ и закрыть'),
));
?>

<?php $this->endWidget(); ?>
