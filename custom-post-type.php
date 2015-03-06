<?php

class VideoPlayerCustomPostType{

private $post_type = 'videoplayer';
private $post_label = 'Video Player Ultimate';
private $prefix = '_video_player_';
private $video_ids = array();
function __construct() {
	
	
	add_action("init", array(&$this,"create_post_type"));
	add_action( 'init', array(&$this, 'video_player_register_shortcodes'));
	add_action( 'wp_head', array(&$this, 'enqueue_styles'));
	add_action( 'wp_head', array(&$this, 'enqueue_scripts'));
	add_action( 'wp_footer', array(&$this, 'publish_video_ids'));
	
	add_action( 'cmb2_init', array(&$this,'videoplayer_register_metabox' ));
	
	register_activation_hook( __FILE__, array(&$this,'activate' ));
}

function create_post_type(){
	register_post_type($this->post_type, array(
	         'label' => _x($this->post_label, $this->post_type.' label'), 
	         'singular_label' => _x('All '.$this->post_label, $this->post_type.' singular label'), 
	         'public' => true, // These will be public
	         'show_ui' => true, // Show the UI in admin panel
	         '_builtin' => false, // This is a custom post type, not a built in post type
	         '_edit_link' => 'post.php?post=%d',
	         'capability_type' => 'page',
	         'hierarchical' => false,
	         'rewrite' => array("slug" => $this->post_type), // This is for the permalinks
	         'query_var' => $this->post_type, // This goes to the WP_Query schema
	         //'supports' =>array('title', 'editor', 'custom-fields', 'revisions', 'excerpt'),
	         'supports' =>array('title', 'author'),
	         'add_new' => _x('Add New', 'Event')
	         ));
}



/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_init' hook.
 */

function videoplayer_register_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_videoplayer_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb_demo = new_cmb2_box( array(
		'id'            => $this->prefix . 'metabox',
		'title'         => __( 'Video Player', 'cmb2' ),
		'object_types'  => array( $this->post_type, ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
	) );

	$cmb_demo->add_field( array(
		'name'       => __( 'Video Url', 'cmb2' ),
		'desc'       => __( 'Upload media, choose a file you already uploaded, or place a url to a video here.', 'cmb2' ),
		'id'         => $this->prefix . 'video_url',
		'type'       => 'file',
	) );
	$cmb_demo->add_field( array(
		'name'       => __( 'Video Poster', 'cmb2' ),
		'desc'       => __( 'Upload media, choose a file you already uploaded, or place a url to the video poster here.', 'cmb2' ),
		'id'         => $this->prefix . 'video_poster',
		'type'       => 'file',
	) );
}

function publish_video_ids(){
		$params = array(
		  'video_player_ids' => $this->video_ids,
		  'video_js_swf' => plugin_dir_url(__FILE__).'video-js/video-js.swf',
		);
		wp_localize_script( 'video-player-js', 'VideoPlayerParams', $params );
}


function video_player_shortcode($atts){
		extract( shortcode_atts( array(
			'id' => '',
			'url' => '',
			'type' => 'mp4',
			'poster' => '',
		), $atts ) );
		$dir = plugin_dir_path( __FILE__ );
		
		if($id){
			$videoUrl = get_post_meta($id, $this->prefix . 'video_url', true);
			$poster = get_post_meta($id, $this->prefix . 'video_poster', true);
		}
		else{
			$id = rand();
			$videoUrl = $url;
		}
		$randomNumber = rand();
		$videoId = $id.'-'.$randomNumber;
		$this->video_ids[] = $videoId;
		ob_start();
		include $dir.'template/videoPlayerTemplate.php';
		return ob_get_clean();
}



function video_player_register_shortcodes(){
		add_shortcode( 'video_player', array(&$this,'video_player_shortcode' ));
	}


function activate() {
	// register taxonomies/post types here
	$this->create_post_type();
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}

function enqueue_styles(){
	wp_register_style( 'video-player-css', plugin_dir_url(__FILE__).'css/videoPlayer.css' );
	wp_enqueue_style('video-player-css');
	wp_enqueue_style('video.js-css', plugin_dir_url(__FILE__).'video-js/video-js.css');
	
}

function enqueue_scripts(){
	
	wp_enqueue_script('video.js-js', plugin_dir_url(__FILE__).'video-js/video.js');
	wp_enqueue_script('video-player-js', plugin_dir_url(__FILE__).'js/videoPlayer.js');
}



}// end VideoPlayerCustomPostType class

new VideoPlayerCustomPostType();


?>