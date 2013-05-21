<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * Billing Model
 */
class MemberModelBilling extends JModelItem
{
    public $renewBeforeDays = 10;
    
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
    
    /** user country NL or others
     * 
     * @param type $user_id
     * @return string
     */
    public function getUserCountry($user_id) {
       $country = NULL;
       $query = "SELECT country FROM #__akeebasubs_users WHERE user_id ='$user_id'";
       $result = $this->customQuery($query, 0);
       if($result == 'NL') $country = $result;
       return $country;
    }
    
    
    /** actual (default) subscription data that will using for  renews
     * 
     * @param type $user_id
     * @return object
     */
    public  function actualSubscriptionData($user_id) {
        $country = $this->getUserCountry($user_id);
        $query = "SELECT 
            akeebasubs_level_id,
            title,
            price,
            duration,
            (SELECT country FROM #__akeebasubs_users WHERE user_id='$user_id') user_country,
            (SELECT taxrate FROM #__akeebasubs_taxrules WHERE country='$country' AND enabled=1) tax_value
            FROM #__akeebasubs_levels WHERE enabled=1";
       $subscription = $this->customQuery($query, 2);
       return $subscription;
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
    
   /** user subscriptions
    * 
    * @param type $user_id
    * @return object list
    */ 
   public function getUserSubscriptionData($user_id) {
       $query = "SELECT 
            akeebasubs_subscription_id,
            user_id,
            akeebasubs_level_id,
            publish_up,
            publish_down,
            enabled,
            state,
            net_amount,
            tax_amount,
            gross_amount,
            (SELECT max(publish_down) FROM #__akeebasubs_subscriptions WHERE state='C' AND user_id='$user_id') expire_date,
            (SELECT credits_ballance FROM #__akeebasubs_payment_data WHERE user_id = #__akeebasubs_subscriptions.user_id) credits_ballance
            FROM #__akeebasubs_subscriptions WHERE user_id='$user_id' AND enabled=1";
       $subscription = $this->customQuery($query, 2);
       return $subscription;
   }
   
   
       
    /**
     * 
     * @param type $expire_date
     * @return boolean
     */
    public function checkIfActiveUserSubscription($expire_date) {
        if ($this->current_time < $expire_date) return true;
    }
    
    /** days difference
     * 
     * @param type timestamp
     * @param type timestamp
     * @return float
     */
    public function dateDaysDifference($date1, $date2) {
        $date1 = &JFactory::getDate($date1);
        $date2 = &JFactory::getDate($date2);
        
        $dateUnix1 = $date1->toUnix();
        $dateUnix2 = $date2->toUnix();
        
        $daysDiff = abs($dateUnix2 - $dateUnix1) / (60*60*24);
        return $daysDiff;
    }
    
    /** return user ballance
     * 
     * @param type $user_id
     * @return decimal
     */
    public function getUserCreditsBallance($user_id) {
       $query = "SELECT credits_ballance FROM #__akeebasubs_payment_data WHERE user_id ='$user_id'";
       $ballance = $this->customQuery($query, 0);
       return $ballance;
   }
 
    /**
     * 
     * @param type oblect
     * @return boolean
     */
    public function checkIfCreditsBallanceEnough($user_id, $actualSubscriptionData) {

        $userBallance = $this->getUserCreditsBallance($user_id);
        $enough = false;
        if(round($userBallance, 1) >= 
                round(($actualSubscriptionData->price + ($actualSubscriptionData->price * $actualSubscriptionData->tax_value / 100)), 1)
        ) {
            $enough =  true;
        }
        return $enough;
        
    }
    
    
    /** check if now is the time to renew  for user (10 days before expire)
     * 
     * @param type int
     * @return boolean
     */
    public function checkIfTimeForRenew($subscription) {
        $renew = false;
        // if less that 10 days before subscription end 
        $date_difference = $this->dateDaysDifference($subscription->expire_date, $this->current_time );
        
        if($date_difference < $this->renewBeforeDays) {
            $renew = true;
        }
        return $renew; 
    }
    
    /**
     * 
     * @param type object
     * @return boolean
     */
    public function checkIfSubscriptionIsExpired($subscription) {
        $expired = false;
        if($subscription == NULL) {
            $expired = true;
        }
        return $expired;
    }
    
    
    /** find if user was subscribed before or is new user
     * 
     * @param type $user_id
     * @return boolean
     */
    public function checkIfUserWasSubscribedBefore($user_id) {
        $subscribed = false;
        $query = "SELECT user_id FROM #__akeebasubs_subscriptions WHERE user_id='$user_id'";
        $result = $this->customQuery($query, 0);
        if($result) {
            $subscribed = true;
        }
        return $subscribed;
    }
    
    
    /** activate subscription with credits
     * 
     * @param type $user_id
     * @param type $actualSubscriptionData
     */
    public function activateNewSubscriptionWithCredits($user_id, $actualSubscriptionData, $subscription, $credit_model) {
        $aid = $credit_model->get_aid ($user_id);
        $activationData =  $this->createNewSubscription($user_id, $actualSubscriptionData, $subscription, 'C', 'credits', 1, $aid);
        
          if($activationData['id']) {
            $activationData['data']->akeebasubs_subscription_id = $activationData['id'];	
            $this->burnUserCredits($activationData);
        }
        

    }

    /** if no credits, generate not paid invoice before 10 days subscription expire
     * 
     * @param type $user_id
     * @param type $actualSubscriptionData
     */
    public function allowPayUserManually($user_id, $actualSubscriptionData, $subscription, $aid) {
        $nextSubscriptionExists = $this->checkIfExistsNextSubscription($user_id);
        if(!$nextSubscriptionExists) {
            $activationData = $this->createNewSubscription($user_id, $actualSubscriptionData, $subscription, 'N', '', 0, $aid);
            $activationData['data']->akeebasubs_subscription_id = $activationData['id'];
            $this->createInvoice($activationData['data'], 'invoice');
            echo "<br/>" . $user_id . ' pay yourself until you dont have credits';
        } else {
             echo "<br/>" . $user_id . ' system waiting for manually payment or auto payment with credits when it will be available';
        }
    }
    

    
    /** default function
     * 
     * @param type $subscription
     */
    public function doNothing($user_id) {
        echo "<br/>" . $user_id . ' not a time do something now'. "<br/>";
         
        
    }
    
    
    
    /** create not payed account for pay user manually
      * 
      * @param int $user_id
      * @param object $actualSubscriptionData
      * @return array
      */
    public function createNewSubscription($user_id, $actualSubscriptionData, $subscription, $state, $processor, $enabled, $aid ) {
        $subscriptionId = $this->checkIfExistsNextSubscription($user_id);
        $db= &JFactory::getDbo();
        $data = new stdClass();
        $data->user_id 	= $user_id;
        $data->akeebasubs_level_id =  $actualSubscriptionData->akeebasubs_level_id;

        $data->publish_up = $this->calculateNextPublishUpDate($subscription);

        $data->publish_down = $this->calculateNextPublishDownDate($subscription, $actualSubscriptionData);
        $data->enabled = $enabled;
        $data->processor = $processor;
        $data->processor_key = rand(1000, 10000);
        $data->discount_amount 	 = 0;
        $data->prediscount_amount = 0;
        $data->state = $state;
        $data->net_amount = $actualSubscriptionData->price;
        $data->tax_amount = round($actualSubscriptionData->price * ($actualSubscriptionData->tax_value / 100), 2);
        $data->gross_amount = $this->getGrossSubscriptionPrice($actualSubscriptionData);
        $data->tax_percent = $actualSubscriptionData->tax_value;
        $data->created_on = $this->current_time;
        $data->akeebasubs_affiliate_id = $aid;

        
        if($subscriptionId) {
            $id = $nextSubscriptionId;
            $this->deleteNotPaidSubscription($subscriptionId);
                 
        } 
        
       
        try {
            $db->insertObject('#__akeebasubs_subscriptions', $data);

            $result = array(
                'id' => $db->insertid(),
                'data' => $data
            );
            
            $message = "<br/> " . 'new subscription was creared for ' . $user_id  . ' for period ' . 
                    $data->publish_up . ' - ' . $data->publish_down . ' , id = ' . $db->insertid();

            $this->member_model->eventEmail('subscription_created_with_credits', $message);

            echo $message;
            
            return $result;
            
        } catch (Exception $e) {
            
            $message = 'New subscripton  was not created for ' . $user_id . ' for period ' . 
                    $data->publish_up . ' - ' . $data->publish_down;

            $message .= "<br/><br/>" . print_r($data, true) . "<br/> <br/>";
            
            $message .= "<br/> <br/> <strong style='color:red'>" .$e->getMessage() . "</strong><br/>";
            
                    
            $this->member_model->eventEmail('subscription_created_with_credits', $message);

            echo $user_id . ' ' . $message;
            
            return false;
        }   
        
    }
    
    public function getGrossSubscriptionPrice($actualSubscriptionData) {
            $gross_amount = round(($actualSubscriptionData->price + ($actualSubscriptionData->price * $actualSubscriptionData->tax_value / 100)), 1);
            return $gross_amount;
    }
    
    /** after pay with credits remove old not actual subscription info
     * 
     * @param type $id
     */
    public function deleteNotPaidSubscription($id) {
        $db = &JFactory::getDbo();
        $query = "DELETE FROM #__akeebasubs_subscriptions WHERE akeebasubs_subscription_id='$id'";
        $db->setQuery($query);
        $db->query();
    }
    
    
    /**
     * 
     * @param type $subscription
     * @return type
     */
    public function calculateNextPublishUpDate($subscription) {
        $publish_up = $this->current_time;
        if($subscription) {
        $publish_up = &JFactory::getDate($subscription->publish_down);
        $publish_upUnix = $publish_up->toUnix() + 1; // + 1 second
        $publish_up = gmdate('Y-m-d H:i:s', $publish_upUnix);
        } 
        return $publish_up;
    }
    
     /**
     * 
     * @param type $subscription
     * @return type
     */
    public function calculateNextPublishDownDate($subscription, $actualSubscriptionData) {
        $publish_up = &JFactory::getDate($subscription->publish_down);
        $publish_upUnix = $publish_up->toUnix() + 1; // + 1 second
        $publish_downUnix = $publish_upUnix  + 60*60*24*$actualSubscriptionData->duration; 
        $publish_down = gmdate('Y-m-d H:i:s', $publish_downUnix);
        return $publish_down;
    }
    
    
       
    /** burn (update) user credits
     * 
     * @param array $activationData
     */
    public function burnUserCredits($activationData) {

        $user_id = $activationData['data']->user_id;
        
        $userCreditsBallance = $this->getUserCreditsBallance($user_id);
     

        $spendedCredits = $activationData['data']->gross_amount;
        
        $newUserCreditsBallance = $userCreditsBallance - $spendedCredits;
        
        $updateBallance = $this->updateUserCreditsBallance($user_id, $newUserCreditsBallance);
        
        if($updateBallance) {
            
            $this->createInvoice($activationData['data'], 'invoice');

            $message = "</br>" . 'Ballance decreased from  ' . $userCreditsBallance . ' to ' . $newUserCreditsBallance . ' for ' . $user_id . "<br/>";
        
            $this->member_model->eventEmail('ballance_decreased_for_user', $message);

            echo $message;
            
            return true;
        } 
        
    }
    
    
    /** update user credits ballance function
     * 
     * @param type $user_id
     * @param type $newUserCreditsBallance
     * @return boolean
     */
    public function updateUserCreditsBallance($user_id, $newUserCreditsBallance) {
        $db = &JFactory::getDbo();
        $query = "UPDATE #__akeebasubs_payment_data SET credits_ballance='$newUserCreditsBallance' WHERE user_id='$user_id'";
        $db->setQuery($query);
   
        try {
            $db->query();
            return true;
            
        } catch (Exception $e) {
            
            $message = "<br/> " . 'credits ballance was NOT updated for ' . $user_id  . ' to ' .  $newUserCreditsBallance;
            
            $message .= "<br/> <br/> <strong style='color:red'>" .$e->getMessage() . "</strong><br/>";
            
                    
            $this->member_model->eventEmail('user_credits_ballance_updated', $message);

            echo $message;
            
            return false;
        }   
        
        
    }
    
    
    /** add to user total ballance after added credit 
     * 
     * @param type $user_id
     * @param type $credit_value
     * @return boolean
     */
    public function addCreditsToUserBallance($user_id, $credit_value) {
        
        $userCreditsBallance = $this->getUserCreditsBallance($user_id);
     
        $newUserCreditsBallance = $credit_value + $userCreditsBallance;
        
        $updateBallance = $this->updateUserCreditsBallance($user_id, $newUserCreditsBallance);
        //debug_print_backtrace();
        if ($updateBallance) {

            $message = "</br>" . 'Total ballance increased from  ' . $userCreditsBallance . ' to ' . $newUserCreditsBallance . ' for ' . $user_id . "<br/>";
        
            $this->member_model->eventEmail('ballance_increased_for_user', $message);

            echo $user_id . ' ' . $message;

            return true;
        }
    }
    
    /** creating, saving and send with email the invoice
     * 
     * @param type object
     */
    public function createInvoice($row, $type) {

        // Load the "akeebasubs" plugins
        JLoader::import('joomla.plugin.helper');
        JPluginHelper::importPlugin('akeebasubs');
        $app = JFactory::getApplication();
        
        $info = array(
            'modified' => true,
            'type'  => $type
        );
        
        try {
            $respond = $app->triggerEvent('onAKSubscriptionChange', array($row, $info));
            
            return true;
            
        } catch (Exception $e) {
            $message = "<br/><br/>" . 'The  ' . $type . ' was not created ' . "<br/><br/>";

            $message .= "<br/><br/>" . print_r($row, true) . "<br/> <br/>";
            
            $message .= "<br/> <br/> <strong style='color:red'>" .$e->getMessage() . "</strong><br/>";
                    
            $this->member_model->eventEmail('invoice_creditnota_not_created', $message);

            return false;
        }   

        
        
    }
    
    
    /** check if should system create a row in akeebasubs_subscriptions for next period
     * 
     * @param type $user_id
     * @param type $subscription
     * @return boolean
     */
    public function checkIfExistsNextSubscription($user_id) {
        
        $exists = false;
        $query = "SELECT akeebasubs_subscription_id  FROM #__akeebasubs_subscriptions WHERE user_id='$user_id' AND state='N'";
        $result = $this->customQuery($query, 0);
        if($result) $exists = $result;
        return $exists;
    }
    
        /** default function
     * 
     * @param type $subscription
     */
    public function stopUserMembership($user_id) {
        
        $this->setToZeroUserCreditBallance($user_id);
    }
    
    
    /**
     * 
     * @param type $user_id
     */
    public function setToZeroUserCreditBallance($user_id) {
  
        $current_ballance = $this->getUserCreditsBallance($user_id);
        if($current_ballance == 0) {
            echo "<br/>" .  $user_id . ' credits ballance = 0' . "<br/>" ;
            return;
        }
            
        $updateUserCreditsBallance = $this->updateUserCreditsBallance($user_id, '0.00');
        if($updateUserCreditsBallance) {
            
            $message = "<br/>user ". $user_id . " credits ballance reset to zero <br>";
        
            $this->member_model->eventEmail('user_ballance_set_to_zero', $message);

            echo $user_id . ' ' . $message;
           
        }
    }
    
    


}



