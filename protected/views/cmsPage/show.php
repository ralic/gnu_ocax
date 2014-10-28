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

$this->setPageTitle($content->pageTitle);
?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/cmspage.css" />

<?php if(isset($preview)){ ?>
<script>
$(function() {
	$('.language_link').hide();
});
function savePreview(){
	form = $('#cms-page-form');
	form.attr(	'action',
				"<?php echo Yii::app()->request->baseUrl; ?>/cmsPage/savePreview/<?php echo $model->id;?>?lang=<?php echo $content->language;?>"
			);
	form.submit();
}
function editPreview(){
	form = $('#cms-page-form');
	form.attr(	'action',
				"<?php echo Yii::app()->request->baseUrl; ?>/cmsPage/editPreview/<?php echo $model->id;?>?lang=<?php echo $content->language;?>"
			);
	form.submit();}
</script>

<?php
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'cms-page-form',
	'enableAjaxValidation'=>false,
));
echo $form->hiddenField($content,'previewBody');
$this->endWidget();

echo '<div id="cmsOptions">';
	echo '<div style="width:30%; float: left; text-align: center;">';
	echo '<a href="#" onclick="js:savePreview();">'.__('Save changes').'</a>';
	echo '</div>';
	echo '<div style="width:30%; float: left; text-align: center;">';
	echo '<a href="#" onclick="js:editPreview();">'.__('Edit page').'</a>';
	echo '</div>';
	echo '<div style="width:30%; float: left; text-align: center;">';
	echo CHtml::link(__('Manage pages'),array('cmsPage/admin'));
	echo '</div>';
echo '<div style="clear:both;"></div>';
echo '</div>';

} ?>

<!-- start page here -->
<div id="cmsPageBreadcrumbs">
<?php
	if($parent = CmsPage::model()->findByAttributes(array('block'=>$model->block, 'published'=>1, 'weight'=>0))){
		//array('order'=>'weight')

		echo '<a href="'.$this->createUrl('site/index').'">'.__('Home').'</a>';
		if($parent->id != $model->id){
			if($parentContent = CmsPageContent::model()->findByAttributes(array('page'=>$parent->id,'language'=>$content->language))){
				echo ' &rarr; <a href="'.$this->createUrl('p/'.$parentContent->pageURL).'">'.$parentContent->pageTitle.'</a>';
			}
		}
		echo ' &rarr; <a href="'.$this->createUrl('p/'.$content->pageURL).'">'.$content->pageTitle.'</a>';
}
?>
</div>
<?php
if(!$model->published)
	echo '<i class="icon-attention green"></i> '.__('Not published');
if(!Yii::app()->user->isGuest && Yii::app()->user->isEditor() && !isset($preview))
	echo	'<i class="icon-edit-1 color" style="float:right; font-size:18px; cursor:pointer;" '.
			'onclick="js:window.location.href=\''.Yii::app()->request->baseUrl.'/cmsPage/update/'.$model->id.'\'"></i>';
?>

<div class="cms_titulo"><?php echo CHtml::encode($content->pageTitle); ?></div>
<div class="cms_content">
	<?php echo isset($preview) ? $content->previewBody : $content->body; ?>
</div>
