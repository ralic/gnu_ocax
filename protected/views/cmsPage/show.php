
<?php
$this->setPageTitle($content->pageTitle);
?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 15%; float: left;  margin: 0px;}
	.right{width: 83%; float: left; margin: 0px;}
	.clear{clear:both;}
	.activeItem a{color: red;}
</style>


<div class="outer">

<div class="left">
	<?php

	foreach ($items as $menu_item) {
		foreach($menu_item->cmsPageContents as $item){
			if($item->language == $content->language){
				break;	
			}
		}
		$itemclass='';
		if($content->pageURL == $item->pageURL)
			$itemclass='class="activeItem"';
		echo '<div '.$itemclass.'>';
		echo CHtml::link($item->pageTitle,array('p/'.$menu_item->id.'/'.$item->pageURL));
		echo '</div>';
		echo '<br />';
	}

?>
</div>

<div class="right">
	<div style="font-size:1.5em;text-align:center;padding-bottom:20px;"><?php echo $content->pageTitle; ?></div>
	<?php echo $content->body; ?>
</div>
</div>

<div class="clear"></div>
