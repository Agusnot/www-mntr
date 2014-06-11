<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

	if($Guardar)
	{
		if(!$Edit)
		{
			$cons="Insert into Salud.Nivelesusu(Nivel) values ('$Nivel')";
		}
		else
		{
			$cons="Update Salud.Nivelesusu set Nivel='$Nivel' where Nivel='$NivelAnt'";
		}
		$res=ExQuery($cons);echo ExError();
		?>
        <script language="javascript">
	        location.href='ConfNiveles.php?DatNameSID=<? echo $DatNameSID?>';
        </script>
        <?
	}
	if($Edit)
	{
		$cons="Select * from Salud.Nivelesusu where Nivel='$Nivel'";
		$res=ExQuery($cons);
		$fila=ExFetchArray($res);
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<script language="javascript">
	function salir(){
		 location.href='ConfNiveles.php?DatNameSID=<? echo $DatNameSID?>';
	}
	function Validar()
	{
		if(document.FORMA.Ambito.value=="")
		{
			alert("Debe ingresar un nivel!!!");return false;
		}
	}
</script>
<script language='javascript' src="/Funciones.js"></script>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
	<tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Nivel</td><td><input type="text" maxlength="30" name="Nivel" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila['nivel']?>"></td>        
    </tr>

    <tr>
    	<td colspan="2" align="center"><input type="submit" value="Guardar" name="Guardar"><input type="button" value="Cancelar" onClick="salir()"</tr>
</table>
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="NivelAnt" value="<? echo $Nivel?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>