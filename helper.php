<?php
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS."lib".DS."factory.php"); 
require_once (JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS."lib".DS."functions.php");        
   
$db = &JFactory::getDBO();
$jshopConfig = &JSFactory::getConfig();
$jshopConfig->cur_lang = $jshopConfig->frontend_lang;

?>