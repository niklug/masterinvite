<?php

defined('_JEXEC') or die('Restricted access');
//JRequest::checkToken('') or die('Invalid Token');
// import Joomla view library
jimport('joomla.application.component.view');

//=======================================================
// AJAX View
//======================================================
class MemberViewCredits extends JViewLegacy {
    
	/** 
	 * 
	 */
	function payCredit() {
            
            if (!JSession::checkToken('get')) {
                $result = array(
                    'message' => 'invalid token',
                    'status' => false
                );
                echo json_encode($result);
                die();
            }

            $user_id = JRequest::getVar('user_id');
            $transactions = JRequest::getVar('transactions');
            $amount = JRequest::getVar('amount');            
            $subscription_id = JRequest::getVar('subscription_id');
	    
            $model = $this -> getModel('credits');
	    echo $model->payCredit($user_id, $amount, $transactions, $subscription_id);
	}
        
       

}
