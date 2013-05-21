<?php
/**
 * @version     1.0.0
 * @package     com_member
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Nikolay Korban <niklug@ukr.net> - http://
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_member/assets/css/member.css');
?>
<script type="text/javascript">
    
    
	Joomla.submitbutton = function(task)
	{
        if(task == 'credit.cancel'){
            Joomla.submitform(task, document.getElementById('credit-form'));
        }
        
		if (document.formvalidator.isValid(document.id('credit-form'))) {
			Joomla.submitform(task, document.getElementById('credit-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_member&layout=edit&id='.(int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="credit-form" class="form-validate">
	<div class="row-fluid">
		<div class="span10 form-horizontal">
            <fieldset class="adminform">

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('akeebasubs_subscription_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('akeebasubs_subscription_id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('user_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('user_id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('credit_invoice_number'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('credit_invoice_number'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('publish_up'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('publish_up'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('publish_down'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('publish_down'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('amount'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('amount'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('transactions'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('transactions'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('credit_paid'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('credit_paid'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('created_on'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created_on'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('payment_data'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('payment_data'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
			</div>

				
            </fieldset>
    	</div>
        
        

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
        
    </div>
</form>