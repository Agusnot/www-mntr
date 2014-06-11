<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
	if(!$NewRecord){$NewRecord=0;}
	if(!$Cerrar){$Cerrar=0;}
	if($Eliminar)
	{
		$NumCar=strlen($Cuenta);

		$cons3="Select sum(NoCaracteres) from Presupuesto.EstructuraPuc where Compania='$Compania[0]' and Anio=$AnioAc";
		$res3=ExQuery($cons3,$conex);
		$fila3=ExFetch($res3);
		$NoTotalCar=$fila3[0];

		$cons2="Select NoCaracteres from Presupuesto.EstructuraPuc where Compania='$Compania[0]' and Anio=$AnioAc Order By Nivel";
		$res2=ExQuery($cons2,$conex);
		while($fila2=ExFetch($res2))
		{
			$NumCarPUC=$NumCarPUC+$fila2[0];
			if($Act){$MaxLen=$fila2[0];$Act=0;}
			if($NumCar==$NumCarPUC){$Act=1;}
		}

		$cons="Select * from Presupuesto.Movimiento where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and date_part('year',Fecha)=$AnioAc and Vigencia='$Vigencia' 
		and ClaseVigencia='$TipoVigencia'";
		$res=ExQuery($cons);
		if(ExNumRows($res)==0)
		{
			$cons1="Select * from Presupuesto.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$AnioAc and Vigencia='$Vigencia' and 
			ClaseVigencia='$TipoVigencia'";
			$res1=ExQuery($cons1);
			if(ExNumRows($res1)==1)
			{
				$cons="Delete from Presupuesto.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$AnioAc and Vigencia='$Vigencia' and 
				ClaseVigencia='$TipoVigencia'";
				$res=ExQuery($cons);
				$CtaDest=substr($Cuenta,0,strlen($Cuenta)-$MaxLen);
				$Cuenta=$CtaDest;echo $CtaBuscar;
?>		
				<script language="JavaScript">
					parent(0).Abajo.location.href="ListaCuentasPUC.php?DatNameSID=<? echo $DatNameSID?>&Seccion=<?echo substr($Seccion,1,strlen($Seccion)-2)?>&CtaBuscar=<?echo $CtaBuscar?>&Vigencia=<?echo $Vigencia?>&TipoVigencia=<?echo $TipoVigencia?>#<?echo $CtaDest?>";
					parent(0).Abajo.location.href="ListaCuentasPUC.php?DatNameSID=<? echo $DatNameSID?>&CtaBuscar=<?echo $CtaBuscar?>&Vigencia=<?echo $Vigencia?>&TipoVigencia=<?echo $TipoVigencia?>#<?echo $CtaDest?>";
					location.href="DetalleCuenta.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>&Seccion=<?echo $Seccion?>&Vigencia=<?echo $Vigencia?>&TipoVigencia=<?echo $TipoVigencia?>";
				</script>
<?			}
			else
			{
				echo "<em>Cuenta tiene subcuentas, no es posible eliminar</em>";
			}
		}
		else
		{
			echo "<em>Cuenta tiene movimiento, no es posible eliminar</em>";
		}
	}
	if($Nuevo)
	{
		$Nombre="";$Tipo="";$CentoCostos="";$Corriente="";
		$cons="Select * from Presupuesto.PlanCuentas where Cuenta='$Cuenta' and Compania='$Compania[0]' and Anio=$AnioAc and Vigencia='$Vigencia' and ClaseVigencia='$TipoVigencia'";
		$res=ExQuery($cons,$conex);
		$fila=ExFetchArray($res);
		$Naturaleza=$fila['Naturaleza'];$Tipo="";
		$Ubicar=" onload='document.FORMA.NewCuenta.focus();'";
	}

	if($Guardar)
	{
		if($NewRecord)
		{
			if($Tipo=="Titulo"){$Apropiacion=0;}
			$Cuenta=$PreNewCuenta.$NewCuenta;
			$cons="Insert into Presupuesto.PlanCuentas (Anio,Compania,Cuenta,Nombre,Naturaleza,Tipo,Vigencia,Apropiacion,ClaseVigencia)
			values('$AnioAc','$Compania[0]','" .$Cuenta. "','$Nombre','$Naturaleza','$Tipo','$Vigencia','$Apropiacion','$TipoVigencia')";
		}
		else
		{
			if($Tipo=="Titulo"){$Apropiacion=0;}
			$cons="Update Presupuesto.PlanCuentas set Nombre='$Nombre',Naturaleza='$Naturaleza',Tipo='$Tipo',
			Apropiacion='$Apropiacion'
			where Cuenta='$Cuenta' and Compania='$Compania[0]' and Anio=$AnioAc and Vigencia='$Vigencia' and ClaseVigencia='$TipoVigencia'";
			$SeccAnt=$Seccion;
			$Seccion=substr($Seccion,0,strlen($Seccion)-2);
		}
		$res=ExQuery($cons,$conex);
		if($Cerrar!=1){

		if($Tipo=="Detalle"){if($PreNewCuenta){$Cuenta=$PreNewCuenta;}}
		if($NewRecord){
		?>
		<script language="JavaScript">
			parent(0).Abajo.location.href="ListaCuentasPUC.php?DatNameSID=<? echo $DatNameSID?>&Seccion=<?echo $Seccion?>&CtaBuscar=<?echo $CtaBuscar?>&Vigencia=<?echo $Vigencia?>&TipoVigencia=<?echo $TipoVigencia?>#<?echo $PreNewCuenta?>";
			parent(0).Abajo.location.href="ListaCuentasPUC.php?DatNameSID=<? echo $DatNameSID?>&CtaBuscar=<?echo $CtaBuscar?>&Vigencia=<?echo $Vigencia?>&TipoVigencia=<?echo $TipoVigencia?>#<?echo $PreNewCuenta?>";
		</script>
		<?
		}
		if($Tipo=="Titulo" && $NewRecord){$Seccion=$Seccion."_0";}
		}

		else
		{?>
		<script language="JavaScript">
			window.close();
		</script>
<?		}
if($SeccAnt){$Seccion=$SeccAnt;}
	}

	if(!$Nuevo)
	{

		$cons="Select * from Presupuesto.PlanCuentas where Cuenta='$Cuenta' and Compania='$Compania[0]' and Anio=$AnioAc and Vigencia='$Vigencia' and ClaseVigencia='$TipoVigencia'";
		$res=ExQuery($cons,$conex);
                //echo $cons;
		$fila=ExFetchArray($res);
		$Nombre=$fila['nombre'];$Naturaleza=$fila['naturaleza'];$Tipo=$fila['tipo'];$CentoCostos=$fila['centrocostos'];$Corriente=$fila['corriente'];
		$Rec=$fila['recursocgr'];$SRecurso=$fila['origenreccgr'];$Dependencia=$fila['dependenciacgr'];$Situacion=$fila['situacioncgr'];
		$Banco=$fila['banco'];
		if(!$Vigencia){$Vigencia=$fila['vigencia'];}
		$SIDEF=$fila['codigocgr'];$SIA=$fila['sia'];$Apropiacion=$fila['apropiacion'];

		$cons9="Select CodigoFUT from Presupuesto.AmarreFUT where Compania='$Compania[0]' and CuentaPresup='$Cuenta'
		and Anio=$AnioAc and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
		$res9=ExQuery($cons9);
		$fila9=ExFetch($res9);$FUT="<br>".$fila9[0];
	}
?>
<style>body{background:<?echo $Estilo[6]?>;color:<?echo $Estilo[7]?>;font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>}</style>
<style>
.Tit1{color:white;background:<?echo $Estilo[1]?>;font-weight:bold;}
</style>
<body <?echo $Ubicar?>>
<script language="JavaScript">
	parent(2).location.href="ResumenEjecucion.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>&Ano=<?echo $Anio?>&Vigencia=<?echo $Vigencia?>&TipoVigencia=<?echo $TipoVigencia?>";
</script>
<script language="JavaScript">
	function Validar()
	{
		if(document.FORMA.NewCuenta.value.length!=document.FORMA.NewCuenta.maxLength){alert("Digitos incorrectos");return false;}
		if(document.FORMA.NewCuenta.value==""){alert("Escriba una cuenta valida!!!");return false;}
		if(document.FORMA.Nombre.value==""){alert("Escriba un nombre de cuenta valido!!!");return false;}
		if(document.FORMA.Tipo.value==""){alert("Seleccione un tipo de Cuenta!!!");document.FORMA.Tipo.focus();return false;}
	}
</script>
<form name="FORMA" onSubmit="return Validar()">
<table border="1" cellpadding="4" cellspacing="4" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>" >
<tr><td class="Tit1">Codigo</td><td colspan="5">
<?
	if($Nuevo)
	{
		echo $Cuenta;
		$NumCar=strlen($Cuenta);

		$cons3="Select sum(NoCaracteres) from Presupuesto.EstructuraPuc where Compania='$Compania[0]' and Anio=$AnioAc";
		$res3=ExQuery($cons3,$conex);
		$fila3=ExFetch($res3);
		$NoTotalCar=$fila3[0];

		$cons2="Select NoCaracteres from Presupuesto.EstructuraPuc where Compania='$Compania[0]' and Anio=$AnioAc order By Nivel";
		$res2=ExQuery($cons2,$conex);
		while($fila2=ExFetch($res2))
		{
			$NumCarPUC=$NumCarPUC+$fila2[0];
			if($Act){$MaxLen=$fila2[0];$Act=0;}
			if($NumCar==$NumCarPUC){$Act=1;}
		}
		if($Mayores==1){$MaxLen=1;}
		?>
		<input type="Hidden" name="PreNewCuenta" value="<?echo $Cuenta?>">
		<input type="Text" name="NewCuenta" style="width:<?echo ($MaxLen*15)?>px" maxlength="<?echo $MaxLen?>"></td></tr>
		<?
	}
	else
	{   
        
?>
		<input type="Text" name="Cuenta" readonly="yes" value="<?echo $Cuenta?>"></td></tr>
<?	}?>
<tr><td class="Tit1">Nombre</td><td colspan="5"><input style="width:400px;" type="Text" name="Nombre" value="<?echo $Nombre?>"></td></tr>
<tr><td class="Tit1">Naturaleza</td><td>
<select name="Naturaleza">
<option value="">-Naturaleza-</option>
<?
	$cons1="Select Naturaleza from Presupuesto.NaturalezaCuentas";
	$res1=ExQuery($cons1);
	while($fila1=ExFetch($res1))
	{
		if($fila['naturaleza']==$fila1[0]){echo "<option selected value='$fila1[0]'>$fila1[0]</option>";}
		else{echo "<option value='$fila1[0]'>$fila1[0]</option>";}
	}
?>
</select>
</td>
<td class="Tit1">Tipo</td><td>
<select name="Tipo" onChange="if(this.value=='Titulo'){CentroCostos.checked=false;CentroCostos.disabled=true}else{CentroCostos.disabled=false;}">
<option value="">-Tipo-</option>
<?
	$cons1="Select Tipo from Presupuesto.TiposCuenta";
	$res1=ExQuery($cons1);
	while($fila1=ExFetch($res1))
	{
		if($Tipo==$fila1[0]){echo "<option selected value='$fila1[0]'>$fila1[0]</option>";}
		else{echo "<option value='$fila1[0]'>$fila1[0]</option>";}
	}
?>
</select>
</td>
<?
	if($Tipo=="Titulo"){$Disab=" disabled";}
	else{$Disab="";}
?>
<td class="Tit1">Vigencia</td>
<td>
<input type="Text" name="Vigencia" readonly="yes" style="width:70px;" value="<?echo $Vigencia?>">
<?if($Vigencia!="Actual"){?>
<input type="Text" name="TipoVigencia" readonly="yes" style="width:70px;" value="<?echo $TipoVigencia?>"><?}?>
</td>
<?
	if($Tipo=="Titulo")
	{
		$ReadOnly=" readonly=yes ";
		$cons2="Select sum(Apropiacion) from Presupuesto.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$AnioAc and Vigencia='$Vigencia' and ClaseVigencia='$TipoVigencia'";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		$Apropiacion=number_format($fila2[0],2);

	}
	else{$ReadOnly="";}
	if($Nuevo){$Apropiacion=0;}
?>
<tr><td class="Tit1">Apropiacion</td><td><input style="text-align:right"  type="Text" <?echo $ReadOnly?> name="Apropiacion" value="<?echo $Apropiacion?>"></td>
<td colspan="4"><center><input type="Button" value="Cuenta Cero" style="width:90px;" onClick="open('ConfCuentaCero.php?DatNameSID=<? echo $DatNameSID?>&CtaPresupuestal=<?echo $Cuenta?>&NomCuenta=<?echo $Nombre?>&Anio=<?echo $AnioAc?>&Vigencia=<?echo $Vigencia?>&ClaseVigencia=<?echo $TipoVigencia?>','','width=700,height=500,scrollbars=yes')">
<input type="Button" value="PAC" onClick="open('ConfPAC.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>&Anio=<?echo $AnioAc?>&Naturaleza=<?echo $Naturaleza?>','','width=650,height=500,scrollbars=yes')"  style="width:90px;"></td>

</tr>
<tr>

<td colspan="2" style="cursor:hand" onClick="open('AsignarCGR.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<? echo $Cuenta?>&Vigencia=<? echo $Vigencia?>&Anio=<? echo $AnioAc?>&ClaseVigencia=<?echo $TipoVigencia?>','','width=800,height=600,scrollbars=yes')"><center><img width="70" src="/Imgs/SIDEF.jpg"><BR><strong><?echo $SIDEF."-".$Rec."-".$SRecurso?></td>

<td colspan="3" style="cursor:hand" align="center" onClick="open('AsignaSIA.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>&Anio=<?echo $AnioAc?>&ClaseVigencia=<?echo $TipoVigencia?>&Vigencia=<?echo $Vigencia?>','','width=400,height=600,scrollbars=yes')"><center>
<img border="0" src="/Imgs/LogoSIA.jpg" width="60"><br><strong><?echo $SIA?></center></strong></td>

<td align="center" style="cursor:hand" align="center" onclick="open('AsignaFUT.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>&Anio=<?echo $AnioAc?>&ClaseVigencia=<?echo $TipoVigencia?>&Vigencia=<?echo $Vigencia?>','','width=930,height=850,scrollbars=yes')"><img src="/Imgs/FUT.jpg"><?echo $FUT?></td>

</tr>
</table>
<br>
<?
	$cons="Select * from Presupuesto.Movimiento where Compania='$Compania[0]' and Cuenta ilike '$Cuenta%' and date_part('year',Fecha)=$AnioAc 
	and Vigencia='$Vigencia' and ClaseVigencia='$TipoVigencia'";
	$res=ExQuery($cons);
	$NumRecs=ExNumRows($res);
	if($Nuevo){$NumRecs=0;}
	if($NumRecs==0){?>
<input type="Submit" name="Guardar" value="Guardar">
<?}if(!$Nuevo){
if($NumRecs==0){?>
<input type="Button" value="Eliminar" onClick="location.href='DetalleCuenta.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Cuenta=<?echo $Cuenta?>&Vigencia=<?echo $Vigencia?>&TipoVigencia=<?echo $TipoVigencia?>'">
<?}
	if($Tipo=="Titulo")
	{
?>
	<input type="Submit" name="Nuevo" value="Nuevo">
<?
	}}
?>

<input type="Hidden" name="NewRecord" value="<?echo $Nuevo?>">
<input type="Hidden" name="Long" value="<?echo $MaxLen?>">
<input type="Hidden" name="Seccion" value="<?echo $Seccion?>">
<input type="Hidden" name="Cerrar" value="<?echo $Cerrar?>">
<input type="Hidden" name="CtaBuscar" value="<?echo $CtaBuscar?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</table>
<hr>
</body>
</html>
