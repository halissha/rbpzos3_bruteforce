<?php
session_start();

$string = '';

function random_color_part() {
  return str_pad(dechex(mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
  return random_color_part() . random_color_part() . random_color_part();
}

 
for ($i = 0; $i < 5; $i++) {
    // this numbers refer to numbers of the ascii table (lower case)
    $string .= chr(rand(97, 121));
}

$_SESSION['rand_code'] = $string;
$image = imagecreatetruecolor(170, 50);
$black = imagecolorallocate($image, 255, 255, 255);
$color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255)); // red

imagefilledrectangle($image,0,0,399,99,$black);
imagettftext ($image, 30, 0, 10, 40, $color, "/Users/halisha/phpcaptcha/arial.ttf", $_SESSION['rand_code']);
imagesetthickness($image, 1);

for ($i = 0; $i < 20; $i++) {
  $color = random_color();
  $colour = (int)$color;
  imageline($image, 0,rand(0,80),180,rand(0,80), $colour);
}
 
header("Content-type: image/png");
imagepng($image);