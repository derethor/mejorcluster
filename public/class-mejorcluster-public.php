<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.derethor.net
 * @since      1.0.0
 *
 * @package    Mejorcluster
 * @subpackage Mejorcluster/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Mejorcluster
 * @subpackage Mejorcluster/public
 * @author     Javier Loureiro <derethor@gmail.com>
 */
class Mejorcluster_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mejorcluster.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mejorcluster-public.js', array( 'jquery' ), $this->version, false );

	}

  public function do_shortcode_cluster($atts) {

		$options = get_option( 'mejorcluster_settings' );
    $enabled = gb ($options,'enabled','yes');
    if ($enabled !='yes') return '';

    extract(shortcode_atts(array(
      'round' => gb ($options,'round','yes'),
      'shadow' => gb ($options,'shadow','yes'),
      'title_tag' => gs ($options,'title_tag','h5'),
      'desc_tag' => gs ($options,'desc_tag','p'),
      'skip_title' => gb ($options,'skip_title','no'),
      'skip_title_link' => gb ($options,'skip_title_link','no'),
      'skip_desc' => gb ($options,'skip_desc','no'),
      'skip_image' => gb ($options,'skip_image','no'),
      'skip_image_link' => gb ($options,'skip_image_link','no'),
      'grid' => gs($options,'grid','3'),
      'maxitems' => gs($options,'maxitems','9'),
      'orderby' => gs($options,'orderby','title'),
      'posts' => '',
      'exclude' => '',
      'parent' => '',
      'categories' => '',
      'tags' => '',
      'classname' => '',
    ), $atts));

    $shadow= $shadow == 'yes';
    $round = $round == 'yes';

    $skip_title = $skip_title == 'yes';
    $skip_title_link = $skip_title_link == 'yes';
    $skip_image = $skip_image == 'yes';
    $skip_image_link = $skip_image_link == 'yes';
    $skip_desc = $skip_desc == 'yes';

    $postsarray = array_map('intval', explode(',', $posts));
    $excldarray = array_map('intval', explode(',', $exclude));

    $pararray = array_map('intval', explode(',', $parent));
    $catarray = array_map('intval', explode(',', $categories));
    $tagarray = array_map('intval', explode(',', $tags));

    // get page list
    if($posts!= '') { // by post list
      $the_query = array(
        'post__in' => $postsarray,
        'post_type' => 'any',
        'post__not_in' => $excldarray,
        'posts_per_page' => $maxitems,
        'orderby'        => $orderby,
      );
    } elseif( $parent!= '') { // by parent
      $the_query = array(
        'post_parent__in' => $pararray,
        'post_type' => 'any',
        'post__not_in' => $excldarray,
        'posts_per_page' => $maxitems,
        'orderby'        => $orderby,
      );
    } elseif( $categories!= '') { // by category
      $the_query = array(
        'category__in' => $catarray,
        'post_type' => 'any',
        'post__not_in' => $excldarray,
        'posts_per_page' => $maxitems,
        'orderby'        => $orderby,
      );
    } elseif( $tags!= '') { // by tag
      $the_query = array(
        'tag__in' => $tagarray,
        'post_type' => 'any',
        'post__not_in' => $excldarray,
        'posts_per_page' => $maxitems,
        'orderby'        => $orderby,
      );
    } else { // by own category
      $categories = get_the_category($post->ID);
      $category_id = $categories[0]->cat_ID;
      $the_query = array(
        'cat' => $category_id,
        'post_type' => 'any',
        'post__not_in' => $excldarray,
        'posts_per_page' => $maxitems,
        'orderby'        => $orderby,
      );
    };

    $cssclass = 'mejorcluster';

    if ($classname != '') $cssclass .= " $classname";

    $cssclass.= sprintf ( ' mejorcluster-grid-%d' , $grid );

    if($shadow) {
      $cssclass.= ' mejorcluster-shadow';
    }

    if($round) {

      if($skip_title) {
        $cssclass.= ' mejorcluster-round-image';
      } else {
        $cssclass.= ' mejorcluster-round';
      };
    }

    if(!$skip_image) {
      $cssclass.= ' mejorcluster-display-image';
    } else {
      $cssclass.= ' mejorcluster-display-text';
    }

    // start output

    $output = '';
    $output .= "<div class='$cssclass'>";

    query_posts($the_query);
    global $post;
    if (have_posts()) : while (have_posts()) : the_post();

    $stored_meta = get_post_meta($post->ID);

    // Title
    if ( isset ( $stored_meta['mejorcluster-title'] ) && strlen($stored_meta['mejorcluster-title'][0]) > 0 ) {
      $title = $stored_meta['mejorcluster-title'][0];
    } else {
      $title = get_the_title($post->ID);
    };
    $the_title = wp_trim_words( $title, 6 );

    // Description
    if ( isset ( $stored_meta['mejorcluster-desc'] ) && strlen($stored_meta['mejorcluster-desc'][0]) > 0 ) {
      $content = $stored_meta['mejorcluster-desc'][0];
    } else {
      $content = get_the_content($post->ID);
    };
    $the_content = wp_trim_words( $content, 10 );

    // Image
    if ( isset ( $stored_meta['mejorcluster-image'] ) && strlen($stored_meta['mejorcluster-image'][0]) > 0 ) {
      $the_thumb = $stored_meta['mejorcluster-image'][0];
    } else {
      $the_thumb = get_the_post_thumbnail_url($post->ID);
    }

    // Permalink
    $the_link = get_permalink($post->ID);

    // create the cluster item
    $output .= "<article class='mejorcluster-item'>";

      if (!$skip_image)
      {
        $output .= "<header class='mejorcluster-item-header'>";
        if (!$skip_image_link) $output .=   "<a href='$the_link' rel='bookmark class='mejorcluster-image-link'>";
        $output .=     "<figure class='mejorcluster-figure'><img src='$the_thumb' class='mejorcluster-image' alt='$the_title' /></figure>";
        if (!$skip_image_link) $output .=   "</a>";
        $output .= "</header>";
      }

      if (! ($skip_title && $skip_desc) ) $output .= '<div class="mejorcluster-item-text">';

        if(!$skip_title)
        {
          $output .= "<$title_tag class='mejorcluster-title'>";
          if (!$skip_title_link) $output .= "<a href='$the_link' rel='bookmark' class='mejorcluster-title-link'>";
          $output .= $the_title;
          if (!$skip_title_link) $output .= "</a>";
          $output .= "</$title_tag>";
        }

        if(!$skip_desc)
        {
          $output .= "<$desc_tag class='mejorcluster-desc'>$the_content</$desc_tag>";
        }

      if (! ($skip_title && $skip_desc) ) $output .= '</div>';

    $output .= "</article>";

  endwhile; else:
  endif;

  $output .= "</div>";

  wp_reset_query();

  return $output;
  }
}

function gs ( $options , $name , $default_value )
{
  $kname = 'mejorcluster_' . $name;
  if ( isset ($options) )
  {
    if ( array_key_exists ($kname,$options) ) { return $options[$kname]; } else { return $default_value; }
  } else {
    return $default_value;
  }
}

function gb ( $options , $name , $default_value )
{
  $kname = 'mejorcluster_' . $name;
  if ( isset ($options) )
  {
    if ( array_key_exists ($kname,$options) ) { return 'yes'; } else { return 'no'; }
  } else {
    return $default_value;
  }
}

