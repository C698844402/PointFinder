<?php
/**
 * Listing Type REST API Custom End point for Visual Composer Field
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 */
 class Modulex_MXListingType_Custom_Listing_Title {

     public function __construct() {
         $this->namespace     = '/modulexlistingtypelist/v1';
         $this->resource_name = 'posts';
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

         register_rest_route( $this->namespace, '/' . $this->resource_name. '', array(
             array(
                 'methods'   => 'GET',
                 'callback'  => array( $this, 'get_items' ),
                 'permission_callback' => array( $this, 'get_items_permissions_check' ),
             ),
             'schema' => array( $this, 'get_item_schema' ),
         ) );
     }

     /**
      * Check permissions for the posts.
      *
      * @param WP_REST_Request $request Current request.
      */
     public function get_items_permissions_check( $request ) {
         if ( ! current_user_can( 'read' ) ) {}
         return true;
     }

     /**
      * Grabs the five most recent posts and outputs them as a rest response.
      *
      * @param WP_REST_Request $request Current request.
      */
     public function get_items( $request ) {
        $r_type = (isset($request['_type']))?sanitize_text_field( $request['_type'] ):'';
        $q = '';
        if (!empty($r_type)) {
          $q = sanitize_text_field( $request["q"] );
        }
        $args = array(
           'posts_per_page' => 10,
        	 'post_type' => 'mxlistingtype',
        	 'order' => 'DESC',
         	 'orderby' => 'Date',
           's' => $q
        );
        if (isset($request['lt'])) {
          $args['p'] = intval($request['lt']);
        }

        $posts = get_posts($args);
        $data = array();

        if ( empty( $posts ) ) {
           return rest_ensure_response( $data );
        }

        foreach ( $posts as $post ) {
           $response = $this->prepare_item_for_response( $post, $request );
           $data[] = $this->prepare_response_for_collection( $response );
        }

        return rest_ensure_response( $data );
     }


     /**
      * Matches the post data to the schema we want.
      *
      * @param WP_Post $post The comment object whose response is being prepared.
      */
     public function prepare_item_for_response( $post, $request ) {
         $post_data = array();

         $schema = $this->get_item_schema( $request );

         if ( isset( $schema['listingtypes']['id'] ) ) {
             $post_data['id'] = (int) $post->ID;
         }

				 if ( isset( $schema['listingtypes']['text'] ) ) {
             $post_data['text'] = $post->post_title;
         }

         return rest_ensure_response( $post_data );
     }

     /**
      * Prepare a response for inserting into a collection of responses.
      *
      * This is copied from WP_REST_Controller class in the WP REST API v2 plugin.
      *
      * @param WP_REST_Response $response Response object.
      * @return array Response data, ready for insertion into collection data.
      */
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

     /**
      * Get our sample schema for a post.
      *
      * @param WP_REST_Request $request Current request.
      */
     public function get_item_schema( $request ) {
         $schema = array(
             '$schema'              => 'http://json-schema.org/draft-07/schema#',
             'title'                => 'post',
             'type'                 => 'object',
             'listingtypes'           => array(
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
 }
  if (!function_exists('modulex_mxlistingtype_custom_listing_title')) {
    function modulex_mxlistingtype_custom_listing_title() {
       $controller = new Modulex_MXListingType_Custom_Listing_Title();
       $controller->register_routes();
    }
  }

 add_action( 'rest_api_init', 'modulex_mxlistingtype_custom_listing_title' );
