<?php
    /***********************************************************************************************************************
     * ╔═══╗ ╔══╗ ╔═══╗ ╔════╗ ╔═══╗ ╔══╗  ╔╗╔╗╔╗ ╔═══╗ ╔══╗   ╔══╗  ╔═══╗ ╔╗╔╗ ╔═══╗ ╔╗   ╔══╗ ╔═══╗ ╔╗  ╔╗ ╔═══╗ ╔╗ ╔╗ ╔════╗
     * ║╔══╝ ║╔╗║ ║╔═╗║ ╚═╗╔═╝ ║╔══╝ ║╔═╝  ║║║║║║ ║╔══╝ ║╔╗║   ║╔╗╚╗ ║╔══╝ ║║║║ ║╔══╝ ║║   ║╔╗║ ║╔═╗║ ║║  ║║ ║╔══╝ ║╚═╝║ ╚═╗╔═╝
     * ║║╔═╗ ║╚╝║ ║╚═╝║   ║║   ║╚══╗ ║╚═╗  ║║║║║║ ║╚══╗ ║╚╝╚╗  ║║╚╗║ ║╚══╗ ║║║║ ║╚══╗ ║║   ║║║║ ║╚═╝║ ║╚╗╔╝║ ║╚══╗ ║╔╗ ║   ║║
     * ║║╚╗║ ║╔╗║ ║╔╗╔╝   ║║   ║╔══╝ ╚═╗║  ║║║║║║ ║╔══╝ ║╔═╗║  ║║─║║ ║╔══╝ ║╚╝║ ║╔══╝ ║║   ║║║║ ║╔══╝ ║╔╗╔╗║ ║╔══╝ ║║╚╗║   ║║
     * ║╚═╝║ ║║║║ ║║║║    ║║   ║╚══╗ ╔═╝║  ║╚╝╚╝║ ║╚══╗ ║╚═╝║  ║╚═╝║ ║╚══╗ ╚╗╔╝ ║╚══╗ ║╚═╗ ║╚╝║ ║║    ║║╚╝║║ ║╚══╗ ║║ ║║   ║║
     * ╚═══╝ ╚╝╚╝ ╚╝╚╝    ╚╝   ╚═══╝ ╚══╝  ╚═╝╚═╝ ╚═══╝ ╚═══╝  ╚═══╝ ╚═══╝  ╚╝  ╚═══╝ ╚══╝ ╚══╝ ╚╝    ╚╝  ╚╝ ╚═══╝ ╚╝ ╚╝   ╚╝
     *----------------------------------------------------------------------------------------------------------------------
     *
     * @author     Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
     * @date       24.11.2020 03:18
     * @copyright  Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
     * @license    GNU General Public License version 2 or later;
     **********************************************************************************************************************/
    defined('_JEXEC') or die; // No direct access to this file

    /* @var $displayData array */
    /* @var $self object */


    extract($displayData);

    ?>
    <ul _ngcontent-c88="" class="cart-actions__list" id="shoppingCartActions">
        <li _ngcontent-c88="" class="cart-actions__item">
            <button _ngcontent-c88="" data-action="removeListRecentlyViewed"
                    class="button button--medium button--with-icon button--link cart-actions__button"
                    type="button" onclick="window.Mod_jshopping_slider_module.RecentlyViewed.removeRecentlyViewed(event)">
                <svg _ngcontent-c88="" aria-hidden="true" height="24" pointer-events="none" width="24">
                    <use _ngcontent-c88="" pointer-events="none" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-trash"></use>
                </svg>
                Удалить из истории
            </button>
        </li><!---->
        <li _ngcontent-c88="" data-action="cancel" class="cart-actions__item">
            <button _ngcontent-c88="" class="button button--medium button--with-icon button--link cart-actions__button" type="button">
                <svg _ngcontent-c88="" aria-hidden="true" height="24" pointer-events="none" width="24">
                    <use _ngcontent-c88="" pointer-events="none" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-remove"></use>
                </svg>
                Отменить
            </button>
        </li>
    </ul>


<?php

