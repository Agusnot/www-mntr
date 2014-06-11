<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");		
?>
<script language="javascript">
	
	function ValidaDocumento(Objeto){
	frames.FrameOpener.location.href="/Admision/Agenda/ValidaDocReasignar.php?DatNameSID=<? echo $DatNameSID?>&Buscar=1&Ced="+Objeto.value;
	document.getElementById('FrameOpener').style.position='absolute';
	document.getElementById('FrameOpener').style.top='90px';
	document.getElementById('FrameOpener').style.left='100px';
	document.getElementById('FrameOpener').style.display='';
	document.getElementById('FrameOpener').style.width='800';
	document.getElementById('FrameOpener').style.height='390';
	
}
</script>	
<html>
<head>
<style>
	a{color:blue; text-decoration:none;}
	a:hover{color:red; text-decoration:underline;}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">
<tr>
<?	if(!$CedulaSel){?>
	<td bgcolor="#e5e5e5" align="center" style="font-weight:bold">Cedula</td>
    <td><input type="text" name="Ced" value="<? echo $Ced?>" onFocus="ValidaDocumento(this)"  onKeyUp="ValidaDocumento(this);xLetra(this)" onKeyDown="xLetra(this)"></td>    
</tr>
<? 	}
	else{      	 
	    $cons="Select hrsini,minsini,hrsfin,minsfin,cedula,primape,segape,primnom,segnom,telefono,entidad,estado,tiempocons,fecha,medico,id from central.terceros,salud.agenda 
		where terceros.identificacion=agenda.cedula and agenda.cedula='$CedulaSel' and estado='Pendiente' and agenda.compania='$Compania[0]' and terceros.compania='$Compania[0]'";
		//echo $cons;
	 	$res=ExQuery($cons);echo ExError();
		if(ExNumRows($res)>0){?>
    		<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
				<td>Hora</td><td>Fecha</td><td>Cedula</td><td>Nombre<td>Telefono</td><td>Entidad</td><td>Especialidad</td><td>Medico</td><td>Estado</td>
			</tr>
    	 <? while($fila = ExFetchArray($res)){ 
		 		$cons2="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as Nombre  from Central.Terceros where  identificacion='$fila[10]' and Tipo='Asegurador' and Compania='$Compania[0]' order by primape";
				$res2=ExQuery($cons2);echo ExError();//consulta de la agenda
				$fila2=ExFetchArray($res2);
				
				$cons3="select nombre from central.usuarios where usuario='$fila[14]'";
				$res3=ExQuery($cons3);
				$fila3=ExFetch($res3);
				$cons4="select especialidad from salud.medicos where usuario='$fila[14]' and compania='$Compania[0]'"; 
				$res4=ExQuery($cons4);
				$fila4=ExFetch($res4);
				
		 		if($fila[3]==0){$cero5='0';}else{$cero5='';}
				if($fila[1]==0){$cero4='0';}else{$cero4='';}?>  
		 		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" align="center">
        	<?  echo "<td>$fila[0]:$fila[1]$cero4-$fila[2]:$fila[3]$cero5</td><td>$fila[13]</td><td>$fila[4]</td><td>$fila[5] $fila[6] $fila[7] $fila[8]</td><td>$fila[9]</td><td>$fila2[0]</td><td>$fila4[0]</td><td>$fila3[0]</td><td>$fila[11]</td><tr>";       
			}	
		}
		else{?>
			<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td>El usuario no tiene citas programadas</td></tr>	
	<? 	}
	}
?>

</table>
</form>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" >
</body>
</html>