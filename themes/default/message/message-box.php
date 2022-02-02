<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($member_details);
?>

    <?php if($is_vacation == 1){ ?>
    <div class="alert alert-info mt-2">
        <div id="seller-vacation-div" class="mb-0">
            <p class="lead mb-0"><?php D(__('message_box_page_is_vacation',"<strong>Opps! </strong> This seller is on vacation and is not receiving messages at the momment. Please try again later."));?></p>
        </div>
    </div>
    
    <?php } ?>

    <?php if($status != 1){ ?>
    <div class="alert alert-danger mt-2">
            <p class="lead mb-0"><?php D(__('message_box_page_is_blocked',"This seller has been blocked so you can\'t send him messages anymore."));?></p>
    </div>
    <?php } ?> 

    <?php if($status == 1 AND $is_vacation != 1){ ?>

    <form id="insert-message-form">
	<textarea class="form-control text" cols="1" rows="1" id="message" placeholder="<?php D(__('message_box_page_message_input',"Type your Message Here"));?>" data-autoresize></textarea>
	
	<!--<p class="typing-status mb-1"></p>
	<p class="mb-2 mt-2 d-none files"></p>-->
	
    <div class="uploadButton">
        <input class="uploadButton-input" type="file" accept="image/*, application/pdf" id="file" />
        <label class="uploadButton-button" for="file"><i class="icon-material-outline-attach-file"></i></label>        
    	<button type="submit" id="send-msg" class="btn btn-site"><i class="icon-feather-send d-sm-none"></i><span class="hide-under-575"><?php D(__('message_box_page_Send',"Send"));?></span></button>
        
        <button type="button" id="send-offer" class="btn btn-outline-site"><?php D(__('message_box_page_Create_A_Offer',"Create A Offer"));?></button>
    
        <button type="button" class="btn btn-outline-success arrow-drop mr-0" data-html="true" data-toggle="toggleEnabled" data-placement="top" data-title="<strong><?php D(__('message_box_page_Pressing_Enter_will',"Pressing Enter will :"));?></strong>" data-content='<label class="fake-radio-green"><input type="radio" name="toggle-send" value="new-line" onclick="setCheck(this.value)"><span class="radio-img"></span><div><?php D(__('message_box_page_Start_a_new_line',"Start a new line"));?> <small><?php D(__('message_box_page_Press_Ctrl_Enter_to_send_message.',"Press Ctrl+Enter to send message."));?></small></div></label><label class="fake-radio-green"><input type="radio" name="toggle-send" value="send-msg" onclick="setCheck(this.value)"><span class="radio-img"></span><div><?php D(__('message_box_page_Send_message',"Send message"));?><small><?php D(__('message_box_page_Press_Shift_Enter_to_start_a_new_line.',"Press Shift+Enter to start a new line."));?></small></div></label>'>
        <i class="icon-feather-chevron-down"></i>
        </button>
    </div>
    
    <div class="custom-file float-left mb-3" hidden>
    <input type="hidden" id="sendType" value="new-line">
    <input type="hidden" id="fileVal">
    <span id="fileVal"></span>
    <input type="file" class="custom-file-input d-none" id="file">
    <label class="custom-file-label btn pl-0 ml-0" for="file">
    <img src="<?php D(theme_url().IMAGE)?>attach.png" width="25" alt="">
    </label>
    </div>
	</form>
    
	
   <script>
    var current = $("#sendType").val();
    $("input[value=" + current + "]").prop("checked", true);
    $(".fake-radio-green input").click(function(e){
    var val = $(this).val();
    $("#sendType").val(val);
    });
	function setCheck(val){
		$("#sendType").val(val);
	}
    </script>


<?php } ?>


<script>
var seller_id = "<?php D($message->chatwith); ?>";
var message_group_id = "<?php D($message->conversations_id); ?>";
function performAction(ev){
	var formID=$(ev).attr('id');
	if(formID=='accept_request' || formID=='decline_request'){
		var forminput='action='+formID;
		var buttonsection=$('#'+formID);
	}else{
		var modal=$(ev).closest('.modal');
		var buttonsection=$('#'+formID).find('.saveBTN');
		var forminput=$('#'+formID).serialize();
	}
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	$.ajax({
        type: "POST",
        url: "<?php D(get_link('saveActionURLMessageAJAX'))?>/"+message_group_id,
        data:forminput,
        dataType: "json",
        cache: false,
		success: function(msg) {
			buttonsection.html(buttonval).removeAttr('disabled');
			clearErrors();
			if (msg['status'] == 'OK') {
				$(modal).modal('hide');
				var message='<?php D(__('popup_message_box_page_Send_message_success',"Your request has been submitted successfully!"));?>';
				if(msg['message']){
					message=msg['message'];
				}
				 swal({
                  type: 'success',
                  text: message,
                  timer: 2000,
                  onOpen: function(){
                    swal.showLoading()
                  }
                  }).then(function(){
                  	if(msg['redirect']){
						window.location.href=msg['redirect'];
					}else{
						document.getElementById(formID).reset();
					}
                  	
                })	
			} else if (msg['status'] == 'FAIL') {
				registerFormPostResponse(formID,msg['errors']);
			}
		}
	})
	
	return false;
}
$('body').on('click','.acceptOfferBTN',function(){
		single_message_id = message_group_id;
		offer_id = $(this).data('offer');
		$.ajax({
		method: "POST",
		url: "<?php D(get_link('MessageOfferAccept'));?>",
		data: {single_message_id: single_message_id, offer_id: offer_id}
		})
		.done(function(data){
			$("#accept-offer-div").html(data);
		});
});
$(document).ready(function(){

  $('[data-toggle="toggleEnabled"]').popover({sanitize: false});

var text =  $('.text').emojioneArea({
	events: {
		keydown: function (editor, event) {
			
			var action = $("#sendType").val();
			//console.log(action);
			if(action == "send-msg"){
				//console.log(event.keyCode);
				if (event.keyCode == 13 && event.shiftKey) {

				}else if(event.keyCode == 13){
					event.preventDefault();
					sendMessage();
				}
			}else{
				if(event.keyCode == 13 && event.ctrlKey){
					event.preventDefault();
					sendMessage();
				}
			}
		}
	}
});
/*
var text =  $('.text').emojioneArea({
  events: {
    keydown: function (editor, event) {
	    var status = "typing";
	    $.ajax({
	    method: 'POST',
	    url: '<?php D(get_link('TypeStatus'));?>',
	    data: {message_group_id:message_group_id , status: status,mode:'update'}
	    });
	    action = $("#sendType").val();
	    if(action == "send-msg"){
			if (event.keyCode == 13 && event.shiftKey) {
		    }else if(event.keyCode == 13){
				event.preventDefault();
				sendMessage();
		    }
	  	}else{
		  	if(event.keyCode == 13 && event.ctrlKey){
				event.preventDefault();
				sendMessage();
		    }
	  	}
	},
    keyup: function (editor, event) {
        var status = "untyping";
        setTimeout(function(){
        $.ajax({
            method: 'POST',
            url: '<?php D(get_link('TypeStatus'));?>',
	    	data: {message_group_id:message_group_id , status: status,mode:'update'}
            });
        }, 5000);
    }
 }
});
// Javascript Jquery Code When User Start Typing Starts	//
$("#message").keydown(function(){
	var textarea = $("#message").val();
	var status = "typing";
	$.ajax({
	method: 'POST',
	url: '<?php D(get_link('TypeStatus'));?>',
	data: {message_group_id:message_group_id , status: status,mode:'update'}
	});
});	
// Javascript Jquery Code When User Start Typing Ends //

// Javascript Jquery Code When User Stop Typing Starts //
$("#message").keyup(function(){
	var textarea = $("#message").val();
	var status = "untyping";
	setTimeout(function(){
	$.ajax({
	method: 'POST',
	url: '<?php D(get_link('TypeStatus'));?>',
	data: {message_group_id:message_group_id , status: status,mode:'update'}
	});
	}, 5000);
});	
// Javascript Jquery Code When User Stop Typing Ends //

// Javascript Jquery Code To Reload User Typing Status Every half second Code Starts ///
*/
});

var height = 0;
$(".col-md-8 .messages .inboxMsg").each(function(i, value){
	height += parseInt($(this).height());
});
height += 2000;
$(".col-md-8 .messages").animate({scrollTop: height});
$(document).off('submit').on('submit','#insert-message-form', function(event){
	event.preventDefault();
	sendMessage();
	$(this).off('submit'); 
});
function sendMessage() {
	$("#send-msg").prop("disabled", true);
	$("#send-msg").html("<i class='fa fa-spinner fa-pulse fa-lg fa-fw'></i>");
	message = $('.emojionearea-editor').html();
	if(message==""){
    swal({
      type: 'warning',
      text: '<?php D(__('popup_message_box_page_Send_message_empty_error',"Message can\'t be empty!"));?>',
 	});
	$("#send-msg").prop("disabled", false);
	$("#send-msg").html("<?php D(__('message_box_page_Send',"Send"));?>");
	}else{
		file = $('#fileVal').val();
		$.ajax({
		method: "POST",
		dataType: "json",
		url: "<?php D(get_link('SendMessageBoard'));?>",
		data: {single_message_id: message_group_id, message: message, attachment: file},
		success: function(data){
			$('#message').val('');
			$('#fileVal').val('');
			$(".emojionearea-editor").html("");
			$('.files').html('');
			$("#send-msg").prop("disabled", false);
			$("#send-msg").html("<?php D(__('message_box_page_Send',"Send"));?>");
			
			clearTimeout(recallAjax);
			load_message_ajax(message_group_id);
		}
		});
	}
}
$(document).on('change','#file', function(){
	var form_data = new FormData();
	var name = document.getElementById('file').files[0];
	form_data.append("fileinput", name);
	$.ajax({
		url:"<?php D(get_link('uploadFileRequestFormCheckAJAXURL'))?>",
		method:"POST",
		data:form_data,
		dataType:'json',
		contentType:false,
		cache:false,
		processData:false,
	}).done(function(data){
		if(data.status=='OK'){
			var file = "<span class='border rounded p-1'>"+data.upload_response.original_name+"</span>";
			$(".files").removeClass("d-none").html(file);
			$("#fileVal").val(JSON.stringify(data.upload_response));
		}else{
			swal({
		      type: 'warning',
		      text: '<?php D(__('popup_global_file_not_suported_error',"Your File Format Extension Is Not Supported."));?>',
		 	});
		}
	});
});

$("#send-offer").click(function(){
	$('#send-offer-modal').modal('show');
	/*receiver_id = seller_id;
	message = $("#message").val();
	file = $("#file").val();
	if(file == ""){
		message_file = file;
	}else{
		message_file = document.getElementById("file").files[0].name;
	}
	$.ajax({
		method: 'POST',
		url: '<?php D(get_link('SendOfferMessageURL'))?>',
		data: {receiver_id: receiver_id, message: message, file: message_file}
	}).done(function(data){
		$("#send-offer-div").html(data);
	});*/
});
$(".custom-control-label").click(function(){
	$("#submit-proposal").removeAttr("disabled");
});
$("#submit-proposal").click(function(){
  var  proposal_id = document.querySelector('#send-offer-modal input[name="proposal_id"]:checked').value;	   
   var title=$('.prow_'+proposal_id).data('title');
   
   $('#submit-proposal-details #offer_proposal_id').val(proposal_id);
   $('#submit-proposal-details #offer_receiver_id').val(seller_id);
   $('#submit-proposal-details #offer_proposal_title').html(title);
});
$("#proposal-details-form").submit(function(event){
	event.preventDefault();
	var formID='proposal-details-form';
	var buttonsection=$('#'+formID).find('.saveBTN');
	var forminput=$('#'+formID).serialize();
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	$.ajax({
        type: "POST",
        url: "<?php D(get_link('SendOfferMessageAjax'))?>",
        data:forminput,
        dataType: "json",
        cache: false,
		success: function(msg) {
			buttonsection.html(buttonval).removeAttr('disabled');
			clearErrors();
			if (msg['status'] == 'OK') {
				$('#submit-proposal-details').modal('hide');
				var message='<?php D(__('popup_message_box_page_Send_proposal_success',"Your request has been submitted successfully!"));?>';
				if(msg['message']){
					message=msg['message'];
				}
				 swal({
                  type: 'success',
                  text: message,
                  timer: 2000,
                  onOpen: function(){
                    swal.showLoading()
                  }
                  }).then(function(){
					clearTimeout(recallAjax);
					load_message_ajax(message_group_id);
                })	
			} else if (msg['status'] == 'FAIL') {
				registerFormPostResponse(formID,msg['errors']);
			}
		}
	})
});



/*setInterval(function(){
	$.ajax({
		method: "POST",
		url: "<?php D(get_link('TypeStatus'));?>",
		data: {seller_id : seller_id, message_group_id: message_group_id,'mode':'receive'}
	}).done(function(data){
		if(data=='typing'){
			$('.typing-status').html("<b class='text-success'><?php D($seller_name);?></b> is "+ data);
		}else{
			$('.typing-status').html('');
		}
		
		 
	});
}, 500);*/

// Javascript Jquery Code To Reload User Typing Status Every half second Code Ends //







</script>
<style>
	.emojionearea-button{
		display:none
	}
</style>