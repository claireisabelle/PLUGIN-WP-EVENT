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



