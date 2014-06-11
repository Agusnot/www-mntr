<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<style>
	a{color:black;text-decoration:none;}
	a:hover{color:blue;text-decoration:underline;}
</style>
<?
	if($TipoBusq=="Codigo")
	{
		$cons="Select * from ContratacionSalud.CUPS where Codigo ilike '$Item%'";	
	}
	elseif($TipoBusq=="Nombre")
	{
		$cons="Select * from ContratacionSalud.CUPS where Nombre ilike '$Item%' ";
	}
	$res=ExQuery($cons);
	?>
	<table style="font-family: Tahoma; font-size: 12px;">
	<tr><td>Descripcion</td>
	<?
	
	while($fila=ExFetch($res))
	{

		if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
		else{$BG="white";$Fondo=1;}?>
		<tr bgcolor="<?echo $BG?>"><td><a href="#" onclick="parent.document.form1.Cup.value='<?echo $fila[0]?>';parent.document.form1.Cup.focus();parent.document.form1.Bsq.value='Codigo'"><?echo "$fila[0] $fila[1]"?></a></td></tr>	
	<?}

?>