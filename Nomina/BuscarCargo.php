<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Vin){		
	$cons="Select cargos.codigo,cargos.cargo from Nomina.cargos,Nomina.tiposvinculacion where Cargos.Compania='$Compania[0]'
	and tiposvinculacion.Compania=Cargos.compania and  tiposvinculacion.codigo='$Vin'
	and tiposvinculacion.codigo=cargos.vinculacion order by codigo";
	//echo $cons;
	$res=ExQuery($cons);
?>
<script language='JavaScript'>
parent.document.FORMA.Cargo.length=<? echo ExNumRows($res);?>+1;
		<? while($fila=ExFetch($res)){$i++;?>
		parent.document.FORMA.Cargo.options[<?echo $i?>].value="<? echo $fila[0]?>";
		parent.document.FORMA.Cargo.options[<?echo $i?>].text="<? echo $fila[1]?>";
		<? }?>
</script>
<?}?>