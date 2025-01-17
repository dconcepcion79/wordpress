<?php
/**
 * Functions and definitions
 *
 * Sets up the theme using core catchresponsive-core and provides some helper functions using catchresponsive-custon-functions.
 * Others are attached to action and
 * filter hooks in WordPress to change core functionality
 *
 * @package Catch Themes
 * @subpackage Catch Responsive
 * @since Catch Responsive 1.0 
 */

//define theme version
if ( !defined( 'CATCHRESPONSIVE_THEME_VERSION' ) )
define ( 'CATCHRESPONSIVE_THEME_VERSION', '1.0.2' );

/**
 * Implement the core functions
 */
require get_template_directory() . '/inc/catchresponsive-core.php';