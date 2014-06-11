<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	
	

	if($EntidadUrg){
	
	$cons="SELECT contrato FROM contratacionsalud.contratos WHERE  Entidad='$EntidadUrg' AND estado='AC' AND Compania = '$Compania[0]'  AND fechaini<=(SELECT NOW()) AND (fechafin>=(SELECT NOW()) OR fechafin IS NULL) Group By Contrato";
	$res=ExQuery($cons);
	
?>
<script language='JavaScript'>
parent.document.FORMA.ContratoUrg.length=<? echo ExNumRows($res);?>+1;
		<? while($fila=ExFetch($res)){$i++;?>
		parent.document.FORMA.ContratoUrg.options[<?echo $i?>].value="<? echo $fila[0]?>";
		parent.document.FORMA.ContratoUrg.options[<?echo $i?>].text="<? echo $fila[0]?>";
		<? }?>
</script>
<?}?>