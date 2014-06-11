<? 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$cons="Select usuario from salud.medicos,salud.cargos where medicos.Compania='$Compania[0]' 
	and medicos.compania=cargos.compania and usuario='$usuario[1]' and (vistobuenoaux=1 or vistobuenojefe=1) and medicos.cargo=cargos.cargos";	
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$PermiteNNotaAbrir=$fila[0];	
	$cons="Select usuario from salud.medicos,salud.cargos where medicos.Compania='$Compania[0]' 
	and medicos.compania=cargos.compania and usuario='$usuario[1]' and  vistobuenojefe=1 and medicos.cargo=cargos.cargos";	
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$PermiteNNotaCerrar=$fila[0];
	$cons="Select Estado from historiaclinica.ctrlliquidos where Compania='$Compania[0]' and Cedula='$Paciente[1]' and NumServicio=$NumServicio order by AutoId desc";
	$res=ExQuery($cons); $fila=ExFetch($res);
	$EstadoCtrlLiq=$fila[0];
	//echo "$NumServicio --> $Servicios";
?>
<head>
<script languaje="javascript">
var Boton="";
function Validar()
{
	if(Boton.name=="AbrirFormato")
	{
		if(!confirm("Esta seguro de Crear un Nuevo Formato para el Control de Liquidos del paciente <? echo "$Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5] ";?>?")){return false;}	
	}	
	if(Boton.name=="CerrarFormato")
	{
		if(!confirm("Esta seguro de Cerrar el Formato Actual para el Control de Liquidos del paciente <? echo "$Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5] ";?>?")){return false;}	
	}	
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post"  target="ContenidoCL" onSubmit="return Validar();" >
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="NumServicio" value="<? echo $NumServicio?>" />
<input type="hidden" name="Servicios" value="<? echo $Servicios?>" />
<center>
<?
if(!$EstadoCtrlLiq||$EstadoCtrlLiq=="CE")
{?>
<input type="submit" name="AbrirFormato" value="Abrir Formato" <? if(!$PermiteNNotaAbrir){echo "Disabled title='No tiene permisos para Abrir un Nuevo Formato!!!'";}if($Servicios=="Todos los Servicios"){echo "Disabled title='Debe Seleccionar el Servicio Actual!!!'";}?> onClick="Boton=this;document.FORMA.action='NuevoControlLiquidos.php';" />
<?
}
else
{?>
<input type="submit" name="NuevaAnotacion" value="Nueva Anotacion" <? if(!$PermiteNNotaAbrir){echo "Disabled title='No tiene permisos para realizar una nueva Anotación!!!'";}if($Servicios=="Todos los Servicios"){echo "Disabled title='Debe Seleccionar el Servicio Actual!!!'";}?> onClick="Boton=this;document.FORMA.action='NuevoControlLiquidos.php';" />
<input type="submit" name="CerrarFormato" value="Cerrar Formato" <? if(!$PermiteNNotaCerrar){echo "Disabled title='No tiene permisos para cerrar el Formato!!!'";}if($Servicios=="Todos los Servicios"){echo "Disabled title='Debe Seleccionar el Servicio Actual!!!'";}?> onClick="Boton=this;document.FORMA.action='NuevoControlLiquidos.php';" />
<?
}?>
</center>
</form>
</body>