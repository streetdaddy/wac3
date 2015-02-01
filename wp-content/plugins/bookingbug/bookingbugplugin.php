<?php
/*
Plugin Name: BookingBug plugin
Version: 2.2
Plugin URI: http://wordpress.org/extend/plugins/bookingbug
Description: BookingBug is the complex flexible booking and scheduling platform for any service requirments. This widget offers sidebar and shortcode integrations for all of BookingBug's *Free* and paid plans
Author: BookingBug.com
Author URI: http://www.bookingbug.com
*/
/*  Copyright 2010  BookingBug  (contact : www.bookingbug.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Register the shortcode
// [bookingbug id="xxyyyy" style="wide" palette=""]
function bbug_shortcode($atts) {


	$prms = "<script src='//www.bookingbug.com/widget";
   
    if ($atts[event_id] != null){
		$prms = $prms . "/event/?";
	} else {
		$prms = $prms . "/all/?";
	}
	
	foreach ($atts as $key => $value) 
	  $prms = $prms . "&" . $key . "=" .$value;

	  $x = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	  $resize = $x . "resize.html";
	  if (substr($resize, 0, 7) == "http://")
		$resize = substr($resize, 7);
	
	$prms = $prms . "&resize=" . $resize . "'></script>";

	return $prms;
}

add_shortcode('bookingbug', 'bbug_shortcode');

// Register the widget

function bookingbug_loaded()
{
	$widget_ops = array('classname' => 'bookingbug_widget', 'description' => "Add BookingBug booking widgets to your sidebar and blog posts." );
	wp_register_sidebar_widget('bookingbug_widget', 'BookingBug', 'widget_BookingBug', $widget_ops);
}

add_action('plugins_loaded','bookingbug_loaded');


function widget_BookingBug($args) {
	extract($args);

	$options = get_option("widget_sideBookingBug");

	echo $before_widget;
	echo $before_title;
	echo $options['title'];
	echo $after_title;
	widget_sideBookingBug();
	echo $after_widget;
}


function widget_sideBookingBug() {
	$options = get_option("widget_sideBookingBug");
	$bbug_id= $options['BookingBugId'];
	$palette = $options['palette'];
	$x = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	$resize = $x . "resize.html";
	if (substr($resize, 0, 7) == "http://")
		$resize = substr($resize, 7);

	$path = "http://www.bookingbug.com/widget/all?id=" . $bbug_id . "&palette=" . $palette . "&style=small" . "&resize=" . $resize;
?>
<script type="text/javascript" src="<?php echo($path)?>"></script>
<?php
}

function BookingBug_control()
{
	$options = get_option("widget_sideBookingBug");
	if (!is_array( $options ))
	{
		$options = array('BookingBugId' => '', 'palette' => '', 'title' => '');
	}

	if ($_POST['sideBookingBug-Submit']) {
		$options['BookingBugId'] = htmlspecialchars($_POST['sideBookingBug-BookingBugId']);
		$options['palette'] = htmlspecialchars($_POST['sideBookingBug-Palette']);
		$options['title'] = htmlspecialchars($_POST['sideBookingBug-Title']);
		update_option("widget_sideBookingBug", $options);
	}

?>

<p>
<label for="sideBookingBug-BookingBugId">Title: </label><br />
<input class="widefat" type="text" id="sideBookingBug-Title" name="sideBookingBug-Title" value="<?php echo $options['title'];?>" />
<br /><br />

<label for="sideBookingBug-BookingBugId">BookingBug ID: </label><br />
<input class="widefat" type="text" id="sideBookingBug-BookingBugId" name="sideBookingBug-BookingBugId" value="<?php echo $options['BookingBugId'];?>" />
<br /><br />

<label for="sideBookingBug-Palette">Palette: </label><br />
<input class="widefat" type="text" id="sideBookingBug-Palette" name="sideBookingBug-Palette" value="<?php echo $options['palette'];?>" />
<br /><br />

<input type="hidden" id="sideBookingBug-Submit" name="sideBookingBug-Submit" value="1" />
</p>
<?php
}


// register the widget control

wp_register_widget_control('bookingbug_widget',  'BookingBug', 'BookingBug_control');

