<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

	$cons="Select Grupo from Consumo.Grupos where AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'";
	$res=ExQuery($cons);echo ExError();
	//echo $cons;

	$cons1="Select TipoProducto from Consumo.TiposProducto where AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'";
	$res1=ExQuery($cons1);echo ExError();
	//echo $cons1;

	$cons2="Select Bodega from Consumo.Bodegas where AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'";
	$res2=ExQuery($cons2);echo ExError();
	//echo $cons2;

?>
<script language="javascript">
	parent.document.Forma.Grupo.length=1;
	parent.document.Forma.TipoPro.length=1;
	parent.document.Forma.Bodega.length=1;



	parent.document.Forma.Grupo.length=<?echo ExNumRows($res);?>+1;
	<? $i=0;while($fila=ExFetch($res)){$i++;?>;
	parent.document.Forma.Grupo.options[<?echo $i?>].value="<?echo $fila[0]?>";
	parent.document.Forma.Grupo.options[<?echo $i?>].text="<?echo $fila[0]?>";
	<?}?>


	parent.document.Forma.TipoPro.length=<?echo ExNumRows($res1);?>+1;
	<?$i=0;while($fila1=ExFetch($res1)){$i++;?>;
	parent.document.Forma.TipoPro.options[<?echo $i?>].value="<?echo $fila1[0]?>";
	parent.document.Forma.TipoPro.options[<?echo $i?>].text="<?echo $fila1[0]?>";
	<?}?>


	parent.document.Forma.Bodega.length=<?echo ExNumRows($res2);?>+1;
	<?$i=0;while($fila2=ExFetch($res2)){$i++;?>;
	parent.document.Forma.Bodega.options[<?echo $i?>].value="<?echo $fila2[0]?>";
	parent.document.Forma.Bodega.options[<?echo $i?>].text="<?echo $fila2[0]?>";
	<?}?>

</script>