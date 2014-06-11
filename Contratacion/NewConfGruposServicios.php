<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if(!$Anio){$Anio=$ND[year];}
	if($Guardar){
		if(!$Edit){
			$cons="insert into contratacionsalud.gruposservicio (compania,codigo,grupo,grupomeds) values ('$Compania[0]','$Codigo','$Grupo','$TipoGrupMed')";
			$res=ExQuery($cons);
		?>	<script language="javascript">location.href='ConfCuentxGruposServ.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Grupo=<? echo $Grupo?>'</script><?
		}
		else{
			$cons="update contratacionsalud.gruposservicio set codigo='$Codigo',grupo='$Grupo',grupomeds='$TipoGrupMed'
			where compania='$Compania[0]' and codigo='$CodigoAnt' and grupo='$GrupoAnt'";
			$res=ExQuery($cons);
		?>	<script language="javascript">location.href='ConfGruposServicios.php?DatNameSID=<? echo $DatNameSID?>'</script><?
		}		    
	}
	if($Edit){
		$cons="select codigo,grupo,grupomeds from contratacionsalud.gruposservicio where compania='$Compania[0]' and codigo='$Codigo' and grupo='$Grupo'";
		$res=ExQuery($cons);	
		$fila=ExFetch($res);
		$Codigo=$fila[0]; $Grupo=$fila[1]; $TipoGrupMed=$fila[2]; 
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
		document.getElementById('Busquedas').style.left='42%';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
	function Validar()
	{
		if(document.FORMA.Codigo.value==""){alert("Debe digitar el codigo!!!");return false;}
		if(document.FORMA.Grupo.value==""){alert("Debe digitar el grupo!!!");return false;}
		//if(document.FORMA.CuentaCont.value==""){alert("Debe seleccionar una Cuenta de Caja!!!");return false;}		
	}
</script>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="CuentaCont" value="<? echo $CuentaCont?>"/>
<table  BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Codigo</td>
        <td><input type="text" name="Codigo" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" value="<? echo $Codigo?>" onFocus="Ocultar()" style="width:60"></td>
	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Grupo</td>
        <td><input type="text" name="Grupo" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" value="<? echo $Grupo?>" onFocus="Ocultar()" style="width:200"></td>
	</tr>
    <!--
    <tr bgcolor="#e5e5e5" style="font-weight:bold">
    	<td  onfocus="Ocultar();">Cuenta Contable</td>
		<td><input type="text" name="CuentaCaja" value="<? echo $CuentaCaja?>"
			onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaCaja&Objeto1=NomCuenta&Objeto2=CuentaCont&SigObjeto=Guardar&Cuenta='+this.value+'&Anio=<? echo $Anio?>';" 
			onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaCaja&Objeto1=NomCuenta&Objeto2=CuentaCont&SigObjeto=Guardar&Cuenta='+this.value+'&Anio=<? echo $Anio?>';ValCuenta.value=0" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"
             onChange="document.FORMA.CuentaCont.value='';document.FORMA.NomCuenta.value=''" size="10"/>
      		<input type="text" name="NomCuenta" value="<? echo $NomCuenta?>" style="border:thin" onFocus="Ocultar();" readonly style="width:400"/></td>
    </tr>-->
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Grupo Medicamentos</td>
        <td>
        	<select name="TipoGrupMed">
            	<option></option>
                <option value="Medicamentos" <? if($TipoGrupMed=="Medicamentos"){?> selected<? }?>>Medicamentos</option>
                <option value="MedicoQuirurgico" <? if($TipoGrupMed=="MedicoQuirurgico"){?> selected<? }?>>Medico Quirurgicos</option>
				 <option value="DispositivoMedico" <? if($TipoGrupMed=="DispositivoMedico"){?> selected<? }?>>Dispositivo Medico</option>
            </select>
        </td>
  	</tr>
    <tr>
    	<td colspan="2" align="center">        
        	<input type="submit" value="Guardar" name="Guardar">
            <input type="button" value="Cancelar" onClick="location.href='ConfGruposServicios.php?DatNameSID=<? echo $DatNameSID?>'">
        </td>
    </tr>    
</table>    
<input type="hidden" name="GrupoAnt" value="<? echo $Grupo?>">
<input type="hidden" name="CodigoAnt" value="<? echo $Codigo?>">
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="" frameborder="0" height="400"></iframe>
</body>
</html>
