<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

	if($Depto){
	$cons="Select codmpo,municipio from Central.Municipios,Central.Departamentos where Departamentos.Departamento='$Depto'
	 and Departamentos.Codigo=Municipios.Departamento order by codmpo";
	//$cons="Select municipio,codmpo from Central.Municipios where Departamento='$Depto' order by codmpo";
	//echo $cons;
	$res=ExQuery($cons);
?>
<script language='JavaScript'>
parent.document.FORMA.Mpo.length=<? echo ExNumRows($res);?>+1;
		<? while($fila=ExFetch($res)){$i++;?>
		parent.document.FORMA.Mpo.options[<?echo $i?>].value="<? echo $fila[1]?>";
		parent.document.FORMA.Mpo.options[<?echo $i?>].text="<? echo $fila[1]?>";
		<? }?>
</script>
<?php } 

        if($departamentonac){
	$cons="Select codmpo,municipio from Central.Municipios,Central.Departamentos where Departamentos.Departamento='$departamentonac'
	 and Departamentos.Codigo=Municipios.Departamento order by codmpo";
	//$cons="Select municipio,codmpo from Central.Municipios where Departamento='$Depto' order by codmpo";
	//echo $cons;
	$res=ExQuery($cons);
?>
<script language='JavaScript'>
parent.document.FORMA.municipionac.length=<? echo ExNumRows($res);?>+1;
		<? while($fila=ExFetch($res)){$i++;?>
		parent.document.FORMA.municipionac.options[<?echo $i?>].value="<? echo $fila[1]?>";
		parent.document.FORMA.municipionac.options[<?echo $i?>].text="<? echo $fila[1]?>";
		<? }?>
</script>
<?php } ?>
?>