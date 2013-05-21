<?php
/**
 * @module		com_ola
 * @author-name Christophe Demko
 * @adapted by  Ribamar FS
 * @copyright	Copyright (C) 2012 Christophe Demko
 * @license		GNU/GPL, see http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * Ola Component Controller
 */
class MemberController extends JControllerLegacy
{
    
    	function display() {
                
		$document	= JFactory::getDocument();
		$vName	 = JRequest::getCmd('view');
		$vFormat = $document->getType();
		$lName	 = JRequest::getCmd('layout', 'default');
		
                
		if ($vName == 'overview') {	
   			$model = $this->getModel('overview');
			$billing_model = $this->getModel('billing');
			$view = $this->getView($vName, $vFormat);
			$view->setModel($model, true);
			$view->setModel($billing_model, true);
                        
		}


		parent::display();
	}
        
        
        //------------------------------------------------------
	function inviteEmail() {
		$view = &$this -> getView('member', 'json');
		$view->setModel($this->getModel('member'));
		$view -> inviteEmail();
	}
        
        //------------------------------------------------------
	function checkAffId() {
		$view = &$this -> getView('member', 'json');
		$view->setModel($this->getModel('member'));
		$view -> checkAffId();
	}
        
                //------------------------------------------------------
	function savePaymentData() {
		$view = &$this -> getView('member', 'json');
		$view->setModel($this->getModel('member'));
		$view -> savePaymentData();
	}
        
        
        
}
