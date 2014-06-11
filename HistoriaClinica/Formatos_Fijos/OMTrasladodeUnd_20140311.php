<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Trasladar){
		$ND=getdate();
		$cons5 = "Select numservicio from Salud.Servicios where Compania = '$Compania[0]' and cedula='$Paciente[1]' and estado = 'AC' order by numservicio desc";					
		//echo $cons5;
		$res5 = ExQuery($cons5);
		$fila5 = ExFetch($res5);			
		$AutoId = $fila5[0];
		$cons="update salud.servicios set tiposervicio='$AmbitoTraslado' where compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio=$AutoId";
		$res=ExQuery($cons);
		
		$cons="update salud.pacientesxpabellones set estado='AN',lugtraslado='$UndTraslado',fechae='$ND[year]-$ND[mon]-$ND[mday]',horae='$ND[hours]:$ND[minutes]:$ND[seconds]',idcama=0 where cedula='$Paciente[1]' and compania='$Compania[0]' and ambito='$Ambito' and estado='AC' and pabellon='$Pabellon'";
		//echo $cons."<br>\n";
		$res=ExQuery($cons);echo ExError();
		$cons="insert into salud.pacientesxpabellones(usuario,cedula,pabellon,estado,fechai,horai,ambito,numservicio,compania,idcama) values ('$usuario[1]','$Paciente[1]','$UndTraslado','AC','$ND[year]-$ND[mon]-$ND[mday]','$ND[hours]:$ND[minutes]:$ND[seconds]','$AmbitoTraslado',$AutoId,'$Compania[0]',0)";
		//echo $cons;	
		$res=ExQuery($cons);echo ExError();
		
		$cons8 = "Select numorden from salud.ordenesmedicas where cedula='$Paciente[1]' and Compania = '$Compania[0]' and idescritura=$IdEscritura order by numorden desc";					
		//echo $cons8;
		$res8 = ExQuery($cons8);
		if(ExNumRows($res8)>0){
			$fila8 = ExFetch($res8);			
			$Numorden = $fila8[0]+1;
		}
		else{
			$Numorden = 1;
		}
		$Detalle="Trasladar paciente a $AmbitoTraslado Unidad $UndTraslado";
		$cons2="insert into salud.ordenesmedicas(compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo) values ('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$AutoId,'$Detalle',$IdEscritura,'$Numorden','$usuario[1]','Traslado de Unidad','AC',0)";
		//echo $cons2;
		$res2=ExQuery($cons2);echo ExError();		
	}
	$cons="select ambito,pabellon,idcama,numservicio,ambito,pabellon from salud.pacientesxpabellones where cedula='$Paciente[1]' and estado='AC' and compania='$Compania[0]'";
	$res=ExQuery($cons);echo ExError();
	$fila=ExFetch($res);	
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
function validar(){
	if(document.FORMA.UndTraslado.value==""){
		alert("Debe haber una unidad de destino!!!");return false;
	}
	if(document.FORMA.CamasDispo.value<=0){
		alert("No hay camas disponibles en la unidad de destino!!!");return false;
	}
}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">
<input type="hidden" name="CamasDispo">
<input type="hidden" name="NumSer" value="<? echo $fila[3]?>">
<input type="hidden" name="Ambito" value="<? echo $fila[4]?>">
<input type="hidden" name="Pabellon" value="<? echo $fila[5]?>">

<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Proceso</td></tr>
    <tr><td align="center"><? echo $fila[0]?></td></tr>
    <tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Servicio</td></tr>
    <tr><td align="center"><? echo $fila[1]?></td></tr>
    <tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Cama</td></tr>
<?	if($fila[2]!=0){
		$NoCama=0;?>    
	    <tr><td align="center"><? echo $fila[2]?></td></tr>
<? 	}
	else{
		echo "<tr><td align='center'>Sin Asignar</td></tr>";
		$NoCama=1;
	}?> 
 	<tr>
            <td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Proceso destino</td>
        </tr>
        <tr>
            <td align="center">
            <select name="AmbitoTraslado" onChange="document.FORMA.submit();"><option></option>
                <?
                $consxyz = "Select Ambito from Salud.Ambitos Where Compania='$Compania[0]' and Hospitalizacion=1";
                $resxyz = ExQuery($consxyz);
                while($filaxyz = ExFetch($resxyz))
                {
                    echo "<option ";
                    if($AmbitoTraslado == $filaxyz[0])
                    {
                        echo "selected";
                    }
                    echo " value='$filaxyz[0]'>$filaxyz[0]</option>";
                }
                ?>
            </select>
            </td>
        </tr>
        <tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Unidad de destino</td></tr>
<? 	$cons20="select pabellon from salud.pabellones where ambito='$AmbitoTraslado' and compania='$Compania[0]' and pabellon !='$fila[1]'";

	$res20=ExQuery($cons20);

	if(ExNumRows($res20)>0){
		$NoUnidad=0;?>
        <tr><td align="center">
        <select name="UndTraslado" onChange="document.FORMA.submit();"><option></option>
<?			while($row0=ExFetch($res20)){
				if($row0[0]==$UndTraslado){
					echo "<option value='$row0[0]' selected>$row0[0]</option>";
				}
				else{
					echo "<option value='$row0[0]'>$row0[0]</option>";
				}
			}?>
        </select></td></tr>
<?	}	
	else{
		echo "<tr><td align='center'>No hay una posible unidad de traslado</td></tr>";
		echo "<input type='hidden' name='UndTraslado' value=''>";		
	}?>
     <tr>
     <td align="center" colspan="4"><iframe scrolling="no"  id="FrameOpener" name="FrameOpener" style="display:" frameborder="0" height="100"></iframe></td>
	<tr>    
    	<td colspan="4" align="center"><input type="submit" name="Trasladar" value="Trasladar"><input type="button" value="Salir" onClick="location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"></td>
    </tr>
</table>   
<input type="hidden" name="NoCama" value="<? echo $NoCama?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form> 
<script language="javascript">
frames.FrameOpener.location.href='CamasHospitalizacion.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $AmbitoTraslado?>&UnidadHosp='+document.FORMA.UndTraslado.value
</script>
</body>
</html>
