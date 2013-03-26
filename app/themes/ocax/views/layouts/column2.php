<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-19">
	<div id="content" style="margin:0 -10px 0 -10px;">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<div class="span-5 last">
	<div id="sidebar">
	<?php

//http://www.yiiframework.com/doc/blog/1.1/en/portlet.menu

		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Operations',
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->menu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		$this->endWidget();
		if($this->contextHelp){
			echo '<div class="contextHelp">';
			echo '<div class="title">Context help</div>';
			echo $this->contextHelp;
			echo '</div>';
		}
		if($this->sidebarText)
			echo '<p>'.$this->sidebarText.'</p>';
	?>
	</div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>
