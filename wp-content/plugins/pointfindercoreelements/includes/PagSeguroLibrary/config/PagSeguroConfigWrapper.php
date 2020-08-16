<?php
/**
 * 2007-2014 [PagSeguro Internet Ltda.]
 *
 * NOTICE OF LICENSE
 *
 *Licensed under the Apache License, Version 2.0 (the "License");
 *you may not use this file except in compliance with the License.
 *You may obtain a copy of the License at
 *
 *http://www.apache.org/licenses/LICENSE-2.0
 *
 *Unless required by applicable law or agreed to in writing, software
 *distributed under the License is distributed on an "AS IS" BASIS,
 *WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *See the License for the specific language governing permissions and
 *limitations under the License.
 *
 *  @author    PagSeguro Internet Ltda.
 *  @copyright 2007-2014 PagSeguro Internet Ltda.
 *  @license   http://www.apache.org/licenses/LICENSE-2.0
 */

/**
* 
*/

class PagSeguroConfigWrapper
{
    

    const PAGSEGURO_ENV = "";
    const PAGSEGURO_EMAIL = "";
    const PAGSEGURO_TOKEN_PRODUCTION = "";
    const PAGSEGURO_TOKEN_SANDBOX = "";
    const PAGSEGURO_APP_ID_PRODUCTION = "";
    const PAGSEGURO_APP_ID_SANDBOX = "";
    const PAGSEGURO_APP_KEY_PRODUCTION = "";
    const PAGSEGURO_APP_KEY_SANDBOX = "";
    const PAGSEGURO_CHARSET = "UTF-8";
    const PAGSEGURO_LOG_ACTIVE = false;
    const PAGSEGURO_LOG_LOCATION = "";


    private static function PFPGIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){
    
      global $pfpgcontrol_options;

      if (empty($pfpgcontrol_options)) {
        $pfpgcontrol_options = get_option('pfpgcontrol_options');
      }

      if($field2 == ''){
        if (!isset($pfpgcontrol_options[''.$field.''])) {
          return $default;
        }
        if ($pfpgcontrol_options[''.$field.''] == "") {
          return $default;
        }
        return $pfpgcontrol_options[''.$field.''];
      }else{
        if (!isset($pfpgcontrol_options[''.$field.''][''.$field2.''])) {
          return $default;
        }
        if ($pfpgcontrol_options[''.$field.''][''.$field2.''] == "") {
          return $default;
        }
        return $pfpgcontrol_options[''.$field.''][''.$field2.''];
      };
    }

    public static function getConfig()
    {
        $PagSeguroConfig = array();

          $pags_status = self::PFPGIssetControl('pags_status','',0);
          $pags_mode = self::PFPGIssetControl('pags_mode','',0);
          if ($pags_mode == 1) {
            $PagSeguroConfig['environment'] = "production";
          }else{
            $PagSeguroConfig['environment'] = "sandbox";
          }

          $PagSeguroConfig['credentials'] = array();
          $PagSeguroConfig['credentials']['email'] = self::PFPGIssetControl('pags_email','','');
          $PagSeguroConfig['credentials']['token']['production'] = self::PFPGIssetControl('pags_token','','');
          $PagSeguroConfig['credentials']['token']['sandbox'] = self::PFPGIssetControl('pags_token','','');
          $PagSeguroConfig['credentials']['appId']['production'] = self::PFPGIssetControl('pags_appid','','');
          $PagSeguroConfig['credentials']['appId']['sandbox'] = self::PFPGIssetControl('pags_appid','','');
          $PagSeguroConfig['credentials']['appKey']['production'] = self::PFPGIssetControl('pags_appkey','','');
          $PagSeguroConfig['credentials']['appKey']['sandbox'] = self::PFPGIssetControl('pags_appkey','','');

          $PagSeguroConfig['application'] = array();
          $PagSeguroConfig['application']['charset'] = "UTF-8"; // UTF-8, ISO-8859-1

          $PagSeguroConfig['log'] = array();
          $PagSeguroConfig['log']['active'] = false;
          $PagSeguroConfig['log']['fileLocation'] = "";

        return $PagSeguroConfig;
    }
}
