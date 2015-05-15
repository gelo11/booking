<?php
$cs = Yii::app()->getClientScript();
$cs->registerCss('datepicker', '.colorpicker.dropdown-menu{z-index: 10000;}');

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'booking-variants-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'type' => 'vertical',
    'htmlOptions' => array('class' => 'well', 'enctype' => 'multipart/form-data'),
    'inlineErrors' => true,
        ));



$mainAssets = Yii::app()->assetManager->publish(
        Yii::getPathOfAlias('application.modules.booking.views.bookingvariants.assets')
);
Yii::app()->clientScript->registerScriptFile($mainAssets . '/js/main.js');
Yii::app()->clientScript->registerCSSFile($mainAssets . '/js/jquery.datepick/jquery.datepick.css');
Yii::app()->clientScript->registerScriptFile($mainAssets . '/js/jquery.datepick/jquery.datepick.js');
Yii::app()->clientScript->registerScript(
        'datepick', '$(".datepick").datepick({
            firstDay: 1,
            multiSelect: 999,
//            rangeSelect: true,
            monthsToShow: 3, 
            monthsToStep: 3,
            minDate: "1",
            prevText: "' . Yii::t('booking', 'Назад') . '", 
            nextText: "' . Yii::t('booking', 'Вперед') . '"
        });'
);
?>
<div class="alert alert-info">
    <?php echo Yii::t('booking', 'Поля, отмеченные'); ?>
    <span class="required">*</span>
    <?php echo Yii::t('booking', 'обязательны.'); ?>
</div>

<?php echo $form->errorSummary($model); ?>

<?php if (count($languages) > 1): ?>
    <?php echo $form->dropDownListRow($model, 'lang', $languages, array('class' => 'popover-help', 'empty' => Yii::t('booking', '--выберите--'))); ?>
    <?php if (!$model->isNewRecord): ?>
        <?php foreach ($languages as $k => $v): ?>
            <?php if ($k !== $model->lang): ?>
                <?php if (empty($langModels[$k])): ?>
                    <a href="<?php echo $this->createUrl('/booking/booking/create', array('id' => $model->id, 'lang' => $k)); ?>"><i class="iconflags iconflags-<?php echo $k; ?>" title="<?php echo Yii::t('booking', 'Добавить перевод на {lang} язык', array('{lang}' => $v)) ?>"></i></a>
                <?php else: ?>
                    <a href="<?php echo $this->createUrl('/booking/booking/update', array('id' => $langModels[$k])); ?>"><i class="iconflags iconflags-<?php echo $k; ?>" title="<?php echo Yii::t('booking', 'Редактировать перевод на {lang} язык', array('{lang}' => $v)) ?>"></i></a>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
<?php else: ?>
    <?php echo $form->hiddenField($model, 'lang'); ?>
<?php endif; ?>

<div class="row-fluid control-group <?php echo $model->hasErrors('is_default') ? 'error' : ''; ?>">
    <?php echo $form->checkBoxRow($model, 'is_default', $model->getDefaultStatusList()); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('booking_id') ? 'error' : ''; ?>">
    <?php echo $form->dropDownListRow($model, 'booking_id', CHtml::listData($this->module->getBookingList(), 'id', 'title'), array('class' => 'span7 popover-help', 'empty' => Yii::t('booking', '--выберите--'))); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('title') ? 'error' : ''; ?>">
    <?php echo $form->textFieldRow($model, 'title', array('size' => 60, 'maxlength' => 255, 'class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('title'), 'data-content' => $model->getAttributeDescription('title'))); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('alias') ? 'error' : ''; ?>">
    <?php echo $form->textFieldRow($model, 'alias', array('size' => 60, 'maxlength' => 150, 'class' => 'span7 popover-help', 'data-original-title' => $model->getAttributeLabel('alias'), 'data-content' => $model->getAttributeDescription('alias'))); ?>
</div>

<?php echo $form->hiddenField($model, 'date'); ?>

<div class="row-fluid control-group">
    <div class="span3 popover-help <?php echo $model->hasErrors('creation_date') ? 'error' : ''; ?>" data-original-title='<?php echo $model->getAttributeLabel('creation_date'); ?>' data-content='<?php echo $model->getAttributeDescription('creation_date'); ?>'>
        <?php
        echo $form->datepickerRow(
                $model, 'creation_date', array(
            'prepend' => '<i class="icon-calendar"></i>',
            'options' => array(
                'format' => 'dd.mm.yyyy',
                'weekStart' => 1,
                'autoclose' => true,
            ),
            'class' => 'span11'
                )
        );
        ?>
    </div>

    <div class="span3 popover-help <?php echo $model->hasErrors('change_date') ? 'error' : ''; ?>" data-original-title='<?php echo $model->getAttributeLabel('change_date'); ?>' data-content='<?php echo $model->getAttributeDescription('change_date'); ?>'>
        <?php
        echo $form->datepickerRow(
                $model, 'change_date', array(
            'prepend' => '<i class="icon-calendar"></i>',
            'options' => array(
                'format' => 'dd.mm.yyyy',
                'weekStart' => 1,
                'autoclose' => true,
            ),
            'class' => 'span11'
                )
        );
        ?>
    </div>
</div>
<br/>
<div class="dates">
    <?php if (empty($model->dates)) : ?>
        <?php
        $dates = new BookingVariantsDates;
        $today = date("d.m.Y");
        $dates->event_date = $today . ' - ' . $today;
        $i = 0;
        ?>
        <div class="row-fluid control-group">
            <div class="span3 popover-help <?php echo $dates->hasErrors('event_date') ? 'error' : ''; ?>" data-original-title='<?php echo $dates->getAttributeLabel('event_date'); ?>' data-content='<?php echo $dates->getAttributeDescription('event_date'); ?>'>
                <?php
                echo $form->dateRangeRow(
                        $dates, 'event_date', array(
                    'prepend' => '<i class="icon-calendar"></i>',
                    'options' => array(
                        'format' => 'DD.MM.YYYY',
                        'weekStart' => 1,
                        'minDate' => date("d.m.Y"),
                        'locale' => array(
                            'applyLabel' => Yii::t('booking', 'Добавить'),
                            'cancelLabel' => Yii::t('booking', 'Отмена'),
                            'fromLabel' => Yii::t('booking', 'С'),
                            'toLabel' => Yii::t('booking', 'По'),
                            'weekLabel' => Yii::t('booking', 'Н'),
                            'customRangeLabel' => Yii::t('booking', 'Свой период'),
                            'firstDay' => 1,
                        )
                    ),
                    'class' => 'span11',
                    'id' => 'BookingVariantsDates_event_date_' . $i,
                    'name' => 'BookingVariantsDates[' . $i . '][event_date]',
                    'labelOptions' => array('for' => 'BookingVariantsDates_event_date_' . $i)
                        )
                );
                ?>
                <?php echo $form->hiddenField($dates, 'variant_id'); ?>
                <div class="clearfix">
                    <a href="#" class="btn btn-primary" role="button" onClick="addDates();return false;"><i class="icon-plus"></i> <?php echo Yii::t('booking', 'Добавить еще одну дату заказа'); ?></a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script type="text/javascript">
    function addDates() 
    {
        var total_dates = jQuery('.dates').find('.alert').length;
        var v = jQuery('#BookingVariantsDates_event_date_0').val();
        if(v != '')
        jQuery('.dates').find('.row-fluid').before('<div class="alert alert-info">' + 
            '<button type="button" class="close" data-dismiss="alert">&times;</button>' + 
            '<div>' + v + '</div>' + 
            '<input name="BookingVariantsDates[' + (parseInt(total_dates) + 1) + '][event_date]" id="BookingVariantsDates_event_date_' + (parseInt(total_dates) + 1) + '" type="hidden" value="' + v + '">' + 
            '<input name="BookingVariantsDates[' + (parseInt(total_dates) + 1) + '][variant_id]" id="BookingVariantsDates_variant_id_' + (parseInt(total_dates) + 1) + '" type="hidden" value="' + <?php echo $model->id ?> + '">' + 
            '</div>'
        );
        jQuery('#BookingVariantsDates_event_date_0').val('')
    }
</script>

<div class="row-fluid control-group">

    <div class="span3 popover-help <?php echo $model->hasErrors('start_date') ? 'error' : ''; ?>" data-original-title='<?php echo $model->getAttributeLabel('start_date'); ?>' data-content='<?php echo $model->getAttributeDescription('start_date'); ?>'>
        <?php
        echo $form->datepickerRow(
                $model, 'start_date', array(
            'prepend' => '<i class="icon-calendar"></i>',
            'options' => array(
                'format' => 'dd.mm.yyyy',
                'weekStart' => 1,
                'autoclose' => true,
            ),
            'class' => 'span11'
                )
        );
        ?>
    </div>

    <div class="span3">
        <div class="row-fluid control-group <?php echo $model->hasErrors('booking_id') ? 'error' : ''; ?>">
            <?php echo $form->dropDownListRow($model, 'deadline', $model->getDeadlineList(), array('class' => 'span7 popover-help', 'empty' => Yii::t('booking', 'до начала мероприятия'))); ?>
        </div>
    </div>

    <?php /*
      <div class="span3 popover-help <?php echo $model->hasErrors('last_buy_date') ? 'error' : ''; ?>" data-original-title='<?php echo $model->getAttributeLabel('last_buy_date'); ?>' data-content='<?php echo $model->getAttributeDescription('last_buy_date'); ?>'>
      <?php
      echo $form->datepickerRow(
      $model, 'last_buy_date', array(
      'prepend' => '<i class="icon-calendar"></i>',
      'options' => array(
      'format' => 'dd.mm.yyyy',
      'weekStart' => 1,
      'autoclose' => true,
      ),
      'class' => 'span11'
      )
      );
      ?>
      </div>

      <div class="span3 popover-help <?php echo $model->hasErrors('finish_date') ? 'error' : ''; ?>" data-original-title='<?php echo $model->getAttributeLabel('finish_date'); ?>' data-content='<?php echo $model->getAttributeDescription('finish_date'); ?>'>
      <?php
      echo $form->datepickerRow(
      $model, 'finish_date', array(
      'prepend' => '<i class="icon-calendar"></i>',
      'options' => array(
      'format' => 'dd.mm.yyyy',
      'weekStart' => 1,
      'autoclose' => true,
      ),
      'class' => 'span11'
      )
      );
      ?>
      </div>
     */ ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('no_finish_date') ? 'error' : ''; ?>">
    <div class="span6"></div>
    <div class="span4"><?php echo $form->checkBoxRow($model, 'no_finish_date', $model->getProtectedStatusList()); ?></div>
</div>
<?php /*
  <?php echo $model->today; ?>
  <div class="row-fluid">
  <?php echo $form->labelEx($model, 'today'); ?>
  <div class="datepick"></div>
  </div>
 */ ?>
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
        <span class="help-block"><?php echo Yii::t('booking', "Полный текст компании отображается при переходе по ссылке &laquo;Подробнее&raquo; или иногда при клике на заголовке компании."); ?></span>
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
        <span class="help-block"><?php echo Yii::t('booking', "Опишите основную мысль компании или напишие некий вводный текст (анонс), пары предложений обычно достаточно. Данный текст используется при выводе списка компаний, например, на главной странице."); ?></span>
        <?php echo $form->error($model, 'short_text'); ?>
    </div>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('is_protected') ? 'error' : ''; ?>">
    <?php echo $form->checkBoxRow($model, 'is_protected', $model->getProtectedStatusList()); ?>
</div>

<div class="row-fluid control-group <?php echo $model->hasErrors('status') ? 'error' : ''; ?>">
    <?php echo $form->dropDownListRow($model, 'status', $model->getStatusList(), array('class' => 'span7 popover-help', 'options' => ($model->isNewRecord ? array(Booking::STATUS_PUBLISHED => array('selected' => true)) : ''))); ?>
</div>

<?php if (!$model->isNewRecord) : ?>
    <div class="alert drop-alert" style="opacity: 0;" ><a class="close" data-dismiss="alert">×</a><b>&nbsp;</b></div>
    <div class="row-fluid">
        <div class="droppable-wrap span10">
            <div style="width: <?php echo $image_params->width ?>px;height: <?php echo $image_params->height ?>px;background: url(<?php echo $model->imageUrl ?>) no-repeat 0 0;position: relative;" class="droppable-image" >
                <?php
                $this->beginWidget('zii.widgets.jui.CJuiDroppable', array(
                    'tagName' => 'ul',
                    'options' => array(
                        'drop' => 'js:function(event,ui){

                    var newPosX = ui.offset.left - $(this).offset().left;
                    var newPosY = ui.offset.top - $(this).offset().top;
                    var itemsCount = $(this).find("li").length;
                    if(ui.draggable.hasClass(\'cloned\')){
                        var cloned_item_id = parseInt(ui.draggable.attr("data-id"));
                        jQuery.ajax({
                            \'url\' : \'/booking/bookingitemsposition/update/\' + cloned_item_id,
                            \'type\' : "POST",
                            \'data\' : {"BookingItemsPosition[pos_x]": newPosX, "BookingItemsPosition[pos_y]": newPosY, "' . Yii::app()->request->csrfTokenName . '": "' . Yii::app()->request->csrfToken . '"},
                            \'success\' : function(r) {
                                    jQuery(".drop-alert").addClass("alert-success");
                                    jQuery(".drop-alert").removeClass("alert-error");
                                    jQuery(".drop-alert b").html(r);
                                    jQuery(".drop-alert").animate({opacity: 1}).delay(4000).animate({opacity: 0});
                            },
                            \'error\' : function(r) {
                                    jQuery(".drop-alert").addClass("alert-error");
                                    jQuery(".drop-alert").removeClass("alert-success");
                                    jQuery(".drop-alert b").show().html(r);
                            }
                        });
                        $("#BookingItemsPosition_pos_x_" + cloned_item_id).val(newPosX);
                        $("#BookingItemsPosition_pos_y_" + cloned_item_id).val(newPosY);
                    } else {
                        var item_id = String(ui.draggable.attr("id"));
                        var item_id_num = parseInt(item_id.replace(/[^0-9]+/g, \'\'));
                        var cloned_item = ui.draggable.clone();
                        var cloned_item_title = cloned_item.find(".draggable-item-title").text();
                        cloned_item.addClass(\'cloned\');
                        cloned_item.attr("id", "booking-items-position-" + itemsCount);
                        cloned_item.css({\'left\': newPosX + \'px\',\'top\': newPosY + \'px\', \'position\' : \'absolute\'});
                        cloned_item.find(".item-texts").prepend(\'<div class="draggable-item-number"><a href="#items_modal_\' + (itemsCount + 1) + \'" role="button" class="btn btn-mini" data-toggle="modal">\' + (itemsCount + 1) + \'</a></div>\');
                        $(this).append( cloned_item.draggable({containment: "parent"}) );
                        jQuery.ajax({
                            \'url\' : \'/booking/bookingitemsposition/create\',
                            \'type\' : "POST",
                            \'data\' : {"BookingItemsPosition[item_id]": item_id_num, "BookingItemsPosition[booking_id]": ' . $model->booking_id . ', "BookingItemsPosition[variant_id]": ' . $model->id . ', "BookingItemsPosition[pos_x]": newPosX, "BookingItemsPosition[pos_y]": newPosY, "BookingItemsPosition[date]": "", "BookingItemsPosition[status]": 1, "' . Yii::app()->request->csrfTokenName . '": "' . Yii::app()->request->csrfToken . '"},
                            \'success\' : function(r) {
                                    jQuery("#items_modal_empty").clone().appendTo(".items-positioning-modals").attr("id", "items_modal_" + (itemsCount + 1));
                                    jQuery("#items_modal_" + (itemsCount + 1)).modal().find(".modal-body").html(r);
                                    jQuery("#items_modal_" + (itemsCount + 1)).find(".modal-header h4").html((itemsCount + 1));
                                    $(".redactor").redactor();
                            },
                            \'error\' : function(r) {printObject(r)}
                        });
                    }
                    
                }',
                    ),
                    'htmlOptions' => array(
                        'class' => 'droppable-list',
                    ),
                ));
                if (!empty($positioning_items)) {
                    $this->renderPartial('_items_positioning', array(
                        'booking' => $model,
                        'positioning_items' => $positioning_items,
                    ));
                }
                $this->endWidget();
                ?>
            </div>
        </div>
        <div class="draggable-wrap span2">
            <div class="well" style="min-height: <?php echo $image_params->height - 34 ?>px;">
                <div class="row-fluid control-group ">
                    <label class="checkbox" for="disable_draggable">
                        <input type="checkbox" name="disable_draggable" id="disable_draggable" />
                        <?php echo Yii::t('booking', 'отключить перетаскивание') ?>
                    </label>
                </div>
                <ul class="draggable-list clearfix">

                    <?php
                    $this->renderPartial('_items_template', array(
                        'booking' => $model,
                        'items' => $items,
                    ));
                    ?>

                </ul>
            </div>
        </div>

    </div>


<?php endif; ?>
<br/>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'label' => $model->isNewRecord ? Yii::t('booking', 'Добавить конфигурацию и продолжить') : Yii::t('booking', 'Сохранить конфигурацию и продолжить'),
));
?>
<?php if (!$model->isNewRecord) : ?>
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'htmlOptions' => array('name' => 'submit-type', 'value' => 'index'),
        'label' => $model->isNewRecord ? Yii::t('booking', 'Добавить конфигурацию и закрыть') : Yii::t('booking', 'Сохранить конфигурацию и закрыть'),
    ));
    ?>
<?php endif; ?>
<?php $this->endWidget(); ?>
<?php if (!$model->isNewRecord) : ?>
    <div class="items-positioning-modals">
        <?php
        $this->renderPartial('_items_positioning_modal', array(
            'booking' => $model,
            'positioning_items' => $positioning_items,
        ));
        ?>

        <?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'items_modal_empty', 'htmlOptions' => array('style' => 'width:800px;margin-left: -400px;display: none;'))); ?>
        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <h4></h4>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>
        <?php $this->endWidget(); ?>

    </div>
<?php endif; ?>
<div style="display: none">
    <?php
    $this->widget('bootstrap.widgets.TbColorPicker', array(
        'model' => $model,
        'attribute' => 'colorpicker',
    ));
    ?>
</div>
