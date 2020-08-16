        <?php 
        if (!is_page_template('pf-empty-page.php' ) && !is_page_template('terms-conditions.php' )) {
        	if(!class_exists('Pointfindercoreelements')){
    		?>
			<div class="pf-mobile-up-button">
	            <?php 
	            $setup4_membersettings_loginregister = PFSAIssetControl('setup4_membersettings_loginregister','','0');
	            if ($setup4_membersettings_loginregister == 1) {
	            ?>
	            	<a title="<?php echo esc_html__('User Menu','pointfindercoreelements'); ?>" class="pf-up-but pf-up-but-umenu"><i class="fas fa-user"></i></a>
	            <?php 
	            }
	            ?>
	            <a title="<?php echo esc_html__('Menu','pointfindercoreelements'); ?>" class="pf-up-but pf-up-but-menu"><i class="fas fa-bars"></i></a>
	            <a title="<?php echo esc_html__('Back to Top','pointfindercoreelements'); ?>" class="pf-up-but pf-up-but-up"><i class="fas fa-angle-up"></i></a>
	            <a title="<?php echo esc_html__('Open','pointfindercoreelements'); ?>" class="pf-up-but pf-up-but-el"><i class="fas fa-ellipsis-h"></i></a>
            </div>
            <div class="pf-desktop-up-button">
                <a title="<?php echo esc_html__('Back to Top','pointfindercoreelements'); ?>" class="pf-up-but pf-up-but-up"><i class="fas fa-angle-up"></i></a>
            </div>
            <?php
	    	}
	        ?>
	        </div>
	        </div>
        <?php
    	}
        ?>
		<?php wp_footer();?>
	</body>
</html>
