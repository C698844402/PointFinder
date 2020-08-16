<?php

if (trait_exists('PointFinderPageNotFound')) {
  return;
}


/**
 * Page not Found
 */
trait PointFinderPageNotFound
{
  public function PFPageNotFound(){
		?>
		<section role="main">
					<div class="pf-container">
							<div class="pf-row">
									<div class="col-lg-12">

											<form method="get" class="form-search" action="<?php echo esc_url(home_url("/")); ?>" data-ajax="false">
											<div class="pf-notfound-page animated flipInY">
													<h3><?php esc_html_e( 'Sorry!', 'pointfindercoreelements' ); ?></h3>
													<h4><?php esc_html_e( 'Nothing found...', 'pointfindercoreelements' ); ?></h4><br>
													<p class="text-lightblue-2"><?php esc_html_e( 'You better try to search', 'pointfindercoreelements' ); ?>:</p>
													<div class="row">
															<div class="pfadmdad input-group col-sm-4 col-sm-offset-4">
																	<i class="pfadmicon-glyph-386"></i>
																	<input type="text" name="s" class="form-control" onclick="this.value='';"  onfocus="if(this.value==''){this.value=''};" onblur="if(this.value==''){this.value=''};" value="<?php esc_html_e( 'Search', 'pointfindercoreelements' ); ?>">
																	<span class="input-group-btn">
																			<button onc class="btn btn-success" type="submit"><?php esc_html_e( 'Search', 'pointfindercoreelements' ); ?></button>
																		</span>
															</div>
													</div><br>
													<a class="btn btn-primary btn-sm" href="<?php echo esc_url(home_url("/")); ?>"><i class="pfadmicon-glyph-857"></i><?php esc_html_e( 'Return Home', 'pointfindercoreelements' ); ?></a>
											</div>
											</form>

									</div>
							</div>
					</div>
			</section>
		<?php
	}
}
