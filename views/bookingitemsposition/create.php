<?php
/* @var $this BookingItemsPositionController */
/* @var $model BookingItemsPosition */

$this->breadcrumbs=array(
	'Booking Items Positions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List BookingItemsPosition', 'url'=>array('index')),
	array('label'=>'Manage BookingItemsPosition', 'url'=>array('admin')),
);
?>

<h1>Create BookingItemsPosition</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>