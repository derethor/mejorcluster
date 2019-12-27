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
      update_post_meta( $post_id, 'mejorcluster-image', sanitize_text_field($_POST[ 'mejorcluster-image' ]) );
    }

    return $post_id;
  }

  public function add_custom_box()
  {
    $options = get_option( 'mejorcluster_settings' );
    $name = 'mejorcluster_hide_metaeditor';
    if ( is_array ($options) and ( array_key_exists ( $name , $options) ) ) return;

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

		register_setting( 'mejorcluster_options', 'mejorcluster_settings' );

		add_settings_section(
			'mejorcluster_options_section',
			__( 'Mejor Cluster Settings', 'mejorcluster' ),
			array( $this,'settings_section_callback'),
			'mejorcluster_options'
		);

		add_settings_field(
			'mejorcluster_enabled',
			__( 'Enabled', 'mejorcluster' ),
			array($this,'settings_checkbox_enabled_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

		add_settings_field(
			'mejorcluster_hide_metaeditor',
			__( 'Hide Custom Box on Posts Editor', 'mejorcluster' ),
			array($this,'settings_checkbox_hide_metaeditor_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

		add_settings_field(
			'mejorcluster_includecss',
			__( 'Dont Include CSS stylesheet', 'mejorcluster' ),
			array($this,'settings_checkbox_skipcss_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

		add_settings_field(
			'mejorcluster_round',
			__( 'Round Border', 'mejorcluster' ),
			array($this,'settings_checkbox_round_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

		add_settings_field(
			'mejorcluster_shadow',
			__( 'Shadow Border', 'mejorcluster' ),
			array($this,'settings_checkbox_shadow_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

		add_settings_field(
			'mejorcluster_skip_title',
			__( 'Skip Title', 'mejorcluster' ),
			array($this,'settings_checkbox_skip_title_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

		add_settings_field(
			'mejorcluster_skip_title_link',
			__( 'Skip Title Link', 'mejorcluster' ),
			array($this,'settings_checkbox_skip_title_link_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

		add_settings_field(
			'mejorcluster_skip_desc',
			__( 'Skip Description', 'mejorcluster' ),
			array($this,'settings_checkbox_skip_desc_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

		add_settings_field(
			'mejorcluster_skip_image',
			__( 'Skip Image', 'mejorcluster' ),
			array($this,'settings_checkbox_skip_image_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

		add_settings_field(
			'mejorcluster_skip_image_link',
			__( 'Skip Image Link', 'mejorcluster' ),
			array($this,'settings_checkbox_skip_image_link_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

		add_settings_field(
			'mejorcluster_imagesize',
			__( 'Image Size', 'mejorcluster' ),
			array($this,'settings_list_imagesize_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

		add_settings_field(
			'mejorcluster_grid',
			__( 'Grid Columns', 'mejorcluster' ),
			array($this,'settings_text_grid_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

		add_settings_field(
			'mejorcluster_maxitems',
			__( 'Max Items', 'mejorcluster' ),
			array($this,'settings_text_maxitems_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

		add_settings_field(
			'mejorcluster_orderby',
			__( 'Order By', 'mejorcluster' ),
			array($this,'settings_text_orderby_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

		add_settings_field(
			'mejorcluster_title_tag',
			__( 'Title Tag', 'mejorcluster' ),
			array($this,'settings_text_title_tag_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

		add_settings_field(
			'mejorcluster_desc_tag',
			__( 'Desc Tag', 'mejorcluster' ),
			array($this,'settings_text_desc_tag_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

		add_settings_field(
			'mejorcluster_title_maxwords',
			__( 'Title Max number of words', 'mejorcluster' ),
			array($this,'settings_text_title_maxwords_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

		add_settings_field(
			'mejorcluster_desc_maxwords',
			__( 'Description Max number of words', 'mejorcluster' ),
			array($this,'settings_text_desc_maxwords_render'),
			'mejorcluster_options',
			'mejorcluster_options_section'
		);

	}

	private function settings_text_render( $name , $default_value ) {
		$options = get_option( 'mejorcluster_settings' );
    $kname= 'mejorcluster_' . $name;
    $value = $default_value;
    if (is_array ($options) && isset($options[$kname]) ) $value=$options[$kname];
    $value = esc_html ($value);
		echo "<input type='text' name='mejorcluster_settings[$kname]' value='$value' >";
	}

  private function settings_checkbox_render ( $name , $default_value ) {
		$options = get_option( 'mejorcluster_settings' );
    $kname = 'mejorcluster_' . $name;

    if (is_array ($options)) {
      if ( isset($options[$kname]) ) { $value=1; } else { $value=0; }
    } else {
      $value = $default_value;
    }

		echo "<input type='checkbox' name='mejorcluster_settings[".esc_attr($kname)."]' "; checked($value,1); echo " value='1'>";
  }

  private function settings_list_render ( $name ,  $values , $default_value ) {

    if (!is_array($values)) return;

		$options = get_option( 'mejorcluster_settings' );
    $kname = 'mejorcluster_' . $name;

    $selected = $default_value;
    if (is_array ($options) && isset($options[$kname]) ) $selected=$options[$kname];

    echo "<select name='mejorcluster_settings[" . esc_attr($kname) . "]'>";
    foreach ($values as $value)
    {
      echo "<option value=".esc_attr($value);
      if ( $selected == $value ) echo " selected ";
      echo ">".esc_html($value)."</option>";
    }
    echo "</select>";

  }

	public function settings_checkbox_enabled_render(  ) { $this->settings_checkbox_render ('enabled',1); }
	public function settings_checkbox_hide_metaeditor_render(  ) { $this->settings_checkbox_render ('hide_metaeditor',0); }
	public function settings_checkbox_skipcss_render(  ) { $this->settings_checkbox_render ('skipcss',0); }
	public function settings_checkbox_round_render(  ) { $this->settings_checkbox_render ('round',1); }
	public function settings_checkbox_shadow_render(  ) { $this->settings_checkbox_render ('shadow',1); }
	public function settings_checkbox_skip_title_render(  ) { $this->settings_checkbox_render ('skip_title',0); }
	public function settings_checkbox_skip_title_link_render(  ) { $this->settings_checkbox_render ('skip_title_link',1); }
	public function settings_checkbox_skip_image_render(  ) { $this->settings_checkbox_render ('skip_image',0); }
	public function settings_checkbox_skip_image_link_render(  ) { $this->settings_checkbox_render ('skip_image_link',0); }
	public function settings_checkbox_skip_desc_render(  ) { $this->settings_checkbox_render ('skip_desc',0); }
	public function settings_list_imagesize_render( ) { $this->settings_list_render ('imagesize',get_intermediate_image_sizes(),'medium' ); }
	public function settings_text_grid_render( ) { $this->settings_text_render ('grid', '3' ); }
	public function settings_text_maxitems_render( ) { $this->settings_text_render ('maxitems', '9' ); }
	public function settings_text_orderby_render( ) { $this->settings_text_render ('orderby', 'title' ); }
	public function settings_text_title_tag_render( ) { $this->settings_text_render ('title_tag', 'h5' ); }
	public function settings_text_desc_tag_render( ) { $this->settings_text_render ('desc_tag', 'p' ); }
	public function settings_text_title_maxwords_render( ) { $this->settings_text_render ('title_maxwords', '6' ); }
	public function settings_text_desc_maxwords_render( ) { $this->settings_text_render ('desc_maxwords', '10' ); }

	public function settings_section_callback(  ) {
		// this is called when the page is displayed
	}

	public function render_options_page(  ) {
			?>
			<form action='options.php' method='post'>

				<?php
				settings_fields( 'mejorcluster_options' );
				do_settings_sections( 'mejorcluster_options' );
				submit_button();
				?>

			</form>
			<?php
	}


}
