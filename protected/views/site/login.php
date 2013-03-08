<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */


?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 48%; float: left;  margin: 0px;}
	.right{width: 48%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<div class="outer">
<div class="left">

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<div class="title">Login</div>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Login'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<p></p>

</div>
<div class="right">
<p style="font-size:1.5em;margin-bottom:10px;">¿Aun no tienes una cuenta?</p>
<p><a href="<?php echo Yii::app()->request->baseUrl; ?>/site/register">Crea un cuenta</a>
y audita tu municipio</p>
<br/>
<p style="font-size:1.5em;margin-bottom:10px;">¿Has olvidado tu contraseña?</p>
<p>Instrucciones aquí</p>

</div>
</div>
