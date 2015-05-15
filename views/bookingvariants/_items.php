<?php if (!empty($items)) : ?>
    <?php
    $i = 0;
    ?>
    <?php foreach ($items as $item): ?>

        <?php
        $this->beginWidget('zii.widgets.jui.CJuiDraggable', array(
            'tagName' => 'li',
            'options' => array(
                'cursor' => 'move',
                'helper' => 'clone',
            ),
            'htmlOptions' => array(
                'style' => 'width: ' . $item->width . 'px;height: ' . $item->lenght . 'px;',
                'class' => 'draggable-item well well-small',
                'id' => 'draggable-item-' . $item->id,
            )
        ));
        ?>
        <div>
            <span class="draggable-item-title"><?php echo $item->title; ?></span>
        </div>
        <?php $i++; ?>
        <?php $this->endWidget(); ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php unset($item); ?>
<?php if ($positioning_items) : ?>
    <?php
    $i = 0;
    $y = 0;
    $boofer = 0;
    ?>
    <?php foreach ($positioning_items as $item): ?>
        <?php
        if ($boofer == $item->item_id && $i > 0) {
            $y++;
        } else {
            $boofer = $item->item_id;
        }

        $this->beginWidget('zii.widgets.jui.CJuiDraggable', array(
            'tagName' => 'li',
            'options' => array(
                'cursor' => 'move',
                'containment' => 'parent',
            ),
            'htmlOptions' => array(
                'style' => 'width: ' . (empty($item->width) ? $item->booking_items->width : $item->width) . 'px;height: ' . (empty($item->height) ? $item->booking_items->lenght : $item->height) . 'px;left: ' . $item->pos_x . 'px;top: ' . $item->pos_y . 'px;position: absolute;',
                'class' => 'draggable-item well well-small cloned',
                'id' => 'booking-items-position-' . $i,
            )
        ));
        ?>
        <div>
            <div class="draggable-item-number"><a href="#items_modal_<?php echo $item->id ?>" role="button" class="btn btn-mini" data-toggle="modal"><?php echo $i + 1; ?></a></div>
            <span class="draggable-item-title"><?php echo empty($item->title) ? $item->booking_items->title : $item->title; ?></span>
        </div>

        <?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'items_modal_' . $item->id, 'htmlOptions' => array('style' => 'width:800px;margin-left: -400px;display: none;'))); ?>
        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <h4><?php echo $i + 1; ?></h4>
        </div>
        <div class="modal-body">
            <?php
            $this->renderPartial('/bookingitemsposition/_form_ajax', array(
                'model' => $item,
            ));
            ?>
        </div>
        <div class="modal-footer">

        </div>
        <?php $this->endWidget(); ?>

        <input type="hidden" name="BookingVariants[items][<?php echo $item->item_id; ?>][<?php echo $y; ?>][id]" id="input-id-booking-items-position-<?php echo $i; ?>" value="<?php echo $item->id; ?>">
        <input type="hidden" name="BookingVariants[items][<?php echo $item->item_id; ?>][<?php echo $y; ?>][pos_x]" id="input-x-booking-items-position-<?php echo $i; ?>" value="<?php echo $item->pos_x; ?>">
        <input type="hidden" name="BookingVariants[items][<?php echo $item->item_id; ?>][<?php echo $y; ?>][pos_y]" id="input-y-booking-items-position-<?php echo $i; ?>" value="<?php echo $item->pos_y; ?>">
        <input type="hidden" name="BookingVariants[items][<?php echo $item->item_id; ?>][<?php echo $y; ?>][width]" id="input-width-booking-items-position-<?php echo $i; ?>" value="<?php echo $item->width; ?>">
        <input type="hidden" name="BookingVariants[items][<?php echo $item->item_id; ?>][<?php echo $y; ?>][height]" id="input-height-booking-items-position-<?php echo $i; ?>" value="<?php echo $item->height; ?>">        
        <input type="hidden" name="BookingVariants[items][<?php echo $item->item_id; ?>][<?php echo $y; ?>][title]" id="input-title-booking-items-position-<?php echo $i; ?>" value="<?php echo $item->title; ?>">
        <input type="hidden" name="BookingVariants[items][<?php echo $item->item_id; ?>][<?php echo $y; ?>][full_text]" id="input-full_text-booking-items-position-<?php echo $i; ?>" value="<?php echo $item->full_text; ?>">
        <input type="hidden" name="BookingVariants[items][<?php echo $item->item_id; ?>][<?php echo $y; ?>][image]" id="input-image-booking-items-position-<?php echo $i; ?>" value="<?php echo $item->image; ?>">
        <input type="hidden" name="BookingVariants[items][<?php echo $item->item_id; ?>][<?php echo $y; ?>][status]" id="input-status-booking-items-position-<?php echo $i; ?>" value="<?php echo $item->status; ?>">
        <input type="hidden" name="BookingVariants[items][<?php echo $item->item_id; ?>][<?php echo $y; ?>][is_protected]" id="input-is_protected-booking-items-position-<?php echo $i; ?>" value="<?php echo $item->is_protected; ?>">
        <?php $this->endWidget(); ?>
        <?php $i++; ?>

    <?php endforeach; ?>
<?php endif; ?>
<?php /*
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('li.cloned').live('click', function(e) {
            if (this == e.target) {
                var number = jQuery(this).find('.draggable-item-number').text();

                var id = jQuery(this).attr('id');
                var id_num = id.substr(id.length - 1);
                var id_db = jQuery('#input-id-' + id).val();
                var pos_x = jQuery('#input-x-' + id).val();
                var pos_y = jQuery('#input-y-' + id).val();
                var title = jQuery('#input-title-' + id).val();
                var full_text = jQuery('#input-full_text-' + id).val();
                var image = jQuery('#input-image-' + id).val();
                var status = jQuery('#input-status-' + id).val();
                var is_protected = jQuery('#input-is_protected-' + id).val();
                var imageUrl = '<?php echo Yii::app()->baseUrl . '/' . Yii::app()->getModule('yupe')->uploadPath . '/' . Yii::app()->getModule('booking')->uploadPath . '/' ?>';
                jQuery('#items_modal .modal-header').find('h4').text(number);
                jQuery('#items_modal .modal-body').html(
                        '<form method="post" enctype="multipart/form-data" action="/booking/bookingitemsposition/updateajax/' + id_db + '" class="form-vertical" id="booking-items-position-form" >' +
                        '<div style="display:none"><input type="hidden" name="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken ?>"></div>' +
                        '<div class="row-fluid control-group "><label for="BookingItemsPosition_title"><?php echo Yii::t('BookingModule.booking', 'Заголовок'); ?></label><input size="60" maxlength="255" class="span10" name="BookingItemsPosition[title]" id="BookingItemsPosition_title" type="text" value="' + title + '"></div>' +
                        '<div class="row-fluid control-group ">' + (image ? '<img width="300" height="300" src="' + imageUrl + image + '" alt="' + title + '">' : '') + '<label for="BookingItemsPosition_image">Изображение</label><input id="ytBookingItemsPosition_image" type="hidden" value="' + image + '" name="BookingItemsPosition[image]"><input name="BookingItemsPosition[image]" id="BookingItemsPosition_image" type="file"></div>' +
                        '<div class="row-fluid control-group "><label for="BookingItemsPosition_full_text"><?php echo Yii::t('BookingModule.booking', 'Описание'); ?></label><textarea class="redactor" name="BookingItemsPosition[full_text]" id="BookingItemsPosition_full_text" >' + full_text + '</textarea></div>' +
                        '<input type="hidden" name="BookingItemsPosition[pos_x]" id="BookingItemsPosition_pos_x" value="' + pos_x + '" />' +
                        '<input type="hidden" name="BookingItemsPosition[pos_y]" id="BookingItemsPosition_pos_x" value="' + pos_y + '" />' +
                        '</form>'
                        );
                $('#items_modal').modal().css({
                    width: '800px',
                    'margin-left': function() {
                        return -($(this).width() / 2);
                    },
                });
                $('.redactor').redactor();
            }
        });
    });
</script>
*/ ?>