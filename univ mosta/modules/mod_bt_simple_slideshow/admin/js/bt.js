jQuery(document).ready(function($){
	var parent = 'li:first';
	if($(".row-fluid").length){
		parent = 'div.control-group:first';
	}
	$('#bt-drop-area').parents(parent).find('.control-label, label').hide();
	$('#bt-drop-area').parents('.controls').css('margin-left', '0px');
	$('#btss-message').parent().css('margin-left','0px');
	$("#jform_params_asset-lbl").parents(parent).remove();
	$("#jform_params_ajax-lbl").parents(parent).remove();
	$("#jform_params_warning-lbl").parents(parent).remove();
	$('#module-sliders li').append($('<div>').css('clear', 'both'));
	$('#module-sliders li > .btn-group').each(function(){
		if($(this).find('input').length != 2 ) return;
		if(this.id.indexOf('advancedparams') ==0) return;
		$(this).hide();
		var group = this;
		var el = $(group).find('input:checked');	
		var switchClass ='';

		if(el.val()=='' || el.val()=='0' || el.val()=='no' || el.val()=='false'){
			switchClass = 'no';
		}else{
			switchClass = 'yes';
		}
		var switcher = new Element('div',{'class' : 'switcher-'+switchClass, 'id': $(this).attr('id') + '_switcher'});
		switcher.inject(group, 'after');
		switcher.addEvent("click", function(){
			var el = $(group).find('input:checked');	
			if(el.val()=='' || el.val()=='0' || el.val()=='no' || el.val()=='false'){
				switcher.setProperty('class','switcher-yes');
			}else {
				switcher.setProperty('class','switcher-no');
			}
			
			$(group).find('input:not(:checked)').attr('checked',true);
		});
	})
	
	
	$(".pane-sliders select").each(function(){
		if(this.id.indexOf('advancedparams') ==0) return;
		var width = 0
		
		width = parseInt($(this).width())+40;
		$(this).css("width",width>150? width:150);
		$(this).chosen();
		
	});	
	
	$(".pane-sliders textarea").parent().css('overflow','hidden');
	$(".bg-pattern").parent().css('overflow','hidden');
	$(".bg-pattern").parent().css('width','100%');
	
	$(".chosen-container").click(function(){
		$(".panel .pane-slider,.panel .panelform").css("overflow","visible");	
	})
	$(".panel .title").click(function(){
		$(".panel .pane-slider,.panel .panelform").css("overflow","hidden");		
	});
	
	// Group element
	$(".bt_control").each(function(){ 
		if($(this).parents(parent).css('display')=='none' ) return;
		$(this).change(function(){
			var name = this.id.replace('jform_params_','');
			var parent = 'li:first';
			if($(this).parent().hasClass('controls')){
				parent = 'div.control-group:first';
			}
			$(this).find('option').each(function(){
					$('.'+name+'_'+this.value).each(function(){
						$(this).parents(parent).hide();
					})
				})
				
				$('.'+name+'_'+$(this).find('option:selected').val()).each(function(){
					$(this).parents(parent).fadeIn(500);
			})
		});
		$(this).change();
	});
})