<?
	session_start();
	mysql_select_db("salud", $conex);
?>
<html>
<head>
	<title>Buscar Paciente</title>
</head>
<style>
	a{color:black;text-decoration:none;}
	a:hover{color:blue;text-decoration:underline;}
</style>
<body background="/Imgs/Fondo.jpg">
<?
	if($NumCed || $NumHa)
	{
		if($NumHa){
		$NumCed=$NumHa;}
		$cons="Select primape,segape,primnom,segnom from admision where numced='$NumCed'";
	}
	elseif($PrimApe || $SegApe || $PrimNom || $SegNom)
	{
		$cons = "SELECT NumCed,PrimApe,SegApe,PrimNom,SegNom FROM admision 
		Where PrimApe like '%$PrimApe%' 
		And SegApe like '%$SegApe%' And PrimNom like '%$PrimNom%' And SegNom like '%$SegNom%'
		Order By PrimApe, SegApe, PrimNom, SegNom";
		$res=mysql_query($cons);
		if(mysql_num_rows($res)==1)
		{$fila=ExFetch($res);?>
			<script language="JavaScript">
				location.href="BuscarRegistroAgenda.php?NumCed=<?echo $fila[0]?>";
			</script>
<?		}
		elseif(mysql_num_rows($res)>1)
		{
			echo "<table border=1 cellspacing=0 bordercolor='white'><tr bgcolor='#E5E5E5'><td><strong>COINCIDENTES</td></tr>";
			while($fila=ExFetch($res))
			{
				if($Fondo==1){$BG="#E5E5E5";$Fondo=0;}
				else{$BG="white";$Fondo=1;}
				echo "<tr bgcolor='$BG'><td><a href='#' onclick=location.href='BuscarRegistroAgenda.php?NumCed=$fila[0]'>$fila[1] $fila[2] $fila[3] $fila[4]</a></td></tr>";
			}
		}
	}
	else
	{
?>
<form name="FORMA">
<br><br>
<table>
<tr><td>No. Historia:</td><td><input type="Text" name="NumHa"></td>
<td>No Documento</td><td><input type="Text" name="NumCed"></td></tr>
<tr><td>Primer apellido:</td><td><input type="Text" name="PrimApe"></td>
<td>Segundo apellido</td><td><input type="Text" name="SegApe"></td></tr>
<tr><td>Primer Nombre:</td><td><input type="Text" name="PrimNom"></td>
<td>Segundo Nombre:</td><td><input type="Text" name="SegNom"></td></tr>
</table>
<br><br>
<input type="Submit" value="Buscar registro">
<input type='Button' value='Salir' style='width:130px;' onclick='window.close();'>
</form>
</body>
<?
	}
?>
</html>
