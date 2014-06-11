<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Guardar)
	{

	$consUsuDest="Select Usuario from Central.UsuariosxModulos where modulo='Disponibilidades'";
	$resUsuDest=ExQuery($consUsuDest);
	while($filaUsuDest=ExFetch($resUsuDest))
	{
		$i++;
		$UDest[$i]=$filaUsuDest[0];
	}

	$cons="select id from central.correos where compania='$Compania[0]' order by id desc";
	//echo $cons;
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$AutoId=$fila[0]+1; 

	if($Estado){
		while (list($val,$cad) = each ($Estado)) 
		{

			if($cad=="Aprobado")
			{

				$cons33="Select item, caracteristicas, costo, justificacion,Fecha,Estado,Id 
				from Presupuesto.SolGasto 
				where Compania='$Compania[0]' and Id=$val";
				$res33=ExQuery($cons33);
				$fila33=ExFetch($res33);

				for($n=1;$n<=$i;$n++)
				{
					$cons9="insert into central.correos (compania,id,asunto,usucrea,fechacrea,usurecive,mensaje) values 
					('$Compania[0]',$AutoId,'Aprobacion de Gasto Presupuestal','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
					'$UDest[$n]','Se ha APROBADO la solicitud de Gasto para $fila33[0] caraceristicas: $fila33[1] <br><br><strong>MONTO SOLICITADO: ".number_format($fila33[2],2)."</strong><br>Se solicita favor realizar Certificado de Disponibilidad Presupuestal.')";
					$res9=ExQuery($cons9);
					$AutoId++;
				}
			}
			$cons="Update presupuesto.solgasto set Estado='$cad' where Id=$val and Compania='$Compania[0]'";
			$res=ExQuery($cons);
		}}
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA">
<?
	if($Aprobador)
	{?>
<table border="1" cellpadding="4"  bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;">
<tr bgcolor="<?echo $Estilo[1]?>" style="color:white"><td>Estado</td><td>Usuario</td></tr>
<tr>
<td>
<select name="FiltraEstado" onChange="document.FORMA.submit();">
<option <? if($FiltraEstado=='Pendiente'){ echo " selected ";}?> value="Pendiente">Pendientes</option>
<option <? if($FiltraEstado=='Aprobado'){ echo " selected ";}?> value="Aprobado">Aprobadas</option>
<option <? if($FiltraEstado=='Rechazado'){ echo " selected ";}?> value="Rechazado">Rechazadas</option>
</select>
</td>

<td>
<select name="FiltraUsuario" onChange="document.FORMA.submit();"><option></option>
<?
	$cons="Select usuario from Presupuesto.SolGasto Order By Usuario";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($fila[0]==$FiltraUsuario){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select>
</td>

</tr>

</table>
    	<br>
<?	}
?>

<table border="1" cellpadding="4"  bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;">
<tr bgcolor="<?echo $Estilo[1]?>" style="color:white;font-weight:bold"><td>Fecha</td><td>Item</td><td>Caracteristicas</td><td>Costo</td><td>Justificacion</td><td>Estado</td><td colspan="2"></td></tr>
<?
	if($Aprobador)
	{
		if(!$FiltraEstado){$FiltraEstado="Pendiente";}
		$condAdc=" and Estado='$FiltraEstado'";
		if($FiltraUsuario){$condAdc= $condAdc . " and Usuario='$FiltraUsuario'";}
	}
	else
	{
		$condAprobar=" and Usuario='$usuario[0]' ";
	}
	$cons="Select item, caracteristicas, costo, justificacion,Fecha,Estado,Id 
	from Presupuesto.SolGasto 
	where Compania='$Compania[0]' $condAprobar $condAdc Order By ID";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[4]</td><td>$fila[0]</td><td>$fila[1]</td><td align='right'>".number_format($fila[2],2)."</td><td>$fila[3]</td>";
		$Total=$Total+$fila[2];
		if($Aprobador && $fila[5]=="Pendiente")
		{
			echo "<td>
			<select name='Estado[$fila[6]]'>
			<option value='Pendiente'></option>
			<option value='Aprobado'>Aprobado</option>
			<option value='Rechazado'>Rechazado</option>
			</select>
			
			</td>";
		}
		else
		{
			echo "<td>$fila[5]</td>";
			if($fila[5]=="Pendiente")
			{
				echo "<td><a border=0 href='NuevaSolGasto.php?DatNameSID=$DatNameSID&Id=$fila[6]&Editar=1'><img border=0 src='/Imgs/b_edit.png'></a></td>";
				echo "<td><img border=0 src='/Imgs/b_drop.png'></td>";
			}
		}
		echo "</tr>";
	}
	echo "<tr align='right' bgcolor='$Estilo[1]' style='color:white;font-weight:bold'><td colspan=3>TOTAL</td><td>".number_format($Total,2)."</td></tr>";
?>
</table><br>
<?
if($Aprobador)
{
?>
<input type="submit" value="Guardar Cambios" name="Guardar">
<?	}
else
{
?>
<input type="button" value="Nueva Solicitud" onClick="location.href='NuevaSolGasto.php?DatNameSID=<? echo $DatNameSID?>'">
<?	}?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="Hidden" name="Aprobador" value="<? echo  $Aprobador?>">
</body>

</form>