<?php
/* @var $this BookingItemsPositionController */
/* @var $model BookingItemsPosition */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'booking-items-position-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'type' => 'vertical',
    'htmlOptions' => array('class' => 'well', 'enctype' => 'multipart/form-data'),
    'inlineErrors' => true,
        ));
?>

<?php echo $form->errorSummary($model); ?>


<div class="row-fluid control-group <?php echo $model->hasErrors('title') ? 'error' : ''; ?>">
    <?php echo $form->textFieldRow($model, 'title', array('size' => 60, 'maxlength' => 150, 'class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('title'), 'data-content' => $model->getAttributeDescription('title'))); ?>
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
        <?php echo $form->error($model, 'full_text'); ?>
    </div>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('is_protected') ? 'error' : ''; ?>">
    <?php echo $form->checkBoxRow($model, 'is_protected', $model->getProtectedStatusList()); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('status') ? 'error' : ''; ?>">
    <?php echo $form->dropDownListRow($model, 'status', $model->getStatusList(), array('class' => 'span7 popover-help')); ?>
</div>
<?php echo $form->hiddenField($model, 'date') ?>
<?php echo $form->hiddenField($model, 'item_id') ?>
<?php echo $form->hiddenField($model, 'booking_id') ?>
<?php echo $form->hiddenField($model, 'variant_id') ?>
<?php echo $form->hiddenField($model, 'pos_x') ?>
<?php echo $form->hiddenField($model, 'pos_y') ?>
<?php echo $form->hiddenField($model, 'width') ?>
<?php echo $form->hiddenField($model, 'height') ?>
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
