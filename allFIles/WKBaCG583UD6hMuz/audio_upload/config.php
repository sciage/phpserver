<?php
	
	return array(
	    // Bootstrap the configuration file with AWS specific features
	    'includes' => array('_aws'),
	    'services' => array(
	        // All AWS clients extend from 'default_settings'. Here we are
	        // overriding 'default_settings' with our default credentials and
	        // providing a default region setting.
	        'default_settings' => array(
	            'params' => array(
	                'credentials' => array(
	                    'key'    => 'AKIAIFTTOM2Z43WOTZAA',
	                    'secret' => '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',
	                ),
	                'region' => 'us-west-2'
	            )
	        )
	    )
    );
?>