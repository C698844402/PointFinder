<?php


if (trait_exists('PointFinderPostTypeName')) {
  return;
}

/**
 * Post type Name Functions
 */
trait PointFinderPostTypeName
{

  public function PFGetPostTypeName(){
    return $this->PFSAIssetControl("setup3_pointposttype_pt1","","pfitemfinder");
  }

}
