<?php

//Plugin Name: Toolbar Notepad

$toolbar_notepad = new Toolbar_Notepad();

class Toolbar_Notepad {

	function __construct() {
		add_action( 'init', array( &$this, 'init' ) );
	}
	function init() {
		if ( ! is_user_logged_in() ) return;

		$this->user = get_current_user_id();
		$this->username = get_userdata( $this->user )->display_name;
		$this->notes = get_user_meta( $this->user, 'notepad', true );

		add_action( 'admin_bar_menu', array( &$this, 'admin_bar_menu'), 100 );

		add_action( 'wp_footer', array( &$this, 'notepad_markup') );
		add_action( 'admin_footer', array( &$this, 'notepad_markup') );

		add_action( 'wp_enqueue_scripts', array( &$this, 'scripts' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'scripts' ) );

		add_action( 'wp_ajax_save_notes', array( &$this, 'save_notes_cb' ) );
	}

	function admin_bar_menu( $wp_admin_bar ) {

		$wp_admin_bar->add_menu( array(
			'id' => 'toolbar-notepad',
			'title' => __('Notepad'),
			'href' => '#',
		) );

	}

	function notepad_markup() {
		?>
		<div class="tbnp-page-wrap">
			<div class="tbnp-notepad">
				<p>
				<button class="edit">Edit</button> <button class="save" style="display:none">Save</button>
				Notes for <?php echo $this->username; ?>
				</p>
				<textarea name="toolbar-notes" readonly='readonly'><?php
				echo $this->notes;
				?></textarea>
			</div>
		</div>
		<?php
	}

	function scripts() {
		if ( ! is_user_logged_in() ) return;

		wp_enqueue_script('jquery');
		wp_enqueue_script('toolbar-notepad', plugins_url('toolbar-notepad.js', __FILE__ ), array('jquery') );
		wp_localize_script('toolbar-notepad', 'tbnp', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'loading' => admin_url('images/loading.gif'),
			'nonce' => wp_create_nonce( 'save-user-notes' )
		) );
		wp_enqueue_style('toolbar-notepad', plugins_url('toolbar-notepad.css', __FILE__ ) );
	}

	function save_notes_cb() {
		check_ajax_referer( 'save-user-notes', 'nonce' );
		$notes = strip_tags( $_POST['notes'] );

		if ( update_user_meta( $this->user, 'notepad', $notes ) )
			die( 'updated' );

	}

}