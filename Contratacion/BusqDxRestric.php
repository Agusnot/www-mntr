<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Agregar)
	{
		while( list($cad,$val) = each($Dx))
		{
			if($cad && $val)
			{	
				$cons="insert into contratacionsalud.dxrestriccups (compania,cup,dx) values ('$Compania[0]','$CodCup','$cad')";
				$res=ExQuery($cons);
			}
		}?>
        <script language="javascript">
			parent.parent.document.FORMA.submit();
		</script>
<?	}
	
?>
<html>
<head>	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
function Validar()
{
	var ban=0;
	for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
	{ 
		var elemento = document.forms[0].elements[i]; 
		if (elemento.type == "checkbox") 
		{ 
			if(elemento.checked&&elemento.name!='Todos'){
				ban=1
			}
		} 	
	} 
	if(ban==0){
		alert("Debe seleccionar almenos un diagnostico!!!");return false;
	}	
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<?
if($Codigo||$Nombre)
{
	$cons="select dx from contratacionsalud.dxrestriccups where compania='$Compania[0]' and cup='$CodCup'";
	$res=ExQuery($cons);
	//echo $cons;
	if(ExNumRows($res)>0)
	{
		$DxPrev=1;
	}
	
	if($Codigo){$Cod="And codigo ilike '$Codigo%'";}
	if($Nombre){$Nom="and diagnostico ilike '%$Nombre%'";}
	if($DxPrev){$DxP=" and codigo not in (select dx from contratacionsalud.dxrestriccups where compania='$Compania[0]' and cup='$CodCup')";}
	$cons="select codigo,diagnostico from salud.cie where codigo is not null $Cod $Nom $DxP order by codigo,diagnostico";	
	$res=ExQuery($cons);
}	
?>

<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' cellpadding="4" align="center">
<?	if($Codigo||$Nombre)
	{?>
		<tr align="center"><td colspan="3"><input type="submit" name="Agregar" value="Agregar"></td>
<?	}?>        
	<tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center" > 
    	<td>codigo</td><td>Nombre</td><td></td>
	</tr>
<?	if($Codigo||$Nombre)
	{
		while($fila=ExFetch($res))
		{?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
				<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td>
				<td><input type="checkbox" name="Dx[<? echo $fila[0]?>]" checked></td>
			</tr>		
	<?	}
	}?>    
</table>
<input type="hidden" name="CodCup" value="<? echo $CodCup?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>    