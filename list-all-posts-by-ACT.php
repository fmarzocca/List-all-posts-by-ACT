<?php
/*
	Plugin Name: List all posts by Authors, nested Categories and Titles
	Plugin URI: https://github.com/fmarzocca/List-all-posts-by-ACT
	Description: A plugin to list all posts by Author, nested Categories and Titles
	Version: 2.10.0
	Author: Fabio Marzocca
	Author URI: http://www.marzocca.net
	Text Domain:   list-all-posts-by-authors-nested-categories-and-titles
	License: GPL2
	
	Copyright 2015-2024  by Fabio Marzocca  (email : marzoccafabio@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


defined('ABSPATH') or die("No script kiddies please!");
define('ATP_DIR', dirname(__FILE__));

require_once 'include/ACT-displayer.php';
require_once 'include/ACT-admin.php';

/* Shortcode */

function ACT_fullindex($atts)
{
    $atts = shortcode_atts(array(
            'exclude'           =>  "",
            'admin'             =>  0,
            'singleuser'        =>  0,
            'show'              =>  "Category, Author, Title",
            'postspercategory'  => -1,
            'postsperauthor'    => -1,
            'totalpoststitle'   => -1,
            'reverse-date'      =>  0,
		    'postdate'			=>	0
             ), $atts);
    return ACT_hierarchy_indexes($atts);
}
add_shortcode( 'ACT-list', 'ACT_fullindex' );
 
/* Add CSS and scripts on the frontend*/    
function ACT_css()
{
        wp_register_style( 'ACT_css', plugins_url( 'ACT.css', __FILE__ ) );
        wp_enqueue_style( 'ACT_css' );
} // function
add_action( 'wp_enqueue_scripts', 'ACT_css' );


 
/* Add CSS and scripts on the admin section*/
function ACT_load_admin_css($hook)
{
    
    if ('tools_page_ACT_shortcode_helper' != $hook) {
        return;
    }
    
    wp_register_style( 'ACT_view_css', plugins_url( 'ACT_view.css', __FILE__ ) );
    wp_enqueue_style( 'ACT_view_css' );
    wp_register_script( 'ACT_view_js', plugins_url( 'ACT_view.js', __FILE__ ) );
    wp_enqueue_script( 'ACT_view_js');
    wp_register_script( 'ACT_form_js', plugins_url( 'ACT_form.js', __FILE__ ), array( 'jquery' ));
    // Localize the script with new data
    $translation_array = array(
    'alert_showlist' => __( 'You must select at least one list to show!', 'list-all-posts-by-authors-nested-categories-and-titles' ),
    'alert_end' => __( 'The shortcode has been generated. You\'ll find it at the bottom of this page.', 'list-all-posts-by-authors-nested-categories-and-titles' )
    );
    wp_enqueue_script( 'ACT_form_js');
    wp_localize_script( 'ACT_form_js', 'ACT_obj', $translation_array );
}
add_action('admin_enqueue_scripts', 'ACT_load_admin_css');

/*******************************************************************************

	ADD THE 'ACT List Shortcodes' ITEM TO THE TOOLS MENU

*******************************************************************************/
function ACT_tools_menu()
{
    if (function_exists('add_management_page')) {
        add_management_page(
            __('ACT List Shortcodes', 'list-all-posts-by-authors-nested-categories-and-titles'),
            __('ACT List Shortcodes', 'list-all-posts-by-authors-nested-categories-and-titles'),
            'administrator',
            'ACT_shortcode_helper',
            'ACT_shortcode_helper');
    }
}
add_action('admin_menu', 'ACT_tools_menu');

/***********************************************************************

	HOOK AJAX
	********************************************************************/
add_action('wp_ajax_ACT_processform', 'ACT_processform');
