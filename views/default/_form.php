<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'booking-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'type' => 'vertical',
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

<?php if (count($languages) > 1): ?>
    <?php echo $form->dropDownListRow($model, 'lang', $languages, array('class' => 'popover-help', 'empty' => Yii::t('BookingModule.booking', '--выберите--'))); ?>
    <?php if (!$model->isNewRecord): ?>
        <?php foreach ($languages as $k => $v): ?>
            <?php if ($k !== $model->lang): ?>
                <?php if (empty($langModels[$k])): ?>
                    <a href="<?php echo $this->createUrl('/booking/default/create', array('id' => $model->id, 'lang' => $k)); ?>"><i class="iconflags iconflags-<?php echo $k; ?>" title="<?php echo Yii::t('BookingModule.booking', 'Добавить перевод на {lang} язык', array('{lang}' => $v)) ?>"></i></a>
                <?php else: ?>
                    <a href="<?php echo $this->createUrl('/booking/default/update', array('id' => $langModels[$k])); ?>"><i class="iconflags iconflags-<?php echo $k; ?>" title="<?php echo Yii::t('BookingModule.booking', 'Редактировать перевод на {lang} язык', array('{lang}' => $v)) ?>"></i></a>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
<?php else: ?>
    <?php echo $form->hiddenField($model, 'lang'); ?>
<?php endif; ?>
<?php if ($model->isNewRecord) : ?>
    <div class="row-fluid control-group <?php echo $model->hasErrors('company_id') ? 'error' : ''; ?>">
        <?php echo $form->dropDownListRow($model, 'company_id', CHtml::listData($this->module->getCompanyList(), 'id', 'title'), array('class' => 'span7 popover-help', 'empty' => Yii::t('BookingModule.booking', '--выберите--'))); ?>
    </div>
<?php else : ?>
    <div class="row-fluid control-group <?php echo $model->hasErrors('company_id') ? 'error' : ''; ?>">
        <?php echo $form->labelEx($model, 'company_id'); ?>
        <div class="controls">
            <div class="control-text"><strong><?php echo $model->companyName ?></strong></div>
        </div>
        <?php echo $form->hiddenField($model, 'company_id') ?>
    </div>                
<?php endif; ?>

<div class="row-fluid control-group <?php echo $model->hasErrors('title') ? 'error' : ''; ?>">
    <?php echo $form->textFieldRow($model, 'title', array('size' => 60, 'maxlength' => 150, 'class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('title'), 'data-content' => $model->getAttributeDescription('title'))); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('alias') ? 'error' : ''; ?>">
    <?php echo $form->textFieldRow($model, 'alias', array('size' => 60, 'maxlength' => 150, 'class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('alias'), 'data-content' => $model->getAttributeDescription('alias'))); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('link') ? 'error' : ''; ?>" style="display: none;">
    <?php echo $form->textFieldRow($model, 'link', array('size' => 60, 'maxlength' => 150, 'class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('link'), 'data-content' => $model->getAttributeDescription('link'))); ?>
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
<div class="row-fluid control-group <?php echo $model->hasErrors('short_text') ? 'error' : ''; ?>">
    <div class="span12">
        <?php echo $form->labelEx($model, 'short_text'); ?>
        <?php
        $this->widget($this->module->editor, array(
            'model' => $model,
            'attribute' => 'short_text',
            'options' => $this->module->editorOptions,
        ));
        ?>
        <?php echo $form->error($model, 'short_text'); ?>
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

<div class="row-fluid control-group">
    <div class="span4 popover-help <?php echo $model->hasErrors('creation_date') ? 'error' : ''; ?>" data-original-title='<?php echo $model->getAttributeLabel('creation_date'); ?>' data-content='<?php echo $model->getAttributeDescription('creation_date'); ?>'>
        <?php
        echo $form->datepickerRow(
                $model, 'creation_date', array(
            'prepend' => '<i class="icon-calendar"></i>',
            'options' => array(
                'format' => 'dd.mm.yyyy',
                'weekStart' => 1,
                'autoclose' => true,
            ),
            'class' => 'span9'
                )
        );
        ?>
    </div>

    <div class="span4 popover-help <?php echo $model->hasErrors('change_date') ? 'error' : ''; ?>" data-original-title='<?php echo $model->getAttributeLabel('change_date'); ?>' data-content='<?php echo $model->getAttributeDescription('change_date'); ?>'>
        <?php
        echo $form->datepickerRow(
                $model, 'change_date', array(
            'prepend' => '<i class="icon-calendar"></i>',
            'options' => array(
                'format' => 'dd.mm.yyyy',
                'weekStart' => 1,
                'autoclose' => true,
            ),
            'class' => 'span9'
                )
        );
        ?>
    </div>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('is_protected') ? 'error' : ''; ?>">
    <?php echo $form->checkBoxRow($model, 'is_protected', $model->getProtectedStatusList()); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('status') ? 'error' : ''; ?>">
    <?php echo $form->dropDownListRow($model, 'status', $model->getStatusList(), array('class' => 'span7 popover-help', 'options' => ($model->isNewRecord ? array(Booking::STATUS_PUBLISHED => array('selected' => true)) : ''))); ?>
</div>

<?php $collapse = $this->beginWidget('bootstrap.widgets.TbCollapse'); ?>
<div class="accordion-group">
    <div class="accordion-heading">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
            <?php echo Yii::t('BookingModule.booking', 'Данные для поисковой оптимизации'); ?>
        </a>
    </div>
    <div id="collapseOne" class="accordion-body collapse">
        <div class="accordion-inner">
            <div class="row-fluid control-group <?php echo $model->hasErrors('keywords') ? 'error' : ''; ?>">
                <?php echo $form->textFieldRow($model, 'keywords', array('size' => 60, 'maxlength' => 150, 'class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('keywords'), 'data-content' => $model->getAttributeDescription('keywords'))); ?>
            </div>
            <div class="row-fluid control-group <?php echo $model->hasErrors('description') ? 'error' : ''; ?>">
                <?php echo $form->textAreaRow($model, 'description', array('rows' => 3, 'cols' => 98, 'class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('description'), 'data-content' => $model->getAttributeDescription('description'))); ?>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

<br/>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'label' => $model->isNewRecord ? Yii::t('BookingModule.booking', 'Добавить зону и продолжить') : Yii::t('BookingModule.booking', 'Сохранить зону и продолжить'),
));
?>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'htmlOptions' => array('name' => 'submit-type', 'value' => 'index'),
    'label' => $model->isNewRecord ? Yii::t('BookingModule.booking', 'Добавить зону и закрыть') : Yii::t('BookingModule.booking', 'Сохранить зону и закрыть'),
));
?>

<?php $this->endWidget(); ?>
