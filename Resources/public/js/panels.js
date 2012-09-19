jQuery(document).ready(function(){
    
    var options = jQuery('#neutron-panels').data('options');
    
    jQuery('#neutron-plugin-containers').accordion({
        active: false, 
        collapsible: true, 
        autoHeight: false,
        animated: false
    });
	
    var dlg = jQuery( "#neutron-panels-dialog" );
    dlg.dialog({
        autoOpen: false,
        modal: true,
        minWidth: 500,
        close: function(event, ui) {
            var item = jQuery(this).data('item');
            var parent = item.parent();
            if(item.find(':hidden').eq(0).val().length == 0){
                item.remove();
                var widgets = parent.find('.widget');
            
                if(widgets.length == 0){
                    parent.find('p.desc').show();
                }
                
                jQuery.each(widgets, function(k,v){
                    jQuery(this).find(':hidden').eq(5).val(k);
                });            
            }
            
            jQuery('.dlg-error-msg').hide();
            jQuery(this).data('item', null);
            jQuery('#neutron-widget-reference').html('');
            jQuery.uniform.update('#neutron-widget-reference');
        }
    });
    
    jQuery('.widget a.edit-btn').live('click', function(){
       
        dlg.data('item', jQuery(this).parent());
        openDialog(dlg, options, jQuery(this).parent().find(':hidden').eq(0).val());
        return false;
   
    });
    
    jQuery('.widget a.delete-btn').live('click', function(){
        var widget = jQuery(this).parent();
        var parent = jQuery(this).parent().parent();
        widget.fadeOut(function(){
            jQuery(this).remove();
            var widgets = parent.find('.widget');
            
            if(widgets.length == 0){
                parent.find('p.desc').show();
            }
            
            jQuery.each(widgets, function(k,v){
                jQuery(this).find(':hidden').eq(5).val(k);
            });   
        });

        return false;
    });
    
    
    createButtons();
	
    jQuery('#neutron-panels-dialog-save').button().click(function(){
        jQuery('.dlg-error-msg').hide();
        
        var item = dlg.data('item'); 
        
        var widget_identifier = jQuery('#neutron-widget-reference').val();
   
        var label = jQuery('#neutron-widget-reference')
    		.find('option[value="'+ widget_identifier +'"]').text();

        if(label.length > 35){
            label = label.substr(0, 35);
        }
 
        if(widget_identifier.length == 0){
            jQuery('#dlg-error-msg-blank').fadeIn();
            return false;
        } else if(item.parent().find(':hidden[value="'+ widget_identifier +'"]').length > 0){
        	jQuery('#dlg-error-msg-exist').fadeIn();
            return false;
        }

        var formElms = item.find(':hidden');
        formElms.eq(0).val(widget_identifier);
        formElms.eq(1).val(options.category);
        formElms.eq(2).val(item.data('widget').name);
        formElms.eq(3).val(options.plugin);
        formElms.eq(4).val(item.parent().data('panel').name);

        
        item.find('.widget-label').text(label);
        dlg.dialog('close');
        return false;
    });
    
    jQuery( "#widget-list .widget" ).hover(
        function(){
            var widgetOptions = jQuery(this).data('widget');
            
            if(widgetOptions.isPanelAware){
                jQuery.each(widgetOptions.allowedPanels, function(index, panel){
                    jQuery('#' + panel).addClass('accepted');
                });
            } else {
                jQuery('#neutron-plugin-containers .neutron-sortable').addClass('accepted');
            }
        },
        function(){
            var widgetOptions = jQuery(this).data('widget');
            
            if(widgetOptions.isPanelAware){
                jQuery('#neutron-plugin-containers .neutron-sortable')
                    .removeClass('accepted');
            } 
        }
    );
	
    jQuery( ".neutron-sortable" ).sortable({
        revert: true,
        items: 'div.widget',
        placeholder: "ui-state-highlight",
        forceHelperSize: true,
        forcePlaceholderSize: true,
        beforeStop: function(event, ui) {
			
            if(ui.item.find('a').length > 0){
                return;
            }
            
            ui.item.parent().find('p.desc').hide();
            
            var widgetNum = ui.item.parent().find('.widget').length - 1;
            var prototype = ui.item.parent().data('prototype');
            var widgetHtml = jQuery(prototype).find('div').html().replace(/__name__/g, widgetNum);

            ui.item.append(widgetHtml);
            ui.item.append('<a class="delete-btn" href="#" style="float:right"></a>');
            ui.item.append('<a href="#" class="edit-btn" style="float:right"></a>');
            
    
           
            dlg.data('item', ui.item);
            createButtons();
            openDialog(dlg, options, null);
 
        },
        update: function(event, ui) {
            var widgets = ui.item.parent().find('.widget');
            
            if(widgets.length == 0){
                ui.item.parent().find('p.desc').show();
            }
  
            jQuery.each(widgets, function(k,v){
                jQuery(this).find(':hidden').eq(5).val(k);
            });
        }

    });
    
	
    jQuery( "#widget-list .widget" ).draggable({
        connectToSortable: ".neutron-sortable.accepted",
        helper: "clone",
        revert: "invalid",
        stack: '.neutron-sortable',
        zIndex: 2700,
        start: function(event, ui){
            var widgetOptions = ui.helper.data('widget');
            if(widgetOptions.isPanelAware){
                jQuery.each(widgetOptions.allowedPanels, function(k,v){
                    jQuery('#' + v).addClass('ui-state-highlight')
                        .prev().addClass('ui-state-highlight');
                });
            }
            
        },
        stop: function(event,ui){
            var widgetOptions = ui.helper.data('widget');
            
            if(widgetOptions.isPanelAware){
                jQuery('#neutron-plugin-containers .neutron-sortable')
                    .removeClass('ui-state-highlight')
                    .prev().removeClass('ui-state-highlight');
            }   
        }
        
    });
    
    jQuery('.neutron-sortable').droppable({
        drop: function (event, ui) {
            jQuery(this).sortable("refresh");
        },
        tolerance: "touch"
    });
	
    jQuery( ".neutron-sortable" ).disableSelection();
	
	
});

function createButtons()
{
    jQuery( ".widget a.edit-btn" ).button({
        icons: {
            primary: "ui-icon-pencil"
        },
        text: false
    });
    
    jQuery( ".widget a.delete-btn" ).button({
        icons: {
            primary: "ui-icon-trash"
        },
        text: false
    });
}

function openDialog(dlg, options, selectedOption)
{   
    var widget = dlg.data('item').data('widget').name;
    
    jQuery.ajax({
        async : false,
        type: 'POST',
        url: options.widget_instance_url, 
        data: {
            'name': widget
        },
        success : function(response) {
            if (response == undefined || response == null){
                dlg.data('item').data('widget', null);
                dlg.dialog('close');	
                return false;
            }		

            var html = '<option value="" >'+ options.instances_empty_value +'</option>';
            jQuery.each(response, function(k,v){
                html += '<option value="'+ v.identifier +'">'+ v.label +'</option>';
            });

            jQuery('#neutron-widget-reference').html(html);
            
            if(selectedOption){
                jQuery('#neutron-widget-reference')
                    .find('option[value="'+selectedOption+'"]').attr('selected', true);
            }
            
            jQuery.uniform.update('#neutron-widget-reference');
            dlg.dialog('open');
        },
        error: function(){
            dlg.data('item').remove();
        }
    });

}