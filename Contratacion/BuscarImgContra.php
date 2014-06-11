<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<html>
<head>
<script language="javascript"> 
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
	function guarde(){
		alert(document.FORMA.RutaImg.value);
	}
	function CopiarURL(){		
		document.FORMA.Aux.value=document.FORMA.RutaImg.value;
		document.FORMA.Guardar.value='1';
		document.FORMA.submit();
	}
</script>
<? 
	if($Guardar){
		$ND=getdate();		
		$Aux2=str_replace('\\','/',$Aux);
		
		$raiz=$_SERVER['DOCUMENT_ROOT'];	
		if (is_uploaded_file($_FILES['RutaImg']['tmp_name'])) 
		{
			if(strpos($_FILES['RutaImg']['type'], "pdf")){					
				
				copy($_FILES['RutaImg']['tmp_name'],"$raiz/Imgs/Contratos/".$_FILES['RutaImg']['name']);								
				$cons="update contratacionsalud.contratos set 
				imgcontrato='/Imgs/Contratos/".$_FILES['RutaImg']['name']."',usuarioimg='$usuario[1]',fechaimg='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
				where entidad='$Entidad' and compania='$Compania[0]' and Contrato='$Contrato' and numero='$NoContrato'";
				//echo $cons;
				$ruta="/Imgs/Contratos/".$_FILES['RutaImg']['name'];
				$res=ExQuery($cons);
				?><script language="javascript">
					parent.document.getElementById('RutI').value="<? echo $ruta?>";
					parent.document.getElementById('RutaImg').value="<? echo $ruta?>";
					CerrarThis();
					//parent.location.href='AyudasDiagnosticas.php?DatNameSID=<? echo $DatNameSID?>';</script><?
			}
			else{
				?><script language="javascript">alert("El archivo no es tipo pdf!!!");</script><?
			}
		}
		else{
			?><script language="javascript">alert("No se pudo subir el archivo!!!");</script><?
		}		
		
	}	
	
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<form name="FORMA" method="post" enctype="multipart/form-data">
<table border="1" bordercolor="#e5e5e5"  align="center" style='font : normal normal small-caps 13px Tahoma;'>  
	<tr><td><input type="file" name="RutaImg" id="RutaImg" style="width:350"/></td></tr>  	
    <tr><td align="center"><input type="button" value="Guardar" onClick="CopiarURL()"/></td></tr>
    <input type="hidden" name="Aux">        
</table>
<input type="hidden" name="Entidad" value="<? echo $Entidad?>">
<input type="hidden" name="Contrato" value="<? echo $Contrato?>">
<input type="hidden" name="NoContrato" value="<? echo $NoContrato?>">
<input type="hidden" name="Guardar">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
