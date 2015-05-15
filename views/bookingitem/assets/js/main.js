/* 
 * Some additional js
 */
jQuery(document).ready(function() {
    
    hideSeats(jQuery('.whole-item'));
    
    jQuery('.whole-item').live('click', function() {
        hideSeats(jQuery(this));
    });
    
});

function hideSeats(o) {
    if (o.is(':checked')) {
        o.parents('.control-group').next('.control-group').hide();
    } else {
        o.parents('.control-group').next('.control-group').show();
    }
}