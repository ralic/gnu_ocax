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

/* @var $this EnquiryController */
/* @var $model Enquiry */

if(Yii::app()->request->isAjaxRequest){
	Yii::app()->clientScript->scriptMap['jquery.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
}else
	echo '<link rel="stylesheet" type="text/css" href="'.Yii::app()->request->baseUrl.'/fonts/fontello/css/fontello.css" />';
?>

<style>
.comments { margin-top:15px; }
.voteBlock { float:right;text-align:right; white-space:nowrap; }
.clear { clear:both; }
</style>


<script>
function isUser(){
	if('1' == '<?php echo Yii::app()->user->isGuest;?>'){
		alert("<?php echo __('Please login to participate')?>");
		return 0;
	}else
		return 1;
}
function canParticipate(){
<?php
	if(!Yii::app()->user->isGuest && User::model()->findByAttributes(array('username'=>Yii::app()->user->id))->is_active)
		$participate = 1;
	else
		$participate = 0;
?>
	if('0' == '<?php echo $participate?>')
		alert("<?php echo __('Before participating, please confirm your email address')?>");
	return <?php echo $participate?>;
}
function toggleComments(comments_block_id){
	//$('#comment_form').hide();
	if ($('#'+comments_block_id).is(":visible"))
		$('#'+comments_block_id).slideUp('fast');
	else
		$('#'+comments_block_id).slideDown('fast');
}
function updateSubscriptionTotal(addMe){
	if($('#subscriptionTotal').length>0){
		total=parseInt($('#subscriptionTotal').html());
		$('#subscriptionTotal').html(total+addMe);
	}
}
function getCommentForm(comment_on, id, el){
	if(!isUser())
		return;
	if(!canParticipate())
		return;
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/comment/getForm',
		type: 'GET',
		dataType: 'json',
		data: {'comment_on': comment_on, 'id': id },
		beforeSend: function(){ /*$ ('#right_loading_gif').show(); */ },
		complete: function(){ /* $('#right_loading_gif').hide(); */ },
		success: function(data){
			//$('#comment_form').html();
			$('#comment_form').html(data.html);
			$(el).after($('#comment_form'));
			$('#comment_form').show();
			$('.add_comment_link').show();
			$('#comment_form').prev('.add_comment_link').hide();
		},
		error: function() {
			alert("Error on get comment form");
		}
	});
}
function cancelComment(){
	//$('#comment_form').html();
	$('#comment_form').slideUp('fast');
	$('#comment_form').prev('.add_comment_link').show();
}
function submitComment(form){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/comment/create',
		type: 'POST',
		dataType: 'json',
		data: $(form).serialize(),
		beforeSend: function(){
						$('#comment_form').find(':input').prop('disabled',true);
						$(form).find('.loading_gif').show();
					},
		complete: function(){
						$('#comment_form').html('');
						$('#comment_form').hide();
					},
		success: function(data){
				if(data != 0){
					$('#comment_form').parents('.add_comment:first').before(data.html);

					show_comments_link = $('#comment_form').parents('.comments').find('.show_comments_link');
					comment_count = show_comments_link.find('.comment_count');
					count = parseInt(comment_count.html())+1;
					comment_count.html(count);
					show_comments_link.show();
					if($('#subscribe_checkbox').length>0)
						$('#subscribe_checkbox').attr('checked', true);
					updateSubscriptionTotal(data.newSubscription);
				}
				$('#comment_form').prev('.add_comment_link').show();
		},
		error: function() { alert("error on create comment"); },
	});
}
function deleteComment(comment_id){
	retVal = confirm("<?php echo __('Are you sure you want to delete it?');?>");
	if( retVal == false ){
		return;
	}
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/comment/delete/'+comment_id,
		type: 'POST',
		success: function(data){
				if(data == 1){
					show_comments_link = $('#comment_'+comment_id).parents('.comments').find('.show_comments_link');
					comment_count = show_comments_link.find('.comment_count');
					count = comment_count.html() -1;
					comment_count.html(count);
					if(count == 0)
						show_comments_link.hide();
					$('#comment_'+comment_id).remove();
				}
		},
		error: function() { alert("error on delete comment"); },
	});
}
function vote(reply_id, like){
	if(!isUser())
		return;
	if(!canParticipate())
		return;
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/vote/create',
		type: 'POST',
		dataType: 'json',
		data: { 'reply': reply_id, 'like': like },
		success: function(data){
				if(data != 0){
					if(data.already_voted){
						if(data.already_voted == 1)
							alert('<?php echo __('You have already voted favourably');?>');
						else
							alert('<?php echo __('You have already voted unfavourably');?>');
					}else{
						$("#voteLikeTotal_"+reply_id).html(data.total_likes);
						$("#voteDislikeTotal_"+reply_id).html(data.total_dislikes);
					}
				}
		},
		error: function() { alert("error on vote"); },
	});
}
function getContactForm(recipient_id){
	if(!isUser())
		return;
	if(!canParticipate())
		return;
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/email/contactPetition',
		type: 'GET',
		data: {'recipient_id': recipient_id, 'enquiry_id': <?php echo $model->id?> },
		beforeSend: function(){ },
		complete: function(){ },
		success: function(data){
			if(data != 1){
				$('#contact_petition_content').html();
				$("#contact_petition_content").html(data);
				$('#contact_petition').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, speed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on get Contact petition");
		}
	});
}
function sendContactForm(form){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/email/contactPetition',
		type: 'POST',
		data: $('#'+form).serialize(),
		beforeSend: function(){
					$('#contact_petition_buttons').replaceWith($('#contact_petition_sending'));
					$('#contact_petition_sending').show();
					},
		complete: function(){ /* $('#right_loading_gif').hide(); */ },
		success: function(data){
			if(data == 1){
				$('#contact_petition_sending').replaceWith($('#contact_petition_sent'));
				$('#contact_petition_sent').show();

			}else{
				$('#contact_petition_sending').replaceWith($('#contact_petition_error'));
				$('#contact_petition_error').html(data);
				$('#contact_petition_error').show();
			}
			setTimeout(function() {
				$('#contact_petition').fadeOut('fast',
										function(){
											$('#contact_petition').bPopup().close();
									});
    		}, 2000);
		},
		error: function() {
			alert("Error on post Contact petition");
		}
	});
}
function viewFile(url){
	var win=window.open(url, '_blank');
	win.focus();
}
</script>

<div class="enquiryBody"><?php echo $model->body;?></div>
<div style="clear:both"></div>

<?php $this->renderPartial('//comment/_showThread', array('model'=>$model)); ?>

<?php
// start replies
$replys = Reply::model()->findAll(array('condition'=>'enquiry =  '.$model->id));
foreach($replys as $reply)
	$this->renderPartial('//reply/_view', array('model'=>$reply));

?>
<div class="clear"></div>

<div id="comment_form" style="display:none"></div>

<div id="budget_popup" class="modal" style="width:900px;">
	<i class='icon-cancel-circled modalWindowButton bClose'></i>
	<i class='icon-popup modalWindowButton bModal2Page' onclick="js:budgetModal2Page();"></i>
	<div id="budget_popup_body"></div>
</div>

<?php if (!Yii::app()->user->isGuest) : ?>
	<div id="contact_petition" class="modal" style="width:700px;">
		<i class='icon-cancel-circled modalWindowButton bClose'></i>
		<div id="contact_petition_content"></div>
	</div>
<?php endif ?>

<?php if ($model->team_member == Yii::app()->user->getUserID()) : ?>
<script>
function uploadFile(model,model_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/file/create?model='+model+'&model_id='+model_id,
		type: 'POST',
		success: function(data){
			if(data != 0){
				$("#files_popup_content").html(data);
				$('#files_popup').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, speed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on get file/create");
		}
	});
}
function deleteFile(file_id){
	answer=confirm("<?php echo __('Are you sure you want to delete it?');?>");
	if(!answer)
		return 1;
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/file/delete/'+file_id,
		type: 'POST',
		success: function(){
				$("#attachment_"+file_id).remove();
		},
		error: function() {
			alert("Error on get file/delete");
		}
	});
}
</script>

<?php echo $this->renderPartial('//file/modal'); ?>

<?php endif ?>
