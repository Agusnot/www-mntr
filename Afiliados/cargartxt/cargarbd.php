<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
$conex = pg_connect("dbname=sistema user=postgres password=Server*1982") or die ('no establecida');
$cons="select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom) from central.terceros where compania='$Compania[0]' and tipo='Asegurador'
order by primape,segape,primnom,segnom";
$respg=pg_query($conex,$cons);
while($fila=pg_fetch_row($respg))
{
	$Aseguradores[$fila[0]]=$fila[1];
}
$link = mysql_connect("localhost","root","");
mysql_select_db("afiliados",$link) OR DIE ("Error: Imposible Conectar");




if ($Cargar)
{		
	$cons4="select entidad,transito from auxafiliados";
	$res4=mysql_query($cons4);
	$datos=mysql_fetch_array($res4);

	if ($datos[1]=="No")
	{
		$cons6="DELETE FROM usuarios WHERE transito='No' and  entidad='$datos[0]'" ;
		$res6=mysql_query($cons6,$link);
		echo mysql_error($link);	
	}
	else
	{
		$cons6="DELETE FROM usuarios WHERE transito='Si' and  entidad='$datos[0]'" ;
		$res6=mysql_query($cons6,$link);
		echo mysql_error($link);	
	}
	$cons5="select * from  usuarios";
	$res5=mysql_query($cons5);
	
	while($fila5=mysql_fetch_array($res5))
	{
		$bdusuarios['$fila5[0]']['$fila5[6]']=array($fila5[0],$fila5[1],$fila5[2],$fila5[3],$fila5[4],$fila5[5],$fila5[6],$fila5[8]);		
	}
	$cons="select * from  auxafiliados limit 0,20";
	$res=mysql_query($cons);
	$cont=0;
	while (mysql_fetch_field($res))// ----cuenta el numero de columnas que tiene la fila
	{
		$cont++;
	}
	$cont=$cont-2;
	$cons="select ";
	$banC=1;
	for($h=0;$h<=$cont;$h++)
	{
		if($_POST["Columna_".$h])
		{			
			if($banC==1)
			{
				$banC=0;
				$cons=$cons." c".$h;	
			}
			else
			{				
				$cons=$cons.",c".$h;
			}
			
			$Columnas["c".$h]=array("c".$h,$_POST["Columna_".$h]);
			if($_POST["Columna_".$h]=='cedula'){$Ced="c".$h; }
		}
		
	}
	$cons=$cons." from auxafiliados";
	$resC=mysql_query($cons); echo mysql_error();
	while($filaC=mysql_fetch_array($resC))
	{
		$banTran=0;
		$banExiste=0;
		$banDuplicado=0;
		$cons5="select * from  usuarios where cedula='".$filaC[$Ced]."'";
		$res5=mysql_query($cons5);
		while($fila5=mysql_fetch_array($res5))
		{
			$banExiste=1;			
			if($fila5['transito']=='Si')
			{
				$Transito=$Transito.$filaC[$Ced]."\r\n";
				$banTran=1;				
			}
			else
			{
				if($fila5['transito']=='No'&&$datos[1]=="No")
				{
					$banDuplicado=1;					
				}
			}		
		}
		$banConsult=0;
		$consIns="insert into usuarios (";
		$consIns2="(";
		foreach($Columnas as $Cols)
		{
			if($banConsult==0)
			{
				$consIns=$consIns.$Cols[1];
				$banConsult=1;
				$consIns2=$consIns2."'".$filaC[$Cols[0]]."'";
			}
			else
			{
				$consIns=$consIns.",".$Cols[1];
				$consIns2=$consIns2.",'".$filaC[$Cols[0]]."'";
			}
		}
		$consIns=$consIns.",entidad,transito)";
		$consIns=$consIns." values ";
		$consIns2=$consIns2.",'$datos[0]','$datos[1]'";
		$consIns=$consIns.$consIns2.")";
		
		if($banExiste==0)
		{
			$resIns=mysql_query($consIns);
		}
		else
		{
			if($banTran==1)
			{
				$resIns=mysql_query($consIns);
				echo mysql_error();
				// generar archivo txt transito
			}
			if($banDuplicado==1)
			{
				$CedsDup=$CedsDup.$filaC[$Ced]."\r\n";	// generar archivo txt duplicados
			}
		}
	}
		?><script language="javascript">alert("Los datos ha sido cargados correctamente!!!");</script><?
	if($Transito)
	{
		$Transito="CEDULAS ENCONTRADAS EN TRANSITO \r\n".$Transito;
		$Fichero = fopen("Transitos $datos[0].TXT","w+") or die('Error de al crear archivo plano de errores');
		fwrite($Fichero, $Transito);
		fclose($Fichero);
		echo "<tr align='center'><td colspan=30><a target='_PARENT' href='Transitos $datos[0].TXT' title='Clic derecho para guardar el archivo'><br>Transitos Encontrados ".$Aseguradores[$datos[0]]."<br></a></td></tr>";
	}
	if($CedsDup)
	{
		$CedsDup="CEDULAS QUE NO SE INSERTARON POR ESTAR DUPLICADAS\r\n".$CedsDup;
		$Fichero = fopen("Cedulas duplicadas $datos[0].TXT","w+") or die('Error de al crear archivo plano de errores');
		fwrite($Fichero, $CedsDup);
		fclose($Fichero);
		echo "<tr align='center'><td colspan=30><a target='_PARENT' href='Cedulas duplicadas $datos[0].TXT' title='Clic derecho para guardar el archivo'><br>Cedulas duplicadas ".$Aseguradores[$datos[0]]."<br></a></td></tr>";
	}
}
$cons="select * from  auxafiliados limit 0,20";
$res=mysql_query($cons,$link);
$cons2 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'usuarios' AND table_schema = 'afiliados' 
and COLUMN_NAME!='entidad' and COLUMN_NAME!='transito'"; 
$res2=mysql_query($cons2);
$cons3 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'auxafiliados' AND table_schema = 'afiliados' 
and COLUMN_NAME!='entidad' and COLUMN_NAME!='transito' "; 
$res3=mysql_query($cons3);
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function GuardarScroll(){		
		document.FORMA.PosScroll.value= document.body.scrollLeft;		
		document.FORMA.submit();
	}
</script>
<title>paso 2</title>

<script language="javascript">
	function Validar()
	{
		var ban=0;
		for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
		{ 
			var elemento = document.forms[0].elements[i]; 
			//alert(elemento.type);
			if (elemento.type == "select-one") 
			{ 
				if(elemento.value=='cedula'){
					ban=1;
				}
			} 	
		} 		
		if(ban==0){alert("Uno de los campos seleccionados debe ser cedula!!!");return false;}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg" onLoad="document.body.scrollLeft=<? echo $PosScroll?>">

<form name="FORMA" method="post" onSubmit="return Validar()">

<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<?
$cons="select * from  auxafiliados limit 0,20";
$res=mysql_query($cons);
 if (mysql_num_rows($res)>0)
 { 
   
	 
	$cont=0;
	while (mysql_fetch_field($res))// ----cuenta el numero de columnas que tiene la fila
	{
		$cont++;
	}
	$cont=$cont-2;
	$i=0;
	$b=0;
	$cons4="select entidad from auxafiliados";
	$res4=mysql_query($cons4);
	$datos=mysql_fetch_array($res4);
	
 	?>
	 <tr>
       	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan= "<? echo $cont ?>"> <? echo "Se va a cargar la base de datos de ".$Aseguradores[$datos[0]]?></td>
     </tr>
    <?
	while ($fila=mysql_fetch_array($res))
	{
		if ($b<1)
		{
		?>
        <tr>
        <?
		for($a=0;$a<$cont;$a++)
		{
			
			?>
            <td>
         	 
           <select name="Columna_<? echo $a?>" onChange="GuardarScroll()">
             	<option></option>            <? 
			  	while($columnas=mysql_fetch_row($res2))
			  	{
					$ban=0;
					for ($j=0;$j<$cont;$j++)
					{
						if ($j!=$a)
						{
							if($_POST["Columna_".$j]==$columnas[0])
							{
								$ban=1;
							}
						}				
					}
					 if ($ban!=1)
					{
						if($_POST["Columna_".$a]==$columnas[0])
						{
							echo "<option value='$columnas[0]'selected>$columnas[0]</option>";
						}
						else
						{
							echo "<option value='$columnas[0]'>$columnas[0]</option>";
						}
					}
								  
												
			  	}  ?>
            </select>
            </td>  
           <?			
		$cons2 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'usuarios' AND table_schema = 'afiliados'
		and COLUMN_NAME!='entidad' and COLUMN_NAME!='transito' order by COLUMN_NAME";
		$res2=mysql_query($cons2);
		}
		?>
		</tr>
        <?
        }	
         $b=1;
		?>	
        <tr>
        <?
        while ($i<$cont)
		{
          	?>
			<td> <? echo $fila[$i] ?>&nbsp;</td>
            <? 
			$i++;
		}
        ?>
			</tr>
       	<?
		 $i=0;	
	
	}
 }
 ?>
   <tr>
	    <td align="center" colspan="100">
    	<input type="submit" name="Cargar" value="Asignar columnas">
    </td>
	</tr>
</table> 
<input type="hidden" name="PosScroll">
<input type="hidden" name="Identf" value="<? echo $Identf?>">
</form>
   
</body>
</html>