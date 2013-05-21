<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * Overview Model
 */
class MemberModelOverview extends JModelItem
{
    
    public function __construct() {
        parent::__construct();
        // current date
        $UTC = '2';
        $offset = $UTC * 60 * 60;
        $this->offset = $offset;
        $dateFormat = "Y-m-d H:i:s";
        $current_time = gmdate($dateFormat, time() + $offset);
        $this->current_time = $current_time;
        
        $dateFormatDay = "Y-m-d";
        $current_date = gmdate($dateFormatDay, time() + $offset);
        $this->current_date = $current_date;
        
    }

   

     /** select database query
     *
     * @param type $query
     * @param type $type
     * @return database data 
     */
    public function customQuery($query, $type) {
	$db = & JFactory::getDBO();
	$db->setQuery($query);
	if ($db->getErrorNum()) {
	    JError::raiseWarning(500, $db->stderr());
	}
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
    }
    
    
    /**
     * 
     * @param type $user_id
     * @return type
     */
    public function getNumberActiveMembers($user_id) {
        $count = 0;
        $query = "SELECT count(akeebasubs_subscription_id) FROM #__akeebasubs_subscriptions
            WHERE enabled=1
            AND akeebasubs_affiliate_id='$user_id'";
        $result = $this->customQuery($query, 0);
        if($result) $count = $result;
        return $count;
    }
    
    
    
    public function creditsAmountPerPeriod($user_id, $startDate, $endDate) {

        $db = & JFactory::getDBO(); 
        $query = "SELECT SUM(value) as daily_credits_amount FROM #__akeebasubs_credits 
            WHERE user_id='$user_id'
            AND created_on  
            BETWEEN" . $db->quote($startDate) . "AND" . $db->quote($endDate) . "
        ";
        $result = $this->customQuery($query, 0);
        
        return $result;
        
    }
    
    /**
     * 
     * @param type $user_id
     * @param type $days_before
     * @return type
     */
    public function creditsGraphicData($user_id, $days_before = 7) {

        $data = array();
       
        for($i = 0; $i < $days_before; $i++) {
           $data[$this->monthDayDateFormat($this->getDayBeforeToday($i))] = $this->creditsAmountPerPeriod($user_id, $this->getDayBeforeToday($i), $this->getDayBeforeToday($i - 1));
        }
        
        return $data;
    }
    
    
    /**
     * 
     * @param type $days
     * @return type
     */
    public function getDayBeforeToday($days) {
        $today = &JFactory::getDate($this->current_date);
        $todayUnix = $today->toUnix();
        $beforeTodayUnix =  $todayUnix - 60*60*24*$days ; 
        $beforeToday = gmdate('Y-m-d', $beforeTodayUnix);
        return $beforeToday;
    }
    
    
    
    public function monthDayDateFormat($date) {
        $date = &JFactory::getDate($date);
        $dateUnix = $date->toUnix();
        return gmdate('M d', $dateUnix);
    }
    
}