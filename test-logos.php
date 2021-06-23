<style>
.square{
    padding: 10px 20px;
    border-box: border-sizing;
    border-radius: 6px;
    display: inline-block;
    margin: 5px;
}
.square.dark{color:#fff}
.square.light{color:#000}
</style>

<?php
require_once("colors-image.class.php");

$timestart = time();

echo "<img src='images/logo/bilari.png' height='100'><br>";
$image = new ColorsImage("images/logo/bilari.png", 4);
$colors = $image->get_pallete_by_similarity(10);

foreach($colors as $color){
    $hex = $color['hex'];
    $type = ColorsImage::get_color_type($hex);
    $pixels = $color['count'];
    echo "<div class='square $type' style='background-color: $hex'>$hex ($pixels)</div>";
}

echo "<div style='clear:both'></div>";

echo "<img src='images/logo/bilari.png' height='100'><br>";
$image = new ColorsImage("images/logo/bilari.png");
$colors = $image->get_pallete_by_similarity(10);

foreach($colors as $color){
    $hex = $color['hex'];
    $type = ColorsImage::get_color_type($hex);
    $pixels = $color['count'];
    echo "<div class='square $type' style='background-color: $hex'>$hex ($pixels)</div>";
}

echo "<div style='clear:both'></div>";





echo "<img src='images/logo/campeaotrofeus.png' height='100'><br>";
$image = new ColorsImage("images/logo/campeaotrofeus.png");
//$colors = $image->get_pallete(10, false);
$colors = $image->get_pallete_by_similarity(10);

foreach($colors as $color){
    $hex = $color['hex'];
    $type = ColorsImage::get_color_type($hex);
    $pixels = $color['count'];
    echo "<div class='square $type' style='background-color: $hex'>$hex ($pixels)</div>";
}

echo "<div style='clear:both'></div>";





echo "<img src='images/logo/markpizzas-1.png' height='100'><br>";
$image = new ColorsImage("images/logo/markpizzas-1.png");
// $colors = $image->get_pallete(10, false);
$colors = $image->get_pallete_by_similarity(10);

foreach($colors as $color){
    $hex = $color['hex'];
    $type = ColorsImage::get_color_type($hex);
    $pixels = $color['count'];
    echo "<div class='square $type' style='background-color: $hex'>$hex ($pixels)</div>";
}

echo "<div style='clear:both'></div>";

$timestop = time();

echo "<br><br>";
echo "Execução em ".($timestop - $timestart)." segundos";