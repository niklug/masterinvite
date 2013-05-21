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

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_member_banner', JPATH_ADMINISTRATOR);
?>
<?php if( $this->item ) : ?>

    <div class="item_fields">
        
        <ul class="fields_list">

			<li><?php echo JText::_('COM_MEMBER_BANNER_FORM_LBL_BANNER_ID'); ?>:
			<?php echo $this->item->id; ?></li>
			<li><?php echo JText::_('COM_MEMBER_BANNER_FORM_LBL_BANNER_NAME'); ?>:
			<?php echo $this->item->name; ?></li>
			<li><?php echo JText::_('COM_MEMBER_BANNER_FORM_LBL_BANNER_LANGUAGE'); ?>:
			<?php echo $this->item->language; ?></li>
			<li><?php echo JText::_('COM_MEMBER_BANNER_FORM_LBL_BANNER_WIDTH'); ?>:
			<?php echo $this->item->width; ?></li>
			<li><?php echo JText::_('COM_MEMBER_BANNER_FORM_LBL_BANNER_HEIGHT'); ?>:
			<?php echo $this->item->height; ?></li>
			<li><?php echo JText::_('COM_MEMBER_BANNER_FORM_LBL_BANNER_FILENAME'); ?>:
			<?php echo $this->item->filename; ?></li>
			<li><?php echo JText::_('COM_MEMBER_BANNER_FORM_LBL_BANNER_HINTS'); ?>:
			<?php echo $this->item->hints; ?></li>
			<li><?php echo JText::_('COM_MEMBER_BANNER_FORM_LBL_BANNER_PREVIEW'); ?>:
			<?php echo $this->item->preview; ?></li>
			<li><?php echo JText::_('COM_MEMBER_BANNER_FORM_LBL_BANNER_CODE'); ?>:
			<?php echo $this->item->code; ?></li>
			<li><?php echo JText::_('COM_MEMBER_BANNER_FORM_LBL_BANNER_CREATED_BY'); ?>:
			<?php echo $this->item->created_by; ?></li>
			<li><?php echo JText::_('COM_MEMBER_BANNER_FORM_LBL_BANNER_STATE'); ?>:
			<?php echo $this->item->state; ?></li>
			<li><?php echo JText::_('COM_MEMBER_BANNER_FORM_LBL_BANNER_CREATED'); ?>:
			<?php echo $this->item->created; ?></li>


        </ul>
        
    </div>
    <?php if(JFactory::getUser()->authorise('core.edit.own', 'com_member_banner')): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_member_banner&task=banner.edit&id='.$this->item->id); ?>">Edit</a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_member_banner')):
								?>
									<a href="javascript:document.getElementById('form-banner-delete-<?php echo $this->item->id ?>').submit()">Delete</a>
									<form id="form-banner-delete-<?php echo $this->item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_member_banner&task=banner.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
										<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
										<input type="hidden" name="jform[name]" value="<?php echo $this->item->name; ?>" />
										<input type="hidden" name="jform[language]" value="<?php echo $this->item->language; ?>" />
										<input type="hidden" name="jform[width]" value="<?php echo $this->item->width; ?>" />
										<input type="hidden" name="jform[height]" value="<?php echo $this->item->height; ?>" />
										<input type="hidden" name="jform[filename]" value="<?php echo $this->item->filename; ?>" />
										<input type="hidden" name="jform[hints]" value="<?php echo $this->item->hints; ?>" />
										<input type="hidden" name="jform[preview]" value="<?php echo $this->item->preview; ?>" />
										<input type="hidden" name="jform[code]" value="<?php echo $this->item->code; ?>" />
										<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
										<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
										<input type="hidden" name="jform[created]" value="<?php echo $this->item->created; ?>" />
										<input type="hidden" name="option" value="com_member_banner" />
										<input type="hidden" name="task" value="banner.remove" />
										<?php echo JHtml::_('form.token'); ?>
									</form>
								<?php
								endif;
							?>
<?php else: ?>
    Could not load the item
<?php endif; ?>
