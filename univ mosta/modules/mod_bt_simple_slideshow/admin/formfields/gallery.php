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
// No direct access
defined('_JEXEC') or die;
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class JFormFieldGallery extends JFormField {

    protected $type = 'gallery';
    public $_name = 'gallery';

    protected function getLabel() {
        return '';
    }

    protected function _build($moduleID, $name, $value) {
        
		
        // Remove temp files
        $items = json_decode($value);

        //check if 0 folder exists
        $saveDir = JPATH_SITE . '/modules/mod_bt_simple_slideshow/images';
        if ($moduleID != 0 && JFolder::exists($saveDir . '/0')) {
            JFolder::move($saveDir . '/0', $saveDir . '/' . $moduleID);
        }

        //load file if miss save
        $originalDir = $saveDir . '/' . $moduleID . '/original';
        if (is_dir($originalDir)) {
            $open = opendir($originalDir);
            $arrFiles = array();
            $filename = readdir($open);
            while ($filename !== false) {
                //check validated file
                if (filetype($originalDir . '/' . $filename) == "file") {
                    $existed = false;
                    if (count($items) > 0) {
                        foreach ($items as $item) {
                            if ($item->file == $filename) {
                                $existed = true;
                                break;
                            }
                        }
                    }
                    if (!$existed) {
                        $objFile = new stdClass();
                        $objFile->file = $filename;
                        $objFile->title = '';
                        $items[] = $objFile;
                    }
                }
                $filename = readdir($open);
            }
        }
        $value = json_encode($items);

        $html = '
			<div id="btss-message" class="clearfix"></div>
            <ul id="btss-upload-list"></ul>
			<div id="btss-file-uploader">
				<noscript>
					<p>' . JText::_('NOTICE_JAVASCRIPT') . '</p>
				</noscript>
			</div>
			<input id="btss-gallery-hidden" type="hidden" name="' . $name . '" value="" />
			
			<ul class="clearfix" id="btss-gallery-container">
			</ul>
			<script type="text/javascript">
			jQuery(document).ready(function($){
				$("#btss-gallery-container").sortable({"placeholder": "sortable-placeholder"});
			});
			</script>
			';
        ?>
        <script type="text/javascript">
		jQuery(document).ready(function(){
			BTSlideshow = jQuery(document).BtGallery({
				liveUrl: '<?php echo JURI::root(); ?>modules/mod_bt_simple_slideshow/images',
				encodedItems: <?php echo $value; ?>,
				moduleID: '<?php echo $moduleID; ?>',
				galleryContainer: '#btss-gallery-container',
				dialogTemplate:       
				'<div id="btss-dialog" title="<?php echo JText::_('DIALOG_TITLE'); ?>">'+
				'<fieldset style="clear: both;" class="adminform">' +
					'<ul class="adminformlist">' +
					'<li>' +
					'<label class="btss-title-lbl" class="hasTip" title="<?php echo JText::_('FIELD_TITLE_DESC'); ?>" for="btss-title"><?php echo JText::_('FIELD_TITLE_LABEL'); ?></label>' +
					'<input class="btss-title" type="text" name="btss-title" size="90" />' +
					'</li>' +
					'<li>' +
					'<label class="btss-link-lbl" class="hasTip" title="<?php echo JText::_('FIELD_LINK_DESC'); ?>" for="btss-link"><?php echo JText::_('FIELD_LINK_LABEL'); ?></label>' +
					'<input class="btss-link" type="text" name="btss-link" size="90" />' +
					'</li>' +
					'<li>' +
					'<label class="btss-target-lbl" class="hasTip" title="<?php echo JText::_('FIELD_TARGET_DESC'); ?>" for="btss-target"><?php echo JText::_('FIELD_TARGET_LABEL'); ?></label>' +
					'<select class="btss-target" name="btss-link">' +
					'   <option value=""><?php echo JText::_('FIELD_TARGET_CURRENT') ?></option>' +
					'   <option value="_blank"><?php echo JText::_('FIELD_TARGET_BLANK') ?></option>' +
					'   <option value="window"><?php echo JText::_('FIELD_TARGET_WINDOW') ?></option>' +
					'</select>'+
					'</li>' +						
					'<li>' +
					'<label class="btss-desc-lbl" class="hasTip" title="<?php echo JText::_('FIELD_DESCRIPTION_DESC'); ?>" for="btss-desc"><?php echo JText::_('FIELD_DESCRIPTION_LABEL'); ?></label>' +
					'<textarea style="width: 375px;" class="btss-desc" name="btss-desc" rows="2" cols="20"></textarea>' +
					'</li>' +
					'</ul>' +                      
					'</fieldset>' +						
					
					'<div style="clear: both;">' +
					'<label>&nbsp;</label><button class="btss-dialog-ok btn btn-small" style="margin-left: 10px;"><?php echo JText::_('BTN_OK'); ?></button><button class="btss-dialog-cancel btn btn-small" style="margin-left: 10px;"><?php echo JText::_('BTN_CANCEL'); ?></button>'+
					'</div>' +
				   
				'</div>'	
			});
		});
        </script>
        <?php
        return $html;
    }

    protected function getInput() {


        $moduleID = $this->form->getValue('id');
        if ($moduleID == '')
            $moduleID = 0;
        $this->sync($moduleID);
        return $this->_build($moduleID, $this->name, $this->value);
    }

    private function sync($moduleID) {
        if (!JRequest::get('post')) {
            $items = json_decode($this->value);
            $itemNames = array();
            if ($items) {
                foreach ($items as $item) {
                    $itemNames[] = $item->file;
                }
            }
            $saveDir = JPATH_SITE . '/modules/mod_bt_simple_slideshow/images';

            //sync with older version
            if (JFolder::exists($saveDir . '/' . $moduleID)) {
                if ($items) {
                    foreach ($items as $olderFile) {
                        @JFile::move($saveDir . '/' . $moduleID . '/original/' . $olderFile->file, $saveDir . '/original/' . $olderFile->file);
                        @JFile::move($saveDir . '/' . $moduleID . '/manager/' . $olderFile->file, $saveDir . '/manager/' . $olderFile->file);
                        @JFile::move($saveDir . '/' . $moduleID . '/thumbnail/' . $olderFile->file, $saveDir . '/thumbnail/' . $olderFile->file);
                        @JFile::move($saveDir . '/' . $moduleID . '/slideshow/' . $olderFile->file, $saveDir . '/slideshow/' . $olderFile->file);
                    }
                }
                JFolder::delete($saveDir . '/' . $moduleID);
            } else {

                //move images after save
                if($items){
                    foreach ($items as $key => $item) {
					
						if(isset($item->remote) && $item->remote){
							if(!JFile::exists($saveDir . '/manager/' . $item->file)){
								if(JFile::exists($saveDir . '/tmp/manager/' . $item->file)){
									JFile::move($saveDir . '/tmp/manager/' . $item->file, $saveDir . '/manager/' . $item->file);
									continue;
								}else{
									 unset($items[$key]);
								}
							}
							continue;
						}
					
                        if (!JFile::exists($saveDir . '/original/' . $item->file)) {
                            if (JFile::exists($saveDir . '/tmp/original/' . $item->file)) {
                                JFile::move($saveDir . '/tmp/original/' . $item->file, $saveDir . '/original/' . $item->file);
                                JFile::move($saveDir . '/tmp/manager/' . $item->file, $saveDir . '/manager/' . $item->file);
                            }else{
                                unset($items[$key]);
                            }
                        }
                    }
					$this->value = json_encode(array_values($items));
                }
                
                //delete images if not save
                $tmpOriginalFiles = JFolder::files($saveDir . '/tmp/original');
                $config = JFactory::getConfig();
                $cacheTime = $config->get('cachetime') * 60;
				if($tmpOriginalFiles){
					foreach ($tmpOriginalFiles as $originalFile) {
						$modifiedTime = filemtime($saveDir . '/tmp/original/' . $originalFile);
						if (time() - $modifiedTime > $cacheTime && !in_array($originalFile, $itemNames)) {
							@JFile::delete($saveDir . '/tmp/original/' . $originalFile);
							@JFile::delete($saveDir . '/tmp/manager/' . $originalFile);
						}
					}
				}
            }
        }
    }

}
