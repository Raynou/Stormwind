<?php

final class ImageHandler
{

    /**
     * Finds the profile image with a given ID in the Moodle file system
     *
     * @param int $imageID The ID of the profile image in the user's Moodle database
     * @return string The URL of the profile image
     */
    public static function getProfileImageURL($imageID) 
    {
        // Change port and host according to the route where Moodle is running
        $path = [
            'host' => 'localhost',
            'port' => '80',
            'route' => 'moodle/pluginfile.php/5/user/icon/boost/f1?rev',
            'id' => $imageID,
        ];

        $profileImagePath = "http://{$path['host']}:{$path['port']}/{$path['route']}={$path['id']";
        $finalPath = getcwd() . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'profile.png';

        self::downloadImageFromURL($profileImagePath, $finalPath);

        return $finalPath;
    }

    /**
     * Downloads an image from a given url and saves it in a given filename.
     * 
     * @param string $url The url of the image
     * @param string $filename The name that the downloaded resource will be have
     */
    public static function downloadImageFromURL($url, $filename)
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
}
