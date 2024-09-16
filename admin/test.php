<?php
function PassWord( $q, $status ) {
	if($status=='hide'){
		$cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
		$qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
		return( $qEncoded );
	}
	
	if($status=='show'){
	    $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
		$qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
		return( $qDecoded );
	}
}


$input = "wAQAS";
$pass = PassWord($input, 'hide');

echo $pass . '<br />' . PassWord($pass, 'show');


?>