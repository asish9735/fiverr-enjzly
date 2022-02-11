<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//dd($proposal_details,true);

?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">

<div class="breadcrumbs">
	<div class="container-fluid">
		<h1><?php D(__('edit_proposal_page_heading',"Edit This Proposal"));?></h1>
	</div>
</div>

<section class="section">
<div class="container-fluid">
	<div class="row">
      <div class="col-lg-auto col-12">
        <?php
          $templateLayout=array('view'=>'inc/user-nav','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
          load_template($templateLayout,$data);
        ?>
      </div>
      <div class="col-lg col-12">
     	<?php /*?><ul class="nav nav-tabs">

            <li class="nav-item"> <a class="nav-link <?php //if($tab==''){ echo " active"; } ?> active" data-toggle="tab" href="#details">

              <?php D(__('edit_proposal_page_tab_details',"Proposal Details"));?>

              </a> </li>

            <li class="nav-item  d-none "> <a class="nav-link <?php if($tab=='price'){ echo " active"; } ?>" data-toggle="tab" href="#pricing"> Proposal Pricing </a> </li>

            <li class="nav-item  d-none "> <a class="nav-link <?php if($tab=='extra'){ echo " active"; } ?>" data-toggle="tab" href="#extras"> Extras </a> </li>

          </ul><?php */?>              

        <div class="tab-content">

          <div class="tab-pane fade <?php //if($tab==''){ echo "show active"; } ?> show active" id="details">

            <?php D($edit_proposal_tab_details);?>

          </div>

          <div class="tab-pane fade <?php if($tab=='price'){ echo "show active"; } ?>" id="pricing">

            <?php //D($edit_proposal_tab_price);?>

          </div>

          <!--- Add Ons Starts --->

          

          <div class="tab-pane fade <?php if($tab=='extra'){ echo "show active"; } ?>" id="extras"><!--- Add Ons Tab Starts --->

            <?php //D($edit_proposal_tab_extra);?>

          </div>

          <!--- Add Ons Tab Ends ---> 

          

        </div>

      </div>
	  </div>
</div>
</div>
</section>



<div id="insertimageModal" class="modal" role="dialog">

  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

    <div class="modal-content mycustom-modal">

      <div class="modal-header">

        <button type="button" class="btn btn-dark pull-left" data-dismiss="modal" onclick="$('#proposal_img1').val('');">

        <?php D(__('global_Close',"Close"));?>

        </button>

        <h4 class="modal-title">

          <?php D(__('modal_insertimage_heading',"Crop & Insert Image"));?>

        </h4>

        <button class="btn btn-site crop_image pull-right">

        <?php D(__('modal_insertimage_Crop_Image',"Crop Image"));?>

        </button>

      </div>

      <div class="modal-body">

        <input type="hidden" name="img_type" value="">

        <div id="image_demo" style="width:100% !important;"></div>

      </div>

    </div>

  </div>

</div>

<div id="wait"></div>

<script>

var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';

$(document).ready(function(){

$("#proposal_enable_referrals").change(function(){

	var value = $(this).val();

	if(value == "1"){

		$('.proposal_referral_money').show();

	}else if(value == "0"){

		$('.proposal_referral_money').hide();	

	}

});

$(".pricing").change(function(){

	var value = $(this).val();

	if(value == "1"){

		$('.packages').hide();

		$('.add-attribute').hide();

		$('.proposal-price').show();

	}else if(value == "0"){

		$('.packages').show();

		$('.add-attribute').show();

		$('.proposal-price').hide();	

	}

});	



$(".insert-attribute").on('click', function(){

	var attrcount=$('.newattribute').length;

	//$('#wait').addClass("loader");

	var attribute_name = $('.attribute-name').val();

	if(attribute_name){

		attrcount=attrcount+1;

		$('.time_row').each(function(){

			var id=$(this).data('row-id');

			var html='<div class="form-field newattribute attribute_'+attrcount+'" data-attr-id="'+attrcount+'"><label >'+attribute_name+'<input type="hidden" name="attribute_count[]" value="'+attrcount+'"><input type="hidden" name="attribute_name_'+attrcount+'[]" value="'+attribute_name+'"></label><div class="input-group"><input class="form-control attribute-value-'+id+'" value="" data-attribute="'+attrcount+'" name="attribute_value_'+id+'_'+attrcount+'" id="attribute_value_'+id+'_'+attrcount+'"><div class="input-group-append"><button type="button" class="btn btn-outline-danger delete-attribute" data-attribute="'+attrcount+'" onclick="$(\'.attribute_'+attrcount+'\').remove();"><i class="fa fa-trash"></i></button></div></div></div>';

			

			$(this).before(html);

		})

		$(".attribute-name").val('');

	}

});	

$(".save-package").click(function(){

	$('#wait').addClass("loader");

	var package_id = $(this).data("package");

	var description = $('.description-value-'+package_id).val();

	var delivery_time = $('.delivery-time-value-'+package_id).val();

	var price = $('.price-value-'+package_id).val();

	$.ajax({

		method: "POST",

		url: "<?php D(get_link('editproposalURLAJAX'))?>",

		dataType: "json",

        cache: false,

		data: { 

			tab:'savepackage',description : description, delivery_time : delivery_time , price : price , package_id: package_id,pid:"<?php D($proposal_details['proposal']->proposal_id);?>",token:"<?php D($token);?>" 

		},

		success: function(msg) {

			$('#wait').removeClass("loader");

			if (msg['status'] == 'OK') {

				 swal({

                  type: 'success',

                  text: 'Proposal package saved successfully!',

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                });

			} else if (msg['status'] == 'FAIL') {

				swal({

                  type: 'warning',

                  text: 'Fail to update',

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                });

			}

		}

	});

});



$(".save-attribute").on('click', function(){

	$('#wait').addClass("loader");

	var attribute_id = $(this).data("attribute");

	var attribute_value = $('.attribute-value-'+attribute_id).val();

	$.ajax({

		method: "POST",

		url: "<?php D(get_link('editproposalURLAJAX'))?>",

		dataType: "json",

        cache: false,

		data: { attribute_value : attribute_value, attribute_id: attribute_id, tab:'saveattribute',pid:"<?php D($proposal_details['proposal']->proposal_id);?>",token:"<?php D($token);?>" },

		success: function(msg) {

			$('#wait').removeClass("loader");

			if (msg['status'] == 'OK') {

				 swal({

                  type: 'success',

                  text: 'Attribute saved successfully!',

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                });

			} else if (msg['status'] == 'FAIL') {

				swal({

                  type: 'warning',

                  text: 'Fail to update',

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                });

			}

		}

	});

});

$(".delete-attribute").on('click', function(){

	$('#wait').addClass("loader");

	var attribute_name = $(this).data("attribute");

	var proposal_id = <?php D($proposal_details['proposal']->proposal_id);?>;

	$.ajax({

		url: "<?php D(get_link('editproposalURLAJAX'))?>",

		method: "POST",

		dataType: "json",

        cache: false,

		data: {attribute_name: attribute_name , tab:'deleteattribute',pid:"<?php D($proposal_details['proposal']->proposal_id);?>",token:"<?php D($token);?>" },

		success:function(msg){

			$('#wait').removeClass("loader");

			if (msg['status'] == 'OK') {

				 swal({

                  type: 'success',

                  text: 'Attribute deleted successfully!',

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                }).then(function(){

                  	window.location.href=msg['redirect'];

                })

			} else if (msg['status'] == 'FAIL') {

				swal({

                  type: 'warning',

                  text: 'Fail to update',

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                });

			}

		}

	});

});

$('textarea:first').summernote({

        placeholder: '<?php D(__('edit_proposal_page_textarea_input',"Write Your Description Here."));?>',

        height: 150,

        toolbar: [

        ['style', ['style']],

        ['font', ['bold', 'italic', 'underline', 'clear']],

        ['fontname', ['fontname']],

        ['fontsize', ['fontsize']],

        ['height', ['height']],

        ['color', ['color']],

        ['para', ['ul', 'ol', 'paragraph']],

        ['table', ['table']],

        ['insert', ['link', 'picture']],

      ],

});

$("#category_id").change(function(){	

	$("#sub_category_id").hide();

	$( ".load_cubcategory_loader").html('<div class="text-center" style="min-height: 70px;width: 100%;line-height: 50px;">'+SPINNER+'<div>').show();

	

	$.get( "<?php echo get_link('getsubcatAJAXURL')?>",{'formtype':'getsubcat','Okey':$(this).val()}, function( data ) {

		var html='<option value=""> <?php D(__('edit_proposal_page_Select_Sub_Category',"Select A Sub Category"));?> </option>';

		if(data){

			for(x in data){

				html+='<option value="'+data[x]['category_subchild_id']+'">'+data[x]['name']+'</option>';

			}

		}

		setTimeout(function(){ $("#sub_category_id").html(html).show();$( ".load_cubcategory_loader").hide();},1000)

	},'json');
	loadExtraAttr($(this).val());


});



$image_crop = $('#image_demo').croppie({

    enableExif: true,

    viewport: {

      width:700,

      height:390,

      type:'square' //circle

    },

    boundary:{

      width:100,

      height:400

    }    

});	

$('.crop_image').click(function(event){

	$('#wait').addClass("loader");

 	var name = $('input[type=hidden][name=img_type]').val();

    $image_crop.croppie('result', {

      type: 'canvas',

      size: 'viewport'

    }).then(function(response){

    	$("#thumbnail_primary").html('<div class="center">'+SPINNER+'</div>');

	    $.ajax({

	        url:"<?php D(get_link('uploadFileProposalFormCheckAJAXURL'))?>?type=main",

	        type: "POST",

	        data:{image: response, name: name },

	        dataType: 'json',

	        success:function(response){

	        	$('#proposal_img1').val('');

	        	$('#wait').removeClass("loader");

		        $('#insertimageModal').modal('hide');

	        	 if(response.status=='OK'){

		          	var name = response.upload_response.original_name;

	    			$("#thumbnail_primary").html('<input type="hidden" name="mainimage" value=\''+JSON.stringify(response.upload_response)+'\'/> <a href="<?php D(VZ);?>" class="  ripple-effect ico btn btn-sm btn-circle  btn-danger" onclick="$(this).parent().remove()"><i class="fa fa-trash"></i></a>');

	    			var imageUrl="<?php D(URL_USERUPLOAD.'tempfile/');?>"+name;

	    			$("#thumbnail_primary").css("background-image", "url(" + imageUrl + ")");

	    			

    			}

	          	//$('input[type=hidden][name='+ name +']').val(data);

	        }

	    });

    })

 });

});

$('#proposal_img1').on('change', function(){

	var size = $(this)[0].files[0].size; 

	var ext = $(this).val().split('.').pop().toLowerCase();

	if($.inArray(ext,['jpeg','jpg','gif','png']) == -1){

		alert('Your File Extension Is Not Allowed.');

		$(this).val('');

	}else{

   	 	crop(this);

	}

});

function crop(data){

    var reader = new FileReader();

    reader.onload = function (event) {

      $image_crop.croppie('bind',{

      url: event.target.result

      }).then(function(){

      	console.log('jQuery bind complete');

      });

    }

    reader.readAsDataURL(data.files[0]);

    $('#insertimageModal').modal('show');

    $('input[type=hidden][name=img_type]').val(data.files[0].name);

}

$("#fileinputvideo").change(function(){

    var fd = new FormData();

	var all_files= $('#fileinputvideo')[0].files;

	for(var i=0;i<all_files.length;i++){

		var files = $('#fileinputvideo')[0].files[i];

		fd.append('fileinput',files);

        uploadDataVideo(fd);

	}

});

function uploadDataVideo(formdata){

	var vnum =1;	

	$("#uploadfile_container_video").html('<div id="thumbnailv_'+vnum+'" class="thumbnail_sec_video  mt-3" style="width:250px;height:200px">'+SPINNER+'</div>');

    $.ajax({

        url: "<?php D(get_link('uploadFileProposalFormCheckAJAXURL'))?>?type=video",

        type: 'post',

        data: formdata,

        contentType: false,

        processData: false,

        dataType: 'json',

        success: function(response){

        	$('#fileinputvideo').val('');

           if(response.status=='OK'){

    			var name = response.upload_response.original_name;

    			$("#thumbnailv_"+vnum).html('<input type="hidden" name="projectvideo" value=\''+JSON.stringify(response.upload_response)+'\'/> <video width="220" height="150" controls><source src="<?php D(URL_USERUPLOAD.'proposal-video/');?>'+name+'"></video><a href="<?php D(VZ);?>" class=" ripple-effect ico btn btn-sm btn-circle  btn-danger" onclick="$(this).parent().remove()"><i class="fa fa-trash"></i></a>');

    			 

		   }else{

		   		$("#thumbnailv_"+vnum).html('<p class="text-danger">Error in upload file</p>');

		   }

           

        },

        

    }).fail(function(){

    	$("#thumbnailv_"+vnum).html('<p class="text-danger">Error occurred</p>');

    });

	}

function uploadData(formdata){

	var len = $("#uploadfile_container div.thumbnail_sec").length;

   	var num = Number(len);

	num = num + 1;	

	if(num>4){

		swal({

          type: 'error',

           text: 'Max limit 4',

          timer: 2000,

          onOpen: function(){

            swal.showLoading()

          }

      });

		return false;

	}

	$("#uploadfile_container").append('<div id="thumbnail_'+num+'" class="thumbnail_sec">'+SPINNER+'</div>');

    $.ajax({

        url: "<?php D(get_link('uploadFileProposalFormCheckAJAXURL'))?>?type=image",

        type: 'post',

        data: formdata,

        contentType: false,

        processData: false,

        dataType: 'json',

        success: function(response){

           if(response.status=='OK'){

    			var name = response.upload_response.original_name;

    			$("#thumbnail_"+num).html('<input type="hidden" name="projectfile[]" value=\''+JSON.stringify(response.upload_response)+'\'/> <a href="<?php D(VZ);?>" class=" ripple-effect ico btn btn-sm btn-circle  btn-danger" onclick="$(this).parent().remove()"><i class="fa fa-trash"></i></a>');

    			

    			var urlImg='<?php D(get_link('downloadTempURL'))?>/'+response.upload_response.file_name;

    			$("#thumbnail_"+num).css({"background-image": "url('"+urlImg+"')"})

		   }else{

		   		$("#thumbnail_"+num).html('<p class="text-danger">Error in upload file</p>');

		   }

           

        },

        

    }).fail(function(){

    	$("#thumbnail_"+num).html('<p class="text-danger">Error occurred</p>');

    });

}

function saveProposal(ev){

	var formID="postproposalform";

	var buttonsection=$('#'+formID).find('.saveBTN');

	var buttonval = buttonsection.html();

	buttonsection.html(SPINNER).attr('disabled','disabled');

	

	$.ajax({

        type: "POST",

        url: "<?php D(get_link('editproposalURLAJAX'))?>/",

        data:$('#'+formID).serialize(),

        dataType: "json",

        cache: false,

		success: function(msg) {

			buttonsection.html(buttonval).removeAttr('disabled');

			clearErrors();

			if (msg['status'] == 'OK') {

				 swal({

                  type: 'success',

                  text: '<?php D(__('popup_proposal_update_success_message',"Proposal updated successfully!"));?>',

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                  }).then(function(){

                  	window.location.href=msg['redirect'];

                })	

			} else if (msg['status'] == 'FAIL') {

				registerFormPostResponse(formID,msg['errors']);

			}

		}

	})	

}

function saveProposalPriceFixed(ev){

	var formID="postproposalpriceFixedform";

	var buttonsection=$('#'+formID).find('.saveBTN');

	var buttonval = buttonsection.html();

	buttonsection.html(SPINNER).attr('disabled','disabled');

	

	$.ajax({

        type: "POST",

        url: "<?php D(get_link('editproposalURLAJAX'))?>/",

        data:$('#'+formID).serialize(),

        dataType: "json",

        cache: false,

		success: function(msg) {

			buttonsection.html(buttonval).removeAttr('disabled');

			clearErrors();

			if (msg['status'] == 'OK') {

				 swal({

                  type: 'success',

                  text: 'Proposal details successfully inserted!',

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                  }).then(function(){

                  	window.location.href=msg['redirect'];

                })	

			} else if (msg['status'] == 'FAIL') {

				registerFormPostResponse(formID,msg['errors']);

			}

		}

	})	

}

function saveProposalPricePackage(ev){

	var formID="postproposalpricePakageform";

	var buttonsection=$('#'+formID).find('.saveBTN');

	var buttonval = buttonsection.html();

	buttonsection.html(SPINNER).attr('disabled','disabled');

	

	$.ajax({

        type: "POST",

        url: "<?php D(get_link('editproposalURLAJAX'))?>/",

        data:$('#'+formID).serialize(),

        dataType: "json",

        cache: false,

		success: function(msg) {

			buttonsection.html(buttonval).removeAttr('disabled');

			clearErrors();

			if (msg['status'] == 'OK') {

				 swal({

                  type: 'success',

                  text: 'Package details successfully updated!',

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                  }).then(function(){

                  	window.location.href=msg['redirect'];

                })	

			} else if (msg['status'] == 'FAIL') {

				registerFormPostResponse(formID,msg['errors']);

			}

		}

	})	

}

function saveProposalExtra(ev){

	var formID=$(ev).attr('id');

	var buttonsection=$('#'+formID).find('.saveBTN');

	var buttonval = buttonsection.html();

	buttonsection.html(SPINNER).attr('disabled','disabled');

	

	$.ajax({

        type: "POST",

        url: "<?php D(get_link('editproposalURLAJAX'))?>/",

        data:$('#'+formID).serialize(),

        dataType: "json",

        cache: false,

		success: function(msg) {

			buttonsection.html(buttonval).removeAttr('disabled');

			clearErrors();

			if (msg['status'] == 'OK') {

				 swal({

                  type: 'success',

                  text: msg['message'],

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                  }).then(function(){

                  	window.location.href=msg['redirect'];

                })	

			} else if (msg['status'] == 'FAIL') {

				registerFormPostResponse(formID,msg['errors']);

			}

		}

	})	

}

function deleteExtra(ev,){

	var extraid=$(ev).data('id');

	var buttonsection=$(ev);

	var buttonval = buttonsection.html();

	buttonsection.html(SPINNER).attr('disabled','disabled');

	

	$.ajax({

        type: "POST",

        url: "<?php D(get_link('editproposalURLAJAX'))?>/",

        data:{id:extraid,tab:'extradelete',pid:"<?php D($proposal_details['proposal']->proposal_id);?>",token:"<?php D($token);?>" },

        dataType: "json",

        cache: false,

		success: function(msg) {

			buttonsection.html(buttonval).removeAttr('disabled');

			clearErrors();

			if (msg['status'] == 'OK') {

				 swal({

                  type: 'success',

                  text: "Your proposal extra has been deleted successfully",

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                  }).then(function(){

                  	window.location.href=msg['redirect'];

                })	

			} else if (msg['status'] == 'FAIL') {

				swal({

                  type: 'warning',

                  text: 'Fail to delete',

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                });

			}

		}

	})	

}
function loadExtraAttr(category_id){
	$( ".extraoption").html('<div class="text-center" style="min-height: 70px;width: 100%;line-height: 50px;">'+SPINNER+'<div>').show();
	$.get( "<?php echo get_link('getextraProposalAJAXURL')?>",{'Okey':category_id,'pid':"<?php D($proposal_details['proposal']->proposal_id);?>"}, function( data ) {
		$('.extraoption_1').html(data.section_1);
		$('.extraoption_2').html(data.section_2);
		$('.extraoption_3').html(data.section_3);
        setTimeout(function(){
             $('[data-toggle="popover"]').popover({
                trigger: 'hover',
                //container: 'body',
                html : true
            });
        },1000)
	},'json'); 
}
loadExtraAttr(<?php echo $proposal_details['proposal_category']->category_id;?>);
</script>