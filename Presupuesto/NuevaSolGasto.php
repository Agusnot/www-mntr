<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if(!$Fecha){$Fecha="$ND[year]-$ND[mon]-$ND[mday]";}
	if($Cancelar)
	{?>
		<script language="javascript">
			location.href='SolicitudGasto.php?DatNameSID=<? echo $DatNameSID?>';
		</script>
<?	}
	if($Guardar)
	{
		$consUsuDest="Select Usuario from Central.UsuariosxModulos where modulo='Aprobar Solicitud de Gasto'";
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

		if($Editar)
		{
		$cons="Update presupuesto.solgasto set 
	           item='$Items', caracteristicas='$Caracteristicas', costo=$Costo, justificacion='$Justificacion' where
			   Compania='$Compania[0]' and usuario='$usuario[0]' and Id=$Id";
		}
		else
		{


			$cons="INSERT INTO presupuesto.solgasto(
				   compania, usuario, item, caracteristicas, costo, justificacion,Fecha)
				   VALUES ('$Compania[0]', '$usuario[0]', '$Items', '$Caracteristicas',$Costo, '$Justificacion','$Fecha')";
		
			for($n=1;$n<=$i;$n++)
			{
				$cons9="insert into central.correos (compania,id,asunto,usucrea,fechacrea,usurecive,mensaje) values 
				('$Compania[0]',$AutoId,'Solicitud de Gasto Presupuestal','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
				'$UDest[$n]','Se ha realizado la solicitud de Gasto para $Items caraceristicas: $Caracteristicas <br><strong>MONTO SOLICITADO: ".number_format($Costo,2)."</strong>')";
				$res9=ExQuery($cons9);
				$AutoId++;
			}
			   
		}
		$Editar=NULL;
		$res=ExQuery($cons);
		echo ExError();
		?>
        <script language="javascript">
			location.href='SolicitudGasto.php?DatNameSID=<? echo $DatNameSID?>';
		</script>
        <?
		
	}
	if($Editar)
	{
		$cons="Select Fecha, costo, item, caracteristicas,  justificacion from Presupuesto.SolGasto where
		Compania='$Compania[0]' and Usuario='$usuario[0]' and id=$Id";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Fecha="$fila[0]";$Costo=$fila[1];$Items=$fila[2];$Caracteristicas=$fila[3];$Justificacion=$fila[4];
	}
?>
<form name="FORMA">
<br><br><center>
<table  border="1" cellpadding="4"  bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;color:white;font-weight:bold">

<tr><td bgcolor="<?echo $Estilo[1]?>" >Fecha</td><td><input type="text" readonly  value="<? echo $Fecha?>"></td>
<td bgcolor="<?echo $Estilo[1]?>" >Costo</td><td>$ <input type="text" name="Costo" value="<? echo $Costo?>"></td></tr>

<tr><td bgcolor="<?echo $Estilo[1]?>" >Item(s) solicitado(s)</td><td colspan="3"><textarea style="width:400px;height:100px;" name="Items"><? echo $Items?></textarea></td></tr>
<tr><td bgcolor="<?echo $Estilo[1]?>" >Caracteristicas</td><td colspan="3"><textarea style="width:400px;height:50px;" name="Caracteristicas"><? echo $Caracteristicas?></textarea></td></tr>
<tr><td bgcolor="<?echo $Estilo[1]?>" >Justificacion</td><td colspan="3"><textarea style="width:400px;height:50px;" name="Justificacion"><? echo $Justificacion?></textarea></td></tr>

</table>
<br>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Id" value="<? echo $Id?>">
<input type="hidden" name="Editar" value="<? echo $Editar?>">

<input type="submit" name="Guardar" value="Guardar">
<input type="submit" name="Cancelar" value="Cancelar">
</form>
</center>
</body>