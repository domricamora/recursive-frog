<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$output = $root.'/public/img/og-cover.png';
$logoPath = $root.'/public/img/recursivefrog-logo.png';
$windowsFonts = (getenv('WINDIR') ?: 'C:\\Windows').'\\Fonts\\';

$regularFont = null;
$boldFont = null;

foreach ([$windowsFonts.'arial.ttf', '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf'] as $font) {
    if (is_file($font)) {
        $regularFont = $font;
        break;
    }
}

foreach ([$windowsFonts.'arialbd.ttf', '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf'] as $font) {
    if (is_file($font)) {
        $boldFont = $font;
        break;
    }
}

if (! extension_loaded('gd') || ! function_exists('imagettftext')) {
    throw new RuntimeException('GD with FreeType support is required to generate the OG cover.');
}

if ($regularFont === null || $boldFont === null) {
    throw new RuntimeException('Could not find regular and bold TrueType fonts for the OG cover.');
}

$image = imagecreatetruecolor(1200, 630);
if (! $image) {
    throw new RuntimeException('Could not create the OG cover canvas.');
}

imageantialias($image, true);
$bone = imagecolorallocate($image, 252, 250, 246);
$ink = imagecolorallocate($image, 18, 18, 16);
$muted = imagecolorallocate($image, 95, 95, 83);
$jade = imagecolorallocate($image, 18, 184, 134);
$grid = imagecolorallocate($image, 239, 236, 228);
$line = imagecolorallocate($image, 220, 216, 206);
$softJade = imagecolorallocate($image, 217, 244, 235);

imagefill($image, 0, 0, $bone);

for ($x = 0; $x <= 1200; $x += 60) {
    imageline($image, $x, 0, $x, 630, $grid);
}

for ($y = 0; $y <= 630; $y += 60) {
    imageline($image, 0, $y, 1200, $y, $grid);
}

$logo = imagecreatefrompng($logoPath);
if (! $logo) {
    throw new RuntimeException('Could not open the Recursive Frog logo.');
}

$logoSize = getimagesize($logoPath);
$logoHeight = 54;
$logoWidth = (int) round($logoSize[0] * ($logoHeight / $logoSize[1]));
$resizedLogo = imagecreatetruecolor($logoWidth, $logoHeight);
imagealphablending($resizedLogo, false);
imagesavealpha($resizedLogo, true);
$transparent = imagecolorallocatealpha($resizedLogo, 255, 255, 255, 127);
imagefill($resizedLogo, 0, 0, $transparent);
imagecopyresampled($resizedLogo, $logo, 0, 0, 0, 0, $logoWidth, $logoHeight, $logoSize[0], $logoSize[1]);
imagecopy($image, $resizedLogo, 72, 68, 0, 0, $logoWidth, $logoHeight);

imagettftext($image, 36, 0, 72 + $logoWidth + 18, 110, $ink, $boldFont, 'Recursive Frog');
imagettftext($image, 15, 0, 74, 222, $muted, $regularFont, 'DIGITAL SYSTEMS FOR CEBU BUSINESSES');

imagettftext($image, 72, 0, 68, 324, $ink, $boldFont, 'Websites.');
imagettftext($image, 72, 0, 68, 410, $ink, $boldFont, 'Software.');
imagettftext($image, 72, 0, 68, 496, $jade, $boldFont, 'Automation.');

imageline($image, 74, 548, 1126, 548, $line);
imagettftext($image, 14, 0, 74, 584, $muted, $regularFont, 'BUILT AROUND HOW YOUR BUSINESS WORKS');
imagettftext($image, 14, 0, 934, 584, $muted, $regularFont, 'CEBU, PHILIPPINES');

imageline($image, 854, 306, 1000, 380, $jade);
imageline($image, 1000, 380, 896, 472, $jade);
imageline($image, 1000, 380, 1080, 470, $jade);
imagefilledellipse($image, 854, 306, 116, 116, $softJade);
imageellipse($image, 854, 306, 116, 116, $jade);
imagefilledellipse($image, 1000, 380, 150, 150, $bone);
imageellipse($image, 1000, 380, 150, 150, $jade);
imagefilledellipse($image, 896, 472, 82, 82, $softJade);
imageellipse($image, 896, 472, 82, 82, $jade);
imagefilledellipse($image, 1080, 470, 82, 82, $softJade);
imageellipse($image, 1080, 470, 82, 82, $jade);
imagettftext($image, 12, 0, 817, 310, $muted, $regularFont, 'WEB');
imagettftext($image, 12, 0, 950, 384, $muted, $regularFont, 'SYSTEMS');
imagettftext($image, 10, 0, 860, 476, $muted, $regularFont, 'WORKFLOWS');
imagettftext($image, 10, 0, 1056, 474, $muted, $regularFont, 'GROWTH');

if (! imagepng($image, $output, 9)) {
    throw new RuntimeException('Could not save the OG cover image.');
}

imagedestroy($resizedLogo);
imagedestroy($logo);
imagedestroy($image);

fwrite(STDOUT, "Generated {$output} (1200x630 PNG).".PHP_EOL);
