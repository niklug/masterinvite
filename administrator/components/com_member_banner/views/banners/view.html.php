<?php
/**
 * @version     1.0.0
 * @package     com_member_banner
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Nikolay Korban <niklug@ukr.net> - http://
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Member_banner.
 */
class Member_bannerViewBanners extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}
        
		Member_bannerHelper::addSubmenu('banners');
        
		$this->addToolbar();
        
        $this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/member_banner.php';

		$state	= $this->get('State');
		$canDo	= Member_bannerHelper::getActions($state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_MEMBER_BANNER_TITLE_BANNERS'), 'banners.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/banner';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
			    JToolBarHelper::addNew('banner.add','JTOOLBAR_NEW');
		    }

		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    JToolBarHelper::editList('banner.edit','JTOOLBAR_EDIT');
		    }

        }

		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('banners.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('banners.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'banners.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('banners.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
            	JToolBarHelper::custom('banners.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    JToolBarHelper::deleteList('', 'banners.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    JToolBarHelper::trash('banners.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_member_banner');
		}
        
        //Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_member_banner&view=banners');
        
        $this->extra_sidebar = '';
        
		//Filter for the field language
		$select_label = JText::sprintf('COM_MEMBER_BANNER_FILTER_SELECT_LABEL', 'Language');
		$options = array();
		$options[0] = new stdClass();
		$options[0]->value = "dutch";
		$options[0]->text = JText::_('COM_MEMBER_BANNER_BANNERS_DUTCH');
		$options[1] = new stdClass();
		$options[1]->value = "english";
		$options[1]->text = JText::_('COM_MEMBER_BANNER_BANNERS_ENGLISH');
		$options[2] = new stdClass();
		$options[2]->value = "spanish";
		$options[2]->text = JText::_('COM_MEMBER_BANNER_BANNERS_SPANISH');
		JHtmlSidebar::addFilter(
			$select_label,
			'filter_language',
			JHtml::_('select.options', $options , "value", "text", $this->state->get('filter.language'), true)
		);

		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);

        
	}
    
	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.name' => JText::_('COM_MEMBER_BANNER_BANNERS_NAME'),
		'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
		'a.width' => JText::_('COM_MEMBER_BANNER_BANNERS_WIDTH'),
		'a.height' => JText::_('COM_MEMBER_BANNER_BANNERS_HEIGHT'),
		'a.filename' => JText::_('COM_MEMBER_BANNER_BANNERS_FILENAME'),
		'a.hints' => JText::_('COM_MEMBER_BANNER_BANNERS_HINTS'),
		'a.preview' => JText::_('COM_MEMBER_BANNER_BANNERS_PREVIEW'),
		'a.created_by' => JText::_('COM_MEMBER_BANNER_BANNERS_CREATED_BY'),
		'a.state' => JText::_('JSTATUS'),
		'a.created' => JText::_('COM_MEMBER_BANNER_BANNERS_CREATED'),
		);
	}
        
        
        protected  function showPreview($item_id, $item_filename, $item_width, $item_height) {
            $html = '<img onclick="previewShow(' . $item_id .')" class="preview_button"  alt="Preview" src="'. JUri::base() .'components/com_member_banner/assets/images/search16.png">';
            
            // in list small banner
            if(preg_match('/.swf$/', $item_filename)) {
                // flash banner
                $html .= '<object width="140" height="20" wmode="opaque" loop="loop"';
                $html .= 'data="' .  JUri::base() . 'components/com_member_banner/images/' .  $item_filename . '"';
                $html .= 'type="application/x-shockwave-flash"> <param name="wmode" value="opaque" /> <param name="movie"';
                $html .= 'value="' . JUri::base() . 'components/com_member_banner/images/' . $item_filename . '" />';
                $html .= '</object>';
            } else {
                // src format banner
                $html .= '<img  width="140px" height="20px"  src="'. JUri::base() . 'components/com_member_banner/images/' . $item_filename .'">';

            }
            
            // big preview
            $html .= '<div class = "iconpreview preview_' . $item_id . '">';
            
            if(preg_match('/.swf$/',$item_filename)) {
                // flash preview banner
                $html .= '<object class="preview_flash" width="' . $item_width . 'px" height="' . $item_height . 'px" wmode="opaque" loop="loop"';
                $html .= 'data="' .  JUri::base() . 'components/com_member_banner/images/' .  $item_filename . '"';
                $html .= 'type="application/x-shockwave-flash"> <param name="wmode" value="opaque" /> <param name="movie"';
                $html .= 'value="' . JUri::base() . 'components/com_member_banner/images/' . $item_filename . '" />';
                $html .= '</object>';
            } else {
                // src format preview banner
                $html .= '<img class="previewsrc" width="' . $item_width .'" height="' . $item_height . '"  src="' . JUri::base() . 'components/com_member_banner/images/' . $item_filename .'">';

            }
            
            // hide image button
            $html .= '<img class="hideimage " src="' . JUri::base() . 'components/com_member_banner/images/close.png" alt="close" title="close" onclick="hidePreview(' . $item_id . ')">';
            $html .= '</div>';
            return $html;
        }

    
}
