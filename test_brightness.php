<?php
require_once("colors-image.class.php");

$dark_color_hex = "#272822";
$dark_color_rgb = array(
    "r" => "39",
    "g" => "40",
    "b" => "34"
);

echo ColorsImage::get_brightness($dark_color_hex);

echo "<br>";

echo ColorsImage::get_brightness($dark_color_rgb);

echo "<br>";

echo ColorsImage::get_color_type($dark_color_rgb);


echo "<br>";

$super_dark_color_hex = "#000000";
$super_dark_color_rgb = array(
    "r" => "0",
    "g" => "0",
    "b" => "0"
);

echo ColorsImage::get_brightness($super_dark_color_hex);

echo "<br>";

echo ColorsImage::get_brightness($super_dark_color_rgb);

echo "<br>";

echo ColorsImage::get_color_type($super_dark_color_rgb);

echo "<br>";

$light_color_hex = "#cecece";
$light_color_rgb = array(
    "r" => "206",
    "g" => "206",
    "b" => "206"
);

echo ColorsImage::get_brightness($light_color_hex);

echo "<br>";

echo ColorsImage::get_brightness($light_color_rgb);

echo "<br>";

echo ColorsImage::get_color_type($light_color_rgb);


echo "<br>";

$super_light_color_hex = "#FFFFFF";
$super_light_color_rgb = array(
    "r" => "255",
    "g" => "255",
    "b" => "255"
);

echo ColorsImage::get_brightness($super_light_color_hex);

echo "<br>";

echo ColorsImage::get_brightness($super_light_color_rgb);

echo "<br>";

echo ColorsImage::get_color_type($super_light_color_rgb);