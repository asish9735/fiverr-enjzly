<?php
$seller_user_name=getUserName($proposal->proposal_seller_id);
$seller_heading=getFieldData('member_heading', 'member_basic','member_id', $proposal->proposal_seller_id);
$url=get_link('ProposalDetailsURL').'/'.$seller_user_name.'/'.$proposal->proposal_url;
$proposal_rating_details=getProposalRating($proposal->proposal_id,array('stat'));
$proposal_rating=0;
$count_reviews=$proposal_rating_details->total_review;
$average_rating=$proposal_rating_details->avg_review;
$seller_status = is_online($proposal->proposal_seller_id);
$image = NO_IMAGE;
if($proposal->proposal_image && file_exists(ABS_USERUPLOAD_PATH.'proposal-files/'.$proposal->proposal_image)){
	$image = URL_USERUPLOAD.'proposal-files/'.$proposal->proposal_image;
}
$is_featured=0;
if($proposal->proposal_featured==1 && $proposal->featured_end_date>date('Y-m-d H:i:s')){
	$is_featured=1;
}
$proposal->is_featured=$is_featured;
//dd($proposal,true);
$arr=array(
	'select'=>'f.file_id,f.original_name,f.server_name,f.file_ext',
	'table'=>'proposal_files as p_f',
	'join'=>array(
		array('table'=>'files as f','on'=>'p_f.file_id=f.file_id','position'=>'left'),
	),
	'where'=>array('p_f.proposal_id'=>$proposal->proposal_id),
);
$proposal_files=getData($arr);
?>


<div class="card proposal-card">
	<div class="card-image">
	<div id="carousel_<?php echo $proposal->proposal_id;?>" class="carousel slide" data-ride="carousel">
		<ol class="carousel-indicators">
		<?php if($proposal_files){
				foreach($proposal_files as $f=>$rowsimage){?>
			<li data-target="#carousel_<?php echo $proposal->proposal_id;?>" data-slide-to="<?php echo $f;?>" class="<?php if ($f==0){echo 'active';}?>"></li>
			<?php 
				}
			}?>
		</ol>
		<div class="carousel-inner" role="listbox">
			<?php if($proposal_files){
				foreach($proposal_files as $f=>$rowsimage){?>
				<div class="carousel-item  <?php if($f==0){D('active');}?>">
				<a href="<?php D($url); ?>"><img src="<?php D(URL_USERUPLOAD.'proposal-files/'.$rowsimage->server_name); ?>" class="card-img-top"></a>
				</div>
			<?php }
			}else{?>
			<div class="carousel-item active">
			<a href="<?php D($url); ?>"><img src="<?php D($image); ?>?a1" class="card-img-top"></a>
			</div>
			<?php }?>
		</div>
		<a class="carousel-control-prev" href="#carousel_<?php echo $proposal->proposal_id;?>" data-slide="prev"> <i class="icon-line-awesome-angle-left"></i> </a>
		<a class="carousel-control-next" href="#carousel_<?php echo $proposal->proposal_id;?>" data-slide="next"> <i class="icon-line-awesome-angle-right"></i> </a>
	</div>
	<?php if($is_featured==1){?>
    	<div class="featuretag">Featured</div>
	<?php }?>
        <?php
		if(empty($proposal->hide_footer_action)){
			
		?>        
		<?php 
		if($proposal->proposal_enable_referrals == 1){
				if($loggedUser){ 
					if($proposal->proposal_seller_id != $loggedUser['MID']){ ?>
                        <a class="icn-list proposal-offer" data-id="<?php D($proposal->proposal_id); ?>">
                        <?php 
                        $templateLayout=array('view'=>IMAGE.'affiliate.svg','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
                        load_template($templateLayout,$affiliatedata);
                        ?>
                        </a>
				<?php }
				}else{ ?>
					<a class="icn-list" data-toggle="modal" data-target="#login-modal">
						<?php 
						$templateLayout=array('view'=>IMAGE.'affiliate.svg','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
						load_template($templateLayout,$affiliatedata);
						?>
					</a>
			<?php }
		} 
			
		if($loggedUser){ 
			if($proposal->proposal_seller_id != $loggedUser['MID']){
				$is_favorite=is_favorite($loggedUser['MID'],$proposal->proposal_id);
				if($is_favorite){
					$show_favorite_class = "mark-unfav";
				}else{
					$show_favorite_class = "mark-fav ";
				}
				 ?>
					<i data-id="<?php D($proposal->proposal_id); ?>" href="#" class="icon-line-awesome-heart <?php D($show_favorite_class); ?>" data-toggle="tooltip" data-placement="top" title="Favorite"></i>
		<?php }
		}/* else{
			 ?>
            <a href="#" data-toggle="modal" data-target="#login-modal">
                <i class="icon-line-awesome-heart mark-unfav" data-toggle="tooltip" data-placement="top" title="Favorite"></i>
            </a>
		<?php } */ ?>
		<?php }?>
    	            
    </div>
    <div class="card-body">
    	<div class="d-flex align-items-center mb-3">
            <span class="fit-avatar mr-3">        	
                <a tabindex="0" role="button" data-toggle="popover" data-trigger="hover" title="<?php D($seller_user_name); ?>" data-content="<h5><?php echo $seller_heading;?></h5>" data-placement="left" href="<?php D(get_link('viewprofileURL'))?><?php D($seller_user_name); ?>">
                <img src="<?php D(getMemberLogo($proposal->proposal_seller_id))?>" alt="" height="40" width="40" /></a>
                <?php if($seller_status == 1){ ?>
                <div class="verified-badge"></div>
                <?php }?>
            </span> 
            <div>
            	<h6 class="mb-0">Admin User</h6>
                <small>2 Order in Queue</small>
            </div>
            <a href="javascript:void(0)" class="btn-wishlist ml-auto"><i class="icon-line-awesome-heart"></i></a>
        </div>
        <div class="star-rating proposal-rating" data-rating="<?php echo round($average_rating,1);?>" data-showcount="true" data-digit="(<?php echo $count_reviews; ?>)"></div>
		<h4><a href="<?php D($url); ?>" class="js-proposal-card-imp-data"> <?php D($proposal->proposal_title); ?></a></h4>
		<p class="proposal-link-main"><?php D($proposal->proposal_title); ?></p>
        
        <?php /*?>
        <div class="proposal-seller-info">			
			<div class="seller-info-wrapper">
				<a href="<?php D(get_link('viewprofileURL'))?><?php D($seller_user_name); ?>" class="seller-name">
				<?php D($proposal->member_name); D($seller_user_name); ?>
				</a>
				<div class="gig-seller-tooltip">
					<?php D(getLevelName($proposal->seller_level)); ?>
				</div>
			</div>
		</div>	<?php */?>
        
	<?php /* if($seller_status == 1){ ?>
		<div class="is-online float-right">
		  	<i class="fa fa-circle"></i> <?php D(__('global_online','online'));?>
		</div>
	<?php } */?>
    	<div class="proposal-price">
			<h3><span class="fS-body"><?php // D(__('global_starting_price','Starting Price'));?><i class="icon-line-awesome-bolt"></i> From </span> <span class="price"><?php D(CURRENCY); ?><?php D($proposal->display_price); ?></span></h3>
            <span><i class="icon-feather-eye"></i> <?php echo getnoofviews($proposal->proposal_id);?></span>
		</div>
	</div>	
</div>

<div class="card proposal-card card-ads ads-list-view mb-4">
    <div class="row row-0">
        <div class="col-lg-3 col-sm-4 col-12">
            <div class="card-image">
            <div id="carousel_<?php echo $proposal->proposal_id;?>" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                <?php if($proposal_files){
                        foreach($proposal_files as $f=>$rowsimage){?>
                    <li data-target="#carousel_<?php echo $proposal->proposal_id;?>" data-slide-to="<?php echo $f;?>" class="<?php if ($f==0){echo 'active';}?>"></li>
                    <?php 
                        }
                    }?>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <?php if($proposal_files){
                        foreach($proposal_files as $f=>$rowsimage){?>
                        <div class="carousel-item  <?php if($f==0){D('active');}?>">
                        <a href="<?php D($url); ?>"><img src="<?php D(URL_USERUPLOAD.'proposal-files/'.$rowsimage->server_name); ?>" class="card-img-top"></a>
                        </div>
                    <?php }
                    }else{?>
                    <div class="carousel-item active">
                    <a href="<?php D($url); ?>"><img src="<?php D($image); ?>?a1" class="card-img-top"></a>
                    </div>
                    <?php }?>
                </div>
                <a class="carousel-control-prev" href="#carousel_<?php echo $proposal->proposal_id;?>" data-slide="prev"> <i class="icon-line-awesome-angle-left"></i> </a>
                <a class="carousel-control-next" href="#carousel_<?php echo $proposal->proposal_id;?>" data-slide="next"> <i class="icon-line-awesome-angle-right"></i> </a>
            </div>
            <?php if($is_featured==1){?>
                <div class="featuretag">Featured</div>
            <?php }?>
                <?php
                if(empty($proposal->hide_footer_action)){
                    
                ?>        
                <?php 
                if($proposal->proposal_enable_referrals == 1){
                        if($loggedUser){ 
                            if($proposal->proposal_seller_id != $loggedUser['MID']){ ?>
                                <a class="icn-list proposal-offer" data-id="<?php D($proposal->proposal_id); ?>">
                                <?php 
                                $templateLayout=array('view'=>IMAGE.'affiliate.svg','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
                                load_template($templateLayout,$affiliatedata);
                                ?>
                                </a>
                        <?php }
                        }else{ ?>
                            <a class="icn-list" data-toggle="modal" data-target="#login-modal">
                                <?php 
                                $templateLayout=array('view'=>IMAGE.'affiliate.svg','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
                                load_template($templateLayout,$affiliatedata);
                                ?>
                            </a>
                    <?php }
                } 
                    
                if($loggedUser){ 
                    if($proposal->proposal_seller_id != $loggedUser['MID']){
                        $is_favorite=is_favorite($loggedUser['MID'],$proposal->proposal_id);
                        if($is_favorite){
                            $show_favorite_class = "mark-unfav";
                        }else{
                            $show_favorite_class = "mark-fav ";
                        }
                         ?>
                            <i data-id="<?php D($proposal->proposal_id); ?>" href="#" class="icon-line-awesome-heart <?php D($show_favorite_class); ?>" data-toggle="tooltip" data-placement="top" title="Favorite"></i>
                <?php }
                }/* else{
                     ?>
                    <a href="#" data-toggle="modal" data-target="#login-modal">
                        <i class="icon-line-awesome-heart mark-unfav" data-toggle="tooltip" data-placement="top" title="Favorite"></i>
                    </a>
                <?php } */ ?>
                <?php }?>
                            
            </div>
        </div>
        <div class="col-lg-9 col-sm-8 col-12">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="fit-avatar mr-3">        	
                        <a tabindex="0" role="button" data-toggle="popover" data-trigger="hover" title="<?php D($seller_user_name); ?>" data-content="<h5><?php echo $seller_heading;?></h5>" data-placement="left" href="<?php D(get_link('viewprofileURL'))?><?php D($seller_user_name); ?>">
                        <img src="<?php D(getMemberLogo($proposal->proposal_seller_id))?>" alt="" height="40" width="40" /></a>
                        <?php if($seller_status == 1){ ?>
                        <div class="verified-badge"></div>
                        <?php }?>
                    </span> 
                    <div>
                        <h6 class="mb-0">Admin User</h6>
                        <small>2 Order in Queue</small>
                    </div>
                    <a href="javascript:void(0)" class="btn-wishlist ml-auto"><i class="icon-line-awesome-heart"></i></a>
                </div>
                <div class="star-rating proposal-rating" data-rating="<?php echo round($average_rating,1);?>" data-showcount="true" data-digit="(<?php echo $count_reviews; ?>)"></div>
                <h4><a href="<?php D($url); ?>" class="js-proposal-card-imp-data"> <?php D($proposal->proposal_title); ?></a></h4>
                <p class="proposal-link-main"><?php D($proposal->proposal_title); ?></p>
                
                <?php /*?>
                <div class="proposal-seller-info">			
                    <div class="seller-info-wrapper">
                        <a href="<?php D(get_link('viewprofileURL'))?><?php D($seller_user_name); ?>" class="seller-name">
                        <?php D($proposal->member_name); D($seller_user_name); ?>
                        </a>
                        <div class="gig-seller-tooltip">
                            <?php D(getLevelName($proposal->seller_level)); ?>
                        </div>
                    </div>
                </div>	<?php */?>
                
            <?php /* if($seller_status == 1){ ?>
                <div class="is-online float-right">
                    <i class="fa fa-circle"></i> <?php D(__('global_online','online'));?>
                </div>
            <?php } */?>
                <div class="proposal-price">
                    <h3><span class="fS-body"><?php // D(__('global_starting_price','Starting Price'));?><i class="icon-line-awesome-bolt"></i> From </span> <span class="price"><?php D(CURRENCY); ?><?php D($proposal->display_price); ?></span></h3>
                    
                </div>
            </div>	
            <div class="card-footer">
                	<ul class="task-icons d-flex justify-content-between w-100 mb-0">
                    	<li><i class="icon-line-awesome-tag"></i> SEO</li>
                        <li><i class="icon-line-awesome-language"></i> Bilingual</li>   
                        <li><img src="<?php D(theme_url().IMAGE);?>flags/in.svg" alt="" height="15" width="20" class="flag"> California - USA, Dubai</li>
                        <li><span><i class="icon-feather-eye"></i> <?php echo getnoofviews($proposal->proposal_id);?></span></li>
                    </ul>
                </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
$('[data-toggle="popover"]').popover({
  trigger: 'hover',
  //container: 'body',
  html : true
});
});
</script>