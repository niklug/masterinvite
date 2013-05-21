<?php
defined('_JEXEC') or die();

define('AKEEBASUBS_VERSION', '3.0.2');
define('AKEEBASUBS_DATE', '2013-03-14');
define('AKEEBASUBS_PRO', '1');
if(version_compare(JVERSION, '3.0', 'ge')) {
	define('AKEEBASUBS_VERSIONHASH', md5(AKEEBASUBS_VERSION.AKEEBASUBS_DATE.JFactory::getConfig()->get('secret','')));
} else {
	define('AKEEBASUBS_VERSIONHASH', md5(AKEEBASUBS_VERSION.AKEEBASUBS_DATE.JFactory::getConfig()->getValue('secret','')));
}
?>