<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
	if($RetirarConf)
	{
		$cons2="Delete from Presupuesto.AmarreFUT where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
		and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
		$res2=ExQuery($cons2);			
			
	}
	if($Guardar)
	{
		$cons="Select * from Presupuesto.AmarreFUT where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
		and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
		$res=ExQuery($cons);

		if($TipoFUT=="Ingresos")
		{
			if(ExNumRows($res)>0)
			{
				$cons2="Update Presupuesto.AmarreFUT set CodigoFUT='$CodigoFUT',Variable1='$TieneDocSoporte',Variable2='$NoDocSoporte',
				Variable3='$PorcDestinacion',Variable4='$VrDestinacion',ClaseFUT='$TipoFUT' where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
				and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";			
			}	
			else
			{
				$cons2="Insert into Presupuesto.AmarreFUT (Compania,CuentaPresup,Anio,Vigencia,ClaseFUT,ClaseVigencia,CodigoFUT,Detalle,Variable1,
				Variable2,Variable3,Variable4) values ('$Compania[0]','$Cuenta',$Anio,'$Vigencia','$TipoFUT','$ClaseVigencia','$CodigoFUT','$NombreFUT',
				'$TieneDocSoporte','$NoDocSoporte','$PorcDestinacion','$VrDestinacion')";			
			}
			$res2=ExQuery($cons2);
																															
		}
		if($TipoFUT=="Funcionamiento")
		{
			if(ExNumRows($res)>0)
			{
				$cons2="Update Presupuesto.AmarreFUT set CodigoFUT='$CodigoFUT',Variable1='$IngresosCtes',Variable2='$Participacion',
				Variable3='$OtrasFtes',ClaseFUT='$TipoFUT' where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
				and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";			
			}
			else
			{
				$cons2="Insert into Presupuesto.AmarreFUT (Compania,CuentaPresup,Anio,Vigencia,ClaseFUT,ClaseVigencia,CodigoFUT,Detalle,Variable1,
				Variable2,Variable3,Variable4) values ('$Compania[0]','$Cuenta',$Anio,'$Vigencia','$TipoFUT','$ClaseVigencia','$CodigoFUT','$NombreFUT',
				'$IngresosCtes','$Participacion','$OtrasFtes','0')";			
			}
			$res2=ExQuery($cons2);
																															
		}																

		if($TipoFUT=="Inversion")
		{
			$cons2="Delete from Presupuesto.AmarreFUT where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
			and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";			
			
			$res2=ExQuery($cons2);
			
			$cons2="Insert into Presupuesto.AmarreFUT (Compania,CuentaPresup,Anio,Vigencia,ClaseFUT,ClaseVigencia,CodigoFUT,Detalle,Variable1,
			Variable2,Variable3,Variable4) values ('$Compania[0]','$Cuenta',$Anio,'$Vigencia','$TipoFUT','$ClaseVigencia','$CodigoFUT','$NombreFUT',
			'1','$Fuente1','$PorcFte1','0')";
			$res2=ExQuery($cons2);

			for($n=2;$n<=10;$n++)
			{
				if($Fuentes[$n]!=""){
				$cons2="Insert into Presupuesto.AmarreFUT (Compania,CuentaPresup,Anio,Vigencia,ClaseFUT,ClaseVigencia,CodigoFUT,Detalle,Variable1,
				Variable2,Variable3,Variable4) values ('$Compania[0]','$Cuenta',$Anio,'$Vigencia','$TipoFUT','$ClaseVigencia','$CodigoFUT','$NombreFUT',
				'$n','$Fuentes[$n]','$PorcFte[$n]','0')";
				$res2=ExQuery($cons2);}
			}												
		}																


		if($TipoFUT=="Deuda")
		{
			$cons2="Delete from Presupuesto.AmarreFUT where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
			and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";			
			
			$res2=ExQuery($cons2);
			
			$cons2="Insert into Presupuesto.AmarreFUT (Compania,CuentaPresup,Anio,Vigencia,ClaseFUT,ClaseVigencia,CodigoFUT,Detalle,Variable1,
			Variable2,Variable3,Variable4,Porcentaje) values ('$Compania[0]','$Cuenta',$Anio,'$Vigencia','$TipoFUT','$ClaseVigencia','$CodigoFUT','$NombreFUT',
			'1','$Fuente1','$Deuda1','$Operacion1','$PorcFte1')";
			$res2=ExQuery($cons2);

			for($n=2;$n<=10;$n++)
			{
				if($Fuentes[$n]!=""){
				$cons2="Insert into Presupuesto.AmarreFUT (Compania,CuentaPresup,Anio,Vigencia,ClaseFUT,ClaseVigencia,CodigoFUT,Detalle,Variable1,
				Variable2,Variable3,Variable4,Porcentaje) values ('$Compania[0]','$Cuenta',$Anio,'$Vigencia','$TipoFUT','$ClaseVigencia','$CodigoFUT','$NombreFUT',
				'$n','$Fuentes[$n]','$Deudas[$n]','$Operaciones[$n]','$PorcFte[$n]')";
				$res2=ExQuery($cons2);}
			}												
		}																

		if($TipoFUT=="CxP")
		{
			$cons2="Delete from Presupuesto.AmarreFUT where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
			and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";			
			
			$res2=ExQuery($cons2);
			
			$cons2="Insert into Presupuesto.AmarreFUT (Compania,CuentaPresup,Anio,Vigencia,ClaseFUT,ClaseVigencia,CodigoFUT,Detalle,Variable1,
			Variable2,Variable3,Variable4,Variable5) values ('$Compania[0]','$Cuenta',$Anio,'$Vigencia','$TipoFUT','$ClaseVigencia','$CodigoFUT','$NombreFUT',
			'1','$Fuente1','$TipoActo1','$NumeroActo1','$FechaActo1')";
			$res2=ExQuery($cons2);

			for($n=2;$n<=10;$n++)
			{
				if($Fuentes[$n]!=""){
				$cons2="Insert into Presupuesto.AmarreFUT (Compania,CuentaPresup,Anio,Vigencia,ClaseFUT,ClaseVigencia,CodigoFUT,Detalle,Variable1,
				Variable2,Variable3,Variable4,Variable5) values ('$Compania[0]','$Cuenta',$Anio,'$Vigencia','$TipoFUT','$ClaseVigencia','$CodigoFUT','$NombreFUT',
				'$n','$Fuentes[$n]','$TipoActo[$n]','$NumeroActo[$n]','$FechaActo[$n]')";
				$res2=ExQuery($cons2);}
			}												
		}																

		if($TipoFUT=="Reservas")
		{
			$cons2="Delete from Presupuesto.AmarreFUT where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
			and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";			
			
			$res2=ExQuery($cons2);
			
			$cons2="Insert into Presupuesto.AmarreFUT (Compania,CuentaPresup,Anio,Vigencia,ClaseFUT,ClaseVigencia,CodigoFUT,Detalle,Variable1,
			Variable2,Variable3,Variable4,Variable5) values ('$Compania[0]','$Cuenta',$Anio,'$Vigencia','$TipoFUT','$ClaseVigencia','$CodigoFUT','$NombreFUT',
			'1','$Fuente1','$TipoActo1','$NumeroActo1','$FechaActo1')";
			$res2=ExQuery($cons2);

			for($n=2;$n<=10;$n++)
			{
				if($Fuentes[$n]!=""){
				$cons2="Insert into Presupuesto.AmarreFUT (Compania,CuentaPresup,Anio,Vigencia,ClaseFUT,ClaseVigencia,CodigoFUT,Detalle,Variable1,
				Variable2,Variable3,Variable4,Variable5) values ('$Compania[0]','$Cuenta',$Anio,'$Vigencia','$TipoFUT','$ClaseVigencia','$CodigoFUT','$NombreFUT',
				'$n','$Fuentes[$n]','$TipoActo[$n]','$NumeroActo[$n]','$FechaActo[$n]')";
				$res2=ExQuery($cons2);}
			}												
		}																

	}				

		$cons="Select CodigoFUT,Variable1,Variable2,Variable3,Variable4,ClaseFUT from Presupuesto.AmarreFUT where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
		and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
		$res=ExQuery($cons);
		if(ExNumRows($res)>0)
		{
			$fila=ExFetch($res);
			$TipoFUT=$fila[5];
			if($fila[5]=="Ingresos")
			{
				$CodigoFUT=$fila[0];$TieneDocSoporte=$fila[1];$NoDocSoporte=$fila[2];$PorcDestinacion=$fila[3];$VrDestinacion=$fila[4];
			}
			elseif($fila[5]=="Funcionamiento")
			{
				$CodigoFUT=$fila[0];$IngresosCtes=$fila[1];$Participacion=$fila[2];$OtrasFtes=$fila[3];
			}
			elseif($fila[5]=="Inversion")
			{
				$CodigoFUT=$fila[0];
				$cons="Select CodigoFUT,Variable1,Variable2,Variable3,Variable4,ClaseFUT from Presupuesto.AmarreFUT where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
				and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' and Variable1='1'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$Fuente1=$fila[2];$PorcFte1=$fila[3];
				
				$cons="Select CodigoFUT,Variable1,Variable2,Variable3,Variable4,ClaseFUT from Presupuesto.AmarreFUT where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
				and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' and Variable1!='1' Order By Variable1";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					$Fuentes[$fila[1]]=$fila[2];$PorcFte[$fila[1]]=$fila[3];				
				}
			}


			elseif($fila[5]=="Deuda")
			{
				$CodigoFUT=$fila[0];
				$cons="Select CodigoFUT,Variable1,Variable2,Variable3,Variable4,ClaseFUT,Porcentaje from Presupuesto.AmarreFUT where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
				and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' and Variable1='1'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$Fuente1=$fila[2];$PorcFte1=$fila[6];$Deuda1=$fila[3];$Operacion1=$fila[4];
				
				$cons="Select CodigoFUT,Variable1,Variable2,Variable3,Variable4,ClaseFUT,Porcentaje from Presupuesto.AmarreFUT where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
				and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' and Variable1!='1' Order By Variable1";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					$Fuentes[$fila[1]]=$fila[2];$PorcFte[$fila[1]]=$fila[6];$Deudas[$fila[1]]=$fila[3];$Operaciones[$fila[1]]=$fila[4];				
				}
			}

			elseif($fila[5]=="CxP")
			{
				$CodigoFUT=$fila[0];
				$cons="Select CodigoFUT,Variable1,Variable2,Variable3,Variable4,ClaseFUT,Variable5 from Presupuesto.AmarreFUT where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
				and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' and Variable1='1'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$Fuente1=$fila[2];$TipoActo1=$fila[3];$NumeroActo1=$fila[4];$FechaActo1=$fila[6];
				
				$cons="Select CodigoFUT,Variable1,Variable2,Variable3,Variable4,ClaseFUT,Variable5 from Presupuesto.AmarreFUT where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
				and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' and Variable1!='1' Order By Variable1";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{

					$Fuentes[$fila[1]]=$fila[2];$TipoActo[$fila[1]]=$fila[3];$NumeroActo[$fila[1]]=$fila[4];$FechaActo[$fila[1]]=$fila[6];
				}
			}

			elseif($fila[5]=="Reservas")
			{
				$CodigoFUT=$fila[0];
				$cons="Select CodigoFUT,Variable1,Variable2,Variable3,Variable4,ClaseFUT,Variable5 from Presupuesto.AmarreFUT where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
				and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' and Variable1='1'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$Fuente1=$fila[2];$TipoActo1=$fila[3];$NumeroActo1=$fila[4];$FechaActo1=$fila[6];
				
				$cons="Select CodigoFUT,Variable1,Variable2,Variable3,Variable4,ClaseFUT,Variable5 from Presupuesto.AmarreFUT where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
				and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' and Variable1!='1' Order By Variable1";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{

					$Fuentes[$fila[1]]=$fila[2];$TipoActo[$fila[1]]=$fila[3];$NumeroActo[$fila[1]]=$fila[4];$FechaActo[$fila[1]]=$fila[6];
				}
			}

		}

?>


<body background="/Imgs/Fondo.jpg" onLoad="document.FORMA.CodigoFUT.focus();">
<form name="FORMA1" method="post">
	<?
		$cons="Select * from Presupuesto.TiposConfFUT";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($fila[0]==$TipoFUT){$Check=" checked ";}
			else{$Check="";}
			echo "<em>$fila[0]</em> <input type='radio' name='TipoFUT' value='$fila[0]' $Check onclick='document.FORMA1.submit();'>";
		}
	?>
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="submit" value="Retirar Configuracion"  name="RetirarConf">
</form>
</body>
<?
	if($TipoFUT=="Ingresos")
	{
		?>
		<form name="FORMA" method="post">
		<table border="1" bordercolor="#e5e5e5" width="100%" style="font-family:<?echo $Estilo[3]?>;font-size:12;font-style:<?echo $Estilo[5]?>">
		<tr style="font-weight:bold;text-align:center" bgcolor="#e5e5e5"><td>Codigo FUT<td>Doc Soporte?</td><td>No Doc Soporte</td><td>% Dest</td><td>Vr Destinacion</td><td>Tipo Ingreso</td>
		<tr><td><input type="text" name="CodigoFUT" value="<?echo $CodigoFUT?>"></td>
		<td>
		<select name="TieneDocSoporte">
		<?if($TieneDocSoporte=="Si"){?><option selected="yes" value="Si">Si</option><?}?><option value="Si">Si</option>
		<?if($TieneDocSoporte=="No"){?><option selected="tes" value="No">No</option><?}?><option value="No">No</option>
		</select></td>
		<td><input type="text" style="width:90px;" name="NoDocSoporte" value="<?echo $NoDocSoporte?>"></td>		
		<td><input type="text" style="width:90px;" name="PorcDestinacion" value="<?echo $PorcDestinacion?>"></td>		
		<td><input type="text" name="VrDestinacion" style="width:90px;" value="<?echo $VrDestinacion?>"></td>
        <td><select name="TipoIngreso">
        <option value="Efectivo">Efectivo</option>
        <option value="Situac Fondos">Situac Fondos</option>
        </select></td>
        
        </tr>		
		<tr><td colspan="6">
		<iframe width="100%" height="400" src="FUTIngresos.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Ingresos&Codigo=<?echo $CodigoFUT?>#<?echo $CodigoFUT?>"></iframe>
		</td>
        
        </tr>
		<tr><td><input type="submit" name="Guardar" value="Guardar"> 
		<input type="button" value="Volver" onClick="window.close();" /></td></tr>
		<input type="hidden" name="TipoFUT" value="<?echo $TipoFUT?>">
        <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
		</table>
		</form>
	<?}


	if($TipoFUT=="Funcionamiento")
	{
		?>
		<form name="FORMA" method="post">
		<table border="1" bordercolor="#e5e5e5" width="100%" style="font-family:<?echo $Estilo[3]?>;font-size:12;font-style:<?echo $Estilo[5]?>">
		<tr style="font-weight:bold;text-align:center" bgcolor="#e5e5e5"><td>Codigo FUT<td>% Ingresos Corrientes</td><td>% Participaci&oacute;n Proposito gral</td><td>% Otra Fuentes</td>
		<tr><td><input type="text" name="CodigoFUT" value="<?echo $CodigoFUT?>"></td>

		<td><input type="text" style="width:90px;" name="IngresosCtes" value="<?echo $IngresosCtes?>"></td>		
		<td><input type="text" style="width:90px;" name="Participacion" value="<?echo $Participacion?>"></td>		
		<td><input type="text" style="width:90px;" name="OtrasFtes" value="<?echo $OtrasFtes?>"></td>		

		<tr><td colspan="5">
		<iframe width="100%" height="400" src="FUTIngresos.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Funcionamiento&Codigo=<?echo $CodigoFUT?>#<?echo $CodigoFUT?>"></iframe>
		</td></tr>
		<tr><td><input type="submit" name="Guardar" value="Guardar"> <input type="button" value="Volver" onClick="window.close();" /></td></tr>
        <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
		<input type="hidden" name="TipoFUT" value="<?echo $TipoFUT?>">
		</table>
		</form>
	<?}

	if($TipoFUT=="Inversion")
	{
		?>
		<form name="FORMA" method="post">
		<table border="1" bordercolor="#e5e5e5" width="100%" style="font-family:<?echo $Estilo[3]?>;font-size:12;font-style:<?echo $Estilo[5]?>">
		<tr style="font-weight:bold;text-align:center" bgcolor="#e5e5e5"><td>Codigo FUT</td><td>Fuente Principal</td><td>%</td>
		<tr><td><input type="text" name="CodigoFUT" value="<?echo $CodigoFUT?>"></td>
		<td>
		<select name="Fuente1" style="width:550px;"><option></option>
		<?
			$cons="Select trim(Fuente) from Presupuesto.FuentesFUT";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[0]==$Fuente1){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select>
		</td>
		<td><input style="width:40px;" type="text" value="<?echo $PorcFte1?>" name="PorcFte1"/></td></tr>
		
		<tr><td colspan="5">
		<iframe width="100%" height="400" src="FUTIngresos.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Inversion&Codigo=<?echo $CodigoFUT?>#<?echo $CodigoFUT?>"></iframe>
		</td></tr>
		<tr><td><input type="submit" name="Guardar" value="Guardar"/> <input type="button" value="Volver" onClick="window.close();" /></td></tr>
		<input type="hidden" name="TipoFUT" value="<?echo $TipoFUT?>"/>
		
		<?for($n=2;$n<=10;$n++){?>

		<tr>
		<td colspan="5">Fte <?echo $n?>
		<select name="Fuentes[<?echo $n?>]" style="width:750px;"><option></option>
		<?
			$cons="Select trim(Fuente) from Presupuesto.FuentesFUT";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[0]==$Fuentes[$n]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}			
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select>
		<input style="width:40px;" type="text" name="PorcFte[<?echo $n?>]" value="<?echo $PorcFte[$n]?>">
		</td>
		</tr><?}?>

		</table>
        <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
		</form>
	<?}


	if($TipoFUT=="Deuda")
	{
		?>
		<form name="FORMA" method="post">
		<table border="1" bordercolor="#e5e5e5" width="100%" style="font-family:<?echo $Estilo[3]?>;font-size:12;font-style:<?echo $Estilo[5]?>">
		<tr style="font-weight:bold;text-align:center" bgcolor="#e5e5e5"><td>Codigo FUT</td><td>Fuente</td><td>Deuda</td><td>Operacion</td><td>%</td>
		<tr><td><input type="text" name="CodigoFUT" value="<?echo $CodigoFUT?>"></td>
		<td>
		<select name="Fuente1" style="width:500px;"><option></option>
		<?
			$cons="Select trim(Fuente) from Presupuesto.FuentesFUT";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[0]==$Fuente1){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select>
		</td>
		
		<td>
		<select name="Deuda1" style="width:60px;"><option></option>
		<?
			$cons="Select trim(Deuda) from Presupuesto.DeudasFUT";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[0]==$Deuda1){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select>
		</td>
		
		<td>
		<select name="Operacion1" style="width:100px;"><option></option>
		<?
			$cons="Select trim(Operacion) from Presupuesto.OperacionesFUT";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[0]==$Operacion1){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select>
		</td>


		<td><input style="width:40px;" type="text" value="<?echo $PorcFte1?>" name="PorcFte1"/></td></tr>
		
		<tr><td colspan="5">
		<iframe width="100%" height="400" src="FUTIngresos.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Deuda&Codigo=<?echo $CodigoFUT?>#<?echo $CodigoFUT?>"></iframe>
		</td></tr>
		<tr><td><input type="submit" name="Guardar" value="Guardar"/> <input type="button" value="Volver" onClick="window.close();" /></td></tr>
		<input type="hidden" name="TipoFUT" value="<?echo $TipoFUT?>"/>

		<tr style="font-weight:bold" bgcolor="#e5e5e5"><td colspan="2">Fuentes Adicionales</td><td>Deuda</td><td>Operacion</td><td>%
		
		<?for($n=2;$n<=9;$n++){?>
		<tr>
		<td colspan="2" >
		<select name="Fuentes[<?echo $n?>]" style="width:600px;"><option></option>
		<?
			$cons="Select trim(Fuente) from Presupuesto.FuentesFUT";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[0]==$Fuentes[$n]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}			
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select></td>
		<td>
		<select name="Deudas[<?echo $n?>]" style="width:60px;"><option></option>
		<?
			$cons="Select trim(Deuda) from Presupuesto.DeudasFUT";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[0]==$Deudas[$n]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select>
		</td>
		
		<td>
		<select name="Operaciones[<?echo $n?>]" style="width:100px;"><option></option>
		<?
			$cons="Select trim(Operacion) from Presupuesto.OperacionesFUT";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[0]==$Operaciones[$n]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select>
		</td>
		<td>		
		<input style="width:40px;" type="text" name="PorcFte[<?echo $n?>]" value="<?echo $PorcFte[$n]?>">
		</td>
		</tr><?}?>

		</table>
        <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
		</form>
	<?}

	if($TipoFUT=="CxP")
	{
		?>
		<form name="FORMA" method="post">
		<table border="1" bordercolor="#e5e5e5" width="100%" style="font-family:<?echo $Estilo[3]?>;font-size:12;font-style:<?echo $Estilo[5]?>">
		<tr style="font-weight:bold;text-align:center" bgcolor="#e5e5e5"><td>Codigo FUT</td><td>Fuente</td><td>Tipo Acto</td><td>No acto</td><td>Fecha acto</td>
		<tr><td><input type="text" name="CodigoFUT" value="<?echo $CodigoFUT?>" style="width:100px;"></td>
		<td>
		<select name="Fuente1" style="width:500px;"><option></option>
		<?
			$cons="Select trim(Fuente) from Presupuesto.FuentesFUT";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[0]==$Fuente1){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select>
		</td>
		
		<td>
		<select name="TipoActo1" style="width:60px;"><option></option>
		<?
			$cons="Select trim(Tipo) from presupuesto.futtiposacto";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[0]==$TipoActo1){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select>
		</td>
		
		<td>
		<input type="text" name="NumeroActo1" value="<? echo $NumeroActo1?>" style="width:80px;">
		</td>


		<td><input style="width:80px;" type="text" value="<?echo $FechaActo1?>" name="FechaActo1"/></td></tr>
		
		<tr><td colspan="5">
		<iframe width="100%" height="400" src="FUTIngresos.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CxP&Codigo=<?echo $CodigoFUT?>#<?echo $CodigoFUT?>"></iframe>
		</td></tr>
		<tr><td><input type="submit" name="Guardar" value="Guardar"/> <input type="button" value="Volver" onClick="window.close();" /></td></tr>
		<input type="hidden" name="TipoFUT" value="<?echo $TipoFUT?>"/>

		<tr style="font-weight:bold" bgcolor="#e5e5e5"><td colspan="2">Fuentes Adicionales</td><td>Tipo Acto</td><td>No acto</td><td>Fecha acto
		
		<?for($n=2;$n<=9;$n++){?>
		<tr>
		<td colspan="2" >
		<select name="Fuentes[<?echo $n?>]" style="width:600px;"><option></option>
		<?
			$cons="Select trim(Fuente) from Presupuesto.FuentesFUT";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[0]==$Fuentes[$n]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}			
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select></td>

		<td>
		<select name="TipoActo[<? echo $n?>]" style="width:60px;"><option></option>
		<?
			$cons="Select trim(Tipo) from presupuesto.futtiposacto";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[0]==$TipoActo[$n]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select>
		</td>
		
		<td>
		<input type="text" name="NumeroActo[<? echo $n?>]" value="<? echo $NumeroActo[$n]?>" style="width:80px;">
		</td>


		<td><input style="width:80px;" type="text" value="<?echo $FechaActo[$n]?>" name="FechaActo[<? echo $n?>]"/></td>
		</tr><?}?>

		</table>
        <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
		</form>
	<?}

	if($TipoFUT=="Reservas")
	{
		?>
		<form name="FORMA" method="post">
		<table border="1" bordercolor="#e5e5e5" width="100%" style="font-family:<?echo $Estilo[3]?>;font-size:12;font-style:<?echo $Estilo[5]?>">
		<tr style="font-weight:bold;text-align:center" bgcolor="#e5e5e5"><td>Codigo FUT</td><td>Fuente</td><td>Tipo Acto</td><td>No acto</td><td>Fecha acto</td>
		<tr><td><input type="text" name="CodigoFUT" value="<?echo $CodigoFUT?>" style="width:100px;"></td>
		<td>
		<select name="Fuente1" style="width:500px;"><option></option>
		<?
			$cons="Select trim(Fuente) from Presupuesto.FuentesFUT";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[0]==$Fuente1){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select>
		</td>
		
		<td>
		<select name="TipoActo1" style="width:60px;"><option></option>
		<?
			$cons="Select trim(Tipo) from presupuesto.futtiposacto";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[0]==$TipoActo1){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select>
		</td>
		
		<td>
		<input type="text" name="NumeroActo1" value="<? echo $NumeroActo1?>" style="width:80px;">
		</td>


		<td><input style="width:80px;" type="text" value="<?echo $FechaActo1?>" name="FechaActo1"/></td></tr>
		
		<tr><td colspan="5">
		<iframe width="100%" height="400" src="FUTIngresos.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Reservas&Codigo=<?echo $CodigoFUT?>#<?echo $CodigoFUT?>"></iframe>
		</td></tr>
		<tr><td><input type="submit" name="Guardar" value="Guardar"/> <input type="button" value="Volver" onClick="window.close();" /></td></tr>
		<input type="hidden" name="TipoFUT" value="<?echo $TipoFUT?>"/>

		<tr style="font-weight:bold" bgcolor="#e5e5e5"><td colspan="2">Fuentes Adicionales</td><td>Tipo Acto</td><td>No acto</td><td>Fecha acto
		
		<?for($n=2;$n<=9;$n++){?>
		<tr>
		<td colspan="2" >
		<select name="Fuentes[<?echo $n?>]" style="width:600px;"><option></option>
		<?
			$cons="Select trim(Fuente) from Presupuesto.FuentesFUT";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[0]==$Fuentes[$n]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}			
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select></td>

		<td>
		<select name="TipoActo[<? echo $n?>]" style="width:60px;"><option></option>
		<?
			$cons="Select trim(Tipo) from presupuesto.futtiposacto";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[0]==$TipoActo[$n]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
		</select>
		</td>
		
		<td>
		<input type="text" name="NumeroActo[<? echo $n?>]" value="<? echo $NumeroActo[$n]?>" style="width:80px;">
		</td>


		<td><input style="width:80px;" type="text" value="<?echo $FechaActo[$n]?>" name="FechaActo[<? echo $n?>]"/></td>
		</tr><?}?>

		</table>
        <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
		</form>
	<?}


?>
