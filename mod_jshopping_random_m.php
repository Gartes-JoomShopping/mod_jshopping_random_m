<?php
    /*******************************************************************************************************************
     *     ╔═══╗ ╔══╗ ╔═══╗ ╔════╗ ╔═══╗ ╔══╗        ╔══╗  ╔═══╗ ╔╗╔╗ ╔═══╗ ╔╗   ╔══╗ ╔═══╗ ╔╗  ╔╗ ╔═══╗ ╔╗ ╔╗ ╔════╗
     *     ║╔══╝ ║╔╗║ ║╔═╗║ ╚═╗╔═╝ ║╔══╝ ║╔═╝        ║╔╗╚╗ ║╔══╝ ║║║║ ║╔══╝ ║║   ║╔╗║ ║╔═╗║ ║║  ║║ ║╔══╝ ║╚═╝║ ╚═╗╔═╝
     *     ║║╔═╗ ║╚╝║ ║╚═╝║   ║║   ║╚══╗ ║╚═╗        ║║╚╗║ ║╚══╗ ║║║║ ║╚══╗ ║║   ║║║║ ║╚═╝║ ║╚╗╔╝║ ║╚══╗ ║╔╗ ║   ║║
     *     ║║╚╗║ ║╔╗║ ║╔╗╔╝   ║║   ║╔══╝ ╚═╗║        ║║─║║ ║╔══╝ ║╚╝║ ║╔══╝ ║║   ║║║║ ║╔══╝ ║╔╗╔╗║ ║╔══╝ ║║╚╗║   ║║
     *     ║╚═╝║ ║║║║ ║║║║    ║║   ║╚══╗ ╔═╝║        ║╚═╝║ ║╚══╗ ╚╗╔╝ ║╚══╗ ║╚═╗ ║╚╝║ ║║    ║║╚╝║║ ║╚══╗ ║║ ║║   ║║
     *     ╚═══╝ ╚╝╚╝ ╚╝╚╝    ╚╝   ╚═══╝ ╚══╝        ╚═══╝ ╚═══╝  ╚╝  ╚═══╝ ╚══╝ ╚══╝ ╚╝    ╚╝  ╚╝ ╚═══╝ ╚╝ ╚╝   ╚╝
     *------------------------------------------------------------------------------------------------------------------
     *
     * @author     Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
     * @date       30.11.2020 08:19
     * @copyright  Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
     * @license    GNU General Public License version 2 or later;
     ******************************************************************************************************************/

    use Joomla\CMS\Factory;
    use Joomla\CMS\Helper\ModuleHelper;
    use Joomla\CMS\Table\Table;
    use Joomla\CMS\Uri\Uri;

    defined('_JEXEC') or die('Restricted access');
    /**
     * @var stdClass                 $module Парметры модуля
     * @var Joomla\Registry\Registry $params Настройки модуля
     */







    require_once dirname(__FILE__) . '/helper.php';
    $app = Factory::getApplication();
    $session = Factory::getSession();
    $doc = \Joomla\CMS\Factory::getDocument();
    $doc->addStyleSheet(Uri::root() . 'modules/mod_jshopping_random_m/assets/css/slide-random_m.css');


    $layout = 'default' ;
    $noimage = "noimage.gif";
    $show_image = $params->get('show_image' , 1);
    $module_type = $params->get('module_type' ) ;






    # Скрипт JS загрузчика слайдеров
    $_pathScript = JPATH_ROOT . '/modules/mod_jshopping_random_m/tmpl';
    $layoutScript    = new \Joomla\CMS\Layout\FileLayout( '_module-script' ,$_pathScript );

    $category_id = $app->input->get('category_id' , false , 'INT');
    $product_id = $app->input->get('product_id' , false , 'INT');


    $Helper = ModJshoppingRandomMHelper::instance($params);

    # Установка способа отображения модуля
    $Helper->setDisplayMethod($module , $params);
    # парсинг названия модуля
    $module  = $Helper->getNameModule($module);




    
    
    $category_id = is_array($category_id) ? $category_id : [$category_id];

    $Options = $doc->getScriptOptions('SlickProductDetail' );

    if( empty( $Options ) )
    {
        $__v  = $params->get('__v' , '1.7.2');
        $Options = [
            '__module' => 'jshopping_random_m' ,
            '__v' => $__v ,
            'current_category' => $app->input->get('category_id' , false , 'INT') ,
            'product_id' => $app->input->get('product_id' , false , 'INT') ,
            'sliders' =>[] ,
        ];
        # Скрипт загрузчика
        echo $layoutScript->render($Options) ;






        if( $doc->getType() == 'html' && $params->get('add_preloader_access_css' , 1 )  )
        {

            $html = '<link rel="preload" href="' . Uri::root() . 'libraries/GNZ11/assets/js/modules/Slick/slick.css?v=1.7.2" as="style">';
            $doc->addCustomTag($html);
            $html = '<link rel="preload" href="' . Uri::root() . 'libraries/GNZ11/assets/js/modules/Slick/slick-theme.css?v=1.7.2" as="style">';
            $doc->addCustomTag($html);
        }#END IF

    }#END IF
    $Options['sliders'][ $module->id ] = [] ;





    $module_type = $params->get('module_type' , 'Random') ;

    $count_products = $params->get('count_products' , 7) ;


    if( $module->id == 333 )
    {
        //        echo'<pre>';print_r( $params );echo'</pre>'.__FILE__.' '.__LINE__;
        //        die(__FILE__ .' '. __LINE__ );

    }#END IF
    # Суффикс CSS-класса модуля
    $moduleClass_sfx = $params ->get('moduleclass_sfx' , null ) ;
    $moduleClass_sfx .= ' module-block' ;
    $templatesElement = [] ;
    switch ($module_type){
        # Недавно просмотренные
        case 'RecentlyViewed':
            $doc->addScriptOptions('SlickProductDetail' , ['RecentlyViewed' => true ]);
            // Добавить к названию модуля элемент управления
            $module = $Helper::addTitleControl($module , $params );
            // Элементы меню заголовка
            $templatesElement['headMenu'] = $Helper::getLayoutHtml('RecentlyViewedHeadMenu' , $module , $params ) ;
            $templatesElement['productMenu'] = $Helper::getLayoutHtml('RecentlyViewedProductMenu' , $module , $params ) ;
            $templatesElement['productMenuBtn'] = $Helper::getLayoutHtml('RecentlyViewedProductMenuBtn' , $module , $params ) ;
            $moduleClass_sfx .= ' hide' ;
            $layout = 'recently_viewed' ;
            break ;
        # Родственные товары
        case 'Related':

            # Получить родственные товары
            $last_prod = $Helper->getRelatedProducts( $product_id );

            # Если количество отобранных товаров больше чем ( из настроек модуля )товаров на экране + 1
            # обрезаем до количества на экране + 1 (+1 нужен для ого чтобы были стрелки в право)
            if( count($last_prod) > $count_products + 1  )
            {
                $last_prod = array_slice( $last_prod , 0 , $count_products +1 );
            }#END IF

            break ;
        # случайные товары из массива категорий
        default:
            # Получить случайные товары из массива категорий
            $last_prod = $Helper->getRandProducts($count_products , $params , $category_id , [] );
    }
    $moduleClass_sfx .= ' '.$module_type ;
    $params->set('moduleclass_sfx' , $moduleClass_sfx ) ;

    $Options['sliders'][ $module->id ]['module_type'] = $module_type ;
    $Options['sliders'][ $module->id ]['display_method'] = $params->get('display_method' , 'default');
    $Options['sliders'][ $module->id ]['product_id'] = $product_id ;
    $Options['sliders'][ $module->id ]['count_products'] = $count_products ;
    $Options['sliders'][ $module->id ]['count_sliders'] = $params->get('count_sliders' , 6) ;
    $Options['sliders'][ $module->id ]['total_product'] = $Helper->totalRow ;
    $Options['sliders'][ $module->id ]['loaded_product'] = [] ;
    // дополнительные элементы для слайдера
    $Options['sliders'][ $module->id ]['templatesElement'] = $templatesElement ;

    require( ModuleHelper::getLayoutPath('mod_jshopping_random_m' , $layout  ) );


    $doc->addScriptOptions('SlickProductDetail' , $Options);
    $Helper->totalRow = 0 ;













