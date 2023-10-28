<?php declare(strict_types = 1);

namespace Stormwind;

use Aws\Rekognition\RekognitionClient;
use Aws\Rekognition\RekognitionException;

final class FaceMatching {

    /**
     * Compares the similarity between two images with AWS Rekognition help. The function uses two images for do it, the target image and the source image, the
     * first one is the image that will be serve as a reference and the second is the image that will be compared with the first one.
     *
     * @param string $photoTarget The file path of the target image
     * @param string $photoSource The file path of the source image
     *
     * @return bool Returns a boolean that determines either is the same person or not.
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

        try {
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
        } catch (RekognitionException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}