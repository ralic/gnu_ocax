<?php
/* @var $this BudgetController */
/* @var $model Budget */

if(Yii::app()->request->isAjaxRequest){
	Yii::app()->clientScript->scriptMap['jquery.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
}

$this->layout='//layouts/column1';

$root_budget = $model->findByAttributes(array('csv_id'=>$model->csv_id[0], 'year'=>$model->year));
if(!$root_budget){
	$this->render('//site/error',array('code'=>'Budget not found', 'message'=>__('Budget with internal code').' "'.$model->csv_id[0].'" '.__('is not defined')));
	Yii::app()->end();
}

$dataProvider=new CActiveDataProvider('Enquiry', array(
	'criteria'=>array(
		'condition'=>'budget = '.$model->id.' AND state >= '.ENQUIRY_ACCEPTED,
		'order'=>'created DESC',
	),
	'pagination'=>array(
		'pageSize'=>20,
	),
));

?>
<script></script>

<?php
	echo '<div style="font-size:1.6em">'.$model->getTitle().'</div>';

	echo '<div>';
		echo '<div class="view" style="width:450px;padding:0px;margin-left:10px;margin-top:-5px;float:right;">';
		echo $this->renderPartial('_enquiryView',array(	'model'=>$model,
														'showCreateEnquiry'=>1,
														'showLinks'=>1,
														'noConcept'=>1,
												),false,true);
		$this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
						array('name'=>'initial_provision', 'type'=>'raw', 'value'=>format_number($model->initial_provision).' €'),
						array('name'=>'trimester_1', 'type'=>'raw', 'value'=>format_number($model->trimester_1).' €'),
						array('name'=>'trimester_2', 'type'=>'raw', 'value'=>format_number($model->trimester_2).' €'),
						array('name'=>'trimester_3', 'type'=>'raw', 'value'=>format_number($model->trimester_3).' €'),
						array('name'=>'trimester_4', 'type'=>'raw', 'value'=>format_number($model->trimester_4).' €'),
					),
		));
		echo '</div>';	
	
	echo '<p  style="margin-top:15px;">';
	if($description = $model->getDescription()){
		echo $description;
	}	
	
	echo '<div style="font-size:1.3em;margin-top:35px;">';
	if(!$dataProvider->getData()){
		echo '<p style="margin-bottom:10px">'.__('No enquiries have been made about this budget yet').'.</p>'.
			CHtml::link(__('Do you wish to make an enquiry').'?' ,array('enquiry/create', 'budget'=>$model->id));
	}
	echo '</div>';
	echo '</p>';
	echo '</div>';

?>
<div style="clear:both"></div>

<?php

if(/*!Yii::app()->request->isAjaxRequest &&*/ count($dataProvider->getData()) > 0){
	echo '<p>';
	if(count($dataProvider->getData()) == 1)
		echo '<div style="font-size:1.3em;margin-top:25px;">'.__('One enquiry has already been made about this budget').'</div>';
	else{
		$str = str_replace("%s", count($dataProvider->getData()), __('%s enquiries have already been made about this budget'));
		echo '<div style="font-size:1.3em;margin-top:25px;">'.$str.'</div>';
	}

	$this->widget('PGridView', array(
		'id'=>'enquiry-grid',
		'dataProvider'=>$dataProvider,
	    'onClick'=>array(
	        'type'=>'url',
	        'call'=>Yii::app()->request->baseUrl.'/enquiry/view',
	    ),
		'template' => '{items}{pager}',
		'ajaxUpdate'=>true,
		'pager'=>array('class'=>'CLinkPager',
						'header'=>'',
						'maxButtonCount'=>6,
						'prevPageLabel'=>'< Prev',
		),
		'columns'=>array(
				array(
					'header'=>__('Enquiry'),
					'name'=>'title',
					'value'=>'$data[\'title\']',
				),
				array(
					'header'=>'Estat',
					'name'=>'state',
					'type' => 'raw',
					'value'=>'$data->getHumanStates($data[\'state\'])',
				),
				'created',
    	        array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
	)));
}

?>
</p>

