<?php
require_once("colors-image.class.php");

try{
    $image = new ColorsImage("images/camiseta.jpg");
    // $image = new ColorsImage("https://marks.agency/wp-content/uploads/elementor/thumbs/logo-mark-amarelo-transparente-493x201-1-oxyg8z1ny3hgdab9gyok3eybsvbu7exoy879h3qk8w.png");
    // var_dump($image->get_total_colors());
    // var_dump($image->get_pallete());
    // var_dump($image->get_pallete(10.0));
    // var_dump($image->get_pallete_percentage(10.0));

    header('Content-type: image/jpeg');
    echo $image->get_thumbnail(100, 100);

}catch(Exception $e){
    echo $e->getMessage();
}