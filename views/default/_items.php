<?php if(!empty($items)) : ?>
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
    <?php $this->endWidget(); ?>
<?php endforeach; ?>
<?php endif; ?>

<?php if(!empty($booking_items)) : ?>
<?php 
$i = 0; 
$y = 0;
$boofer = 0;
?>
<?php foreach ($booking_items as $item): ?>
    <?php
    if($boofer == $item->item_id && $i > 0) {
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
            'style' => 'width: ' . $item->booking_items->width . 'px;height: ' . $item->booking_items->lenght . 'px;left: ' . $item->pos_x . 'px;top: ' . $item->pos_y . 'px;position: relative;',
            'class' => 'draggable-item well well-small cloned',
            'id' => 'booking-items-position-' . $i,
        )
    ));
    ?>
    <div>
        <span class="draggable-item-title"><?php echo $item->booking_items->title; ?></span>
    </div>
    <input type="hidden" name="BookingItemsPosition[<?php echo $item->item_id; ?>][<?php echo $y; ?>][pos_x]" id="input-x-booking-items-position-<?php echo $y; ?>" value="<?php echo $item->pos_x; ?>">
    <input type="hidden" name="BookingItemsPosition[<?php echo $item->item_id; ?>][<?php echo $y; ?>][pos_y]" id="input-y-booking-items-position-<?php echo $y; ?>" value="<?php echo $item->pos_y; ?>">
    <?php $this->endWidget(); ?>
<?php $i++; ?>

<?php endforeach; ?>
<?php endif; ?>
    