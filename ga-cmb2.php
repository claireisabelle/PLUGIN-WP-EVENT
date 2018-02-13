<?php
/*
Plugin Name: Gourmet Artistry Metaboxes CMB2
Plugin URI:
Description: Adds Metaboxes with CMB2 to Gourtmet Artistry Site
Version: 1.0
Author: Claire Bourdalé - Arroweb
Author URI: https://arroweb.net
License: GLP2
Licence URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if( file_exists(dirname( __FILE__ ) . '/CMB2/init.php') ){
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

/*
****************************************
* FIELDS CREATION
****************************************
*/

function metaboxes_events_cmb2(){
	$prefix = 'ga_fields_events_';

	$metabox_events = new_cmb2_box(array(
		'id' => $prefix . 'metabox',
		'title' => __('Events Metaboxes', 'cmb2'),
		'object_types' => array('events') // Custom Post Type auquel ça se rattache
	));

	// Ajout d'un champ text
	$metabox_events->add_field(array(
		'name'		=> __('City', 'cmb2'),
		'desc'		=> __('City where the event takes place', 'cmb2'),
		'id'		=> $prefix . 'city',
		'type'		=> 'text',
	));

	// Ajout d'un champ date dans un calendrier
	$metabox_events->add_field(array(
		'name'		=> __('Event Date', 'cmb2'),
		'desc'		=> __('Event Date (pick from the calendar', 'cmb2'),
		'id'		=> $prefix . 'date',
		'type'		=> 'text_datetime_timestamp',
	));

	// Ajout d'un champ nombre de places disponibles
	$metabox_events->add_field(array(
		'name'		=> __('Seats Available', 'cmb2'),
		'desc'		=> __('Enter the number of seats available', 'cmb2'),
		'id'		=> $prefix . 'seats',
		'type'		=> 'text',
	));

	// Ajout d'un champ programme
	$metabox_events->add_field(array(
		'name'		=> __('Program', 'cmb2'),
		'desc'		=> __('Add the Program for this Event', 'cmb2'),
		'id'		=> $prefix . 'program',
		'type'		=> 'text',
		'repeatable' => true
	));
}
add_action('cmb2_admin_init', 'metaboxes_events_cmb2');



/*
****************************************
* PRINT UPCOMING EVENTS
****************************************
*/

function upcoming_events($text){
	$args = array(
		'post_type'		 => 'events',
		'orderby'		 => 'meta_value',
		'meta_key'		 => 'ga_fields_events_date',
		'order'			 => 'ASC',
		'posts_per_page' =>	-1, // -1 permet d'afficher tous les posts
		'meta_query'	 => array(
			array(
				'key' 	=> 'ga_fields_events_date',
				'value' => time(),
				'compare' => '>=',
				'type'  => 'NUMERIC'
			)
		),
	);

	echo "<h2 class='text-center events-title'>" . $text['text'] .  "</h2>";

	echo "<ul class='list-events no-bullet'>";

	$events = new WP_Query($args);

	while($events->have_posts()) : $events->the_post();

		echo "<li>";
		echo the_title('<h3 class="text-center">', '</h3>');

		echo "<div class='row'>";
		echo "<div class='medium-6 columns'>";
		echo "<div class='event-info'>";

			echo "<p>" . get_the_term_list($post->ID, 'type_event', '<b>Type: </b>', ', ', '') . "</p>";
			echo "<p><b>Seats Available: </b>" . get_post_meta(get_the_ID(), 'ga_fields_events_seats', true) . "</p>";
			echo "<p><b>City: </b>" . get_post_meta(get_the_ID(), 'ga_fields_events_city', true) . "</p>";

			$dateEvent = get_post_meta(get_the_ID(), 'ga_fields_events_date', true);
			echo "<p class='date-event'><b>Date: </b>" . gmdate('d-m-Y', $dateEvent) . " <b>Time: </b>" . gmdate('H:i', $dateEvent) . "</b></p>";

		echo "</div>";
		echo "</div>";

		echo "<div class='medium-6 columns agenda'>";
		echo "<h4 class='text-center'>Agenda of the Event: </h4>";

			$agenda = get_post_meta(get_the_ID(), 'ga_fields_events_program', true);

			foreach($agenda as $a){
				echo "<p>" . $a . "</p>";
			}

		echo "</div>";
		echo "</div>";

		echo "</li>";
	endwhile;
	wp_reset_postdata();

	echo "</ul>";
}
add_shortcode('upcoming-events', 'upcoming_events');


/*
****************************************
* PAST EVENTS
****************************************
*/

function past_events($text){
	$args = array(
		'post_type'		 => 'events',
		'orderby'		 => 'meta_value',
		'meta_key'		 => 'ga_fields_events_date',
		'order'			 => 'ASC',
		'posts_per_page' =>	-1, // -1 permet d'afficher tous les posts
		'meta_query'	 => array(
			array(
				'key' 	=> 'ga_fields_events_date',
				'value' => time(),
				'compare' => '<=',
				'type'  => 'NUMERIC'
			)
		),
	);

	echo "<h2 class='text-center events-title'>" . $text['text'] .  "</h2>";

	echo "<ul class='list-events no-bullet'>";

	$events = new WP_Query($args);

	while($events->have_posts()) : $events->the_post();

		echo "<li>";
		echo the_title('<h3 class="text-center">', '</h3>');

		echo "<div class='row'>";
		echo "<div class='medium-6 columns'>";
		echo "<div class='event-info'>";

			echo get_the_term_list($post->ID, 'type_event', 'Type: ', ', ', '');
			echo "<p><b>Seats Available: </b>" . get_post_meta(get_the_ID(), 'ga_fields_events_seats', true) . "</p>";
			echo "<p><b>City: </b>" . get_post_meta(get_the_ID(), 'ga_fields_events_city', true) . "</p>";

			$dateEvent = get_post_meta(get_the_ID(), 'ga_fields_events_date', true);
			echo "<p class='date-event'><b>Date: </b>" . gmdate('d-m-Y', $dateEvent) . " <b>Time: " . gmdate('H:i', $dateEvent) . "</b></p>";

		echo "</div>";
		echo "</div>";

		echo "<div class='medium-6 columns agenda'>";
		echo "<h4 class='text-center'>Agenda of the Event: </h4>";

			$agenda = get_post_meta(get_the_ID(), 'ga_fields_events_program', true);

			foreach($agenda as $a){
				echo "<p>" . $a . "</p>";
			}

		echo "</div>";
		echo "</div>";

		echo "</li>";
	endwhile;
	wp_reset_postdata();

	echo "</ul>";
}
add_shortcode('past-events', 'past_events');


/*
****************************************
* CSS FILE
****************************************
*/
function ga_metaboxes_css(){
	wp_register_style('metaboxes-css', plugins_url('ga-cmb2/css/ga-cmb2-styles.css'));
	wp_enqueue_style('metaboxes-css');
}
add_action('wp_enqueue_scripts', 'ga_metaboxes_css');