<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$user = &JFactory::getUser();
$username= $user->username
?>
    
<div  class="emailwrapper">

    <fieldset>
            <div class="explanation">
        <?php echo JText::_('COM_MEMBER_EMAIL_EXPLANATION')?>
    </div>
        <div class="fieldgroup">
            <label class="recipientslabel" for="recipients"><?php echo JText::_('COM_MEMBER_EMAIL_RECIPIENTS')?>:</label>
            <textarea class="lightgreypill" rows="2" cols="50" id="emailslist" name="recipients" value=""></textarea> 
        </div>
    </fieldset>
    <fieldset>
        <div >
            <label for="subject"><?php echo JText::_('COM_MEMBER_EMAIL_SUBJECT')?>:</label>
            <input class="subject"  id="subject" name="subject" value="">
        </div>
    </fieldset>
    <fieldset>
        <div >
            <label for="message"><?php echo JText::_('COM_MEMBER_EMAIL_MESSAGE')?>:</label>
            <textarea id="message_text" class="lightgreypill" rows="4" cols="50"   name="message" value=""></textarea>
        </div>
        <div class="explanation">
            <?php echo JText::_('COM_MEMBER_EMAIL_TYPE_MESSAGE')?>
            
            </br>
            <strong><a onclick="insertAffLink()" href="javascript:void(0)"> <?php echo JText::_('COM_MEMBER_EMAIL_CLICK_HERE')?></a></strong> 
            <?php echo JText::_('COM_MEMBER_EMAIL_CLICK_HERE_TO_INSERT')?>
        </div>
    </fieldset>
    <fieldset>
        <div class="sendemail">
           <button id="sendemailbutton" onclick="inviteEmail()" class="cyan"><?php echo JText::_('COM_MEMBER_EMAIL_SEND_MASS_EMAIL')?></button> 
        </div>
    </fieldset>
    <div id="emailinfo"></div>
         
</div>
    
<script type="text/javascript">
    /** send emails
     * 
     */
    function inviteEmail(){
      
        var emaillist = jQuery('#emailslist').val();
        var subject = jQuery('#subject').val();
        if(!subject) { alert('<?php echo JText::_('COM_MEMBER_EMAIL_SUBJECT_EMPTY')?>'); return;}
        var body = jQuery('#message_text').val();
        if(!body) { alert('<?php echo JText::_('COM_MEMBER_EMAIL_MESSAGE_EMPTY')?>'); return;}
        jQuery("#loader").show();
        jQuery("#sendemailbutton").attr("disabled", "disabled");    
        var emails = emaillist.split(',');
        jQuery.each(emails, function(index, email) { 
            email = jQuery.trim(email);
            if(IsEmail(email)) {
                jQuery.ajax({
                    type : "POST",
                    url : 'index.php?option=com_member&tmpl=component&<?php echo JSession::getFormToken(); ?>=1',
                    data : {
                        //-------------------------
                        // Required for Authentication
                        //-------------------------
                        view : 'member',
                        format : 'json',
                        task : 'inviteEmail',
                        //--------------------------
                        // Custom Query data.
                        //--------------------------
                        email : email,
                        subject : subject,
                        body : body
                    },
                    dataType : 'text',
                    success : function(message) {
                        //alert(message);
                        setTimeout(function(){
                            jQuery("#loader").hide();
                            jQuery("#sendemailbutton").removeAttr("disabled");
                            jQuery('#emailinfo').append(message + "</br>");
                        }, 2000); 
                        

                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown)
                    {
                        alert("error");
                    }
                });
            } else {
                alert(email + '<?php echo JText::_('COM_MEMBER_EMAIL_NOT_VALID')?> ');
                 jQuery("#loader").hide();
                 jQuery("#sendemailbutton").removeAttr("disabled");
            }

        });

    }


    function insertAffLink() {
        var affiliate_link = ' <?php echo JURI::base() . '?aid=' . $username ?> ';
        var message_text = jQuery('#message_text').val();
        message_text = message_text + affiliate_link;
        jQuery('#message_text').val(message_text);

    }
</script>
