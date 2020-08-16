<?php

if (!trait_exists('PointFinderOptionFunctions')) {
  /**
   * Option Functions
   */
  trait PointFinderOptionFunctions
  {

    public function PFSAIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){
      global $pointfindertheme_option;

      if (empty($pointfindertheme_option)) {
        $pointfindertheme_option = get_option('pointfindertheme_options');
      }

      if($field2 == ''){
        if (!isset($pointfindertheme_option[''.$field.''])) {
          return $default;
        }
        if ($pointfindertheme_option[''.$field.''] == "") {
          return $default;
        }
        return $pointfindertheme_option[''.$field.''];
      }else{
        if (!isset($pointfindertheme_option[''.$field.''][''.$field2.''])) {
          return $default;
        }
        if ($pointfindertheme_option[''.$field.''][''.$field2.''] == "") {
          return $default;
        }
        return $pointfindertheme_option[''.$field.''][''.$field2.''];
      };

    }

    public function PFASSIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){
      global $pfascontrol_options;
      
      if (empty($pfascontrol_options)) {
        $pfascontrol_options = get_option('pfascontrol_options');
      }

      if($field2 == ''){
        if (!isset($pfascontrol_options[''.$field.''])) {
          return $default;
        }
        if ($pfascontrol_options[''.$field.''] == "") {
          return $default;
        }
        return $pfascontrol_options[''.$field.''];
      }else{
        if (!isset($pfascontrol_options[''.$field.''][''.$field2.''])) {
          return $default;
        }
        if ($pfascontrol_options[''.$field.''][''.$field2.''] == "") {
          return $default;
        }
        return $pfascontrol_options[''.$field.''][''.$field2.''];
      };
    }

    public function PFADVIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){
      global $pfadvancedcontrol_options;

      if (empty($pfadvancedcontrol_options)) {
        $pfadvancedcontrol_options = get_option('pfadvancedcontrol_options');
      }

      if($field2 == ''){
        if (!isset($pfadvancedcontrol_options[''.$field.''])) {
          return $default;
        }
        if ($pfadvancedcontrol_options[''.$field.''] == "") {
          return $default;
        }
        return $pfadvancedcontrol_options[''.$field.''];
      }else{
        if (!isset($pfadvancedcontrol_options[''.$field.''][''.$field2.''])) {
          return $default;
        }
        if ($pfadvancedcontrol_options[''.$field.''][''.$field2.''] == "") {
          return $default;
        }
        return $pfadvancedcontrol_options[''.$field.''][''.$field2.''];
      };
    }

    public function PFSizeSIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){

      global $pfsizecontrol_options;

      if (empty($pfsizecontrol_options)) {
        $pfsizecontrol_options = get_option('pfsizecontrol_options');
      }

      if($field2 == ''){
        if (!isset($pfsizecontrol_options[''.$field.''])) {
          return $default;
        }
        if ($pfsizecontrol_options[''.$field.''] == "") {
          return $default;
        }
        return $pfsizecontrol_options[''.$field.''];
      }else{
        if (!isset($pfsizecontrol_options[''.$field.''][''.$field2.''])) {
          return $default;
        }
        if ($pfsizecontrol_options[''.$field.''][''.$field2.''] == "") {
          return $default;
        }
        return $pfsizecontrol_options[''.$field.''][''.$field2.''];
      };
    }

    public function PFREVSIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){

      global $pfitemreviewsystem_options;

      if (empty($pfitemreviewsystem_options)) {
        $pfitemreviewsystem_options = get_option('pfitemreviewsystem_options');
      }

      if($field2 == ''){
        if (!isset($pfitemreviewsystem_options[''.$field.''])) {
          return $default;
        }
        if ($pfitemreviewsystem_options[''.$field.''] == "") {
          return $default;
        }
        return $pfitemreviewsystem_options[''.$field.''];
      }else{
        if (!isset($pfitemreviewsystem_options[''.$field.''][''.$field2.''])) {
          return $default;
        }
        if ($pfitemreviewsystem_options[''.$field.''][''.$field2.''] == "") {
          return $default;
        }
        return $pfitemreviewsystem_options[''.$field.''][''.$field2.''];
      };
    }

    public function PFPGIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){
      
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

    public function PFCFIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){
      global $pfcustomfields_options;

      if (empty($pfcustomfields_options)) {
        $pfcustomfields_options = get_option('pfcustomfields_options');
      }

      if($field2 == ''){
        if (!isset($pfcustomfields_options[''.$field.''])) {
          return $default;
        }
        if ($pfcustomfields_options[''.$field.''] == "") {
          return $default;
        }
        return $pfcustomfields_options[''.$field.''];
      }else{
        if (!isset($pfcustomfields_options[''.$field.''][''.$field2.''])) {
          return $default;
        }
        if ($pfcustomfields_options[''.$field.''][''.$field2.''] == "") {
          return $default;
        }
        return $pfcustomfields_options[''.$field.''][''.$field2.''];
      };
    }

    public function PFSFIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){
      global $pfsearchfields_options;

      if (empty($pfsearchfields_options)) {
        $pfsearchfields_options = get_option('pfsearchfields_options');
      }

      if($field2 == ''){
        if (!isset($pfsearchfields_options[''.$field.''])) {
          return $default;
        }
        if ($pfsearchfields_options[''.$field.''] == "") {
          return $default;
        }
        return $pfsearchfields_options[''.$field.''];
      }else{
        if (!isset($pfsearchfields_options[''.$field.''][''.$field2.''])) {
          return $default;
        }
        if ($pfsearchfields_options[''.$field.''][''.$field2.''] == "") {
          return $default;
        }
        return $pfsearchfields_options[''.$field.''][''.$field2.''];
      };
    }

    public function PFMSIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){
      
      global $pointfindermail_option;

      if (empty($pointfindermail_option)) {
        $pointfindermail_option = get_option('pointfindermail_options');
      }

      if($field2 == ''){
        if(isset($pointfindermail_option[''.$field.'']) == false || $pointfindermail_option[''.$field.''] == ""){
          $output = $default;
        }else{
          $output = $pointfindermail_option[''.$field.''];
        }
      }else{
        if(isset($pointfindermail_option[''.$field.''][''.$field2.'']) == false || $pointfindermail_option[''.$field.''][''.$field2.''] == ""){
          $output = $default;
        }else{
          $output = $pointfindermail_option[''.$field.''][''.$field2.''];
        }
      };
      return $output;
    }

    public function PFPFIssetControl($field, $field2 = '', $default = ''){
      global $pfcustompoints_options;

      if (empty($pfcustompoints_options)) {
        $pfcustompoints_options = get_option('pfcustompoints_options');
      }

      if($field2 == ''){
        if (!isset($pfcustompoints_options[''.$field.''])) {
          return $default;
        }
        if ($pfcustompoints_options[''.$field.''] == "") {
          return $default;
        }
        return $pfcustompoints_options[''.$field.''];
      }else{
        if (!isset($pfcustompoints_options[''.$field.''][''.$field2.''])) {
          return $default;
        }
        if ($pfcustompoints_options[''.$field.''][''.$field2.''] == "") {
          return $default;
        }
        return $pfcustompoints_options[''.$field.''][''.$field2.''];
      };
    }

    public function PFTWIssetControl($field, $field2 = '', $default = ''){

      global $pftwitterwidget_options;

      if (empty($pftwitterwidget_options)) {
        $pftwitterwidget_options = get_option('pftwitterwidget_options');
      }

      if($field2 == ''){
        if (!isset($pftwitterwidget_options[''.$field.''])) {
          return $default;
        }
        if ($pftwitterwidget_options[''.$field.''] == "") {
          return $default;
        }
        return $pftwitterwidget_options[''.$field.''];
      }else{
        if (!isset($pftwitterwidget_options[''.$field.''][''.$field2.''])) {
          return $default;
        }
        if ($pftwitterwidget_options[''.$field.''][''.$field2.''] == "") {
          return $default;
        }
        return $pftwitterwidget_options[''.$field.''][''.$field2.''];
      };
      
    }

    public function PFPBSIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){

      global $pfpbcontrol_options;

      if (empty($pfpbcontrol_options)) {
        $pfpbcontrol_options = get_option('pfpbcontrol_options');
      }


      if($field2 == ''){
        if (!isset($pfpbcontrol_options[''.$field.''])) {
          return $default;
        }
        if ($pfpbcontrol_options[''.$field.''] == "") {
          return $default;
        }
        return $pfpbcontrol_options[''.$field.''];
      }else{
        if (!isset($pfpbcontrol_options[''.$field.''][''.$field2.''])) {
          return $default;
        }
        if ($pfpbcontrol_options[''.$field.''][''.$field2.''] == "") {
          return $default;
        }
        return $pfpbcontrol_options[''.$field.''][''.$field2.''];
      };
    }

    public function PFGetPostTypeName(){
      return $this->PFSAIssetControl("setup3_pointposttype_pt1","","pfitemfinder");
    }

  }
}