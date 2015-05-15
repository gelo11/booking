<?php
$i = 0;
?>
<?php foreach ($positioning_items as $item): ?>
    <?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'items_modal_' . $i, 'htmlOptions' => array('style' => 'width:800px;margin-left: -400px;display: none;'))); ?>
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
    <?php $i++; ?>

<?php endforeach; ?>
