<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		$cons="Update Salud.Edadminima set Edad='$Edad' where Compania='$Compania[0]'";
		$res=ExQuery($cons);echo ExError();
	}	
	$result=ExQuery("Select * from Salud.Edadminima where Compania='$Compania[0]'");
	$fila=ExFetchArray($result);
?>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Edad.value=="")
		{
			alert("No deben haber campos vacios!!!");return false;
		}
		if(parseInt(document.FORMA.Edad.value)<0){alert("La edad minima no puede ser negativa");return false;}
	}
</script>
<script language='javascript' src="/Funciones.js"></script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="1" cellspacing="5"> 
		<TR bgcolor="#e5e5e5" style="font-weight:bold">
			<TD align="center">Edad Minima</TD></td>
		</TR>
	<?	 
		echo "<tr align='center'><td aling='center'><input type='text' style='width:25' onKeyUp='xNumero(this)' onKeyDown='xNumero(this)' name='Edad' value='".$fila['edad']."'></td><td>";?>
		</tr>
	</table><br>
	<input  type="submit" value="Guardar" name="Guardar">
    <input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>