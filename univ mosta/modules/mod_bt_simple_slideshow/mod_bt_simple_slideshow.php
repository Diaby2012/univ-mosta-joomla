<?php
/**
 * @package 	Module BT Simple Slideshow
 * @created		December 2014
 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2014 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

// No direct access
defined('_JEXEC') or die;

require_once 'helpers/helper.php';
$photos = BtSimpleSlideshowHelper::getPhotos($params);

$dir = JPATH_ROOT . '/modules/mod_bt_simple_slideshow/images/';
foreach ($photos as $key => $photo) {
  	//move temporary files to folder after if user go to front-end immediately after save and close module
	if (!JFile::exists($dir . '/original/' . $photo->file)) {
		if (JFile::exists($dir . '/tmp/original/' . $photo->file)) {
			JFile::move($dir . '/tmp/original/' . $photo->file, $dir . '/original/' . $photo->file);
			JFile::move($dir . '/tmp/manager/' . $photo->file, $dir . '/manager/' . $photo->file);
		}else{
			unset($photos[$key]);
		}
	}

}
$numberOfPhotos  = count($photos);

if (count($photos) == 0) {
	echo JText::sprintf('NOTICE_NO_IMAGES', $module->id);
	return;
}

$moduleID = $module->id;
$moduleURI = JURI::base()."modules/mod_bt_simple_slideshow/";
$originalPath = JPATH_BASE . '/modules/mod_bt_simple_slideshow/images/original/';

$moduleWidth = $params->get('module_width', 'auto');
$moduleHeight = $params->get('module_height', '50%');
$cropImage = $params->get('crop_image', 0);
$cropWidth = $params->get('crop_width', 1000);
$cropHeight = $params->get('crop_height', 500);
$thumbnailWidth = $params->get('thumbnail_width', 100);
$thumbnailHeight = $params->get('thumbnail_height', 50);
$imageQuality = $params->get('jpeg_compression', 80);
$portrait = $params->get('portrait', true);
$showNavigation = $params->get('navigation', true);
$showPagination = $params->get('pagination', true);
$showPaginationThumbnail = $params->get('pagination_thumbnail', false);
$pauseOnHover = $params->get('pause_on_hover', false);
$effect = $params->get('effect', array('sliderLeft'));
if(is_array($effect)){
	$effect = implode(',',$effect);
}
$effectDuration = $params->get('effect_duration', 1500);
$interval = $params->get('interval', 3000);
$autoplay = $params->get('autoplay', 1);
$showCaption = $params->get('show_caption', 1);

if($cropImage && (!$cropWidth || !$cropHeight)){
	echo JText::sprintf('NOTICE_CROP_SIZE', $module->id);
	return;
}
if($showPaginationThumbnail && (!$thumbnailWidth || !$thumbnailHeight)){
	echo JText::sprintf('NOTICE_THUMBNAIL_SIZE', $module->id);
	return;
}

// Make thumbnail & slideshow images if haven't created or just change the size
require_once (JPATH_ROOT . '/modules/mod_bt_simple_slideshow/helpers/images.php');
//create image again

if($cropImage || $showPaginationThumbnail){
	$crop = false;
	$cropThumbnail = false;
	
	$cropPath = JPATH_BASE . '/modules/mod_bt_simple_slideshow/images/slideshow/';
	$cropConfigFile = JPATH_BASE . '/modules/mod_bt_simple_slideshow/images/cropConfig.txt';
	
	if(file_exists($cropConfigFile)){
		$config = json_decode(file_get_contents($cropConfigFile), true);
		if(!is_array($config)) $config = array();
		if(!isset($config[$module->id])){
			$config[$module->id] = array(
					'slideshow' => array('width' => $cropWidth, 'height' => $cropHeight),
					'thumbnail' => array('width' => $thumbnailWidth, 'height' => $thumbnailHeight),
					'quality'	=> $imageQuality
			);
			$crop = true;
			$cropThumbnail = true;
		}else{

			if($config[$module->id]['slideshow']['width'] != $cropWidth || $config[$module->id]['slideshow']['height'] != $cropHeight){
				$crop = true;
				$config[$module->id]['slideshow']['width'] = $cropWidth;
				$config[$module->id]['slideshow']['height'] = $cropHeight;
			}
			
			if($config[$module->id]['thumbnail']['width'] != $thumbnailWidth || $config[$module->id]['thumbnail']['height'] != $thumbnailHeight){
				$cropThumbnail = true;
				$config[$module->id]['thumbnail']['width'] = $thumbnailWidth;
				$config[$module->id]['thumbnail']['height'] = $thumbnailHeight;
			}	
			
			if($config[$module->id]['quality'] != $imageQuality){
				$crop = true;
				$cropThumbnail = true;
				$config[$module->id]['quality'] = $imageQuality;
			}
		}
		if($crop || $cropThumbnail){
			$pt = fopen($cropConfigFile, 'w+');
			fwrite($pt, json_encode($config));
			fclose($pt);
		}
	}else{
		$config = array();
		$config[$module->id] = array(
				'slideshow' => array('width' => $cropWidth, 'height' => $cropHeight),
				'thumbnail' => array('width' => $thumbnailWidth, 'height' => $thumbnailHeight),
				'quality'	=> $imageQuality
		); 
		$pt = fopen($cropConfigFile, 'w+');
		fwrite($pt, json_encode($config));
		fclose($pt);
		$crop = true;
		$cropThumbnail = true;
	}
}
if($cropImage){
    foreach($photos as $photo){
		$originalFile = $originalPath.$photo->file;
        $file = $cropPath.$photo->file;
        if(!file_exists($file) || $crop){
            BTImageHelper::resize($originalFile, $file, $cropWidth, $cropHeight,true,$imageQuality);
        }
    }
    $folder = $moduleURI . 'images/slideshow/';
}else{
    $folder = $moduleURI.'images/original/';
}

if($showPaginationThumbnail){
	$thumbailPath = JPATH_BASE . '/modules/mod_bt_simple_slideshow/images/thumbnail/';
	$thumbnailLink = $moduleURI.'images/thumbnail/';
	foreach($photos as $photo){
		$originalFile = $originalPath.$photo->file;
		$file = $thumbailPath.$photo->file;
		if(!file_exists($file) || $cropThumbnail){
			BTImageHelper::resize($originalFile, $file, $thumbnailWidth, $thumbnailHeight,true,$imageQuality);
		}
	}
}

BtSimpleSlideshowHelper::fetchHead( $params );
require JModuleHelper::getLayoutPath('mod_bt_simple_slideshow', 'default');
?>