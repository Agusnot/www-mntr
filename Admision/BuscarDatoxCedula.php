<?
	session_start();
	include("Funciones.php");
	mysql_select_db("Central", $conex);

	$cons="Select PrimApe,SegApe,PrimNom,SegNom,Telefono,FecNac from Terceros where Cedula='$CedPaciente'";
	$res=mysql_query($cons);
	$fila=ExFetch($res);
?>
	<form name="FORMA">
	<input type="Text" name="PrimApe" value="<?echo $fila[0]?>">
	<input type="Text" name="SegApe" value="<?echo $fila[1]?>">
	<input type="Text" name="PrimNom" value="<?echo $fila[2]?>">
	<input type="Text" name="SegNom" value="<?echo $fila[3]?>">
	<input type="Text" name="Telefono" value="<?echo $fila[4]?>">	
	</form>

<script language="JavaScript">
	opener.document.FORMA.PrimApe.value=document.FORMA.PrimApe.value;
	opener.document.FORMA.SegApe.value=document.FORMA.SegApe.value;
	opener.document.FORMA.PrimNom.value=document.FORMA.PrimNom.value;
	opener.document.FORMA.SegNom.value=document.FORMA.SegNom.value;
	opener.document.FORMA.Telefono.value=document.FORMA.Telefono.value;	
	window.close();
</script>