<?php 

if (trait_exists('PointFinderListingTypeConnections')) {
	return;
}

/**
 * Listing Type Connection System
 */
trait PointFinderListingTypeConnections
{
    /* For add screen */
    public function pointfinder_category_form_custom_field_add( $taxonomy ) {
	    switch ($taxonomy) {
	        case 'pointfinderfeatures':
	        case 'pointfinderitypes':
	        case 'pointfinderconditions':
	            $this->pointfinder_taxonomy_connection_field_creator('');
	            break;
	    }
	}


	/* For edit screen */
	public function pointfinder_category_form_custom_field_edit( $tag, $taxonomy ) {
	    
	    $process = false;

	    switch ($taxonomy) {
	        case 'pointfinderfeatures':
	            $option_name = 'pointfinder_features_customlisttype_' . $tag->term_id;
	            $selected_value = get_option( $option_name );
	            $process = true;
	            break;

	        case 'pointfinderitypes':
	            $selected_value = get_term_meta($tag->term_id,'pointfinder_itemtype_clt',true);
	            $process = true;
	            break;

	        case 'pointfinderconditions':
	            $selected_value = get_term_meta($tag->term_id,'pointfinder_condition_clt',true);
	            $process = true;
	            break;
	    }

	    if ($process) {
	        $this->pointfinder_taxonomy_connection_field_creator($selected_value);
	    }
	}


	/** Save Custom Field Of Category Form */
	public function pointfinder_category_form_custom_field_save( $term_id, $tt_id ) {

	    if (isset($_POST['taxonomy'])) {

	        $taxonomy = $_POST['taxonomy'];
	        $pflist = (isset($_POST['pfupload_listingtypes']))?$_POST['pfupload_listingtypes']:'';
	    
	        switch ($taxonomy) {
	            case 'pointfinderfeatures':
	                if ( isset( $pflist ) ) { 
	                    $option_name = 'pointfinder_features_customlisttype_' . $term_id;
	                    update_option( $option_name, $pflist );
	                }else{
	                    $option_name = 'pointfinder_features_customlisttype_' . $term_id;
	                    update_option( $option_name, "" );
	                }
	                break;

	            case 'pointfinderitypes':
	                update_term_meta($term_id, 'pointfinder_itemtype_clt',$pflist);
	                break;

	            case 'pointfinderconditions':
	                update_term_meta($term_id, 'pointfinder_condition_clt',$pflist);
	                break;

	        }   
	    }   
	}

	public function pointfinder_taxonomy_connection_field_creator($selected_value){

		echo '<tr class="form-field"><th scope="row" valign="top"></th><td>';
	    echo '<section>';



	    $listdefault = (isset($selected_value))?$selected_value:'';
	    $output_options = $output = "";

	    $fieldvalues = get_terms('pointfinderltypes',array('hide_empty'=>false));

	    foreach( $fieldvalues as $parentfieldvalue){

	        if($parentfieldvalue->parent == 0){

	        	$fieldtaxSelectedValueParent = 0;

	        	if(!empty($listdefault)){
		            if(is_array($listdefault)){
		                if(in_array($parentfieldvalue->term_id, $listdefault)){ $fieldtaxSelectedValueParent = 1;}
		            }else{
		                if(strcmp($listdefault,$parentfieldvalue->term_id) == 0){ $fieldtaxSelectedValueParent = 1;}
		            }
		        }

		        if($fieldtaxSelectedValueParent == 1){
		            $output_options .= '<option value="'.$parentfieldvalue->term_id.'" selected class="pftitlebold">&nbsp;'.$parentfieldvalue->name.'</option>';
		        }else{
		            $output_options .= '<option value="'.$parentfieldvalue->term_id.'" class="pftitlebold">&nbsp;'.$parentfieldvalue->name.'</option>';
		        }

		        foreach( $fieldvalues as $firstchild_fieldvalue){
					if($firstchild_fieldvalue->parent == $parentfieldvalue->term_id){
						$fieldtaxSelectedValueFC = 0;

			        	if(!empty($listdefault)){
				            if(is_array($listdefault)){
				                if(in_array($firstchild_fieldvalue->term_id, $listdefault)){ $fieldtaxSelectedValueFC = 1;}
				            }else{
				                if(strcmp($listdefault,$firstchild_fieldvalue->term_id) == 0){ $fieldtaxSelectedValueFC = 1;}
				            }
				        }

				        if($fieldtaxSelectedValueFC == 1){
				            $output_options .= '<option value="'.$firstchild_fieldvalue->term_id.'" selected>&nbsp;&nbsp;-&nbsp;'.$firstchild_fieldvalue->name.'</option>';
				        }else{
				            $output_options .= '<option value="'.$firstchild_fieldvalue->term_id.'">&nbsp;&nbsp;-&nbsp;'.$firstchild_fieldvalue->name.'</option>';
				        }

				        foreach( $fieldvalues as $secondchild_fieldvalue){
							if($secondchild_fieldvalue->parent == $firstchild_fieldvalue->term_id){
								$fieldtaxSelectedValueSC = 0;

					        	if(!empty($listdefault)){
						            if(is_array($listdefault)){
						                if(in_array($secondchild_fieldvalue->term_id, $listdefault)){ $fieldtaxSelectedValueSC = 1;}
						            }else{
						                if(strcmp($listdefault,$secondchild_fieldvalue->term_id) == 0){ $fieldtaxSelectedValueSC = 1;}
						            }
						        }

						        if($fieldtaxSelectedValueSC == 1){
						            $output_options .= '<option value="'.$secondchild_fieldvalue->term_id.'" selected>&nbsp;&nbsp;--&nbsp;'.$secondchild_fieldvalue->name.'</option>';
						        }else{
						            $output_options .= '<option value="'.$secondchild_fieldvalue->term_id.'">&nbsp;&nbsp;--&nbsp;'.$secondchild_fieldvalue->name.'</option>';
						        }
							}
						}
					}
				}
	        }
	    }

	    echo '<div class="pf_fr_inner" data-pf-parent="">';
	    echo '<label for="pfupload_listingtypes" class="lbl-text">'.esc_html__("Connection with Listing Type","pointfindercoreelements").':</label>';
	    echo '<label class="lbl-ui select">
	    <select multiple name="pfupload_listingtypes[]" id="pfupload_listingtypes">
	    ';
	    echo $output_options;
	    echo '
	    </select>
	    </label>';

	    echo '</div>';
	    echo '</section>';


	    echo '
	    <script>
	    jQuery(function(){
	        jQuery("#pfupload_listingtypes").multiselect({
	            buttonWidth: "300px",
	            disableIfEmpty: true,
	            nonSelectedText: "'.esc_html__("Please select","pointfindercoreelements").'",
	            nSelectedText: "'.esc_html__("selected","pointfindercoreelements").'",
	            allSelectedText: "'.esc_html__("All selected","pointfindercoreelements").'",
	            selectAllText: "'.esc_html__("Select all","pointfindercoreelements").'",
	            includeSelectAllOption: true,
	            enableFiltering: true,
	            filterPlaceholder: "'.esc_html__("Search","pointfindercoreelements").'",
	            enableFullValueFiltering: true,
	            enableCaseInsensitiveFiltering: true,
	            maxHeight: 300
	        });

	        jQuery("#addtag #submit").on("click",function(){
	        	jQuery(document).ajaxComplete(function() {
	        		jQuery("#pfupload_listingtypes").multiselect("deselectAll", false);
	        		jQuery("#pfupload_listingtypes").multiselect("updateButtonText");
	        	});
	        });
	    });
	    </script>
	    </td>
	    </tr>
	    ';
	}
}