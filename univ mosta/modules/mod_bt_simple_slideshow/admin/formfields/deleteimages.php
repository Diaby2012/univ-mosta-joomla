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
class JFormFieldDeleteImages extends JFormField{
    protected $type = 'deleteimages';
    protected function  getInput() {
        $html  = '<button id="btnDeleteAll">'.JText::_("BUTTON_DELETEALL").'</button>
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#btnDeleteAll").click(function(){
				if(jQuery("#btss-gallery-container li").length > 0){
					if(confirm("'.JText::_('CONFIRM_DELETE_ALL').'")){
						BTSlideshow.removeAll();	
					}	
				}
				return false;
			});
		})
		</script>
		';
        return $html;
    }

}