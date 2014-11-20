<?php

/**
 * OCAX -- Citizen driven Observatory software
 * Copyright (C) 2014 OCAX Contributors. See AUTHORS.

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

if(Yii::app()->request->isAjaxRequest){
	Yii::app()->clientScript->scriptMap['jquery.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
}


if(Yii::app()->user->isTeamMember() || Yii::app()->user->isManager())
	$enquiry_count = count($model->enquirys);
else{
	if($user_id=Yii::app()->user->getUserID()){
		$criteria = new CDbCriteria;
		$criteria->condition = 'budget = '.$model->id.' AND user = "'.$user_id.'"';
		$enquiry_count = count(Enquiry::model()->findAll($criteria));		
	
		$criteria = new CDbCriteria;
		$criteria->condition = 'budget = '.$model->id.' AND state >= '.ENQUIRY_ACCEPTED.' AND NOT user = "'.$user_id.'"';
		$enquiry_count = $enquiry_count + count(Enquiry::model()->findAll($criteria));
	}else{
		$criteria = new CDbCriteria;
		$criteria->condition = 'budget = '.$model->id.' AND state >= '.ENQUIRY_ACCEPTED;
		$enquiry_count = count(Enquiry::model()->findAll($criteria));
	}
}

if(isset($showLinks)){
	$budgetModal = array('onclick'=>'js:showBudget('.$model->id.', this);return false;');
	$create_enquiry_link = '';
	if(isset($showCreateEnquiry)){
	$create_enquiry_link = 	'<span style="float:right">'.
							CHtml::link(__('New enquiry'), $this->createAbsoluteUrl('enquiry/create?budget='.$model->id)).
							'</span>';
	}elseif(!isset($showMore)){
		$create_enquiry_link = 	'<span style="float:right">'.
								'<a href="'.$this->createAbsoluteUrl('budget/view/'.$model->id).'" onClick="js:showBudget('.$model->id.', this);return false;">'.__('More detail').'</a>';
								'</span>';
	}
	if($enquiry_count){
		if($enquiry_count == 1){
			if((isset($enquiry) && $enquiry->budget == $model->id) || isset($showMore))
				$enquiries = __('1 enquiry made').' '.$create_enquiry_link;
			else
				$enquiries = CHtml::link(__('1 enquiry made'), array('budget/view','id'=>$model->id), $budgetModal).' '.$create_enquiry_link;
		}else{
			if(!isset($showMore))
				$enquiries = CHtml::link($enquiry_count.' '.__('enquiries made'), array('budget/view','id'=>$model->id), $budgetModal).' '.$create_enquiry_link;
			else
				$enquiries = $enquiry_count.' '.__('enquiries made').' '.$create_enquiry_link;
		}
	}else
		$enquiries = '0 '.__('enquiries made').' '.$create_enquiry_link;

	$budget_concept= CHtml::link($model->getConcept(), $this->createAbsoluteUrl('budget/view',array('id'=>$model->id)), $budgetModal);
}else{
	if($enquiry_count){
		if($enquiry_count == 1)
			$enquiries = __('1 enquiry made');
		else
			$enquiries = $enquiry_count.' '.__('enquiries made');
	}else
		$enquiries = '0 '.__('enquiries made');
	$budget_concept = $model->getConcept();
}

$percentage = '<span style="float:right">'.$model->getPercentage().'% '.__('of total').'</span>';

$yearCode_value = ($model->code)? $model->getYearString().' / '.$model->code : $model->getYearString();
if(isset($showLinks) && isset($showMore) && $model->budgets){
		$htmlOptions = array('style'=>'float:right;');
		if(Yii::app()->request->isAjaxRequest)
			$htmlOptions['target'] = '_blank';
		$link = CHtml::link(' <i class="icon-chart-pie color"></i>'.__('Graph'),
							array(	'budget/graph',
									'id'=>$model->id
							),
							$htmlOptions
						);
		$yearCode_value = $yearCode_value.$link;
}
	
$attributes=array(
		array(
	        'label'=>($model->code)? __('Year / Code'):__('Year'),
	        'type'=>'raw',
	        'value'=>$yearCode_value,
		),
		array('name'=>'actual_provision', 'type'=>'raw', 'value'=>format_number($model->actual_provision).' '.$percentage),
		array(
	        'label'=>__('Euros per person'),
	        'value'=>format_number($model->actual_provision / $model->getPopulation()),
		),

	);

if(!isset($hideConcept)){
	$label=$model->getLabel();
	//if(isset($showLinks))
	//	$label .=' <img style="margin-top:1px;margin-right:-13px;float:right;" src="'.Yii::app()->getBaseUrl(true).'/images/info_small.png" />';
	$row =	array(
				array(
					'label'=>$label,
					'type'=>'raw',
					'value'=> $budget_concept,
				),	
			);
	array_splice( $attributes, 0, 0, $row );
}
if(!isset($showMore)){
	$row =	array(
	       		'label'=>__('Enquiries'),
				'type'=>'raw',
				'value'=>$enquiries,
			);
	$attributes[]=$row;
}

$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>$attributes,
));

if(isset($showMore)){
	$attributes=array(
				array('name'=>'initial_provision', 'type'=>'raw', 'value'=>format_number($model->initial_provision)),
			);
	if($model->getExecuted()){
		$attributes[]=array('name'=>'trimester_1', 'type'=>'raw', 'value'=>format_number($model->trimester_1));
		$attributes[]=array('name'=>'trimester_2', 'type'=>'raw', 'value'=>format_number($model->trimester_2));
		$attributes[]=array('name'=>'trimester_3', 'type'=>'raw', 'value'=>format_number($model->trimester_3));
		$attributes[]=array('name'=>'trimester_4', 'type'=>'raw', 'value'=>format_number($model->trimester_4));
	}else{
		$attributes[]=array(
						'label'=>__('Executed'),
						'type'=>'raw',
						'value'=>__('Unknown'),
					);
	}
	$attributes[]=array(
	       			'label'=>__('Enquiries'),
					'type'=>'raw',
					'value'=>$enquiries,
				);
	
	$this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>$attributes,
	));
}
?>



