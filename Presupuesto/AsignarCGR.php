<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");	
	$cons9="Select clase from central.compania where Nombre='$Compania[0]'";
	$res9=ExQuery($cons9);
	$fila9=ExFetch($res9);
	$Clase=$fila9[0];

	if($Guardar)
	{
		//echo "$Anio-->$Cuenta-->$Vigencia-->$ClaseVigencia"	;	
		$cons="Select Codigo from presupuesto.codigoscgr where Codigo='$CodCGR' and Clase='$Clase' Order By Codigo";
		$res=ExQuery($cons);
		if(ExNumRows($res)>0)
		{	
			$cons="Select Codigo from presupuesto.recursoscgr where Codigo='$RecursoCGR'";
			$res=ExQuery($cons);
			if(ExNumRows($res)>0)
			{	
				$cons="Select Codigo from presupuesto.origenespreccgr where Codigo='$Origen'";
				$res=ExQuery($cons);
				if(ExNumRows($res)>0)
				{
					$cons="Select Codigo from  presupuesto.destinacioncgr where Codigo='$Destinacion'";
					$res=ExQuery($cons);
					if(ExNumRows($res)>0)
					{
						$cons="Select Codigo from  presupuesto.finalidadcgr where Codigo='$Finalidad'";
						$res=ExQuery($cons);
						if(ExNumRows($res)>0)
						{	
							if($Situacion){$Situacion=1;}
							else{$Situacion=0;}	
							if($Vigencia=="Actual"&&!$ClaseVigencia)
							{
								$cons="Update Presupuesto.PlanCuentas set CodigoCGR='$CodCGR', RecursoCGR='$RecursoCGR', origenreccgr='$Origen',
								 DestinacionCGR='$Destinacion',FinalidadCGR='$Finalidad', SituacionCGR=$Situacion,dependenciacgr='$Dependencia' 
								 where Compania='$Compania[0]' 
								 and Anio='$Anio' and Cuenta='$Cuenta' and Vigencia='Actual'
								 and ClaseVigencia=''";
							}
							if($Vigencia=="Anteriores"&&$ClaseVigencia=="CxP")
							{
								$cons="Update Presupuesto.PlanCuentas set CodigoCGR='$CodCGR', RecursoCGR='$RecursoCGR', origenreccgr='$Origen', 
								DestinacionCGR='$Destinacion',FinalidadCGR='$Finalidad', SituacionCGR=$Situacion,dependenciacgr='$Dependencia' 
								 where Compania='$Compania[0]' 
								and Anio='$Anio' and Cuenta='$Cuenta' and Vigencia='Anteriores'and ClaseVigencia='CxP'";
							}
							if($Vigencia=="Anteriores"&&$ClaseVigencia=="Reservas")
							{
								$cons="Update Presupuesto.PlanCuentas set CodigoCGR='$CodCGR', RecursoCGR='$RecursoCGR', origenreccgr='$Origen',
								DestinacionCGR='$Destinacion', FinalidadCGR='$Finalidad', SituacionCGR=$Situacion,dependenciacgr='$Dependencia' 
								where Compania='$Compania[0]' 
								and Anio='$Anio' and Cuenta='$Cuenta' and Vigencia='Anteriores'	and ClaseVigencia='Reservas'";
							}
							$res=ExQuery($cons);	
							?><script language="javascript">window.close();</script><?	
						}
						else
						{
							?><script language="javascript">alert("El Codigo de Finalidad no Existe!!! por favor verifiquelo");</script><?
						}
					}
					else
					{
						?><script language="javascript">alert("El Codigo de Destinacion de Recurso no Existe!!! por favor verifiquelo");</script><?
					}
				}
				else
				{
					?><script language="javascript">alert("El Codigo de Origen CGR no Existe!!! por favor verifiquelo");</script><?
				}
			}
			else
			{
				?><script language="javascript">alert("El Codigo de Recurso CGR no Existe!!! por favor verifiquelo");</script><?
			}
		}
		else
		{
			?><script language="javascript">alert("El Codigo CGR no Existe!!! por favor verifiquelo");</script><?		
		}
	}
?>
<head>
<title>Compuconta Software</title>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='345px';

		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';	
	}
	function Validar()
	{
		if(document.FORMA.CodCGR.value==""){alert("Por favor ingrese el codigo de CGR!!!");return false; }
		if(document.FORMA.RecursoCGR.value==""){alert("Por favor ingrese el codigo de Recurso CGR!!!");return false;}
		if(document.FORMA.Origen.value==""){alert("Por favor ingrese el codigo de origen!!!");return false;}
		if(document.FORMA.Destinacion.value==""){alert("Por favor ingrese el codigo de Destinacion!!!");return false;}
		if(document.FORMA.CodCGR.value==""){alert("Por favor ingrese el codigo de la Finalidad CGR");return false;}
	}	
</script>

<body background="/Imgs/Fondo.jpg">
<?
if($Cuenta)
{

	$Cu=substr($Cuenta,0,1);
	if($Cu==1){$TipoCGR="INGRESOS";}
	if($Cu==2){$TipoCGR="GASTOS";}
	$cons="Select CodigoCGR, RecursoCGR, origenreccgr, DestinacionCGR, FinalidadCGR, SituacionCGR,DependenciaCGR from Presupuesto.Plancuentas
	where Compania='$Compania[0]' and Anio=$Anio and Cuenta='$Cuenta' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$CodCGR=$fila[0]; $RecursoCGR=$fila[1]; $Origen=$fila[2]; $Destinacion=$fila[3]; $Finalidad=$fila[4]; $Situacion=$fila[5];
	$Dependencia=$fila[6];
	if(!$CodCGR){$Situacion=1;}

	$cons="Select descripcion from Presupuesto.CodigosCGR where Codigo='$CodCGR' and Clase='$Clase' Order By Codigo";
	$res=ExQuery($cons)	; 	$fila=ExFetch($res); 	$NombreCGR=$fila[0];
	$cons="Select Recurso from  presupuesto.recursoscgr where Codigo='$RecursoCGR'";
	$res=ExQuery($cons)	; 	$fila=ExFetch($res); 	$NombreRecurso=$fila[0];
	$cons="Select Origen from  presupuesto.origenespreccgr where Codigo='$Origen'";
	$res=ExQuery($cons)	; 	$fila=ExFetch($res); 	$NombreOrigen=$fila[0];
	$cons="Select Destinacion from  presupuesto.destinacioncgr where Codigo='$Destinacion'";
	$res=ExQuery($cons)	; 	$fila=ExFetch($res); 	$NombreDestinacion=$fila[0];
	$cons="Select Finalidad from  presupuesto.finalidadcgr where Codigo='$Finalidad'";
	$res=ExQuery($cons)	; 	$fila=ExFetch($res); 	$NombreFinalidad=$fila[0];
	$cons="Select Nombre from  presupuesto.DependenciasCGR where Codigo='$Dependencia'";
	$res=ExQuery($cons)	; 	$fila=ExFetch($res); 	$NombreDependencia=$fila[0];
?>
<form name="FORMA" method="post" onSubmit="return Validar()" >
<table border="1" cellpadding="4" cellspacing="4" bordercolor="<? echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>" width="100%">
<tr>
	<td colspan="4" style="color:white; background:<? echo $Estilo[1]?>;font-weight:bold;" align="center"> ASIGNACION CGR</td>
</tr>
<tr >
    <td style="color:white; background:<? echo $Estilo[1]?>;font-weight:bold;" width="25%">Codigo CGR</td><td width="15%"><input type="text" name="CodCGR" value="<? echo $CodCGR?>" onKeyDown="xNumero(this)"  
        onkeyup="document.FORMA.NombreCGR.value='';evitarSubmit(event);Pasar(event,'RecursoCGR');xNumero(this);Mostrar();
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CodCGR&TipoG=Codigo CGR&TipoCGR=<? echo $TipoCGR?>&CodCGR='+this.value;" 
        onfocus="Mostrar();
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CodCGR&TipoG=Codigo CGR&TipoCGR=<? echo $TipoCGR?>&CodCGR='+this.value;" onkeydown="document.FORMA.NombreCGR.value='';"
        style="width:100px"/> </td>
    <td style="color:white; background:<? echo $Estilo[1]?>;font-weight:bold;" width="25%">Nombre CGR</td><td width="35%" ><input type="text" name="NombreCGR" value="<? echo $NombreCGR?>" style=" width:100%;border:thin" readonly="readonly" onFocus="Ocultar()" /></td>
</tr>
<tr >
	<td style="color:white; background:<? echo $Estilo[1]?>;font-weight:bold;" >Codigo Recurso</td><td ><input type="text" name="RecursoCGR" value="<? echo $RecursoCGR?>" 
    	onkeyup="document.FORMA.NombreRecurso.value='';evitarSubmit(event);Pasar(event,'Origen');xNumero(this);Mostrar();
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Generico1&TipoG=Recurso&Valor='+this.value+'&Valor1=Codigo&Valor2=Recurso&Tabla=Presupuesto.RecursosCGR&Objeto=RecursoCGR&Objeto1=NombreRecurso&SigObjeto=Origen';" 
        onfocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Generico1&TipoG=Recurso&Valor='+this.value+'&Valor1=Codigo&Valor2=Recurso&Tabla=Presupuesto.RecursosCGR&Objeto=RecursoCGR&Objeto1=NombreRecurso&SigObjeto=Origen';"
        style="width:50px"/></td>
        <td style="color:white; background:<? echo $Estilo[1]?>;font-weight:bold;">Recurso </td><td><input type="text" name="NombreRecurso" value="<? echo $NombreRecurso?>" style=" width:100%;border:thin" readonly="readonly" onFocus="Ocultar()"/></td>
</tr>																																																																																																																																																																																																
<tr>
	<td style="color:white; background:<? echo $Estilo[1]?>;font-weight:bold;">Codigo Origen Especifico</td><td ><input type="text" name="Origen" value="<? echo $Origen?>" 
    onkeyup="document.FORMA.NombreOrigen.value='';evitarSubmit(event);Pasar(event,'Origen');xNumero(this);Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Generico1&TipoG=Origen Especifico&Valor='+this.value+'&Valor1=Codigo&Valor2=Origen&Tabla=Presupuesto.OrigenEspRecCGR&Objeto=Origen&Objeto1=NombreOrigen&SigObjeto=Destinacion';" 
        onfocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Generico1&TipoG=Origen Especifico&Valor='+this.value+'&Valor1=Codigo&Valor2=Origen&Tabla=Presupuesto.OrigenEspRecCGR&Objeto=Origen&Objeto1=NombreOrigen&SigObjeto=Destinacion';"
    style="width:50px"/></td>
    <td style="color:white; background:<? echo $Estilo[1]?>;font-weight:bold;" >Origen Especifico de Recurso </td><td><input type="text" name="NombreOrigen" value="<? echo $NombreOrigen?>" style=" width:100%;border:thin" readonly="readonly" onFocus="Ocultar()"/></td>   
 </tr>
<tr>
	<td style="color:white; background:<? echo $Estilo[1]?>;font-weight:bold;">Codigo Destinacion Recurso</td><td ><input type="text" name="Destinacion" value="<? echo $Destinacion?>" 
    onkeyup="document.FORMA.NombreDestinacion.value='';evitarSubmit(event);Pasar(event,'Origen');xNumero(this);Mostrar();
    	frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Generico1&TipoG=Destinacion Recurso&Valor='+this.value+'&Valor1=Codigo&Valor2=Destinacion&Tabla=Presupuesto.DestinacionCGR&Objeto=Destinacion&Objeto1=NombreDestinacion&SigObjeto=Finalidad';" 
        onfocus="Mostrar();        
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Generico1&TipoG=Destinacion Recurso&Valor='+this.value+'&Valor1=Codigo&Valor2=Destinacion&Tabla=Presupuesto.DestinacionCGR&Objeto=Destinacion&Objeto1=NombreDestinacion&SigObjeto=Finalidad';"
    style="width:50px"/></td>
    <td style="color:white; background:<? echo $Estilo[1]?>;font-weight:bold;" >Destinacion Recurso </td><td><input type="text" name="NombreDestinacion" value="<? echo $NombreDestinacion?>" style=" width:100%;border:thin" readonly="readonly" onFocus="Ocultar()"/></td>
</tr>
<tr>
    <td style="color:white; background:<? echo $Estilo[1]?>;font-weight:bold;">Codigo Finalidad</td><td ><input type="text" name="Finalidad" value="<? echo $Finalidad?>" 
    onkeyup="document.FORMA.NombreFinalidad.value='';evitarSubmit(event);Pasar(event,'Finalidad');xNumero(this);Mostrar();
    	frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Generico1&TipoG=Finalidad&Valor='+this.value+'&Valor1=Codigo&Valor2=Finalidad&Tabla=Presupuesto.FinalidadCGR&Objeto=Finalidad&Objeto1=NombreFinalidad&SigObjeto=Situacion';" 
        onfocus="Mostrar();        
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Generico1&TipoG=Finalidad&Valor='+this.value+'&Valor1=Codigo&Valor2=Finalidad&Tabla=Presupuesto.FinalidadCGR&Objeto=Finalidad&Objeto1=NombreFinalidad&SigObjeto=Situacion';"
    style="width:60px"/></td>
    <td style="color:white; background:<? echo $Estilo[1]?>;font-weight:bold;">Nombre Finalidad </td><td><input type="text" name="NombreFinalidad" value="<? echo $NombreFinalidad?>" style="border:thin; width:100%" readonly="readonly" onFocus="Ocultar()"/></td>
</tr>


<tr>
    <td style="color:white; background:<? echo $Estilo[1]?>;font-weight:bold;">Codigo Dependencia</td><td ><input type="text" <? if($Clase=="Descentralizado"){ echo " disabled ";}?> name="Dependencia" value="<? echo $Dependencia?>" 
    onkeyup="document.FORMA.NombreDependencia.value='';evitarSubmit(event);Pasar(event,'Dependencia');xNumero(this);Mostrar();
    	frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Generico1&TipoG=Dependencia&Valor='+this.value+'&Valor1=Codigo&Valor2=Nombre&Tabla=Presupuesto.DependenciasCGR&Objeto=Dependencia&Objeto1=NombreDependencia&SigObjeto=Situacion';" 
        onfocus="Mostrar();        
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Generico1&TipoG=Dependencia&Valor='+this.value+'&Valor1=Codigo&Valor2=Nombre&Tabla=Presupuesto.DependenciasCGR&Objeto=Dependencia&Objeto1=NombreDependencia&SigObjeto=Situacion';"
    style="width:60px"/></td>
    <td style="color:white; background:<? echo $Estilo[1]?>;font-weight:bold;">Nombre Dependencia </td><td><input type="text" name="NombreDependencia" value="<? echo $NombreDependencia?>" style="border:thin; width:100%" readonly="readonly" onFocus="Ocultar()"/></td>
</tr>

<tr style="color:white; background:<? echo $Estilo[1]?>;font-weight:bold;">
	<td >Situacion de Fondos</td><td colspan="3" ><input type="checkbox" name="Situacion"  <? if($Situacion == 1){ echo " checked ";}?> onFocus="Ocultar()"/></td>
</tr>
</table>
<?
}
else
{
	?><center><font face="Tahoma" color="#0066FF" >Por favor seleccione una cuenta!!!</font></center><?
	$Deshab="disabled";
}
?>
<input type="submit" name="Guardar" value="Guardar " <? echo $Deshab?>/>
<input type="button" name="Cerrar" value="Cerrar" onClick="window.close();"/>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php?DatNameSID=<? echo $DatNameSID?>" frameborder="0" scrolling="yes" style="border:#e5e5e5" height="305" width="100%"></iframe>
</body>