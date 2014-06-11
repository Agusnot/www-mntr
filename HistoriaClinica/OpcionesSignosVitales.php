<? 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$cons="Select vistobuenoaux from salud.medicos,salud.cargos where medicos.Compania='$Compania[0]' and medicos.compania=cargos.compania
	and usuario='$usuario[1]' and (vistobuenoaux=1 or vistobuenojefe=1) and medicos.cargo=cargos.cargos";	
	//echo $cons;
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$PermiteNNota=$fila[0];	
	//echo "$NumServicio --> $Servicios";
?>
<head>
<script languaje="javascript">

</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post"  target="ContenidoSV">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="NumServicio" value="<? echo $NumServicio?>" />
<input type="hidden" name="Servicios" value="<? echo $Servicios?>" />
<center>
<input type="submit" name="Nueva Nota" value="Nueva Nota" <? if(!$PermiteNNota){echo "Disabled title='No tiene permisos para crear una nueva Nota!!!'";}if($Servicios=="Todos los Servicios"){echo "Disabled title='Debe Seleccionar el Servicio Actual!!!'";}?> onClick="document.FORMA.action='NuevoSignoVital.php';" />
<input type="submit" name="Graficar" value="Graficar" onClick="document.FORMA.action='Grafica.php';" /> 
</center>
</form>
</body>