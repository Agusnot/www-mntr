<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	
	
	
	if($ContratoUrg){
	
	$cons="SELECT numero FROM contratacionsalud.contratos WHERE compania='$Compania[0]'  AND estado='AC' 
		AND  Contrato='$ContratoUrg' AND Entidad='$EntidadUrg' AND fechaini<=(SELECT NOW()) AND (fechafin>=(SELECT NOW())  OR fechafin IS NULL)";
	$res=ExQuery($cons);
	
?>
<script language='JavaScript'>
parent.document.FORMA.NoContratoUrg.length=<? echo ExNumRows($res);?>+1;
		<? while($fila=ExFetch($res)){$i++;?>
		parent.document.FORMA.NoContratoUrg.options[<?echo $i?>].value="<? echo $fila[0]?>";
		parent.document.FORMA.NoContratoUrg.options[<?echo $i?>].text="<? echo $fila[0]?>";
		<? }?>
</script>
<?}?>