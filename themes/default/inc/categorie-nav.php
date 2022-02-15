<div class="home-header category-nav filter-container">	
	<div class="filter-header d-lg-none"><h3><i class="icon-feather-x"></i> Categoies</h3></div>
	<div class="container-fluid">
    <nav id="navigation" class="mt-0">                					
		<ul id="responsive">     
<?php
$allcategory=getAllCategory();
				
if($allcategory){
	foreach($allcategory as $k=>$category){
		$allsubcategory=getAllSubCategory($category->category_id);
		if($k<7 || count($allcategory)<=9){
		?>
	<li class="mega-drop"><a href="<?php D(get_link('CategoryURL').$category->category_key);?>" id="flip" title="<?php D($category->name); ?>"><?php D($category->name); ?></a>
		<div class="mega-menu four-columns">
        <ul>
		<?php if($allsubcategory){
            //$half=count($allsubcategory)/2;
            //$maxc=ceil($half)-1;
            foreach($allsubcategory as $sk=>$subcategory){
            ?>
            <li><a href="<?php D(get_link('CategoryURL').$category->category_key.'/'.$subcategory->category_subchild_key);?>" title="<?php D($subcategory->name); ?>"><?php D($subcategory->name); ?></a></li>
            <?php
            if($sk==$maxc){
            D('
                ');
            }
            }
        }
            ?>
         </ul>			
	</div>
    </li>		
<?php
}else{
	if($k==7){
		?>
<li>
	<a href="#" id="flip" class="nav-link_ p-2"><?php // D(__('header_category_More','More'));?> <i class="icon-feather-plus-circle" style="font-size: 18px;"></i></a>
		
        <ul>	
<?php }?>
 	<li>
     	<a href="<?php D(get_link('CategoryURL').$category->category_key);?>" title="<?php D($category->name); ?>">
    	 	<?php D($category->name); ?>
     	</a>
 	</li>
 	
<?php if($k+1==count($allcategory)){?> 	
	</ul>
            
		
</li>
<?php }?>		
		<?php
	}
}

}
?>

	</ul>
	</nav>		
	</div>	
</div>