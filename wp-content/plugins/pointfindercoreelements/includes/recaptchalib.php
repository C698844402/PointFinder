<?php
if(!class_exists('Pointfinder_reCaptcha_System')){
  class Pointfinder_reCaptcha_System {

      use PointFinderOptionFunctions;

      private $secret;
      private $publickey;

      private static $_siteVerifyUrl = "https://www.google.com/recaptcha/api/siteverify?";

      public function __construct() {

      $private_key = $this->PFSAIssetControl('reprik');
          if(!empty($private_key)){$this->secret = $private_key;}

      $public_key = $this->PFSAIssetControl('repubk');
      if(!empty($public_key)){$this->publickey = $public_key;}

      }

      private function submitRequest($path, $data){

          /*$ch = curl_init($path);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_TIMEOUT, 15);
          curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
          $response = curl_exec($ch);

          curl_close($ch);*/

          $args = array(
              'timeout'     => 30,
              'httpversion' => '1.1',
              'user-agent'  => 'WordPress; ' . esc_url(home_url("/")),
          ); 

          $response = wp_remote_get( $path.http_build_query($data), $args );

          if (!is_wp_error( $response )) {
             if (isset($response["body"])) {
                  return $response["body"];
             } 
          }

          return '';
      }

      public function verifyResponse($remoteip,$response){

          $getResponse = $this->submitRequest(self::$_siteVerifyUrl,array('secret' => $this->secret,'response' => $response,'remoteip'=>$remoteip));

          $answers = json_decode($getResponse, true);

          

          if (trim($answers['success']) == true && $answers["score"] >= 0.31) {
              return true;
          } else {
              return false;
          }

      }

    /*
    * Generate Recaptcha Widget
    */
    public function create_recaptcha($formname = ''){

        $wemdlx_rndnum = rand(10,1000);

        return '
        <script>
          jQuery(function(){
            setTimeout(function(){
              if (typeof grecaptcha != "undefined"){
                grecaptcha.ready(function(){
                  jQuery.reCAPTCHA_execute("'.$formname.'");
                });
              };
            },0);
          });
        </script>
        ';
    }


    /*
    * reCaptcha Check
    */
    public function check_recaptcha($recaptcha_response_field){
        if (!empty($recaptcha_response_field)) {
            $resp = $this->verifyResponse($_SERVER["REMOTE_ADDR"],$recaptcha_response_field);
        }else{
            return false;
        }
        if ($resp != null && $resp == true) {
            return true;
        }else{
            return false;
        }
    }

  }
}