<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if(!$Anio){$Anio=$ND[year];}
	if($Guardar){
		if(!$Edit){
			$cons="insert into salud.especialidades (compania,especialidad,cuentacont,nomcuenta,centrocostos) values ('$Compania[0]','$Especialidad','$CuentaCaja','$NomCuenta','$CC')";
		}
		else{
			$cons="update salud.especialidades set especialidad='$Especialidad',cuentacont='$CuentaCaja',nomcuenta='$NomCuenta',centrocostos='$CC'
			where compania='$Compania[0]' and especialidad='$EspecialidadAnt'";
		}
		$res=ExQuery($cons);
	?>	<script language="javascript">location.href='ConfEspecialidades.php?DatNameSID=<? echo $DatNameSID?>'</script><?    
	}
	if($Edit){
		$cons="select especialidad,cuentacont,nomcuenta,centrocostos from salud.especialidades where compania='$Compania[0]' and especialidad='$Especialidad'";
		$res=ExQuery($cons);	
		$fila=ExFetch($res);
		$Especialidad=$fila[0]; $CuentaCaja=$fila[1]; $NomCuenta=$fila[2]; $CuentaCont=$fila[2]; $CC=$fila[3]; $AuxCC=$fila[3];
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
		document.getElementById('Busquedas').style.top='160px';
		document.getElementById('Busquedas').style.left='10';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{			
		document.getElementById('Busquedas').style.display='none';
	}
	function Validar()
	{
		if(document.FORMA.Especialidad.value==""){alert("Debe digitar una especialidad!!!");return false;}
		if(document.FORMA.CuentaCont.value==""){alert("Debe seleccionar una Cuenta Contable!!!");return false;}		
		if(document.FORMA.AuxCC.value==""){alert("Debe seleccionar un Centro de Costos");return false;}
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
<input type="hidden" name="CuentaCont" value="<? echo $CuentaCont?>"/>
<table  BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Especialidad</td>
        <td><input type="text" name="Especialidad" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" value="<? echo $Especialidad?>" onFocus="Ocultar()"></td>
	</tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold">
    	<td  onfocus="Ocultar();">Cuenta Contable</td>
		<td><input type="text" name="CuentaCaja" value="<? echo $CuentaCaja?>"
			onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaCaja&Objeto1=NomCuenta&Objeto2=CuentaCont&SigObjeto=Guardar&Cuenta='+this.value+'&Anio=<? echo $Anio?>';" 
			onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaCaja&Objeto1=NomCuenta&Objeto2=CuentaCont&SigObjeto=Guardar&Cuenta='+this.value+'&Anio=<? echo $Anio?>';ValCuenta.value=0" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"
             onChange="document.FORMA.CuentaCont.value='';document.FORMA.NomCuenta.value=''" size="10"/>
 		
        	<input type="text" name="NomCuenta" value="<? echo $NomCuenta?>" style="border:thin" onFocus="Ocultar();" readonly/ style="width:400">
     	</td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Centro de Costos</td>
        <td>
        	<input type="Text" name="CC" style="width:95px;" value="<? echo $CC?>" onFocus="BuscarCC(this.name,this.value,FORMA.AuxCC.name,FORMA.Guardar.name)" 
            onKeyDown="BuscarCC(this.name,this.value,FORMA.AuxCC.name,FORMA.Guardar.name)" onKeyUp="BuscarCC(this.name,this.value,FORMA.AuxCC.name,FORMA.Guardar.name)">
            <input type="hidden" name="AuxCC" value="<? echo $AuxCC?>">
     	</td>
    </tr>
    <tr>
    	<td colspan="2" align="center">        
        	<input type="submit" value="Guardar" name="Guardar">
            <input type="button" value="Cancelar" onClick="location.href='ConfEspecialidades.php?DatNameSID=<? echo $DatNameSID?>'">
        </td>
    </tr>    
</table>    
<input type="hidden" name="EspecialidadAnt" value="<? echo $Especialidad?>">
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="" frameborder="0" height="400" width="600"></iframe>
</body>
</html>
