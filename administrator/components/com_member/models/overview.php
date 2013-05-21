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
class MemberModeloverview extends JModelList
{

  
    /** get data for overview table
     * 
     * @return object list
     */
    public function getStatistic() {
        $db = &JFactory::getDbo();
        
        $limit = JRequest::getVar('limit', 5);
        
        
        
        $period = JRequest::getVar('filter_search', 0);
        
        

        $query = "SELECT
            DATE_FORMAT(created_on, '%Y-%m') as period,
            count(id) as transactions,
            SUM(value) as credits_earned,
            
            (SELECT SUM(gross_amount) FROM #__akeebasubs_subscriptions
            WHERE state='C' AND 
            MONTH(publish_up)=MONTH(CONCAT(period,'-01')) AND
            YEAR(publish_up)=YEAR(CONCAT(period,'-01'))) incoming_money,
            

            (SELECT SUM(amount) FROM #__akeebasubs_subscriptions
            WHERE state='C' AND 
            MONTH(publish_up)=MONTH(CONCAT(period,'-01')) AND
            YEAR(publish_up)=YEAR(CONCAT(period,'-01'))) credits_payout

            FROM #__akeebasubs_credits";
        
        if($period) {
            
              $query .= " WHERE  created_on LIKE '$period%'";
        }
          
        $query .= " GROUP BY YEAR(created_on), MONTH(created_on) ORDER BY created_on DESC ";
        
        if($limit != 'all') {
            $query .= " LIMIT 0, " . $limit;
        }
        
        $db->setQuery($query);
      
        $result = $db->loadObjectList();
        
        return $result;
    }


}
