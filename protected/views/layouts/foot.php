<div id="footer">
	<div style="width: 680px; float:left; margin:10px 0 10px 5px;">
		<?php
			echo '<img id="avatar" style="float:left;" src="'.Yii::app()->request->baseUrl.'/files/logo.png" />';
		?>
		<div style="float:left;">
			<?php
			echo '<b>'.Config::model()->getObservatoryName().'</b><br />';
			echo '<u>'.__('Contact information').'</u><br />';
			if($blog = Config::model()->findByPk('observatoryBlog')->value)
				echo '<a href="'.$blog.'">'.$blog.'</a><br />';

			echo __('Email').': '.Config::model()->findByPk('emailContactAddress')->value.'<br />';
			if($telf = Config::model()->findByPk('telephone')->value)
				echo __('Telephone').': '.$telf.'<br />';
			?>
		</div>
	</div>

	<div style="width: 250px; float:right; margin:10px 0 10px 0;">
		<div style="float: left;">
			AGPLv3 Copyright &copy; <?php echo date('Y'); ?><br />
			<?php $lang=Yii::app()->language; ?>
			<a href="http://ocax.net/?<?php echo $lang;?>">http://ocax.net</a><br />
			<a href="http://ocmunicipal.net/?lang=<?php echo $lang;?>">http://ocmunicipal.net</a><br/>
		</div>
		<div style="float:right;margin-left:20px">
			<a href="http://auditoriaciudadana.net"><div id="pacd_logo"></div></a>
		</div>
	</div>

<div style="clear:both;"></div>
</div><!-- footer -->

<div style="width:980px;margin:0 auto;margin-top:5px;">
	<div id="postFooterRSSLink">
	<?php 
		echo Config::model()->getObservatoryName().' RSS feed ';
		echo CHtml::link('<img src="'.Yii::app()->baseUrl.'/images/rss-16x16.png"/>',array('/site/feed'));
	?>
	</div>
</div>
</div>
