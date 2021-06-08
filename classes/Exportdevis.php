<?php
/**
 * 2007-2019 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
require_once _PS_MODULE_DIR_ . 'eg_printcart/controllers/front/lib/html2pdf.php';

class Exportdevis
{
    public function export()
    {
        $context = Context::getContext();
        /**
         * company
         */
        $shop_img = Configuration::get('PS_LOGO');
        $date_devis = date("d-m-y");
        $currency = new CurrencyCore(Context::getContext()->cookie->id_currency);
        $my_currency_iso_code = $currency->iso_code;
       

        Context::getContext()->smarty->assign('currency',$my_currency_iso_code);
        Context::getContext()->smarty->assign('date_devis',$date_devis);
        $shop_activity = Configuration::get('PRINTCART_ACTIVITY', Context::getContext()->language->id);
        $shop_tel = Configuration::get('PRINTCART_PHONE', Context::getContext()->language->id);
        $shop_fax = $shop_tel = Configuration::get('PRINTCART_FAX', Context::getContext()->language->id);
        $shop = array(
            "shop_img" => $shop_img,
            "desc" => $shop_activity,
            "Tel" => $shop_tel,
            "Fax" => $shop_fax
        );

        /**
         * partie du client
         */
        $customer_email = Context::getContext()->customer->email;
        $customer_lastname = Context::getContext()->customer->lastname; //nom
        $customer_firstname = Context::getContext()->customer->firstname;

        $customer = array(
            "firstname" => $customer_firstname,
            "lastname" => $customer_lastname,
            "mail" => $customer_email
        );

        /**
         * partie adresse client
         */

        $adr = new Customer(Context::getContext()->customer->id);
        
        $adr_details = $adr->getAddresses((int)Context::getContext()->language->id);
        /**
         * partie Footer
         */
        $logo_path = _PS_IMG_DIR_ .'maisonfer-logo-1567519421.jpg';
        $footer = array(
            "footer_siege_social" => Configuration::get('PRINTCART_SIEGE', Context::getContext()->language->id),
            "footer_capital" => Configuration::get('PRINTCART_CAPTIAL', Context::getContext()->language->id),
            "PRINTCART_SIREL" => Configuration::get('PRINTCART_SIREL', Context::getContext()->language->id),
            "logo_path" => $logo_path
        );

        /**
         * partie panier
         */

        $current_cart = new Cart(Context::getContext()->cart->id);

        $cart_details = $current_cart->getSummaryDetails(1, false);
        /**
         * produits dans le panier
         */

        $products = Context::getContext()->cart->getProducts();
        
        $total_product = 0;

        foreach ($products as $product) {
           
            $total_product += $product['cart_quantity'];
        }


        ob_start();

        $content = ob_get_clean();

        $data = array(
            'shop' => $shop,
            'customer' => $customer,
            'adress' => $adr_details,
            'footer' => $footer,
            'cart_details' => $cart_details,
            'products' => $products,
            'total' => $total_product
        );
        $context->smarty->assign($data);
        $tpls = array(
            'shop_tab' => $context->smarty->fetch('module:eg_printcart/views/templates/pdf_tpl/shop.tpl'),

        );
        $context->smarty->assign($tpls);

        $content = $context->smarty->fetch('module:eg_printcart/views/templates/pdf_tpl/shop.tpl');
        return $content;
    }

}
