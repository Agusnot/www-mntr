<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($ND[mon]<10){$cero='0';}else{$cero='';}
	if($ND[mday]<10){$cero1='0';}else{$cero1='';}
	$FechaComp="$ND[year]-$cero$ND[mon]-$cero1$ND[mday]";
	
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
		if(!$FechaIni){
			$cons="insert into salud.plantillanotas(compania,usuario,cedula,fechaini,nota,justificacion,numservicio,estado,idescritura,numorden) values        
			('$Compania[0]','$usuario[1]','$Paciente[1]','$ND[year]-$ND[mon]-$ND[mday]','$Nota','$Justificacion',$NumServ,'AC',$IdEscritura,$AutoId)";
		}
		else{
			$cons="insert into salud.plantillanotas(compania,usuario,cedula,fechaini,fechafin,nota,justificacion,numservicio,estado) values        
			('$Compania[0]','$usuario[1]','$Paciente[1]','$FechaIni','$FechaFin','$Nota','$Justificacion',$NumServ,'AC')";
		}
		$res = ExQuery($cons);echo ExError($res);
		//echo "$cons<br>\n";
		$cons="insert into salud.ordenesmedicas(compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo) values
		('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$NumServ,'$Nota',$IdEscritura,$AutoId,'$usuario[1]','Nota','AC',1)";				
		$res = ExQuery($cons);echo ExError($res);
		//echo $cons;		?>        
		<script language="javascript">
			location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>';
		</script><?		
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
function Validar()
{

	if(document.FORMA.Nota.value=="")
	{		
		alert("La nota no puede quedar vacia!!!");
	}
	else
	{	
		if(document.FORMA.Radios.value=="Definido"){
			if(document.FORMA.FechaIni.value==''||document.FORMA.FechaFin.value==''){
				alert("Debe seleccionar la fecha de inicio y la fecha final!!!");
			}else{	
				if(document.FORMA.FechaIni.value<document.FORMA.FechaComp.value){		
					alert("La fecha inicial seleccionada es invalida!!!");
				}
				else{					
					if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){
						alert("La fecha inicial es menor a la fecha final");
					}
					else{
						document.FORMA.Guardar.value=1;
						document.FORMA.submit();
					}
				}
			}
		}
		else{
			document.FORMA.Guardar.value=1;
			document.FORMA.submit();				
		}
	}
}
function Agrega(VrBoton){
	if(document.FORMA.Nota.value!=""){
		document.FORMA.Nota.value=document.FORMA.Nota.value+" "+VrBoton.name;	
	}
	else
	{
		document.FORMA.Nota.value=VrBoton.name;	
	}
}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
	<tr><td colspan="4" align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Notas</td></tr>
  	<tr>
    	<td colspan="4" align="center"><textarea name="Nota" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" cols="70" rows="7"><? if ($Limpiar){echo trim("$RadioNota $Nota");}else{echo trim("$Nota");}?></textarea></td>
    </tr>    
<!--
    <tr><td td colspan="4" align="center">Indefinido<input type="radio" name="Vigencia" value="Indefinido" 
    <? 	if($Vigencia=="Indefinido"||$Vigencia==''){ echo " checked ";$Radios="Indefinido";} ?> onClick="FORMA.submit()" /> 
        Definido<input type="radio" name="Vigencia" value="Definido" 
    <? 	if($Vigencia=="Definido"){ echo " checked ";$Radios="Definido";} ?> onClick="FORMA.submit()" />     
    </tr>-->
<? 	if($Vigencia=="Indefinido"||$Vigencia==''){ $Radios="Indefinido";} ?>
   	<input type="hidden" name="Radios" value="<? echo $Radios?>">
<? 	if($Vigencia=="Definido"){?>
		<tr>
       		<td align="center" colspan="4">
            Desde: <input type="text" name="FechaIni" value="<? echo $FechaIni?>" readonly="readonly" size="6" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')"  
	        value="<? echo $FechaIni; ?>"  /> 
           	Hasta: <input type="text" name="FechaFin" value="<? echo $FechaFin?>" readonly="readonly" size="6"	onclick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')"  
       	    value="<? echo $FechaFin;?>"  /></td>
            <input type="hidden" name="FechaComp" value="<? echo $FechaComp?>">
	   	</tr>
<?	}
	$cons="select nota from salud.notas where compania='$Compania[0]'";
	$res=ExQuery($cons);echo ExError();
	if(ExNumRows($res)>0){
		echo "<tr>";
		$salto=0;
		while($fila=ExFetch($res)){
			if($salto==4){$salto=0;echo "</tr><tr>";}?>
			<td align="left"><input title="Agregar" type="button" name="<? echo $fila[0]?>" value="" onClick="Agrega(this)" ><? echo $fila[0]?></td>
<?			$salto++;
		}
		echo "</tr>";
	}?>
    <tr><td td colspan="4" align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Justificacion</td></tr>
    <tr><td td colspan="4" align="center"><textarea name="Justificacion" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" cols="70" rows="7"><? echo $Justificacion?></textarea></td></tr>
	<tr><td td colspan="4" align="center"><input type="button" value="Guardar" onClick="Validar()"><input type="button" value="Cancelar" onClick="location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"></td></tr>	
</table>
<input type="hidden" name="Guardar" value="">
<input type="hidden" name="Limpiar" value="">
<input type="hidden" name="IdEscritura" value="<? echo $IdEscritura?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>     
</body>
</html>
