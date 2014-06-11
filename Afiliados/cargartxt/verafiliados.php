<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
$conex = pg_connect("dbname=sistema user=postgres password=Server*1982") or die ('no establecida');
$cons="select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom) from central.terceros where compania='$Compania[0]' and tipo='Asegurador'
order by primape,segape,primnom,segnom";
$respg=pg_query($conex,$cons);

$link = mysql_connect("localhost","root","");
mysql_select_db("afiliados",$link) OR DIE ("Error: Imposible Conectar");

?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ver Afiliados</title>

<script language="javascript">
	function Validar()
	{
		var ban=0;
		if(document.FORMA.Documento.value==""&&document.FORMA.PrimApe.value==""&&document.FORMA.SegApe.value==""&&document.FORMA.PrimNom.value==""&&document.FORMA.SegNom.value==""&&document.FORMA.Entidad.value=="") 
	    {
        	 alert("Tiene que haber almenos un criterio de busqueda!!!"); return false; 
	    }
		else
		{
			 ban=1;
		}
		
	}
</script>
<script language='javascript' src="/Funciones.js"></script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()"  enctype="multipart/form-data">

<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
	<tr  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
    	<td>Documento</td><td>Primer Apellido</td><td>Segundo Apellido</td><td>Primer Nombre</td><td>Segundo Nombre</td>   
  	</tr>
    <tr>        
		<td><input type="text" name="Documento" value="<? echo $Documento; ?>" style="width:100" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)"></td>        
        <td><input type="text" name="PrimApe" style="width:100" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" value="<? echo $PrimApe?>"></td>        
        <td><input type="text" name="SegApe" style="width:100" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" value="<? echo $SegApe?>"></td>       
        <td><input type="text" name="PrimNom" style="width:100" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" value="<? echo $PrimNom?>"></td>        
        <td><input type="text" name="SegNom" style="width:100" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" value="<? echo $SegNom?>"></td>
	</tr>
    <tr  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
    	<td colspan="5">Entidad</td>
    </tr>
    <tr>
    	<td colspan="5" align="center">
        	<select name="Entidad">
            	<option></option>
         	<? 	while($fila=pg_fetch_row($respg))
				{
					if($fila[0]==$Entidad){
						echo "<option value='$fila[0]' selected>$fila[1]</option>";
					}
					else
					{
						echo "<option value='$fila[0]'>$fila[1]</option>";
					}
					$Aseguradores[$fila[0]]=$fila[1];
				}    ?>	
            </select>
        </td>
    </tr>
	<tr>
        <td align="center" colspan="5">
            <input type="submit" name="Cargar" value="Ver">
        </td>
	</tr>
<br>
    
</table>
<?
if($Cargar)
{
	if($Documento){$Doc=" and cedula like '$Documento%'";}
	if($PrimApe){$PA=" and apellido1 like '$PrimApe%'";}
	if($SegApe){$SA=" and apellido2 like '$SegApe%'";}
	if($PrimNom){$PN=" and nombre2 like '$PrimNom%'";}
	if($SegNom){$SN=" and nombre2 like '$SegNom%'";}
	if($Entidad){$Ent=" and entidad like '$Entidad%'";}
	$cons="select * from usuarios where cedula is not null $Doc $PA $SA $PN $SN $Ent order by cedula";
	$res=mysql_query($cons); 
	echo mysql_error();?>
    <br>
	<table BORDER=1  style='font : normal normal small-caps 11px Tahoma;' bordercolor="#e5e5e5" cellpadding="1" cellspacing="1" align="center">
        <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td></td>
    <?	$cons2 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'usuarios' AND table_schema = 'afiliados'";
        $res2=mysql_query($cons2);
        while($fila2=mysql_fetch_row($res2))
        {
            echo "<td>$fila2[0]</td>";
        }?>    
    	</tr>        
    <?
	
	if($res)
	{
		$cont=0;
		while($fila=mysql_fetch_array($res))
		{ 
			$cont++;
			echo "<tr align='center'><td>$cont</td>";
			 $cons2 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'usuarios' AND table_schema = 'afiliados'";
        	$res2=mysql_query($cons2);
        	while($fila2=mysql_fetch_row($res2))
        	{
				if($fila2[0]=="entidad")
				{?>
            		<td><? echo $Aseguradores[$fila[$fila2[0]]]?>&nbsp;</td>
        <?		}
				else
				{?>
                	<td><? echo $fila[$fila2[0]]?>&nbsp;</td>
			<?	}
			}
			echo "</tr>";
        }
	}
   ?>
    </table>
<?	
} 
?>

</form>    
</body>
</html>