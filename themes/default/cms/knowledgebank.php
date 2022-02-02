<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($filter);
?>
<div class="header">
<div class="container">
	<div class="text-center">
       <h2 class="text-white pt-5"><?php D($cms->title); ?></h2>
       <h4 class="text-white"><?php D(__('knowledge_page_sub_heading','Everything you need to know'));?></h4>
	</div>
</div>
</div>
<br><br>
<div class="container">
	<div class="row pb-4">
		<div class="col-md-12">
            <p class="text-justify">
                <?php D(html_entity_decode($cms->content)); ?>
            </p>
		</div>
	</div>
</div>