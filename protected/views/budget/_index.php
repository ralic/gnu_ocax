<?php
/* @var $this BudgetController */
/* @var $model Budget */
Yii::app()->getClientScript()->registerCoreScript( 'jquery.ui' );

Yii::app()->clientScript->registerScript('search', "
  $('#budget-form').submit(function(){
  $.fn.yiiListView.update('search-results', {
  data: $(this).serialize()
  });
  return false;
});
");

//$year = $model->year;
$featured=$model->findAllByAttributes(array('year'=>$model->year, 'featured'=>1));
?>

<style>
.pie_graph{

}
</style>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jraphael/raphael-min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jraphael/g.raphael-min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jraphael/g.pie-min.js"></script>

<script>
function slideInChild(parent_id,child_id){
	$('#scroll_back').attr('parent_id',parent_id);
	$('#scroll_back').show();
	$('#'+child_id).hide();
	$('#'+parent_id).hide(	"slide",
							{ direction: "left" },
							1000,
							function(){
								$('#'+child_id).css("visibility","visible");
								$('#'+child_id).fadeIn('fast');
							;}
						);
}

function getPie(budget_id){
	if($("#pie_display").children("#"+budget_id).length){
		alert('exists');
		slideInChild($("#pie_display").children("#"+budget_id).attr('parent_id'),budget_id);
		return;
	}
	new_pie_div=$('<div id="'+budget_id+'" style="visibility:hidden" class="pie_graph"></div>');
	$('#pie_display').append(new_pie_div);
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/getPieData/'+budget_id,
		type: 'GET',
		async: false,
		dataType: 'json',
		beforeSend: function(){ },
		success: function(data){
			new_pie_div.attr('parent_id',data.params.parent_id);
			$('#pie_display').append(new_pie_div);
			createPie(budget_id, data);
			slideInChild(data.params.parent_id,budget_id);
		},
		error: function() {
			alert("Error on get Pie Data");
		}
	});

}

function createPie(div_id, data){
	r = Raphael(div_id, 800, 440);
	r.text(250, 50, data.params.title).attr({ font: "30px sans-serif" });
	pie = r.piechart(250, 250, 150, data.numbers,{
				legend: data.labels,
				legendpos: "east",
				legendothers: data.params.others,
				href: data.links,
			}
	);
	pie.hover(function () {
		this.sector.stop();
		this.sector.scale(1.1, 1.1, this.cx, this.cy);

		if (this.label) {
			this.label[0].stop();
			this.label[0].attr({ r: 7.5 });
			this.label[1].attr({ "font-weight": 800 });
		}
	},
	function () {
		this.sector.animate({ transform: 's1 1 ' + this.cx + ' ' + this.cy }, 500, "smooth");

		if (this.label) {
			this.label[0].animate({ r: 5 }, 500, "bounce");
			this.label[1].attr({ "font-weight": 400 });
		}
	});
}

function goBack(el){
	parent_pie=$('#'+$(el).attr('parent_id'));
	if(parent_pie.attr('parent_id')){
		$('#scroll_back').attr('parent_id',parent_pie.attr('parent_id'));
	}else{
		$('#scroll_back').hide();
	}
	parent_pie.show("slide",{ direction: "left" },	1000);
	child_pie=$("#pie_display").children(".pie_graph").hide();
	return false;
}
// legend font size
// http://stackoverflow.com/questions/13043989/raphael-piechart-legend-font
// http://stackoverflow.com/questions/4679785/graphael-bar-chart-with-text-x-axis
// https://gist.github.com/ejucovy/451637

$(function() {
	<?php
		foreach($featured as $budget){?>
			//new_pie_div=$('<div id="<?php echo $budget->id?>" class="pie_graph"></div>');
			$('#pie_display').append('<div id="<?php echo $budget->id?>" class="pie_graph"></div>');
			<?php
			$data = $this->actionGetPieData($budget->id);
			echo 'createPie('.$budget->id.','.$data.');';
		}
	?>
});


// this is for interactive graphic
$(function() {
	$('.budget').bind('click', function() {
		budget_id = $(this).attr('budget_id');
		content = '';
		if(1 == 1){	// why did I if this?
			enquiry_link='<?php echo Yii::app()->request->baseUrl;?>/enquiry/create?budget='+budget_id;
			enquiry_link='<a href="'+enquiry_link+'">hacer una enquiry</a>';
			content=content+'Deseas '+enquiry_link+'?';
		}
		$('#budget_options_content').html(content);
		$('#budget_options').bPopup({
			modalClose: false
			, position: ([ 'auto', 200 ])
			, follow: ([false,false])
			, fadeSpeed: 10
			, positionStyle: 'absolute'
			, modelColor: '#ae34d5'
		});
	});
});
$(function() {
	$("#Budget_concept").on("click", function(event){
		$("#Budget_code").val('');
	});
	$("#Budget_code").on("click", function(event){
		$("#Budget_concept").val('');
	});
});
</script>


<div style="font-size:2.5em;text-align:center;margin-top:-10px;">
<?php echo Config::model()->findByPk('councilName')->value;?>

</div>

<div style="
	margin-bottom:15px;
	font-size:1.5em;
	">
<?php echo __('Budget for').' '.$model->getYearString();

if(Yii::app()->user->isAdmin())
	$years=$model->findAll(array('condition'=>'parent IS NULL'));
else
	$years=$model->findAll(array('condition'=>'parent IS NULL AND code = 1'));

if(count($years) > 1){
	$list=CHtml::listData($years, 'year', function($year) {
		return $year->getYearString();
	});

	echo '<span style="float:right">';
		echo __('Available years').' ';
		echo CHtml::dropDownList('budget', $model->year, $list,
								array(	'id'=>'selectYear',
										'onchange'=>'location.href="'.Yii::app()->request->baseUrl.'/budget?year="+this.options[this.selectedIndex].value'
								));
	echo '</span>';
}
?>
</div>

<div style="
	border-top: 1px solid #C9E0ED;
	border-bottom: 1px solid #C9E0ED;
	padding:20px;
	margin-left:-30px;
	margin-right:-40px;
	background-color:#F0F8FF;
	-webkit-box-shadow: 0 8px 6px -3px grey;
	-moz-box-shadow: 0 8px 6px -3px grey;
	box-shadow: 0 8px 6px -3px grey;
	">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'id'=>'budget-form',
	'method'=>'get',
)); ?>

	<?php echo $form->hiddenField($model,'year'); ?>

	<div class="row">
		<?php echo $form->label($model,'concept'); ?>
		<?php echo $form->textField($model,'concept',array('size'=>40,'maxlength'=>255)); ?>

		<span style="margin-left:15px">
		<?php echo $form->label($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>5,'maxlength'=>255)); ?>
		</span>
		<span style="margin-left:150px;"><?php echo CHtml::submitButton(__('Filter')); ?></span>
	</div>

<?php $this->endWidget(); ?>
</div><!-- search-form -->
<div style="clear:both"></div>

<?php
$dataProvider = $model->publicSearch();
$data = $dataProvider->getData();
if( count($data) > 0){ ?>
	<div style="font-size:1.5em;margin-top:15px;margin-bottom:5px"><?php echo __('Filtered results')?></div>
	<?php $this->widget('zii.widgets.CListView', array(
		'id'=>'search-results',
		'ajaxUpdate' => true,
		'dataProvider'=> $dataProvider,
		'itemView'=>'_searchResults',
		'enableHistory' => true, 
	));
}else{
?>
<style>
.button {
   border-top: 1px solid #96d1f8;
   background: #65a9d7;
   background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#65a9d7));
   background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
   background: -moz-linear-gradient(top, #3e779d, #65a9d7);
   background: -ms-linear-gradient(top, #3e779d, #65a9d7);
   background: -o-linear-gradient(top, #3e779d, #65a9d7);
   padding: 13.5px 27px;
   -webkit-border-radius: 8px;
   -moz-border-radius: 8px;
   border-radius: 8px;
   -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
   -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
   box-shadow: rgba(0,0,0,1) 0 1px 0;
   text-shadow: rgba(0,0,0,.4) 0 1px 0;
   color: white;
   font-size: 19px;
   font-family: Helvetica, Arial, Sans-Serif;
   text-decoration: none;
   vertical-align: middle;
   }
.button:hover {
   border-top-color: #28597a;
   background: #28597a;
   color: #ccc;
   }
.button:active {
   border-top-color: #1b435e;
   background: #1b435e;
   }
</style>

<div style="margin-top:10px;height:10px;">
<a href="javascript:void(0)" id="scroll_back" style="display:none" onclick="javascript:goBack(this);">go back</a>

<?php
if($zip = File::model()->findByAttributes(array('model'=>'DatabaseDownload'))){
	echo '<a style="float:right" href="'.$zip->webPath.'">'.__('Download database').'</a>';
}?>
</div>

<div id="pie_display" style="margin-top:20px"></div>


<?php
if($zip = File::model()->findByAttributes(array('model'=>'DatabaseDownload'))){
	echo '<div style="margin-top:40px;">';
	echo '<a class="button" href="'.$zip->webPath.'">'.__('Download database').'</a>';
	echo '</div>';
}?>


<?php


	foreach($featured as $budget){
		echo '<div style="margin-top:40px;">';
		echo CHtml::link($budget->concept,array('budget/view','id'=>$budget->id), array('class'=>'button'));
		echo '</div>';
	}
}
?>



