<?php
/**
 * @version     1.0.0
 * @package     com_member
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Nikolay Korban <niklug@ukr.net> - http://
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';


class MemberControllerCron extends MemberController
{
    public function __construct($config = array()) {
        parent::__construct($config);
        // connect billing model
        $this->billing_model = $this->getModel();
        
        // connect billing model
        $this->credit_model = $this->getModel('Credit');
        
             // connect billing model
        $this->member_model = $this->getModel('Member');
                
        // current time
        $this->current_time = $this->billing_model->current_time;
    }
    
    /**
     * Proxy for getModel.
     * @since	1.6
     */
    public function &getModel($name = 'Billing', $prefix = 'MemberModel') {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }

    
    private function getSubscribedUsers() {
        $query = "SELECT DISTINCT user_id FROM #__akeebasubs_users";
        $users = $this->billing_model->customQuery($query, 3);
        return $users;
    }
    
    
    public function run() {
        ob_start();
        
        $users = $this->getSubscribedUsers();
        
        echo "<br/> started " . $this->credit_model->current_time . "<br/>";
        //var_dump($users);
        
        foreach ($users as $user_id) {
            //echo $user_id;
            try {
                $this->billingProcessing($user_id);
            } catch (Exception $e) {
                echo "<br/> <br/> <strong style='color:red'>" .$e->getMessage() . "</strong><br/>";
                continue;
            }
        }
        
        echo "<hr>";

        $contents = ob_get_contents();
        ob_end_clean();

        // see the contents now
        echo $contents;
        
        $this->member_model->eventEmail('send_cron_respond', $contents);
        
        file_put_contents( JPATH_COMPONENT_SITE . '/cron_log.html', $contents,  FILE_APPEND);
        
        die();
    }



    public function billingProcessing($user_id) {
        //$user_id = '1017';
        /*
        // TODO only for my id
        $user = &JFactory::getUser();
        $user_id = $user->id;
        if(!$user_id) die('you are not logged');
         * 
         */
        // user current subscription data
        $subscription = $this->billing_model->getUserSubscriptionData($user_id);
        // next renew user data
        $actualSubscriptionData = $this->billing_model->actualSubscriptionData($user_id);
        // 10 days before subscription end
        $isTimeForRenew = $this->billing_model->checkIfTimeForRenew($subscription);
        // credit ballance not less than monthly membership
        $creditBallanceEnough = $this->billing_model->checkIfCreditsBallanceEnough($user_id, $actualSubscriptionData);
        // subscription expired
        $subscriptionExpired = $this->billing_model->checkIfSubscriptionIsExpired($subscription);
        
        $subscription_conditions = array(
            'isTimeForRenew' => $isTimeForRenew,
            'creditBallanceEnough' => $creditBallanceEnough,
            'subscriptionExpired' => $subscriptionExpired
        );
        /*
        require_once('FirePHPCore/FirePHP.class.php');

        $firephp = FirePHP::getInstance(true);

        $var = array('i' => 10, 'j' => 20);

        //$firephp->log($var, 'debug');
        */
        echo "</br>"; 
        echo $user_id . ' ';
        var_dump($subscription_conditions );
        echo "</br>";
     
        //var_dump($subscription);
        

        
        switch ($subscription_conditions) {
            //1
            case ($subscription_conditions['isTimeForRenew'] == false) and 
                 ($subscription_conditions['creditBallanceEnough'] == false) and 
                 ($subscription_conditions['subscriptionExpired'] == false) :
                $this->billing_model->doNothing($user_id);
                // do nothing
                break;
            //2
            case ($subscription_conditions['isTimeForRenew'] == false) and 
                 ($subscription_conditions['creditBallanceEnough'] == true) and
                 ($subscription_conditions['subscriptionExpired'] == false) :
                $this->billing_model->doNothing($user_id);
                break;
            //3
            case ($subscription_conditions['isTimeForRenew'] == true) and 
                 ($subscription_conditions['creditBallanceEnough'] == false) and
                 ($subscription_conditions['subscriptionExpired'] == false) :
                
                     
                $this->billing_model->allowPayUserManually($user_id, $actualSubscriptionData, $subscription, $this->credit_model->get_aid ($user_id));
                break;
            //4
            case ($subscription_conditions['isTimeForRenew'] == true) and 
                 ($subscription_conditions['creditBallanceEnough'] == true) and
                 ($subscription_conditions['subscriptionExpired'] == true) :
              
                $this->billing_model->activateNewSubscriptionWithCredits($user_id, $actualSubscriptionData, $subscription,  $this->credit_model);
                break;
            //5
            case ($subscription_conditions['isTimeForRenew'] == false) and 
                 ($subscription_conditions['creditBallanceEnough'] == true) and
                 ($subscription_conditions['subscriptionExpired'] == true) :
                $this->billing_model->activateNewSubscriptionWithCredits($user_id, $actualSubscriptionData, $subscription,  $this->credit_model);
                
                break;
            //6
            case ($subscription_conditions['isTimeForRenew'] == true) and 
                 ($subscription_conditions['creditBallanceEnough'] == true) and
                 ($subscription_conditions['subscriptionExpired'] == false) :
                $this->billing_model->doNothing($user_id);
                break;
           //7
            case ($subscription_conditions['isTimeForRenew'] == false) and 
                 ($subscription_conditions['creditBallanceEnough'] == false) and
                 ($subscription_conditions['subscriptionExpired'] == true) :
                $this->billing_model->stopUserMembership($user_id);
                break;
            //8
            case ($subscription_conditions['isTimeForRenew'] == true) and 
                 ($subscription_conditions['creditBallanceEnough'] == false) and
                 ($subscription_conditions['subscriptionExpired'] == true) :
                
                $this->billing_model->stopUserMembership($user_id);
                break;

            default:
                break;
        }
        
        
        
        /*
        //$credit_value = $this->credit_model->addCredit($user_id);
 
        if ($credit_value) {
            $this->billing_model->addCreditsToUserBallance($user_id, $credit_value);
        }
         * 
         */
        
     
        
        
    }
    



}




