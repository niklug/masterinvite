<?php
/**
 * @version     1.0.0
 * @package     com_member
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Nikolay Korban <niklug@ukr.net> - http://
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Member records.
 */
class MemberModelcredits extends JModelList
{

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'akeebasubs_subscription_id', 'a.akeebasubs_subscription_id',
                'user_id', 'a.user_id',
                'credit_invoice_number', 'a.credit_invoice_number',
                'publish_up', 'a.publish_up',
                'publish_down', 'a.publish_down',
                'amount', 'a.amount',
                'transactions', 'a.transactions',
                'credit_paid', 'a.credit_paid',
                'created_on', 'a.created_on',
                'payment_data', 'a.payment_data',

            );
        }
        
        require_once JPATH_ROOT . '/components/com_member/models/credit.php';
        $this->credit_model = new MemberModelCredit();
        require_once JPATH_ROOT . '/components/com_member/models/billing.php';
        $this->billing_model = new MemberModelBilling();
        require_once JPATH_ROOT . '/components/com_member/models/member.php';
        $this->member_model = new MemberModelMember();

        parent::__construct($config);
        
    }


	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
                
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
                
		$this->setState('filter.state', $published);
        
        
		//Filtering credit_paid
		$this->setState('filter.credit_paid', $app->getUserStateFromRequest($this->context.'.filter.credit_paid', 'filter_credit_paid', '', 'string'));

		//Filtering payment_data
		$this->setState('filter.payment_data', $app->getUserStateFromRequest($this->context.'.filter.payment_data', 'filter_payment_data', '', 'string'));

        
		// Load the parameters.
		$params = JComponentHelper::getParams('com_member');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.credit_invoice_number', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('`#__akeebasubs_subscriptions` AS a');

                $query->select('u.*');
                $query->join('LEFT', '#__akeebasubs_payment_data AS u ON u.user_id=a.user_id');


		// Filter by search in title
		$search = $this->getState('filter.search');
        
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {

                                list($y, $m, $d) = explode("-", $search);
                                $search = $db->Quote('%'.$db->escape($search, true).'%');
                                if(checkdate($m, $d, $y)){
                                    
                                    $query->where('( a.created_on LIKE '.$search.' )');
                                } else {
                                    $query->where('( a.credit_invoice_number LIKE '.$search.' )');
                                }
				
                                
                                
			}
		}
        


		//Filtering credit_paid
		$filter_credit_paid = $this->state->get("filter.credit_paid");
                
		if ($filter_credit_paid != '') {
			$query->where("a.credit_paid = '".$db->escape($filter_credit_paid)."'");
		}

		//Filtering payment_data
		$filter_payment_data = $this->state->get("filter.payment_data");
                
		if ($filter_payment_data) {
			//$query->where("a.payment_data = '".$db->escape($filter_payment_data)."'");
                        $query->where("u.payment_method = '".$db->escape($filter_payment_data)."'");
                        
		}  
                
  
                // show not current active subscriptions
                $query->where("a.enabled = '0'");
                
                // show paid only subscriptions
                $query->where("a.state = 'C'");
        
        
		// Add the list ordering clause.
        $orderCol	= $this->state->get('list.ordering');
        $orderDirn	= $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol.' '.$orderDirn));
        }

		return $query;
	}
        
        
        /** amount of sum of credits per period minus subscription fee
         * 
         * @param type $user_id
         * @param type $publish_up
         * @param type $publish_down
         * @param type $credit_paid
         * @return string
         */
        public function getAmount($user_id, $publish_up, $publish_down, $credit_paid, $paidAmount ) {
            
            if ($credit_paid == '1') {
                $amount = $paidAmount;
            } else {
                $creditsDataPerPeriod = $this->credit_model->getUserCreditsDataPerPeriod($user_id, $publish_up, $publish_down);

                $actualSubscriptionData = $this->billing_model->actualSubscriptionData($item->user_id);
            
                $gross_amount = $this->billing_model->getGrossSubscriptionPrice($actualSubscriptionData);
                $amount = $creditsDataPerPeriod->credit_value - $gross_amount;
            }

            if ($amount  && $amount > 0) {
                $result =  $amount;
            } else {
                $result =  '0.00';
   
            }
            return $result;
        }
        
        
        /** return number of transactions (credits done) for user for period
         * 
         * @param type $user_id
         * @param type $publish_up
         * @param type $publish_down
         * @param type $credit_paid
         * @return type
         */
        public function getTransactions($user_id, $publish_up, $publish_down, $credit_paid, $transactions) {
            if($credit_paid == '1') {
                return $transactions;
            } 
            
            $creditsDataPerPeriod = $this->credit_model->getUserCreditsDataPerPeriod($user_id, $publish_up, $publish_down);
            return $creditsDataPerPeriod->transactions;
         
        }
        
        /**
         * 
         * @param type $user_id
         * @return type
         */
        public function getUserPaymentData($user_id) {
            
            $userPaymentData = $this->member_model->getUserPaymentData($user_id);
            return $userPaymentData;
        }
        
        
        /** return choosen by user payment method: save, paypal, banktransfer
         * 
         * @param type $user_id
         * @return type
         */
        public function getPaymentMethod($user_id) {

            $userPaymentData = $this->getUserPaymentData($user_id);
            $payment_method = $userPaymentData->payment_method;

            switch ($payment_method) {
                case 1:
                    $method =  "Save";
                     break;
                case 2:
                    $method =  "Banktransfer";
                     break;
                case 3:
                    $method = "Paypal";
                     break;


                 default:
                     $method =  "Save";
                     break;
             }
             return $method;
        }
        
        
        /** html of preview popup with user payment details
         * 
         * @param type $item
         * @return string
         */
        public function showPreview($item) {
            
            if($item->credits_ballance > 0) {
                $credits_ballance = $item->credits_ballance;
            } else {
                $credits_ballance = '0.00';
            }
             
            
             $html = '<a href="javascript:void(0)" onclick="previewShow(' . $item->akeebasubs_subscription_id .')" ><img  class="preview_button"  alt="Preview" src="'. JUri::base() .'components/com_member_banner/assets/images/search16.png"></a>';
             
        
             
             $html .= '<div style="display:none"  id="preview_' . $item->akeebasubs_subscription_id . '">';
             
             $html .= '<div class="iconpreview">';
             
             $html .= "<br/>";
             
             $html .= '<img class="hideimage " src="' . JUri::base() . 'components/com_member_banner/images/close.png" alt="close" title="close" onclick="hidePreview(' . $item->akeebasubs_subscription_id  . ')">';
             
             $html .= '<div style="border-bottom: 1px solid #CCCCCC;  padding: 5px; position: absolute; width: 91%;">';
             
             $html .= '<div style="float:left" >Current user ballance:</div>';
             
             $html .= '<div style="float:right;">' . '€ ' . $credits_ballance .  '</div>';
                
             $html .= '</div>';
             
             if($item->payment_method == '1' OR $item->payment_method == NULL) {
             
                 $html .= '<div style="  margin-top: 50px; padding: 5px; position: relative;  width: 91%;">';

                 $html .= '<div style="text-align:center" >Save money</div>';

            
                 $html .= '</div>';
             
             }
             
             
             
             if($item->payment_method == '3') {
             
                 $html .= '<div style="  margin-top: 50px; padding: 5px; position: relative;  width: 91%;">';

                 $html .= '<div style="float:left" >Paypal adress:</div>';

                 $html .= '<div style="float:right;">' .  $item->payment_paypal_adress .  '</div>';

                 $html .= '</div>';
             
             }
             
             if($item->payment_method == '2') {
                          
                 $html .= '<div style="  margin-top: 50px; padding: 5px; position: relative;  width: 91%;">';

                 $html .= '<div style="float:left" >Account holder:</div>';

                 $html .= '<div style="float:right;">' .  $item->payment_bank_account_holder .  '</div>';

                 $html .= '</div>';


                 $html .= '<div style="  margin-top: 20px; padding: 5px; position: relative;  width: 91%;">';

                 $html .= '<div style="float:left" >Account number:</div>';

                 $html .= '<div style="float:right;">' .  $item->payment_bank_account_number .  '</div>';

                 $html .= '</div>';



                 $html .= '<div style="  margin-top: 20px; padding: 5px; position: relative;  width: 91%;">';

                 $html .= '<div style="float:left" >IBAN number:</div>';

                 $html .= '<div style="float:right;">' .  $item->payment_bank_iban_number .  '</div>';

                 $html .= '</div>';
             
             }

             
             
             $html .= '</div>';
             
             $html .= '</div>';
            
             return $html;
        }
        
        
        /** on click pay credit button in credit list
         * 
         * @param type $user_id
         * @param type $amount
         * @param type $transactions
         * @param type $subscription_id
         * @return type
         */
        public function payCredit($user_id, $amount, $transactions, $subscription_id) {
            
            if($amount < 1) {
                $result = array(
                    'message' => 'too small credits ballance',
                    'status' => false
                );
                return json_encode($result);
            }

            // check if current subscription has paid status
            $subscriptionObject = $this->getSubscriptionObject($subscription_id);
            

            if($subscriptionObject->credit_paid) {
               $result = array(
                    'message' => 'credits already paid for this period',
                    'status' => true
                );
                return json_encode($result);
            }

             
            
            // check if user current ballance is enough
            $currentUserBallance = $this->billing_model->getUserCreditsBallance($user_id);
            
       
                
                
            if ($currentUserBallance < $amount) {
               $result = array(
                    'message' => 'user current ballance is not enough (€' . $currentUserBallance . ')',
                    'status' => false
                );
                return json_encode($result);
            }
            
         
            
            // decrease user credits ballance
            $newUserCreditsBallance = $currentUserBallance - $amount;
            
            $update = $this->billing_model->updateUserCreditsBallance($user_id, $newUserCreditsBallance);
            

            
            if($update) {
                $status = true;
                
                $message = 'Ballance for user ' .  $user_id . ' updated from ' . round($currentUserBallance, 1) . ' to ' . round($newUserCreditsBallance, 1);
                
                $this->member_model->eventEmail('administration_ballance_decreased_for_user',  $message);

                // update subsctiption to paid
                $setSubscriptionStatusPaid = $this->setSubscriptionStatusPaid($user_id, $amount, $transactions, $subscription_id);
                
                if(!$setSubscriptionStatusPaid) {
                    $message .= ";  subscription not turn on paid";
                }
                // creation and send credit nota
          
                $createCreditnota = $this->createCreditnota($user_id, $subscription_id);
                
                if(!$createCreditnota) {
                    $message .= "; " .  "creditnota was not created";
                    $this->member_model->eventEmail('invoice_creditnota_not_created', $message);
                }
                         
            } else {
                $message = 'Error in updating user ballance';
                $status = false;
            }
            
            $result = array(
                'message' => $message,
                'status' => $status
            );
            
            return json_encode($result);
            
        }
        
        
        /** after pay credit (reduce user credits ballance) subscription for period updates to paid status
         * 
         * @param type $user_id
         * @param type $amount
         * @param type $transactions
         * @param type $subscription_id
         * @return boolean
         */
        private function setSubscriptionStatusPaid($user_id, $amount, $transactions, $subscription_id) {
            
            $db = &JFactory::getDbo();
            
            $object = new stdClass();
            
            $object->akeebasubs_subscription_id = $subscription_id;
            
            $object->credit_paid = '1';
            
            $object->amount = $amount;
            
            $object->transactions  = $transactions;
            
           
            
            try {
                    $db->updateObject('#__akeebasubs_subscriptions', $object, 'akeebasubs_subscription_id', false);

                    return true;
            
                } catch (Exception $e) {

                    $message = "<br/> " . 'subscription not turn on paid for ' . $user_id  . ' subscription_id ' .  $subscription_id;

                    $message .= "<br/><br/>" . print_r($object, true) . "<br/> <br/>";
                    
                    $message .= "<br/> <br/> <strong style='color:red'>" .$e->getMessage() . "</strong><br/>";

                    $this->member_model->eventEmail('administrator_set_subscription_status_paid_error', $message);

                    return false;
                }  
        
        }
        
        
        /**
         * 
         * @param type $user_id
         * @param type $subscription_id
         */
        public function createCreditnota($user_id, $subscription_id) {
            
            $subscriptionObject = $this->getSubscriptionObject($subscription_id);
            
            $paymentMethod = $this->getPaymentMethod($subscriptionObject->user_id);
            
            $subscriptionObject->payment_method = $paymentMethod;
 
            try {
                 $createInvoice = $this->billing_model->createInvoice($subscriptionObject, 'creditnota');
                return true;
            } catch (Exception $e) {

                return false;
            }
            
            
        }
        
        /** get current subscription data
         * 
         * @param type $subscription_id
         * @return type
         */
        public function getSubscriptionObject($subscription_id) {
            $db = &JFactory::getDbo();
            $query = "SELECT * FROM #__akeebasubs_subscriptions WHERE akeebasubs_subscription_id ='$subscription_id'";
            $db->setQuery($query);
            return  $db->loadObject();
        }
        

}
