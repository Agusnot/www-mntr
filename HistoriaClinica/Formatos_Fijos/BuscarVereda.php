<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

	if($Depto&&$Mpo){
		$cons="Select vereda,codvereda from Central.veredas,central.municipios  where veredas.Departamento='$Depto' and municipios.municipio='$Mpo' and codmpo=veredas.municipio";
		echo $cons;
		$res=ExQuery($cons);
	?>	
		<script language="javascript">
		parent.document.FORMA.VeredaUsu.length=<? echo ExNumRows($res);?>+1;
		<? while($fila=ExFetch($res)){$i++;?>
			parent.document.FORMA.VeredaUsu.options[<? echo $i?>].value="<? echo $fila[0]?>";
			parent.document.FORMA.VeredaUsu.options[<? echo $i?>].text="<? echo $fila[0]?>";
		<? }?>
		</script>	
<?	}?>