<?php
// Generates a real PNG badge so CKEditor and Q2A render and save it cleanly
$type = isset($_GET['type']) && strtoupper($_GET['type']) === 'TRUTH' ? 'TRUTH' : 'DARE';
$hex  = isset($_GET['color']) ? preg_replace('/[^a-fA-F0-9]/', '', $_GET['color']) : ($type === 'TRUTH' ? '00b894' : 'd63031');

if (strlen($hex) !== 6) {
    $hex = ($type === 'TRUTH') ? '00b894' : 'd63031';
}

$r = hexdec(substr($hex, 0, 2));
$g = hexdec(substr($hex, 2, 2));
$b = hexdec(substr($hex, 4, 2));

$width = 340;
$height = 64;

$img = imagecreatetruecolor($width, $height);
imagesavealpha($img, true);

$bgColor     = imagecolorallocate($img, $r, $g, $b);
$borderColor = imagecolorallocate($img, 24, 26, 32);
$white       = imagecolorallocate($img, 255, 255, 255);
$gold        = imagecolorallocate($img, 252, 213, 53);

imagefilledrectangle($img, 0, 0, $width - 1, $height - 1, $bgColor);
imagerectangle($img, 0, 0, $width - 1, $height - 1, $borderColor);
imagerectangle($img, 1, 1, $width - 2, $height - 2, $borderColor);

$line1 = "ANSWER ROULETTE";
$line2 = "I LANDED ON: " . $type;

$font1 = 3;
$font2 = 5;

$x1 = ($width - (imagefontwidth($font1) * strlen($line1))) / 2;
$x2 = ($width - (imagefontwidth($font2) * strlen($line2))) / 2;

imagestring($img, $font1, (int)$x1, 12, $line1, $white);
imagestring($img, $font2, (int)$x2, 32, $line2, $gold);

header('Content-Type: image/png');
header('Cache-Control: public, max-age=86400');
imagepng($img);
imagedestroy($img);
