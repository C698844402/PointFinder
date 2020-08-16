<?php

 class Modulex_MXCategory_CustomList {

     public function __construct() {
         $this->namespace     = '/modulexcategorylist/v1';
         $this->resource_name = 'cats';
     }

     public function register_routes() {
         register_rest_route( $this->namespace, '/' . $this->resource_name. '/(?P<lt>\d+)', array(
             array(
                 'methods'   => 'GET',
                 'callback'  => array( $this, 'get_items' ),
                 'permission_callback' => array( $this, 'get_items_permissions_check' ),
             ),
             'schema' => array( $this, 'get_item_schema' ),
         ) );

         register_rest_route( $this->namespace, '/' . $this->resource_name. '/(?P<lt>\d+)/(?P<fw>\d+)/(?P<_type>\d+)', array(
             array(
                 'methods'   => 'GET',
                 'callback'  => array( $this, 'get_items' ),
                 'permission_callback' => array( $this, 'get_items_permissions_check' ),
             ),
             'schema' => array( $this, 'get_item_schema' ),
         ) );

         register_rest_route( $this->namespace, '/' . $this->resource_name. '/(?P<lt>\d+)/(?P<fw>\d+)/(?P<_type>\d+)/(?P<q>\d+)', array(
             array(
                 'methods'   => 'GET',
                 'callback'  => array( $this, 'get_items' ),
                 'permission_callback' => array( $this, 'get_items_permissions_check' ),
             ),
             'schema' => array( $this, 'get_item_schema' ),
         ) );

         register_rest_route( $this->namespace, '/' . $this->resource_name. '/(?P<lt>\d+)/(?P<fw>\d+)', array(
             array(
                 'methods'   => 'GET',
                 'callback'  => array( $this, 'get_items' ),
                 'permission_callback' => array( $this, 'get_items_permissions_check' ),
             ),
             'schema' => array( $this, 'get_item_schema' ),
         ) );

         register_rest_route( $this->namespace, '/' . $this->resource_name. '/(?P<lt>\d+)/(?P<fw>\d+)/(?P<sl>\d+)', array(
             array(
                 'methods'   => 'GET',
                 'callback'  => array( $this, 'get_items' ),
                 'permission_callback' => array( $this, 'get_items_permissions_check' ),
             ),
             'schema' => array( $this, 'get_item_schema' ),
         ) );
     }


     public function get_items_permissions_check( $request ) {
         if ( ! current_user_can( 'read' ) ) {}
         return true;
     }


     public function get_items( $request ) {

       if (!isset($request['lt'])) {
         return;
       }

       $r_type = (isset($request['_type']))?sanitize_text_field( $request['_type'] ):(isset($_GET['_type']))?sanitize_text_field($_GET['_type']):'';
       $q = '';
       $ppp = 6;
       if (!empty($r_type)) {
         $q = (isset($request['q']))?sanitize_text_field( $request['q'] ):(isset($_GET['q']))?sanitize_text_field($_GET['q']):'';;
         $ppp = 12;
       }

       $sl = (is_array($request['sl']))?absint($request['sl']):$this->string2BasicArray($request['sl']);
       $fw = (isset($request['fw']))?sanitize_text_field($request['fw']):(isset($_GET['fw']))?sanitize_text_field($_GET['fw']):'';
       $ct = (isset($request['ct']))?$this->string2BasicArray($request['ct']):(isset($_GET['ct']))?$this->string2BasicArray($_GET['ct']):'';

       switch ($fw) {
         case 'c':$taxonomy_name = "mxcategories";break;
         case 't':$taxonomy_name = "mxtags";break;
         default:$taxonomy_name = "mxcategories";break;
       }

       $args = array(
          'number' => $ppp,
          'taxonomy' => $taxonomy_name,
          'order' => 'ASC',
          'orderby' => 'name',
          'name__like' => $q,
          'fields' => 'id=>name',
          'hide_empty' => false,
          'meta_query' => array(
            'relation' => 'AND',
            array(
              'key' => 'mx_parent_listing_type',
              'value' => absint($request['lt']),
              'compare' => '=',
              'type' => 'NUMERIC'
            )
          )
       );

       if (!empty($sl)) {
         $args['term_taxonomy_id'] = $sl;
       }

       $terms = get_terms($args);
       if (empty($terms) && $fw == 't') {
         unset($args['meta_query']);

         $args['meta_query'] = array(
           'relation' => 'AND',
           array(
             'key' => 'mx_parent_categories',
             'value' => $ct,
             'compare' => 'IN'
           )
         );

         $terms = get_terms($args);
       }
       $data = array();

       if ( empty( $terms ) ) {
          return rest_ensure_response( $data );
       }

       foreach ( $terms as $term_key => $term_value ) {
          $response = $this->prepare_item_for_response( $term_key,$term_value, $request );
          $data[] = $this->prepare_response_for_collection( $response );
       }

       return rest_ensure_response( $data );
     }


     public function prepare_item_for_response( $term_key,$term_value, $request ) {
         $term_data = array();
         $schema = $this->get_item_schema( $request );

         if ( isset( $schema['categories']['id'] ) ) {
             $term_data['id'] = (int) $term_key;
         }

				 if ( isset( $schema['categories']['text'] ) ) {
             $term_data['text'] = $term_value;
         }

         return rest_ensure_response( $term_data );
     }


     public function prepare_response_for_collection( $response ) {
         if ( ! ( $response instanceof WP_REST_Response ) ) {
             return $response;
         }

         $data = (array) $response->get_data();
         $server = rest_get_server();

         if ( method_exists( $server, 'get_compact_response_links' ) ) {
             $links = call_user_func( array( $server, 'get_compact_response_links' ), $response );
         } else {
             $links = call_user_func( array( $server, 'get_response_links' ), $response );
         }

         if ( ! empty( $links ) ) {
             $data['_links'] = $links;
         }

         return $data;
     }


     public function get_item_schema( $request ) {
         $schema = array(
             '$schema'              => 'http://json-schema.org/draft-07/schema#',
             'title'                => 'post',
             'type'                 => 'object',
             'categories'           => array(
                 'id' => array(
                     'description'  => esc_html__( 'Unique identifier for the object.', 'modulexthemecore' ),
                     'type'         => 'integer',
                     'context'      => array( 'view', 'edit', 'embed' ),
                     'readonly'     => true,
                 ),
								 'text' => array(
                     'description'  => esc_html__( 'The title for the object.', 'modulexthemecore' ),
                     'type'         => 'string',
										 'readonly'     => true,
                 ),
             ),
         );

         return $schema;
     }


     public function authorization_status_code() {

         $status = 401;

         if ( is_user_logged_in() ) {
             $status = 403;
         }

         return $status;
     }

     private function string2BasicArray($string, $kv = ',') {
			$ka = array();
			if($string != ''){
				if(strpos($string, $kv) != false){
					$string_exp = explode($kv,$string);
					foreach($string_exp as $s){
						$ka[]=absint($s);
					}
				}else{
					return array(intval($string));
				}
			}
			return $ka;
		}
 }

  if(!function_exists('modulex_mxcategory_customlist')){
    function modulex_mxcategory_customlist() {
       $controller = new Modulex_MXCategory_CustomList();
       $controller->register_routes();
    }
  }
  add_action( 'rest_api_init', 'modulex_mxcategory_customlist' );
