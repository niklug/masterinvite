<?php 
// No direct access to this file
defined('_JEXEC') or die;

//button class
$size = $params->get('size');

$button_class = 'cyan';
if($size == 'big') {
    $button_class = 'bigcyan';
} 

// affiliate id
$aid = JRequest::getVar('aid');
$session = JFactory::getSession();
if($aid) {
    $session->set('aid', $aid);
}

if(!$aid) {
    $aid = $session->get('aid');
}



?>
<style>
    #insert_iad {
        background-color: #FFFFFF;
        border: 1px solid #CCCCCC;
        border-radius: 10px 10px 10px 10px;
        height: 160px;
        left: 50%;
        margin-left: -250px;
        margin-top: -80px;
        position: fixed;
        top: 50%;
        width: 500px;
        z-index: 999;
    }
    #aid_label {
        padding-bottom: 10px;
        padding-right: 0;
        text-transform: none;
    }
    
    .hideimage{
        cursor: pointer;
        margin: 5px;
        position: absolute;
        right: 0;
        top: 0;
    }
    
    
    .bigcyan {
        background: none repeat scroll 0 0 #8AD5DA;
        border-radius: 12px;
        color: #FFFFFF;
        font-family: Arial,Helvetica,sans-serif;
        font-size: 22px;
        font-weight: bold;
        height: 80px;
        line-height: 1;
        padding: 12px 0 !important;
        text-align: center;
        text-transform: uppercase;
        width: 400px;
    }
    
    .bigcyan:hover {
        background: none repeat scroll 0 0 #21CEDA;
    }

</style>
<div style="text-align: center">
    <button class="<?php echo $button_class ?>"  id="aff_registration"  onclick="checkAffId()"><?php echo JText::_('BUTTON_TEXT') ?></button>
    <br/>
    <div id="insert_iad" style="display:none;" >
        <br/>
        <label id="aid_label" for="aid_field"><?php echo JText::_('INSERT_AID') ?></label>
        <br/>
        <input type="text" name="aid"  id="aid_field"  value=""  />
        <br/>
        <button class="cyan"  id="aff_registration"  onclick="checkAffId()"><?php echo JText::_('CONTINUE') ?></button>
        <img class="hideimage" src="administrator/components/com_member_banner/images/close.png" alt="close" title="close" onclick="hidePopup()">
    </div>
</div>

<script type="text/javascript">
    
    function checkAffId() {
        var aid = '<?php echo $aid ?>';

        if(!aid) {
            jQuery("#insert_iad").show();
            
        }
        
        var aid_input = jQuery("input[name=aid]").val();
        
        if(aid_input) {
            aid = aid_input;
        }
 
        
            jQuery.ajax({
                type : "GET",
                url : "index.php?option=com_member&tmpl=component&<?php echo JSession::getFormToken(); ?>=1",
                data : {
                    //-------------------------
                    // Required for Authentication
                    //-------------------------
                    view : 'member',
                    format : 'json',
                    task : 'checkAffId',
                    //--------------------------
                    // Custom Query data.
                    //--------------------------
                    aid : aid

                },
                dataType : 'text',
                success : function(message) {
                    
                    if(message == 1) {
                        document.location.href= '<?php echo JUri::base()?>index.php?option=com_akeebasubs&view=level&layout=default&format=html&slug=members-plan&aid=' +  aid;
                    } else {
                        jQuery("#insert_iad").show();
                        if(aid_input) {
                            jQuery("#aid_label").html('<?php echo JText::_('WRONG_AID'); ?>');
                        }
                        
                        jQuery("input[name=aid]").val('');
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown)
                {
                    alert("error");
                }
            });
        
        
    }
    
    
    function hidePopup() {
        jQuery("#insert_iad").hide();
    }

</script>
