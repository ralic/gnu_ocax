<?php
/* @var $this BudgetController */
/* @var $model Budget */
Yii::app()->clientScript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
?>

<?php $parent_budget=$model->findByPk($model->parent);?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'title'=>'Update budget','parent_budget'=>$parent_budget)); ?>
