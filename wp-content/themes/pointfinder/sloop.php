<article id="post-<?php the_ID(); ?>" <?php post_class('pointfinder-post'); ?>>

    <?php
    pf_singlepost_thumbnail();
    pf_singlepost_title();

    echo '<div class="post-content">';

    if (!class_exists('Pointfindercoreelements')) {
        the_content();
    }else{
        do_action('pointfinder_gallery_post_action');
    }
    

    echo '</div>';
    $defaults = array(
        'before'           => '<p>' . esc_html__( 'Pages:', 'pointfinder' ),
        'after'            => '</p>',
        'link_before'      => '',
        'link_after'       => '',
        'next_or_number'   => 'number',
        'separator'        => ' ',
        'nextpagelink'     => esc_html__( 'Next page', 'pointfinder' ),
        'previouspagelink' => esc_html__( 'Previous page', 'pointfinder' ),
        'pagelink'         => '%',
        'echo'             => 1
    );

    wp_link_pages( array(
    'before'      => '<div class="pf-page-links"><span class="pf-page-links-title">' . __( 'Pages:', 'pointfinder' ) . '</span>',
    'after'       => '</div>',
    'link_before' => '<span>',
    'link_after'  => '</span>',
    ) );
    

    pf_singlepost_info();
    pf_singlepost_comments();
    ?>

</article>
