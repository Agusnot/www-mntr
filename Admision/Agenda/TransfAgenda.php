<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($ND[mon]<10){$C1="0";}else{$C1="";}
	if($ND[mday]<10){$C2="0";}else{$C2="";}
	$FecActual="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
	if($Trasladar)
	{
		$cons="select estado,hrsini,minsini,hrsfin,minsfin from salud.agenda where compania='$Compania[0]' and fecha>='$Fechaini' and fecha<='$Fechafin' and medico='$MedOrigen' 
		and estado='Pendiente'";
		$res=ExQuery($cons);		
		if(ExNumRows($res)>0)
		{
			$cons2="select fecha from salud.agenda where compania='$Compania[0]' and fecha>='$Fechaini' and fecha<='$Fechafin' and medico='$MedDestino'
			and estado!='Cancelada' group by fecha";
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)>0)
			{
				$msj="No se puede realizar el traslado de agenda debido a que el medico de destino tiene citas los dias:";
				while($fila2=ExFetch($res2))
				{
					$msj=$msj." $fila2[0],";
				}?>
				<script language="javascript">
					alert('<? echo $msj?>');
				</script>					
		<?	}
			else
			{
				$ban=0; $Dias; $ContDias=0; $Horario;
				$cons3="select fecha,idhorario,horaini,minsinicio,horasfin,minsfin 
				from salud.dispoconsexterna where compania='$Compania[0]' and fecha>='$Fechaini' and fecha<='$Fechafin' and usuario='$MedOrigen' order by fecha,idhorario";
				$res3=ExQuery($cons3);
				while($fila3=ExFetch($res3))
				{
					$cons4="select fecha from salud.dispoconsexterna where compania='$Compania[0]' and fecha='$fila3[0]' and compania='$Compania[0]' and usuario='$MedDestino'
					and idhorario='$fila3[1]' and horaini='$fila3[2]' and minsinicio='$fila3[3]' and horasfin='$fila3[4]' and minsfin='$fila3[5]'";
					//echo $cons4."<br>";
					$res4=ExQuery($cons4);
					if(ExNumRows($res4)<=0)
					{
						if($fila3[3]<10){$Cmins1="0";}else{$Cmins1="";}
						if($fila3[3]<10){$Cmins2="0";}else{$Cmins2="";}
						$ban=1; $Dias[$ContDias]=$fila3[0]; $Horario[$ContDias]="de ".$fila3[2].":".$Cmins1.$fila3[3]." a ".$fila3[4].":".$Cmins2.$fila3[5];
						$ContDias++;
					}
					if($ban==1)
					{
						echo "No se puede trasladar la agenda debido a que los horarios no coinciden los dias: <br>";
						$cont=0;
						foreach($Dias as $Ds)
						{
							echo $Ds." ".$Horario[$cont]."<br>";;
							$cont++;
						}
						
					}
					else
					{
						$cons4="select cup,hrsini,minsini,hrsfin,minsfin,tiempocons,entidad,contrato,nocontrato,cedula,medico,fecha,usucrea,usumodif,id,fechacrea,sobrecupo
						from salud.agenda where compania='$Compania[0]' and medico='$MedOrigen' and fecha>='$Fechaini' and fecha<='$Fechafin' and estado='Pendiente'";
						$res4=ExQuery($cons4);
						while($fila=ExFetch($res4))
						{
							$cons5="insert into salud.agenda								
							(compania,cup,hrsini,minsini,hrsfin,minsfin,tiempocons,entidad,contrato,nocontrato,cedula,medico,fecha,usucrea,usumodif,id,fechacrea,sobrecupo,estado
							,fechareasig) values ('$Compania[0]','$fila[0]','$fila[1]','$fila[2]','$fila[3]','$fila[4]','$fila[5]','$fila[6]','$fila[7]','$fila[8]','$fila[9]'
							,'$MedDestino','$fila[11]','$fila[12]','$fila[13]',$fila[14],'$fila[15]',$fila[16],'Pendiente'
							,'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]')";
							//echo $cons5."<br>";
							$res5=ExQuery($cons5);
							$cons5="delete from salud.agenda where compania='$Compania[0]' and medico='$MedOrigen' and fecha='$fila[11]' and id=$fila[14] and estado='Pendiente'";
							$res5=ExQuery($cons5);
							//echo $cons5."<br>";
							
						}
					}
				}
				
			}
		}
		else
		{?>
			<script language="javascript">
				alert('El medico de origen no tiene citas para trasladar en el periodo seleccionado');
			</script>
<?		}
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.MedOrigen.value==document.FORMA.MedDestino.value){alert("El medico origen debe ser diferente al de medico destino");return false;}
		if(document.FORMA.Fechaini.value==""){alert("Debe seleccionar la fecha incial");return false;}		
		if(document.FORMA.Fechaini.value<document.FORMA.FecActual.value){alert("La fecha inicial debe ser mayor o igual a la fecha actual");return false;}
		if(document.FORMA.Fechafin.value==""){alert("Debe seleccionar la fecha final");return false;}		
		if(document.FORMA.Fechafin.value<document.FORMA.FecActual.value){alert("La fecha final debe ser mayor o igual a la fecha actual");return false;}
		if(document.FORMA.Fechafin.value<document.FORMA.Fechaini.value){alert("La fecha final debe ser menor o igual a la fecha final");return false;}
		
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">
	<tr>
    <?	$cons="select nombre,medico from central.usuarios,salud.agenda where compania='$Compania[0]' and fecha>='$FecActual' and usuario=medico 
		group by nombre,medico order by nombre";
		$res=ExQuery($cons);
		//echo $cons;?>
    	<td  bgcolor="#e5e5e5" align="center" style="font-weight:bold">Medico Origen</td>
        <td>
        	<select name="MedOrigen" onChange="document.FORMA.submit();"><option></option>
            <?	while($fila=ExFetch($res)){
            		if($fila[1]==$MedOrigen){
						echo "<option value='$fila[1]' selected>$fila[0]</option>";}
					else{
						echo "<option value='$fila[1]'>$fila[0]</option>";
					}
				}
			?>
        	</select>
      	</td>
		
    <?	$cons="select especialidad from salud.medicos where compania='$Compania[0]' and usuario='$MedOrigen'";
		$res=ExQuery($cons);
		//echo $cons;
		$fila=ExFetch($res); $Esp=$fila[0];
		if($MedOrigen){
			$cons="select nombre,dispoconsexterna.usuario from central.usuarios,salud.dispoconsexterna,salud.medicos where dispoconsexterna.compania='$Compania[0]' and fecha>='$FecActual' 
			and dispoconsexterna.usuario=usuarios.usuario and medicos.compania='$Compania[0]' and medicos.usuario=dispoconsexterna.usuario and especialidad='$Esp'
			and dispoconsexterna.usuario!='$MedOrigen' group by nombre,dispoconsexterna.usuario order by nombre";
			$res=ExQuery($cons);
		}
		//echo $cons;?>
    	<td  bgcolor="#e5e5e5" align="center" style="font-weight:bold">Medico Destino</td>
        <td>
        	<select name="MedDestino">
            <?	while($fila=ExFetch($res)){
            		if($fila[1]==$MedDestino){
						echo "<option value='$fila[1]' selected>$fila[0]</option>";}
					else{
						echo "<option value='$fila[1]'>$fila[0]</option>";
					}
				}
			?>
        	</select>
      	</td>
    </tr>
    <tr>
    	<td  bgcolor="#e5e5e5" align="center" style="font-weight:bold">Desde</td>
        <td><input type="text" name="Fechaini" value="<? echo $Fechaini?>" readonly onClick="popUpCalendar(this, FORMA.Fechaini, 'yyyy-mm-dd')" style="width:80"></td>
        <td  bgcolor="#e5e5e5" align="center" style="font-weight:bold">Hasta</td>
        <td><input type="text" name="Fechafin" value="<? echo $Fechafin?>" readonly onClick="popUpCalendar(this, FORMA.Fechafin, 'yyyy-mm-dd')" style="width:80"></td>
    </tr>
    <tr align="center">
    	<td colspan="4"><input type="submit" name="Trasladar" value="Trasladar Agenda"/>
    </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="FecActual" value="<? echo $FecActual?>">
</form>    
</body>
</html>
