<?php

/**
 * @version     1.0.0
 * @package     com_member_banner
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Nikolay Korban <niklug@ukr.net> - http://
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Member_banner records.
 */
class Member_bannerModelBanners extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {
        
        // Initialise variables.
        $app = JFactory::getApplication();


        // List state information.
        parent::populateState($ordering, $direction);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );
        
        $query->from('`#__member_banners` AS a');
        

		// Join over the created by field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');


		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
                $query->where('( a.name LIKE '.$search.' )');
			}
		}
        


		//Filtering language
		$filter_language = $this->state->get("filter.language");
                $language = JRequest::getVar('banner_lang');
               
		if ($language) {
			$query->where("a.language = '".$language."'");
		}     
                $query->where("a.state = '1'");
                
                $limit =  JRequest::getVar('limit', 10);
                
                if($limit != 'all') {
                
                    $this->setState('list.limit', $limit);
                }

                $this->setState('list.start', 0);
              
        return $query;
    }
    
            public function getBanners() {
            $db = &JFactory::getDbo();
            $query = "SELECT * FROM #__member_banners WHERE state=1";
            $db->setQuery($query);
            $result = $db->loadObjectList();
            return $result;
        }

}
