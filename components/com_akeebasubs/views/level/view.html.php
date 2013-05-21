<?php
/**
 * @package AkeebaSubs
 * @copyright Copyright (c)2010-2013 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

class AkeebasubsViewLevel extends FOFViewHtml
{	
	protected function onRead($tpl = null) {
		JRequest::setVar('hidemainmenu', true);
		$model = $this->getModel();
                                
                $aid = JRequest::getVar('aid');
                
                $aid_exists = $this->checkAffId($aid);
                
                if($aid_exists) {
                    $session = &JFactory::getSession();
                    $session->set('affid', $aid);
                }
                
		$this->assignRef( 'item',		$model->getItem() );
                $this->assignRef( 'aid_exists',		$aid_exists );
                
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
                $db->setQuery($query);
                if ($db->getErrorNum()) {
                    JError::raiseWarning(500, $db->stderr());
                }
                $result = $db->loadResult();
                if ($result) {
                    $aff_exists = true;
                }
                return $aff_exists;
            }
}