<?php 
// No direct access to this file
defined('_JEXEC') or die;

JLoader::register('modaffiliateHelper', JPATH_BASE.'/modules/mod_affiliate/helper.php');

require JModuleHelper::getLayoutPath('mod_affiliate', $params->get('layout', 'default'));
