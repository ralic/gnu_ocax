<?php
/* @var $this CmspageController */
/* @var $model CmsPage */

$this->menu=array(
	array('label'=>'Manage CmsPage', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'title'=>'Create CmsPage')); ?>
