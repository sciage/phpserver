<?php 
if($_POST['action']=='user_contact')
	{
	
			  $to  = 'sudhapatidar92@gmail.com'; 
			$subject = 'Contact mail from mobileapp';
 			$message = '<html><head><title>Contact mail from mobileapp</title></head>
			<body>
			  <p>Detail of Contact person,</p>
			    <p>First Name: '.$_POST['first_name'].'</p>
            <p>Email address: '.$_POST['contact_email'].'</p>
            <p>Your new messages is: '.$_POST['contact_message'].'</p>
			</body>
			</html>';
 			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'To: info <info@carpediemsocial.com>' . "\r\n";
			mail($to, $subject, $message, $headers);
			echo "1";	
			$_SESSION['success']="Submitted Successfully.";
			unset($_POST);
		
	}	


?>
