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
    protected $params;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
        $app                = JFactory::getApplication();
        
        $this->state		= $this->get('State');
        $this->items		= $this->get('Items');
        $this->pagination	= $this->get('Pagination');
        $this->params       = $app->getParams('com_member_banner');

        
        
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {;
            throw new Exception(implode("\n", $errors));
        }
        
        $this->_prepareDocument();
        parent::display($tpl);
	}


	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$title	= null;
                
                $document = &JFactory::getDocument();
                $document -> addStyleSheet('components' . DS . 'com_member_banner' . DS .'assets'. DS . 'member_banner.css');

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('com_member_banner_DEFAULT_PAGE_TITLE'));
		}
		$title = $this->params->get('page_title', '');
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}    
        
      protected function getSortFields()
	{
		return array(
		'dutch' => JText::_('COM_MEMBER_BANNER_BANNERS_DUTCH'),
                'english' => JText::_('COM_MEMBER_BANNER_BANNERS_ENGLISH'),
                'spanish' => JText::_('COM_MEMBER_BANNER_BANNERS_SPANISH')

		);
	}
        
                
      protected function showPreview($item_id, $item_filename, $item_width, $item_height) {
          
        $html = '<img onclick="previewShow(' . $item_id . ')" class="preview_button"  alt="Preview" src="' . JUri::base() . 'administrator/components/com_member_banner/assets/images/search16.png">';

        // in list small banner
        if (preg_match('/.swf$/', $item_filename)) {
            // flash banner
            $html .= '<object width="140" height="20" wmode="opaque" loop="loop"';
            $html .= 'data="' . JUri::base() . 'administrator/components/com_member_banner/images/' . $item_filename . '"';
            $html .= 'type="application/x-shockwave-flash"> ';
            $html .= '</object>';
        } else {
            // src format banner
            $html .= '<img  width="140px" height="20px"  src="' . JUri::base() . 'administrator/components/com_member_banner/images/' . $item_filename . '">';
        }

        // big preview
        $html .= '<div class = "iconpreview preview_' . $item_id . '">';

        if (preg_match('/.swf$/', $item_filename)) {
            // flash preview banner
            $html .= '<object class="preview_flash" width="' . $item_width . 'px" height="' . $item_height . 'px" wmode="opaque" loop="loop"';
            $html .= 'data="' . JUri::base() . 'administrator/components/com_member_banner/images/' . $item_filename . '"';
            $html .= 'type="application/x-shockwave-flash"> ';
            $html .= '</object>';
        } else {
            // src format preview banner
            $html .= '<img class="previewsrc" width="' . $item_width . '" height="' . $item_height . '"  src="' . JUri::base() . 'administrator/components/com_member_banner/images/' . $item_filename . '">';
        }

        // hide image button
        $html .= '<img class="hideimage " src="' . JUri::base() . 'administrator/components/com_member_banner/images/close.png" alt="close" title="close" onclick="hidePreview(' . $item_id . ')">';
        $html .= '</div>';
        return $html;
    }
    
    
    protected function showCode($item_id, $item_filename, $item_width, $item_height) {
        $user = &JFactory::getUser();
        $html = '<div class = "bannercode code_' . $item_id . '">';
        $banner_html = '<a href="' . JUri::base() . '?aid=' . $user->username . '">';
        if (preg_match('/.swf$/', $item_filename)) {
            // flash preview banner
            $banner_html .= '<object  width="' . $item_width . 'px" height="' . $item_height . 'px" wmode="opaque" loop="loop"';
            $banner_html .= 'data="' . JUri::base() . 'administrator/components/com_member_banner/images/' . $item_filename . '"';
            $banner_html .= 'type="application/x-shockwave-flash"> ';
            $banner_html .= '</object>';
        } else {
            // src format preview banner
            $banner_html .= '<img  width="' . $item_width . '" height="' . $item_height . '"  src="' . JUri::base() . 'administrator/components/com_member_banner/images/' . $item_filename . '">';
        }
        $banner_html .= '</a>';
        $html .= htmlspecialchars($banner_html);
        $html .= '<img class="hideimage " src="' . JUri::base() . 'administrator/components/com_member_banner/images/close.png" alt="close" title="close" onclick="hideCode(' . $item_id . ')">';
        $html .= '</div>';

        return $html;
    }
    	
}
