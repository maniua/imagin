<?php
/**
 * http://www.network-science.de/ascii/
 *
 * Script permettant de stocker les images par date de prise de vues
 * @author mantoine
 */
$directory = null;
$setOrder = false;

$ascii = <<< ASCII
                  _   _            __.__     ___    _                          ____             _
/'\_/`\     _    ( ) ( )        _ (  _  )   (  _`\ ( )_                       (  _ \  _        ( )_
|     |   /'_`\  | `\| | _   _ (_)| (_) |   | (_(_)| ,_)   _    _ __   __     | |_) )(_)   ___ | ,_) _   _  _ __   __    ___
| (_) | /'/'_` ) | , ` |( ) ( )| ||  _  |   `\__ \ | |   /'_`\ ( '__)/'__`\   | ,__/ | | /'___)| |  ( ) ( )( '__)/'__`\/',__)
| | | |( ( (_| | | |`\ || (_) || || | | |   ( )_) || |_ ( (_) )| |  (  ___/   | |    | |( (___ | |_ | (_) || |  (  ___/\__, \
(_) (_) \ `\__,_)(_) (_)`\___/'(_)(_) (_)   `\____)`\__)`\___/'(_)  `\____)   (_)    (_)`\____)`\__)`\___/'(_)  `\____)(____/
         `\_____)

ASCII;


echo "\n\n$ascii \n\n";
if (count($argv) === 2) {
    $directory = $argv[1];
} elseif (count($argv) === 3) {
    $directory = $argv[1];
    switch ($argv[2]) {
        case 'true':
            $setOrder = true;
            break;
        case 'false':
            $setOrder = false;
            break;
        default:
            die("ERROR : not a valid boolean '{$argv[2]}' \n");
    }
}

if (! is_null($directory) && is_dir($directory)) {
    dirList($directory, $setOrder);
} else {
    die("ERROR : not a valid directory '$directory' \n");
}

function dirList($directory, $sortOrder)
{

    // Get each file and add its details to two arrays
    $results = array();
    $handler = opendir($directory);
    $cameraInfo = $file_dates = $file_names = array();
    while ($file = readdir($handler)) {
        if (! is_dir($directory . '/' . $file) && $file != '.' && $file != '..' && $file != "robots.txt" && $file != ".htaccess") {
            $imagePath = $directory . "/" . $file;
            $metaInfo = cameraUsed($imagePath);
            $currentModified = filectime($imagePath);
            $creationDate = new DateTime();
            $creationDate->setTimestamp($currentModified);
            if ($metaInfo['date'] !== 'Unavailable') {
                $creationDate = new DateTime($metaInfo['date']);
            }
            $file_names[] = $file;
            $file_dates[] = $creationDate;
            $cameraInfo[] = $metaInfo;
        }
    }
    closedir($handler);
    if( empty($file_names)){
        die("ERROR : There is no file to process into '$directory' \n");
    }
    // Sort the date array by preferred order
    if ($sortOrder == "newestFirst") {
        arsort($file_dates);
    } else {
        asort($file_dates);
    }

    // Match file_names array to file_dates array
    $file_names_Array = array_keys($file_dates);
    foreach ($file_names_Array as $idx => $name)
        $name = $file_names[$name];
    $file_dates = array_merge($file_dates);

    $i = 0;
    // Loop through dates array and then echo the list
    foreach ($file_dates as $file_dates) {
        $date = $file_dates;
        $j = $file_names_Array[$i];
        $file = $file_names[$j];
        $meta = $cameraInfo[$j];
        $i ++;
        $coolDate = $date->format('Ymd');
        $directoryPath = $directory . '/' . $coolDate;
        if (! is_dir($directoryPath) && ! file_exists($directoryPath)) {
            mkdir($directoryPath);
        }
        if (! is_dir($directoryPath.'/meta_info') && ! file_exists($directoryPath.'/meta_info')) {
            mkdir($directoryPath.'/meta_info');
        }
        copy($directory . '/' . $file, $directoryPath . '/' . $file);
        file_put_contents($directoryPath . '/meta_info/' . $file . '_meta_info', var_export($meta, true));
        echo "File name: $file - Date Added: $coolDate. \n";
    }
}

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
