<?php
    /***********************************************************************************************************************
     *----------------------------------------------------------------------------------------------------------------------
     *
     * @author     Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
     * @date       27.11.2020 20:48
     * @copyright  Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
     * @license    GNU General Public License version 2 or later;
     **********************************************************************************************************************/

    use Joomla\Registry\Registry;

    defined('_JEXEC') or die; // No direct access to this file

    /**
     * @var array                        $last_prod
     * @var array                        $Options - данные текущего модуля
     * @var stdClass                     $module
     * @var Registry                     $params
     * @var string                       $module_type
     * @var Joomla\CMS\Layout\FileLayout $layoutScript
     */


    $app = \Joomla\CMS\Factory::getApplication();
    $__v = '?v=' . $params->get('__v' , '1.7.2');
    $template = $app->getTemplate();
    $path = JPATH_THEMES . '/' . $template . '/html/com_jshopping/val_shopping/list_products/';

    $display_method = $params->get('display_method' , 'default');

    if( $module->id == 333 )
    {

//        echo'<pre>';print_r( $display_method );echo'</pre>'.__FILE__.' '.__LINE__;
//        die(__FILE__ .' '. __LINE__ );

    }#END IF


    ?>



<div class="jshop_list_product">
    <div class="slick-similar-products" data-slick="<?= $module->id ?>" data-slick-type="<?= $module_type ?>">
        <?= $display_method != 'visibility' ?: '<template>'   ?>
        <?php
            foreach ($last_prod as $product)
            {
                $Options['sliders'][$module->id]['loaded_product'][] = $product->product_id; ?>
                <div class="jswidth20 block_product">
                    <?php
                        # Подключение карточи товара из шаблона JoomShoping
                        # /GJTemplate.elektro/html/com_jshopping/val_shopping/list_products/product.php
                        include($path . 'product.php'); ?>
                </div>
                <?php
            }#END FOREACH
        ?>
        <?= $display_method != 'visibility' ?: '</template>'   ?>
    </div>

</div>







