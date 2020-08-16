<?php
/**********************************************************************************************************************************
*
* Breadcrumbs section
*
* Author: Webbu
***********************************************************************************************************************************/

if (trait_exists('PointFinderListingBreadcrumbs')) {
  return;
}

trait PointFinderListingBreadcrumbs
{
  function pf_the_breadcrumb($params = array()) {

    $defaults = array(
      'taxname' => '',
      '_text_custom_css' => '',
      '_text_custom_css_main' => ''
    );

    $params = array_merge($defaults, $params);

    $_text_custom_css_main = (!empty($params['_text_custom_css_main']))?$params['_text_custom_css_main']:'';
    $_text_custom_css = (!empty($params['_text_custom_css']))?$params['_text_custom_css']:'';

    $setup3_modulessetup_breadcrumbs = $this->PFSAIssetControl('setup3_modulessetup_breadcrumbs','','1');

    if ($setup3_modulessetup_breadcrumbs == 1) {

      if (function_exists('is_bbpress')) {
        if(is_bbpress()){
          return;
        }
      }


      $output = '';
      $output .= '<ul id="pfcrumbs" class="'.trim($_text_custom_css_main).'" '.trim($_text_custom_css).'>';

      if (!is_home()) {
        $output .= '<li><a href="';
        $output .= esc_url(home_url("/"));
        $output .= '">';
        $output .= esc_html__('Home','pointfindercoreelements');
        $output .= "</a></li>";

        if (is_category() || is_single()) {

          switch ($this->post_type) {
            case $this->post_type_name:
              $output2 = '';
              if($this->item_terms){
                $cat_count = count($this->item_terms);
                $i = 1;

                foreach($this->item_terms as $category) {
                  if (!empty($category->parent)) {

                    if ($i == 1) {
                      $term_parent_name = get_term_by('id', $category->parent, 'pointfinderltypes','ARRAY_A');
                      $get_termname = $term_parent_name['name'].' / '.$category->name;
                      $output2 .= '<li>';
                      $output2 .= '<a href="'.get_term_link( $category->parent, 'pointfinderltypes' ).'" title="' . esc_attr( wp_sprintf( esc_html__( "View all posts in %s","pointfindercoreelements" ), $term_parent_name['name']) ) . '">'.$term_parent_name['name'].'</a>';
                      $output2 .= '</li>';
                      $i = $i + 1;
                    }
                  }

                  $output2 .= '<li>';
                  $output2 .= '<a href="'.get_term_link( $category->term_id,'pointfinderltypes' ).'" title="' . esc_attr( wp_sprintf( esc_html__( "View all posts in %s","pointfindercoreelements" ), $category->name ) ) . '">'.$category->name.'</a>';
                  $output2 .= '</li>';
                }
              $output .= trim($output2);
              }
              break;

            case 'post':

              $list_cats = get_category_by_path("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",false);
              $ci = 0;
              if (isset($list_cats)) {
                $output .= '<li>'.$list_cats->name.'</li>';
              }

              break;
            default:
              $list_cats = get_the_category();
              $ci = 0;
              foreach ($list_cats as $list_cat) {
                if($ci < 2){
                  $output .= '<li>'.$list_cat->name.'</li>';
                }
                $ci++;
              }
          }

          if (is_single()) {
            $output .= "<li>";
            $output .= '<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>';
            $output .= '</li>';
          }
        }elseif (is_page()) {

          $parents = get_post_ancestors($this->post_id);
          $parents = array_reverse($parents);
          if (!empty($parents)) {
            foreach ($parents as $key => $value) {
              $output .= '<li>';
              $output .= '<a href="'.get_permalink($value).'" title="'.get_the_title($value).'">'.get_the_title($value).'</a>';
              $output .= '</li>';
            }
          }

          $output .= '<li>';
          $output .= '<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>';
          $output .= '</li>';
        }elseif (is_tax()) {
          $output .= "<li>";
          $output .= $params['taxname'];
          $output .= '</li>';
        }elseif (is_tag()) {
          $output .= "<li>";
          $output .= single_tag_title('',false);
          $output .= '</li>';
        }


      }elseif (is_day()) {
        $output .="<li>".esc_html__('Archive for','pointfindercoreelements')." "; get_the_time('F jS, Y'); $output .='</li>';
      }elseif (is_month()) {
        $output .="<li>".esc_html__('Archive for','pointfindercoreelements')." "; get_the_time('F, Y'); $output .='</li>';
      }elseif (is_year()) {
        $output .="<li>".esc_html__('Archive for','pointfindercoreelements')." "; get_the_time('Y'); $output .='</li>';
      }elseif (is_author()) {
        $output .="<li>".esc_html__('Author Archive','pointfindercoreelements').""; $output .='</li>';
      }elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
        $output .= "<li>".esc_html__('Blog Archives','pointfindercoreelements').""; 
        $output .='</li>';
      }elseif (is_search()) {
        $output .="<li>".esc_html__('Search Results','pointfindercoreelements').""; $output .='</li>';
      }
      
      $output .= '</ul>';

      return $output;
    }

  }
}