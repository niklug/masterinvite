<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Ola class for the Ola Component
 */
class MemberViewCredit extends JViewLegacy
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
            
            $model = $this->getModel();
            
            $user = &JFactory::getUser();
            $user_id = $user->id;
  
            $items = $model->creditsItems($user_id);
            
            $this->assign('items', $items);

	}
       

}
