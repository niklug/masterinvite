<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>

<div id="ptabs">
    <ul>
        <li><a href="#ptabs-1"><?php echo JText::_('COM_MEMBER_AFFILIATE_PROGRAM') ?></a></li>
        <li><a href="#ptabs-2"><?php echo JText::_('COM_MEMBER_BILLING_AND_CREDIT') ?></a></li>
        <li><a id="myaccount" href="#ptabs-3"><?php echo JText::_('COM_MEMBER_ACCOUINT_DETAILS') ?></a></li>

    </ul>
    <div id="ptabs-1">
        <div id="c1tabs" >
            
            <ul>
                <li><a href="index.php?option=com_member&view=overview&tmpl=component"><?php echo JText::_('COM_MEMBER_OVERVIEW') ?></a></li>
                <li><a href="index.php?option=com_member_banner&view=banners&tmpl=component"><?php echo JText::_('COM_MEMBER_BANNERS') ?></a></li>
                <li><a href="#c1tabs-3"><?php echo JText::_('COM_MEMEBER_EMAIL') ?></a></li>
                <li><a href="index.php?option=com_content&view=article&tmpl=component&id=257"><?php echo JText::_('COM_MEMEBER_SELFPRINTING_MATERIAL') ?></a></li>
                <li><a href="index.php?option=com_content&view=article&tmpl=component&id=258"><?php echo JText::_('COM_MEMEBER_ORDER_PRINTING_MATERIAL') ?></a></li>
                <li><a href="#c1tabs-6"><?php echo JText::_('COM_MEMBER_PAYMENT_DATA') ?></a></li>

            </ul>
            <div id="c1tabs-1" >
                
            </div>
            <div id="c1tabs-2">

            </div>
            <div id="c1tabs-3">
                
                <?php echo $this->loadTemplate('email');?>
            </div>
       
            <div id="c1tabs-4">

            </div>
            <div id="c1tabs-5">
                
            </div>
            
            <div id="c1tabs-6">
                
                <?php echo $this->loadTemplate('paymentdata');?>
            </div>
           
        </div>
    </div>
    <div id="ptabs-2">
        <div id="c2tabs">
            <ul>
                <li><a href="index.php?option=com_akeebasubs&view=subscriptions&tmpl=component"><?php echo JText::_('COM_MEMBER_BILLING') ?></a></li>
                <li><a href="index.php?option=com_member&view=credit&tmpl=component"><?php echo JText::_('COM_MEMBER_CREDITS') ?></a></li>

            </ul>
            <div id="c2tabs-1">
                
            </div>
  
            <div id="c2tabs-2">
                
            </div>
        </div>
        
    </div>
    <div id="ptabs-3">
       
    </div>
    
    <div id="ajaxloader" style="width: 100%;text-align: center">
        <img id="loader" style="disply:none;"  src="components/com_member/views/member/tmpl/images/ajax-loader.gif" alt="loading...">
    </div>
    
</div>



