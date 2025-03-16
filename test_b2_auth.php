<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;

// Configuration du client S3 pour Backblaze B2
$s3Client = new S3Client([
    'version'     => 'latest',
    'region'      => 'us-east-005',
    'endpoint'    => 'https://s3.us-east-005.backblazeb2.com',
    'credentials' => [
        'key'    => '6d1230aae13c',
        'secret' => '0052f708e320a1635fd05eca5904aaf2aabd0c9c8d',
    ],
    'http' => [
        'verify' => false
    ]
]);

// Création d'un nouveau bucket
try {
    $result = $s3Client->createBucket([
        'Bucket' => 'nom-de-votre-bucket',
    ]);
    echo "Bucket créé avec succès : " . $result['Location'] . "\n";
} catch (Aws\Exception\AwsException $e) {
    echo "Erreur lors de la création du bucket : " . $e->getMessage() . "\n";
}
?>
