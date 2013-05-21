<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Ola class for the Ola Component
 */
class MemberViewOverview extends JViewLegacy
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
            echo '<!--[if IE]><script type="text/javascript" src="/components/com_member/views/overview/tmpl/js/excanvas.js"></script><![endif]-->';
            $document -> addStyleSheet('components' . DS . 'com_member' . DS .'views'. DS .'overview'. DS . 'tmpl' . DS . 'css' . DS . 'overview.css');
            $document -> addStyleSheet('components' . DS . 'com_member' . DS .'views'. DS .'overview'. DS . 'tmpl' . DS . 'css' . DS . 'histogram.css');
            $document -> addStyleSheet('components' . DS . 'com_member' . DS .'views'. DS .'overview'. DS . 'tmpl' . DS . 'css' . DS . 'visualize.jQuery.css');

            $document -> addscript('components' . DS . 'com_member' . DS .'views'. DS .'overview'. DS . 'tmpl' . DS . 'js' . DS . 'excanvas.js');
            $document -> addscript('components' . DS . 'com_member' . DS .'views'. DS .'overview'. DS . 'tmpl' . DS . 'js' . DS . 'visualize.jQuery.js');
            $document -> addscript('components' . DS . 'com_member' . DS .'views'. DS .'overview'. DS . 'tmpl' . DS . 'js' . DS . 'enhance.js');
            $document -> addCustomTag('
		<script type="text/javascript">
                jQuery(function(){
                jQuery("#histogram").visualize();
                });
		</script>'
	    );
            
            $model = $this->getModel();
            $billing_model =  $this->getModel('billing');
            $user = &JFactory::getUser();
            $user_id = $user->id;
            
            $this->assign('user_id', $user_id);
            $this->assign('username', $user->username);
            $this->assign('model', $model);
            $this->assign('billing_model', $billing_model);
            

	}
       

}
