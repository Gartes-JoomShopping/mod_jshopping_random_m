
<?php
/**
* @version      2.5.0 12.11.2010
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

    defined('_JEXEC') or die('Restricted access');
    
    if (!file_exists(JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS.'jshopping.php')){
        JError::raiseError(500,"Please install component \"joomshopping\"");
    } 
    
    require_once (JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS."lib".DS."factory.php"); 
    require_once (JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS."lib".DS."functions.php");        
    JSFactory::loadCssFiles();
    JSFactory::loadLanguageFile();
    $jshopConfig = &JSFactory::getConfig();
    
    $product = &JTable::getInstance('product', 'jshop');
    $cat_str = JRequest::getInt('category_id'); 
    if (is_array($cat_str)) {    
        $cat_arr = array();
        foreach($cat_str as $key=>$curr){
		   if ($curr->label_id == '6') continue;
           if (intval($curr)) $cat_arr[$key] = intval($curr);
        }  
    } else {
        $cat_arr = array();
        if (intval($cat_str)) $cat_arr[] = intval($cat_str);
    }
    $last_prod = $product->getRandProducts($params->get('count_products', 5), $cat_arr);   
    foreach($last_prod as $key=>$value){
		if ($key->label_id == '6') continue;
        $last_prod[$key]->product_link = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$value->category_id.'&product_id='.$value->product_id, 1);
    }
    $noimage = "noimage.gif";
    $show_image = $params->get('show_image',1);
    require(JModuleHelper::getLayoutPath('mod_jshopping_random_m'));        
?>