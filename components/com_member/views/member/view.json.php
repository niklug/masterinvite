<?php

defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');

//=======================================================
// AJAX View
//======================================================
class MemberViewMember extends JViewLegacy {
    
	/** send email on Email Tab
	 * 
	 */
	function inviteEmail() {
            JSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));
	    $email = JRequest::getVar('email');
	    $subject = JRequest::getVar('subject');
	    $body = JRequest::getVar('body');
            $model = $this -> getModel("member");
	    echo $model->inviteEmail($email, $subject, $body);
	}
        
        
        /** check affiliare id in users
         * 
         */
        function checkAffId() {
            JSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));
            $aid = JRequest::getVar('aid');
            $model = $this -> getModel("member");
	    echo $model->checkAffId($aid);
            
        }
        
        
        /** save user payment data (Payment data Tab)
         * 
         */
        function savePaymentData() {
            JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
            $post = JRequest::get('post');
            if($post) {
                $model = $this -> getModel("member");
                echo $model->savePaymentData($post);
            }
            
        }


}
