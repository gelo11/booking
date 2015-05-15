/* 
 * Some additional js
 */
jQuery(document).ready(function() {
    //disable draggable and enable resizable
    jQuery('#disable_draggable').live('click', function() {
        var token = jQuery("input[name=YUPE_TOKEN]").val();
        var items = jQuery('.droppable-list').find('li');
        if (jQuery(this).is(':checked')) {
            items.draggable({containment: 'parent', cursor: 'move'}).draggable('destroy');
            items.resizable({
                maxWidth: parseInt(jQuery('.droppable-image').width()),
                maxHeight: parseInt(jQuery('.droppable-image').height()),
                containment: "parent",
                stop: function(event, ui) {
                    var item_id = parseInt(jQuery(this).attr('data-id'));
                    if (!item_id) {
                        var html_id = String(jQuery(this).attr('id'));
                        var num_id = parseInt(html_id.replace(/[^0-9]+/g, '')) + 1;
                        html_id = String(jQuery('#items_modal_' + num_id).find("form").attr('id'));
                        item_id = parseInt(html_id.replace(/[^0-9]+/g, ''));
                    }
                    var size = ui.size;
                    if (item_id > 0) {
                        jQuery.ajax({
                            'url': '/booking/bookingitemsposition/update/' + item_id,
                            'type': "POST",
                            'data': {"BookingItemsPosition[height]": size.height, "BookingItemsPosition[width]": size.width, YUPE_TOKEN: token},
                            'success': function(r) {
                                jQuery(".drop-alert").addClass("alert-success");
                                jQuery(".drop-alert").removeClass("alert-error");
                                jQuery(".drop-alert b").html(r);
                                jQuery(".drop-alert").animate({opacity: 1}).delay(4000).animate({opacity: 0});
                            },
                            'error': function(r) {
                                jQuery(".drop-alert").addClass("alert-error");
                                jQuery(".drop-alert").removeClass("alert-success");
                                jQuery(".drop-alert b").show().html(r);
                            }
                        });
                        jQuery('#BookingItemsPosition_width_' + item_id).val(size.width);
                        jQuery('#BookingItemsPosition_height_' + item_id).val(size.height);
                    }
                }
            });
            jQuery('.draggable-list').slideUp();
        } else {
            items.draggable({containment: 'parent', cursor: 'move'}).resizable('destroy');
            jQuery('.draggable-list').slideDown();
        }
    });
    jQuery('.redactor').redactor();
    jQuery('.colorpicker').colorpicker();
    
    hideFinishDate(jQuery('#BookingVariants_no_finish_date'));

    jQuery('#BookingVariants_no_finish_date').live('click', function() {
        hideFinishDate(jQuery(this));
    });
    
    jQuery('.whole-item').live('click', function() {
        hideSeats(jQuery(this));
    });
    
});


function hideFinishDate(o) {
    if (o.is(':checked')) {
        $('#BookingVariants_finish_date').parents('.span4').hide();
    } else {
        $('#BookingVariants_finish_date').parents('.span4').show();
    }
}

function hideSeats(o) {
    if (o.is(':checked')) {
        o.parents('.control-group').next('.control-group').hide();
    } else {
        o.parents('.control-group').next('.control-group').show();
    }
}