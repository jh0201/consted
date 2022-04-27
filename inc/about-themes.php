<?php

/*Add theme menu page*/
 
add_action('admin_menu', 'consted_admin_menu');

function consted_admin_menu() {
	
	$consted_page_title = esc_html__("Learn More Consted",'consted');
	
	$consted_menu_title = esc_html__("Learn More Consted",'consted');
	
	add_theme_page($consted_page_title, $consted_menu_title, 'edit_theme_options', 'consted_theme_info', 'consted_theme_info_page');
	
}

/*
**
** Premium Theme Feature Page
**
*/

function consted_theme_info_page(){
	if ( is_admin() ) {
		get_template_part('/inc/premium-screen/index');
		
	} 
}

function consted_admin_script($consted_hook){
	
	if($consted_hook != 'appearance_page_consted_theme_info') {
		return;
	} 
	wp_enqueue_style( 'consted-custom-css', get_template_directory_uri() .'/inc/premium-screen/pro-custom.css',array(),'1.0' );

}

add_action( 'admin_enqueue_scripts', 'consted_admin_script' );



if ( ! class_exists( 'Consted_Admin' ) ) :

/**
 * consted_Admin Class.
 */
class Consted_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_loaded', array( __CLASS__, 'hide_notices' ) );
		add_action( 'load-themes.php', array( $this, 'admin_notice' ) );
	}

	/**
	 * Add admin notice.
	 */
	public function admin_notice() {
		global $consted_pagenow;

		wp_enqueue_style( 'consted-message', get_template_directory_uri() . '/inc/premium-screen/message.css', array(), '1.0' );

		// Let's bail on theme activation.
		if ( 'themes.php' == $consted_pagenow && isset( $_GET['activated'] ) ) {
			add_action( 'admin_notices', array( $this, 'welcome_notice' ) );
			update_option( 'consted_admin_notice_welcome', 1 );

		// No option? Let run the notice wizard again..
		} elseif( ! get_option( 'consted_admin_notice_welcome' ) ) {
			add_action( 'admin_notices', array( $this, 'welcome_notice' ) );
		}
	}

	/**
	 * Hide a notice if the GET variable is set.
	 */
	public static function hide_notices() {
		if ( isset( $_GET['consted-hide-notice'] ) && isset( $_GET['_consted_notice_nonce'] ) ) {
			if ( ! wp_verify_nonce( wp_unslash($_GET['_consted_notice_nonce']), 'consted_hide_notices_nonce' ) ) {
				/* translators: %s: plugin name. */
				wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'consted' ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) 
			/* translators: %s: plugin name. */{
				wp_die( esc_html__( 'Cheatin&#8217; huh?', 'consted' ) );
			}

			$hide_notice = sanitize_text_field( wp_unslash( $_GET['consted-hide-notice'] ) );
			update_option( 'consted_admin_notice_' . $hide_notice, 1 );
		}
	}

	/**
	 * Show welcome notice.
	 */
	public function welcome_notice() {
		?>
		<div id="message" class="updated cresta-message">
        
			<a class="cresta-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( remove_query_arg( array( 'activated' ), add_query_arg( 'consted-hide-notice', 'welcome' ) ), 'consted_hide_notices_nonce', '_consted_notice_nonce' ) ); ?>"><?php  /* translators: %s: plugin name. */ esc_html_e( 'Dismiss', 'consted' ); ?></a>
            
			<p><?php printf( /* translators: %s: plugin name. */  esc_html__( 'Welcome! Thank you for choosing Consted! To fully take advantage of the best our theme can offer please make sure you visit our %1$sLearn More Page%2$s.', 'consted' ), '<a href="' . esc_url( admin_url( 'themes.php?page=consted_theme_info' ) ) . '">', '</a>' ); ?></p>
			<p class="submit">
				<a class="button-secondary" href="<?php echo esc_url( admin_url( 'themes.php?page=consted_theme_info' ) ); ?>"><?php esc_html_e( 'Learn More Consted', 'consted' ); ?></a>
			</p>
		</div>
		<?php
	}



	

	
}

endif;

return new Consted_Admin();




