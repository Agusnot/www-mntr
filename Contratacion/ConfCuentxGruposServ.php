<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
	if(!$Anio){$Anio=$ND[year];}
	if($Guardar){
		$cons4="delete from contratacionsalud.cuentaxgrupos where compania='$Compania[0]' and codigo='$Codigo' and grupo='$Grupo'";
		$res4=ExQuery($cons4);
		$cons3="select tipo from central.tiposaseguramiento";
		$res3=ExQuery($cons3);
		while($fila3=ExFetch($res3)){			
			$cons4="insert into contratacionsalud.cuentaxgrupos (compania,codigo,grupo,cuentaconta,tipoaseg) 
			values ('$Compania[0]','$Codigo','$Grupo','".$CuentaCaja[$fila3[0]]."','$fila3[0]')";
			$res4=ExQuery($cons4);
		}
	?>
    	<script language="javascript">
        	location.href="ConfGruposServicios.php?DatNameSID=<? echo $DatNameSID?>";
        </script>
    <?
	}
	if($Edit){
		$cons="select codigo,grupo,cuentaconta,tipoaseg,Nombre from contratacionsalud.cuentaxgrupos,Contabilidad.PlanCuentas
		where cuentaxgrupos.compania='$Compania[0]' and codigo='$Codigo' and grupo='$Grupo' and PlanCuentas.Compania='$Compania[0]' and Anio=$Anio
		and cuenta=cuentaconta";	
		//echo $cons;
		//$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Contabilidad.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$Anio Order By Cuenta";
		$res=ExQuery($cons);
		while($fila=ExFetch($res)){
			$Datos[$fila[3]]=array($fila[2],$fila[4]);
		}
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='1px';
		document.getElementById('Busquedas').style.left='740';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{			
		document.getElementById('Busquedas').style.display='none';
	}
	function Validar()
	{
		
	<?	$cons2="select tipo from central.tiposaseguramiento";
		$res2=ExQuery($cons2);
		while($fila2=ExFetch($res2)){?>			
			if(document.getElementById('CuentaCont[<? echo $fila2[0]?>]').value==""){alert("Debe seleccionar todas Cuenta Contable!!!");return false;}
							
	<?	}?>
	}
	function BuscarCC(Objeto,Cuenta,Aux,Sig) // CON ESTA FUNCION REALIZO LA BUSQUEDA DE LOS DATOS. EN ESTE CASO EL TERCERO RECIBIENDO EL OBJETO YLA CUENTA
	{		
		Mostrar();
		frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CCG&Reporteador=1&Objeto='+Objeto+'&Anio=<? echo $Anio?>&CC='+Cuenta+'&Objeto2='+Aux+'&SigObjeto='+Sig;		
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table  BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td colspan="3" align="center">GRUPO <? echo strtoupper($Grupo)?></td>
    </tr>
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Regimen</td><td colspan="2">Cuenta Contable</td>
	</tr>
<?	$cons="select tipo from central.tiposaseguramiento";
	$res=ExQuery($cons);
	while($fila=ExFetch($res)){?>
		<tr>
        	<td><? echo $fila[0]?></td>
            <td  onfocus="Ocultar();" bgcolor="#e5e5e5" style="font-weight:bold">Cuenta Contable</td>
			<td bgcolor="#e5e5e5" style="font-weight:bold">
      	<?	if($Datos[$fila[0]][0]){
				$CuentaCaja[$fila[0]]=$Datos[$fila[0]][0];
				$NomCuenta[$fila[0]]=$Datos[$fila[0]][1];
				$CuentaCont[$fila[0]]=$Datos[$fila[0]][0];
			}?>
            <input type="text" id="CuentaCaja[<? echo $fila[0]?>]" name="CuentaCaja[<? echo $fila[0]?>]" value="<? echo $CuentaCaja[$fila[0]]?>"
			
            onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaCaja[<? echo $fila[0]?>]&Objeto1=NomCuenta[<? echo $fila[0]?>]&Objeto2=CuentaCont[<? echo $fila[0]?>]&SigObjeto=Guardar&Cuenta='+this.value+'&Anio=<? echo $Anio?>';" 
			
            onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaCaja[<? echo $fila[0]?>]&Objeto1=NomCuenta[<? echo $fila[0]?>]&Objeto2=CuentaCont[<? echo $fila[0]?>]&SigObjeto=Guardar&Cuenta='+this.value+'&Anio=<? echo $Anio?>';ValCuenta.value=0" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"
            
            onChange="document.FORMA.CuentaCont[<? echo $fila[0]?>].value='';document.FORMA.NomCuenta[<? echo $fila[0]?>].value=''" size="10"/>
 		
        		<input type="text" name="NomCuenta[<? echo $fila[0]?>]" id="NomCuenta[<? echo $fila[0]?>]" value="<? echo $NomCuenta[$fila[0]]?>" style="border:thin" onFocus="Ocultar();" readonly/ style="width:400">
                <input type="hidden" name="CuentaCont[<? echo $fila[0]?>]" id="CuentaCont[<? echo $fila[0]?>]" value="<? echo $CuentaCont[$fila[0]]?>"/>
     		</td>
        </tr>	
<?	}?>    
    <tr>
    	<td colspan="3" align="center"><input type="submit" value="Guardar" name="Guardar">
	<?	if($Edit){?>    
			<input type="button" value="Cancelar" onClick="location.href='ConfGruposServicios.php?DatNameSID=<? echo $DatNameSID?>'">
	<?	}?>		
    	</td>
	</tr>
</table>    
<input type="hidden" name="Grupo" value="<? echo $Grupo?>">
<input type="hidden" name="Codigo" value="<? echo $Codigo?>">
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="" frameborder="0" height="400"></iframe>
</body>
</html>
