<?
	session_start();
?>

<?php

/* JPEGCam Test Script */
/* Receives JPEG webcam submission and saves to local file. */
/* Make sure your directory has permission to write files as your web server user! */




$jpeg_data = file_get_contents('php://input');
$filename = "my_file.jpg";
//$filename = $Paciente[31].'.JPG';
$result = file_put_contents( $filename, $jpeg_data );

/*
$result = file_put_contents( $filename, file_get_contents('php://input') );
if (!$result) {
	print "ERROR: Failed to write data to $filename, check permissions\n";
	exit();
}

$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/' . $filename;
print "$url\n";*/
echo "Nombre archivo: $filename";

?>
