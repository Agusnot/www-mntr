<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	if($Guardar){
		$ND=getdate();
		//echo $Aux;		
		$Aux2=str_replace('\\','/',$Aux);
		//echo $Aux2;
		$cons="select ruta from salud.rutaimgsanexos where compania='$Compania[0]'";
		$res=ExQuery($cons); 
		if(ExNumRows($res)>0){
			$fila=ExFetch($res);				
			if(file_exists($_SERVER['DOCUMENT_ROOT'].$fila[0].$Paciente[1]."/".$_FILES['RutaImg']['name'])&&!$Edit)
			{?>
               	<script language="javascript">
					alert("El archivo ya ha sido registrado!!!");
				</script>
		<?	}
			else
			{
				$consV="select nombre from salud.anexos where compania='$Compania[0]' and cedula='$Paciente[1]' and nombre='$Nombre'";
				$resV=ExQuery($consV);
				if(ExNumRows($resV))
				{?>
					<script language="javascript">
						alert("Este nombre ya ha sido registrado!!!");
					</script>
			<?	}
				else
				{
		 		if (is_uploaded_file($_FILES['RutaImg']['tmp_name'])) 
					{
						if(strpos($_FILES['RutaImg']['type'], "jpeg")||strpos($_FILES['RutaImg']['type'], "png")||strpos($_FILES['RutaImg']['type'], "gif")||strpos($_FILES['RutaImg']['type'], "pdf")){
						
							if(!is_dir($_SERVER['DOCUMENT_ROOT'].$fila[0].$Paciente[1]))
							{
								mkdir($_SERVER['DOCUMENT_ROOT'].$fila[0].$Paciente[1]);
								chmod($_SERVER['DOCUMENT_ROOT'].$fila[0].$Paciente[1], 0755);
							}					
							//exit;
							copy($_FILES['RutaImg']['tmp_name'], $_FILES['RutaImg']['name']); 					
							copy($_SERVER['DOCUMENT_ROOT']."/HistoriaClinica/Formatos_Fijos/".$_FILES['RutaImg']['name'],$_SERVER['DOCUMENT_ROOT'].$fila[0].$Paciente[1]."/".$_FILES['RutaImg']['name']);
							unlink($_SERVER['DOCUMENT_ROOT']."/HistoriaClinica/Formatos_Fijos/".$_FILES['RutaImg']['name']);					
							$Aux2=$fila[0].$Paciente[1]."/".$_FILES['RutaImg']['name'];
							if(!$Edit){
								$cons="insert into salud.anexos (compania,usuario,fecha,nombre,ruta,cedula) 
								values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Nombre','$Aux2','$Paciente[1]')";
							}
							else{
								$cons="update salud.anexos set nombre='$Nombre',ruta='$Aux2',usuariomod='$usuario[1]',fechamod='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
								where compania='$Compania[0]' and cedula='$Paciente[1]' and nombre='$NombreAnt' and ruta='$RutaAnt'";
							}
							//echo $cons;
							$res=ExQuery($cons);
							?><script language="javascript">location.href='Anexos.php?DatNameSID=<? echo $DatNameSID?>';</script><?
						}
						else{
							?><script language="javascript">alert("El archivo no es tipo pdf o imagen!!!");</script><?
						}
					}			
					else{
						?><script language="javascript">alert("No se pudo subir el archivo!!!");</script><?
					}
				}		
			}
		}
		else{
			?><script language="javascript">alert("No se ha configurado la ruta destino de los anexos!!!");</script><?
		}
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function CopiarURL()
	{		
		if(document.FORMA.Nombre.value==""){
			alert("Debe diguitar un nombre");
		}
		else{
			if(document.FORMA.RutaImg.value==""){
				alert("Debe seleccionar una archivo pdf o imagen");
			}
			else{
				document.FORMA.Aux.value=document.FORMA.RutaImg.value;
				document.FORMA.Guardar.value='1';
				document.FORMA.submit();			
			}
		}
	}
</script>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" enctype="multipart/form-data">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">
	<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Nombre</td>
    	<td><input type="text" name="Nombre" value="<? echo $Nombre?>" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" style="width:350"></td>
    </tr> 
    <tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Ruta</td>
    	<td><input type="file" name="RutaImg" id="RutaImg" style="width:350"/></td>
        	<input type="hidden" name="Aux">
    </tr>
    <tr>
    	<td colspan="2" align="center">	<input type="button" value="Guardar" onClick="CopiarURL()">
        								<input type="button" value="Cancelar" onClick="location.href='Anexos.php?DatNameSID=<? echo $DatNameSID?>'"></td>
    </tr>
</table>
<input type="hidden" name="Guardar">
<input type="hidden" name="NombreAnt" value="<? echo $Nombre?>">
<input type="hidden" name="RutaAnt" value="<? echo $Ruta?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>    
</body>
</html>
