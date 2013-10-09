<?php

/**
 * OCAX -- Citizen driven Municipal Observatory software
 * Copyright (C) 2013 OCAX Contributors. See AUTHORS.

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

/* @var $this EnquiryController */
/* @var $model Enquiry */

if(Yii::app()->request->isAjaxRequest){
	Yii::app()->clientScript->scriptMap['jquery.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.ba-bbq.js'] = false;
}
if(!Yii::app()->request->isAjaxRequest){?>
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<?php } ?>

<style>           
.socialIcons {	margin:0px; }
.socialIcons img { cursor:pointer;  margin-right:10px; }
#directlink span  { cursor:pointer; }
#directlink span:hover { color:black; }
.social_popup {	
	display :none;
	position: absolute;
	padding:7px;
	z-index: 1;
	width: 330px;
	background-color: #98FB98;
}
.clear{clear:both;}	
</style>

<script>
!function(d,s,id){
	var js,fjs=d.getElementsByTagName(s)[0];
	if(!d.getElementById(id)){
		js=d.createElement(s);
		js.id=id;
		js.src="https://platform.twitter.com/widgets.js";
		fjs.parentNode.insertBefore(js,fjs);
	}
}
(document,"script","twitter-wjs");
</script>

<div id="fb-root"></div>
<script>
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>


<script>
function subscribe(el){
	if('1' == '<?php echo Yii::app()->user->isGuest;?>'){
		$(el).attr('checked', false);
		alert('Please login to subscribe');
		$('#subscribe').hide();
		return;
	}
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/enquiry/subscribe',
		type: 'POST',
		dataType: 'json',
		data: { 'enquiry': <?php echo $model->id;?> },
		//beforeSend: function(){ },
		//complete: function(){ },
		success: function(data){
			$('#subscribe').fadeOut();
		},
		error: function() { alert("error on subscribe"); },
	});
}
function clickSocialIcon(el){
	if( $(el).attr('social_icon') ){
		$('#'+$(el).attr('social_icon')).show();
	}
}
$(function() {
	$('.social_popup').mouseleave(function() {
		$('.social_popup').hide();
	});
});

function toggleStatesDiagram(){
	if ( $('#states_diagram').is(':visible') )
		$('#states_diagram').slideUp('fast');
	else{
		$('#states_diagram').slideDown('fast');
	}
}


function showBudget(budget_id, element){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/getBudget/'+budget_id,
		type: 'GET',
		beforeSend: function(){
						$('.loading_gif').remove();
						$(element).after('<img style="vertical-align:middle;" class="loading_gif" src="<?php echo Yii::app()->request->baseUrl;?>/images/loading.gif" />');
					},
		complete: function(){ $('.loading_gif').remove(); },
		success: function(data){
			if(data != 0){
				$("#budget_popup_body").html(data);
				$('#budget_popup').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, fadeSpeed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on show budget");
		}
	});
}
</script>


 

<?php if($reformulatedDataprovider = $model->getReformulatedEnquires()){
$providerData = $reformulatedDataprovider->getData();

echo '<style>.highlight_row{background:#FFDEAD;}</style>';
echo 	'<div style="font-size:1.3em">'.__('The enquiry').' "'.$providerData[0]->title.'" '.__('has been reformulated').
		' '. (count($providerData)-1) .' '.__('time(s)').'</div>';

$this->widget('PGridView', array(
	'id'=>'reforumulated-enquiry-grid',
	'dataProvider'=>$reformulatedDataprovider,
	'template' => '{items}{pager}',
	'rowCssClassExpression'=>'($data->id == '.$model->id.')? "highlight_row":"row_id_".$row." ".($row%2?"even":"odd")',
    'onClick'=>array(
        'type'=>'url',
        'call'=>Yii::app()->request->baseUrl.'/enquiry/view',
    ),
	'pager'=>array('class'=>'CLinkPager',
					'header'=>'',
					'maxButtonCount'=>6,
					'prevPageLabel'=>'< Prev',
	),
	'columns'=>array(
			array(
				'header'=>__('Enquiry'),
				'value'=>'$data[\'title\']',
			),
			array(
				'header'=>__('State'),
				'type' => 'raw',
				'value'=>'$data->getHumanStates($data[\'state\'])',
			),
			array(
				'header'=>__('Formulated'),
				'name'=>'created',
				'value'=>'format_date($data[\'created\'])',
			),
			array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
)));
}?>

<h1><?php echo $model->title?></h1>
<hr style="margin-top:-10px;margin-bottom:-5px;" />
<div id="states_diagram" style="display:none;z-index:10;position:absolute;">
<img src="<?php echo Yii::app()->request->baseUrl;?>/images/states.png" onClick="js:toggleStatesDiagram();"/>
</div>

<div style="margin-top:5px;float:right;text-align:left;margin-left:10px;padding:0px;width:500px;">
<?php $this->widget('zii.widgets.CDetailView', array(
	'cssFile' => Yii::app()->request->baseUrl.'/css/pdetailview.css',
	'data'=>$model,
	'attributes'=>array(
		array(
	        'label'=>__('Formulated'),
			'type' => 'raw',
	        'value'=>($model->user0->username == Yii::app()->user->id || $model->user0->is_disabled == 1) ?
						format_date($model->created).' '.__('by').' '.$model->user0->fullname :
						format_date($model->created).' '.__('by').' '.CHtml::link(
															CHtml::encode($model->user0->fullname), '#',
															array('onclick'=>'js:getContactForm('.$model->user.');return false;')
														),
		),
		array(
	        'label'=>__('Type'),
	        'value'=>($model->related_to) ? $model->getHumanTypes($model->type).' ('.__('reformulated').')' : $model->getHumanTypes($model->type),
		),
		array(
	        'label'=>__('Subscribed users'),
	        'value'=>count($model->subscriptions),
		),
		array(
	        'label'=>__('State'),
			'type' => 'raw',
			'value'=> CHtml::link(
						CHtml::encode($model->getHumanStates($model->state)), '#',
						array('onclick'=>'js:toggleStatesDiagram();')
					),
		),
	),
));

if($model->state >= ENQUIRY_AWAITING_REPLY){
	$submitted_info=format_date($model->submitted).', '.__('Registry number').': '.$model->registry_number;
	$attributes=array(
					array(
	        			'label'=>__('Submitted'),
						'type'=>'raw',
						'value'=>$submitted_info,
					),
				);
	if($model->documentation){
		$document = '<a href="'.$model->documentation0->getWebPath().'" target="_new">'.$model->documentation0->name.'</a>';
		$attributes[]=array(
				        'label'=>__('Documentation'),
						'type'=>'raw',
	        			'value'=>$document,
					);
	}
	$this->widget('zii.widgets.CDetailView', array(
		'cssFile' => Yii::app()->request->baseUrl.'/css/pdetailview.css',
		'data'=>$model,
		'attributes'=>$attributes,
	));
}
if($model->budget)
	$this->renderPartial('//budget/_enquiryView', array('model'=>$model->budget0, 'showLinks'=>1, 'showEnquiriesMadeLink'=>1, 'enquiry'=>$model));
?>
</div>

<div>
<div class="socialIcons" style="margin-top:10px;margin-bottom:10px;">

<div id="directlink" class="social_popup">
<?php
	$url = $this->createAbsoluteUrl('/enquiry/'.$model->id);
	echo '<span onClick=\'location.href="'.$url.'";\'>'.$url.'</span>';
?>
</div>

<div id="subscribe" class="social_popup">
<?php
	$criteria = new CDbCriteria;
	$criteria->condition = 'enquiry = '.$model->id.' AND user = '.Yii::app()->user->getUserID();
	$checked = '';
	if( EnquirySubscribe::model()->findAll($criteria) )
			$checked = 'checked';
?>
<?php echo __('Keep me informed via email when there are changes')?>
<input type="checkbox"	onClick="js:subscribe(this);"
						style="
						    vertical-align: middle;
						    position: relative;
						    bottom: 1px;
						"
	<?php echo $checked; ?>
/>
</div>

<img social_icon="directlink" src="<?php echo Yii::app()->request->baseUrl;?>/images/link.png" onClick="js:clickSocialIcon(this);"/>
<?php
if($model->state >= ENQUIRY_ACCEPTED){
	echo '<img social_icon="subscribe" src="'.Yii::app()->request->baseUrl.'/images/mail.png" onClick="js:clickSocialIcon(this);"/>';
	echo '<div	class="fb-like"
				data-href="'.$this->createAbsoluteUrl('/enquiry/'.$model->id).'"
				data-send="false"
				data-layout="button_count"
				data-width="80px"
				data-show-faces="false"
				data-font="arial">
		</div>';
	echo '<span style="margin-left:10px"></span>';
	echo '<a	href="https://twitter.com/share"
				class="twitter-share-button"
				data-url="'.$this->createAbsoluteUrl('/enquiry/'.$model->id).'"
				data-counturl="'.$this->createAbsoluteUrl('/enquiry/'.$model->id).'"
				data-text="'.$model->title.'"
				data-hashtags="'.Config::model()->findByPk('siglas')->value.'"
				data-lang="en"
				style="width:80px">
		</a>';

}?>

</div>



<?php
if($model->state == ENQUIRY_PENDING_VALIDATION && $model->user == Yii::app()->user->getUserID()){
	echo '<div style="font-style:italic;">Puedes '.CHtml::link('editar la enquiry',array('enquiry/edit','id'=>$model->id)).' y incluso ';
	echo CHtml::link('borrarla',"#",
                    array(
						"submit"=>array('delete', 'id'=>$model->id),
						"params"=>array('returnUrl'=>Yii::app()->request->baseUrl.'/user/panel'),
						'confirm' => '¿Estás seguro?'));
	echo ' hasta que la '.Config::model()->findByPk('siglas')->value.' reconozca la entrega. (+ comments and subscriptions).</div>';
}
?>

<?php echo $this->renderPartial('_view', array('model'=>$model)); ?>
</div>
<div class="clear"></div>


