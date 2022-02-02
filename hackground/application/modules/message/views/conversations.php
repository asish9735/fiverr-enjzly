 <style>
 .card {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: .25rem;
}
.card {
    margin-bottom: 1.5rem;
    border-radius: 0;
}
.card-body {
    padding: 15px;
}
 .card-header {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    background-color: rgba(0,0,0,.03);
    border-bottom: 1px solid rgba(0,0,0,.125);
}
 .message-div {
    border: 1px solid #e6e6e6;
    padding: 12px 20px 10px 20px;
}
.message-div .message-image {
    float: left;
    width: 45px;
    height: 45px;
    margin-right: 12px;
    border-radius: 50%;
}
.message-div .message-desc {
    margin-left: 56px;
}
.text-muted {
    color: #868e96!important;
}
.float-right {
    float: right!important;
}
.order-status-message {
	
text-align:center;	

padding:25px 20px;

	
}
.message-div .message-offer {
	background:#fafafa;
	border:1px solid #e5e5e5;
	padding:12px 20px 20px 20px;
	margin-bottom:6px;
} 

.message-div .message-offer .price{
	font-size:36px;
	color:#28a745;
} 

.message-div .message-offer p{
	margin-bottom:8px;
}

 </style>
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         <?php echo $main_title ? $main_title : '';?>
        <small><?php echo $second_title ? $second_title : '';?></small>
      </h1>
     <?php echo $breadcrumb ? $breadcrumb : '';?>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo $title ? $title : '';?></h3>

          <div class="box-tools pull-right">
			Room Id: #<?php echo $conversation_details->conversations_id; ?>
          </div>
        </div>

        <div class="card">
            <!--- card Starts --->
            <div class="card-header">
                <!--- card-header Starts --->
                <h4 class="h4">
                    <i class="fa fa-money-bill-alt"></i> Order Conversation Between <?php echo $conversation_details->sender->member_name; ?> & <?php echo $conversation_details->receiver->member_name; ?>
                </h4>
            </div>
            <!--- card-header Ends --->
            <div class="card-body">
                <!--- card-body Starts --->
                <?php
                $currency=get_setting('site_currency');
			if($conversation_details->conversations){
				foreach($conversation_details->conversations as $k=>$conversation){
					//print_r($conversation);
					/*$status=$conversation->status;*/
					if($conversation->sender_id==$conversation_details->sender_id){
						$sender_user_name=$conversation_details->sender->member_name;
					}else{
						$sender_user_name=$conversation_details->receiver->member_name;
					}
				?>

				 <div class="message-div">
                        <img src="<?php echo getMemberLogo($conversation->sender_id); ?>" class="img-responsive message-image">
                        <h5><?php echo $sender_user_name; ?><span class="float-right text-muted" style="font-weight: normal;">
                            <?php echo date('F d, Y H:i:s',strtotime($conversation->sending_date)); ?> 
                        </span></h5>
                        <p class="message-desc">
                            <?php echo html_entity_decode($conversation->message); ?>
                           <?php if(!empty($conversation->attachment)){ ?>
                            <a href="<?php echo USER_UPLOAD.'message-files/'.$conversation->attachment?>" download class="d-block mt-2 ml-1">
                                <i class="fa fa-download"></i> <?php echo $conversation->attachment; ?>
                            </a>
                            <?php }?>
                        </p>
                        
						<?php if($conversation->offer_id){ ?>
                        <div class="message-offer" style="clear: both"><!--- message-offer Starts --->
                        <div class="row"><!--- row Starts --->
                            <div class="col-lg-2 col-md-3"><!--- col-lg-2 col-md-3 Starts --->
                                <img src="<?php echo USER_UPLOAD.'proposal-files/'.$conversation->proposal_image?>" class="img-responsive message-image" style="    width: 150px;height: 100%; border-radius: 0px;">
                            </div><!--- col-lg-2 col-md-3 Ends --->
                            <div class="col-lg-10 col-md-9"><!--- col-lg-10 col-md-9 Starts --->
                                <h5 class="mt-md-0 mt-2">
                                    <?php echo $conversation->proposal_title; ?>
                                    <span class="price float-right d-sm-block d-none"> <?php echo $currency; ?> <?php echo $conversation->amount; ?> </span>
                                </h5>
                                <p><?php echo $conversation->description; ?></p>
                                <p class="d-block d-sm-none"> <b> Price / Amount : </b> <?php echo $currency; ?><?php echo $conversation->amount; ?> </p>
                                <p><b>Delivery Time : </b> <?php echo $conversation->delivery_time; ?>days </p>
                        
                            <?php if($conversation->status != 1){ ?>
                                <button class="btn btn-success rounded-0 mt-2 float-right">
                                    Offer has not been accepted yet.
                                </button>
                            <?php }elseif($conversation->status == 1){ ?>
                                <p class="float-right">
                                    <a class="btn btn-outline-site mr-2" href="<?php echo base_url('orders/order_detail')?>/<?php echo $conversation->order_id; ?>" target="_blank">
                                    View Order
                                    </a>
                                    <button class="btn btn-success" disabled>
                                        Offer Accepted
                                    </button>
                                </p>
                            <?php } ?>
                            </div><!--- col-lg-10 col-md-9 Ends --->
                        </div><!--- row Ends --->
                        </div><!--- message-offer Ends --->
                        <?php } ?>

                    </div>	
				<?php	
				}
			}else{
			?>
			<h3 class='text-center'>This Order Has No Conversations.</h3>
			<?php
			}?>

            </div>
            <!--- card-body Ends --->

        </div>
        <!--- card Ends --->
	</div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
<style>

.v-middle tr td{
	vertical-align: middle !important;
}

.v-middle tr td span.hightlight{
	    font-weight: bold;
  
}
</style>  
  
<div class="modal fade" id="ajaxModal">
	  <div class="modal-dialog">
		<div class="modal-content">
		 
		</div>
	  </div>
</div>

<script>

function updateWalletBalance(wallet_id){
	if(wallet_id){
		$.ajax({
			url : '<?php echo base_url('wallet/update_wallet');?>',
			data: {wallet_id: wallet_id, cmd: 'update_origional'},
			type: 'POST',
			dataType: 'JSON',
			success: function(res){
				if(res.status == 1){
					location.reload();
				}
			}
		});
	}
}

function view_txn_detail(txn_id){
	Modal.openURL({
		title : 'Transaction Detail',
		url: '<?php echo base_url($curr_controller.'load_ajax_page?page=single_txn_detail');?>&id='+txn_id+'&wallet_id=<?php echo $wallet_id;?>'
	});
}

function add(){
	var url = '<?php echo base_url($curr_controller.'load_ajax_page?page='.$add_command);?>';
	load_ajax_modal(url);
}

function edit(id){
	var url = '<?php echo base_url($curr_controller.'load_ajax_page?page='.$edit_command);?>&id='+id;
	load_ajax_modal(url);
}

function deleteRecord(id, permanent){
	permanent = permanent || false;
	var c = confirm('Are you sure to delete this record ?');
	if(c){
		console.log('ok');
		var url = '<?php echo base_url($curr_controller.'delete_record');?>/'+id;
		if(permanent){
			url += '?cmd=remove';
		}
		$.getJSON(url, function(res){
			if(res.cmd && res.cmd == 'reload'){
				location.reload();
			}
		});
	}else{
		return false;
	}
}

function changeStatus(sts, id, ele){
	var status = [1, 0];
	if(status.indexOf(sts) !== -1){
		var url = '<?php echo base_url($curr_controller.'change_status');?>';
		$.ajax({
			url : url,
			data: {ID: id, status: sts},
			type: 'POST',
			dataType: 'json',
			success: function(res){
				if(res.cmd){
					if(res.cmd == 'reload'){
						location.reload();
					}else if(res.cmd == 'replace'){
						if(typeof ele !== 'undefined'){
							$('[data-toggle="tooltip"]').tooltip("destroy");
							$(ele).replaceWith(res.data.html);
							init_plugin();
						}
					}
				}
				
			}
		});
	}
	return false;
}

function changeStatusAll(sts){
	var data = $('#main_table').find('input').serialize();
	var status = [1, 0];
	if(status.indexOf(sts) !== -1){
		data += '&status=' + sts;
		data += '&action_type=multiple';
		var url = '<?php echo base_url($curr_controller.'change_status');?>';
		$.ajax({
			url : url,
			data: data,
			type: 'POST',
			dataType: 'json',
			success: function(res){
				if(res.cmd){
					if(res.cmd == 'reload'){
						location.reload();
					}else if(res.cmd == 'replace'){
						if(typeof ele !== 'undefined'){
							$('[data-toggle="tooltip"]').tooltip("destroy");
							$(ele).replaceWith(res.data.html);
							init_plugin();
						}
					}
				}
				
			}
		});
	}
	return false;
}

function deleteSelected(){
	var c = confirm('Are you sure to delete selected record ?');
	if(c){
		var data = $('#main_table').find('input').serialize();
		data += '&action_type=multiple';
		var url = '<?php echo base_url($curr_controller.'delete_record');?>';
		$.ajax({
			url : url,
			data: data,
			type: 'POST',
			dataType: 'json',
			success: function(res){
				if(res.cmd){
					if(res.cmd == 'reload'){
						location.reload();
					}
				}
				
			}
		});
	}
	
	return false;
}

function init_event(){
	
	var item  = $('.check_all_main').data('target');
	
	$(item).on('change', function(){
		checkSelected();
	});
	
	$('.check_all_main').on('change', function(){
		var is_checked = $(this).is(':checked');
		var target = $(this).data('target');
		if(is_checked){
			$(target).prop('checked', true);
		}else{
			$(target).prop('checked', false);
		}
		$(target).triggerHandler('change');
	});
	
	function checkSelected(){
		var target  = $('.check_all_main').data('target');
		var l = $(target + ':checked').length;
		if(l == 0){
			$('#global_action_btn').find('button').attr('disabled', 'disabled');
			$('#global_action_btn').hide();
		}else{
			$('#global_action_btn').find('button').removeAttr('disabled');
			$('#global_action_btn').show();
		}
	} 
}

$(function(){
	
	init_plugin(); /* global.js */
	init_event();
	
	
});
</script>
