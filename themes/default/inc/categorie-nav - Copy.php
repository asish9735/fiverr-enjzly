<?php
$allcategory=getAllCategory();
				
if($allcategory){
	foreach($allcategory as $k=>$category){
		$allsubcategory=getAllSubCategory($category->category_id);
		if($k<7 || count($allcategory)<=9){
		?>
<li class="mega-drop-down nav-item">
	<a href="<?php D(get_link('CategoryURL').$category->category_key);?>" id="flip" class="nav-link" title="<?php D($category->name); ?>"><?php D($category->name); ?></a>
	<div class="animated fadeIn mega-menu" id="panel" >
    	<div class="mega-menu-wrap">
			<div class="row">
				<div class="col-md-6 pad0 col-sm-6">
                	<ul class="description">
                	<?php if($allsubcategory){
                		$half=count($allsubcategory)/2;
                		$maxc=ceil($half)-1;
                		foreach($allsubcategory as $sk=>$subcategory){
                		?>
                		<li><a class="dropdown-item" href="<?php D(get_link('CategoryURL').$category->category_key.'/'.$subcategory->category_subchild_key);?>" title="<?php D($subcategory->name); ?>"><?php D($subcategory->name); ?></a></li>
                		<?php
                		if($sk==$maxc){
                			D('
                			</ul>
                			</div>
                			<div class="col-md-6 pad0 col-sm-6">
                			<ul class="description">');
                		}
                		}
                	}
                		?>
                	 </ul>
                </div>
			</div>
		</div>
	</div>
</li>
<?php
}else{
	if($k==7){
		?>
<li class="mega-drop-down nav-item">
	<a href="#" id="flip" class="nav-link p-1 mt-1"><?php // D(__('header_category_More','More'));?> <i class="icon-feather-plus-square" style="font-size: 22px;"></i></a>
     <div class="animated fadeIn mega-menu" id="panel" style="width: 30%;">
		<div class="mega-menu-wrap">
            <div class="row">
                <div class="col-md-12 pad0 col-sm-6">
                  	<ul class="description">	
<?php }?>
 	<li>
     	<a class="dropdown-item" href="<?php D(get_link('CategoryURL').$category->category_key);?>" title="<?php D($category->name); ?>">
    	 	<?php D($category->name); ?>
     	</a>
 	</li>
 	
<?php if($k+1==count($allcategory)){?> 	
					</ul>
				</div>
			</div>
		</div>
	</div>
</li>
<?php }?>		
		<?php
	}
}

}
?>