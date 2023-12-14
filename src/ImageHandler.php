<?php declare(strict_types = 1);

namespace Stormwind;

/**
 * Utilities for handling images in the Moodle File System.
 */

final class ImageHandler
{
    /**
     * Finds an image with a given ID in a URL and saves it in the `tmp` folder.
     * This function is useful when you need to download images from Moodle, such as
     * user's profile images.
     *
     * @param int $imageID The ID of the image.
     * @param string $path The path where the image will be saved. This value includes the name of the image as well.
     * @param string $host The host where your Moodle installation is running.
     * @param string $port The port where your Moodle installation is running. This parameter can be empty. 
     * @param string $route The URL where the function will find the image. By default, this parameter has the value for the user profile images.
     * 
     */
    public static function getImageFromURL($imageID, $path, $host = "localhost", $port = "80", $route = "moodle/pluginfile.php/5/user/icon/boost/f1?rev")
    {
        $URL = [
            'host' => $host,
            'port' => $port,
            'route' => $route,
            'id' => $imageID,
        ];

        // In case that isn't necessary specify the port
        if($port === NULL || $port === "")
        {
            $profileImageURL = "http://{$URL['host']}/{$URL['route']}={$URL['id']}";
        }
        else
        {
            $profileImageURL = "http://{$URL['host']}:{$path['port']}/{$URL['route']}={$URL['id']}";
        }

        self::downloadImageFromURL($profileImageURL, $path);
    }

    /**
     * Downloads an image from a given URL and saves it as a given name.
     * 
     * @param string $url The url of the image
     * @param string $filename The name that the downloaded resource will have
     */
    private static function downloadImageFromURL($url, $filename)
    {
        $ch = curl_init($url);

        // Set cURL configs
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the answer instead of just show it
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL

        $response = curl_exec($ch);

        if (curl_errno($ch)) 
        {
            echo 'Error: ' . curl_error($ch);
        }

        // Save the response (the image) as a file
        file_put_contents($filename, $response);

        curl_close($ch);
    }

    /**
     * Converts a base64 string to an image.
     * 
     * @param string $uri The base64 string representing the image.
     * @param string $path The path where the new image will be saved.
     * 
     */
    public static function base64ToImage($uri, $path)
    {
        $image = fopen($path, "wb"); 
        $uri = explode(',', $uri);
        fwrite($image, base64_decode($uri[1])); 
        fclose($image); 
    }

    /**
     * Converts an image into base64 string
     * @param $path The image path
     */

    public static function imageToBase64($path)
    {
        return file_get_contents($path);
    }

}
