<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.derethor.net
 * @since      1.0.0
 *
 * @package    Mejorcluster
 * @subpackage Mejorcluster/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mejorcluster
 * @subpackage Mejorcluster/admin
 * @author     Javier Loureiro <derethor@gmail.com>
 */
class Mejorcluster_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mejorcluster_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mejorcluster_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mejorcluster-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mejorcluster_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mejorcluster_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mejorcluster-admin.js', array( 'jquery' ), $this->version, false );

	}

  public function add_custom_box_html($post)
  {
  $stored_meta = get_post_meta( $post->ID );

  // form security
  wp_nonce_field( 'mejorcluster-metabox-submit' , 'mejorcluster-metabox-nonce' );
?>
<table>
<tr><td><label for="mejorcluster-title">Title:</td><td><input type="text" size=60 name="mejorcluster-title" value="<?php
  if ( isset ( $stored_meta['mejorcluster-title'] ) ){ echo $stored_meta['mejorcluster-title'][0]; }
?>" /></td></tr>
<tr><td><label for="mejorcluster-desc">Description:</td><td><input type="text" size=120 name="mejorcluster-desc" value="<?php
  if ( isset ( $stored_meta['mejorcluster-desc'] ) ){ echo $stored_meta['mejorcluster-desc'][0]; }
?>" /></td></tr>

<tr><td><label for="mejorcluster-image">Image</label></td>
<td>
<p><img style="max-width:200px;height:auto;" id="mejorcluster-image-preview" src="<?php
  if ( isset ( $stored_meta['mejorcluster-image'] ) ){ echo $stored_meta['mejorcluster-image'][0]; }
?>" /></p>
<p><input type="text" size=120 name="mejorcluster-image" id="mejorcluster-image" value="<?php
  if ( isset ( $stored_meta['mejorcluster-image'] ) ){ echo $stored_meta['mejorcluster-image'][0]; }
?>" /></p>
<p><input type="button" id="mejorcluster-image-button" class="button" value="Choose or Upload an Image" /></p>
</td></tr>

</table>

<script>
  jQuery('#mejorcluster-image-button').click(function() {
    var send_attachment_bkp = wp.media.editor.send.attachment;
    wp.media.editor.send.attachment = function(props, attachment) {
      jQuery('#mejorcluster-image').val(attachment.url);
      jQuery('#mejorcluster-image-preview').attr('src',attachment.url);
      wp.media.editor.send.attachment = send_attachment_bkp;
    }
    wp.media.editor.open();
    return false;
  });
</script>

<?php
  }

  public function save_meta_box( $post_id )
  {
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'mejorcluster-metabox-nonce' ] ) && wp_verify_nonce( $_POST[ 'mejorcluster-metabox-nonce' ], 'mejorcluster-metabox-submit' ) ) ? 'true' : 'false';

    $post = get_post ($post_id);
    if (!$post) return $post_id;

    $post_type = get_post_type_object( $post->post_type );
    $is_valid_user = current_user_can( $post_type->cap->edit_post, $post_id );

    if ( !$is_valid_user || $is_autosave || $is_revision || !$is_valid_nonce  ) {
      return $post_id;
    }

    if( isset( $_POST[ 'mejorcluster-title' ] ) ) {
      update_post_meta( $post_id, 'mejorcluster-title', sanitize_text_field($_POST[ 'mejorcluster-title' ]) );
    }
    if( isset( $_POST[ 'mejorcluster-desc' ] ) ) {
      update_post_meta( $post_id, 'mejorcluster-desc', sanitize_text_field($_POST[ 'mejorcluster-desc' ]) );
    }
    if( isset( $_POST[ 'mejorcluster-image' ] ) ) {
      update_post_meta( $post_id, 'mejorcluster-image', $_POST[ 'mejorcluster-image' ] );
    }

    return $post_id;
  }

  public function add_custom_box()
  {
      add_meta_box(
        'mejorcluster_box_id',                // Unique ID
        'Mejor Cluster',                      // Box title
        array($this,'add_custom_box_html'),   // Content callback, must be of type callable
        '',                                   // Post type
        'normal',
        'default'
      );
  }

	public function add_admin_menu() {
		add_options_page( 'mejorcluster', __('El mejor cluster','mejorcluster'), 'manage_options', 'mejorcluster', array($this,'render_options_page') );
	}

	public function settings_init() {

		register_setting( 'pluginPage', 'mejorcluster_settings' );

		add_settings_section(
			'mejorcluster_pluginPage_section',
			__( 'Opciones del mejor cluster', 'mejorcluster' ),
			array( $this,'settings_section_callback'),
			'pluginPage'
		);

		add_settings_field(
			'mejorcluster_enabled',
			__( 'Enabled', 'mejorcluster' ),
			array($this,'settings_checkbox_enabled_render'),
			'pluginPage',
			'mejorcluster_pluginPage_section'
		);

		add_settings_field(
			'mejorcluster_round',
			__( 'Round Border', 'mejorcluster' ),
			array($this,'settings_checkbox_round_render'),
			'pluginPage',
			'mejorcluster_pluginPage_section'
		);

		add_settings_field(
			'mejorcluster_shadow',
			__( 'Shadow Border', 'mejorcluster' ),
			array($this,'settings_checkbox_shadow_render'),
			'pluginPage',
			'mejorcluster_pluginPage_section'
		);

		add_settings_field(
			'mejorcluster_skip_title',
			__( 'Skip Title', 'mejorcluster' ),
			array($this,'settings_checkbox_skip_title_render'),
			'pluginPage',
			'mejorcluster_pluginPage_section'
		);

		add_settings_field(
			'mejorcluster_skip_title_link',
			__( 'Skip Title Link', 'mejorcluster' ),
			array($this,'settings_checkbox_skip_title_link_render'),
			'pluginPage',
			'mejorcluster_pluginPage_section'
		);

		add_settings_field(
			'mejorcluster_skip_desc',
			__( 'Skip Description', 'mejorcluster' ),
			array($this,'settings_checkbox_skip_desc_render'),
			'pluginPage',
			'mejorcluster_pluginPage_section'
		);

		add_settings_field(
			'mejorcluster_skip_image',
			__( 'Skip Image', 'mejorcluster' ),
			array($this,'settings_checkbox_skip_image_render'),
			'pluginPage',
			'mejorcluster_pluginPage_section'
		);

		add_settings_field(
			'mejorcluster_skip_image_link',
			__( 'Skip Image Link', 'mejorcluster' ),
			array($this,'settings_checkbox_skip_image_link_render'),
			'pluginPage',
			'mejorcluster_pluginPage_section'
		);

		add_settings_field(
			'mejorcluster_grid',
			__( 'Grid Columns', 'mejorcluster' ),
			array($this,'settings_text_grid_render'),
			'pluginPage',
			'mejorcluster_pluginPage_section'
		);

		add_settings_field(
			'mejorcluster_maxitems',
			__( 'Max Items', 'mejorcluster' ),
			array($this,'settings_text_maxitems_render'),
			'pluginPage',
			'mejorcluster_pluginPage_section'
		);

		add_settings_field(
			'mejorcluster_orderby',
			__( 'Order By', 'mejorcluster' ),
			array($this,'settings_text_orderby_render'),
			'pluginPage',
			'mejorcluster_pluginPage_section'
		);

	}

	private function settings_text_render( $name , $default_value ) {
		$options = get_option( 'mejorcluster_settings' );
    $kname= 'mejorcluster_' . $name;
    $value = $default_value;
    if (isset ($options) && isset($options[$kname]) ) $value=$options[$kname];
		echo "<input type='text' name='mejorcluster_settings[$kname]' value='$value' >";
	}

  private function settings_checkbox_render ( $name , $default_value ) {
		$options = get_option( 'mejorcluster_settings' );
    $kname = 'mejorcluster_' . $name;

    if (isset ($options)) {
      if ( isset($options[$kname]) ) { $value=1; } else { $value=0; }
    } else {
      $value = $default_value;
    }

		echo "<input type='checkbox' name='mejorcluster_settings[$kname]' "; checked($value,1); echo " value='1'>";
  }

	public function settings_checkbox_enabled_render(  ) { $this->settings_checkbox_render ('enabled',1); }
	public function settings_checkbox_round_render(  ) { $this->settings_checkbox_render ('round',1); }
	public function settings_checkbox_shadow_render(  ) { $this->settings_checkbox_render ('shadow',1); }
	public function settings_checkbox_skip_title_render(  ) { $this->settings_checkbox_render ('skip_title',0); }
	public function settings_checkbox_skip_title_link_render(  ) { $this->settings_checkbox_render ('skip_title_link',1); }
	public function settings_checkbox_skip_image_render(  ) { $this->settings_checkbox_render ('skip_image',0); }
	public function settings_checkbox_skip_image_link_render(  ) { $this->settings_checkbox_render ('skip_image_link',0); }
	public function settings_checkbox_skip_desc_render(  ) { $this->settings_checkbox_render ('skip_desc',0); }
	public function settings_text_grid_render( ) { $this->settings_text_render ('grid', '3' ); }
	public function settings_text_maxitems_render( ) { $this->settings_text_render ('maxitems', '9' ); }
	public function settings_text_orderby_render( ) { $this->settings_text_render ('orderby', 'title' ); }

	public function settings_section_callback(  ) {
		// this is called when the page is displayed
	}

	public function render_options_page(  ) {
			?>
			<form action='options.php' method='post'>

				<?php
				settings_fields( 'pluginPage' );
				do_settings_sections( 'pluginPage' );
				submit_button();
				?>

			</form>
			<?php
	}


}
