<?php 

/**
 * VC New params
 */
class PointFinderVCNewParams
{

	public function __construct(){
	}

	function visualcomposer_single_page_elements(){
		vc_remove_element("vc_wp_links");
		vc_remove_element("vc_wp_meta");
		vc_remove_element("vc_wp_text");
		vc_remove_element("vc_wp_rss");
		vc_remove_element("vc_wp_pages");
		vc_remove_element("vc_wp_archives");
		vc_remove_element("vc_wp_posts");
		vc_remove_element("vc_wp_tagcloud");
		vc_remove_element("vc_wp_recentcomments");
		vc_remove_element("vc_wp_categories");
		vc_remove_element("vc_wp_calendar");
		vc_remove_element("vc_wp_custommenu");
		vc_remove_element("vc_wp_search");

		vc_add_shortcode_param('pfa_select1', array($this,'pfextendvc_select1'));
		vc_add_shortcode_param('pfa_numeric', array($this,'pfextendvc_numeric1'));
		vc_add_shortcode_param('pfa_select2', array($this,'pfextendvc_select2'));
		vc_add_shortcode_param('pfa_select3', array($this,'pfextendvc_select3'));
		vc_add_shortcode_param('pfa_showimg', array($this,'pfextendvc_showimg'));
		vc_add_shortcode_param('pf_info_field', array($this,'pf_info_vc_field'));
		vc_add_shortcode_param('pf_info_line_field', array($this,'pf_info_line_vc_field'));
		vc_add_shortcode_param('pf_custom_pointsx', array($this,'pf_custom_points_functx'));
	}


	function pfcustom_css_classes_for_vc_row_and_vc_column($class_string, $tag) {

		$run = true;

		if (isset($_GET['vc_editable'])) {
			$vc_editable = sanitize_text_field( $_GET['vc_editable'] );
			if ($vc_editable) {
				$run = false;
			}
		}

		if ($run) {
			if ($tag=='vc_column' || $tag=='vc_column_inner' || $tag=='vc_row' || $tag=='vc_row_inner') {
				$class_string = preg_replace('/vc_col-sm-(\d{1,2})/', 'col-lg-$1 col-md-$1', $class_string);
				$class_string = preg_replace('/vc_span(\d{1,2})/', 'col-lg-$1 col-md-$1', $class_string);
				$class_string = preg_replace('/vc_column_container/', '', $class_string);
			}
		}


	  return $class_string;
	}

	function pf_vc_remove_all_pointers() {
	   remove_action( 'admin_enqueue_scripts', 'vc_pointer_load' );
	}

	function pf_remove_vcmeta_boxes() {
		global $pagenow;
		if($pagenow == 'post.php' || $pagenow == 'post-new.php'){
			$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
			remove_meta_box( 'vc_teaser', 'post', 'side');
			remove_meta_box( 'vc_teaser', 'page',  'side');
			remove_meta_box( 'vc_teaser', $setup3_pointposttype_pt1, 'side');
		}
	}

    //Icon Select
	function pfextendvc_select1($settings, $value) {
	   $pf_icons_arr =
	   array(
				   	array('icon' => 'pfadmicon-glyph','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-1','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-2','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-3','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-4','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-5','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-6','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-7','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-8','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-9','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-10','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-11','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-12','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-13','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-14','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-15','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-16','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-17','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-18','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-19','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-20','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-21','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-22','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-23','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-24','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-25','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-26','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-27','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-28','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-29','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-30','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-31','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-32','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-33','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-34','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-35','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-36','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-37','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-38','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-39','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-40','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-41','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-42','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-43','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-44','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-45','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-46','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-47','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-48','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-49','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-50','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-51','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-52','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-53','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-54','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-55','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-56','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-57','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-58','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-59','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-60','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-61','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-62','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-63','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-64','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-65','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-66','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-67','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-68','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-69','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-70','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-71','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-72','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-73','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-74','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-75','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-76','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-77','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-78','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-79','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-80','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-81','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-82','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-83','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-84','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-85','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-86','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-87','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-88','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-89','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-90','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-91','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-92','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-93','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-94','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-95','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-96','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-97','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-98','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-99','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-100','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-101','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-102','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-103','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-104','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-105','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-106','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-107','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-108','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-109','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-110','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-111','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-112','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-113','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-114','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-115','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-116','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-117','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-118','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-119','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-120','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-121','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-122','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-123','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-124','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-125','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-126','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-127','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-128','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-129','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-130','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-131','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-132','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-133','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-134','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-135','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-136','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-137','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-138','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-139','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-140','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-141','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-142','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-143','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-144','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-145','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-146','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-147','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-148','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-149','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-150','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-151','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-152','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-153','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-154','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-155','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-156','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-157','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-158','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-159','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-160','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-161','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-162','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-163','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-164','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-165','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-166','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-167','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-168','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-169','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-170','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-171','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-172','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-173','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-174','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-175','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-176','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-177','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-178','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-179','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-180','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-181','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-182','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-183','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-184','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-185','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-186','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-187','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-188','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-189','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-190','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-191','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-192','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-193','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-194','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-195','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-196','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-197','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-198','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-199','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-200','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-201','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-202','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-203','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-204','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-205','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-206','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-207','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-208','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-209','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-210','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-211','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-212','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-213','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-214','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-215','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-216','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-217','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-218','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-219','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-220','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-221','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-222','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-223','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-224','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-225','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-226','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-227','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-228','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-229','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-230','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-231','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-232','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-233','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-234','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-235','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-236','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-237','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-238','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-239','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-240','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-241','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-242','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-243','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-244','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-245','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-246','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-247','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-248','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-249','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-250','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-251','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-252','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-253','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-254','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-255','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-256','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-257','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-258','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-259','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-260','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-261','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-262','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-263','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-264','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-265','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-266','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-267','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-268','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-269','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-270','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-271','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-272','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-273','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-274','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-275','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-276','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-277','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-278','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-279','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-280','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-281','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-282','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-283','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-284','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-285','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-286','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-287','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-288','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-289','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-290','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-291','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-292','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-293','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-294','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-295','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-296','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-297','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-298','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-299','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-300','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-301','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-302','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-303','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-304','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-305','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-306','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-307','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-308','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-309','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-310','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-311','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-312','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-313','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-314','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-315','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-316','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-317','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-318','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-319','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-320','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-321','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-322','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-323','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-324','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-325','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-326','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-327','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-328','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-329','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-330','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-331','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-332','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-333','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-334','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-335','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-336','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-337','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-338','keyword' => ''),
				   	array('icon' => 'pfadmicon-glyph-339','keyword' => '')
	   	);

	   $output ='<div class="pfextendvc_select1">';
	   $output .= '<div id="pfformheader"></div>';
	   $output .= '<ul>';
	   if(is_array($pf_icons_arr)){
		   foreach ( $pf_icons_arr as $iconclass ) {
				$output .= '<li class="'.$iconclass['icon'].'" data-pfa-iconname="'.str_replace('icon-','',$iconclass['keyword']).'"></li>';
		   }
	   }
	   $output .='</ul>
		<input type="hidden" class="wpb_vc_param_value wpb-textinput" id="'.$settings['param_name'].''.'" name="'.$settings['param_name'].''.'" value="'.$value.'">
		<script type="text/javascript">
		(function ($) {
		  "use strict"
		  function listFilter(header, list) {
			var form = $("<form>").attr({"class":"filterform","action":"#"}),
				input = $("<input>").attr({"class":"filterinput","type":"text"});
			/*
			$(form).append(input).appendTo(header);

			$(input).change( function () {
				var filter = $(this).val();
				if(filter) {
				  $(list).find("li:not([data-pfa-iconname*=" + filter + "])").fadeOut();
				  $(list).find("li[data-pfa-iconname*=" + filter + "]").fadeIn();
				} else {
				  $(list).find("li").fadeIn();
				}
				return false;
			  })
			.keyup( function () {
				$(this).change();
			});
			*/
		  }


		  //ondomready
		  $( document ).one("ajaxComplete",function () {

			';
			if($value != ''){
			$output .= '
				$(".pfextendvc_select1 ul li").each(function(){
					if($(this).attr("class") == "'.$value.'"){
						$(this).attr("data-pfa-status","active")
					}
				});
			';
			}
			$output.='
			$(".pfextendvc_select1 ul li").on("click",function(){
				$(".pfextendvc_select1 ul li").each(function(){
					$(this).attr("data-pfa-status","")
				});
				$(this).attr("data-pfa-status","active")
				$("#'.$settings['param_name'].'").val($(this).attr("class"));
			});

		});
		listFilter($("#pfformheader"), $(".pfextendvc_select1 ul"));
		})(jQuery);</script>
		</div>';


		return $output;
	}

	//Numeric field
	function pfextendvc_numeric1 ($settings, $value){
		$output = '
		<div class="pf-numeric-field1">
				<input name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-textinput box_title textfield pfnumeric-field" type="text" value="'.$value.'" onKeyPress="return numbersonly(this, event)" size="2"><span class="pfnumeric-text"><strong>px</strong></span>
		</div>';

		return $output;
	}

	//select2 with search
	function pfextendvc_select2($settings, $value) {
	   $output = '';
	   if($value != ''){
		  $value_x = pfstring2BasicArray($value);
	   }else{
		$value_x = array();
	   }
	   $output .= '<div class="pfextendvc_select2">';
	   $output .= '<select multiple ';
	   $output .='id="'.$settings['param_name'].'_select2'.'"';
	   $output .='"';
	   $output .= $settings['param_name'].' '.$settings['type'].'_field">';

				 foreach($settings['value'] as $myvaluekey => $myvalue){

					if($myvalue != ''){
						$output .= '<option value="'.$myvaluekey.'"';

						if(in_array($myvaluekey,$value_x)){ $output .= ' selected';}

						$output .='>'.$myvalue.'</option>';

					}else{

						$output .= '<option value="">'.$myvaluekey.'</option>';

					}

				 }

		$output .= '</select><input type="hidden" class="wpb_vc_param_value wpb-textinput" id="'.$settings['param_name'].''.'" name="'.$settings['param_name'].''.'" value="'.$value.'"> <script type="text/javascript">
		(function($) {
		"use strict"

		$( document ).one("ajaxComplete",function() {
			';

		$output .= '$("#'.$settings['param_name'].'").val($("#'.$settings['param_name'].'_select2").val());';

		$output .= '

			$("#'.$settings['param_name'].'_select2").select2({formatNoMatches:"", allowClear: true, closeOnSelect:true});
			$("#'.$settings['param_name'].'_select2").change(function(){$("#'.$settings['param_name'].'").val($(this).val());});
		});
		})(jQuery);</script></div>';


		return $output;
	}

	//Select2 Without search
	function pfextendvc_select3($settings, $value) {
	  $search = ', minimumResultsForSearch: -1';

	  $output = '<div class="pfextendvc_select2">';
	  $output .= '<select id="'.$settings['param_name'].'" name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-textinput '.$settings['param_name'].' '.$settings['type'].'_field">';

		foreach($settings['value'] as $myvaluekey => $myvalue){

			if($myvalue != ''){
				$output .= '<option value="'.$myvaluekey.'"';
				if(strcmp($myvaluekey,$value) == 0){
					$output .= ' selected';
				}
				$output .='>'.$myvalue.'</option>';
			}else{
				$output .= '<option value="">'.$myvaluekey.'</option>';
			}

		}

		$output .= '</select><script type="text/javascript">
		(function($){
		"use strict"
		$( document ).one("ajaxComplete",function(){$("#'.$settings['param_name'].'").select2({formatNoMatches:"", allowClear: true'.$search.'});});
		})(jQuery);</script></div>';


		return $output;
	}

	//ShowImg field
	function pfextendvc_showimg ($settings, $value){
		$output = '
		<div class="pf-showimg-field" id="'.$settings['param_name'].'">
				<img src="'.$value.'">
		</div>';

		$output .= '
		<script type="text/javascript">
		(function ($) {
			"use strict"
			$( document ).one("ajaxComplete",function () {
				$("select[name=\'iconbox_style\']").change( function () {
					$.imgvalue = $(this).val();
					if($.imgvalue == "type1"){
						$("#box_image1 img").attr("src","http://placehold.it/350x200");
					}else if($.imgvalue == "type2"){
						$("#box_image1 img").attr("src","http://placehold.it/350x150");
					};
				})
			});
		})(jQuery);</script>';

		return $output;
	}

	// PF Info Field
	function pf_info_vc_field($settings, $value) {
	   return '<div class="pf-info-vc-field"></div>';
	}

	// PF Info Field
	function pf_info_line_vc_field($settings, $value) {
	   return '<div class="pf-info-vc-field-line"></div>';
	}

	// PF Custom Points
	function pf_custom_points_functx($settings, $value) {
	    if (empty($value)) {
	    	$rownum = 1;
 	    	$value = 0;
	    }else{
	    	parse_str(html_entity_decode($value),$values);
	    	$rownum = (isset($values['rownum']))?$values['rownum']:1;
	    }


		$output = '';
		$output .= '
		<div class="cmappoints">
		<input type="hidden" name="rownum" id="rownum" value="'.$rownum.'">
		</div>
		<div>
			<button id="addAccordion">Add Point</button><button id="removeAccordion">Remove Last Point</button>
			<input type="hidden" name="'.$settings['param_name'].'" id="'.$settings['param_name'].'" value="'.$value.'" class="wpb_vc_param_value wpb-textinput">
		</div>
		<script>
		(function($) {
			"use strict";

				$.PFCloneDiv = function(rownum){
					return \'<div class="mappoint"><h3><a href="#">Point \'+rownum+\'</a></h3><div class=""><div class="vc_col-sm-6 wpb_el_type_textfield"><div class="wpb_element_label">Point Latitude</div><div class="edit_form_line"><input name="cmap_lat[\'+rownum+\']" class="wpb_vc_param_value wpb-textinput cmap_lat textfield" type="text" value=""><span class="vc_description vc_clearfix"><a href="https://labs.mondeca.com/geo/anyplace.html" target="_blank">Please click here for finding your coordinates</a></span></div></div><div class="vc_col-sm-6 vc_column wpb_el_type_textfield"><div class="wpb_element_label">Point Longitude</div><div class="edit_form_line"><input name="cmap_lng[\'+rownum+\']" class="wpb_vc_param_value wpb-textinput cmap_lng textfield" type="text" value=""></div></div><div class="vc_col-sm-12 vc_column wpb_el_type_textfield"><div class="wpb_element_label">Point Title</div><div class="edit_form_line"><input name="cmap_title[\'+rownum+\']" class="wpb_vc_param_value wpb-textinput cmap_title textfield" type="text" value=""></div></div><div class="vc_col-sm-12 vc_column wpb_el_type_textarea"><div class="wpb_element_label">Point Description</div><div class="edit_form_line"><textarea name="cmap_desc[\'+rownum+\']" class="wpb_vc_param_value wpb-textarea cmap_desc textarea"></textarea></div></div></div></div>\';
		        };
			   $( document ).one("ajaxComplete", function() {
					
			        $.pfrowNum = '.$rownum.';
			        $( ".cmappoints" ).accordion({
			            header: "> div > h3",
			            collapsible: true,
			            active: false,
			            autoHeight: false,
			            autoActivate: true
			        });



					for (var i=0; i < '.$rownum.' ; i++) {
			        	$(".cmappoints").append($.PFCloneDiv(i))
			            $(".cmappoints").accordion("refresh");
						if ('.$rownum.' >= 1) {
							var defaultvaluem = "'.$value.'" ;
							if (defaultvaluem != "0") {
								$.PFRValues = $.deserialize(defaultvaluem);
								$("input[name=\'cmap_lat["+i+"]\']").val($.PFRValues.cmap_lat[i]);
								$("input[name=\'cmap_lng["+i+"]\']").val($.PFRValues.cmap_lng[i]);
								$("input[name=\'cmap_title["+i+"]\']").val($.PFRValues.cmap_title[i].replace(/[+]/g, " "));
								$("textarea[name=\'cmap_desc["+i+"]\']").text($.PFRValues.cmap_desc[i].replace(/[+]/g, " "));

							}

						}

			        }


			        $( "button" ).button();
			        $("#addAccordion").on("click", function() {
			            var clonediv = $.PFCloneDiv($.pfrowNum);
			            $(".cmappoints").append(clonediv)
			            $(".cmappoints").accordion("refresh");
			            $.pfrowNum ++;
			            $("#rownum").val($.pfrowNum);
			        });

					$("#removeAccordion").on("click", function() {
						if($.pfrowNum > 1){
				            $(".cmappoints").find(".mappoint").last().remove();
				            $(".cmappoints").accordion("refresh");
				            $.pfrowNum --;
				            $("#rownum").val($.pfrowNum);
				        }
			        });
		';

		$vc_version_current = $this->pointfinder_get_vc_version();

		if (version_compare($vc_version_current, '4.7') >= 0) {
			$output .= '
				$(".vc_ui-button").on("click",function(){
					if ($(this).data("vc-ui-element") == "button-save") {
						$("#'.$settings['param_name'].'").val($(".cmappoints :input").serialize());
					}
				});
			';
		}else{
			$output .= '
				$(".vc_panel-btn-save").on("click",function(){
					$("#'.$settings['param_name'].'").val($(".cmappoints :input").serialize());
				});
			';
		}


		$output .= '

			    });
			})(jQuery);
		</script>
		';
		return $output;
	}

	function pointfinder_get_vc_version(){
		$vc_version_current = 0;
		if(function_exists('vc_set_as_theme')){
			if ( ! function_exists( 'get_plugins' ) ){
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			$plugin_folder = get_plugins( '/');
			$plugin_file = 'js_composer/js_composer.php';
			if (isset($plugin_folder[$plugin_file]['Version'])) {
				$vc_version_current = $plugin_folder[$plugin_file]['Version'];
			}
		}
		return $vc_version_current;
	}

	function additional_init(){

		$vc_layout_sub_controls = array(
		  array('link_post', esc_html__("Link to post", "pointfindercoreelements")),
		  array("no_link", esc_html__("No link", "pointfindercoreelements")),
		  array("link_image", esc_html__("Link to bigger image", "pointfindercoreelements"))
		);

		//Row Modifications
		vc_add_param('vc_row',
			  array(
				  "type" => 'checkbox',
				  "heading" => esc_html__("100% Width", "pointfindercoreelements"),
				  "param_name" => "widthopt",
				  "description" => esc_html__("Enables %100 width for this row.", "pointfindercoreelements"),
				  "value" => Array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
				  'save_always' => true
			  )
		);
		vc_add_param('vc_row',
			  array(
				  "type" => 'checkbox',
				  "heading" => esc_html__("Fixed Background", "pointfindercoreelements"),
				  "param_name" => "fixedbg",
				  "description" => esc_html__("This option enable fixed background if background image added from css design section.", "pointfindercoreelements"),
				  "value" => Array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
				  'save_always' => true
			  )
		);
		vc_add_param('vc_row',
			  array(
				  "type" => 'checkbox',
				  "heading" => esc_html__("Footer Row", "pointfindercoreelements"),
				  "param_name" => "footerrow",
				  "description" => esc_html__("If this row is footer. Please check this.", "pointfindercoreelements"),
				  "value" => Array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
				  'save_always' => true
			  )
		);
		vc_add_param('vc_row',
			  array(
				"type" => "colorpicker",
				"heading" => esc_html__('Text Color', 'pointfindercoreelements'),
				"param_name" => "colorfortext",
				"dependency" => array('element' => 'footerrow','not_empty' => true),
				'save_always' => true
			  )
		);
		vc_add_param('vc_row',
			  array(
				"type" => "colorpicker",
				"heading" => esc_html__('Text Color Hover', 'pointfindercoreelements'),
				"param_name" => "colorfortexth",
				"dependency" => array('element' => 'footerrow','not_empty' => true),
				'save_always' => true
			  )
		);

		vc_add_param('single_icon',
			  array(
				"type" => "textfield",
				"heading" => esc_html__('Icon Text', 'pointfindercoreelements'),
				"param_name" => "textoficon",
				'save_always' => true
			  )
		);


		//Posts Slider  -------------------------------------------------------------------------------------------------------
			vc_remove_param('vc_posts_slider','el_class');
			vc_remove_param('vc_posts_slider','type');
			vc_remove_param('vc_posts_slider','posttypes');
			vc_remove_param('vc_posts_slider','thumb_size');
			vc_add_param('vc_posts_slider',
				  array(
					  "type" => "dropdown",
					  "heading" => esc_html__("Slider Effect", "pointfindercoreelements"),
					  "param_name" => "mode",
					  "value" => array(esc_html__("Fade", "pointfindercoreelements") => 'fade', esc_html__("Fade Up", "pointfindercoreelements") => 'fadeUp',esc_html__("Back Slide", "pointfindercoreelements") => 'backSlide', esc_html__("Go Down", "pointfindercoreelements") => 'goDown'),
					  "description" => esc_html__("If slider enabled (1 Column) You can select a transition effect for it.", "pointfindercoreelements")
				  )
			);
			vc_add_param('vc_posts_slider',
				  array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("Slider autoplay", "pointfindercoreelements"),
					  "param_name" => "autoplay",
					  "description" => esc_html__("Enables autoplay mode.", "pointfindercoreelements"),
					  "value" => Array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes')
				  )
			);
			vc_add_param('vc_posts_slider',
				  array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("Hide pagination control", "pointfindercoreelements"),
					  "param_name" => "hide_pagination_control",
					  "description" => esc_html__("If YES pagination control will be removed.", "pointfindercoreelements"),
					  "value" => Array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes')
				  )
			);
			vc_add_param('vc_posts_slider',
				  array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("Hide prev/next buttons", "pointfindercoreelements"),
					  "param_name" => "hide_prev_next_buttons",
					  "description" => esc_html__('If "YES" prev/next control will be removed.', "pointfindercoreelements"),
					  "value" => Array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes')
				  )
			);
			vc_add_param('vc_posts_slider',
				  array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("Numbered Pagination Controls", "pointfindercoreelements"),
					  "param_name" => "numbered_pagination",
					  "description" => esc_html__("Enables numbered pagination mode. ! Pagination controls must be enabled.", "pointfindercoreelements"),
					  "value" => Array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes')
				  )
			);
			WPBMap::mutateParam('vc_posts_slider',
				array(
					  "type" => "textfield",
					  "heading" => esc_html__("Slider speed", "pointfindercoreelements"),
					  "param_name" => "interval",
					  "value" => "5000",
					  "description" => esc_html__("Duration of animation between slides (in ms)", "pointfindercoreelements")
				  )
			);
			WPBMap::mutateParam('vc_posts_slider',
				array(
					"type" => "textfield",
					"heading" => esc_html__("Widget title", "pointfindercoreelements"),
					"param_name" => "title",
					"description" => esc_html__("Enter text which will be used as widget title. Leave blank if no title is needed.", "pointfindercoreelements"),
					"admin_label" => true
				  )
			);

			WPBMap::mutateParam('vc_custom_heading',
				array(
		            'type' => 'font_container',
		            'param_name' => 'font_container',
		            'value'=>'',
		            'settings'=>array(
		                'fields'=>array(
		                    'tag'=>'h2',
		                    'text_align',
		                    'font_size',
		                    'line_height',
		                    'color',
		                    'tag_description' => esc_html__('Select element tag.','pointfindercoreelements'),
		                    'text_align_description' => esc_html__('Select text alignment.','pointfindercoreelements'),
		                    'font_size_description' => esc_html__('Enter font size. Ex: 18','pointfindercoreelements'),
		                    'line_height_description' => esc_html__('Enter line height. Ex: 20px (You must enter px at the end of number)','pointfindercoreelements'),
		                    'color_description' => esc_html__('Select color for your element.','pointfindercoreelements'),
		                ),
		            ),
		        )
			);






		//Posts Grid -------------------------------------------------------------------------------------------------------
			vc_remove_param('vc_posts_grid','grid_thumb_size');
			vc_remove_param('vc_posts_grid','el_class');
			vc_add_param("vc_posts_grid",
				array(
					  "type" => 'colorpicker',
					  "heading" => esc_html__("Post box background", "pointfindercoreelements"),
					  "param_name" => "itembox_bg",
					  "description" => esc_html__("Optional: You can select a color for item box background.", "pointfindercoreelements"),
					  "value" => ''
				  )
			);
			vc_add_param("vc_posts_grid",
				array(
					  "type" => 'colorpicker',
					  "heading" => esc_html__("Post box font color", "pointfindercoreelements"),
					  "param_name" => "itembox_font",
					  "description" => esc_html__("Optional: You can select a color for item box font.", "pointfindercoreelements"),
					  "value" => ''
				  )
			);
			WPBMap::mutateParam('vc_posts_grid',
				array(
					"type" => "loop",
					"heading" => esc_html__("Carousel content", "pointfindercoreelements"),
					"param_name" => "loop",
					'settings' => array(
					  'size' => array('hidden' => false, 'value' => 10),
					  'post_type' => array('hidden' => true, 'value' => 'post'),
					  'tax_query' => array('hidden' => true),
					  'by_id' => array('hidden' => true),
					  'order_by' => array('value' => 'date'),
					  'order' => array('value' => 'DESC')
					),
					"description" => esc_html__("Create WordPress loop, to populate content from your site.", "pointfindercoreelements"),
				)
			);
			WPBMap::mutateParam('vc_posts_grid',
				array(
					  "type" => "sorted_list",
					  "heading" => esc_html__("Teaser layout", "pointfindercoreelements"),
					  "param_name" => "grid_layout",
					  "description" => esc_html__("Control teasers look. Enable blocks and place them in desired order. Note: This setting can be overrriden on post to post basis.", "pointfindercoreelements"),
					  "value" => "image,title,bloginfo,text",
					  "options" => array(
						  array('image', esc_html__('Thumbnail', "pointfindercoreelements"), $vc_layout_sub_controls),
						  array('title', esc_html__('Title', "pointfindercoreelements"), $vc_layout_sub_controls),
						  array('text', esc_html__('Text', "pointfindercoreelements"), array(
							  array('excerpt', esc_html__('Teaser/Excerpt', "pointfindercoreelements")),
							  array('text', esc_html__('Full content', "pointfindercoreelements"))
						  )),
						  array('link', esc_html__('Read more link', "pointfindercoreelements")),
						  array('bloginfo', esc_html__('Blog info', "pointfindercoreelements"),array(
								array('date', esc_html__("Date Only", "pointfindercoreelements")),
								array("comments", esc_html__("Comments only", "pointfindercoreelements")),
								array("datecomments", esc_html__("Date + Comments", "pointfindercoreelements"))
							)
						  )
					  )
				  )
			);



		// Posts Carousel ---------------------------------------------------------------------------------------------------
			vc_remove_param("vc_carousel", "slides_per_view");
			vc_remove_param("vc_carousel", "partial_view");
			vc_remove_param("vc_carousel", "el_class");
			vc_remove_param("vc_carousel", "mode");
			vc_add_param("vc_carousel",
				array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("Numbered Pagination Controls", "pointfindercoreelements"),
					  "param_name" => "numbered_pagination",
					  "description" => esc_html__("Enables numbered pagination mode. ! Pagination controls must be enabled.", "pointfindercoreelements"),
					  "value" => Array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes')
				  )
			);
			vc_add_param("vc_carousel",
				array(
					  "type" => 'colorpicker',
					  "heading" => esc_html__("Post box background", "pointfindercoreelements"),
					  "param_name" => "itembox_bg",
					  "description" => esc_html__("Optional: You can select a color for item box background.", "pointfindercoreelements"),
					  "value" => ''
				  )
			);
			vc_add_param("vc_carousel",
				array(
					  "type" => 'colorpicker',
					  "heading" => esc_html__("Post box font color", "pointfindercoreelements"),
					  "param_name" => "itembox_font",
					  "description" => esc_html__("Optional: You can select a color for item box font.", "pointfindercoreelements"),
					  "value" => ''
				  )
			);
			WPBMap::mutateParam('vc_carousel',
				array(
					  "type" => "textfield",
					  "heading" => esc_html__("Slider speed", "pointfindercoreelements"),
					  "param_name" => "speed",
					  "value" => "300",
					  "description" => esc_html__("Duration of animation between slides (in ms)", "pointfindercoreelements")
				  )
			);

			WPBMap::mutateParam('vc_carousel',
				array(
					  "type" => "textfield",
					  "heading" => esc_html__("Widget title", "pointfindercoreelements"),
					  "param_name" => "title",
					  "description" => esc_html__("Enter text which will be used as widget title. Leave blank if no title is needed.", "pointfindercoreelements"),
					  "admin_label" => true
				  )
			);
			WPBMap::mutateParam('vc_carousel',
				array(
					"type" => "loop",
					"heading" => esc_html__("Carousel content", "pointfindercoreelements"),
					"param_name" => "posts_query",
					'settings' => array(
					  'size' => array('hidden' => false, 'value' => 10),
					  'post_type' => array('hidden' => true, 'value' => 'post'),
					  'tax_query' => array('hidden' => true),
					  'by_id' => array('hidden' => true),
					  'order_by' => array('value' => 'date'),
					  'order' => array('value' => 'DESC')
					),
					"description" => esc_html__("Create WordPress loop, to populate content from your site.", "pointfindercoreelements"),
				)
			);
			WPBMap::mutateParam('vc_carousel',
				array(
					  "type" => "sorted_list",
					  "heading" => esc_html__("Teaser layout", "pointfindercoreelements"),
					  "param_name" => "layout",
					  "description" => esc_html__("Control teasers look. Enable blocks and place them in desired order. Note: This setting can be overrriden on post to post basis.", "pointfindercoreelements"),
					  "value" => "image,title,bloginfo,text",
					  "options" => array(
						  array('image', esc_html__('Thumbnail', "pointfindercoreelements"), $vc_layout_sub_controls),
						  array('title', esc_html__('Title', "pointfindercoreelements"), $vc_layout_sub_controls),
						  array('text', esc_html__('Text', "pointfindercoreelements"), array(
							  array('excerpt', esc_html__('Teaser/Excerpt', "pointfindercoreelements")),
							  array('text', esc_html__('Full content', "pointfindercoreelements"))
						  )),
						  array('link', esc_html__('Read more link', "pointfindercoreelements")),
						  array('bloginfo', esc_html__('Blog info', "pointfindercoreelements"),array(
								array('date', esc_html__("Date Only", "pointfindercoreelements")),
								array("comments", esc_html__("Comments only", "pointfindercoreelements")),
								array("datecomments", esc_html__("Date + Comments", "pointfindercoreelements"))
							)
						  )
					  )
				  )
			);
			WPBMap::mutateParam('vc_carousel',
				array(
					"type" => "dropdown",
					"heading" => esc_html__("Column Number", "pointfindercoreelements"),
					"param_name" => "thumb_size",
					"value" => array(esc_html__("5 Columns", "pointfindercoreelements") => "grid5", esc_html__("4 Columns - Default", "pointfindercoreelements") => "grid4",esc_html__("3 Columns", "pointfindercoreelements") => "grid3",esc_html__("2 Columns", "pointfindercoreelements") => "grid2"),
					"description" => esc_html__("How many item want to see in viewport? (On mobile and tablet it will resize auto.)", "pointfindercoreelements"),
				  )
			);





		// Image Carousel ---------------------------------------------------------------------------------------------------
			vc_remove_param("vc_images_carousel", "slides_per_view");
			vc_remove_param("vc_images_carousel", "partial_view");
			vc_remove_param("vc_images_carousel", "el_class");
			vc_remove_param("vc_images_carousel", "wrap");
			vc_add_param("vc_images_carousel",
				array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("Numbered Pagination Controls", "pointfindercoreelements"),
					  "param_name" => "numbered_pagination",
					  "description" => esc_html__("Enables numbered pagination mode. ! Pagination controls must be enabled.", "pointfindercoreelements"),
					  "value" => Array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes')
				  )
			);
			vc_add_param('vc_images_carousel',
				  array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("Disable Auto Crop", "pointfindercoreelements"),
					  "param_name" => "autocrop",
					  "description" => esc_html__("Disables auto crop on image.(Not recommended.)", "pointfindercoreelements"),
					  "value" => Array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes')
				  )
			);
			vc_add_param("vc_images_carousel",
				array(
					  "type" => "textfield",
					  "heading" => esc_html__("Custom Size (Optional)", "pointfindercoreelements"),
					  "param_name" => "customsize",
					  "value" => "",
					  "description" => esc_html__("Ex: 300x200  | Custom size value (Optional). Please leave blank for auto resize. (in px)", "pointfindercoreelements")
				  )
			);
			WPBMap::mutateParam('vc_images_carousel',
				array(
					"type" => "dropdown",
					"heading" => esc_html__("Items", "pointfindercoreelements"),
					"param_name" => "img_size",
					"value" => array(
					esc_html__("5 Item", "pointfindercoreelements") => "grid5",
					esc_html__("4 Item - Default", "pointfindercoreelements") => "grid4",
					esc_html__("3 Item", "pointfindercoreelements") => "grid3",
					esc_html__("2 Item", "pointfindercoreelements") => "grid2",
					esc_html__("1 Item - (Slider Mode)", "pointfindercoreelements") => "grid1"
					),
					"description" => esc_html__("How many item want to see in viewport? (On mobile and tablet it will resize auto.)", "pointfindercoreelements"),
				  )
			);
			WPBMap::mutateParam('vc_images_carousel',
				array(
					  "type" => "dropdown",
					  "heading" => esc_html__("Slider Effect", "pointfindercoreelements"),
					  "param_name" => "mode",
					  "value" => array(esc_html__("Fade", "pointfindercoreelements") => 'fade', esc_html__("Fade Up", "pointfindercoreelements") => 'fadeUp',esc_html__("Back Slide", "pointfindercoreelements") => 'backSlide', esc_html__("Go Down", "pointfindercoreelements") => 'goDown'),
					  "description" => esc_html__("If slider enabled (1 Column) You can select a transition effect for it.", "pointfindercoreelements")
				  )
			);





		//  Gallery ---------------------------------------------------------------------------------------------------
			vc_remove_param("vc_gallery", "img_size");
			vc_remove_param("vc_gallery", "el_class");
			vc_remove_param("vc_gallery", "type");
			vc_remove_param("vc_gallery", "interval");
			vc_add_param("vc_gallery",
				array(
					"type" => "dropdown",
					"heading" => esc_html__("Column Number", "pointfindercoreelements"),
					"param_name" => "pfgrid",
					"value" => array(esc_html__("4 Columns - Default", "pointfindercoreelements") => "grid4",esc_html__("3 Columns", "pointfindercoreelements") => "grid3",esc_html__("2 Columns", "pointfindercoreelements") => "grid2"),
					"description" => esc_html__("How many column?", "pointfindercoreelements"),
				  )
			);
	}
}