<?php
/**
 * @version     1.0.0
 * @package     com_member_banner
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
$document->addStyleSheet('components/com_member_banner/assets/css/member_banner.css');
?>
<script type="text/javascript">
    
    
	Joomla.submitbutton = function(task)
	{
        
				js = jQuery.noConflict();
				js('#jform_filename_hidden').val(js('#jform_filename').val());
		if (task == 'banner.cancel' || document.formvalidator.isValid(document.id('banner-form'))) {
			Joomla.submitform(task, document.getElementById('banner-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_member_banner&layout=edit&id='.(int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="banner-form" class="form-validate">
	<div class="row-fluid">
		<div class="span10 form-horizontal">
            <fieldset class="adminform">

				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('language'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('language'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('width'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('width'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('height'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('height'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('filename'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('filename'); ?></div>
			</div>

				<input type="hidden" name="jform[filename]" id="jform_filename_hidden" value="" />
			<?php if($this->item->filename != ''):?>
				<input type="hidden" name="jform[filename_hidden]" value="<?php echo $this->item->filename; ?>" />
			<?php endif;?>				<input type="hidden" name="jform[hints]" value="<?php echo $this->item->hints; ?>" />
				<input type="hidden" name="jform[preview]" value="<?php echo $this->item->preview; ?>" />
			<div style="display:none" class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('code'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('code'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('created'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created'); ?></div>
			</div>

				
            </fieldset>
    	</div>
        
        

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
        
    </div>
</form>