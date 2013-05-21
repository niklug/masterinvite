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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_member_banner', JPATH_ADMINISTRATOR);
?>

<!-- Styling for making front end forms look OK -->
<!-- This should probably be moved to the template CSS file -->
<style>
    .front-end-edit ul {
        padding: 0 !important;
    }
    .front-end-edit li {
        list-style: none;
        margin-bottom: 6px !important;
    }
    .front-end-edit label {
        margin-right: 10px;
        display: block;
        float: left;
        width: 200px !important;
    }
    .front-end-edit .radio label {
        float: none;
    }
    .front-end-edit .readonly {
        border: none !important;
        color: #666;
    }    
    .front-end-edit #editor-xtd-buttons {
        height: 50px;
        width: 600px;
        float: left;
    }
    .front-end-edit .toggle-editor {
        height: 50px;
        width: 120px;
        float: right;
    }

    #jform_rules-lbl{
        display:none;
    }

    #access-rules a:hover{
        background:#f5f5f5 url('../images/slider_minus.png') right  top no-repeat;
        color: #444;
    }

    fieldset.radio label{
        width: 50px !important;
    }
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function(){
        js('#form-banner').submit(function(){
            
				js('#jform_filename_hidden').val(js('#jform_filename').val()); 
        }); 
        
        
    });
</script>

<div class="banner-edit front-end-edit">
<?php if (!empty($this->item->id)): ?>
        <h1>Edit <?php echo $this->item->id; ?></h1>
    <?php else: ?>
        <h1>Add</h1>
    <?php endif; ?>

    <form id="form-banner" action="<?php echo JRoute::_('index.php?option=com_member_banner&task=banner.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
        <ul>
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
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('code'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('code'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
			</div>

			<div class="control-group">
				<?php $canState = false; ?>
					<?php $canState = $canState = JFactory::getUser()->authorise('core.edit.state','com_member_banner'); ?>				<?php if(!$canState): ?>
				<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
					<?php
						$state_string = 'Unpublish';
						$state_value = 0;
						if($this->item->state == 1):
							$state_string = 'Publish';
							$state_value = 1;
						endif;
					?>
					<div class="controls"><?php echo $state_string; ?></div>
					<input type="hidden" name="jform[state]" value="<?php echo $state_value; ?>" />
				<?php else: ?>
					<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('state'); ?></div>					<?php endif; ?>
				</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('created'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created'); ?></div>
			</div>

        </ul>

        <div>
            <button type="submit" class="validate"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
<?php echo JText::_('or'); ?>
            <a href="<?php echo JRoute::_('index.php?option=com_member_banner&task=banner.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>

            <input type="hidden" name="option" value="com_member_banner" />
            <input type="hidden" name="task" value="bannerform.save" />
<?php echo JHtml::_('form.token'); ?>
        </div>
    </form>
</div>
