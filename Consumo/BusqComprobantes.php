<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

	$cons="SELECT Comprobante FROM Consumo.Comprobantes WHERE Tipo='$Tipo' and Compania='$Compania[0]'
	and AlmacenPpal='$AlmacenPpal'
	ORDER BY Comprobante";
	$res=ExQuery($cons);echo ExError();echo $cons;
?>

<script language="javascript">
	parent.document.FORMA.Comprobante.length=0;
	parent.document.FORMA.Comprobante.length=<?echo ExNumRows($res);?>+1;
	<? $i=0;while($fila=ExFetch($res)){$i++;?>;
	parent.document.FORMA.Comprobante.options[<?echo $i?>].value="<?echo $fila[0]?>";
	parent.document.FORMA.Comprobante.options[<?echo $i?>].text="<?echo $fila[0]?>";
	<?}?>
</script>

