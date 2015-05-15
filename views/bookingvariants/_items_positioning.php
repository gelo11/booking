<?php
$i = 0;
?>
<?php foreach ($positioning_items as $item): ?>
<?php 
$width = (empty($item->width) ? $item->booking_items->width : $item->width) . 'px';
$height = (empty($item->height) ? $item->booking_items->lenght : $item->height) . 'px';
$left = $item->pos_x . 'px';
$top = $item->pos_y . 'px';
$bg = empty($item->booking_items->color) ? '' : 'background-color: ' . $item->booking_items->color;
$bg = empty($item->color) ? $bg : 'background-color: ' . $item->color;
$bg = empty($bg) ? 'background-color: #f5f5f5' : $bg;
$border = empty($item->booking_items->border_color) ? '' : 'border: 1px solid ' . $item->booking_items->border_color;
$border = empty($item->border_color) ? $border : 'border: 1px solid ' . $item->border_color;
$border = empty($border) ? 'border: 1px solid #e3e3e3' : $border;
$transparent = empty($item->booking_items->transparent) ? '' : 'opacity: ' . ((int) $item->booking_items->transparent / 10) . ';filter:alpha(opacity=' . ((int) $item->booking_items->transparent * 10) . ');';
$transparent = empty($item->transparent) ? $transparent : 'opacity: ' . ((int) $item->transparent / 10) . ';filter:alpha(opacity=' . ((int) $item->transparent * 10) . ');';
$transparent = empty($transparent) ? 'opacity: 0.9;filter:alpha(opacity=90);' : $transparent;
$border_radius = empty($item->booking_items->border_radius) ? '' : 'border-radius: ' . $item->booking_items->border_radius . ';-moz-border-radius: ' . $item->booking_items->border_radius . ';-webkit-border-radius: ' . $item->booking_items->border_radius . ';-khtml-border-radius: ' . $item->booking_items->border_radius . ';';
$border_radius = empty($item->border_radius) ? $border_radius : 'border-radius: ' . $item->border_radius . ';-moz-border-radius: ' . $item->border_radius . ';-webkit-border-radius: ' . $item->border_radius . ';-khtml-border-radius: ' . $item->border_radius . ';';
$border_radius = empty($border_radius) ? 'border-radius: 2px;-moz-border-radius: 2px;-webkit-border-radius: 2px;-khtml-border-radius: 2px;' : $border_radius;
?>
<?php $img = empty($item->image) ? (empty($item->booking_items->image) ? '' : '<img src="' . $item->booking_items->imageUrl . '" alt="" />') : '<img src="' . $item->imageUrl . '" alt="" />' ?>
    <?php
    $this->beginWidget('zii.widgets.jui.CJuiDraggable', array(
        'tagName' => 'li',
        'options' => array(
            'cursor' => 'move',
            'containment' => 'parent',
        ),
        'htmlOptions' => array(
            'style' => 'width:' . $width . ';height:' . $height . ';left:' . $left . ';top:' . $top . ';position:absolute;' . $bg . ';' . $border . ';' . $transparent . $border_radius,
            'class' => 'draggable-item cloned' . ($item->booking_items->hide_number ? ' hide-number' : ''),
            'id' => 'draggable-item-' . $i,
            'data-id' => $item->id,
        )
    ));
    ?>
    <div class="item-texts">
        <div class="draggable-item-number"><a href="#items_modal_<?php echo $i ?>" role="button" class="btn btn-mini" data-toggle="modal"><?php echo $i + 1; ?></a></div>
        <span class="draggable-item-title"><?php echo empty($item->title) ? $item->booking_items->title : $item->title; ?></span>
    </div>

    <div class="item-image-bg"><?php echo $img ?></div>
    <?php $this->endWidget(); ?>
    <?php $i++; ?>

<?php endforeach; ?>
