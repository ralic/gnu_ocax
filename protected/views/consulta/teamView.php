<?php
/* @var $this ConsultaController */
/* @var $model Consulta */
Yii::app()->clientScript->registerCoreScript('jquery');

$this->menu=array(
	array('label'=>'Actualizar estat', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Anadir respuesta', 'url'=>array('/respuesta/create?consulta='.$model->id)),
	array('label'=>'Editar Consulta', 'url'=>array('edit', 'id'=>$model->id)),
	array('label'=>'Emails enviados', 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'team')),
	array('label'=>'Listar consultas', 'url'=>array('managed')),
	//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
);
?>

<?php if(Yii::app()->user->hasFlash('prompt')):?>
    <div class="flash_prompt">
        <?php /*echo Yii::app()->user->getFlash('success');*/ ?>
		<p style="margin-top:5px;"><b>Enviar un correo a <?php echo $model->user0->fullname;?>?</b></p>
		<?php 
		$url=Yii::app()->request->baseUrl.'/email/create?consulta='.$model->id.'&menu=team';
		?>
			<button onclick="js:window.location='<?php echo $url?>';">Sí</button>
			<button onclick="$('.flash_prompt').slideUp('fast')">No</button>
    </div>
<?php endif; ?>
<?php if(Yii::app()->user->hasFlash('success')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash_success').fadeOut('fast');
    	}, 2000);
		});
	</script>
    <div class="flash_success">
		<p style="margin-top:5px;"><b>Email enviado correctamente</b></p>
    </div>
<?php endif; ?>

<div class="consulta">
<?php echo $this->renderPartial('_teamView', array('model'=>$model)); ?>
</div>




