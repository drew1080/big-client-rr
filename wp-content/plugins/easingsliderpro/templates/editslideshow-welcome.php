<?php

/** Display the panel */
if ( get_option( 'easingsliderpro_disable_welcome_panel' ) == false ) :

    /** URL references */
    $links = array(
        'get-started' => 'http://easingslider.com/docs/installation',
        'display-slideshow' => 'http://easingslider.com/docs/displaying-slideshows',
        'faqs' => 'http://easingslider.com/faqs',
        'support' => 'http://easingslider.com/support',
    );

?>
<div id="easingsliderpro-welcome-message" class="welcome-panel">
    <?php
        /** Before actions */
        do_action( 'easingsliderpro_welcome_panel_before' );
    ?>
    
    <a href="admin.php?page=easingsliderpro_edit_slideshows&amp;disable_welcome_panel=true" class="welcome-panel-close"><?php _e( 'Dismiss', 'easingsliderpro' ); ?></a>
    <div class="welcome-panel-content">
        <h3><?php _e( 'Welcome to Easing Slider "Pro"', 'easingsliderpro' ); ?></h3>
        <p class="about-description">
            <?php _e( 'Thanks for installing Easing Slider "Pro". Here are some links to help get you started.', 'easingsliderpro' ); ?>
        </p>
        <div class="welcome-panel-column-container">
            <div class="welcome-panel-column">
                <h4><?php _e( 'Get Started', 'easingsliderpro' ); ?></h4>
                <a class="button button-primary button-hero" href="<?php echo $links['get-started']; ?>"><?php _e( 'View the Documentation', 'easingsliderpro' ); ?></a>
            </div>

            <div class="welcome-panel-column">
                <h4><?php _e( 'Need some help?', 'easingsliderpro' ); ?></h4>
                <ul>
                    <li><a href='<?php echo $links['display-slideshow']; ?>'><?php _e( 'Displaying a Slideshow', 'easingsliderpro' ); ?></a></li>
                    <li><a href='<?php echo $links['faqs']; ?>'><?php _e( 'Frequently Asked Questions', 'easingsliderpro' ); ?></a></li>
                    <li><a href='<?php echo $links['support']; ?>'><?php _e( 'Help & Support', 'easingsliderpro' ); ?></a></li>
                </ul>
            </div>
        </div>
    </div>

    <?php
        /** After actions */
        do_action( 'easingsliderpro_welcome_panel_after' );
    ?>
</div>
<?php endif; ?>