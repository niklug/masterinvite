<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * Member Model
 */
class MemberModelMember extends JModelItem
{
    
    public function __construct($config = array()) {
        parent::__construct($config);
        
        // current date
        $UTC = '2';
        $offset = $UTC * 60 * 60;
        $this->offset = $offset;
        $dateFormat = "Y-m-d H:i:s";
        $current_time = gmdate($dateFormat, time() + $offset);
        $this->current_time = $current_time;
        
        
        $this->ini_config = parse_ini_file(JPATH_COMPONENT_SITE. "/configuration.ini", true);
        
    }
    
    
    /** send affiliate emails
     * 
     * @param type $email
     * @param type $subject
     * @param type $body
     */
    public function inviteEmail($email, $subject, $body){

	return $this->sendEmail($email, $subject, $body);
    }
    
    /** core functionsend email
     * 
     * @param type $recipient
     * @param type $Subject
     * @param type $body
     * @return type
     */
    private function sendEmail1($recipient, $subject, $body) {
        $mailer = & JFactory::getMailer();

        $config = new JConfig();

        $headers = 'MIME-Version: 1.0' . "\r\n";
        
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        
        $headers .='From:' . $config->fromname . "\r\n";
        
        if (!mail($recipient, $subject, $body, $headers)) {
            return "Error in sending message to $recipient";
        } else {
            return "Message succesfully sent to $recipient";
        }
    }
    

    
    private function sendEmail($recipient, $Subject, $body) {

        $mailer = & JFactory::getMailer();

        $config = new JConfig();

        $sender = array($config->mailfrom, $config->fromname);

        $mailer->setSender($sender);

        //$recipient = 'npkorban@mail.ru';

        $mailer->addRecipient($recipient);

        $mailer->setSubject($Subject);

        $mailer->isHTML(true);

        $mailer->setBody($body);

        $send = & $mailer->Send();

        if ($send) {
            return JText::_('COM_MEMBER_EMAIL_SUCCESFULLY_SENT') . " $recipient";
        } else {
            return JText::_('COM_MEMBER_EMAIL_NOT_SENT') . " $recipient";
        }
    }
    
    
    /** check if affiliate id is valid
     * 
     * @param type $aid
     * @return boolean
     */
    public function checkAffId($aid) {
        $aff_exists = false;
        $db = &JFactory::getDbo();
        $query = "SELECT username FROM #__users WHERE username='$aid'";
        try {
            $db->setQuery($query);

            $result = $db->loadResult();
            if ($result) {
                $aff_exists = true;
            }
            return $aff_exists;
        } catch (Exception $e) {
           
            return false;
        }
    
   }
    
    
    /** update user payment method and data
     * 
     * @param array $post
     * @return boolean
     */
    public function savePaymentData($post) {
        $user = &JFactory::getUser();
        $db = &JFactory::getDbo();
        $post['user_id'] = $user->id;
 
        $object = new stdClass();
        
        $allowed_fields = array(
            'user_id',
            'payment_method',
            'payment_bank_account_holder',
            'payment_bank_account_number',
            'payment_bank_iban_number',
            'payment_paypal_adress'
        );
        foreach ($post as $key => $value) {
            if(in_array($key, $allowed_fields)) {
                $object->$key = $value;
            }
            
        }
        
        if(!$user->id)  return false;
        
        try {
            if($this->getUserPaymentData($user->id)) {

                $db->updateObject('#__akeebasubs_payment_data', $object, 'user_id', false);
            } else {
                $db->insertObject('#__akeebasubs_payment_data', $object, 'id');
            }
            return true;
            
        } catch (Exception $e) {
            
            $message = "<br>" . "error in saving user payment data" . " <br>" ;
            
            $message .= "<br/><br/>" . print_r($object, true) . "<br/> <br/>";
            
            $message .= "<br/> <br/> <strong style='color:red'>" .$e->getMessage() . "</strong><br/>";

            $this->member_model->eventEmail('error_in_save_user_payment_data', $message);

            
            return $message;
        }

    }
    
    
    /**
     * 
     * @param type $user_id
     * @return type
     */
    public function getUserPaymentData($user_id) {
        $db = &JFactory::getDbo();
        $query = "SELECT *  FROM #__akeebasubs_payment_data WHERE user_id='$user_id'";
        $db->setQuery($query);
        $result = $db->loadObject();
        return $result;
    }
    
    
        
    /**on some payment sens event explanation email
     * 
     * @param type $name
     * @param type $text
     * @return type
     */
    public function eventEmail($name, $text) {
        $text .= "<br/> <br/>" . $this->current_time . "<br/> <br/>" ;
        if(!$this->ini_config['event_email'][$name]) return;
        $recipient = $this->ini_config['administrator_email'];
        file_put_contents(JPATH_COMPONENT_SITE . '/cron_errors.html', $text,  FILE_APPEND);
        $result = $this->sendEmail($recipient, 'Payment Event', $text);
        return $result;
    }

}
