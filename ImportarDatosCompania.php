<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	
	if($Iniciar)
	{

		while (list($val,$cad) = each ($BD)) 
		{
			$cons="Select * from $val";
			$res=ExQuery($cons);
			for($i=0;$i<=ExNumFields($res)-1;$i++)
			{
				$Campos=$Campos.ExFieldName($res,$i).",";
			}
			$Campos=substr($Campos,0,strlen($Campos)-1);
			$Campos2=str_replace("Compania","'$Entidad'",$Campos);
			$Campos2=str_replace("compania","'$Entidad'",$Campos);
			$cons2="Insert into $val ($Campos) Select $Campos2 from $val where Compania='$EntOrigen'";
			$Campos="";$Campos2="";
			$res2=ExQuery($cons2);
			if($res2==1)
			{
				$NumTablas++;
			}
		}
		exit;
?>
        <script language="javascript">
			alert("Se cargaron <?echo $NumTablas?> tablas exitosamente");
			window.close();
		</script>
        <?
	}
?>
<em>
<head><title>Importe de Informaci&oacute;n</title></head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA">
<table width="100%" border="1" rules="groups" bordercolor="#666699" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr style="color:white;font-weight:bold" align="center" bgcolor="#666699">
<td colspan="3">Compa&ntilde;ia Origen</td>
<tr><td colspan="3"><center>
<select style="width:550px;" name="EntOrigen">
<?
	$cons="Select Nombre from Central.Compania where Nombre!='$Entidad'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<option value='$fila[0]'>$fila[0]</option>";
	}
	$Msj="<img alt='Documento con registros' src='/Imgs/b_deltbl.png'>";
?>
</select>
</td></tr>
<tr style="color:white;font-weight:bold" align="center" bgcolor="#666699"><td>Documentos</td><td>Contabilidad</td><td>Presupuesto</td>
<tr><td>Plan de Cuentas y Estructura</td><td align="center">
<? $cons="Select * from Contabilidad.PlanCuentas where Compania='$Entidad'";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1; ?>
<input type="Hidden" id="Estructura" name="BD[Contabilidad.EstructuraPUC]">
<input type="Checkbox" name="BD[Contabilidad.PlanCuentas]" onClick="Estructura.value=this.value"><? }else{echo "$Msj";}?></td><td align="center">
<? $cons="Select * from Presupuesto.PlanCuentas where Compania='$Entidad'";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1; ?>
<input type="Hidden" id="Estructura2" name="BD[Presupuesto.EstructuraPUC]">
<input type="Checkbox" name="BD[Presupuesto.PlanCuentas]" onClick="Estructura2.value=this.value"><? }else{echo "$Msj";}?></td></tr>
<tr><td>Comprobantes</td><td align="center">
<? $cons="Select * from Contabilidad.Comprobantes where Compania='$Entidad'";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
<input type="Checkbox" name="BD[Contabilidad.Comprobantes]"><?}else{echo "$Msj";}?></td><td align="center">
<? $cons="Select * from Presupuesto.Comprobantes where Compania='$Entidad'";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
<input type="Checkbox" name="BD[Presupuesto.Comprobantes]"><? }else{echo "$Msj";}?></td></tr>
<tr><td>Cruce de Comprobantes</td><td align="center">
<? $cons="Select * from Contabilidad.CruzarComprobantes where Compania='$Entidad'";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
<input type="Checkbox" name="BD[Contabilidad.CruzarComprobantes]"><? }else{echo "$Msj";}?></td><td align="center"><input disabled type="Checkbox"></td></tr>
<tr><td>Bases de Retenci&oacute;n en la Fuente</td><td align="center">
<? $cons="Select * from Contabilidad.BasesRetencion where Compania='$Entidad'";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
<input type="Checkbox" name="BD[Contabilidad.BasesRetencion]"><? }else{echo "$Msj";}?></td><td align="center"><input disabled type="Checkbox"></td></tr>
<tr><td>Centros de Costo</td><td align="center">
<? $cons="Select * from Central.CentrosCosto where Compania='$Entidad'";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
<input type="Checkbox" name="BD[Central.CentrosCosto]"><? }else{echo "$Msj";}?></td><td align="center"><input disabled type="Checkbox"></td></tr>
<tr><td>Conceptos Contables</td><td align="center">
<? $cons="Select * from Contabilidad.ConceptosPago where Compania='$Entidad'";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
<input type="Checkbox" name="BD[Contabilidad.ConceptosPago]"><?}else{echo "$Msj";}?></td><td align="center"><input disabled type="Checkbox"></td></tr>
<tr><td>Cruce de Cuentas Cero</td><td align="center"><input type="Checkbox" disabled></td><td align="center">
<? $cons="Select * from Presupuesto.CruceCuentasCero where Compania='$Entidad'";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
<input type="Checkbox" name="BD[Presupuesto.CruceCuentasCero]"><? }else{echo "$Msj";}?></td></tr>
<input type="hidden" name="Entidad" value="<? echo $Entidad?>" />
<tr><td>Cuentas de Cierre</td><td align="center">
<? $cons="Select * from Contabilidad.CuentasCierre where Compania='$Entidad'";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
<input type="Checkbox" name="BD[Contabilidad.CuentasCierre]"><? }else{echo "$Msj";}?></td><td align="center"><input disabled type="Checkbox"></td></tr>


<tr><td colspan="3"><center>
<? if($Haga==1){?>
<input type="submit" name="Iniciar" value="Iniciar"><? }?></td></tr>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>