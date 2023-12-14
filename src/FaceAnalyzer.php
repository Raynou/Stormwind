<?php declare(strict_types = 1);

namespace Stormwind;

use Aws\Rekognition\RekognitionClient;
use Aws\Rekognition\RekognitionException;

/**
 * Utilities for face analysis using AWS Rekognition, such face comparasion or face detection.
 */

final class FaceAnalyzer {

    /**
     * Compares the similarity between two images with AWS Rekognition help. The function uses two images for do it, the target image and the source image, the
     * first one is the image that will be serve as a reference and the second is the image that will be compared with the first one.
     *
     * @param string $photoTarget The file path of the target image
     * @param string $photoSource The file path of the source image
     *
     * @return bool  A boolean that determines either is the same person or not.
     */
    public static function compareFaces($photoTarget, $photoSource) 
    {

        $client = new RekognitionClient([
            'version'     => 'latest',
            'region'      => $_ENV['AWS_REGION'],
            'credentials' => [
                'key'    => $_ENV['AWS_PUBLIC_KEY'],
                'secret' => $_ENV['AWS_SECRET_KEY'],
            ],
        ]);

        try 
        {
            $result = $client->compareFaces([
                'SimilarityThreshold' => 80,
                'SourceImage' => [
                    'Bytes' => file_get_contents($photoSource),
                ],
                'TargetImage' => [
                    'Bytes' => file_get_contents($photoTarget),
                ],
            ]);

            return count($result['FaceMatches']) !== 0 && $result['FaceMatches'][0]['Similarity'] > 80;
        } 
        catch (RekognitionException $e) 
        {
            echo 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Uses the AWS Rekognition API to detect faces in a photo, returns an array of feelings of the first person detected in that one.
     * 
     * @param string @photo A base64
     * 
     * @return array An array with the value of each feeling from a person in the photo.
     */
    public static function detectFeelings($photo)
    {
        $client = new RekognitionClient([
            'version'     => 'latest',
            'region'      => $_ENV['AWS_REGION'],
            'credentials' => [
                'key'    => $_ENV['AWS_PUBLIC_KEY'],
                'secret' => $_ENV['AWS_SECRET_KEY'],
            ],
        ]);

        $result = $client->detectFaces([
            "Attributes" => ["EMOTIONS"],
            "Image" => [
                "Bytes" => $photo
            ]
        ]);

        return $result["FaceDetails"][0]["Emotions"];
    }
}