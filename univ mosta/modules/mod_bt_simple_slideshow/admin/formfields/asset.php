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

jimport('joomla.form.formfield');

class JFormFieldAsset extends JFormField {

    protected $type = 'Asset';

    protected function getInput() {
	
		/* @var JDocument $document */
        $document = JFactory::getDocument();
		if (!version_compare(JVERSION, '3.0', 'ge')) {
			$checkJqueryLoaded = false;
			$header = $document->getHeadData();
			foreach($header['scripts'] as $scriptName => $scriptData)
			{
				if(substr_count($scriptName,'/jquery')){
					$checkJqueryLoaded = true;
				}
			}	
			//Add js
			if(!$checkJqueryLoaded) 
				$document->addScript(JURI::root().$this->element['path'].'js/jquery.min.js');
			$document->addScript(JURI::root().$this->element['path'].'js/chosen.jquery.min.js');
			$document->addScript(JURI::root().$this->element['path'].'js/jquery.ui.core.min.js');
			$document->addScript(JURI::root().$this->element['path'].'js/jquery.ui.sortable.min.js');
			$document->addScript(JURI::root().$this->element['path'].'js/jquery.ui.button.min.js');
			$document->addScript(JURI::root().$this->element['path'].'js/jquery.ui.dialog.min.js');
					
			//Add css         
			$document->addStyleSheet(JURI::root().$this->element['path'].'css/chosen.css');        
			
		}else{
			JHtml::_('jquery.ui');
			JHtml::_('jquery.ui', array('sortable'));
		}
		//add javascript

		$document->addScript(JURI::root().$this->element['path'].'js/jquery.json.min.js');
		$document->addScript(JURI::root().$this->element['path'].'js/btslideshow.min.js');
		$document->addScript(JURI::root().$this->element['path'].'js/bt.js');
		$document->addScript(JURI::root().$this->element['path'].'js/filedrop.js');
		$document->addScript(JURI::root().$this->element['path'].'js/jquery.ui.dialog.min.js');
		
		//add css  
		$document->addStyleSheet(JURI::root().$this->element['path'].'css/bt.css');			
		$document->addStyleSheet(JURI::root().$this->element['path'].'/css/btslideshow.css');
		$document->addStyleSheet(JURI::root().$this->element['path'].'/css/jquery.ui.dialog.css');
				
		return null;
    }
}
?>