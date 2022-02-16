<?php

$loggedUser=$this->session->userdata('loggedUser');

$deflang=$this->config->item('language');

$defdir="ltr";

if($this->session->userdata('current_lang')){

	$deflang=$this->session->userdata('current_lang');

	if($deflang=='ar'){

		$defdir="rtl";

	}

}

?>

<!doctype html>

<html lang="<?php D($deflang);?>" dir="<?php D($defdir);?>">

<head>

<head>

<?php

load_view('inc/seo_tags',array('seo_tags'=>$seo_tags));

?>

<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<?php

$this->load->config('minify', TRUE, TRUE);

$is_enable=$this->config->item('enabled','minify');

if($is_enable){

?>

<?php 

}



$this->minify->add_css('bootstrap_'.$deflang.'.css');

$this->minify->add_css('icons.css');
$this->minify->add_css('all.css');
$this->minify->add_css('theme.css?68468');

$this->minify->add_css('custom.css');

$this->minify->add_css('style.css?248781');

$this->minify->add_css('responsive.css?58797');

$this->minify->add_css('colors.css');

if($loggedUser && $this->router->fetch_class()!='home'){

	$this->minify->add_css('user_nav_styles.css');

}else{

	$this->minify->add_css('categories_nav_styles.css');

}

//$this->minify->add_css('font-awesome.css');



if(!empty($load_css)){

	//echo $load_css;

	foreach($load_css as $files){

		$this->minify->add_css($files);

	}

}

//$this->minify->add_css('sweat_alert.css');

$this->minify->add_css('animate.css');

$this->minify->add_css('lang_'.$deflang.'.css');



echo $this->minify->deploy_css(FALSE, 'header.min.css');

?>

<script type="text/javascript">

  	var VPATH="<?php echo VPATH;?>";

  	var THEME_URL="<?php echo theme_url();?>";

</script>

<?php

$this->minify->add_js('promise-bundle.js');

// $this->minify->add_js('ie.js');

$this->minify->add_js('simplebar.min.js');

//$this->minify->add_js('sweat_alert.js');

$this->minify->add_js('jquery.min.js');

$this->minify->add_js('mycustom.js');

if(!empty($load_js)){

	//echo $load_js;

	foreach($load_js as $files){

		$this->minify->add_js($files);

	}

}

?>

<?php 

echo $this->minify->deploy_js(FALSE, 'header.min.js');

?>

	

<!-- Global site tag (gtag.js) - Google Analytics -->

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-157536212-1"></script>

<script>

  window.dataLayer = window.dataLayer || [];

  function gtag(){dataLayer.push(arguments);}

  gtag('js', new Date());

  gtag('config', 'UA-157536212-1');

</script>

</head>

