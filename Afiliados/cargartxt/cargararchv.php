<? 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	$conex = pg_connect("dbname=sistema user=postgres password=Server*1982") or die ('no establecida');
	$cons="select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom) from central.terceros where compania='$Compania[0]' and tipo='Asegurador'
	order by primape,segape,primnom,segnom";
	$respg=pg_query($conex,$cons);
	/*while($fila=pg_fetch_row($res))
	{
		$Aseguradores[$fila[0]]	=$fila[1];
	}*/
	$link = mysql_connect("localhost","root","");
	mysql_select_db("afiliados",$link) OR DIE ("Error: Imposible Conectar");
	
	
	if ($Cargar)
	{
		$cons="show tables from afiliados";
		$res=mysql_query($cons,$link);
		echo mysql_error($link);	
		
		while($fila=mysql_fetch_row($res)){
			if($fila[0]=='auxafiliados'){
				$Ban=1;//----- para saber que existe la tabla
				
			}
		}
		if($Ban==1)
		{
			$cons="DROP TABLE `auxafiliados`";//------creamos la tabla si no existe
			$res=mysql_query($cons,$link);
			echo mysql_error($link);	
		}	
		if($UsuTran)
		{
			$UsuTran="Si";
		}
		else
		{
			$UsuTran="No";
		} 
		if(is_file($_FILES['Archivo']['tmp_name']))
		{
			$Ext = strtolower(substr($Aux,(strlen($Aux)-4)));
			if($Ext==".dat"||$Ext==".txt"||$Ext==".cvs") //-------Verificamos la extencion del archivo
			{
				$ArchivoPre = fopen($_FILES['Archivo']['tmp_name'],"r") or die('Error de apertura'); //------cargo el archivo y
																			//----r me da permisos de lectura de escritura sera w			
				$Linea = explode($Separador,fgets($ArchivoPre)); //----- me serpara el archivo segun el caracter cargado en la vairable
																//---separador fgets lee la linea siguiente del archivo
				$cont=0;
				foreach($Linea as $Columna)//---se utiliza solo como erramienta para calcular las columnas de la variable fila
				{
					$cont++;
				}
				
				if($cont>0)//- si cont mayor a cero exite el archivo y tiene datos y Creamos la talba AuxAfiliados
				{						
					
					$cons="create table `auxafiliados` (`c0` VARCHAR(200) null"; // creamos con un ciclo los campos
					for($i=1;$i<$cont;$i++)
					{
						$cons=$cons." , `c$i` VARCHAR(200) null";
					}
					$cons=$cons.", `entidad` VARCHAR(100)";
					if ($UsuTran)
					{
						$cons=$cons.", `transito` VARCHAR(100))";
					}
					$res=mysql_query($cons,$link);
					echo mysql_error($link);
					//--------------------------------------Insertamos los datos en las tablas-----------------------
					$cons="insert into `auxafiliados` (`c0`";
					for($i=1;$i<$cont;$i++)
					{
						$cons=$cons." , `c$i`";					
					}
					$cons=$cons.",`entidad`";
					$cons=$cons.", `transito`) values ('$Linea[0]'";
													
					for($i=1;$i<$cont;$i++)
					{
						$cons=$cons." , '$Linea[$i]'";					
					}
					
					if ($UsuTran)
					{
						$cons=$cons.",'$entidades'";
						$cons=$cons.",'$UsuTran')";
					}
					else
					{
						$cons=$cons.",'$entidades')";
					}
					
					
					$res=mysql_query($cons,$link);
					echo mysql_error($link);// se carga la primera linea del archivo plano
					$contlineas=1;
					while(!feof($ArchivoPre))// mientras pueda leer el archivo
					{									
						$contlineas++;
						$Linea = explode($Separador,fgets($ArchivoPre));//--obtiene una linea desde el apuntador de archivo
						$cont2=0;
						foreach($Linea as $Columna)//---se utiliza solo como erramienta para calcular las columnas de la variable fila
						{
							$cont2++;
						}
						if($Linea&&$cont2==$cont)
						{								
							$cons="insert into `auxafiliados` (`c0`";
							for($i=1;$i<$cont;$i++){
								$cons=$cons." , `c$i`";					
							}
							$cons=$cons.",`entidad`";
							$cons=$cons.", `transito`) values ('$Linea[0]'";
							for($i=1;$i<$cont;$i++){
								$cons=$cons." , '$Linea[$i]'";					
							}	
							
							if ($UsuTran)
							{
								$cons=$cons.",'$entidades'";
								$cons=$cons.",'$UsuTran')";
							}
							else
							{
								$cons=$cons.",'$entidades')";
							}
								
												
							$res=mysql_query($cons,$link);
							echo mysql_error($link);// se carga el resto de las lineas del archivo plano			
						}
						else
						{
							$Errores=$Errores."Error en la linea $contlineas:";
							$banS=0;
							for($i=0;$i<$cont2;$i++){
								if($banS==0)
								{
									$Errores=$Errores." $Linea[$i]";	
									$banS=1;				
								}
								else
								{
									$Errores=$Errores.",$Linea[$i]";
								}
								
							}
							$Errores=$Errores."\r\n";// \r\n salto de linea para el archivo txt
						}
					}	
					?><script language="javascript">alert("El Archivo ha sido cargado!!!");</script><?
				}	
				else
				{
					?><script language="javascript">alert("No se han encontrado columnas en el Archivo!!!");</script><?
				}				
				
			}
			else
			{
				?><script language="javascript">alert("El tipo Archivo no es valido!!!");</script><?
			}
		}
		else
		{
		?><script language="javascript">alert("El no pudo ser cargado!!!");</script><?			
		}
				
	}
?>
    
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>

<script language="javascript">
	function Validar(){
		
		document.FORMA.Aux.value=document.FORMA.Archivo.value;
		if(document.FORMA.Archivo.value==""){alert("Debe seleccionar el archivo!!!");return false;}
		if(document.FORMA.Separador.value==""){alert("Debe digitar el separador!!!");return false;}		
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()"  enctype="multipart/form-data">

<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<tr>
	<td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Entidad</td>
	<td colspan="3">
   			<?
            $cons="select nombre from entidad"; 
    		$res=mysql_query($cons);
			echo mysql_error();
			?>
            
    <select name="entidades"> 
	<? 	while($fila=pg_fetch_row($respg))
        {
			if($fila[0]==$entidades){
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
	<td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Archivo</td>
    <td><input type="file" name="Archivo" style="width:400"></td>
    <td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Separador</td>
    <td>
    	<input type="text" name="Separador"  style="width:15"
        style="font:normal normal small-caps 22px Arial, Helvetica, sans-serif" maxlength="1">	
    </td>
     <tr align="center">
	<td colspan="6"><strong>Usuarios en Transito</strong> <input type="checkbox" name="UsuTran"></td>
	</tr>
    <tr>
	
    <td align="center" colspan="5">
    	<input type="submit" name="Cargar" value="Cargar">
    </td>
	</tr>
   </tr>

<?
if($Errores)
{
	$Fichero = fopen("Errores ".$Aseguradores[$entidades].".TXT","w+") or die('Error de al crear archivo plano de errores');
	fwrite($Fichero, $Errores);
	fclose($Fichero);
	echo "<tr align='center'><td colspan=30><a target='_PARENT' href='Errores ".$Aseguradores[$entidades].".TXT' title='Clic derecho para guardar el archivo'><br>Errores Encontrados ".$Aseguradores[$entidades]."<br></a></td></tr>";
}
?>
</table>
<input type="hidden" name="Aux"> 
</form>
</body>
</html>