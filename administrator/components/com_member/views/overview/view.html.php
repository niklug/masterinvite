<?php

/**
 * @version     1.0.0
 * @package     com_member
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Nikolay Korban <niklug@ukr.net> - http://
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Member.
 */
class MemberViewOverview extends JViewLegacy {
    
    protected $pagination;

    /**
     * Display the view
     */
    public function display($tpl = null) {
      
        
        $this->prepareView();

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }


        $this->addToolbar();

        parent::display($tpl);
    }
    
    
    
        /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
      

        $state = $this->get('State');
 
        JToolBarHelper::title(JText::_('Overview'));

        JHtmlSidebar::setAction('index.php?option=com_member&view=overview');


        
        //Filter for the field credit_paid
        $select_label = '-Select year-';
        $options = array();
        $options[0] = new stdClass();
        $options[0]->value = "2012";
        $options[0]->text = "2012";
        $options[1] = new stdClass();
        $options[1]->value = "2013";
        $options[1]->text = "2013";
        JHtmlSidebar::addFilter(
                $select_label, 'filter_year', JHtml::_('select.options', $options, "value", "text", '', true)
        );

    }



                
    public function prepareView() {

        $model = $this->getModel();
        
        $items = $model->getStatistic();
        
          
        $this->assign('items', $items);
    }
}
