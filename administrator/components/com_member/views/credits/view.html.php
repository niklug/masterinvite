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
class MemberViewCredits extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        
        $this -> prepareView();

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        

        MemberHelper::addSubmenu('overview');

        $this->addToolbar();

        $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        require_once JPATH_COMPONENT . '/helpers/member.php';

        $state = $this->get('State');
        $canDo = MemberHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_MEMBER_TITLE_CREDITS'), 'credits.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/credit';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('credit.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('credit.edit', 'JTOOLBAR_EDIT');
            }
        }




        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_member');
        }
        

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_member&view=credits');

        $this->extra_sidebar = '';

        //Filter for the field credit_paid
        $select_label = JText::sprintf('COM_MEMBER_FILTER_SELECT_LABEL', 'Paid');
        $options = array();
        $options[0] = new stdClass();
        $options[0]->value = "1";
        $options[0]->text = "Paid";
        $options[1] = new stdClass();
        $options[1]->value = "0";
        $options[1]->text = "Not Paid";
        JHtmlSidebar::addFilter(
                $select_label, 'filter_credit_paid', JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.credit_paid'), true)
        );

        //Filter for the field payment_data
        $select_label = JText::sprintf('COM_MEMBER_FILTER_SELECT_LABEL', 'Payment Data');
        $options = array();
        $options[0] = new stdClass();
        $options[0]->value = "2";
        $options[0]->text = "Paypal";
        $options[1] = new stdClass();
        $options[1]->value = "3";
        $options[1]->text = "Bank transfer";
        $options[2] = new stdClass();
        $options[2]->value = "1";
        $options[2]->text = "Saved";
        JHtmlSidebar::addFilter(
                $select_label, 'filter_payment_data', JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.payment_data'), true)
        );
    }

    protected function getSortFields() {
        return array(
            'a.akeebasubs_subscription_id' => JText::_('COM_MEMBER_CREDITS_AKEEBASUBS_SUBSCRIPTION_ID'),
            'a.user_id' => JText::_('COM_MEMBER_CREDITS_USER_ID'),
            'a.credit_invoice_number' => JText::_('COM_MEMBER_CREDITS_CREDIT_INVOICE_NUMBER'),
            'a.publish_up' => JText::_('COM_MEMBER_CREDITS_PUBLISH_UP'),
            'a.publish_down' => JText::_('COM_MEMBER_CREDITS_PUBLISH_DOWN'),
            'a.amount' => JText::_('COM_MEMBER_CREDITS_AMOUNT'),
            'a.transactions' => JText::_('COM_MEMBER_CREDITS_TRANSACTIONS'),
            'a.credit_paid' => JText::_('COM_MEMBER_CREDITS_CREDIT_PAID'),
            'a.created_on' => JText::_('COM_MEMBER_CREDITS_CREATED_ON'),
            'a.payment_data' => JText::_('COM_MEMBER_CREDITS_PAYMENT_DATA'),
        );
    }
    
    
            
    public function prepareView() {
        
        $document = &JFactory::getDocument();

        
        echo '<script src="' . JUri::base() . 'components/com_member/views/credits/tmpl/js/member.js" type="text/javascript"></script>';
         
        $model = $this->getModel();
          
        $this->assign('model', $model);
        
        

    }

}
