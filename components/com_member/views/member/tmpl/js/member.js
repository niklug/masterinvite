//  on document load events //
jQuery(function() {
    jQuery( "#ptabs" ).tabs();
    
    jQuery( "#c1tabs" ).tabs({
        beforeLoad: function(event, ui){
            //alert('loading');
            jQuery("#loader").show();
            
           
 
        },
        load: function(event, ui){
             //alert('loaded');
            jQuery("#loader").hide();
            

        }
    });
    
   
    
    jQuery( "#c2tabs" ).tabs({
        beforeLoad: function(event, ui){
            //alert('loading');
            jQuery("#loader").show();
            
           
 
        },
        load: function(event, ui){
             //alert('loaded');
            jQuery("#loader").hide();
            

        }
    });
    

    /** on click on  my account tab handler, activate akeeba validation
     *
     */
    jQuery("#myaccount").on('click', function() {
        var url = "index.php?option=com_akeebasubs&view=userinfo&tmpl=component";
        var lang= jQuery('select.inputbox option:selected').html();
        if(lang) {
            url = "index.php/nl/?option=com_akeebasubs&view=userinfo&tmpl=component";
        }
        jQuery("#loader").show();
        jQuery.ajax({
            type: "POST",
            url: url,
            success: function(data)
            {
                
                jQuery("#ptabs-3").html(data);
                jQuery('#saveaccount').bind('click', function() {
                    saveAccount();
                });
                jQuery("#system-message-container").hide();
                
                // akeeba validation
                validatePassword();
                validateName();
                validateEmail();
                jQuery("#loader").hide();
               
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                alert("error");
            }
        });
    });
 

    
    
    
 
 /** save payment data handler
  *
  */
  jQuery("#request_submit_payment").click( function(event) {
       jQuery("#loader").show();
       jQuery("#request_submit_payment").attr("disabled", "disabled");
       jQuery.ajax({
            type : "POST",
            url : "index.php?option=com_member&tmpl=component&view=member&format=json&task=savePaymentData",
            data: jQuery("#save_payment_method").serialize(),
            dataType : 'text',
            success : function(message) {
                
                if(message != '1') alert(message);
                
                
                setTimeout(function(){
                    jQuery("#loader").hide();
                    jQuery("#request_submit_payment").removeAttr("disabled");
                }, 2000); 

            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                alert("error");
            }
        });
        return false;

    });



});


// ***************************FUNCTIONS***************************************//

/** save akeeba user profile handler
 *
 */
function saveAccount() {
    jQuery("#loader").show();
    jQuery("#saveaccount").attr("disabled", "disabled");
    var url = "index.php?option=com_akeebasubs&view=userinfo&tmpl=component"; 

    jQuery.ajax({
        type: "POST",
        url: url,
        data: jQuery("#userinfoForm").serialize(), 
        success: function(data)
        {
            
            setTimeout(function(){
                jQuery("#loader").hide();
                jQuery("#saveaccount").removeAttr("disabled");
            }, 2000); 

            
        },
        error: function(XMLHttpRequest, textStatus, errorThrown)
        {
            alert("error");
        }
    });
    // avoid to execute the actual submit of the form.
    return false; 
}
    
    
    
    
/** email validation
 * 
*/
function IsEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}


/** pay now button at renew by user
 * 
 */
function pay_now() {
    jQuery("#loader").show();
    var url = "index.php?option=com_akeebasubs&view=level&layout=default&format=html&slug=members-plan&tmpl=component&template_short=1";

    jQuery.ajax({
        type: "POST",
        url: url,
        success: function(data)
        {
         
            jQuery("#ptabs-2").html(data);
            
            // akeeba validation
            validatePassword();
            validateName();
            validateEmail();
            
            setTimeout(function(){
                jQuery("#loader").hide();
            }, 2000); 

        },
        error: function(XMLHttpRequest, textStatus, errorThrown)
        {
            alert("error");
        }
    });
   
}

/** on choose payment method logic
 * 
 */
function choosePayoutMethod(value) {
    var method_id = value;
    switch(method_id) {
  
        case '1' :
            jQuery("#saving").show();
            jQuery("#tobank").hide();
            jQuery("#topaypal").hide();
            break;
        case '2' :
            jQuery("#saving").hide();
            jQuery("#tobank").show();
            jQuery("#topaypal").hide();
            break;
        case '3' :
            jQuery("#saving").hide();
            jQuery("#topaypal").show();
            jQuery("#tobank").hide();
            break;
        default :
            jQuery("#saving").show();
            jQuery("#tobank").hide();
            jQuery("#topaypal").hide();
            break;
    }

}


