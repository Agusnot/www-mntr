<?
	session_start();
?>

<head>
<title>Capturar Fotografia</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 20px;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><center>
      <p>Captura de imagen del paciente: <? echo "$Paciente[1]";?> </p>
  	<!-- First, include the JPEGCam JavaScript Library -->
	<script type="text/javascript" src="webcam.js"></script>
	
	<script language="JavaScript">
		webcam.set_api_url( '../Fotos/test.php' );
		webcam.set_quality( 90 ); // JPEG quality (1 - 100)
		webcam.set_shutter_sound( false ); // play shutter click sound
	</script>
	
	
	<!-- Next, write the movie to the page at 320x240, but request the final image at 160x120 -->
	<script language="JavaScript">
		document.write( webcam.get_html(640, 480) );
	</script>
	
	
	
	<br/><form>
		<input type=button value="Configurar..." onClick="webcam.configure()">
		&nbsp;&nbsp;
		<input type=button value="Tomar Fotografia" onClick="webcam.snap()">
	</form>
	
	<script language="JavaScript">
		webcam.set_hook( 'onComplete', 'my_callback_function' );
		function my_callback_function(response) {
			alert("Success! PHP returned: " + response);
		}
	</script>
    </center></td>
  </tr>
</table>
</body>
</html>
