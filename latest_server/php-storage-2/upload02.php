<?php

require __DIR__ . '/gcs-config.php';
require __DIR__ . '/cloud-storage.php';

$env = getenv('GOOGLE_APPLICATION_CREDENTIALS');

if ($env == FALSE) {
    $key_file_path = __DIR__ . "/voiceme-server-key.json";
    $res = putenv("GOOGLE_APPLICATION_CREDENTIALS=$key_file_path");
    $env = getenv('GOOGLE_APPLICATION_CREDENTIALS');
}

$gcs = new CloudStorage(GCS_PROJECT_ID, GCS_BUCKET_NAME);

foreach ($_FILES as $key => $file) {
    $link = $gcs->storeFile($file['tmp_name'], $file['type']);

    $json = array("link" => $link);
    echo json_encode($json);

   // echo $link;
}
