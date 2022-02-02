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


		<button type="button" class="btn btn-primary btn-sm" onclick="processCopy()">

              <i class="fa fa-copy"></i>

				Process All

			</button>
		   

	

            

          </div>

        </div>

       

		<div class="box-body table-responsive no-padding" id="main_table">

              <table class="table table-hover">

                <tbody>

				<tr>

					

               
                  <th style="width:30%">Tables Name</th>
                  <th style="width:30%">Language Column</th>
                  <th >From</th>
                  <th >To</th>

                 
                  <th class="text-right" style="padding-right:20px;">Action</th>

                </tr>

				<?php 
				if(count($languageTables) > 0){foreach($languageTables as $k => $v){ 
				?>

				<tr>

				

                  <td><?php echo $v['table']; ?></td>
                  <td><?php echo $v['column']; ?></td>
                  <td><?php echo $from; ?></td>
                  <td><?php echo $to; ?></td>

                

                  <td class="text-right" style="padding-right:20px;" id="sec_<?php echo $k;?>">

					<a class="copyaction" href="<?php echo JS_VOID; ?>" data-tablename="<?php echo $v['table']; ?>"  data-columnname="<?php echo $v['column']; ?>" data-from="<?php echo $from; ?>" data-to="<?php echo $to; ?>" data-section="<?php echo $k;?>" onclick="return copyLanguage(this)" data-toggle="tooltip" title="From `<?php echo $from; ?>` To `<?php echo $to; ?>`"><i class="fa fa-copy green <?php echo ICON_SIZE;?>"></i></a>
					

				  </td>

                </tr>

				<?php } }else{  ?>

				<tr>

                  <td colspan="10"><?php echo NO_RECORD; ?></td>

                 </tr>

				<?php } ?>

                

               </tbody>

			  </table>

        </div>

		 <!-- /.box-body -->

		<div class="box-footer clearfix">

              <ul class="pagination pagination-sm no-margin pull-right">

              

              </ul>

            </div>

      </div>

      <!-- /.box -->



    </section>

    <!-- /.content -->

  </div>

  <!-- /.content-wrapper -->

  

  

<div class="modal fade" id="ajaxModal">

	  <div class="modal-dialog">

		<div class="modal-content">

		 

		</div>

	  </div>

</div>



<script>


function copyLanguage(ev){
	var c = confirm('By copy from default, old language data will be deleted for this table. Are you sure to process ?');

	if(c){
		
		var section =$(ev).data('section');
		var data = 'table_name='+$(ev).data('tablename')+'&column_name='+$(ev).data('columnname')+'&from='+$(ev).data('from')+'&to='+$(ev).data('to');

		var url = '<?php echo base_url($curr_controller.'copy_record');?>';
		$('#sec_'+section).html('<i class="green ">Processing</i>');
		$.ajax({

			url : url,

			data: data,

			type: 'POST',

			dataType: 'json',

			success: function(res){

				if(res.status==1){

					$('#sec_'+section).html('<i class="green ">Complete</i>');

				}else{
					$('#sec_'+section).html('<i class="red ">Error</i>');
				}

				

			}

		});

	}

	

	return false;
}
var confirmt=false;
function processCopy(){
	if(confirmt){
		var c=confirmt
	}else{
		var c = confirm('By copy from default, old language data will be deleted for this table. Are you sure to process ?');	
	}
	

	if(c){
		
		var element=$('.copyaction').eq(0);
		var section =element.data('section');
		var nextsection =section+1;
		var data = 'table_name='+element.data('tablename')+'&column_name='+element.data('columnname')+'&from='+element.data('from')+'&to='+element.data('to');
		var url = '<?php echo base_url($curr_controller.'copy_record');?>';
		$('#sec_'+section).html('<i class="green ">Processing</i>');
		$.ajax({

			url : url,

			data: data,

			type: 'POST',

			dataType: 'json',

			success: function(res){

				if(res.status==1){

					$('#sec_'+section).html('<i class="green ">Complete</i>');
					if($('#sec_'+nextsection).length>0){
						confirmt=true;
						processCopy();
					}

				}else{
					$('#sec_'+section).html('<i class="red ">Error</i>');
				}

				

			}

		});
		
		
	}

	return false;	
}

$(function(){

	

	init_plugin(); /* global.js */

	//init_event();

	

	

});

</script>

