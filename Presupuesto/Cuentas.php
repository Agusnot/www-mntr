<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post"> 
<table border="1" bordercolor="#e5e5e5"  align="center" style='font : normal normal small-caps 13px Tahoma;'>    	
<?	if($Codigo||$Nombre){
		if($Nombre==""){
			$cons="select codaprobado,nomaprobado from presupuesto.$Tabla where codaprobado ilike '$Codigo%' Order By codaprobado";
		}
		else{
			if($Codigo==""){
				$cons="select codaprobado,nomaprobado from presupuesto.$Tabla where nomaprobado ilike '$Nombre%' Order By Nombre";
			}
			else{
				$cons="select codaprobado,nomaprobado from presupuesto.$Tabla where nomaprobado ilike '$Nombre%' and codaprobado ilike '$Codigo%' Order By codaprobado";
			}
		}
		//echo $cons;
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{		
	?>    	
	 		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" 
            onClick="parent.parent.document.FORMA.CuentaDB.value='<? echo $fila[0]?>';parent.CerrarThis()">
    			<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td>
		   	</tr>
<?		}
	}?>        
</table>
</form>
</body>
</html>
