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
jimport('joomla.html.parameter');
require_once JPATH_ROOT . '/modules/mod_bt_simple_slideshow/helpers/images.php';

class JFormFieldAjax extends JFormField {

    protected $type = 'ajax';
    private $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
    private $saveDir = '';
    private $result = array('success' => false, 'message' => '');
    private $items = array();
	private $moduleID = 0;
	
    protected function getInput() {
        /**
         * Lấy các ảnh đã có 
         */
        $this->params = $this->form->getValue('params');
		$items = array();
		if($this->params){
			if($this->params->gallery){
				$items = json_decode($this->params->gallery);
			}
			foreach ($items as $item) {
				$this->items[] = $item->file;
			}
		}
        $this->saveDir = JPATH_ROOT .'/modules/mod_bt_simple_slideshow/images';
        if (JRequest::get('post') && JRequest::getString('action')) {
			$obLevel = ob_get_level();
			while ($obLevel > 0 ) {
				ob_end_clean();
				$obLevel --;
            }
            echo self::doPost();
            exit;
        }
    }

    private function doPost() {

        /**
         * xử lý dành cho load photset của flickr và album của photoset
         */
        $this->moduleID = JRequest::getInt('id', 0);
        if (JRequest::getString('action')) {
            //kiem tra quyen ghi cua cac thu muc trong thu muc images
            if (!$this->isWritable()) {
                return json_encode($this->result);
            } else {
                /**
                 * Check login & permission
                 */
                $isAdmin = JFactory::getApplication()->isAdmin();
                if (!$isAdmin) {
                    $this->result['message'] = JText::_('JERROR_ALERTNOAUTHOR');
                    return json_encode($this->result);
                } else {
                    /**
                     * Xử lý dành riêng cho uploadify
                     */
                    //nếu là uploadify
					if(JRequest::getString('action') == 'upload'){
						if (!empty($_FILES)) {
							return $this->html5upload();
						} 
					}else if(JRequest::getString('action') == 'delete'){
						return $this->delete();
					}
                }
            }
        }		
    }
	

	/**
	 * Handle uploadify ajax request
	 */
	function html5upload(){
		$validated = true;
		$file = $_FILES['bt_images']['tmp_name'];

		$objFile = new stdClass();
		$extension = explode('.', $_FILES['bt_images']['name']);
		$extension = strtolower($extension[count($extension) - 1]);
		if (in_array($extension, $this->allowedExtensions)) {
			$hashedName = md5($this->moduleID . '-' . 'upload-' . substr($_FILES['bt_images']['name'], 0, strrpos($_FILES['bt_images']['name'], '.')));
			$filename = "{$hashedName}.{$extension}";

			if (!JFile::exists($this->saveDir . "/tmp/manager/{$filename}") && !in_array($filename, $this->items)) {

				if (!JFile::upload($file, "{$this->saveDir}/tmp/original/{$filename}")) {
					$this->result['message'] = JText::_('ERROR_COULD_NOT_SAVE');
					$validated = false;
				} else {
					BTImageHelper::resize($this->saveDir . "/tmp/original/{$filename}", $this->saveDir . "/tmp/manager/{$filename}", 128, 96);
					$objFile->filename = $filename;
					$objFile->title = $filename;
				}
			} else {
				$this->result['message'] = JText::_('FILE_EXISTED');
				$validated = false;
			}
		} else {
			$this->result['message'] = JText::_('FILE_EXTENSION_INVALID');
			$validated = false;
		}
		if ($validated) {
			$this->result['success'] = true;
			$this->result['files'] = $objFile;
		}
		return json_encode($this->result);
	}
	
	/**
	 * Delete image
	 */
	function delete(){
		$file = basename(JRequest::getString('file'));                            
		try {
			JFile::delete($this->saveDir . '/manager/' . $file);
			JFile::delete($this->saveDir . '/original/' . $file);
			JFile::delete($this->saveDir . '/slideshow/' . $file);
			JFile::delete($this->saveDir . '/thumbnail/' . $file);
			JFile::delete($this->saveDir . '/tmp/manager/' . $file);
			JFile::delete($this->saveDir . '/tmp/original/' . $file);
			$this->result['success'] = true;
			$objFile = new stdClass();
			$objFile->filename = $file;
			$this->result['success'] = true;
			$this->result['files'] = $objFile;
		} catch (Exception $ex) {
			$this->result['message'] = $ex->getMessage();
		}
		return json_encode($this->result);
	}
	
	
	
	/**
	 * Check writable permission 
	 */
	function isWritable(){
		if (
			!is_writable($this->saveDir .'/tmp')
			|| !is_writable($this->saveDir . '/slideshow')
			|| !is_writable($this->saveDir . '/thumbnail')
			|| !is_writable($this->saveDir . '/manager')
			|| !is_writable($this->saveDir . '/original')
		) {
			$this->result['message'] = JText::_('ERROR_SAVE_DIR_NOT_WRITABLE');
			return false;
		}
		return true;
	}
	
}

?>