<?php
//Yii::app()->clientScript->scriptMap['jquery.js'] = false;
//Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

$make_enquiry_link=	'<span style="float:right">'.
					CHtml::link(__('make an enquiry'),array('enquiry/create', 'budget'=>$model->id)).
					'</span>';
?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>__('Concept'),
			'type'=>'raw',
			'value'=> isset($showLinks)? CHtml::link($model->concept, array('budget/view','id'=>$model->id)): $model->concept,
		),
		array(
	        'label'=>__('Year'),
	        'value'=>$model->getYearString(),
		),
		'code',
		//array('name'=>'initial_provision', 'type'=>'raw', 'value'=>format_number($model->initial_provision).' €'),
		array('name'=>'actual_provision', 'type'=>'raw', 'value'=>format_number($model->actual_provision).' €'),
		array(
	        'label'=>__('Euros per person'),
	        'value'=>format_number($model->actual_provision / $model->getPopulation()).' €',
		),
		array(
	        'label'=>__('Enquiries'),
			'type'=>'raw',
	        'value'=>count($model->enquirys)?
	        			CHtml::link(count($model->enquirys).' '.__('enquir(ies) already made'), array('budget/view','id'=>$model->id)).
	        			$make_enquiry_link:
	        			__('no enquiries made yet').' '.$make_enquiry_link,
		),		
	),
)); ?>

