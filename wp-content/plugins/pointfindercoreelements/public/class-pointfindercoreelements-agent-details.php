<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themeforest.net/user/webbu
 * @since      1.0.0
 *
 * @package    Pointfindercoreelements
 * @subpackage Pointfindercoreelements/agentdetails
 */

class Pointfindercoreelements_AgentDetails
{
    use PointFinderOptionFunctions,
    PointFinderCommonFunctions,
    PointFinderWPMLFunctions,
    PointFinderAGNTFunctions;
    

    private $agent_post_type_name;
    private $plugin_name;
    private $version;


    public function __construct($plugin_name, $version, $agent_post_type_name)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->agent_post_type_name = $agent_post_type_name;
    }

    public function pointfinder_agent_post_type_filter($status, $post_type){
        if ($post_type == $this->agent_post_type_name) {
            $status = true;
        }else{
            $status = false;
        }

        return $status;
    }


    public function pointfinder_single_agent_output(){
        $post_type = get_post_type();

        if ($post_type != $this->agent_post_type_name) {
            return;
        }

        $post_id = get_the_id();

        $authorpmap = $this->PFSAIssetControl('authorpmap','',1);

        if ($authorpmap != 1) {
           if(function_exists('PFGetDefaultPageHeader')){
                PFGetDefaultPageHeader(array('agent_id' => $post_id));
            } 
        }else{
            echo '<div></div>';
        }
        

        $setup42_itempagedetails_sidebarpos_auth = $this->PFSAIssetControl('setup42_itempagedetails_sidebarpos_auth','','2');
        echo '<section role="main" class="pf-itempage-maindiv">';
            echo '<div class="pf-container clearfix">';
            echo '<div class="pf-row clearfix">';
            if ($setup42_itempagedetails_sidebarpos_auth == 2) {
                $this->PFGetAgentPageCol1($post_id);
                $this->PFGetAgentPageCol2();
            } elseif ($setup42_itempagedetails_sidebarpos_auth == 1) {
                $this->PFGetAgentPageCol2();
                $this->PFGetAgentPageCol1($post_id);
            }else{
                $this->PFGetAgentPageCol1($post_id);
            }
            echo '</div>';
            echo '</div>';
        echo '</section>';
    }


    public function PFGetAgentPageCol1($author_id){
        $setup42_itempagedetails_sidebarpos_auth = $this->PFSAIssetControl('setup42_itempagedetails_sidebarpos_auth','','2');
        if ($setup42_itempagedetails_sidebarpos_auth == 3) {
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="pfauthordetail pfauthordetailpage">';
        }else{
            echo '<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12"><div class="pfauthordetail pfauthordetailpage">';
        }

        echo '<div class="pftrwcontainer hidden-print pf-itempagedetail-element pf-itempage-contactinfo pfnewbglppage">';
        echo '<section role="itempagesidebarinfo" class="pf-itempage-sidebarinfo pfpos2 pf-itempage-elements">';
        echo $this->contactleftsidebar(array('formmode' => 'agent','i'=>2,'the_post_id' => '','the_author_id' => $author_id,'sb_contact'=> 'not','locationofplace'=>'agentdetailpage'));
        echo '</section>';
        echo '</div>';

      


        $setup42_authorpagedetails_background2 = $this->PFSAIssetControl('setup22_searchresults_background2','','#f7f7f7');
        $setup42_authorpagedetails_grid_layout_mode = $this->PFSAIssetControl('setup22_searchresults_grid_layout_mode','','1');
        $setup42_authorpagedetails_defaultppptype = $this->PFSAIssetControl('setup22_searchresults_defaultppptype','','10');
        $setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
        $setup42_authorpagedetails_grid_layout_mode = ($setup42_authorpagedetails_grid_layout_mode == 1) ? 'fitRows' : 'masonry' ;

        $author_id_filtered = $author_id;
        if(class_exists('SitePress')){
            if($this->PF_current_language() != $this->PF_default_language()){
                $agents_pt = $this->PFSAIssetControl("setup3_pointposttype_pt8","","agents");
                $author_id_filtered = apply_filters( 'wpml_object_id', $author_id, $agents_pt, TRUE, $this->PF_default_language() );
               
            }
        }

        $agentposts = $this->pointfinder_agentitemcount_calc($author_id_filtered, $setup3_pointposttype_pt1,'ids');

        $agentposts = (isset($agentposts['ids']))?$agentposts['ids']:'';



        echo '<div id="pfauthor-items">';
        if (!empty($agentposts)) {
        echo do_shortcode('[pf_itemgrid posts_in="'.$agentposts.'" orderby="title" sortby="ASC" items="'.$setup42_authorpagedetails_defaultppptype.'" cols="3" grid_layout_mode="'.$setup42_authorpagedetails_grid_layout_mode.'" filters="true" itemboxbg="'.$setup42_authorpagedetails_background2.'" agentmode="1" author="'.$author_id.'"]' );
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';

    }




    public function PFGetAgentPageCol2(){
        echo '<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 hidden-print">';
        echo '<section role="itempagesidebar" class="pf-itempage-sidebar">';
            echo '<div id="pf-itempage-sidebar">';
                echo '<div class="sidebar-widget">';
                    if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('pointfinder-authorpage-area'))
                echo '</div>';
            echo '</div>';
        echo '</section>';
        echo '</div>';
    }
    
}
