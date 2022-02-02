<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?php D(theme_url().JS)?>vue.js"></script>
<script src="<?php D(theme_url().JS)?>vue-infinite-loading.js"></script>
<script type="text/javascript" src="<?php D(theme_url().JS)?>moment-with-locales.js"></script>
<script type="text/javascript" src="<?php D(theme_url().JS)?>jquery.nicescroll.min.js"></script>

<!-- Dashboard Container -->

<div class="dashboard-container"> 
  <!-- Dashboard Content -->
  <div class="dashboard-content-container">
    <div class="dashboard-content-inner"> 
      
      <!-- Dashboard Headline
			<div class="dashboard-headline">
				<h3>Messages</h3>				
			</div> -->
      
      <div class="messages-container margin-top-0">
        <div class="messages-container-inner" id="message-app"> 
          
          <!-- Messages -->
          <chat-list v-on:set-chat="setActiveChat" :active_chat="active_chat" :last_time="lastMessage" :new_message_received="lastMessageReceived" :login_user="login_user"></chat-list>
          <!-- Messages / End --> 
          
          <!-- Message Content -->
          <div class="message-content justify-content-center">
            <div v-if="active_chat">
              <active-chat-header :active_chat="active_chat"></active-chat-header>
              <active-chat-body ref="chatbody"  :active_chat="active_chat" :login_user="login_user" v-on:update-message="updateMessage" :new_message_received="lastMessageReceived" v-on:last-seen-msg="updateLastSeenMsg"></active-chat-body>
            </div>
            
            <div class="text-center" v-else> <img src="<?php echo theme_url().IMAGE;?>communication.png" alt="">
              <p class="text-muted"><i class="icon-feather-message-circle"></i> <i><?php echo __('message_select_a_chart','Select a chat to view conversation');?></i></p>
            </div>            
          </div>
          <!-- Message Content --> 
          
        </div>
      </div>
      <!-- Messages Container / End --> 
      
    </div>
  </div>
</div>
<?php 
$templateLayout=array('view'=>'message-template','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
load_template($templateLayout,$data);
?>
<div id="accept-offer-div"></div>
<div id="send-offer-modal" class="modal fade"><!-- send-offer-modal modal fade Starts -->
<div class="modal-dialog"><!-- modal-dialog Starts -->
	<div class="modal-content"><!-- modal-content Starts -->
		<div class="modal-header"><!-- modal-header Starts -->
			<h4 class="modal-title"> <?php D(__('modal_send_offer_heading',"Select A Proposal/Service To Offer"));?> </h4>
			<button class="close" data-dismiss="modal">
				<span> &times; </span>
			</button>
		</div><!-- modal-header Ends -->
		<div class="modal-body p-0"><!-- modal-body p-0 Starts -->
			<ul class="request-proposals-list"><!--- request-proposals-list Starts --->
			<?php
			if($myproposal){
				foreach($myproposal as $proposal){
				?><!--- proposal-picture Starts --->
				<li>
					<div class="custom-control custom-radio prow_<?php D($proposal->proposal_id); ?>" data-title="<?php D($proposal->proposal_title); ?>">
					<input type="radio" class="custom-control-input" id="radio-<?php D($proposal->proposal_id); ?>" name="proposal_id" value="<?php D($proposal->proposal_id); ?>" required>
					<label class="custom-control-label" for="radio-<?php D($proposal->proposal_id); ?>">
					<img src="<?php D(URL_USERUPLOAD.'proposal-files/'.$proposal->proposal_image); ?>" alt="" class="mr-2" width="48" height="32"> <?php D($proposal->proposal_title); ?>
					</label>
					</div>            
				</li>        
				<!--- proposal-title Ends --->
				<?php
				}
			}
			?>
			</ul>
			<!--- request-proposals-list Ends --->
		</div><!-- modal-body p-0 Ends -->
		<div class="modal-footer"><!--- modal-footer Starts --->
			<button id="submit-proposal" class="btn btn-site" data-toggle="modal" data-dismiss="modal" data-target="#submit-proposal-details" disabled>
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
			<h4 class="modal-title"> <?php D(__('modal_submit_proposal_details_heading',"Specify Your Proposal Details"));?> </h4>
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
						<label class="font-weight-medium"> <?php D(__('modal_submit_proposal_details_Description',"Description :"));?>  </label>
						<textarea name="offer_description" id="offer_description" class="form-control"></textarea>
					</div><!--- form-group Ends --->
					<div class="form-group clearfix "><!--- form-group Starts --->
						<label class="font-weight-medium float-left"> <?php D(__('modal_submit_proposal_details_Delivery_Time',"Delivery Time :"));?>  </label>
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
					<div class="form-group clearfix"><!--- form-group Starts --->
						<label class="font-weight-medium float-left"> <?php D(__('modal_submit_proposal_details_Total_Offer_Amount',"Total Offer Amount :"));?>  </label>
						<div class="input-group form-curb float-right">
							<div class="input-group-prepend"><span class="input-group-text"><?php D(CURRENCY); ?></span></div>
							<input type="number" name="offer_amount" id="offer_amount" class="form-control" placeholder="<?php D(__('modal_submit_proposal_details_Minimum',"5 Minimum"));?>">
						</div>
					</div><!--- form-group Ends --->
				</div><!--- selected-proposal p-3 Ends --->
				<div class="modal-footer"><!--- modal-footer Starts --->
					<button type="button" class="btn btn-secondary" data-dismiss="modal" data-toggle="modal" data-target="#send-offer-modal"><?php D(__('modal_submit_proposal_details_Back',"Back"));?></button>
					<button type="submit" class="btn btn-site saveBTN"><?php D(__('modal_submit_proposal_details_Submit_Offer',"Submit Offer"));?></button>
				</div><!--- modal-footer Ends --->	
			</form><!--- proposal-details-form Ends --->
		</div><!-- modal-body p-0 Ends -->
	</div><!-- modal-content Ends -->
</div><!--- modal-dialog Ends --->
</div>
<!-- Dashboard Container / End --> 

<script>
var SPINNER='<?php load_view('inc/spinner',array('size'=>50));?>';	
$('document').ready(function(){


	/**
	active_chat : {
	avatar:  {String} user logo,
	name: {String} name,
	message: {String} message,
	time: {Number} time in milliseconds,
	online_status: {Boolean} true|false,
},
	*/
	var seller_id = <?php echo $active_chat ? $active_chat->member_id : '0'; ?>;
	var message_group_id = <?php echo $active_chat ? $active_chat->conversations_id : '0'; ?>;
	var login_user = <?php echo !empty($login_member) ? json_encode($login_member) : 'null'; ?>;
	var App = new Vue({
		el: '#message-app',
		data: {
			active_chat: <?php echo $active_chat ? json_encode($active_chat) : 'null'; ?>,
			login_user: login_user,
			lastMessage: new Date().getTime(),
			lastMessageReceived: new Date().getTime(),
			newMessgeOffer:[]
		},
		methods: {
			setActiveChat: function(d){
				this.active_chat = d;
				seller_id=d.member_id;
				message_group_id=d.conversations_id;
			},
			updateMessage: function(){
				this.lastMessage = new Date().getTime();
			},
			updateLastSeenMsg: function(last_seen_msg){
				this.active_chat.last_seen_msg = last_seen_msg;
			},
			
		}
	});
	App.$on('newmessageoffer', function(data){
		App.$refs.chatbody.updateMessage(data);
	});
	AppService.on('new_message', function(data){
		if(data > 0){
			App.lastMessageReceived = new Date().getTime();
		}
	});
	
	AppService.on('msg_seen_update', function(data){
		if(data.last_message_id != 'undefined'){
			if(App.active_chat && data.conversations_id == App.active_chat.conversations_id){
				App.updateLastSeenMsg(data.last_message_id);
				$.post('<?php echo base_url('message_new/reset_msg_seen')?>');
				App.updateMessage();
			}
		}
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
						App.$emit('newmessageoffer', msg.message_data);
					})	
				} else if (msg['status'] == 'FAIL') {
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})
	});
})

</script>