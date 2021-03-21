/***********************************************************************************************************************
 * ╔═══╗ ╔══╗ ╔═══╗ ╔════╗ ╔═══╗ ╔══╗  ╔╗╔╗╔╗ ╔═══╗ ╔══╗   ╔══╗  ╔═══╗ ╔╗╔╗ ╔═══╗ ╔╗   ╔══╗ ╔═══╗ ╔╗  ╔╗ ╔═══╗ ╔╗ ╔╗ ╔════╗
 * ║╔══╝ ║╔╗║ ║╔═╗║ ╚═╗╔═╝ ║╔══╝ ║╔═╝  ║║║║║║ ║╔══╝ ║╔╗║   ║╔╗╚╗ ║╔══╝ ║║║║ ║╔══╝ ║║   ║╔╗║ ║╔═╗║ ║║  ║║ ║╔══╝ ║╚═╝║ ╚═╗╔═╝
 * ║║╔═╗ ║╚╝║ ║╚═╝║   ║║   ║╚══╗ ║╚═╗  ║║║║║║ ║╚══╗ ║╚╝╚╗  ║║╚╗║ ║╚══╗ ║║║║ ║╚══╗ ║║   ║║║║ ║╚═╝║ ║╚╗╔╝║ ║╚══╗ ║╔╗ ║   ║║
 * ║║╚╗║ ║╔╗║ ║╔╗╔╝   ║║   ║╔══╝ ╚═╗║  ║║║║║║ ║╔══╝ ║╔═╗║  ║║─║║ ║╔══╝ ║╚╝║ ║╔══╝ ║║   ║║║║ ║╔══╝ ║╔╗╔╗║ ║╔══╝ ║║╚╗║   ║║
 * ║╚═╝║ ║║║║ ║║║║    ║║   ║╚══╗ ╔═╝║  ║╚╝╚╝║ ║╚══╗ ║╚═╝║  ║╚═╝║ ║╚══╗ ╚╗╔╝ ║╚══╗ ║╚═╗ ║╚╝║ ║║    ║║╚╝║║ ║╚══╗ ║║ ║║   ║║
 * ╚═══╝ ╚╝╚╝ ╚╝╚╝    ╚╝   ╚═══╝ ╚══╝  ╚═╝╚═╝ ╚═══╝ ╚═══╝  ╚═══╝ ╚═══╝  ╚╝  ╚═══╝ ╚══╝ ╚══╝ ╚╝    ╚╝  ╚╝ ╚═══╝ ╚╝ ╚╝   ╚╝
 *----------------------------------------------------------------------------------------------------------------------
 * @author Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
 * @date 29.11.2020 03:00
 * @copyright  Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 **********************************************************************************************************************/
/* global jQuery , Joomla   */
window.mod_jshopping_random_m_Init = function () {
    var $ = jQuery;
    var self = this;
    // Домен сайта
    var host = Joomla.getOptions('GNZ11').Ajax.siteUrl;
    // Медиа версия
    var __v = '';
    var passiveSupported = false;
    try {
        window.addEventListener( "test", null,
            Object.defineProperty({}, "passive", { get: function() { passiveSupported = true; } }));
    } catch(err) {}
    this.__type = false;
    this.__plugin = false;
    this.__name = false;
    this._params = {
        __module: false,
        RecentlyViewed : false ,
    };
    // Параметры Ajax по умолчвнию
    this.AjaxDefaultData = {
        group   : null ,
        plugin  : null ,
        module  : null ,
        method  : null ,
        option  : 'com_ajax' ,
        format  : 'json' ,
        task    : null ,
    };
    // Default object parameters
    this.ParamsDefaultData = {
        // Медиа версия
        __v: '1.0.0',
        // Режим разработки 
        development_on: false,
    }
    /**
     * Селекторы объекта
     * @type {{slickBlocks: string}}
     */
    this.selectors = {
        slickBlocks : '.slick-similar-products'
    }
    /**
     * Название базы данных
     * @type {string}
     */
    this._DB_NAME = 'jshopping_random_m';
    /**
     * Имя таблицы
     * @type {string}
     * @private
     */
    this._DB_TBL = 'RecentlyViewed' ;
    /**
     * Соедидение с DB
     */
    this.DB ;

    /**
     * Start Init
     * @constructor
     */
    this.Init = function () {
        this._params = Joomla.getOptions('SlickProductDetail', this.ParamsDefaultData);
        __v = self._params.development_on ? '' : '?v=' + self._params.__v;

        // Параметры Ajax Default
        this.setAjaxDefaultData();

        // Загрузить ресурсы Slick
        this.loadAssetsSlick().then(function (r){

            // Инициализация слайдеров которые уже есть на странице
            self.InitSlidersNew();
            // Init - истории просмотренных товаров
            self.InitRecentlyViewed();
        },function (err){console.log(err)});
    };

    /**
     * Init - истории просмотренных товаров
     * @constructor
     */
    this.InitRecentlyViewed = function (){

        if (!self._params.RecentlyViewed ) return ;

        // load Class for Local Storage
        self.getModul('Storage_class').then(function () {
            // Подключаем базу данных
            self.DB = new localStorageDB( self._DB_NAME , localStorage);
            // Если база данных новая
            if ( self.DB.isNew() ){
                // создать таблицу для хранения истории посмотренных товаров
                self.DB.createTable( self._DB_TBL, [ "product_id", "time" , 'count' ]);
                self.DB.commit();
            }

            // Элемент слайдера для просмотренных товаров
            var $RecentlyViewedSlider = $('[data-slick][data-slick-type="RecentlyViewed"]');
            var moduleId = $RecentlyViewedSlider.data('slick') ;

            // Получить все товары из истории просмотра
            var RecentlyViewedProduct = self.DB.queryAll ( self._DB_TBL , { sort: [["time", "ASC"]]  }) ;



            // Если в истории просмотров есть товары то отображаем блок модуля
            if ( RecentlyViewedProduct.length ) {
                $RecentlyViewedSlider.closest('.module-block').removeClass('hide')
            }


            /**
             * Настройка способа отображения модуля
             * если в настройках модуля не установленно  отложенная загрузка - Сразу инициализируем слайдер
             */
            // if ( self.checkDisplayMethod(moduleId ,  self.RecentlyViewed.InitSlider ) ) {
                // Инициализация слайдера на странице если есть
                // self.RecentlyViewed.InitSlider();
            // }


            // Добавить товар в историю просмотра
            if ( self._params.product_id ) self.RecentlyViewed.addHistory();
        });
    };
    /**
     * Настройка способа отображения модуля
     * @param moduleId
     * @param callBack
     * @returns {boolean}
     */
    this.checkDisplayMethod =function ( moduleId , callBack ){
        var res
        var display_method = self._params.sliders[moduleId].display_method ;
        switch (display_method){
            case 'visibility' :
                window.addEventListener('scroll' , function (){
                    self.onCheckPosition( moduleId , callBack )
                } ,passiveSupported ? { passive: true } : false  );
                res = false ;
                break ;
            default:
                res = true ;
        }

        return res ;

    }


    /**
     * Методы слайдера для просмотренных товаров
     * @type {{
     *      InitSlider: (function(): undefined),
     *      addHistory: Window.RecentlyViewed.addHistory
     *      }}
     */
    this.RecentlyViewed = {
        /**
         * Инициализация слайдера на странице если есть
         * @constructor
         */
        InitSlider : function (){

            // Получить все товары из истории просмотра
            var RecentlyViewedProduct = self.DB.queryAll ( self._DB_TBL , { sort: [["time", "ASC"]]  }) ;

            // Элемент слайдера для просмотренных товаров
            var $RecentlyViewedSlider = $('[data-slick][data-slick-type="RecentlyViewed"]');

            // ID модуля
            var sliderModuleId = $RecentlyViewedSlider.data('slick')

            // Если нет товаров для истории просмотра или нет модуля
            if ( !RecentlyViewedProduct.length || !$RecentlyViewedSlider[0] ) return ;

            // Добавить в параметры модуля данные из БД о товарах которые нужно загрузить
            self._params.sliders[sliderModuleId].localDbProduct = RecentlyViewedProduct ;
            // Общее количество товаров
            self._params.sliders[sliderModuleId].total_product = RecentlyViewedProduct.length ;

            // Загрузить слайды и добавить в слайдер
            self.loadProductInSlider( sliderModuleId , false );
            // Инициализация элементов управления для слайдера просмотренных товаров
            self.RecentlyViewed.InitRecentlyControls();

        },
        /**
         * Добавмть кнопки управления для товаров
         * @param thisSlider
         * @param html
         * @returns {*}
         */
        addControlToProduct : function (thisSlider , html){
            var productBtn = thisSlider.templatesElement.productMenuBtn ;
            var $blockProduct = $(html).clone().append(productBtn);
            return $blockProduct[0] ;
        },
        /**
         * Добавить товар в историю просмотра
         */
        addHistory : function (){
            var countViewed = 1 ;
            var WHERE = { product_id: self._params.product_id  } ;
            var product =  self.DB.queryAll ( self._DB_TBL ,  { query : WHERE } ) ;
            // Если товар есть в истории то добавляем +1 к просмотру
            if ( product.length )  countViewed = ++product[0].count ;
            // Значения для вставки или обновления
            var values = { product_id: self._params.product_id  , time    : Date.now() , count   : countViewed , } ;
            // Устанавливаем запрос
            var idRow  = self.DB.insertOrUpdate( self._DB_TBL, WHERE , values );
            // выполняем
            self.DB.commit();

        },
        /**
         * Инициализация элементов управления для слайдера просмотренных товаров
         * @constructor
         */
        InitRecentlyControls : function (){
            var $body = $('body')
            $body.on('click' , 'h3 [data-controls_mod="RecentlyViewed"]' , self.RecentlyViewed.ShowHeadMenu )
            $body.on('click' , '[data-controls_mod="RecentlyViewed"].recently-viewed-product-menu' , self.RecentlyViewed.ShowProductMenu )
        },
        /**
         * Отобразить меню для заголовка
         * @constructor
         */
        ShowHeadMenu : function (){
            var modId = $(this).data('mod_id');
            var $el = $(this) ;
            var templateType = 'headMenu' ;

            self.getModul('MiniMenu').then(function (MiniMenu){
                MiniMenu.createMenu( $el , self._params.sliders[modId].templatesElement[templateType] )
            },function (err){console.log(err)});
        } ,
        /**
         * Отобразить меню для товаров
         * @constructor
         */
        ShowProductMenu : function (){
            var modId = $(this).data('mod_id');
            var $el = $(this) ;
            var templateType = 'productMenu' ;
            self.getModul('MiniMenu').then(function (MiniMenu){
                MiniMenu.createMenu( $el , self._params.sliders[modId].templatesElement[templateType] )
            },function (err){console.log(err)});
        },
        /**
         * Удалить всю историю
         * @param event
         */
        cleanRecentlyViewed : function (event){
            // Удаляем таблицу Истории
            self.DB.truncate( self._DB_TBL ) ;
            self.DB.commit();
            $(event.target)
                .closest('.RecentlyViewed[data-mod_id]')
                .animate({height: "hide"}, 250, function () {
                    $(this).remove();
                });
        },
        removeRecentlyViewed : function ( event ) {
            var $slick =  $(event.target).closest('[data-slick]');
            var $slide =  $(event.target).closest('.slick-slide');
            var $allSliders = $slick.find('.slick-slide');

            console.log('mod_jshopping_random_m_Init:removeRecentlyViewed $slide' , $slide );
            console.log('mod_jshopping_random_m_Init:removeRecentlyViewed $allSliders' , $allSliders );
            console.log('mod_jshopping_random_m_Init:removeRecentlyViewed index' , $allSliders.index($slide) );

            // var slickIndex =  $slide.data('slick-index');
            var slickIndex =  $allSliders.index($slide);


            function getLastSlide(){
                return ($slide.slick("getSlick").slideCount - 1);
            }


            var product_id = $slide.find('[data-product_id]').data('product_id');
            // удалить из DB
            self.DB.deleteRows( self._DB_TBL, {product_id: product_id});
            self.DB.commit();
            // Удалить слайд
            $slick.slick('slickRemove', slickIndex);

            // Если слайдов нет удалить блок модуля
            if ( !$slick.find('[data-slick-index]').length ) self.RecentlyViewed.cleanRecentlyViewed( { target : $slick } );

        },

    }
    /**
     * Загрузить ресурсы (CSS|JS) Slick
     */
    this.loadAssetsSlick = function () {
        return new Promise(function(resolve, reject) {

            Promise.all([

                self.load.css(host + 'libraries/GNZ11/assets/js/modules/Slick/slick.css?v=1.7.2'),
                self.load.css(host + 'libraries/GNZ11/assets/js/modules/Slick/slick-theme.css?v=1.7.2'),
                self.load.js(host + 'libraries/GNZ11/assets/js/modules/Slick/slick.js?v=1.7.2'),
                self.load.svg(['#icon-slider-right' , '#icon-vertical-dots' ] ),

            ]).then(function (r) {
                resolve( r );
            }, function (err) {
                console.log( 'loadAssetsSlick' , err )
                reject(err)
            })
        })
    };
    /**
     * Поиск не инициализированных слайдеров и их запуск
     * @constructor
     */
    this.InitSlidersNew = function (){

        var $collectionSlider = $('[data-slick]:not(.slider-installed)');
        // Переберем все не инициализированные слайдеры
        $collectionSlider.each(function (i,a){
            var $slider = $(a);
            var moduleId = $slider.data('slick') ;
            /**
             * Проверить способ загрузки слайдера
             * Если не установлена отложенная загрузка от позиции на экране то запускаем
             */
            if ( self.checkDisplayMethod( moduleId , self.slidersLazyLoadingStart ) ){
                $slider.addClass('slider-installed');
                //Установить слайдер на найденном элементе
                self.InitSlick( $slider )
                // Добавить слушателей событий на найденном элементе
                setTimeout(function () {
                    self.addEvtListener($slider);
                }, 500);

            }
        })
    }
    /**
     * Инициализация слайдеров с отложенной загрузкой
     * @param moduleId
     */
    this.slidersLazyLoadingStart = function (moduleId) {
        var $module = $('[data-slick="' + moduleId + '"]');
        var moduleType = $module.data('slick-type');
        var $templateContainer = $module.find('template')

        // Если в слайдере есть тег <template />
        if ($templateContainer[0]){
            // Достать из <template />
            self.Optimizing.fromTemplate($templateContainer);
            // Обновление иконок список желаний и сравнения
            $('body').trigger('updateModules.CartAjaxCore'  );
        }

        $module.addClass('slider-installed');
        self.InitSlick($module);
        
        // Добавить слушателей событий на найденном элементе
        setTimeout(function () {
            self.addEvtListener($module);
        }, 500);

        // Init слайдера История просмотра
        if (moduleType === 'RecentlyViewed'){
            self.RecentlyViewed.InitSlider();
        }
    }


    /**
     * Индикатор обновления false - если первое
     * @type {boolean}
     * @private
     */
    this.__countUpdate = [];
    /**
     * Добавить слушателей событий на найденом элементе
     */
    this.addEvtListener = function ( $slider ) {


        /**
         * edge Событие срабатывает только тогда если для параметра
         * draggable установлено значение true и вы перетаскиваете последний слайд.
         */
        $slider.on('edge', function(event, slick, direction){
            // ID модуля
            var sliderModulId = slick.$slider.data('slick')
            console.log('edge was hit')
            self.loadProductInSlider(sliderModulId );
        });
        /**
         * Событие перед сменой слайда
         */
        $slider.on('beforeChange', function(event, slick, currentSlide, nextSlide){

            // ID модуля
            var sliderModulId = slick.$slider.data('slick')

            console.log( 'beforeChange' , slick )

            if (typeof self.__countUpdate[sliderModulId] === "undefined") self.__countUpdate[sliderModulId] = false;
            // За сколько слайдов до конца начинать дозагрузку
            var delta = self.__countUpdate[sliderModulId] ? 2 : 0 ;

            if ( slick.options.slidesToShow + nextSlide + delta === slick.slideCount   ) {
                // Параметры модуля
                var moduleParams = self._params.sliders[sliderModulId] ;

                /**
                 * Если количество загруженных слайдов меньше чем общее количество отобранных
                 * товаров в модуле - отправить на дозагрузку
                 */
                if( +moduleParams.loaded_product.length < +moduleParams.total_product ){
                    // Устанавливаем индикатор что первая дозагрузка слайдов произошла
                    self.__countUpdate[sliderModulId] = true;
                    // Загрузить слайды и добавить в слайдер
                    self.loadProductInSlider( sliderModulId );
                }
            }
        });
        /**
         * Событие после смены слайда
         * slick.slideCount; // Количество слайдов
         * slick.options.slidesToShow;// Отображаемое количество слайдов
         * currentSlide ; // Индекс текущего слайд
         */
        $slider.on('afterChange', function(event, slick, currentSlide ){});
    };
    /**
     * Проверка видимости элемента
     * @param moduleId
     * @param callBack
     */
    this.onCheckPosition = function ( moduleId , callBack ){

        var $checkPositionElement = $('[data-slick="'+moduleId+'"]')
            .closest('.module-block').find('.check-position--visibility');

        // Если блок слежение за позицией на экране есть
        if ( $checkPositionElement[0] ){
            // Если блок в зоне видимости
            if (self.Optimizing.checkPosition($checkPositionElement)){
                $checkPositionElement.removeClass('check-position--visibility')
                if ( typeof callBack === 'function' ) callBack( moduleId );
            }
        }else {
            // Если блоков слежения больше нет
            // Удалить слушателя
            var $moduleBlock = $('.module-block .check-position--visibility');
            if ( !$moduleBlock[0] ){
                return null ;
            }

        }
    };
    /**
     * Установка слайдера
     * @param $slickBlock
     * @constructor
     */
    this.InitSlick = function ( $slickBlock ){

        var idModul = $slickBlock.data('slick');
        var paramsSlider = self._params.sliders[idModul];




        $('body').trigger('updateModules.CartAjaxCore'   );

        $slickBlock.on('init', function(event, slick, direction){
             console.log('mod_jshopping_random_m_Init:' , $('.product-button-wishlist') ); 
        });
        $slickBlock.slick({
            /**
             * Количество слайдов для показа
             * @type        int
             * @default     1
             */
            slidesToShow: +paramsSlider.count_sliders ,
            /**
             * Скорость анимации сменыслайдов/затухания
             * @type int (мс)
             * @default  300
             */
            speed: 200,
            /**
             * Перелистование любое количество слайдов в независимости от slidesToScroll
             */
            swipeToSlide: true ,
            /**
             * Для связанных слайдеров
             */
            asNavFor : null  ,
            /**
             * Бесконечный цикл прокрутки слайдов
             */
            infinite: false,
            /**
             * Чтобы продвинуть слайды, пользователь должен провести пальцем
             * на (1 / touchThreshold) * ширину слайдера
             * @type int
             * @default 5
             */
            touchThreshold : 10 ,
            /**
             * Расположение стрелок навигации
             * @type string
             * @default $(element)
             */
            appendArrows : $slickBlock ,
            /**
             * Шаблон кнопки Prev
             */
            prevArrow: '' +
                '<button type="button" class="simple-slider__control simple-slider__control_type_prev">' +
                '<svg aria-hidden="true" height="40" width="16">' +
                '<use xlink:href="#icon-slider-right" xmlns:xlink="http://www.w3.org/1999/xlink"></use>' +
                '</svg>' +
                '</button>',
            /**
             * Шаблон кнопки Next
             */
            nextArrow: '' +
                '<button type="button" class="simple-slider__control simple-slider__control_type_next">' +
                '<svg aria-hidden="true" height="40" width="16">' +
                '<use xlink:href="#icon-slider-right" xmlns:xlink="http://www.w3.org/1999/xlink"></use>' +
                '</svg>' +
                '</button>'
        });

    };
    /**
     * Загрузить слайды и добавить в слайдер
     * @param sliderModuleId    INT             ID - модуля для которого дозагружаются слайды
     * @param sliderGoNext      BOOL (true)     Если True - переход на следу.щий слайд
     */
    this.loadProductInSlider = function ( sliderModuleId ,  sliderGoNext ){
        if( typeof sliderGoNext === 'undefined') sliderGoNext = true ;

        var Data = this._params ;

        Data.module_id =  sliderModuleId ;
        Data.method =  'getSliders' ;

        self.AjaxPost(Data).then(function (r){
            var $_sliderActive = $('[data-slick="'+sliderModuleId+'"]')
            var thisSlider = self._params.sliders[sliderModuleId]
            // Устанавливаем загружнные слайды
            $.each( r.data.html , function (i,html){
                if (thisSlider.module_type === 'RecentlyViewed' )
                    html = self.RecentlyViewed.addControlToProduct(thisSlider ,html)

                $_sliderActive.slick('slickAdd',html );
            });

            // Обнавляем информацию о состоянии слайдеров
            self._params.sliders = r.data.sliders


            // Если количество загруженных слайдов равно общему количеству товаров ( total_product )


            console.log('mod_jshopping_random_m_Init:thisSlider.module_type' , thisSlider.module_type );
             


            setTimeout(function (){
                // Обновить иконки - список желаний , сравнения
                $('body').trigger('updateModules.CartAjaxCore'   );
            },100)
            // При перетаскивании последнего слайдера после загрузки
            // Переходим на следующий слайд
            if ( sliderGoNext ) $_sliderActive.slick('slickNext') ;

        },function (err){console.log(err)});
    }
    /**
     * Отправить запрос
     * @param Data - отправляемые данные
     * Должен содержать Data.task = 'taskName';
     * @returns {Promise}
     * @constructor
     */
    this.AjaxPost = function (Data) {
        var data = $.extend(true, this.AjaxDefaultData, Data);
        return new Promise(function (resolve, reject) {
            self.getModul("Ajax").then(function (Ajax) {
                // Не обрабатывать сообщения
                Ajax.ReturnRespond = true;
                // Отправить запрос
                Ajax.send(data, self._params.__name).then(function (r) {
                    resolve(r);
                }, function (err) {
                    console.error(err);
                    reject(err);
                })
            });
        });
    };
    /**
     * Параметры Ajax Default
     */
    this.setAjaxDefaultData = function () {
        this.AjaxDefaultData.group = this._params.__type;
        this.AjaxDefaultData.plugin = this._params.__name;
        this.AjaxDefaultData.module = this._params.__module ;
        this._params.__name = this._params.__name || this._params.__module ;
    }
    this.Init();
};



window.mod_jshopping_random_m_Init.prototype = new GNZ11();
window.Mod_jshopping_slider_module = new window.mod_jshopping_random_m_Init();













