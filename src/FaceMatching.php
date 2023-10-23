<?php
require 'vendor/autoload.php'; // Asegúrate de cargar el autoloader de Composer

use Aws\Rekognition\RekognitionClient;
use Aws\Rekognition\RekognitionException;

final class FaceMatching {

    static function loadEnv() {
        echo __DIR__;
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    }

    /**
     * Compara la similitud entre dos imágenes con la ayuda de AWS Rekognition.
     *
     * @param string $photoTarget La URL de la foto del perfil del usuario en Moodle.
     * @param string $photoSource La URL de la foto tomada en el inicio de sesión.
     *
     * @return bool Devuelve un booleano que determina si es la misma persona o no.
     */
    static function compareFaces($photoTarget, $photoSource) {
        $client = new RekognitionClient([
            'version'     => 'latest',
            'region'      => getenv('AWS_REGION'), // Asegúrate de cargar tus credenciales y configuración a través de dotenv u otro método.
            'credentials' => [
                'key'    => getenv('AWS_PUBLIC_KEY'),
                'secret' => getenv('AWS_SECRET_KEY'),
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