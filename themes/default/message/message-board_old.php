<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid pl-md-5 pr-md-5 p-0">
  <div class="row mr-0 ml-0 mt-sm-0 mt-md-4 mb-md-4 box-inbox">
	<div class="specfic col-md-3 p-md-0">
		<div class="card border-0 rounded-0 m-0">
	  		<div class="card-header bg-transparent inboxHeader">
				<div class="search-bar d-none"><!--- search-bar Starts --->
					<div class="input-group"><!--- input-group Starts --->
	      				<input type="text" class="form-control" placeholder="Search for a username">
	      				<span class="input-group-addon"> <a href="#"><?php D(__('global_Close',"Close"));?></a> </span>
	    			</div><!--- input-group Ends --->
				</div><!--- search-bar Ends --->
    			<div class="dropdown float-left mt-1"><!--- dropdown float-left mt-1 Starts --->
					<a class="dropdown-toggle" href="#" data-toggle="dropdown"><?php D(__('message_board_page_All_Conversations',"All Conversations"));?></a>
					<div class="dropdown-menu">
						<a href="#" class="dropdown-item" id="all"><?php D(__('message_board_page_All_Conversations',"All Conversations"));?></a>
						<a href="#" class="dropdown-item" id="unread"><?php D(__('message_board_page_Unread',"Unread"));?></a>
						<a href="#" class="dropdown-item" id="starred"><?php D(__('message_board_page_Starred',"Starred"));?></a>
						<a href="#" class="dropdown-item" id="archived"><?php D(__('message_board_page_Archived',"Archived"));?></a>
					</div>
				 </div><!--- dropdown float-left mt-1 Ends --->
				<div class="float-right mb-1"><!--- float-right mb-1 Starts --->
					<a href="#" class="text-muted search-icon"> <i class="fa fa-lg fa-search"></i> </a>
				</div><!--- float-right mb-1 Ends --->
 			</div>
  			<div class="card-body p-0">
				<ul class="list-unstyled">
				<?php
				$unread=$starred=$archived=0;
				if($conversation_group){
					foreach($conversation_group as $c=>$group){
						//$group->is_read
						
						$selected=$starred=$archived="";
						if($group->is_read==0 && $group->chatwith!=$group->message_sender){
							$selected="unread ";
							$unread++;
						}
						if($selected_room && $selected_room==$group->conversations_id){
							$selected.="selected";
						}
						$message=strip_tags($group->message);	
						if($group->offer_id){
							$message=__('message_board_page_Sent_you_an_offer','Sent you an offer');	
						}
				?>
					<a href="#" class="message-recipients media border-bottom conversationG_<?php D($group->conversations_id);?> <?php D($selected); ?> <?php D($starred); ?> <?php D($archived); ?>" data-username="<?php D($group->chatmember->member_name);?>" data-id="<?php D($group->conversations_id);?>">
				    <img src="<?php D($group->chatmember->logo);?>" class="rounded-circle mr-3" width="50">
				    <div class="media-body">
				      <h6 class="mt-0 mb-1"><?php D($group->chatmember->member_name);?> 
				      <small class="float-right text-muted"><?php D(get_time_ago($group->sending_date)); ?></small>
				      </h6>
				      <?php D(substr($message,0,40)); ?>
				    </div>
					</a>
				<?php		
					}
				}
				?>
				</ul>
	<?php 
	if($unread==0){
	?>
				<p class="lead mt-5 text-center d-none unreadMsg"><?php D(__('message_board_page_no_Unread','There are no conversations under "Unread".'));?></p>
	<?php } ?>

	<?php
	if($starred == 0){
	?>
				<p class="lead mt-5 text-center d-none starredMsg"><?php D(__('message_board_page_no_Starred','There are no conversations under "Starred".'));?></p>
	<?php } ?>
	<?php
	if($archived == 0){
	?>
				<p class="lead mt-5 text-center d-none archivedMsg"><?php D(__('message_board_page_no_Archived','There are no conversations under "Archived".'));?></p>
	<?php } ?>	

	<?php
	if(!$conversation_group){
	?>
				<p class="lead mt-5 text-center"><?php D(__('message_board_page_no_conversations','There are no conversations are available.'));?></p>
	<?php } ?>
  			</div>
		</div>
	</div>
	
	<div class="specfic col-md-9 p-0">
		<center id="selectConversation" class="mt-5 mt-sm-5">
			<img src="<?php D(theme_url().IMAGE)?>chat.png" width="180" alt="">
			<h3 class="mt-3 empty-heading" style="font-weight:410;"><?php D(__('message_board_page_Select_a_Conversation','Select a Conversation'));?></h3>
			<p class="lead"><?php D(__('message_board_page_Select_a_Conversation_info','Try selecting a conversation or searching for someone specific.'));?></p>
		</center>
		<div id="msgHeader" class="card-header bg-transparent inboxHeader2 d-none">
		</div>
		<div id="showSingle" class="row">
		</div>
	</div>
	
	
  </div>
</div>
<div id="wait"></div>
<div id="upload_file_div"></div>
<div id="accept-offer-div"></div>
<div id="send-offer-div"></div>
<div id="report-modal" class="modal fade"><!-- report-modal modal fade Starts -->
	<div class="modal-dialog"><!-- modal-dialog Starts -->
		<div class="modal-content"><!-- modal-content Starts -->
			<div class="modal-header p-2 pl-3 pr-3"><!-- modal-header Starts -->
				<?php D(__('modal_report_message_heading',"Report This Message"));?>
				<button class="close" data-dismiss="modal">
				<span> &times; </span>
				</button>
			</div><!-- modal-header Ends -->
			<div class="modal-body"><!-- modal-body p-0 Starts -->
				<h6><?php D(__('modal_report_message_text',"Why do you wish to report this message?."));?></h6>
				<form method="post"  align="center" id="reportForm" onsubmit="return performAction(this);return false;">
					<input type="hidden" name="action" value="submit_report"/>
					<div class="form-group mt-3"><!--- form-group Starts --->
						<select class="form-control float-right" name="reason" id="reason">
						<option value=""><?php D(__('modal_report_message_Select_Reason',"Select Reason"));?></option>
						<option><?php D(__('modal_report_message_Reason_option_1',"The user asked for payment or wanted to communicate outside of"));?> <?php D(get_option_value('website_name')); ?>.</option>
						<option><?php D(__('modal_report_message_Reason_option_2',"The user behaved inappropriately"));?></option>
						<option><?php D(__('modal_report_message_Reason_option_3',"The user sent spam"));?></option>
						<option><?php D(__('modal_report_message_Reason_option_4',"Other"));?></option>
						</select>
					</div><!--- form-group Ends --->
					<br>
					<br>
					<div class="form-group mt-1 mb-3"><!--- form-group Starts --->
						<label class="pull-left"> <?php D(__('modal_report_message_Additional_Information',"Additional Information"));?> </label>
						<textarea  rows="3" class="form-control" name="additional_information" id="additional_information"></textarea>
					</div><!--- form-group Ends --->
					<button type="submit" name="submit_report" class="float-right btn btn-sm btn-success saveBTN"><?php D(__('modal_report_message_Submit_Report',"Submit Report"));?></button>
				</form>
			</div><!-- modal-body p-0 Ends -->
		</div><!-- modal-content Ends -->
	</div><!-- modal-dialog Ends -->
</div><!-- report-modal modal fade Ends -->
<div id="send-offer-modal" class="modal fade"><!-- send-offer-modal modal fade Starts -->
	<div class="modal-dialog"><!-- modal-dialog Starts -->
		<div class="modal-content"><!-- modal-content Starts -->
			<div class="modal-header"><!-- modal-header Starts -->
				<h5 class="modal-title"> <?php D(__('modal_send_offer_heading',"Select A Proposal/Service To Offer"));?> </h5>
				<button class="close" data-dismiss="modal">
					<span> &times; </span>
				</button>
			</div><!-- modal-header Ends -->
			<div class="modal-body p-0"><!-- modal-body p-0 Starts -->
				<div class="request-proposals-list"><!--- request-proposals-list Starts --->
				<?php
				if($myproposal){
					foreach($myproposal as $proposal){
					?>
					<div class="proposal-picture prow_<?php D($proposal->proposal_id); ?>" data-title="<?php D($proposal->proposal_title); ?>" ><!--- proposal-picture Starts --->
						<input type="radio" id="radio-<?php D($proposal->proposal_id); ?>" class="radio-custom" name="proposal_id" value="<?php D($proposal->proposal_id); ?>" required>
						<label for="radio-<?php D($proposal->proposal_id); ?>" class="radio-custom-label"> </label>
						<img src="<?php D(URL_USERUPLOAD.'proposal-files/'.$proposal->proposal_image); ?>" width="50" height="50">
					</div><!--- proposal-picture Ends --->
					<div class="proposal-title"><!--- proposal-title Starts --->
						<p><?php D($proposal->proposal_title); ?></p>
					</div><!--- proposal-title Ends --->
					<hr>
					<?php
					}
				}
				?>
				</div><!--- request-proposals-list Ends --->
			</div><!-- modal-body p-0 Ends -->
			<div class="modal-footer"><!--- modal-footer Starts --->
				<button class="btn btn-secondary" data-dismiss="modal"> <?php D(__('global_Close',"Close"));?> </button>
				<button id="submit-proposal" class="btn btn-success" data-toggle="modal" data-dismiss="modal" data-target="#submit-proposal-details" disabled>
				<?php D(__('modal_send_offer_Go_Next',"Go Next"));?>
				</button>
			</div><!--- modal-footer Ends --->
		</div><!-- modal-content Ends -->
	</div><!-- modal-dialog Ends -->
</div><!-- send-offer-modal modal fade Ends -->

<div id="submit-proposal-details" class="modal fade"><!--- modal fade Starts --->
	<div class="modal-dialog"><!--- modal-dialog Starts --->
		<div class="modal-content"><!-- modal-content Starts -->
			<div class="modal-header"><!-- modal-header Starts -->
				<h5 class="modal-title"> <?php D(__('modal_submit_proposal_details_heading',"Specify Your Proposal Details"));?> </h5>
				<button class="close" data-dismiss="modal">
				<span> &times; </span>
				</button>
			</div><!-- modal-header Ends -->
			<div class="modal-body p-0"><!-- modal-body p-0 Starts -->
				<form id="proposal-details-form"><!--- proposal-details-form Starts --->
					<div class="selected-proposal p-3"><!--- selected-proposal p-3 Starts --->
						<h5 id="offer_proposal_title">  </h5>
						<hr>
						<input type="hidden" name="offer_proposal_id" id="offer_proposal_id" value="0">
						<input type="hidden" name="offer_receiver_id" id="offer_receiver_id" value="0">
						<div class="form-group"><!--- form-group Starts --->
							<label class="font-weight-bold"> <?php D(__('modal_submit_proposal_details_Description',"Description :"));?>  </label>
							<textarea name="offer_description" id="offer_description" class="form-control"></textarea>
						</div><!--- form-group Ends --->
						<hr>
						<div class="form-group"><!--- form-group Starts --->
							<label class="font-weight-bold"> <?php D(__('modal_submit_proposal_details_Delivery_Time',"Delivery Time :"));?>  </label>
							<select class="form-control float-right" name="offer_delivery_time" id="offer_delivery_time">
							<?php
							if($all_delivery_times){
								foreach($all_delivery_times as $k=>$delivery_times){
								?>
								<option value="<?php D($delivery_times->delivery_id);?>"><?php D($delivery_times->delivery_proposal_title);?></option>
								<?php
								}
							}
							?>
							</select>
						</div><!--- form-group Ends --->
						<hr>
						<div class="form-group"><!--- form-group Starts --->
							<label class="font-weight-bold"> <?php D(__('modal_submit_proposal_details_Total_Offer_Amount',"Total Offer Amount :"));?>  </label>
							<div class="input-group float-right">
								<span class="input-group-addon font-weight-bold"> <?php D(CURRENCY); ?> </span>
								<input type="number" name="offer_amount" id="offer_amount" class="form-control" placeholder="<?php D(__('modal_submit_proposal_details_Minimum',"5 Minimum"));?>">
							</div>
						</div><!--- form-group Ends --->
					</div><!--- selected-proposal p-3 Ends --->
					<div class="modal-footer"><!--- modal-footer Starts --->
						<button type="button" class="btn btn-secondary" data-dismiss="modal" data-toggle="modal" data-target="#send-offer-modal"><?php D(__('modal_submit_proposal_details_Back',"Back"));?></button>
						<button type="submit" class="btn btn-success saveBTN"><?php D(__('modal_submit_proposal_details_Submit_Offer',"Submit Offer"));?></button>
					</div><!--- modal-footer Ends --->	
				</form><!--- proposal-details-form Ends --->
			</div><!-- modal-body p-0 Ends -->
		</div><!-- modal-content Ends -->
	</div><!--- modal-dialog Ends --->
</div>
<div id="message_template" style="display: none">
	<li href="<?php D(VZ);?>" class="inboxMsg media inboxMsg message_{MESSAGE_ID}">
	{SENDER_LOGO}
    <div class="media-body">
      <h6 class="mt-0 mb-1">
      	{SENDER_NAME} <small class="text-muted">{SENT_DATE}</small>
      {REPORT}
      </h6>
     {SENDER_MESSAGE}
     {ATTACHMENT}
     {OFFER}
  </div>
</li>	
</div>
<div id="offer_template" style="display: none">
	<div class="message-offer card mb-3"><!--- message-offer Starts --->
		<div class="card-header p-2">
			<h6 class="mt-md-0 mt-2">
			{PROPOSAL_TITLE}
			<span class="price float-right d-sm-block d-none"> <?php D(CURRENCY); ?>{OFFER_AMOUNT}</span>
			</h6>
		</div>
		<div class="card-body p-2"><!--- card-body Starts --->
			<p> {OFFER_DESCRIPTION} </p>
			<p class="d-block d-sm-none"> <b> <?php D(__('message_board_page_offer_template_price','Price / Amount :'));?> </b> {OFFER_AMOUNT} </p>
			<p> <b> <i class="fa fa-calendar"></i> <?php D(__('message_board_page_offer_template_Delivery_time','Delivery Time :'));?> </b> {DELIVERY_TIME} </p>
		{OFFER_ACTION}
		</div>
	</div>
</div>
<script type="text/javascript">
var from=0;
var recallAjax;
var SPINNER='<?php load_view('inc/spinner',array('size'=>50));?>';
var image_type=['jpg','jpeg','png','gif','bnp'];
function msgHeader(message_group_id){
	clearTimeout(recallAjax);
	from=0;
	$("#wait").addClass("loader");
		$.ajax({
		method:'POST',
		url: "<?php D(get_link('MessageBoardPartLoad'))?>",
		data: {message_group_id:message_group_id,part:'head'},
		success: function(data){
			var new_url ='<?php D(get_link('MessageBoard'))?>'+"/"+message_group_id;
			var title=$('.conversationG_'+message_group_id).data('username');
			window.history.pushState("data",title,new_url);
			document.title=title;
			$("#msgHeader").html(data);
			showSingle(message_group_id);
		}
	});
}
function showSingle(message_group_id){
	$.ajax({
		method: "POST",
		url: "<?php D(get_link('MessageBoardPartLoad'))?>",
		data: {message_group_id:message_group_id,part:'body'},
		success: function(server_response){
			$("#selectConversation").hide();
			$("#msgHeader").removeClass("d-none");
			$("#showSingle").html(server_response);
			$("#wait").removeClass("loader");
			if ( $(window).width() > 767) {
			// Add your javascript for large screens here 
			}else {
				$('.specfic.col-md-3').hide();
				$('.specfic.col-md-9,.specfic.col-md-12').show();
				$('.specfic.col-md-9').attr("class","specfic col-md-12");
				$('#msgSidebar').hide();
			}
			load_conversation(message_group_id);
		}
	});
}
function getFileExtension(filename)
{
  var ext = /^.+\.([^.]+)$/.exec(filename);
  return ext == null ? "" : ext[1];
}
function load_conversation(room_id){
	$( "#load_conversation").html( '<div class="loadermore"><div class="text-center padding-top-50 padding-bottom-50">'+SPINNER+'<div></div>' );
	load_message_ajax(room_id);
}
function load_message_ajax(room_id){
	//$( "#load_conversation .loadermore").show();
	var template_default=$('#message_template').html();
	var offer_default=$('#offer_template').html();
	var template='';
	var offer='';
	var url = "<?php D(get_link('MessageBoardLoadAjax'))?>/"+room_id;
	$.get(url,{'from':from},function(res){
		if (res['status'] == 'OK') {
				$('.typing-status').html('');
				if (res['conversation'].length>0) {
					//console.log(res['conversation']);
					for (x in res['conversation']) {
						if($( "#load_conversation .message_"+res['conversation'][x] ['message_id']).length==0){	
						var serder_by='';
						template=template_default;
						
						if(res['conversation'][x] ['sender_id']=='<?php D($log_member_id)?>'){
							serder_by='me';
							serder_logo=my_logo;
							template=template.replace(/{REPORT}/g,'');
							template=template.replace(/{SENDER_NAME}/g,'Me');
							
						}else{
							is_read='';
							serder_logo=chatwith_logo;
							template=template.replace(/{SENDER_NAME}/g,chatwith_name);
							template=template.replace(/{REPORT}/g,'<small>| <a href="#" data-toggle="modal" data-target="#report-modal" class="text-muted"><small><i class="fa fa-flag"></i> <?php D(__('message_board_page_Report','Report'));?></small></a> </small>');
						}
						
						template=template.replace(/{MESSAGE_ID}/g, res['conversation'][x] ['message_id']);
						template=template.replace(/{SENDER_LOGO}/g, '<img src="'+serder_logo+'" alt="" class="rounded-circle mr-3" width="40"/>');
						if(res['conversation'][x] ['attachment']){
							var ext=getFileExtension(res['conversation'][x] ['attachment']);
							var checkext = ext.toLowerCase();
							var inarray=image_type.indexOf(checkext);
							if(inarray > -1){
								var messagecontent='<br><a download class="d-block mt-2 ml-1" target="_blank" href="<?php D(URL_USERUPLOAD.'message-files/')?>'+res['conversation'][x] ['attachment']+'"><i class="fa fa-download"></i> <img src="<?php D(URL_USERUPLOAD.'message-files/')?>'+res['conversation'][x] ['attachment']+'" alt="" Class="img-thumbnail" width=150/></a>'
							}else{
								var messagecontent=' <br><a download class="d-block mt-2 ml-1" target="_blank" href="<?php D(URL_USERUPLOAD.'message-files/')?>'+res['conversation'][x] ['attachment']+'"><i class="fa fa-download"></i> '+'<img src="<?php echo theme_url().IMAGE;?>file-type/'+ext+'.svg" alt="" width=50 />'+'</a>';
							}
							template=template.replace(/{ATTACHMENT}/g,messagecontent);
						}else{
							template=template.replace(/{ATTACHMENT}/g,'');
						}
						if(res['conversation'][x] ['offer_id']){
							offer=offer_default;
							offer=offer.replace(/{PROPOSAL_TITLE}/g,res['conversation'][x] ['proposal_title']);
							offer=offer.replace(/{OFFER_AMOUNT}/g,res['conversation'][x] ['amount']);
							offer=offer.replace(/{OFFER_DESCRIPTION}/g,res['conversation'][x] ['description']);
							offer=offer.replace(/{DELIVERY_TIME}/g,res['conversation'][x] ['delivery_time']+' days');
							
							if(res['conversation'][x] ['status']==0){
								var offeraction="";
								if(res['conversation'][x] ['offer_sender']!='<?php D($log_member_id)?>'){
									offeraction='<button data-offer="'+res['conversation'][x] ['offer_id']+'" id="accept-offer-'+res['conversation'][x] ['offer_id']+'" class="btn btn-success float-right acceptOfferBTN"><?php D(__('message_board_page_Accept_Offer','Accept Offer'));?></button>';
								}
							}else if(res['conversation'][x] ['status']==1){
								var offeraction='<button class="btn btn-success rounded-0 mt-2 float-right" disabled><?php D(__('message_board_page_Offer_Accepted','Offer Accepted'));?></button><a href="<?php D(get_link('OrderDetailsURL'))?>'+res['conversation'][x] ['order_id']+'" class="mt-3 mr-3 float-right text-success"><?php D(__('message_board_page_View_Order','View Order'));?></a>';
							}
							offer=offer.replace(/{OFFER_ACTION}/g,offeraction);
							
							template=template.replace(/{OFFER}/g,offer);
						}else{
							template=template.replace(/{OFFER}/g,'');
						}
						
						
						template=template.replace(/{SENDER_MESSAGE}/g,res['conversation'][x] ['message']);
						
						
						template=template.replace(/{SENT_DATE}/g,res['conversation'][x]['sending_date']);
						template=template.replace(/{SENT_BY}/g,serder_by);
						$( "#load_conversation .loadermore").before(template);
						}
					}
					myscroll();
				}
				if(res['last_data']['message_id']){
					//console.log(res['last_data']);
					from=res['last_data']['message_id'];
				}
			} else if (res['status'] == 'FAIL') {
				template='Load failed';
			}
			$( "#load_conversation .loadermore").hide();
			//$( "#load_conversation").niceScroll();
			//reinit(room_id);
			recallAjax = setTimeout(function(){load_message_ajax(room_id);},2000);
	},'JSON');
}

function myscroll(){
	//$('html,body').animate({scrollTop: document.body.scrollHeight},"fast");	
	$('#load_conversation').stop().animate({
		  scrollTop: $('#load_conversation')[0].scrollHeight
	}, 800);	
}
function addRemoveSelected(select){
  $(".col-md-3 .message-recipients").removeClass("selected");
  $(select).addClass("selected");
}
$(document).ready(function(){	
	var showMessages;
	var typeStatus;
	$(document).on('click', '.closeMsg', function(e){
		event.preventDefault();
		$(".specfic.col-md-3").show();
		$(".specfic.col-md-12").hide();
	});
	$(document).on('click', '.message-recipients', function(e){
		var message_group_id = $(this).data("id");
		window.location.href="<?php D(get_link('MessageBoard'))?>"+"/"+message_group_id;
		//addRemoveSelected(this);
		//msgHeader(message_group_id);
	});
	$('#all').click(function(){
		$(".inboxHeader .dropdown-toggle").html("<?php D(__('message_board_page_All_Conversations','All Conversations'));?>");
		$(".dropdown-menu a").attr('class','dropdown-item');
		$("#all").attr('class','dropdown-item active');
		$(".message-recipients").show();
		$(".unreadMsg").addClass("d-none");
		$(".archivedMsg").addClass("d-none");
		$(".starredMsg").addClass("d-none");
	});
	$('#unread').click(function(){
		$(".inboxHeader .dropdown-toggle").html("<?php D(__('message_board_page_Unread','Unread'));?>");
		$(".dropdown-menu a").attr('class','dropdown-item');
		$("#unread").attr('class','dropdown-item active');
		$(".message-recipients").hide();
		$(".unread").show();
		$(".unreadMsg").removeClass("d-none");
		$(".archivedMsg").addClass("d-none");
		$(".starredMsg").addClass("d-none");
	}); 
	$('#starred').click(function(){
		$(".inboxHeader .dropdown-toggle").html("<?php D(__('message_board_page_Starred','Starred'));?>");
		$(".dropdown-menu a").attr('class','dropdown-item');
		$("#starred").attr('class','dropdown-item active');
		$(".message-recipients").hide();
		$(".starred").show();
		$(".archivedMsg").addClass("d-none");
		$(".unreadMsg").addClass("d-none");
		$(".starredMsg").removeClass("d-none");
	}); 
	$('#archived').click(function(){
		$(".inboxHeader .dropdown-toggle").html("<?php D(__('message_board_page_Unread','Unread'));?>");
		$(".dropdown-menu a").attr('class','dropdown-item');
		$("#archived").attr('class','dropdown-item active');
		$(".message-recipients").hide();
		$(".archived").show();
		$(".unreadMsg").addClass("d-none");
		$(".starredMsg").addClass("d-none");
		$(".archivedMsg").removeClass("d-none");
	}); 
	$('.search-icon').click(function(){
		$(".search-bar").removeClass("d-none");
		$(".inboxHeader .float-left").addClass("d-none");
		$(".inboxHeader .float-right").addClass("d-none");
	});
	$('.search-bar input').on('keyup', function() {
		var searchVal = $(this).val();
		var filterItems = $('[data-username]');
		if ( searchVal != '' ) {
			filterItems.addClass('d-none');
			$('[data-username*="' + searchVal.toLowerCase() + '"]').removeClass('d-none');
		} else {
			filterItems.removeClass('d-none');
		}
	});
	$('.search-bar a').click(function(){
		$(".search-bar").addClass("d-none");
		$(".search-bar input").val("");
		$(".float-left").removeClass("d-none");
		$(".float-right").removeClass("d-none");
		$('[data-username]').removeClass('d-none');
	});
});

</script>
<?php if($selected_room){?>
<script type="text/javascript">
$(window).on('load', function () {
	var loadchat=setTimeout(function(){ msgHeader(<?php D($selected_room); ?>);},1000);
	
})

</script>
<?php }?>
