<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$filename='../../Fotos/FOTO.jpg';
?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8"/>
  <script type="text/javascript" src="webcam.js"></script>
</head>
<body bgcolor="<? echo $Estilo[1]?>">
<div id='camara'>
  <script language="JavaScript">
    webcam.set_api_url('SubirFoto.php');
    webcam.set_swf_url('webcam.swf');
    webcam.set_quality(100); // JPEG quality (1 - 100)
    webcam.set_shutter_sound(false); // play shutter click sound
    webcam.set_hook("onLoad", null);
    webcam.set_hook("onComplete", null);
    webcam.set_hook("onError", null);
    document.write(webcam.get_html(250, 300));

    function camGrabar(){
      webcam.reset();
      webcam.freeze();
      document.getElementById('btnGrabar').style.display = 'none';
      document.getElementById('btnCancelar').style.display = '';
      document.getElementById('btnEnviar').style.display = '';
    }

    function camCancelar(){
      webcam.reset();
      document.getElementById('btnGrabar').style.display = '';
      document.getElementById('btnCancelar').style.display = 'none';
      document.getElementById('btnEnviar').style.display = 'none';
    }

    function camEnviar()
	{
      webcam.upload();
	<?
		$jpeg_data = file_get_contents('php://input');
		if($jpeg_data)
		{
			$result = file_put_contents($filename,$jpeg_data);
		}
	?>
		parent.parent(0).frames('topFrame').location.href=parent.parent(0).frames('topFrame').location.href + "&AjustarCedula=1";
    }
	function Cerrar()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}

  </script>
</div>
<p>
  <button onClick="camGrabar(); return false;" id='btnGrabar'>Tomar</button>
  <button onClick="camCancelar(); return false;" id='btnCancelar' style='display:none'>Repetir</button>
  <button onClick="camEnviar();" id='btnEnviar' style='display:none'>Subir</button>
  <button onClick="Cerrar();">Cerrar</button>

</p>
</body>
</html> 
