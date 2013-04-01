<?php
/* @var $this EnquiryController */
/* @var $model Enquiry */
/* @var $form CActiveForm */

$user_id = Yii::app()->user->getUserID();
$user= User::model()->findByPk($user_id);
if(!$user->is_active)
	$this->renderPartial('//user/_notActiveInfo', array('model'=>$user));
?>

<script>
$(document).ready(function() {
	if(1 != <?php echo $user->is_active;?>){
		$('#enquiry-form').find(':input:not(:disabled)').prop('disabled',true);
		$('#enquiry-form').find(':textarea:not(:disabled)').prop('disabled',true);
	}
});
</script>


<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'enquiry-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="title">
	<?php
		if($model->isNewRecord){
			if($model->related_to)
				echo ' '.__('New reformulated enquiry');
			else
				echo ' '.__('New enquiry');
		}else
			echo __('Modify enquiry');
	?>
	</div>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model,'budget'); ?>
	<?php echo $form->hiddenField($model,'related_to'); ?>


	<?php if($model->budget){
		echo '<div class="row" style="margin:-15px -10px 10px -10px;">';
		$this->renderPartial('//budget/_enquiryView',array('model'=>Budget::model()->findByPk($model->budget)));
		echo '</div>';
	}?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>

<?php
$this->widget('ext.tinymce.TinyMce', array(
    'model' => $model,
    'attribute' => 'body',
    'compressorRoute' => 'tinyMce/compressor',
    //'spellcheckerUrl' => array('tinyMce/spellchecker'),
    // or use yandex spell: http://api.yandex.ru/speller/doc/dg/tasks/how-to-spellcheck-tinymce.xml
    'spellcheckerUrl' => 'http://speller.yandex.net/services/tinyspell',

    'htmlOptions' => array(
        'rows' => 10,
        'cols' => 80,
    ),
));
?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? __('Publish') : __('Update')); ?>
		<?php	if (!$model->id)
					$cancelURL='/user/panel';
				elseif ($model->team_member == $user_id)	// remember: a team_memebr can edit a enquiry
					$cancelURL='/enquiry/teamView/'.$model->id;
				else
					$cancelURL='/enquiry/'.$model->id;
		?>
		<input type="button" value="<?php echo __('Cancel')?>" onclick="js:window.location='<?php echo Yii::app()->request->baseUrl?><?php echo $cancelURL?>';" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

