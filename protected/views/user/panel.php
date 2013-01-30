<?php
/* @var $this UserController */
/* @var $model User */
/*
 * @property integer $is_team_member
 * @property integer $is_editor
 * @property integer $is_manager
 * @property integer $is_admin
*/

$column=0;
function changeColumn()
{
	global $column;
	if($column==0)
	{
		echo '<div class="clear"></div>';
		echo '<div class="left">';
		$column=1;
	}
	else
	{
		echo '<div class="right">';
		$column=0;
	}
}
?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 48%; float: left;  margin: 0px;}
	.right{width: 48%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>


<div class="outer">
<div class="left">
<h1><?php echo CHtml::link('Nueva consulta',array('consulta/create/'));?></h1>
<p>
Realizar una nueva consulta.<br />
Explicar aquí el procedimiento.
</p>
</div>
<div class="right">
<h1><?php echo CHtml::link('Mi perfil',array('user/update/'));?></h1>
<p>
Change your profile<br />
Configure your email<br />
Change your password</p>
</div>
<?php

if($model->is_team_member){
	changeColumn();
	echo '<h1>'.CHtml::link('Consultas encomendadas',array('consulta/assigned')).'</h1>';
	echo '<p>Gestionar las consultas que te han encargado.</p>';
	echo "</div>";
}

if($model->is_editor){
	changeColumn();
	echo '<h1>'.CHtml::link('Site CMS page editor',array('/cmspage')).'</h1>';
	echo '<p>Edit the general information pages</p>';
	echo "</div>";
}

if($model->is_manager){
	changeColumn();
	echo '<h1>'.CHtml::link('Gestionar consultas',array('consulta/admin')).'</h1>';
	echo '<p>Assign new consultas a miembros del equipo y comprobar el estado de todos las consultas.</p>';
	echo "</div>";
}

if($model->is_admin){
	changeColumn();
	echo '<h1>'.CHtml::link('Admin panel',array('user/admin')).'</h1>';
	echo "<p>Change user profiles<br />Delete users</p>";
	echo "</div>";
}

?>

</div>
<div class="clear"></div>



<?php 

	if($consultas->getData()){
    $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'consulta-grid',
	'dataProvider'=>$consultas,
	//'filter'=>$model,
	'columns'=>array(
        array(
			'name'=>'Mis Consultas',
			'value'=>'$data->title',
		),
		'created',
		'state',
		/*
		'type',
		'capitulo',
		'title',
		'body',
		*/
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array(
				'view' => array(
					'label'=>'View',
		            'url'=>'Yii::app()->createUrl("consulta/view", array("id"=>$data->id))',
				),
				'update' => array(
					'label'=>'Update',
		            'url'=>'Yii::app()->createUrl("consulta/assign", array("id"=>$data->id))',
				),
			),
		),
	),
));} ?>




     