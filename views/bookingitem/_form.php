
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'booking-items-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'type' => 'vertical',
    'htmlOptions' => array('class' => 'well', 'enctype' => 'multipart/form-data'),
    'inlineErrors' => true,
        ));

if($model->isNewRecord) {
    $model->transparent = 7;
    $model->border_radius = '2px 2px 2px 2px';
}

$mainAssets = Yii::app()->assetManager->publish(
        Yii::getPathOfAlias('application.modules.booking.views.bookingitem.assets')
);
Yii::app()->clientScript->registerScriptFile($mainAssets . '/js/main.js');
?>
<div class="alert alert-info">
    <?php echo Yii::t('BookingModule.booking', 'Поля, отмеченные'); ?>
    <span class="required">*</span>
    <?php echo Yii::t('BookingModule.booking', 'обязательны.'); ?>
</div>

<?php echo $form->errorSummary($model); ?>

<?php if (count($languages) > 1): ?>
    <?php echo $form->dropDownListRow($model, 'lang', $languages, array('class' => 'popover-help', 'empty' => Yii::t('BookingModule.booking', '--выберите--'))); ?>
    <?php if (!$model->isNewRecord): ?>
        <?php foreach ($languages as $k => $v): ?>
            <?php if ($k !== $model->lang): ?>
                <?php if (empty($langModels[$k])): ?>
                    <a href="<?php echo $this->createUrl('/booking/bookingitem/create', array('id' => $model->id, 'lang' => $k)); ?>"><i class="iconflags iconflags-<?php echo $k; ?>" title="<?php echo Yii::t('BookingModule.booking', 'Добавить перевод на {lang} язык', array('{lang}' => $v)) ?>"></i></a>
                <?php else: ?>
                    <a href="<?php echo $this->createUrl('/booking/bookingitem/update', array('id' => $langModels[$k])); ?>"><i class="iconflags iconflags-<?php echo $k; ?>" title="<?php echo Yii::t('BookingModule.booking', 'Редактировать перевод на {lang} язык', array('{lang}' => $v)) ?>"></i></a>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
<?php else: ?>
    <?php echo $form->hiddenField($model, 'lang'); ?>
<?php endif; ?>

<div class="row-fluid control-group <?php echo $model->hasErrors('title') ? 'error' : ''; ?>">
    <?php echo $form->textFieldRow($model, 'title', array('size' => 60, 'maxlength' => 255, 'class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('title'), 'data-content' => $model->getAttributeDescription('title'))); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('alias') ? 'error' : ''; ?>">
    <?php echo $form->textFieldRow($model, 'alias', array('size' => 60, 'maxlength' => 255, 'class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('alias'), 'data-content' => $model->getAttributeDescription('alias'))); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('price') ? 'error' : ''; ?>">
    <?php echo $form->textFieldRow($model, 'price', array('class' => 'span4 popover-help', 'data-original-title' => $model->getAttributeLabel('price'), 'data-content' => $model->getAttributeDescription('price'))); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('whole_item') ? 'error' : ''; ?>">
    <?php echo $form->checkBoxRow($model, 'whole_item', array('id' => 'BookingItemsPosition_whole_item_' . $model->id, 'class' => 'whole-item')); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('seats') ? 'error' : ''; ?>">
    <?php echo $form->textFieldRow($model, 'seats', array('class' => 'span4 popover-help', 'data-original-title' => $model->getAttributeLabel('seats'), 'data-content' => $model->getAttributeDescription('seats'))); ?>
</div>

<?php /*
  <div class="row-fluid control-group <?php echo $model->hasErrors('price_per_seats') ? 'error' : ''; ?>">
  <?php echo $form->textFieldRow($model, 'price_per_seats', array('class' => 'span4 popover-help', 'data-original-title' => $model->getAttributeLabel('price_per_seats'), 'data-content' => $model->getAttributeDescription('price_per_seats'))); ?>
  </div>
 */ ?>

<div class="row-fluid control-group">
    <div class="span5 <?php echo $model->hasErrors('color') ? 'error' : ''; ?>">
        <?php echo $form->labelEx($model, 'color'); ?>
        <?php
        $this->widget('bootstrap.widgets.TbColorPicker', array(
            'model' => $model,
            'attribute' => 'color',
            'htmlOptions' => array('class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('color'), 'data-content' => $model->getAttributeDescription('color'))
        ));
        ?>
    </div>
    <div class="span5 <?php echo $model->hasErrors('border_color') ? 'error' : ''; ?>">
        <?php echo $form->labelEx($model, 'border_color'); ?>
        <?php
        $this->widget('bootstrap.widgets.TbColorPicker', array(
            'model' => $model,
            'attribute' => 'border_color',
            'htmlOptions' => array('class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('border_color'), 'data-content' => $model->getAttributeDescription('border_color'))
        ));
        ?>
    </div>
</div>

<div class="row-fluid control-group">
    <div class="span5 <?php echo $model->hasErrors('transparent') ? 'error' : ''; ?>">
        <?php echo $form->textFieldRow($model, 'transparent', array('class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('transparent'), 'data-content' => $model->getAttributeDescription('transparent'))); ?>
    </div>
    <div class="span5 <?php echo $model->hasErrors('border_radius') ? 'error' : ''; ?>">
        <?php echo $form->textFieldRow($model, 'border_radius', array('class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('border_radius'), 'data-content' => $model->getAttributeDescription('border_radius'))); ?>
    </div>
</div>

<div class="row-fluid control-group">
    <div class="span5 <?php echo $model->hasErrors('width') ? 'error' : ''; ?>">
        <?php echo $form->textFieldRow($model, 'width', array('size' => 20, 'maxlength' => 20, 'class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('width'), 'data-content' => $model->getAttributeDescription('width'))); ?>
    </div>

    <div class="span5 <?php echo $model->hasErrors('lenght') ? 'error' : ''; ?>">
        <?php echo $form->textFieldRow($model, 'lenght', array('size' => 20, 'maxlength' => 20, 'class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('lenght'), 'data-content' => $model->getAttributeDescription('lenght'))); ?>
    </div>
</div>
                    
<div class="row-fluid control-group <?php echo $model->hasErrors('hide_number') ? 'error' : ''; ?>">
    <?php echo $form->checkBoxRow($model, 'hide_number', array('class' => 'popover-help', 'data-original-title' => $model->getAttributeLabel('hide_number'), 'data-content' => $model->getAttributeDescription('hide_number'))); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('image') ? 'error' : ''; ?>">
    <div class="span7  popover-help"  data-original-title="<?php echo $model->getAttributeLabel('image'); ?>">
        <?php if (!$model->isNewRecord && $model->image): ?>
            <?php echo CHtml::image($model->imageUrl, $model->title, array('width' => 300, 'height' => 300)); ?>
        <?php endif; ?>
        <?php echo $form->labelEx($model, 'image'); ?>
        <?php echo $form->fileField($model, 'image'); ?>
    </div>
    <div class="span5">
        <?php echo $form->error($model, 'image'); ?>
    </div>
</div>
<div class="row-fluid control-group <?php echo $model->hasErrors('full_text') ? 'error' : ''; ?>">
    <div class="span12">
        <?php echo $form->labelEx($model, 'full_text'); ?>
        <?php
        $this->widget($this->module->editor, array(
            'model' => $model,
            'attribute' => 'full_text',
            'options' => $this->module->editorOptions,
        ));
        ?>
        <span class="help-block"><?php echo Yii::t('BookingModule.booking', "Полный текст компании отображается при переходе по ссылке &laquo;Подробнее&raquo; или иногда при клике на заголовке компании."); ?></span>
        <?php echo $form->error($model, 'full_text'); ?>
    </div>
</div>

<?php /*
  <div class="row-fluid control-group <?php echo $model->hasErrors('left') ? 'error' : ''; ?>">
  <?php echo $form->checkBoxRow($model, 'left', $model->getYesNoList()); ?>
  <div class="span5 <?php echo $model->hasErrors('left_qty') ? 'error' : ''; ?>">
  <?php echo $form->textFieldRow($model, 'left_qty', array('size' => 20, 'maxlength' => 20, 'class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('left_qty'), 'data-content' => $model->getAttributeDescription('left_qty'))); ?>
  </div>
  </div>
  <div class="row-fluid control-group <?php echo $model->hasErrors('right') ? 'error' : ''; ?>">
  <?php echo $form->checkBoxRow($model, 'right', $model->getYesNoList()); ?>
  <div class="span5 <?php echo $model->hasErrors('right_qty') ? 'error' : ''; ?>">
  <?php echo $form->textFieldRow($model, 'right_qty', array('size' => 20, 'maxlength' => 20, 'class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('right_qty'), 'data-content' => $model->getAttributeDescription('right_qty'))); ?>
  </div>
  </div>

  <div class="row-fluid control-group <?php echo $model->hasErrors('top') ? 'error' : ''; ?>">
  <?php echo $form->checkBoxRow($model, 'top', $model->getYesNoList()); ?>
  <div class="span5 <?php echo $model->hasErrors('top_qty') ? 'error' : ''; ?>">
  <?php echo $form->textFieldRow($model, 'top_qty', array('size' => 20, 'maxlength' => 20, 'class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('top_qty'), 'data-content' => $model->getAttributeDescription('top_qty'))); ?>
  </div>
  </div>

  <div class="row-fluid control-group <?php echo $model->hasErrors('bottom') ? 'error' : ''; ?>">
  <?php echo $form->checkBoxRow($model, 'bottom', $model->getYesNoList()); ?>
  <div class="span5 <?php echo $model->hasErrors('bottom_qty') ? 'error' : ''; ?>">
  <?php echo $form->textFieldRow($model, 'bottom_qty', array('size' => 20, 'maxlength' => 20, 'class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('bottom_qty'), 'data-content' => $model->getAttributeDescription('bottom_qty'))); ?>
  </div>
  </div>
 */ ?>
<div class="row-fluid control-group <?php echo $model->hasErrors('is_protected') ? 'error' : ''; ?>">
    <?php echo $form->checkBoxRow($model, 'is_protected', $model->getProtectedStatusList()); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('status') ? 'error' : ''; ?>">
    <?php echo $form->dropDownListRow($model, 'status', $model->getStatusList(), array('class' => 'span7 popover-help', 'options' => ($model->isNewRecord ? array(Booking::STATUS_PUBLISHED => array('selected' => true)) : ''))); ?>
</div>

<br/>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'label' => $model->isNewRecord ? Yii::t('BookingModule.booking', 'Добавить объект и продолжить') : Yii::t('BookingModule.booking', 'Сохранить объект и продолжить'),
));
?>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'htmlOptions' => array('name' => 'submit-type', 'value' => 'index'),
    'label' => $model->isNewRecord ? Yii::t('BookingModule.booking', 'Добавить объект и закрыть') : Yii::t('BookingModule.booking', 'Сохранить объект и закрыть'),
));
?>

<?php $this->endWidget(); ?>
