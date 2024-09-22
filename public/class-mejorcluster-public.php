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

	private $plugin_name;
	private $version;
  private $global_options;

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
		$this->global_options = get_option( 'mejorcluster_settings' );
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

    $skipcss= gb ($this->global_options,'skipcss','no');

    if ($skipcss != 'yes') wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mejorcluster.css', array(), $this->version, 'all' );
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

    global $post;

		$options = $this->global_options;
    $enabled = gb ($options,'enabled','yes');
    if ($enabled !='yes') return '';

    extract(shortcode_atts(array(
      'round' => gb ($options,'round','yes'),
      'shadow' => gb ($options,'shadow','yes'),
      'title_tag' => gs ($options,'title_tag','h5'),
      'desc_tag' => gs ($options,'desc_tag','p'),
      'title_maxwords' => gs ($options,'title_maxwords','6'),
      'desc_maxwords' => gs ($options,'desc_maxwords','10'),
      'skip_title' => gb ($options,'skip_title','no'),
      'skip_title_link' => gb ($options,'skip_title_link','no'),
      'skip_desc' => gb ($options,'skip_desc','no'),
      'skip_image' => gb ($options,'skip_image','no'),
      'skip_image_link' => gb ($options,'skip_image_link','no'),
      'imagesize' => gs($options,'imagesize','medium'),
      'grid' => gs($options,'grid','3'),
      'maxitems' => gs($options,'maxitems','9'),
      'orderby' => gs($options,'orderby','title'),
      'meta' => gs($options,'meta',''),
      'posts' => '',
      'posts_names' => '',
      'category_name' => '',
      'exclude' => '',
      'parent' => '',
      'categories' => '',
      'category' => '',
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

    $title_maxwords = intval($title_maxwords);
    $desc_maxwords = intval($desc_maxwords);

    $postsarray = array_map('shortcode_map', explode(',', $posts) , [$post] );
    $postsnamesarray = array_map('sanitize_text_field', explode(',', $posts_names)  );
    $excldarray = array_map('shortcode_map', explode(',', $exclude) , [$post] );
    $pararray = array_map('shortcode_map' , explode(',', $parent) , [$post] ) ;
    $catarray = array_map('intval', explode(',', $categories));
    $category = intval ( sanitize_text_field( $category ) );
    $category_name = sanitize_text_field( $category_name );
    $tagarray = array_map('intval', explode(',', $tags));

    $metaarray = explode ('=', $meta);

    if ( isset($metaarray) && sizeof($metaarray)==2 )
    {
      $metakey = sanitize_text_field( $metaarray [0] );
      $metavalue = sanitize_text_field ( $metaarray [1] );
    }
    else
    {
      $metakey = null;
      $metavalue = null;
    }

    $classname = sanitize_html_class($classname);

    // get page list
    if($posts!= '') { // by post list
      $the_query = array(
        'post__in' => $postsarray,
        'post_type' => 'any',
        'post__not_in' => $excldarray,
        'posts_per_page' => $maxitems,
        'orderby'        => $orderby,
      );
    } elseif( $posts_names!= '') { // by post slug
      $the_query = array(
        'post_name__in' => $postsnamesarray,
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

    } elseif( $category!= '' ) {
      $the_query = array(
        'cat' => $category,
        'post_type' => 'any',
        'post__not_in' => $excldarray,
        'posts_per_page' => $maxitems,
        'orderby'        => $orderby,
      );

    } elseif( $category_name!= '' ) {
      $the_query = array(
        'category_name' => $category_name,
        'post_type' => 'any',
        'post__not_in' => $excldarray,
        'posts_per_page' => $maxitems,
        'orderby'        => $orderby,
      );


    } elseif( $categories!= '') {  // by category
      $the_query = array(
        'category__in' => $catarray,
        'post_type' => 'any',
        'post__not_in' => $excldarray,
        'posts_per_page' => $maxitems,
        'orderby'        => $orderby,
      );
    } elseif( $tags != '') {  // by tag
      $the_query = array(
        'tag__in' => $tagarray,
        'post_type' => 'any',
        'post__not_in' => $excldarray,
        'posts_per_page' => $maxitems,
        'orderby'        => $orderby,
      );
    } elseif ( !empty($metakey) && !empty($metavalue) ) {
      $the_query = array(
        'meta_key' => $metakey,
        'meta_value' => $metavalue,
        'post_type' => 'any',
        'post__not_in' => $excldarray,
        'posts_per_page' => $maxitems,
        'orderby'        => $orderby,
      );
    } else { // no params
      $the_query = array(
        'post_type' => $post->post_type,
        'post__not_in' => $excldarray,
        'posts_per_page' => $maxitems,
        'orderby'        => $orderby,
      );

      $post_type = $post->post_type;

      if ($post_type == 'post')
      {
        $categories = get_the_category($post->ID);

        if (is_array($categories) && count($categories) > 0)
        {
          $the_query['category__in'] = $categories;
        }
      }
      else // all post types
      {
        $the_query['post_parent'] = $post->ID;
      }

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
    $cssclass = esc_attr($cssclass);

    // start output

    $output = '';
    $output .= "<div class='$cssclass'>";
    query_posts($the_query);
    if (have_posts()) : while (have_posts()) : the_post();

    $stored_meta = get_post_meta($post->ID);

    // Title
    if ( isset ( $stored_meta['mejorcluster-title'] ) && strlen($stored_meta['mejorcluster-title'][0]) > 0 ) {
      $title = $stored_meta['mejorcluster-title'][0];
    } else {
      $title = get_the_title($post->ID);
    };
    $the_title = esc_html (wp_trim_words( $title, $title_maxwords ));

    // Description
    if ( isset ( $stored_meta['mejorcluster-desc'] ) && strlen($stored_meta['mejorcluster-desc'][0]) > 0 ) {
      $content = $stored_meta['mejorcluster-desc'][0];
    } else {
      $content = get_the_content($post->ID);
    };
    $the_content = esc_html ( wp_trim_words( $content, $desc_maxwords ) );

    // Image
    if ( isset ( $stored_meta['mejorcluster-image'] ) && strlen($stored_meta['mejorcluster-image'][0]) > 0 ) {
      $the_thumb = $stored_meta['mejorcluster-image'][0];
    } else {
      $the_thumb = get_the_post_thumbnail_url($post->ID , $imagesize );
    }
    $the_thumb = esc_url ($the_thumb);

    // Permalink
    $the_link = esc_url ( get_permalink($post->ID) );

    // create the cluster item
    $output .= "<article class='mejorcluster-item'>";
      if (!$skip_image)
      {
        $output .= "<header class='mejorcluster-item-header'>";
        if (!$skip_image_link) $output .=   "<a href='$the_link' rel='bookmark' class='mejorcluster-image-link'>";
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

  $value = $default_value;

  if ( is_array ($options) )
  {
    if ( array_key_exists ($kname,$options) ) { $value = $options[$kname]; } else { $value = $default_value; }
  }
  return sanitize_text_field ($value);
}

function gb ( $options , $name , $default_value )
{
  $kname = 'mejorcluster_' . $name;
  if ( is_array ($options) )
  {
    if ( array_key_exists ($kname,$options) ) { return 'yes'; } else { return 'no'; }
  } else {
    return sanitize_text_field($default_value);
  }
}

function shortcode_map ($n,$post)
{
  if ( trim(strtolower($n)) == 'self') return $post->ID;
  if ( trim(strtolower($n)) == 'post_parent') return $post->post_parent;
  return intval($n);
}

