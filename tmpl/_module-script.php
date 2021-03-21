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
     * @date       03.12.2020 14:28
     * @copyright  Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
     * @license    GNU General Public License version 2 or later;
     ******************************************************************************************************************/
    defined('_JEXEC') or die; // No direct access to this file

    /**
     * @var string $__v
     * @var array $displayData
     */
    extract( $displayData );






?>
<script>

    (function (){
        var assetsSvgArr = ['#icon-okay','#icon-compare','#icon-compare-filled','#icon-favorites','#icon-slider-right'] ;
        // Если GNZ11 - не загружена 111
        if (typeof GNZ11 === "undefined"){
            // Дожидаемся события GNZ11Loaded
            document.addEventListener('GNZ11Loaded', function (e) {
                // Загружаем скрипт управления слайдерами
                loadAssets()
            }, false);
        }else if( typeof GNZ11 === "function" && typeof window.mod_jshopping_random_m_Init === "undefined" ){
            // Загружаем скрипт управления слайдерами
            loadAssets();
        }else {
            window.Mod_jshopping_slider_module.InitSlidersNew();
            wgnz11.load.svg( assetsSvgArr );
        }
        // загрузить скрипт управления слайдерами
        function loadAssets(){

            const host = Joomla.getOptions('GNZ11').Ajax.siteUrl;
            wgnz11.load.js( host+ 'modules/mod_jshopping_random_m/assets/js/mod_jshopping_random_m_Init.js?=<?= $__v ?>')
                .then(function (r){
                    wgnz11.load.svg( assetsSvgArr );
            },function (err){console.log(err)});
        }
    })()
</script>

