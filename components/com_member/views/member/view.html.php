<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Ola class for the Ola Component
 */
class MemberViewMember extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null) 
	{

                $this -> prepareView();
  		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Display the view
		parent::display($tpl);
	}
       
        
        
       function prepareView() {
	    $document = &JFactory::getDocument();
            // jguery 1.9.1 and jquery-ui 1.8 added with jqueryeasy plugin,
	    $document -> addscript('components' . DS . 'com_member' . DS .'views'. DS .'member'. DS . 'tmpl' . DS . 'js' . DS . 'member.js');
            
            $document -> addStyleSheet('components' . DS . 'com_member' . DS .'views'. DS .'member'. DS . 'tmpl' . DS . 'css' . DS . 'member.css');
            
            $model = $this->getModel();
            
            $user = &JFactory::getUser();
            
            $payment_data = $model->getUserPaymentData($user->id);
                        
                       
            // connect billing model
            require_once JPATH_COMPONENT_SITE .  '/models/billing.php';
            $billing_model  = new MemberModelBilling();
            
            $is_holland = $billing_model->getUserCountry($user->id);
            
            $this->assign('payment_data', $payment_data);
            $this->assign('is_holland', $is_holland);
            
            
	}
}
