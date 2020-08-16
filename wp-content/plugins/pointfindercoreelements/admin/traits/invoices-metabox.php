<?php 

if (trait_exists('PointfinderInvoicesMetabox')) {
  return;
}

/**
 * Invoices Metabox
 */
trait PointfinderInvoicesMetabox
{
    /**
     * summary
     */
    public function pointfinder_minvoices_add_meta_box($post_type) {
		if ($post_type == 'pointfinderinvoices') {
			
			add_meta_box(
				'pointfinder_invoices_info',
				esc_html__( 'INVOICE INFO', 'pointfindercoreelements' ),
				array($this,'pointfinder_minvoices_meta_box_orderinfo'),
				'pointfinderinvoices',
				'side',
				'high'
			);

			add_meta_box(
				'pointfinder_invoices_process',
				esc_html__( 'INVOICE DETAIL', 'pointfindercoreelements' ),
				array($this,'pointfinder_minvoices_meta_box_orderprocess'),
				'pointfinderinvoices',
				'normal',
				'core'
			);
		}
	}

	/**
	*Start : Invoice Info Content
	**/
		public function pointfinder_minvoices_meta_box_orderinfo( $post ) {

			$prderinfo_itemid = get_post_meta( $post->ID, 'pointfinder_invoice_packageid', true );
			$inv_prefix = $this->PFASSIssetControl('setup_invoices_prefix','','PFI');
			$current_post_status = get_post_status();

			if($current_post_status == 'publish'){
			    $prderinfo_statusorder = '<span class="pforders-orderdetails-lblcompleted">'.esc_html__('PUBLISH','pointfindercoreelements').'</span>';
			}elseif($current_post_status == 'pendingpayment'){
				$prderinfo_statusorder = '<span class="pforders-orderdetails-lblpending">'.esc_html__('PENDING PAYMENT','pointfindercoreelements').'</span>';
			}
			$itemnamex = get_the_title($prderinfo_itemid);

			$itemname = ($itemnamex!= false)? $itemnamex:esc_html__('Plan Deleted','pointfindercoreelements');

			echo '<ul class="pforders-orderdetails-ul">';
				echo '<li>';
				esc_html_e( 'INVOICE ID : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.$inv_prefix.get_the_id().'</div>';
				echo '</li> ';

				if (!empty($prderinfo_statusorder) && !isset($_GET['oa'])) {
					echo '<li>';
					esc_html_e( 'INVOICE STATUS : ', 'pointfindercoreelements' );
					echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_statusorder.'</div>';
					echo '</li> ';
				}
				

			echo '</ul>';
		}
	/**
	*End : Invoice Info Content
	**/

	/**
	*Start : Invoice Detail Content
	**/
		public function pointfinder_minvoices_meta_box_orderprocess( $post ) {
			echo '<ul class="pforders-orderdetails-ul">';

				global $wpdb;
				$post_author = $wpdb->get_var($wpdb->prepare("SELECT post_author FROM $wpdb->posts WHERE post_type = %s and ID = %d",'pointfinderinvoices',$post->ID));

				$user = get_user_by( 'id', $post_author );

				echo '<li>';
				esc_html_e( 'USER : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext"><a href="'.get_edit_user_link($post_author).'" target="_blank" title="'.esc_html__('Click for user details','pointfindercoreelements').'">'.$user->nickname.'('.$post_author.')</a></div>';
				echo '</li> ';


				echo '<li>';
				esc_html_e( 'AMOUNT : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.get_post_meta( $post->ID, 'pointfinder_invoice_amount', true ).'</div>';
				echo '</li> ';

				$orderid = get_post_meta( $post->ID, 'pointfinder_invoice_orderid', true );

				if (!empty($orderid)) {
					echo '<li>';
					esc_html_e( 'ORDER ID : ', 'pointfindercoreelements' );
					echo '<div class="pforders-orderdetails-lbltext"><a href="'.get_edit_post_link($orderid).'">'.get_the_title($orderid).'</a></div>';
					echo '</li> ';
				}

				

				echo '<li>';
				esc_html_e( 'TYPE : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.get_post_meta( $post->ID, 'pointfinder_invoice_invoicetype', true ).'</div>';
				echo '</li> ';

				$invoice_itemid = get_post_meta( $post->ID, 'pointfinder_invoice_itemid', true );
				$invoice_packid = get_post_meta( $post->ID, 'pointfinder_invoice_packageid', true );

				if (!empty($invoice_itemid)) {
					echo '<li>';
					esc_html_e( 'INVOICE ITEM : ', 'pointfindercoreelements' );
					echo '<div class="pforders-orderdetails-lbltext"><a href="'.get_edit_post_link($invoice_itemid).'">'.$invoice_itemid.'-'.get_the_title($invoice_itemid).'</a></div>';
					echo '</li> ';
				}

				if (!empty($invoice_packid)) {
					echo '<li>';
					esc_html_e( 'INVOICE PLAN : ', 'pointfindercoreelements' );
					echo '<div class="pforders-orderdetails-lbltext"><a href="'.get_edit_post_link($invoice_packid).'">'.get_the_title($invoice_packid).'</a></div>';
					echo '</li> ';
				}

				echo '<li>';
				esc_html_e( 'DATE : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.$this->PFU_DateformatS(get_post_meta( $post->ID, 'pointfinder_invoice_date', true ),1).'</div>';
				echo '</li> ';
				

			echo '</ul>';
		}
	/**
	*End : Invoice Detail Content
	**/
}