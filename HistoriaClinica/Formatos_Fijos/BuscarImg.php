<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
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
		//echo $Aux;	
		$raiz=$_SERVER['DOCUMENT_ROOT'];	
		$Aux2=str_replace('\\','/',$Aux);
		//echo $Aux2;
		$cons="select ruta from salud.rutaimgsproced where compania='$Compania[0]'";
		$res=ExQuery($cons); 
		if(ExNumRows($res)>0){
			$fila=ExFetch($res);							
			if(file_exists($_SERVER['DOCUMENT_ROOT'].$fila[0].$Paciente[1]."/".$_FILES['RutaImg']['name']))
			{			echo "Entra";?>
               	<script language="javascript">
					alert("Esta imagen ya ha sido registrada!!!");
				</script>
		<?	}	
			else
			{			
				if (is_uploaded_file($_FILES['RutaImg']['tmp_name'])) 
				{
					if(strpos($_FILES['RutaImg']['type'], "jpeg")||strpos($_FILES['RutaImg']['type'], "pdf")){
						
						if(!is_dir($_SERVER['DOCUMENT_ROOT'].$fila[0].$Paciente[1]))
						{
							mkdir($_SERVER['DOCUMENT_ROOT'].$fila[0].$Paciente[1]);
							chmod($_SERVER['DOCUMENT_ROOT'].$fila[0].$Paciente[1], 0755);
						}	
						$cons2="select consecutivo from salud.consecutivoslab where compania='$Compania[0]' order by consecutivo desc";
						$res2=ExQuery($cons2);
						$fila2=ExFetch($res2);
						$Consecutivo=$fila2[0]+1;
						if(strpos($_FILES['RutaImg']['type'], "pdf")){$NewArchivo="$CodCup-$Numserv-$Consecutivo.pdf";}
						else{$NewArchivo="$CodCup-$Numserv-$Consecutivo.jpg";}
						$ArchivoFinal=$fila[0].$Paciente[1]."/".$NewArchivo;
						copy($_FILES['RutaImg']['tmp_name'], $_FILES['RutaImg']['name']); 
						rename($_FILES['RutaImg']['name'],$NewArchivo);
						copy("$raiz/HistoriaClinica/Formatos_Fijos/".$NewArchivo,"$raiz".$ArchivoFinal);
						//echo "$raiz".$ArchivoFinal;
						unlink("$raiz/HistoriaClinica/Formatos_Fijos/".$NewArchivo);					
						$cons2="insert into salud.consecutivoslab (compania,consecutivo) values ('$Compania[0]',$Consecutivo)";
						$res2=ExQuery($cons2);		
						$cons="update salud.plantillaprocedimientos set 
						rutaimg='$ArchivoFinal',usuariorutaimg='$usuario[1]',fecharutaimg='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
						where cedula='$Paciente[1]' and compania='$Compania[0]' and numservicio=$Numserv and numprocedimiento=$NumProced";
						//echo $cons;
						$res=ExQuery($cons);
						?><script language="javascript">parent.location.href='AyudasDiagnosticas.php?DatNameSID=<? echo $DatNameSID?>';</script><?
					}
					else{
						?><script language="javascript">alert("El archivo no es tipo jpg o pdf!!!");</script><?
					}
				}
				else{
					?><script language="javascript">alert("No se pudo subir el archivo!!!");</script><?
				}		
					
								
			}
		}
		else{
			?><script language="javascript">alert("No se ha configurado la ruta destino de las imagenes y pdfs!!!");</script><?
		}
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<form name="FORMA" method="post" enctype="multipart/form-data">
<table border="1" bordercolor="#e5e5e5"  align="center" style='font : normal normal small-caps 13px Tahoma;'>  
	<tr><td><input type="file" name="RutaImg" id="RutaImg" style="width:350"/></td></tr>  	
    <!--<tr><td><input type="button" value="Guardar" onclick="guarde()"</td></tr>-->
    <tr><td align="center"><input type="button" value="Guardar" onClick="CopiarURL()"/></td></tr>
    <input type="hidden" name="Aux">
</table>
<input type="hidden" name="Numserv" value="<? echo $Numserv?>">
<input type="hidden" name="NumProced" value="<? echo $NumProced?>">
<input type="hidden" name="CodCup" value="<? echo $CodCup?>">
<input type="hidden" name="Guardar">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
