<?php
/**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
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
 * @copyright 2007-2019 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

include "lib/html2pdf.php";
class eg_printcartExportModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $content = Exportdevis::export();
        $pdf = new HTML2PDF("p", "A4", "fr", true, 'UTF-8', array(0, 0, 0, 0));
        ;
        $pdf->pdf->SetTitle($this->trans('Quotation', [], 'Shop.Theme.Global'));
        $pdf->pdf->SetSubject($this->trans('Creation of a quotation.', [], 'Shop.Theme.Global'));
        $pdf->writeHTML($content);
        //$pdf->Output('Devis.pdf','D');
        $pdf->Output(
        $this->trans('Quotation', [], 'Shop.Theme.Global').date("ymd")
        .'.pdf'
        ,'D');
        die;
    }
}
	