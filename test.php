<?php
$url = 'https://www.dropbox.com/sh/4b1i12p3max8jdk/LxI1LBDeT6';
$url = 'https://www.dropbox.com/sh/8wovg0e7zqo2iz7/WgEYcOzBdA';
// Get drobox folder html.
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url); // Dropbox shared folder link
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (FM Scene 4.6.1)');
curl_setopt($ch, CURLOPT_REFERER, 'https://www.dropbox.com/');
curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$html = curl_exec($ch);
// extract links with DOMDocument
$dom = new DOMDocument();
@$dom->loadHTML($html);
$link_folder = '';

$ol = $dom->getElementById('gallery-view-folders');
if (! empty($ol)) {
    $links = $ol->getElementsByTagName('a');
    $link_folder = array();
    foreach ($links as $link) {
        $link_folder = $link->getAttribute('href');
    }
}
$url = ($link_folder === '') ? $url : $link_folder;
curl_setopt($ch, CURLOPT_URL, $url); // Dropbox shared folder link
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (FM Scene 4.6.1)');
curl_setopt($ch, CURLOPT_REFERER, 'https://www.dropbox.com/');
curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$html = curl_exec($ch);
// extract links with DOMDocument
$dom = new DOMDocument();
@$dom->loadHTML($html);
$links = $dom->getElementsByTagName('a');
$processed_links = array();
foreach ($links as $link) {
    if ($link->hasAttribute('class') && $link->hasAttribute('href')) {
        foreach ($link->attributes as $a) {
            if ($a->value == 'filename-link') {
                $processed_links[] = $link->getAttribute('href');
            }
        }
    }
}
$preBalises = array();
$exts = $processed_links;
foreach ($exts as $ext) {
    $url = $ext;
    $path = parse_url($url, PHP_URL_PATH);
    $ext2 = pathinfo($path, PATHINFO_EXTENSION);
//     $tags = get_meta_tags("https://www.dropbox.com/sc/i4ooccm7ueyoylz/65pg_sXHow");
//             var_dump($tags);die;
    $url = 'https://www.dropbox.com/sc/i4ooccm7ueyoylz/65pg_sXHow';

    if (!$fp = fopen($url, 'r')) {
        trigger_error("Impossible d'ouvrir l'URL ($url)", E_USER_ERROR);
    }

    $meta = stream_get_meta_data($fp);

    print_r($meta);

    fclose($fp);


    if ($stream = fopen($url, 'r')) {
        // affiche toute la page, en commençant à la position 10
        echo stream_get_contents($stream, -1, 10);

        fclose($stream);
    }


    if ($stream = fopen($url, 'r')) {
        // Affichage des 5 premiers octets
        echo stream_get_contents($stream, 5);

        fclose($stream);
    }

    die();
    if ($ext2 == 'jpg') {
        $first = "https://dl.dropbox.com";
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, "https://www.dropbox.com/sc/i4ooccm7ueyoylz/65pg_sXHow"); // textfile
        curl_setopt($ch2, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (FM Scene 4.6.1)');
        curl_setopt($ch2, CURLOPT_REFERER, 'https://www.dropbox.com/');
        curl_setopt($ch2, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch2, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch2, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        $str = curl_exec($ch2);
        file_put_contents('/tmp/test', $str);
        $meta = cameraUsed('/tmp/test');
        var_dump($meta);
// //         var_dump($str);

        die();
        $preBalises[] = "<br> <a href=https://dl.dropbox.com$path><img src=https://dl.dropbox.com$path></img></a>";
    }
    if ($ext2 == 'txt') {
        $first = "https://dl.dropbox.com";

        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $first . $path); // textfile
        curl_setopt($ch2, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (FM Scene 4.6.1)');
        curl_setopt($ch2, CURLOPT_REFERER, 'https://www.dropbox.com/');
        curl_setopt($ch2, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch2, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch2, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        $str = curl_exec($ch2);
        $preBalises[] = '<pre>' . preg_replace('!\r?\n!', '<br>', $str) . '</pre>';
    }
}

var_dump($processed_links);
var_dump($preBalises);


// This function is used to determine the camera details for a specific image. It returns an array with the parameters.
function cameraUsed($imagePath)
{
    // Check if the variable is set and if the file itself exists before continuing
    if ((isset($imagePath)) and (file_exists($imagePath))) {

        // There are 2 arrays which contains the information we are after, so it's easier to state them both
        $exif_ifd0 = read_exif_data($imagePath, 'IFD0', 0);
        $exif_exif = read_exif_data($imagePath, 'EXIF', 0);

        // error control
        $notFound = "Unavailable";

        // Make
        if (@array_key_exists('Make', $exif_ifd0)) {
            $camMake = $exif_ifd0['Make'];
        } else {
            $camMake = $notFound;
        }

        // Model
        if (@array_key_exists('Model', $exif_ifd0)) {
            $camModel = $exif_ifd0['Model'];
        } else {
            $camModel = $notFound;
        }

        // Exposure
        if (@array_key_exists('ExposureTime', $exif_ifd0)) {
            $camExposure = $exif_ifd0['ExposureTime'];
        } else {
            $camExposure = $notFound;
        }

        // Aperture
        if (@array_key_exists('ApertureFNumber', $exif_ifd0['COMPUTED'])) {
            $camAperture = $exif_ifd0['COMPUTED']['ApertureFNumber'];
        } else {
            $camAperture = $notFound;
        }

        // Height
        if (@array_key_exists('Height', $exif_ifd0['COMPUTED'])) {
            $height = $exif_ifd0['COMPUTED']['Height'];
        } else {
            $height = $notFound;
        }

        // Width
        if (@array_key_exists('Width', $exif_ifd0['COMPUTED'])) {
            $width = $exif_ifd0['COMPUTED']['Width'];
        } else {
            $width = $notFound;
        }

        // IsColor
        if (@array_key_exists('IsColor', $exif_ifd0['COMPUTED'])) {
            $isColor = $exif_ifd0['COMPUTED']['IsColor'];
        } else {
            $isColor = $notFound;
        }

        // Date
        if (@array_key_exists('DateTime', $exif_ifd0)) {
            $camDate = $exif_ifd0['DateTime'];
        } else {
            $camDate = $notFound;
        }

        // ISO
        if (@array_key_exists('ISOSpeedRatings', $exif_exif)) {
            $camIso = $exif_exif['ISOSpeedRatings'];
        } else {
            $camIso = $notFound;
        }

        // MimeType
        if (@array_key_exists('MimeType', $exif_exif)) {
            $mimeType = $exif_exif['MimeType'];
        } else {
            $mimeType = $notFound;
        }

        // Flash
        if (@array_key_exists('Flash', $exif_exif)) {
            $flash = $exif_exif['Flash'];
        } else {
            $flash = $notFound;
        }

        // FocalLength
        if (@array_key_exists('FocalLength', $exif_exif)) {
            $focalLength = $exif_exif['FocalLength'];
        } else {
            $focalLength = $notFound;
        }

        $return = array();
        $return['make'] = $camMake;
        $return['model'] = $camModel;
        $return['exposure'] = $camExposure;
        $return['aperture'] = $camAperture;
        $return['date'] = $camDate;
        $return['iso'] = $camIso;
        $return['mimeType'] = $mimeType;
        $return['flash'] = $flash;
        $return['focalLength'] = $focalLength;
        $return['isColor'] = $isColor;
        $return['width'] = $width;
        $return['height'] = $height;

        return $return;
    } else {
        return false;
    }
}

?>
