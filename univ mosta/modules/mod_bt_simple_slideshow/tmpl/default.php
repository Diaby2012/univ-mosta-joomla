<?php
/**
 * @package 	Module BT Simple Slideshow
 * @version		1.0.0
 * @created		December 2014
 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2014 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div id="bt-simple-slideshow-<?php echo $module->id?>" class="bt-simple-slideshow<?php echo $params->get('moduleclass_suffix', '')?>" style="width:<?php echo $moduleWidth?>">
	<div class="camera_wrap" id="camera_wrap_<?php echo $module->id?>">
		<?php foreach($photos as $photo){?>
		<div <?php echo $showPaginationThumbnail ? 'data-thumb="' . $thumbnailLink . $photo->file . '"' : ''?> data-src="<?php echo $folder . $photo->file?>">
			<?php if($showCaption){?>
			<div class="camera_caption moveFromBottom">
				<?php if($photo->title){?>
				<h3>
				<?php if(isset($photo->link)){?>
				<a href="<?php echo $photo->link?>" target="<?php echo isset($photo->target) ? $photo->target : '_blank'?>"><?php echo $photo->title?></a>
				<?php }else{echo $photo->title;}?>				
				</h3>
				<?php }?>
				<?php if(isset($photo->desc)){?>
				<p><?php echo $photo->desc?></p>
				<?php }?>
			</div>
			<?php }?>
		</div>
		<?php }?>
		
	</div>
	<div style="clear:both;"></div>
</div>        
<script type="text/javascript">
$w(document).ready(function(){
	$w('#camera_wrap_<?php echo $module->id?>').camera({
		thumbnails: <?php echo $showPaginationThumbnail ? 'true' : 'false'?>,
		fx: '<?php echo $effect?>',
		height: '<?php echo $moduleHeight?>',
		hover: <?php echo $pauseOnHover ? 'true' : 'false'?>,
		loader: 'none',
		navigation: <?php echo $showNavigation ? 'true' : 'false'?>,
	 	playPause: false,
	 	pagination: <?php echo $showPagination ? 'true' : 'false'?>,
		time: <?php echo $interval?>,
		transPeriod: <?php echo $effectDuration?>,
		portrait: <?php echo $portrait ? 'true' : 'false'?>,
		onLoaded: function(){
			<?php if(!$autoplay){?>
			$w('#camera_wrap_<?php echo $module->id?>').cameraPause();
			<?php }?>
		}
	});
});	
</script>			