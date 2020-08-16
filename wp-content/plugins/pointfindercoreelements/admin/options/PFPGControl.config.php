<?php
/**********************************************************************************************************************************
*
* Size Control Settings
* 
* Author: Webbu
*
***********************************************************************************************************************************/

if (!class_exists("Redux_Framework_PF_PGcontrol_Config")) {
	

    class Redux_Framework_PF_PGcontrol_Config{

        public $args = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {
            $this->initSettings();
        }

        public function initSettings() {
            $this->setArguments(); 
            $this->setSections();
            if (!isset($this->args['opt_name'])) { return;}
            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }


        public function setSections() {

            /**
            *Start : PayFast
            **/
                 $this->sections[] = array(
                    'id' => 'payf_gateway',
                    'title' => esc_html__('PayFast API', 'pointfindercoreelements'),
                    'icon' => 'el-icon-cogs',
                    'fields' => array(
                        array(
                            'id' => 'payf_status',
                            'type' => 'button_set',
                            'title' => esc_html__('Status', 'pointfindercoreelements') ,
                            'options' => array(
                                '1' => esc_html__('Enable', 'pointfindercoreelements') ,
                                '0' => esc_html__('Disable', 'pointfindercoreelements')
                            ) , 
                            'default' => 0,
                        ) ,
                        array(
                            'id' => 'payf_mode',
                            'type' => 'button_set',
                            'title' => esc_html__('Mode', 'pointfindercoreelements') ,
                            'options' => array(
                                '1' => esc_html__('Live Mode', 'pointfindercoreelements') ,
                                '0' => esc_html__('Test Mode', 'pointfindercoreelements')
                            ) , 
                            'default' => 0,
                            'required' => array('payf_status','=',1)
                        ) ,

                        array(
                            'id' => 'payf_merid',
                            'type' => 'text',
                            'title' => esc_html__('Merchant ID', 'pointfindercoreelements'),
                            'default' => 'PINTFNDR',
                            'required' => array('payf_status','=',1)
                        ) ,
                        array(
                            'id' => 'payf_merkey',
                            'type' => 'text',
                            'title' => esc_html__('Merchant Key', 'pointfindercoreelements'),
                            'required' => array('payf_status','=',1)
                        ),
                        array(
                            'id' => 'payf_passph',
                            'type' => 'text',
                            'title' => esc_html__('Passphrase', 'pointfindercoreelements'),
                            'required' => array('payf_status','=',1)
                        ),
                    )
                );
            /**
            *End : PayFast
            **/

            /**
            *Start : 2Checkout
            **/
                 $this->sections[] = array(
                    'id' => '2cho_gateway',
                    'title' => esc_html__('2Checkout API', 'pointfindercoreelements'),
                    'icon' => 'el-icon-cogs',
                    'fields' => array(
                        array(
                            'id' => '2cho_status',
                            'type' => 'button_set',
                            'title' => esc_html__('Status', 'pointfindercoreelements') ,
                            'options' => array(
                                '1' => esc_html__('Enable', 'pointfindercoreelements') ,
                                '0' => esc_html__('Disable', 'pointfindercoreelements')
                            ) , 
                            'default' => 0,
                        ) ,
                        array(
                            'id' => '2cho_mode',
                            'type' => 'button_set',
                            'title' => esc_html__('Mode', 'pointfindercoreelements') ,
                            'options' => array(
                                '1' => esc_html__('Live Mode', 'pointfindercoreelements') ,
                                '0' => esc_html__('Test Mode', 'pointfindercoreelements')
                            ) , 
                            'default' => 0,
                            'required' => array('2cho_status','=',1)
                        ) ,

                        array(
                            'id' => '2cho_ordpre',
                            'type' => 'text',
                            'title' => esc_html__('Order ID Prefix', 'pointfindercoreelements'),
                            'default' => 'PINTFNDR',
                            'required' => array('2cho_status','=',1)
                        ) ,
                        array(
                            'id' => '2cho_key3',
                            'type' => 'text',
                            'title' => esc_html__('Seller ID', 'pointfindercoreelements'),
                            'required' => array('2cho_status','=',1)
                        ),
                        array(
                            'id' => '2cho_key4',
                            'type' => 'text',
                            'title' => esc_html__('Secret Word', 'pointfindercoreelements'),
                            'required' => array('2cho_status','=',1)
                        ),
                        array(
                            'id' => '2cho_ccode',
                            'type' => 'text',
                            'title' => esc_html__('Currency Code', 'pointfindercoreelements'),
                            'desc' => esc_html__('AFN, ALL, DZD, ARS, AUD, AZN, BSD, BDT, BBD, BZD, BMD, BOB, BWP, BRL, GBP, BND, BGN, CAD, CLP, CNY, COP, CRC, HRK, CZK, DKK, DOP, XCD, EGP, EUR, FJD, GTQ, HKD, HNL, HUF, INR, IDR, ILS, JMD, JPY, KZT, KES, LAK, MMK, LBP, LRD, MOP, MYR, MVR, MRO, MUR, MXN, MAD, NPR, TWD, NZD, NIO, NOK, PKR, PGK, PEN, PHP, PLN, QAR, RON, RUB, WST, SAR, SCR, SGD, SBD, ZAR, KRW, LKR, SEK, CHF, SYP, THB, TOP, TTD, TRY, UAH, AED, USD, VUV, VND, XOF, YER. Use to specify the currency for the sale.', 'pointfindercoreelements'),
                            'default' => 'USD',
                            'required' => array('2cho_status','=',1)
                        ),
                        array(
                            'id' => '2cho_lang',
                            'type' => 'text',
                            'title' => esc_html__('Language', 'pointfindercoreelements'),
                            'desc' => esc_html__('Chinese – zh, Danish – da, Dutch – nl, French – fr, German – gr, Greek – el, Italian – it, Japanese – jp, Norwegian – no, Portuguese – pt, Slovenian – sl, Spanish - es_ib or es_la', 'pointfindercoreelements'),
                            'default' => 'en',
                            'required' => array('2cho_status','=',1)
                        )
                    )
                );
            /**
            *End : 2Checkout
            **/


            /**
            *Start : iDeal
            **/
                $this->sections[] = array(
                    'id' => 'idealapi',
                    'title' => esc_html__('iDeal API', 'pointfindercoreelements'),
                    'icon' => 'el-icon-cogs',
                    'fields' => array(
                        array(
                            'id' => 'stp_hlp3',
                            'type' => 'info',
                            'notice' => true,
                            'style' => 'info',
                            'desc' => esc_html__('iDeal only accepts EURO currency. If you planning to use this gateway please change other gateway currency Sign ($) before enable this gateway. (From PF Settings > Options Panel > Frontend Upload System > Payment Settings)', 'pointfindercoreelements')
                        ),
                        array(
                            'id' => 'ideal_status',
                            'type' => 'button_set',
                            'title' => esc_html__('Status', 'pointfindercoreelements') ,
                            'options' => array(
                                '1' => esc_html__('Enable', 'pointfindercoreelements') ,
                                '0' => esc_html__('Disable', 'pointfindercoreelements')
                            ) , 
                            'default' => 0,
                        ) ,
                        array(
                            'id' => 'ideal_mode',
                            'type' => 'button_set',
                            'title' => esc_html__('Mode', 'pointfindercoreelements') ,
                            'options' => array(
                                '1' => esc_html__('Live Mode', 'pointfindercoreelements') ,
                                '0' => esc_html__('Test Mode', 'pointfindercoreelements')
                            ) , 
                            'default' => 0,
                            'required' => array('ideal_status','=',1)
                        ) ,

                        array(
                            'id' => 'ideal_id',
                            'type' => 'text',
                            'title' => esc_html__('Mollie API Key', 'pointfindercoreelements'),
                            'required' => array('ideal_status','=',1)
                        ) ,
                    )
                );
            /**
            *End : iDeal
            **/


            /**
            *Start : Payumoney
            **/
                $this->sections[] = array(
                    'id' => 'payumoneyapi',
                    'title' => esc_html__('PayU Money API', 'pointfindercoreelements'),
                    'icon' => 'el-icon-cogs',
                    'fields' => array(
                        array(
                            'id' => 'stp_hlp2',
                            'type' => 'info',
                            'notice' => true,
                            'style' => 'info',
                            'desc' => esc_html__('PayU Money only accepts INR currency. If you planning to use this gateway please change other gateway currency Sign ($) before enable this gateway. (From PF Settings > Options Panel > Frontend Upload System > Payment Settings)', 'pointfindercoreelements')
                        ),
                        array(
                            'id' => 'payu_status',
                            'type' => 'button_set',
                            'title' => esc_html__('Status', 'pointfindercoreelements') ,
                            'options' => array(
                                '1' => esc_html__('Enable', 'pointfindercoreelements') ,
                                '0' => esc_html__('Disable', 'pointfindercoreelements')
                            ) , 
                            'default' => 0,
                        ) ,
                        array(
                            'id' => 'payu_mode',
                            'type' => 'button_set',
                            'title' => esc_html__('Mode', 'pointfindercoreelements') ,
                            'options' => array(
                                '1' => esc_html__('Live Mode', 'pointfindercoreelements') ,
                                '0' => esc_html__('Test Mode', 'pointfindercoreelements')
                            ) , 
                            'default' => 0,
                            'required' => array('payu_status','=',1)
                        ) ,
                        array(
                            'id' => 'payu_provider',
                            'type' => 'button_set',
                            'title' => esc_html__('Provider', 'pointfindercoreelements') ,
                            'options' => array(
                                '1' => esc_html__('PayUmoney', 'pointfindercoreelements') ,
                                '0' => esc_html__('PayUbiz', 'pointfindercoreelements')
                            ) , 
                            'default' => 1,
                            'required' => array('payu_status','=',1)
                        ) ,
                        array(
                            'id' => 'payu_key',
                            'type' => 'text',
                            'title' => esc_html__('Merchant Key', 'pointfindercoreelements'),
                            'required' => array('payu_status','=',1)
                        ) ,

                        array(
                            'id' => 'payu_salt',
                            'type' => 'text',
                            'title' => esc_html__('Merchant Salt', 'pointfindercoreelements'),
                            'required' => array('payu_status','=',1)
                        ) ,
                    )
                );
            /**
            *End : Payumoney
            **/

            /**
            *Start : Pagseguro
            **/
                $this->sections[] = array(
                    'id' => 'pagsapi',
                    'title' => esc_html__('PagSeguro API', 'pointfindercoreelements'),
                    'icon' => 'el-icon-cogs',
                    'fields' => array(
                        array(
                            'id' => 'stp_hlp1',
                            'type' => 'info',
                            'notice' => true,
                            'style' => 'info',
                            'desc' => esc_html__('PagSeguro only accepts BRL currency. If you planning to use this gateway please change other gateway currency Sign ($) before enable this gateway. (From PF Settings > Options Panel > Frontend Upload System > Payment Settings)', 'pointfindercoreelements')
                        ),
                        array(
                            'id' => 'pags_status',
                            'type' => 'button_set',
                            'title' => esc_html__('Status', 'pointfindercoreelements') ,
                            'options' => array(
                                '1' => esc_html__('Enable', 'pointfindercoreelements') ,
                                '0' => esc_html__('Disable', 'pointfindercoreelements')
                            ) , 
                            'default' => 0,
                        ) ,
                        array(
                            'id' => 'pags_mode',
                            'type' => 'button_set',
                            'title' => esc_html__('Mode', 'pointfindercoreelements') ,
                            'options' => array(
                                '1' => esc_html__('Live Mode', 'pointfindercoreelements') ,
                                '0' => esc_html__('Test Mode', 'pointfindercoreelements')
                            ) , 
                            'default' => 0,
                            'required' => array('pags_status','=',1)
                        ) ,

                        array(
                            'id' => 'pags_email',
                            'type' => 'text',
                            'title' => esc_html__('PagSeguro Email', 'pointfindercoreelements'),
                            'required' => array('pags_status','=',1)
                        ) ,

                        array(
                            'id' => 'pags_token',
                            'type' => 'text',
                            'title' => esc_html__('PagSeguro Token', 'pointfindercoreelements'),
                            'required' => array('pags_status','=',1)
                        )
                    )
                );
            /**
            *End : Pagseguro
            **/


            /**
            *Start : Robokassa API
            **/
                $this->sections[] = array(
                    'id' => 'robokassaapi',
                    'title' => esc_html__('Robokassa API', 'pointfindercoreelements'),
                    'icon' => 'el-icon-cogs',
                    'fields' => array(
                        array(
                            'id' => 'robo_status',
                            'type' => 'button_set',
                            'title' => esc_html__('Status', 'pointfindercoreelements') ,
                            'options' => array(
                                '1' => esc_html__('Enable', 'pointfindercoreelements') ,
                                '0' => esc_html__('Disable', 'pointfindercoreelements')
                            ) , 
                            'default' => 0,
                        ) ,
                        array(
                            'id' => 'robo_mode',
                            'type' => 'button_set',
                            'title' => esc_html__('Mode', 'pointfindercoreelements') ,
                            'options' => array(
                                '1' => esc_html__('Live Mode', 'pointfindercoreelements') ,
                                '0' => esc_html__('Test Mode', 'pointfindercoreelements')
                            ) , 
                            'default' => 0,
                            'required' => array('robo_status','=',1)
                        ) ,
                        array(
                            'id' => 'robo_login',
                            'type' => 'text',
                            'title' => esc_html__('Robokassa Shop ID', 'pointfindercoreelements'),
                            'required' => array('robo_status','=',1)
                        ) ,
                        array(
                            'id' => 'robo_pass1',
                            'type' => 'text',
                            'title' => esc_html__('Robokassa Password #1', 'pointfindercoreelements'),
                            'required' => array('robo_status','=',1)
                        ) ,

                        array(
                            'id' => 'robo_pass2',
                            'type' => 'text',
                            'title' => esc_html__('Robokassa Password #2', 'pointfindercoreelements'),
                            'required' => array('robo_status','=',1)
                        ),
                        array(
                            'id' => 'robo_currency',
                            'type' => 'text',
                            'title' => esc_html__('Robokassa Currency', 'pointfindercoreelements'),
                            'desc' => esc_html__('Please leave blank for Ruble otherwise please enter currency code like USD or EUR', 'pointfindercoreelements') ,
                            'required' => array('robo_status','=',1)
                        ) ,
                        array(
                            'id' => 'robo_lang',
                            'type' => 'button_set',
                            'title' => esc_html__('Language', 'pointfindercoreelements') ,
                            'options' => array(
                                'en' => esc_html__('EN', 'pointfindercoreelements') ,
                                'ru' => esc_html__('RU', 'pointfindercoreelements')
                            ) , 
                            'default' => 'ru',
                            'required' => array('robo_status','=',1)
                        ) ,
                    )
                );
            /**
            *End : Robokassa API
            **/


            /**
            *Start : Iyzico API
            **/
                $this->sections[] = array(
                    'id' => 'iyzico_gateway',
                    'title' => esc_html__('Iyzico API', 'pointfindercoreelements'),
                    'icon' => 'el-icon-cogs',
                    'fields' => array(
                        array(
                            'id' => 'iyzico_status',
                            'type' => 'button_set',
                            'title' => esc_html__('Status', 'pointfindercoreelements') ,
                            'options' => array(
                                '1' => esc_html__('Enable', 'pointfindercoreelements') ,
                                '0' => esc_html__('Disable', 'pointfindercoreelements')
                            ) , 
                            'default' => 0,
                        ) ,
                        array(
                            'id' => 'iyzico_mode',
                            'type' => 'button_set',
                            'title' => esc_html__('Mode', 'pointfindercoreelements') ,
                            'options' => array(
                                '1' => esc_html__('Live Mode', 'pointfindercoreelements') ,
                                '0' => esc_html__('Test Mode', 'pointfindercoreelements')
                            ) , 
                            'default' => 0,
                            'required' => array('iyzico_status','=',1)
                        ) ,
                        array(
                            'id' => 'iyzico_key1',
                            'type' => 'text',
                            'title' => esc_html__('API Anahtarı', 'pointfindercoreelements'),
                            'required' => array('iyzico_status','=',1)
                        ) ,
                        array(
                            'id' => 'iyzico_key2',
                            'type' => 'text',
                            'title' => esc_html__('Güvenlik Anahtarı', 'pointfindercoreelements'),
                            'required' => array('iyzico_status','=',1)
                        ) ,
                        array(
                            'id' => 'iyzico_installment',
                            'type' => 'text',
                            'title' => esc_html__('Taksit Bilgisi', 'pointfindercoreelements'),
                            'default' => '1,2,3,6,9',
                            'required' => array('iyzico_status','=',1),
                            'description' => esc_html__('Lütfen virgül ile ayrım esnasında boşluk bırakmayınız. Boşluk karakteri olduğunda işleminizde hata alabilirsiniz.', 'pointfindercoreelements'),
                        ) ,
                    )
                );
            /**
            *End : Iyzico API
            **/
        }

        

        public function setArguments() {


            $this->args = array(
                'opt_name'             => 'pfpgcontrol_options',
                'display_name'         => esc_html__('Point Finder Payment Gateways','pointfindercoreelements'),
                'menu_type'            => 'submenu',
                'page_parent'          => 'pointfinder_tools',
                'menu_title'           => esc_html__('Payment Gateways','pointfindercoreelements'),
                'page_title'           => esc_html__('Payment Gateways', 'pointfindercoreelements'),
                'admin_bar'            => false,
                'allow_sub_menu'       => false,
                'admin_bar_priority'   => 50,
                'global_variable'      => '',
                'dev_mode'             => false,
                'update_notice'        => false,
                'menu_icon'            => 'dashicons-twitter',
                'page_slug'            => '_pfpgconf',
                'save_defaults'        => false,
                'default_show'         => false,
                'default_mark'         => '',
                'transient_time'       => 60 * MINUTE_IN_SECONDS,
                'output'               => true,
                'output_tag'           => false,
                'database'             => '',
                'system_info'          => false,
                'domain'               => 'redux-framework',
                'hide_reset'           => true,
                'update_notice'        => false,
                'compiler'             => true,
            );


        }

    }

    new Redux_Framework_PF_PGcontrol_Config();
	
}
