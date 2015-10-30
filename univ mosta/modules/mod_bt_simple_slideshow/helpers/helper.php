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
defined('_JEXEC') or die; 
class BtSimpleSlideshowHelper {

    
    public static function getPhotos($params) {
        $photos = $params->get('gallery');
        $photos = json_decode($photos);
        switch ($params->get('display_order', 'ordering')) {
            case 'title_asc':
                for ($i = 0; $i < count($photos) - 1; $i++) {
                    for ($j = $i + 1; $j < count($photos); $j++) {
                        $temp = $photos[$i];
                        if (strcmp($photos[$i]->title, $photos[$j]->title) > 0) {
                            $photos[$i] = $photos[$j];
                            $photos[$j] = $temp;
                        }
                    }
                }

                break;
            case 'title_desc' :
                for ($i = 0; $i < count($photos) - 1; $i++)
                    for ($j = $i + 1; $j < count($photos); $j++) {
                        $temp = $photos[$i];
                        if (strcmp($photos[$i]->title, $photos[$j]->title) < 0) {
                            $photos[$i] = $photos[$j];
                            $photos[$j] = $temp;
                        }
                    }
                break;
            case 'random':
                shuffle($photos);
                break;
			case 'first-random':
				$tempArray = array_slice($photos,1);
                shuffle($tempArray);
				$photos = array_merge(array($photos[0]),$tempArray);
                break;
            default:
                break;
        }
        return $photos;
    }

    public static function fetchHead($params){
		$document	= JFactory::getDocument();
		$header = $document->getHeadData();
		$loadJquery = $params->get('load_jquery', '');
		$mainframe = JFactory::getApplication();
		$template = $mainframe->getTemplate();
		
		if($loadJquery === ''){
			$loadJquery =  true;
			foreach($header['scripts'] as $scriptName => $scriptData)
			{
				  if(substr_count($scriptName,'/jquery'))
				  {
						$loadJquery = false;
				  }
			}
		}	
		//Add js
		if($loadJquery)
		{
			$document->addScript(JURI::root().'modules/mod_bt_simple_slideshow/tmpl/js/jquery.min.js');
		}
		
		if(file_exists(JPATH_BASE.'/templates/'.$template.'/html/mod_bt_simple_slideshow/css/style.css'))
		{
			$document->addStyleSheet(  JURI::root().'templates/'.$template.'/html/mod_bt_simple_slideshow/css/style.css');
		}
		else{
			$document->addStyleSheet(JURI::root().'modules/mod_bt_simple_slideshow/tmpl/css/style.css');
		}
		

		$document->addScript(JURI::root().'modules/mod_bt_simple_slideshow/tmpl/js/jquery.easing.1.3.js');
		$document->addScript(JURI::root().'modules/mod_bt_simple_slideshow/tmpl/js/jquery.mobile.customized.min.js');
		$document->addScript(JURI::root().'modules/mod_bt_simple_slideshow/tmpl/js/camera.min.js');
		$document->addScript(JURI::root().'modules/mod_bt_simple_slideshow/tmpl/js/default.js');
            
	}

}

?>
