<?php
require __DIR__ . '/vendor/autoload.php';

# Imports the Google Cloud client library
use Google\Cloud\Storage\StorageClient;

/**
 * class CloudStorage stores images in Google Cloud Storage.
 */
class CloudStorage
{
    private $bucket;
    /**
     * CloudStorage constructor.
     *
     * @param string         $projectId The Google Cloud project id
     * @param string         $bucketName The cloud storage bucket name
     */
    public function __construct($projectId, $bucketName)
    {
        $storage = new StorageClient([
            'projectId' => $projectId,
        ]);
        $this->bucket = $storage->bucket($bucketName);
    }
    /**
     * Uploads a file to storage and returns the url of the new file.
     *
     * @param $localFilePath string
     * @param $contentType string
     *
     * @return string A URL pointing to the stored file.
     */
    public function storeFile($localFilePath, $contentType)
    {
        $f = fopen($localFilePath, 'r');
        $object = $this->bucket->upload($f, [
            'metadata' => ['contentType' => $contentType],
            'predefinedAcl' => 'publicRead',
        ]);
        return $object->info()['mediaLink'];
    }
    /**
     * Deletes a file.
     *
     * @param string $url A URL returned by a call to StorageFile.
     */
    public function deleteFile($url)
    {
        $path_components = explode('/', parse_url($url, PHP_URL_PATH));
        $name = $path_components[count($path_components) - 1];
        $object = $this->bucket->object($name);
        $object->delete();
    }
}
