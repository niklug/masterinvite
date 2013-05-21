<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * Credit Model
 */
class MemberModelCredit extends JModelItem
{
    private $credit_value = 50;
    private $credit_bonus_value = 100;
    private $credit_bonus_number = 5;

    
    public function __construct() {
        parent::__construct();
        // current date
        $UTC = '2';
        $offset = $UTC * 60 * 60;
        $this->offset = $offset;
        $dateFormat = "Y-m-d H:i:s";
        $current_time = gmdate($dateFormat, time() + $offset);
        $this->current_time = $current_time;
        
        // connect member model
        require_once  'member.php';
        $this->member_model  = new MemberModelMember();
        
    }
    
    

     /** select database query
     *
     * @param type $query
     * @param type $type
     * @return database data 
     */
    public function customQuery($query, $type) {
	$db = & JFactory::getDBO();
        try {
            $db->setQuery($query);

            switch ($type) {
                case 0:
                    $result = $db->loadResult();
                    break;
                case 1:
                    $result = $db->loadObjectList();
                    break;
                case 2:
                    $result = $db->loadObject();
                    break;
                case 3:
                    $result = $db->loadResultArray();
                    break;
                case 4:
                    $result = $db->loadRow();
                    break;
                case 5:
                    $result = $db->query();
                    break;
                case 6:
                    $result = $db->loadAssocList();
                    break;
                default:
                    return false;
                    break;
            }

            return $result;
            
        } catch (Exception $e) {
            
            $message = "<br>" . "error at sellect from the table" . " <br>" ;
            
             $message .= "<br/> <br/> <strong style='color:red'>" .$e->getMessage() . "</strong><br/>";

            $this->member_model->eventEmail('error_at_sellect_from_table', $message);

            echo $message;
            
            return false;
        }
    }
    
    
    /** find aid - user_id of the parrent user
     * 
     * @param type $user_id
     * @return string
     */
    public function get_aid ($user_id) {
        $query = "SELECT akeebasubs_affiliate_id  FROM #__akeebasubs_subscriptions WHERE user_id='$user_id'";
        $result  = $this->customQuery($query, 0);
    
        return $result;
    }
    
    /**
     * 
     * @return date
     */
    public function startSubscriptionDate($user_id) {
        //return date('Y-m-01 00:00:00');
        $query = "SELECT publish_up FROM #__akeebasubs_subscriptions WHERE user_id='$user_id' AND enabled='1'" ;
        $result  = $this->customQuery($query, 0);
        return $result;
    }
    
    /**
     * 
     * @return date
     */
    public function endSubscriptionDate($user_id) {
        //return date('Y-m-t 23:59:59');
        $query = "SELECT publish_down FROM #__akeebasubs_subscriptions WHERE user_id='$user_id' AND enabled='1'" ;
        $result  = $this->customQuery($query, 0);
        return $result;
    }

    
    /**
     * 
     * @param int $user_id
     * @return int
     */
    public function addCredit($user_id) {
        
        $db= &JFactory::getDbo();
        $data = new stdClass();
        $data->user_id 	= $this->get_aid($user_id);
        $data->affiliate = $user_id;
        $data->value = $this->creditValue($user_id) ;
        $data->created_on = $this->current_time;
        
        try {
            $db->insertObject('#__akeebasubs_credits', $data);
            
            $message = "<br>" . "credit added to " . $data->user_id . " from payment of " . $user_id .  " with ammount " . $data->value . " <br>" ;

            $this->member_model->eventEmail('credit_added_to_user', $message);

            echo $user_id . ' ' . $message;

            return $data->value;
        }

        catch (Exception $e) {
                        
            $message = "<br/><br/>" . 'Credit in amount ' .$data->value . ' was not added to user ' . 
                    $data->user_id . ' from payment of affiliate ' . $data->affiliate;
           
            $message .= "<br/><br/>" . print_r($data, true) . "<br/> <br/>";
            
            $message .= "<br/> <br/> <strong style='color:red'>" .$e->getMessage() . "</strong><br/>";
            
                    
            $this->member_model->eventEmail('credit_added_to_user', $message);

            echo $user_id . ' ' . $message;
            
            return false;
        }
             
      
    }
    
    
    /**
     * 
     * @param type $user_id
     * @return type
     */    
    public function creditValue($user_id) {
        $credit_value = $this->credit_value;
        
        if($this->checkFifthCredit($user_id)) {
            $credit_value = $this->credit_bonus_value;
        }

        return $credit_value;
    }
    
    
    /**
     * 
     * @param type $user_id
     * @return boolean
     */
    public function  checkFifthCredit($user_id) {
        $aid = $this->get_aid($user_id);
        $db = & JFactory::getDBO();
        $query = "SELECT count(id) FROM #__akeebasubs_credits 
            WHERE user_id='$aid'
            AND created_on  
            BETWEEN" . $db->quote($this->startSubscriptionDate($user_id)) . "AND" . $db->quote($this->endSubscriptionDate($user_id)) . "
        ";
        
        $credits = $this->customQuery($query, 0) + 1;
        $result = false;
        $rest = $credits % $this->credit_bonus_number;
        
        if(($rest == 0) AND ($credits > 1)) $result = true;
        
        return $result;
    }
    
    /** get list of all user past subscriptions
     * 
     * @param type $user_id
     * @return type
     */
    public function creditsItems($user_id) {
       $limit = JRequest::getVar('rows', 10);

       $query = "SELECT 
           akeebasubs_subscription_id,
           user_id,
           publish_up,
           publish_down,
           credit_invoice_number,
           credit_paid,
           (SELECT filename FROM #__akeebasubs_creditnotas WHERE akeebasubs_subscription_id = #__akeebasubs_subscriptions.akeebasubs_subscription_id) file,
           (SELECT SUM(value) FROM #__akeebasubs_credits WHERE user_id='$user_id' AND created_on 
               BETWEEN #__akeebasubs_subscriptions.publish_up  AND #__akeebasubs_subscriptions.publish_down) ammount,
           (SELECT count(id) FROM #__akeebasubs_credits WHERE user_id='$user_id' AND created_on 
               BETWEEN #__akeebasubs_subscriptions.publish_up  AND #__akeebasubs_subscriptions.publish_down) transactions
           FROM #__akeebasubs_subscriptions 
           WHERE user_id='$user_id' AND enabled=0 AND state='C'
       LIMIT 0, $limit";
       $items = $this->customQuery($query, 1);
       return $items;
    }
    
    
    
      /**
     * 
     * @param type $user_id
     * @return boolean
     */
    public function  getUserCreditsDataPerPeriod($user_id, $start, $finish) {
   
        $db = & JFactory::getDBO();
        $query = "SELECT SUM(value) as credit_value, count(id) as transactions FROM #__akeebasubs_credits 
            WHERE user_id='$user_id'
            AND created_on  
            BETWEEN" . $db->quote($start) . "AND" . $db->quote($finish) . "
        ";
        
        $result = $this->customQuery($query, 2);
        return $result;
    }
    
    
 
    
}