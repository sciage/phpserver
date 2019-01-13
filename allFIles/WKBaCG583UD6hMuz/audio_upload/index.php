<?php
	require('aws/aws-autoloader.php');

	use Aws\S3\S3Client;

	if(!empty($_FILES['file'])){


		$s3Client = S3Client::factory(array(
		    'credentials' => array(
		        'key'    => 'AKIAJ7D5GPQ5VORBJQSQ',
		        'secret' => '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',
		    ),
		    'region'=> 'us-west-2',
			'version'=>'latest'
		  )
		);

		$bucket = 'voiceme-audio-bucket';

		$filename = time().$_FILES['file']['name'];
		$tmpFile = $_FILES['file']['tmp_name'];
		$imageType = $_FILES['file']['type'];

		try {
			$result = $s3Client->putObject(array(
			    'Bucket' => $bucket,
			    'Key' => $filename,
			    'Body'   => fopen($tmpFile, 'r'),
			    'ACL' => 'public-read',
			    'ContentType' => $imageType,
			    'StorageClass' => 'STANDARD'
			));		    
		} catch (S3Exception $e) {
		    echo "There was an error uploading the file.\n";
		}

		// Get the URL the object can be downloaded from
		echo $result['ObjectURL'];
	}

?>