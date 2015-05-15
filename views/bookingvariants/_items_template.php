<?php if (!empty($items)) : ?>
    <?php
    $i = 0;
    $img = '';
    ?>
    <?php foreach ($items as $item): ?>
        <?php $img = empty($item->image) ? '' : '<img src="' . $item->imageUrl . '" alt="" />' ?>
        <?php
        $width = $item->width . 'px';
        $height = $item->lenght . 'px';
        $bg = empty($item->color) ? $bg : 'background-color: ' . $item->color;
        $border = empty($item->border_color) ? $border : 'border: 1px solid ' . $item->border_color;
        $border = empty($border) ? 'border: 1px solid #e3e3e3' : $border;
        $transparent = empty($item->transparent) ? $transparent : 'opacity: ' . ((int) $item->transparent / 10) . ';filter:alpha(opacity=' . ((int) $item->transparent * 10) . ');';
        $border_radius = empty($item->border_radius) ? $border_radius : 'border-radius: ' . $item->border_radius . ';-moz-border-radius: ' . $item->border_radius . ';-webkit-border-radius: ' . $item->border_radius . ';-khtml-border-radius: ' . $item->border_radius . ';';
        ?>
        <?php
        $this->beginWidget('zii.widgets.jui.CJuiDraggable', array(
            'tagName' => 'li',
            'options' => array(
                'cursor' => 'move',
                'helper' => 'clone',
            ),
            'htmlOptions' => array(
                'style' => 'width: ' . $width . ';height: ' . $height . ';position: relative;' . $bg . ';' . $border . ';' . $transparent . $border_radius,
                'class' => 'draggable-item' . ($item->booking_items->hide_number ? ' hide-number' : ''),
                'id' => 'booking-items-position-' . $i,
            )
        ));
        ?>
        <div class="item-texts" style="display: none;">
            <span class="draggable-item-title"><?php echo $item->title; ?></span>
        </div>
        <div class="item-image-bg"><?php echo $img ?></div>
        <?php $i++; ?>
        <?php $this->endWidget(); ?>
    <?php endforeach; ?>
<?php endif; ?>