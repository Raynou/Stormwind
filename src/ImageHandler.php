<?php declare(strict_types = 1);

namespace Stormwind;

final class ImageHandler
{
    /**
     * Finds an image with a given ID in a URL. This function is util when you need download images from Moodle, like
     * profile images from a user.
     *
     * @param int $imageID The ID of the image
     * @return string The URL of the profile image
     * 
     */
    public static function getImageFromURL($imageID, $imageName, $host = "localhost", $port = "80", $route = "moodle/pluginfile.php/5/user/icon/boost/f1?rev")
    {
        // Change port and host according to the route where Moodle is running
        $path = [
            'host' => $host,
            'port' => $port,
            'route' => $route,
            'id' => $imageID,
        ];

        $profileImagePath = "http://{$path['host']}:{$path['port']}/{$path['route']}={$path['id']}";
        $finalPath = getcwd() . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'profile.png';

        self::downloadImageFromURL($profileImagePath, $finalPath);

        return $finalPath;
    }

    /**
     * Downloads an image from a given url and saves it as a given filename.
     * 
     * @param string $url The url of the image
     * @param string $filename The name that the downloaded resource will be have
     */
    private static function downloadImageFromURL($url, $filename)
    {
        $ch = curl_init($url);

        // Set cURL configs
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the answer instead of just show it
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
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

}
