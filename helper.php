<?php

    use Joomla\CMS\Factory;
    use Joomla\CMS\Layout\LayoutHelper;
    use Joomla\CMS\MVC\View\HtmlView;
    use Joomla\CMS\Table\Table;
    use Joomla\Registry\Registry;

    defined('_JEXEC') or die('Restricted access');

    require_once(JPATH_SITE . DS . 'components' . DS . 'com_jshopping' . DS . "lib" . DS . "factory.php");
    require_once(JPATH_SITE . DS . 'components' . DS . 'com_jshopping' . DS . "lib" . DS . "functions.php");

    class ModJshoppingRandomMHelper
    {
        /**
         * @var \Joomla\CMS\Application\CMSApplication|null
         * @since 3.9
         */
        private $app;
        /**
         * @var \JDatabaseDriver|null
         * @since 3.9
         */
        private $db;
        public static $instance;

        private $params ;

        public static $viewConfig = [
            'name' => 'category' ,
            'charset' => 'UTF-8' ,
            'template_plath' => null ,
        ];
        /**
         * @var Object jshopConfig конфигурация jshop
         * @since 3.9
         */
        public $jshopConfig;
        /**
         * @var  string Имя шаблона
         * @since 3.9
         */
        private $template;
        /**
         * @var int Общее количество товаров минус загруженные
         * @since 3.9
         */
        public $totalRow;
        /**
         * Шаблоны замены
         * @var string[]
         * @since 3.9
         */
        private static $replaceTemplate = [
            '[[[product-name]]]' => '' ,
        ] ;

        /**
         * helper constructor.
         *
         * @param array $options
         *
         * @throws Exception
         * @since 3.9
         */
        private function __construct( $options = array() )
        {
            $this->params = $options ;
            $this->app = Factory::getApplication();
            $this->db = Factory::getDbo();

            $this->template = $this->app->getTemplate();

            $this->jshopConfig = JSFactory::getConfig();
            $this->jshopConfig->cur_lang = $this->jshopConfig->frontend_lang;

            return $this;
        }#END FN

        /**
         * @param array $options
         *
         * @return ModJshoppingRandomMHelper
         * @throws Exception
         * @since 3.9
         */
        public static function instance( $options = array() )
        {
            if ( self::$instance === null ){
                self::$instance = new self($options);
            }
            return self::$instance;
        }#END FN

        /**
         * Установка способа отображения модуля
         *
         * @param stdClass $module объект модуля
         * @param Registry $params параметры модуля
         *
         * @since  3.9
         * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
         * @date   24.12.2020 08:23
         */
        public function setDisplayMethod(stdClass $module , Registry &$params)
        {
//            echo'<pre>';print_r( $params );echo'</pre>'.__FILE__.' '.__LINE__;



            # Способ отображения
            $display_method = $params->get('display_method' , 'default');
            $header_class = $params->get('header_class' , null ) ;
            switch ($display_method)
            {
                case 'visibility'  :
                    $addClassHeader = 'check-position--visibility' ;
                    $params->set( 'header_class' , $header_class . ' ' . $addClassHeader);
                    break;
                default :

            }
        }

        /**
         * Обработка названия модуля
         * Замена Short Code
         *      [[[product-name]]] - Название товара
         * @param $module
         *
         * @return mixed
         * @since  3.9
         * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
         * @date   01.12.2020 05:31
         *
         */
        public function getNameModule( $module ){
            $product_id = $this->app->input->get('product_id' , false , 'INT') ;
            if( $product_id )
            {
                $product = Table::getInstance('product' , 'jshop');
                $product->load($product_id);
                self::$replaceTemplate[ '[[[product-name]]]' ] = $product->{'name_ru-RU'} ;
            }#END IF

            $fieldArr = [ 'title' , ];
            foreach ( $fieldArr as $key)
            {
                $module->{$key} = str_replace( array_keys( self::$replaceTemplate ) , self::$replaceTemplate , $module->{$key} );
            }#END FOREACH

            return $module  ;
        }

        /**
         * Добавить кнопки для управления
         *
         * @param $module
         * @param $params
         *
         * @return mixed
         * @since  3.9
         * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
         * @date   23.12.2020 13:38
         */
        public static function addTitleControl ( $module , $params){

            $layout = 'mod_jshopping_random_m.recently_viewed_control' ;
            $basePath = JPATH_ROOT . '/modules/mod_jshopping_random_m/layouts/' ;
            $module->title .= LayoutHelper::render( $layout , ['module'=>$module,  'params'=>$params] , $basePath ) ;

            return $module ;
        }

        /**
         * Загрузка Layouts
         *
         * @param       $layout - имя Layout
         * @param       $module
         * @param       $params
         *
         * @return string
         * @since  3.9
         * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
         * @date   12.01.2021 15:06
         */
        public static function getLayoutHtml($layout , $module ,   $params){
            $layout = 'mod_jshopping_random_m.'.$layout ;
            $basePath = JPATH_ROOT . '/modules/mod_jshopping_random_m/layouts/' ;
            return LayoutHelper::render( $layout , [ 'module'=>$module , 'params'=>$params ] , $basePath ) ;
        }


        /**
         * Получить товары из истории просмотра
         *
         * @param array $RecentlyViewed - id товаров для получения
         * @param array $filters - фильтр для товаров
         * @param null  $order
         * @param null  $orderby
         * @param int   $limitstart
         * @param int   $limit
         *
         * @return array
         * @since  3.9
         * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
         * @date   03.12.2020 11:33
         */
        public function getRecentlyViewed( $RecentlyViewed = [] ,  $filters = [] , $order = null, $orderby = null, $limitstart = 0, $limit = 0 ): array
        {
            $session = Factory::getSession();
            $jshopConfig = JSFactory::getConfig();
            $lang = JSFactory::getLang();
            $dispatcher = JDispatcher::getInstance();

            $product = Table::getInstance('product' , 'jshop');


            if( empty( $RecentlyViewed ) )
            {
                $RecentlyViewed = array_reverse( $session->get('RecentlyViewed', [] ) );
                if( empty( $RecentlyViewed ) )  return []; #END IF
            }#END IF


            $this->totalRow = count( $RecentlyViewed ) ;


            $adv_query = "";
            $adv_from = "";

            # Поля для SELECT по умолчанию
            $adv_result = $product->getBuildQueryListProductDefaultResult();

            $dispatcher->trigger('onBeforeQueryCountProductList', array("category", &$adv_result, &$adv_from, &$adv_query, &$filters) );
            # Параметры запроса в зависимости от jshopConfig
            $product->getBuildQueryListProduct("products", "list", $filters, $adv_query, $adv_from, $adv_result);
            # Установка параметров сортировки в запросе
            $order_query = $product->getBuildQueryOrderListProduct($order, $orderby, $adv_from);


            $dispatcher->trigger('onBeforeQueryGetProductList', array("recently_viewed", &$adv_result, &$adv_from, &$adv_query, &$order_query, &$filters) );

            if( !empty( $filters['product_id']  ) )
            {

                $RecentlyViewed = array_diff( $RecentlyViewed , $filters['product_id'] );
            }#END IF

            $adv_query .= ' AND prod.product_id IN (' . implode( ' , ' , $RecentlyViewed ) . ') ' ;



           /* echo'<pre>';print_r( $RecentlyViewed );echo'</pre>'.__FILE__.' '.__LINE__;
            echo'<pre>';print_r( $filters['product_id'] );echo'</pre>'.__FILE__.' '.__LINE__;
            die(__FILE__ .' '. __LINE__ );*/

            $query = "SELECT $adv_result FROM `#__jshopping_products` AS prod
                  LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat USING (product_id)
                  LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
                  $adv_from
                  WHERE prod.product_publish=1 AND cat.category_publish=1 ".$adv_query ;
            $query .= "GROUP BY prod.product_id " ;
            $query .= $order_query ;





            if ($limit){
                $this->db->setQuery($query, $limitstart, $limit);
            }else{
                $this->db->setQuery($query);
            }
            $products = $this->db->loadObjectList('product_id');

//            usort($products,   ['ModJshoppingRandomMHelper' , "sortByPredefinedOrder"] );

            $sort = array_flip($RecentlyViewed);
            usort($products, function($a,$b) use($sort){
                return $sort[$a->product_id] - $sort[$b->product_id];
            });

            $products = listProductUpdateData($products, 1);

            $view = $this->getView();
            $view->set('rows' , $products);
            $dispatcher->trigger('onBeforeDisplayProductListView', array( &$view ) );
            $products = $view->get('rows') ;

            return $products ;

        }

        /**
         * Получение родственных товаров
         *
         * @param $product_id
         *
         * @return mixed
         * @since  3.9
         * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
         * @date   01.12.2020 01:55
         *
         */
        public function getRelatedProducts($product_id)
        {

            $dispatcher = JEventDispatcher::getInstance();

            $product = Table::getInstance('product' , 'jshop');
            $product->load($product_id);
            $product->getRelatedProducts();

            $view = $this->getView();
            $products = $product->product_related;

            $this->totalRow = count($products);

            $view->set('rows' , $products);
            $dispatcher->trigger('onBeforeDisplayProductListView' , array(&$view));
            $products = $view->get('rows');

            return $products;
        }

        /**
         * Получить случайные товары
         *
         * @param int                      $count            Количество товаров
         * @param Joomla\Registry\Registry $params
         * @param array                    $array_categories Массив категорий
         * @param array                    $filters
         *
         * @return mixed
         * @since  3.9
         * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
         * @date   26.11.2020 22:41
         */
        public function getRandProducts(int $count , Registry $params , $array_categories = [] , $filters = []  )
        {

            $dispatcher = JEventDispatcher::getInstance();
            $product = Table::getInstance('product' , 'jshop');

            $adv_query = "";
            $adv_from = "";
            $adv_result = $product->getBuildQueryListProductDefaultResult();
            $product->getBuildQueryListProductSimpleList("rand" , $array_categories , $filters , $adv_query , $adv_from , $adv_result);

            $dispatcher->trigger( 'onBeforeQueryGetProductList', array("rand_products", &$adv_result, &$adv_from, &$adv_query, &$order_query, &$filters) );


            if( isset($filters['product_id']) && is_array($filters['product_id']) && count($filters['product_id']) )
            {
                $adv_query .= " AND prod.product_id NOT IN (" . implode("," , $filters['product_id']) . ")";
            }#END IF

            # Если установлено исключать товары с метками
            $filters['labels'] = $params->get('exclude_label_id' , []);
            if ( isset($filters['labels']) && is_array($filters['labels']) && count($filters['labels']) ){
                $adv_query .= " AND prod.label_id NOT IN (" . implode("," , $filters['labels']) . ")";
            }

            # Если установлено (настройки модуля) не отображать товары без цен
            $filters['product_price'] = $params->get('product_no_price' , 0);
            if ( !$filters['product_price'] ){
                $adv_query .= " AND prod.product_price > 0 ";
            }

            # Получить общее количество строк
            $query = "SELECT count(distinct prod.product_id) FROM `#__jshopping_products` AS prod
                  INNER JOIN `#__jshopping_products_to_categories` AS pr_cat ON pr_cat.product_id = prod.product_id
                  LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
                  $adv_from
                  WHERE prod.product_publish=1 AND cat.category_publish=1 " . $adv_query;
            $this->db->setQuery($query);
            # Общее количество строк
            $this->totalRow = $this->db->loadResult();
            

            
            
            # От общего количества отимаем количество которое будет загружено ( 134 - 7 = 127 )
            $this->totalRow = $this->totalRow - $count;

            if ( $this->totalRow < 0 ) $this->totalRow = 0;
            $limitstart = rand(0 , $this->totalRow);

            $order = array();
            $order[] = "name asc";
            $order[] = "name desc";
            $order[] = "prod.product_price asc";
            $order[] = "prod.product_price desc";
            $orderby = $order[rand(0 , 3)];

            $query = "SELECT $adv_result FROM `#__jshopping_products` AS prod
                  INNER JOIN `#__jshopping_products_to_categories` AS pr_cat ON pr_cat.product_id = prod.product_id
                  LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
                  $adv_from
                  WHERE prod.product_publish=1 AND cat.category_publish=1 " . $adv_query . "
                  GROUP BY prod.product_id order by " . $orderby . "
				  LIMIT " . $limitstart . ", " . $count;
            $this->db->setQuery($query);
            $products = $this->db->loadObjectList();


            $products = listProductUpdateData($products , 1);
            $view = $this->getView();
            $view->set('rows' , $products);
            $dispatcher->trigger('onBeforeDisplayProductListView', array( &$view ) );
            $products = $view->get('rows') ;

            $this->prod = $view->get('rows') ;

            return $products;
        }

        /**
         * Получить View
         * @return HtmlView
         * @since 3.9
         */
        public function getView()
        {
            $view = new HtmlView(self::$viewConfig);
            $path = JPATH_THEMES . '/' . $this->template .'/html/com_jshopping/val_shopping/list_products/' ;
            $view->addTemplatePath($path);
            $view->set( 'config' , $this->jshopConfig )     ;
            return $view ;
        }



        public static function getProductsAjax(){
            die(__FILE__ .' '. __LINE__ );

            $app = Factory::getApplication();
            $template = $app->getTemplate();


            $module = JModuleHelper::getModule( 'mod_jshopping_random_m'  );
            $params = new Registry( $module->params );
            $Helper = ModJshoppingRandomMHelper::instance( $params ) ;

            $category = $app->input->get('current_category' , false , 'INT' );
            $module_type = $app->input->get('module_type' , 'Random' , 'STRING' );
            $count = $app->input->get('count' , false , 'INT' );

            if( !is_array($category) ) $category = [$category]; #END IF

            switch ($module_type){
                case 'Random' :

                    $products = $Helper->getRandProducts( $count , $params ,  $category );
                    break ;
            }



            $noimage = "noimage.gif";
            $show_image = $params->get('show_image' , 1);


            $path = JPATH_THEMES . '/' . $template .'/html/com_jshopping/val_shopping/list_products/' ;
            $htmlData = [] ;
            foreach ( $products as $product)
            {

                ob_start();
                ?>
                <div>
                    <div>
                        <div class="jswidth20 block_product" style="width: 100%; display: inline-block;">
                            <?php
                            include($path . 'product.php');
                            ?>
                        </div>
                    </div>
                </div>

                <?php
                $htmlData[] = ob_get_contents();
                ob_end_clean();

            }#END FOREACH

            echo new JResponseJson($htmlData);
            die();
        }

        /**
         * Получить слайдеры
         * @since  3.9
         * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
         * @date   02.12.2020 03:52
         *
         */
        public static function getSlidersAjax(){
            $app = Factory::getApplication();
            # ID - модуля для которого загружаются товары
            $module_id = $app->input->get('module_id' , false , 'INT') ;
            # Массив всех модулей
            $slidersARR = $app->input->get('sliders' , [] , 'ARRAY') ;

            $module = \Joomla\CMS\Helper\ModuleHelper::getModuleById( (string)$module_id );
            $params = new Registry( $module->params );
            $Helper = ModJshoppingRandomMHelper::instance( $params ) ;

            $currentModuleData = $slidersARR[$module_id];






            switch ($currentModuleData['module_type']){
                # Просмотренные товары
                case 'RecentlyViewed' :
                    $count = $params->get('count_sliders') ;

                    # Массив с товарами из истории просмотра
                    $productsArr =  $currentModuleData['localDbProduct'] ;

                    # Переворачиваем массив из БД
                    $productsArr = array_reverse( $productsArr ) ;
                    # Выбрать колонку в много мерном массиве с Id товаров
                    $productsIds = array_column($productsArr, 'product_id');



                    if( isset( $currentModuleData['loaded_product'] ) )
                    {
                        $filter = [
                            'product_id' => $currentModuleData['loaded_product']
                        ];
                    }else{
                        $currentModuleData['loaded_product'] = [] ;
                    }#END IF


                    # Получить список просмотренных товаров
                    $listProduct = $Helper->getRecentlyViewed( $productsIds , $filter  ) ;


                    $currentModuleData['count_sliders'] = $count ;
//                    $currentModuleData['loaded_product'] = [] ;

//                    echo'<pre>';print_r( $filter );echo'</pre>'.__FILE__.' '.__LINE__;
                    
                    
                    
                    break ;

                case 'Related' : # Похожие товары
                    $product_id = $currentModuleData['product_id'];
                    # Получить родственные товары
                    $listProduct = $Helper->getRelatedProducts( $product_id );
                    
                    break ;
                default : #Random
                    $category_id = $app->input->get('category_id' , false , 'INT');
                    $category_id = is_array($category_id) ? $category_id : [$category_id];
                    $count = $params->get('count_sliders') ;

                    $filter = [
                        'product_id' => $currentModuleData['loaded_product']
                    ];
                    $listProduct = $Helper->getRandProducts( $count , $params ,  $category_id , $filter );
                    $currentModuleData['total_product'] = $Helper->totalRow ;

            }# END SWITCH





            # Создать слайды для отобранных товаров
            $htmlData = $Helper->renderProductSliderAjax( $listProduct , $currentModuleData );



            $slidersARR[$module_id] = $currentModuleData ;
            $retData = [
                'sliders' =>  $slidersARR ,
                'html' =>  $htmlData ,
            ];
            echo new \JResponseJson( $retData );
            die();

        }

        /**
         * Создать слайды для отобранных товаров
         * @param $listProduct
         * @param $currentModuleData
         *
         * @return array
         * @since  3.9
         * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
         * @date   02.12.2020 05:40
         *
         */
        private function renderProductSliderAjax($listProduct , &$currentModuleData){
            $noimage = "noimage.gif";
            $show_image = $this->params->get('show_image' , 1);
            $template = $this->app->getTemplate();

            $path = JPATH_THEMES . '/' . $template .'/html/com_jshopping/val_shopping/list_products/' ;
            $htmlData = [] ;
            $i = 0 ;
            foreach ( $listProduct as $product)
            {

                # TODO - для слайдера type Random добавить фильтр по Id товара
                # Если товар был уже загружен в слайдер пропускаем
                if( in_array( $product->product_id , $currentModuleData['loaded_product'] ) ) continue ; #END IF

                # если количество слайдов больше чем (из настроек модуля) - количество отображаемых слайдов на странице
                if( $i > $currentModuleData['count_sliders'] ) return $htmlData ; #END IF

                # Добавляем Id Товара как загруженный
                $currentModuleData['loaded_product'][] = $product->product_id ;


                ob_start();
                ?>
                <div>
                    <div>
                        <div class="jswidth20 block_product" style="width: 100%; display: inline-block;">
                            <?php
                                 try
                                 {
                                     // Code that may throw an Exception or Error.
                                     include($path . 'product.php');

                                 }
                                 catch (Exception $e)
                                 {
                                     // Executed only in PHP 5, will not be reached in PHP 7
                                     echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
                                     echo'<pre>';print_r( $e );echo'</pre>'.__FILE__.' '.__LINE__;
                                     die(__FILE__ .' '. __LINE__ );
                                 }

                            ?>
                        </div>
                    </div>
                </div>

                <?php



                $htmlData[] = ob_get_contents();
                ob_end_clean();
                $i++ ;
            }#END FOREACH


            return $htmlData ;
        }


    }











