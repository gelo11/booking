<?php
/* @var $this BookingItemsPositionController */
/* @var $model BookingItemsPosition */

$this->breadcrumbs=array(
	'Booking Items Positions'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List BookingItemsPosition', 'url'=>array('index')),
	array('label'=>'Create BookingItemsPosition', 'url'=>array('create')),
	array('label'=>'Update BookingItemsPosition', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete BookingItemsPosition', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BookingItemsPosition', 'url'=>array('admin')),
);
?>

<h1>View BookingItemsPosition #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'item_id',
		'booking_id',
		'variant_id',
		'pos_x',
		'pos_y',
		'title',
		'full_text',
		'image',
		'width',
		'height',
		'date',
		'user_id',
		'status',
		'is_protected',
	),
)); ?>
