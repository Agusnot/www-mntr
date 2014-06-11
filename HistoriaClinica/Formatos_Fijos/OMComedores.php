<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Guardar){
		$cons="select numservicio from salud.servicios where cedula='$Paciente[1]' and compania='$Compania[0]' and estado='AC'";
		$res = ExQuery($cons);echo ExError($res);
		$fila = ExFetch($res);
		$NumServ=$fila[0];		
		$cons="select numorden from salud.ordenesmedicas where cedula='$Paciente[1]' and compania='$Compania[0]' and idescritura='$IdEscritura' order by numorden desc";
		$res = ExQuery($cons);echo ExError($res);
		if(ExNumRows($res)>0){
			$fila = ExFetch($res);		
			$AutoId = $fila[0]+1;
		}
		else{
			$AutoId=1;
		}	
		$Detalle="Comedor $Dieta";
		if($Tiene)
		{	
			//$cons2="select dieta from salud.plantilladietas where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC' and dieta!='$Dieta'";
			$cons2="select comedor from salud.plantillacomedores where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC' and comedor!='$Dieta'";
			$res2=ExQuery($cons2);echo ExError();
			if(ExNumRows($res2)>0){
				//$cons2="update salud.plantilladietas set fechafin='$ND[year]-$ND[mon]-$ND[mday]',estado='AN' where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'";
				$cons2="update salud.plantillacomedores set fechafin='$ND[year]-$ND[mon]-$ND[mday]',estado='AN' where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'";
				$res2=ExQuery($cons2);
				//echo $cons2;
				//$cons2="update salud.ordenesmedicas set estado='AN',acarreo=0 where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC' and tipoorden='Dieta'";
				$cons2="update salud.ordenesmedicas set estado='AN',acarreo=0 where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC' and tipoorden='Comedor'";
				$res2=ExQuery($cons2);
				//$Detalle2="Cambio de Dieta a $Dieta";
				$Detalle2="Cambio de Comedor a $Dieta";
				/*$cons="insert into salud.ordenesmedicas(compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo) values
				('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$NumServ,'$Detalle2',$IdEscritura,$AutoId,'$usuario[1]','Dieta','AC',1)";*/
				$cons="insert into salud.ordenesmedicas(compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo) values
				('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$NumServ,'$Detalle2',$IdEscritura,$AutoId,'$usuario[1]','Comedor','AC',1)";
				$res = ExQuery($cons);echo ExError($res);
				//echo $cons;
				/*$cons="insert into salud.plantilladietas(compania,usuario,cedula,fechaini,dieta,detalle,numservicio,estado) values        
				('$Compania[0]','$usuario[1]','$Paciente[1]','$ND[year]-$ND[mon]-$ND[mday]','$Dieta','$Detalle',$NumServ,'AC')";*/
				$cons="insert into salud.plantillacomedores(compania,usuario,cedula,fechaini,comedor,detalle,numservicio,estado) values        
				('$Compania[0]','$usuario[1]','$Paciente[1]','$ND[year]-$ND[mon]-$ND[mday]','$Dieta','$Detalle',$NumServ,'AC')";
				$res = ExQuery($cons);echo ExError($res);
			}			
		}
		else
		{
			/*$cons="insert into salud.ordenesmedicas(compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo) values
			('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$NumServ,'$Detalle',$IdEscritura,$AutoId,'$usuario[1]','Dieta','AC',1)";*/
            $cons="insert into salud.ordenesmedicas(compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo) values
			('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$NumServ,'$Detalle',$IdEscritura,$AutoId,'$usuario[1]','Comedor','AC',1)";							
			$res = ExQuery($cons);echo ExError($res);
			//echo $cons;		
			/*$cons="insert into salud.plantilladietas(compania,usuario,cedula,fechaini,dieta,detalle,numservicio,estado) values        
			('$Compania[0]','$usuario[1]','$Paciente[1]','$ND[year]-$ND[mon]-$ND[mday]','$Dieta','$Detalle',$NumServ,'AC')";*/
			$cons="insert into salud.plantillacomedores(compania,usuario,cedula,fechaini,comedor,detalle,numservicio,estado) values        
			('$Compania[0]','$usuario[1]','$Paciente[1]','$ND[year]-$ND[mon]-$ND[mday]','$Dieta','$Detalle',$NumServ,'AC')";
			$res = ExQuery($cons);echo ExError($res);
		}?>             
		<script language="javascript">
			location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>';
		</script><?	
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
function Validar(){
	var ban=0;
	for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
	{ 
		var elemento = document.forms[0].elements[i]; 
		if (elemento.type == "radio") 
		{ 
			if(elemento.checked){
				ban=1
			}
		} 	
	} 
	if(ban==0){
		alert("Debe seleccionar almenos una comedor!!!");return false;
	}	
}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
<?	//$cons="select dieta from salud.dietas where compania='$Compania[0]' group by dieta order by dieta";
    $cons="select comedor from salud.comedores where compania='$Compania[0]' group by comedor order by comedor";
	$res=ExQuery($cons);echo ExError();
	//$cons2="select dieta from salud.plantilladietas where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'";
	$cons2="select comedor from salud.plantillacomedores where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'";
	$res2=ExQuery($cons2);echo ExError();
	if(ExNumRows($res2)>0){$Tiene=1;}
	$fila2=ExFetch($res2);	
	if(ExNumRows($res)>0){?>
		<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Comedores</td></tr>        
<?		while($fila=ExFetch($res)){?>
			<tr><td><input type="radio" name="Dieta" value="<? echo $fila[0]?>" <? if($fila2[0]==$fila[0]&&$fila[0]!=''){?> checked<? }?>> <? echo $fila[0]?></td></tr>
<?		}?>
		<tr><td align="center"><input type="submit" name="Guardar" value="Guardar"><input type="button" value="Cancelar" onClick="location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"></td></tr>
<?	}
	else{?>	
    	<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">No se ha ingresado Comedores</td></tr>
		<tr><td align="center" colspan="2"><input type="button" value="Cancelar" onClick="location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"></td></tr>
<?	}?>	
</table>
<input type="hidden" name="Tiene" value="<? echo $Tiene?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>