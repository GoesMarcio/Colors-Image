<?php

class ColorsImage{
    private $image;
    private $width;
    private $height;
    private $type;
    private $image_resource;
    private $image_array = array();
    private $pallete = array();

    public function __construct(string $image){
        try{
            $this->image = $image;
            $this->image_details();
            $this->read_image();
            
        }catch(Exception $e){
            throw $e;
        }
    }

    private function image_details(){
        if(($this->url_exists($this->image) && (list($width, $height) = getimagesize($this->image)))){
		    $this->width = $width;
            $this->height = $height;
            $this->type = exif_imagetype($this->image);
            
        }else{
            throw new Exception("Error reading image");
        }
    }

    private function url_exists($url){
        if(file_exists($url))
            return true;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
        $result = curl_exec($ch);
        curl_close($ch);

        if($result !== false){
            return true;
        }else{
            return false;
        }
    }
    
    private function read_image(){

        switch ($this->type){
            case IMAGETYPE_PNG:
                $function_img = "imagecreatefrompng";
                break;
            case IMAGETYPE_JPEG:
                $function_img = "imagecreatefromjpeg";
            break;
            case IMAGETYPE_GIF:
                $function_img = "imagecreatefromgif";
                break;
            case IMAGETYPE_BMP:
                $function_img = "imagecreatefrombmp";
                break;
            default:
                throw new Exception("Unknown type image");
                return;
        }

        $this->image_resource = $function_img($this->image);

        for($i = 0; $i < $this->width; $i++){
            for($j = 0; $j < $this->height; $j++){
                $index = imagecolorat($this->image_resource, $i, $j);
                $rgb = imagecolorsforindex($this->image_resource, $index);
                $hex = self::RGBToHex($rgb["red"], $rgb["green"], $rgb["blue"]);
                
                $this->image_array[$j][$i] = array(
                    "r" => $rgb["red"],
                    "g" => $rgb["green"],
                    "b" => $rgb["blue"],
                );
                
                if(isset($this->pallete[$hex])){
                    $this->pallete[$hex] += 1;
                }else{
                    $this->pallete[$hex] = 1;
                }

                arsort($this->pallete);
            }
        }
    }

    static public function RGBToHex($r, $g, $b){
        $hex = "#";
        $hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
        $hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
        $hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);

        return strtoupper($hex);
    }

    static public function HexToRGB($hex) {
        $hex = str_replace("#", "", $hex);
      
        if(strlen($hex) == 3){
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        }else{
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }

        $rgb = array(
            "r" => $r, 
            "g" => $g,
            "b" => $b
        );
 
        return $rgb;
    }

    public function get_image_array(){
        return $this->image_array;
    }

    public function get_thumbnail($width, $height){
        // Load
        $thumb = imagecreatetruecolor($width, $height);

        if($this->width > $this->height){
            $_width = $this->height;
            $_height = $this->height;
            $_x = intval(($this->width - $this->height)/2);
            $_y = 0;
        }else{
            $_width = $this->height;
            $_height = $this->height;
            $_x = 0;
            $_y = intval(($this->height - $this->width)/2);
        }

        
        // Resize
        imagecopyresized($thumb, $this->image_resource, 0, 0, $_x, $_y, $width, $height, $_width, $_height);

        return imagejpeg($thumb);
    }

    public function get_width(){
        return $this->width;
    }

    public function get_height(){
        return $this->height;
    }

    public function get_type(){
        return $this->type;
    }

    public function get_total_colors(){
        return count($this->pallete);
    }
    
    public function get_pallete(int $limit = null){
        $data = array();
        foreach($this->pallete as $key => $value){
            $rgb = self::HexToRGB($key);
            array_push($data, array(
                "hex" => $key,
                "rgb" => array(
                    "r" => $rgb["r"], 
                    "g" => $rgb["g"],
                    "b" => $rgb["b"]
                ),
                "count" => $value
            ));
        }

        if($limit){
            return array_slice($data, 0, $limit);
        }

        return $data;
    }

    public function get_pallete_percentage(float $limit = null){
        $total_colors = (float) $this->width * $this->height;
        $hundred = (float) 100;
        $data = array();

        foreach($this->pallete as $key => $value){
            $rgb = self::HexToRGB($key);
            $percentage = (float) ($value * 100) / $total_colors;
            $percentage = round($percentage, 2);
            $hundred -= $percentage;
            array_push($data, array(
                "hex" => $key,
                "rgb" => array(
                    "r" => $rgb["r"], 
                    "g" => $rgb["g"],
                    "b" => $rgb["b"]
                ),
                "percentage" => $percentage
            ));
        }

        $data[0]["percentage"] += round($hundred, 2);
        
        if($limit){
            return array_slice($data, 0, $limit);
        }

        return $data;
    }

    public function get_prominent_colors(float $min_percentage){
        $colors = $this->get_pallete_percentage();
        $data = array();

        foreach($colors as $color) {
			if($color["percentage"] < $min_percentage)
                break;
            
            array_push($data, $color);
		}

        return $data;
    }

    public static function get_brightness($color){
        // check is rga
        if(is_array($color)){
            $c_r = $color['r'];
            $c_g = $color['g'];
            $c_b = $color['b'];
            
            return (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;

        }else{
            // is #hex
            $hex = str_replace('#', '', $color);
            $c_r = hexdec(substr($hex, 0, 2));
            $c_g = hexdec(substr($hex, 2, 2));
            $c_b = hexdec(substr($hex, 4, 2));
            
            return (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;
        }
    }

    public static function get_color_type($color, $coefficient = 130){
        $brightness = self::get_brightness($color);

        if($brightness > $coefficient){
            return "light";
        }else{
            return "black";
        }
    }
}