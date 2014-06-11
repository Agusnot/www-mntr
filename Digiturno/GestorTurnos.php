<?
	include("Funciones.php");
	$ND=getdate();
?>
<head>
<meta http-equiv="refresh" content="1">
</head>
<?	
    $cons="Select Modulo from Digiturno where Valida=0 and Modulo is not null and Fecha='$ND[year]-$ND[mon]-$ND[mday]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
	?>
    <script language="javascript">
		parent.frames.Modulo<? echo $fila[0]?>.location.href=parent.frames.Modulo<? echo $fila[0]?>.location.href;
	</script>
<?	}
?>