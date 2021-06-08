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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}
require_once _PS_MODULE_DIR_ . 'eg_printcart/classes/Exportdevis.php';

class Eg_printcart extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'eg_printcart';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Evolutive-Group';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('eg_printcart');
        $this->description = $this->l('print cart content as pdf file');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }
     /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {

        Configuration::updateValue('EG_PRINTCART_LIVE_MODE', false);

        return parent::install() &&
            $this->registerHook('CustomPrint') &&
            $this->registerHook('actionPdfDevis') &&
            $this->registerHook('actionDeleteDevis');
    }

    public function uninstall()
    {	
        Configuration::deleteByName('EG_PRINTCART_LIVE_MODE');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitEg_printcartModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitEg_printcartModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        //$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                    'tinymce' => true,
                ),
                'input' => array(

                    array(
                        'type' => 'textarea',
                        'label' => $this->l('ACTIVITY'),
                        'desc' => $this->l('Content wich will appear in front'),
                        'lang' => true,
                        'name' => 'PRINTCART_ACTIVITY',
                        'cols' => 40,
                        'rows' => 10,
                        'class' => 'rte',
                        'autoload_rte' => true,
                    ),  
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('PHONE'),
                        'desc' => $this->l('Content wich will appear in front'),
                        'lang' => true,
                        'name' => 'PRINTCART_PHONE',
                        'cols' => 40,
                        'rows' => 10,
                        'class' => 'rte',
                        'autoload_rte' => true,
                    ),      
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('FAX'),
                        'desc' => $this->l('Content wich will appear in front'),
                        'lang' => true,
                        'name' => 'PRINTCART_FAX',
                        'cols' => 40,
                        'rows' => 10,
                        'class' => 'rte',
                        'autoload_rte' => true,
                    ), 
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('SIEGE'),
                        'desc' => $this->l('Content wich will appear in front'),
                        'lang' => true,
                        'name' => 'PRINTCART_SIEGE',
                        'cols' => 40,
                        'rows' => 10,
                        'class' => 'rte',
                        'autoload_rte' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('CAPTIAL'),
                        'desc' => $this->l('Content wich will appear in front'),
                        'lang' => true,
                        'name' => 'PRINTCART_CAPTIAL',
                        'cols' => 40,
                        'rows' => 10,
                        'class' => 'rte',
                        'autoload_rte' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('SIREL'),
                        'desc' => $this->l('Content wich will appear in front'),
                        'lang' => true,
                        'name' => 'PRINTCART_SIREL',
                        'cols' => 40,
                        'rows' => 10,
                        'class' => 'rte',
                        'autoload_rte' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }
       /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        $languages = Language::getLanguages(false);
        $fields = array(
            'PRINTCART_ACTIVITY',
            'PRINTCART_PHONE',
            'PRINTCART_FAX',
            'PRINTCART_SIEGE',
            'PRINTCART_CAPTIAL',
            'PRINTCART_SIREL'
        );

        foreach ($fields as $field) {
            foreach ($languages as $lang) {
            $fields[$field][$lang['id_lang']] = Tools::getValue($field . $lang['id_lang'], Configuration::get($field, $lang['id_lang']));
        }
    }
        return $fields;
    }
    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
        $languages = Language::getLanguages(false);
        $fields = array(
            'PRINTCART_ACTIVITY',
            'PRINTCART_PHONE',
            'PRINTCART_FAX',
            'PRINTCART_SIEGE',
            'PRINTCART_CAPTIAL',
            'PRINTCART_SIREL'
        );
        foreach ($fields as $field) {
            foreach ($languages as $lang) {
                $lan = $lang['id_lang'];
                $fields[$lan] = Tools::getValue($field.'_' . $lan);
            }

        Configuration::updateValue($field, $fields);
        }
    }
    public function hookCustomPrint($params)
    {
        /* Place your code here. */
       
       return  $this->context->smarty->fetch($this->local_path.'views/templates/hook/print.tpl');
    }
    public function hookActionPdfDevis(array $params)
    {  
        $content = Exportdevis::export();
        $pdf = new HTML2PDF("p", "A4", "fr", true, 'UTF-8', array(0, 0, 0, 0));
        $pdf->pdf->SetTitle($this->l('Quotation'));
        $pdf->pdf->SetSubject($this->l('Creation of a quote'));
        $pdf->writeHTML($content);
        $base_directory = dirname(__FILE__).'/tmp_devis';
        
        if(file_exists($base_directory.'/panier_'.$this->context->cart->id.date("ymd").'.pdf')){
            unlink($base_directory.'/panier_'.$this->context->cart->id.date("ymd").'.pdf');
            $pdf->Output($base_directory.'/panier_'.$this->context->cart->id.date("ymd").'.pdf', 'F');
        }else{
            $pdf->Output($base_directory.'/panier_'.$this->context->cart->id.date("ymd").'.pdf', 'F');
        }
          
    }

}