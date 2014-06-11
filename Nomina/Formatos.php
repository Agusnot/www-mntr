<? 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
//	echo $NoEmpleado." <-- ".$Identificacion."//////";	
	include "Funciones.php";
	$Raiz=$_SERVER['DOCUMENT_ROOT'];	
	$cons="Select Perfil from Nomina.AccesoxNomina,Nomina.UsuariosxNomina 
	where Modulo=Perfil and Nivel=0 and Usuario='$usuario[1]' Order By Id";
	$res=ExQuery($cons);
	//echo $cons."<br>";
	$NumRows=ExNumRows($res);	
	$ND=getdate();
	$fecha="$ND[year]-$ND[mon]-$ND[mday]";
	?>
	<script language="JavaScript" type="text/javascript">
	var Op_0 = new tunMen("Nomina","DatosPersonales.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>","Datos",0);
	</script>
    <?
//	echo $NoEmpleado;
//	echo "Estado: ".$Estado."<br>";
//------------Total Empleados ----------------------------------------	
if($Estado=='')
{
	$consE="select count(identificacion) from central.terceros where (tipo='Empleado' or regimen='Empleado' )and compania='$Compania[0]'";
//	echo $consE;
}
else
{
	$consE="select count(terceros.identificacion) from nomina.contratos, central.terceros, nomina.cargos, nomina.tiposvinculacion where contratos.compania=
'$Compania[0]' and contratos.compania=terceros.compania and contratos.identificacion=terceros.identificacion and contratos.tipovinculacion=tiposvinculacion.codigo and contratos.cargo=cargos.codigo and cargos.vinculacion=contratos.tipovinculacion and contratos.fecinicio<'$fecha' and contratos.estado='$Estado' and (tipo='Empleado' or regimen='Empleado')";
//	echo $consE;
}
	$resE=Exquery($consE);	
	$TotEmpleados=ExFetch($resE);
//	echo "<br>>".$TotEmpleados[0]."A<br>";
/*	if($NoEmpleado>$TotEmpleados[0]&&$Sig)
	{
		echo "Ingreso<br>";
		$NoEmpleado=$TotEmpleados[0];
		echo $NoEmpleado;
	}*/
//-------------siguiente y anterior ------------------------------------
	if($Sig || $Ant)
	{
		if($Ant){$NoEmpleado=$NoEmpleado-1;if($NoEmpleado==-1){$NoEmpleado=$TotEmpleados[0]-1;}}
		if($Sig){$NoEmpleado=$NoEmpleado+1;if($NoEmpleado==$TotEmpleados[0]||$NoEmpleado>$TotEmpleados[0]){$NoEmpleado=0;}}	
		if($Estado=='')
		{
			$cons="select identificacion,primape,segape,primnom,segnom from central.terceros where (tipo='Empleado' or regimen='Empleado' )and compania='$Compania[0]' $PA $SA $PN $SN $C $Es order by primape,segape,primnom,segnom";
		}
		else
		{
			$cons="select contratos.identificacion, primape, segape, primnom, segnom from nomina.contratos, central.terceros, nomina.cargos, nomina.tiposvinculacion where contratos.compania=
'$Compania[0]' and contratos.compania=terceros.compania and contratos.identificacion=terceros.identificacion and contratos.tipovinculacion=tiposvinculacion.codigo and contratos.cargo=cargos.codigo and cargos.vinculacion=contratos.tipovinculacion and contratos.fecinicio<'$fecha' and contratos.estado='$Estado' and (tipo='Empleado' or regimen='Empleado') order by primape, segape, primnom, segnom";
		}
//		echo $cons;
		$res=ExQuery($cons);
		$ContEmp=0;
		while($fila=ExFetch($res))
		{
//			echo "&&".$ContEmp."&&".$NoEmpleado."<br>";
			if($ContEmp==$NoEmpleado)
				{
					$Identificacion=$fila[0];
//					echo $ContEmp." ==> ".$NoEmpleado." --> ".$Identificacion;
//					echo $ContEmp."<br>";
					break;
				}
			$ContEmp++;
		}
		?>
		<script language="JavaScript">
//		alert();
		parent.parent.location.href='HojadeVida.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NoEmpleado=<? echo $NoEmpleado?>&Estado=<? echo $Estado?>';
//		alert();
		</script>
        
		<?
	}
/*	$cons100="Select especialidad from Salud.Especialidades where compania='$Compania[0]'";
	$res100=ExQuery($cons100);
	$NumRows=$NumRows+ExNumRows($res100);*/
//	if(!$NoCerrar){$Aumento=1;}
//	else{$Aumento=0;}	
?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<body bgcolor="#666699">
<style type="text/css">
<?	
	if($NoSistema!=1){		
?>
body{background-image: url(/Imgs/Izquierda.jpg);}<?	}?>
	a{color:white;text-decoration:none;}
	a:hover{color:yellow;text-decoration:underline;}
</style>

<font color="#ffffff">


<style type="text/css">
<!--
a.enls:link, a.enls:visited{
color: "white";
text-decoration: none;
}
a.enls:hover{
color: yellow;
text-decoration: underline;

}
a.secac2{
	color: #B87070;
	text-decoration: none;
}
a.secac{
	color: "white";
	text-decoration: none;
}
a.secac:hover{
	color: "yellow";
	text-decoration: underline;
}
.botones {
	color: "white";
	margin: 0;
	padding-left: 18px;
	text-decoration: none;
	text-align: left;
}
.botonesHover {
text-decoration: underline;
color: yellow;
}
/* Atenci&oacute;n, evitar alterar la clase .subMe */
.subMe{
	display: none;
	margin: 0;
	background-image: url(imasmenu/puntosvt.gif);
	background-repeat:  repeat-y;
}
/* Atenci&oacute;n, evitar alterar la clase .subMe */
body {

	font-family: verdana, tahoma, arial, sans serif;
	font-size: 13px;
}
-->
</style>
</head>
<body bgproperties="fixed">
<div style="margin-left: auto; margin-right: auto; width: 70%; font-family: trebuchet ms">
<div id="tunMe"></div>
</div>
<table border="0" cellpadding="0" cellspacing="0"  style="color:yellow; font-weight:bold;">
<tr>
<td><img border="0" src="/Imgs/home.gif"><font style="font-family:Tahoma; font-size:11; font-style:normal">&nbsp;&nbsp;Nomina</font></td>
</tr>
<tr>
<td><img border="0" src='/Imgs/puntosut.gif'><img border="0" src='/Imgs/doct.gif'>
	<a href='DatosPersonales.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>' target="Datos">
	<font style="font-family:Tahoma; font-size:11; font-style:normal">
    DATOS PERSONALES
    </font></a></td>
</tr>
<tr>
<td><img border="0" src='/Imgs/puntosut.gif'><img border="0" src='/Imgs/doct.gif'>
	<a href='InicioContrato.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>' target="Datos">
	<font style="font-family:Tahoma; font-size:11; font-style:normal">    
    CONTRATO
    </font></a></td>
</tr>
<tr>
<td><img border="0" src='/Imgs/puntosut.gif'><img border="0" src='/Imgs/doct.gif'>
	<a href='NominaPersonal.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>' target="Datos">
	<font style="font-family:Tahoma; font-size:11; font-style:normal">
    LIQUIDACION
    </font></a></td>
</tr>
<tr>
<td><img border="0" src='/Imgs/puntosut.gif'><img border="0" src='/Imgs/carpabiertat.gif'>
	<font style="font-family:Tahoma; font-size:11; font-style:normal">
    NOVEDADES
    </font></td>
</tr>

<tr>
<td>&nbsp;&nbsp;
	<img border="0" src='/Imgs/puntosut.gif'><img border="0" src='/Imgs/doct.gif'>
	<a href='ListarHistorial.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Novedad=Incapacidades' target="Datos">
	<font style="font-family:Tahoma; font-size:11; font-style:normal">
	INCAPACIDADES
    </font></a></td>
</tr>
<tr>
<td>&nbsp;&nbsp;
	<img border="0" src='/Imgs/puntosut.gif'><img border="0" src='/Imgs/doct.gif'>
	<a href='ListarHistorial.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Novedad=Licencias' target="Datos">
    <font style="font-family:Tahoma; font-size:11; font-style:normal">
    LICENCIAS
    </font></a></td>
</tr>
<td>&nbsp;&nbsp;
	<img border="0" src='/Imgs/puntosut.gif'><img border="0" src='/Imgs/doct.gif'>
	<a href='ListarHistorial.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Novedad=Suspensiones' target="Datos">
    <font style="font-family:Tahoma; font-size:11; font-style:normal">
    SUSPENSIONES
    </font></a></td>
</tr>
<td>&nbsp;&nbsp;
	<img border="0" src='/Imgs/puntosut.gif'><img border="0" src='/Imgs/doct.gif'>
	<a href='ListarHistorial.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Novedad=Vacaciones' target="Datos">
    <font style="font-family:Tahoma; font-size:11; font-style:normal">
    VACACIONES
    </font></a></td>
</tr>
<tr>
<td><img border="0" src='/Imgs/puntosut.gif'><img border="0" src='/Imgs/doct.gif'>
	<a href='ProgConcepto.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Sig=1&NoEmpleado=<? echo $NoEmpleado?>&Estado=<? echo $Estado?>' target="Datos">
    <font style="font-family:Tahoma; font-size:11; font-style:normal">
    PROG. CONCEPTO
    </font></a></td>
</tr>
<tr>
<td><img border="0" src='/Imgs/puntosut.gif'><img border="0" src='/Imgs/doct.gif'>
	<a href='DesConcepto.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Sig=1&NoEmpleado=<? echo $NoEmpleado?>&Estado=<? echo $Estado?>' target="Datos">
    <font style="font-family:Tahoma; font-size:11; font-style:normal">
    DESPROG. CONCEPTO
    </font></a></td>
</tr>
<tr>
<td><img border="0" src='/Imgs/puntosut.gif'><img border="0" src='/Imgs/doct.gif'>
	<a href='Formatos.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Sig=1&NoEmpleado=<? echo $NoEmpleado?>&Estado=<? echo $Estado?>'>
    <font style="font-family:Tahoma; font-size:11; font-style:normal">
    SIGUIENTE
    </font></a></td>
</tr>
<tr>
<td><img border="0" src='/Imgs/puntosut.gif'><img border="0" src='/Imgs/doct.gif'>
	<a href='Formatos.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Ant=1&NoEmpleado=<? echo $NoEmpleado?>&Estado=<? echo $Estado?>'>
    <font style="font-family:Tahoma; font-size:11; font-style:normal">
    ANTERIOR
    </font></a></td>
</tr>
<!--<tr>
<td><a href="#" onClick="open('BuscarRegistro.php','','width=600,height=130')">buscar</a></td>
</tr>

<tr>
<td><a href='Nuevo.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>' target="Datos">Nuevo</a></td>
</tr>-->
<tr>
<td><img border="0" src='/Imgs/puntosut.gif'><img border="0" src='/Imgs/doct.gif'>
	<a href="/Principal.php?DatNameSID=<? echo $DatNameSID?>" target="_top">
    <font style="font-family:Tahoma; font-size:11; font-style:normal">
    SALIR
    </font></a></td>
</tr>
</table>
</body>
</html>
