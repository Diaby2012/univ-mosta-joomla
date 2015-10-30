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

class JFormFieldHtml5Upload extends JFormField {

    protected $type = 'html5upload';

    protected function getInput() {
        $moduleID = intval($this->form->getValue('id'));
        $moduleURL = JURI::root() . 'modules/mod_bt_apb';
		$class = $this->element['class'] ? (string) $this->element['class'] : '';
		
        $html = '
		<div id="bt-drop-area" class="dropbox ' . $class . '">
			<div class="select-button">
				<input name="images[]" type="file" id="bt-input-file" multiple="multiple"/>
				<span>' . JText::_('SELECT_IMAGES') . '</span>
			</div>
			<p>' . JText::_('DRAG_AND_DROP_TO_UPLOAD') . '</p>
		</div>
		<script type="text/javascript">
		jQuery(document).ready(function($){	
			$("#bt-drop-area").filedrop({
				paramname: "bt_images",
				maxfiles: 10,
				maxfilesize: 2,
				url: "' . JURI::root() . 'administrator/index.php",
				data: {
					"action" : "upload",
					"option" : "com_modules",
					"view" :  "module",
					"layout" : "edit",
					"id" : ' . $moduleID . ',
					"' . JSession::getName() . '": "' . JSession::getId() . '",
					"' . JSession::getFormToken() . '" : 1
				},	
				
				
				error: function(err, file) {
					switch(err) {
						case "BrowserNotSupported":
							BTSlideshow.showMessage("#btss-message", "Your browser does not support HTML5 file uploads!");
							break;
						case "TooManyFiles":
							BTSlideshow.showMessage("#btss-message", "Too many files! Please select 5 at most!");
							break;
						case "FileTooLarge":
							BTSlideshow.showMessage("#btss-message", file.name+ " is too large! Please upload files up to 2MB.");
							break;
						default:
							break;
					}
					inProgess = false;
					BTSlideshow.removeLog();
				},
				drop: function() {
					inProgess = true;
					BTSlideshow.showMessage("#btss-message", "Loading images... <span class=\"btss-upload-spinner\"></span>");
				},
				// Called before each upload is started
				beforeEach: function(file){
					if(!file.type.match(/^image\//)){
						BTSlideshow.showMessage("#btss-message", file.name + " can\'t be uploaded. Only images are allowed!");
						inProgess = false;
						BTSlideshow.removeLog();
						return false;
					}
					
				},
				uploadStarted:function(i, file, len){
					BTSlideshow.showMessage("#btss-message", "<div id=\"btss-upload-file-" + i + "\">Loading <b>" + file.name + "</b><span class=\"btss-upload-spinner\"></span></div>");
				},
				uploadFinished: function(i, file, response, time) {					
					if (!response.success) {
						jQuery("#btss-upload-file-"+i).append("<span style=\"color: red;\"> " + response.message +"</span>");
					}
					else {
						var file = response.files;
						var item = {
							  file: file.filename,
							  title: file.title
						};
						BTSlideshow.add(item, true);
						jQuery("#btss-upload-file-"+i).append("<span style=\"color: green;\"> Completed</span>");
					}
											
				},
				
				afterAll: function(){
					inProgess = false;
					BTSlideshow.removeLog();
				}
				 
			});
			
			//select files
			jQuery("#bt-input-file").change(function (e) {  
				var i = 0, len = this.files.length, reader, file, formdata;  
				
				inProgess = true;
				
				jQuery("#bt-input-file").parent().addClass("disabled");
				jQuery("#bt-input-file").next("span").html("Uploading...");
				jQuery("#bt-input-file").attr("disabled", "disabled");
				
				BTSlideshow.showMessage("#btss-message", "Loading images... <span class=\"btss-upload-spinner\"></span>");
                
				
				if(len > 10){
					BTSlideshow.showMessage("#btss-message", "Too many files! Please select 5 at most!");
					setTimeout(function(){
						jQuery("#bt-input-file").parent().removeClass("disabled");
						jQuery("#bt-input-file").next("span").html("' . JText::_('SELECT_IMAGES') . '");
						jQuery("#bt-input-file").removeAttr("disabled");
						inProgess = false;
						BTSlideshow.removeLog();
					}, 1000);
					return false;
				}
				
				upload(this.files, 0);
              
			});
			function upload(files, i){
				if(i >= files.length) {
					jQuery("#bt-input-file").parent().removeClass("disabled");
					jQuery("#bt-input-file").next("span").html("' . JText::_('SELECT_IMAGES') .'");
					jQuery("#bt-input-file").removeAttr("disabled");
					inProgess = false;
					BTSlideshow.removeLog();
					return;
				}
				

				if (!files[i].type.match(/image.*/)) {  
					BTSlideshow.showMessage("#btss-message", files[i].name + " can\'t be uploaded. Only images are allowed!");
					upload(files, ++i);
					return;
				}
				if( files[i].size > 2 * 1024 * 1024 ){
					BTSlideshow.showMessage("#btss-message", files[i].name + " is too large! Please upload files up to 2MB.");
					upload(files, ++i);
					return;
				}					
				
				
				BTSlideshow.showMessage("#btss-message", "<div id=\"btss-upload-file-" + i + "\">Loading <b>" + files[i].name + "</b><span class=\"btss-upload-spinner\"></span></div>");
				
				//prepare data and submit ajax
				formdata = new FormData();
				formdata.append("bt_images", files[i]);
				formdata.append("action", "upload");
				if (formdata) {					
					$.ajax({
						type: "POST",
						dataType: "JSON",
						url: location.href,
						data: formdata,
						success: function (response) {
							if (!response.success) {
								jQuery("#btss-upload-file-"+i).append("<span style=\"color: red;\"> " + response.message +"</span>");
							}else{	
								var file = response.files;
								var item = {
									  file: file.filename,
									  title: file.title
								};
								BTSlideshow.add(item, true);
								$("#btss-upload-file-"+i).append("<span style=\"color: green;\"> Completed</span>");
							}
							upload(files, ++i);	
						},
						cache: false,
						contentType: false,
						processData: false
					});
				}
			}
			
			
		});	
        </script>';
		return $html;
    } 
	
	
}
?>
