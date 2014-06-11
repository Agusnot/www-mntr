<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

	if($Guardar)
	{
		if(!$Edit)
		{
			$cons="Insert into Salud.Triage(Triage,Compania,prioridad) values ('$Triage','$Compania[0]','$Prioridad')";
		}
		else
		{
			$cons="Update Salud.Triage set Triage='$Triage',prioridad='$Prioridad' where Prioridad='$PrioridadAnt' and Compania='$Compania[0]'";
		}
		$res=ExQuery($cons);echo ExError();
		?>
        <script language="javascript">
	        location.href='ConfTriage.php?DatNameSID=<? echo $DatNameSID?>';
        </script>
        <?
	}
	if($Edit)
	{
		$cons="Select * from Salud.Triage where Prioridad='$Prioridad' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		$fila=ExFetchArray($res);
		$Prioridad=$fila['prioridad'];
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<script language="javascript">
function salir(){
		 location.href='ConfTriage.php?DatNameSID=<? echo $DatNameSID?>';
	}
	function Validar()
	{
		if(document.FORMA.Cargo.value=="")
		{
			alert("Debe ingresar un triage!!");return false;
		}
	}
</script>

<script language='javascript' src="/Funciones.js"></script>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
	<tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Triage</td>
        <td>
			<textarea  name="Triage" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" cols= "100" rows="3"><?php echo $fila['triage']?></textarea>
		</td>        
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Prioridad</td>
        <td>
        <?
        	$cons="select prioridad from salud.prioridadtriage where compania='$Compania[0]' order by valor";
			$res=ExQuery($cons);
		?>
        	<select name="Prioridad">            
			<?	while($fila=ExFetch($res))
				{					
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}	?>        
         	</select>
        </td>
    </tr>
   	<tr>
    	<td colspan="2" align="left"><input type="submit" value="Guardar" name="Guardar"><input type="button" value="Cancelar" onClick="salir()"></td>
 	</tr>
</table>
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
<input type="hidden" name="PrioridadAnt" value="<? echo $Prioridad?>">
</form>
</body>
</html>
