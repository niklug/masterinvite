//  on document load events //
jQuery(function() {


});


// ***************************FUNCTIONS***************************************//

function previewShow(id) {

    jQuery("#preview_" + id).show();
}

function hidePreview(id) {
    jQuery("#preview_" + id).hide();
}



function payCredit(user_id, amount, transactions, subscription_id, token) {
    var token = token;
    
    var subscription_id = subscription_id;
    if (confirm("Are you sure?")) {
        jQuery.ajax({
            type : "POST",
            url : "index.php?option=com_member&tmpl=component&" + token,
            data : {
                //-------------------------
                // Required for Authentication
                //-------------------------
                view : 'credits',
                format : 'json',
                task : 'payCredit',
                //--------------------------
                // Custom Query data.
                //--------------------------
                user_id : user_id,
                amount : amount,
                transactions : transactions,
                subscription_id : subscription_id
            },
            dataType : 'json',
            success : function(data) {
                
                if(data.status == true) {
                    jQuery('#paid_' + subscription_id).html('<img align="middle" title="" src="/media/com_akeebasubs/images/frontend/enabled.png">');
                }
                alert(data.message);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                alert("error");
            }
        });
    } else {
        return false;
    }
}