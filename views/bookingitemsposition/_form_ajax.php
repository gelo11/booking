<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'booking-items-position-form-' . $model->id,
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'type' => 'vertical',
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => "ajax-form"),
    'inlineErrors' => true,
        ));
?>

<?php echo $form->errorSummary($model); ?>


<div class="row-fluid control-group <?php echo $model->hasErrors('title') ? 'error' : ''; ?>">
    <?php echo $form->textFieldRow($model, 'title', array('size' => 60, 'maxlength' => 255, 'id' => 'BookingItemsPosition_title_' . $model->id, 'class' => 'span10', 'data-original-title' => $model->getAttributeLabel('title'), 'data-content' => $model->getAttributeDescription('title'))); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('price') ? 'error' : ''; ?>">
    <?php echo $form->textFieldRow($model, 'price', array('id' => 'BookingItemsPosition_price_' . $model->id, 'class' => 'span6 popover-help', 'data-original-title' => $model->getAttributeLabel('price'), 'data-content' => $model->getAttributeDescription('price'))); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('whole_item') ? 'error' : ''; ?>">
    <?php echo $form->checkBoxRow($model, 'whole_item', array('id' => 'BookingItemsPosition_whole_item_' . $model->id, 'class' => 'whole-item')); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('seats') ? 'error' : ''; ?>" <?php echo empty($model->whole_item) ? '' : 'style="display:none;"' ?>>
    <?php echo $form->textFieldRow($model, 'seats', array('id' => 'BookingItemsPosition_seats_' . $model->id, 'class' => 'span6 popover-help', 'data-original-title' => $model->getAttributeLabel('seats'), 'data-content' => $model->getAttributeDescription('seats'))); ?>
</div>

<?php /*
  <div class="row-fluid control-group <?php echo $model->hasErrors('price_per_seats') ? 'error' : ''; ?>">
  <?php echo $form->textFieldRow($model, 'price_per_seats', array('id' => 'BookingItemsPosition_price_per_seats_' . $model->id, 'class' => 'span6 popover-help', 'data-original-title' => $model->getAttributeLabel('price_per_seats'), 'data-content' => $model->getAttributeDescription('price_per_seats'))); ?>
  </div>
 */ ?>
<div class="row-fluid control-group <?php echo $model->hasErrors('image') ? 'error' : ''; ?>">
    <div class="span7  popover-help"  data-original-title="<?php echo $model->getAttributeLabel('image'); ?>">
        <?php if (!$model->isNewRecord && $model->image): ?>
            <?php echo CHtml::image($model->imageUrl, $model->title, array('width' => 300, 'height' => 300)); ?>
        <?php endif; ?>
        <?php echo $form->labelEx($model, 'image'); ?>
        <?php echo $form->fileField($model, 'image', array('id' => 'BookingItemsPosition_image_' . $model->id)); ?>
    </div>
    <div class="span5">
        <?php echo $form->error($model, 'image'); ?>
    </div>
</div>
<div class="row-fluid control-group <?php echo $model->hasErrors('full_text') ? 'error' : ''; ?>">
    <?php echo $form->textAreaRow($model, 'full_text', array('class' => 'redactor', 'id' => 'BookingItemsPosition_full_text_' . $model->id, 'data-original-title' => $model->getAttributeLabel('full_text'), 'data-content' => $model->getAttributeDescription('full_text'))); ?>
</div>
<?php /*
<div class="row-fluid control-group">
    <div class="span5 <?php echo $model->hasErrors('color') ? 'error' : ''; ?>">
        <?php echo $form->labelEx($model, 'color'); ?>
        <?php
        $this->widget('bootstrap.widgets.TbColorPicker', array(
            'model' => $model,
            'attribute' => 'color',
            'htmlOptions' => array(
                'id' => 'BookingItemsPosition_color_' . $model->id,
            ),
        ));
        ?>
    </div>
    <div class="span5 <?php echo $model->hasErrors('border_color') ? 'error' : ''; ?>">
        <?php echo $form->labelEx($model, 'border_color'); ?>
        <?php
        $this->widget('bootstrap.widgets.TbColorPicker', array(
            'model' => $model,
            'attribute' => 'border_color',
            'htmlOptions' => array(
                'id' => 'BookingItemsPosition_border_color_' . $model->id,
            ),
        ));
        ?>
    </div>
</div>
*/ ?>
<div class="row-fluid control-group">
    <div class="span5 <?php echo $model->hasErrors('color') ? 'error' : ''; ?>">
        <?php echo $form->textFieldRow($model, 'color', array('id' => 'BookingItemsPosition_color_' . $model->id, 'class' => 'span7 colorpicker')); ?>
    </div>
    <div class="span5 <?php echo $model->hasErrors('border_color') ? 'error' : ''; ?>">
        <?php echo $form->textFieldRow($model, 'border_color', array('id' => 'BookingItemsPosition_border_color_' . $model->id, 'class' => 'span7 colorpicker')); ?>
    </div>
</div>

<div class="row-fluid control-group">
    <div class="span5 <?php echo $model->hasErrors('transparent') ? 'error' : ''; ?>">
        <?php echo $form->textFieldRow($model, 'transparent', array('id' => 'BookingItemsPosition_transparent_' . $model->id, 'class' => 'span7 popover-help')); ?>
    </div>
    <div class="span5 <?php echo $model->hasErrors('border_radius') ? 'error' : ''; ?>">
        <?php echo $form->textFieldRow($model, 'border_radius', array('id' => 'BookingItemsPosition_border_radius_' . $model->id, 'class' => 'span7 popover-help')); ?>
    </div>
</div>

<div class="row-fluid control-group">
    <div class="span5 <?php echo $model->hasErrors('width') ? 'error' : ''; ?>">
        <?php echo $form->textFieldRow($model, 'width', array('id' => 'BookingItemsPosition_width_' . $model->id, 'class' => 'span7')); ?>
    </div>
    <div class="span5 <?php echo $model->hasErrors('height') ? 'error' : ''; ?>">
        <?php echo $form->textFieldRow($model, 'height', array('id' => 'BookingItemsPosition_height_' . $model->id, 'class' => 'span7')); ?>
    </div>
</div>

<div class="row-fluid control-group">
    <div class="span5 <?php echo $model->hasErrors('pos_x') ? 'error' : ''; ?>">
        <?php echo $form->textFieldRow($model, 'pos_x', array('id' => 'BookingItemsPosition_pos_x_' . $model->id, 'class' => 'span7')); ?>
    </div>
    <div class="span5 <?php echo $model->hasErrors('pos_y') ? 'error' : ''; ?>">
        <?php echo $form->textFieldRow($model, 'pos_y', array('id' => 'BookingItemsPosition_pos_y_' . $model->id, 'class' => 'span7')); ?>
    </div>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('date') ? 'error' : ''; ?>">
    <div class="span4 popover-help" data-original-title='<?php echo $model->getAttributeLabel('date'); ?>' data-content='<?php echo $model->getAttributeDescription('date'); ?>'>
        <?php
        echo $form->datepickerRow(
                $model, 'date', array(
            'prepend' => '<i class="icon-calendar"></i>',
            'options' => array(
                'format' => 'dd.mm.yyyy',
                'weekStart' => 1,
                'autoclose' => true,
            ),
            'class' => 'span11',
            'id' => 'BookingItemsPosition_date_' . $model->id
                )
        );
        ?>
    </div>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('is_protected') ? 'error' : ''; ?>">
    <?php echo $form->checkBoxRow($model, 'is_protected', $model->getProtectedStatusList(), array('id' => 'BookingItemsPosition_is_protected_' . $model->id)); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('status') ? 'error' : ''; ?>">
    <?php echo $form->dropDownListRow($model, 'status', $model->getStatusList(), array('class' => 'span7 popover-help', 'id' => 'BookingItemsPosition_status_' . $model->id)); ?>
</div>
<?php echo $form->hiddenField($model, 'id', array('id' => 'BookingItemsPosition_id_' . $model->id)) ?>
<?php echo $form->hiddenField($model, 'booking_id', array('id' => 'BookingItemsPosition_booking_id_' . $model->id)) ?>
<?php echo $form->hiddenField($model, 'variant_id', array('id' => 'BookingItemsPosition_variant_id_' . $model->id)) ?>

<br/>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'type' => 'primary',
    'label' => Yii::t('BookingModule.booking', 'Сохранить'),
    'buttonType' => 'button',
    'id' => 'ajax-form-button-update-' . $model->id,
    'htmlOptions' => array('class' => 'ajax-form-button-update'),
));
?>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'button',
    'type' => 'danger',
    'label' => Yii::t('BookingModule.booking', 'Удалить'),
    'id' => 'ajax-form-button-delete-' . $model->id,
    'htmlOptions' => array(
        'class' => 'ajax-form-button-delete',
    ),
));
?>

<?php $this->endWidget(); ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#ajax-form-button-update-<?php echo $model->id; ?>').live('click', function() {
            var new_item_num = parseInt(jQuery("#booking-items-position-form-<?php echo $model->id; ?>").parents(".modal").find(".modal-header h4").text());
            jQuery.ajax({
                'url': '/booking/bookingitemsposition/update/<?php echo $model->id; ?>',
                'type': "POST",
                'data': jQuery("#booking-items-position-form-<?php echo $model->id; ?>").serialize(),
                'success': function(r) {
                    jQuery("li.cloned[data-id=<?php echo $model->id; ?>]").find('.draggable-item-title').html(jQuery("#booking-items-position-form-<?php echo $model->id; ?>").find('#BookingItemsPosition_title_<?php echo $model->id; ?>').val());
                    jQuery("#booking-items-position-" + (new_item_num - 1)).find('.draggable-item-title').html(jQuery("#booking-items-position-form-<?php echo $model->id; ?>").find('#BookingItemsPosition_title_<?php echo $model->id; ?>').val());
                    jQuery("#booking-items-position-form-<?php echo $model->id; ?>").parents(".modal").find(".modal-header").append('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><b>' + r + '</b></div>');
                    jQuery(".modal-header").find(".alert").delay(4000).fadeOut("slow");

                },
                'error': function(r) {
                    jQuery("#booking-items-position-form-<?php echo $model->id; ?>").parents(".modal").find(".modal-header").prepend('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><b>' + r + '</b></div>');

                },
            });
        });
        jQuery('#ajax-form-button-delete-<?php echo $model->id; ?>').live('click', function() {
            if (window.confirm('<?php echo Yii::t('BookingModule.booking', 'Удалить этот объект расстановки?') ?>')) {
                var new_item_num = parseInt(jQuery("#booking-items-position-form-<?php echo $model->id; ?>").parents(".modal").find(".modal-header h4").text());
                jQuery.ajax({
                    'url': '/booking/bookingitemsposition/delete/<?php echo $model->id; ?>',
                    'type': "POST",
                    'data': jQuery("#booking-items-position-form-<?php echo $model->id; ?>").serialize(),
                    'success': function(r) {
                        var modal = jQuery("#booking-items-position-form-<?php echo $model->id; ?>").parents(".modal");
                        modal.modal("hide").remove();
                        jQuery("li.cloned[data-id=<?php echo $model->id; ?>]").remove();
                        jQuery("#booking-items-position-" + (new_item_num - 1)).remove();
                        alert(r);
                    },
                    'error': function(r) {
                        jQuery("#booking-items-position-form-<?php echo $model->id; ?>").parents(".modal").find(".modal-header").prepend('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><b>' + r + '</b></div>');
                    },
                });
            }
        });
    });
</script>