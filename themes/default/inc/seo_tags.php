<?php
$class=$this->router->fetch_class();
$method=$this ->router ->fetch_method(); 
$canonical=VPATH.uri_string();
$canonicals[]=$canonical;
/*$canonical=str_replace('www.','',$canonical);
$canonical=str_replace('http://','',$canonical);
$canonical=str_replace('https://','',$canonical);

$canonicals[]='https://'.$canonical;
$canonicals[]='http://'.$canonical;
$canonicals[]='https://www.'.$canonical;
$canonicals[]='http://www.'.$canonical;*/
$seo_images=array(theme_url().IMAGE.LOGO_NAME);
if($seo_tags && $seo_tags['meta_title']){
	$title=getTextFromString($seo_tags['meta_title']);
	$description=getTextFromString($seo_tags['meta_description']);
	if($seo_tags['seo_images']){
		$seo_images=$seo_tags['seo_images'];
	}
}else{
	$title=getTextFromString(get_option_value('default_seo_site_title'));
	$description=getTextFromString(get_option_value('default_seo_site_description'));
}

$seo_title=substr($title,0,70);
$seo_description=substr($description,0,160);
?>
<title><?php D($seo_title);?></title> <!--max 60-->
<meta name="description" content="<?php D($seo_description);?>" /> <!--max 160-->
<?php foreach($canonicals as $url){?>
<link rel="canonical" href="<?php D($url);?>" /> <!--all dublicate url put-->
<?php }?>
<?php /*?><meta name="robots" content="noindex, nofollow" /> <!--– Means not to index or not to follow this web page.--> <?php */?>
<meta name="robots" content="index, follow" /> <!--– Means index and follow this web page.-->
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php D($canonical);?>" />
<meta name="twitter:url" content="<?php D($canonical);?>" />
<!--<meta property="og:local" content="en-US" />-->
<meta property="og:title" content="<?php D($title);?>" />
<meta property="og:description" content="<?php D($description);?>" />
<meta property="og:site_name" content="<?php D(get_option_value('website_name'));?>" />
<?php if(get_option_value('fb_app_id')){?>
<meta property="fb:app_id" content="<?php D(get_option_value('fb_app_id'));?>" />
<?php }?>
<!--<meta name="twitter:card" content="" />-->
<meta name="twitter:title" content="<?php D($title);?>" />
<meta name="twitter:description" content="<?php D($description);?>" />
<?php if($seo_images){
	foreach($seo_images as $image){
	?>
<meta property="og:image" content="<?php D($image);?>" />
<meta name="twitter:image" content="<?php D($image);?>" />
<?php 	
	}
}?>
<?php if(get_option_value('tw_page_username')){?>
<meta name="twitter:site" content="<?php D(get_option_value('tw_page_username'))?>" /> <!--@username-->
<?php }?>
<?php if(get_option_value('tw_creator_username')){?>
<meta name="twitter:creator" content="<?php D(get_option_value('tw_creator_username'))?>" /> <!--@username-->
<?php }?>
<meta name="author" content="<?php D(get_option_value('website_name'));?>" />
<meta name="organization" content="<?php D(get_option_value('website_name'));?>" />
<link rel="shortcut icon" href="<?php D(theme_url().IMAGE)?>favicon.png" type="image/x-icon">

