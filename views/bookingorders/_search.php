<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'action'      => Yii::app()->createUrl($this->route),
    'method'      => 'get',
    'type'        => 'vertical',
    'htmlOptions' => array('class' => 'well form-vertical'),
)); ?>
    <fieldset class="inline">
        <div class="row-fluid control-group">
            <div class="span3">
                <?php echo $form->textFieldRow($model, 'id'); ?>
            </div>
        </div>
        <div class="row-fluid control-group">
            <div class="span3">
                <?php echo $form->textFieldRow($model, 'booking_id'); ?>
            </div>
            <div class="span3">
                <?php echo $form->textFieldRow($model, 'variant_id'); ?>
            </div>
            <div class="span3">
                <?php echo $form->textFieldRow($model, 'item_id'); ?>
            </div>
            <div class="span3">
                <?php echo $form->textFieldRow($model, 'position_id'); ?>
            </div>
        </div>
        <div class="row-fluid control-group">
            <div class="span3">
                <?php echo $form->textFieldRow($model, 'creation_date'); ?>
            </div>
            <div class="span3">
                <?php echo $form->textFieldRow($model, 'change_date'); ?>
            </div>
        </div>
        <div class="row-fluid control-group">
            <div class="span3">
                <?php echo $form->dropDownListRow($model, 'status', $model->statusList, array('empty' => Yii::t('BookingModule.booking', '- не важен -'))); ?>
            </div>
        </div>
        <div class="row-fluid control-group">
            <div class="span6">
                <?php echo $form->textFieldRow($model, 'sum'); ?>
            </div>
            <div class="span6">
                <?php echo $form->textFieldRow($model, 'qty'); ?>
            </div>
        </div>
        <div class="row-fluid control-group">
            <div class="span6">
                <?php echo $form->textFieldRow($model, 'comments'); ?>
            </div>
        </div>
    </fieldset>

    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'type'        => 'primary',
        'encodeLabel' => false,
        'buttonType'  => 'submit',
        'label'       => '<i class="icon-search icon-white">&nbsp;</i> ' . Yii::t('booking', 'Искать заказ'),
    )); ?>

<?php $this->endWidget(); ?>
