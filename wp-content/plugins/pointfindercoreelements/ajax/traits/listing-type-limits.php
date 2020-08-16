<?php 

if (class_exists('PointFinderListingTypeLimits')) {
	return;
}


class PointFinderListingTypeLimits extends Pointfindercoreelements_AJAX
{
    public function __construct(){}

    public function pf_ajax_listingtypelimits()
    {
        check_ajax_referer('pfget_listingtypelimits', 'security');
        header('Content-Type: application/json; charset=UTF-8;');

        $id = $lang = '';

        if (isset($_POST['id']) && $_POST['id'] != '') {
            $id = sanitize_text_field($_POST['id']);
        }
        $limit = array();
        if (isset($_POST['limit']) && $_POST['limit'] != '') {
            $limit = $_POST['limit'];
            $limit = $this->PFCleanArrayAttr('PFCleanFilters', $limit);
        }

        if (isset($_POST['lang']) && $_POST['lang'] != '') {
            $lang = sanitize_text_field($_POST['lang']);
        }

        /* WPML Fix */
        if (class_exists('SitePress')) {
            if (!empty($lang)) {
                do_action('wpml_switch_language', $lang);
            }
        }

        $this_limit_check = array();

        if (!empty($id)) {
            $pointfinderltypes_fevars = get_option('pointfinderltypes_fevars');

            if (isset($pointfinderltypes_fevars[$id]) && is_array($limit)) {
                if (count($limit) > 0) {
                    foreach ($limit as $key => $value) {
                        $this_limit_check[$value] = (isset($pointfinderltypes_fevars[$id][$value])) ? $pointfinderltypes_fevars[$id][$value] : '';
                    }
                } else {
                    $this_limit_check[$limit] = (isset($pointfinderltypes_fevars[$id][$limit])) ? $pointfinderltypes_fevars[$id][$limit] : '';
                }
            } else {
                if (count($limit) > 0) {
                    foreach ($limit as $key => $value) {
                        $this_limit_check[$value] = '';
                    }
                }
            }
        }

        echo json_encode($this_limit_check);

        die();
    }
}