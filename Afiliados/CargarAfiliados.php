<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	
	
	if($Cargar){
		if($UsuTran){$UsuTran="Si";}else{$UsuTran="No";}
		
		//----------------------Verificamos si la tabla existe, de ser asi se elimina--------------------
		$conex = mysql_connect('localhost','root','Server*1492') or die ('no establecida');
		mysql_select_db("BDAfiliados", $conex);
		$cons="show tables from BDAfiliados";
		$res=mysql_query($cons,$conex);
		echo mysql_error($conex);	
		while($fila=mysql_fetch_row($res)){
			if($fila[0]=='AuxAfiliados'){
				$Ban=1;
			}
		}
		if($Ban==1){
			$cons="DROP TABLE `AuxAfiliados`";
			$res=mysql_query($cons,$conex);
			echo mysql_error($conex);	
		}			
				
		if(is_file($_FILES['Archivo']['tmp_name']))//-------------Verificamos si el archivo existe
		{					
			$Ext = strtolower(substr($Aux,(strlen($Aux)-4)));	
			
			if($Ext==".dat"||$Ext==".txt"||$Ext==".cvs") //-------Verificamos la extencion del archivo
			{
				$ArchivoPre = fopen($_FILES['Archivo']['tmp_name'],"r") or die('Error de apertura');			
				$Linea = explode($Separador,fgets($ArchivoPre));
				$cont=0;
				//------------------------------------------------Encontramos la cantidad de columnas
				foreach($Linea as $Columna){
					$cont++;
				}
				if($cont>0){			
					//--------------------------------------Creamos la talba AuxAfiliados--------------------------
					$conex = mysql_connect('localhost','root','Server*1492') or die ('no establecida');
					$cons="create table `AuxAfiliados` (`c1` VARCHAR(200) null";
					for($i=2;$i<=$cont;$i++){
						$cons=$cons." , `c$i` VARCHAR(200) null";					
					}
					$cons=$cons.", `entidad` VARCHAR(100))";
					mysql_select_db("BDAfiliados", $conex);
					$res=mysql_query($cons,$conex);
					echo mysql_error($conex);
					//--------------------------------------Insertamos los datos en las tablas-----------------------
					$cons="insert into AuxAfiliados (`c1`";
					for($i=2;$i<=$cont;$i++){
						$cons=$cons." , `c$i`";					
					}
					$cons=$cons.",`Entidad`) values ('$Linea[0]'";
					for($i=1;$i<$cont;$i++){
						$cons=$cons." , '$Linea[$i]'";					
					}	
					$cons=$cons.",'$Entidad')";				
					mysql_select_db("BDAfiliados", $conex);
					$res=mysql_query($cons,$conex);
					echo mysql_error($conex);
					while(!feof($ArchivoPre))
					{					
						$Linea = explode($Separador,fgets($ArchivoPre));
						if($Linea)
						{								
							$cons="insert into AuxAfiliados (`c1`";
							for($i=2;$i<=$cont;$i++){
								$cons=$cons." , `c$i`";					
							}
							$cons=$cons.",`Entidad`) values ('$Linea[0]'";
							for($i=1;$i<$cont;$i++){
								$cons=$cons." , '$Linea[$i]'";					
							}	
							$cons=$cons.",'$Entidad')";				
							mysql_select_db("BDAfiliados", $conex);
							$res=mysql_query($cons,$conex);
							echo mysql_error($conex);					
						}
					}
					fclose($ArchivoPre);
					$cons="delete from Transito";
					$res=mysql_query($cons,$conex);
					echo mysql_error($conex);
					$cons="insert into Transito (EnTransito) values ('$UsuTran')";
					$res=mysql_query($cons,$conex);
					echo mysql_error($conex);
					?><script language="javascript">alert("Archivo cargado exitosamente!!!");</script><?
				}
				else{
					?><script language="javascript">alert("No se han encontrado columnas en el Archivo!!!");</script><?
				}
			}
			else{
				?><script language="javascript">alert("El tipo Archivo no es valido!!!");</script><?
			}
		}
		else{
			?><script language="javascript">alert("El Archivo no existe!!!");</script><?			
		}
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
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
<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Entidad</td>
	<td colspan="3"><? 
		$conex = pg_connect("dbname=sistema user=apache password=Server*1982") or die ('no establecida');
		$cons = "Select Identificacion,Primape from Central.Terceros where Tipo='Asegurador' and Compania = '$Compania[0]' 
		group by Identificacion,Primape order by Primape";				
		$res = pg_query($conex,$cons); ?>
        <select name="Entidad">
   	<?	while($fila=pg_fetch_row($res)){
        	echo "<option value='$fila[0]'>$fila[1]</option>";
   		}?>
        </select>
   	</td>    
</tr>
<tr>
	<td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Archivo</td>
    <td><input type="file" name="Archivo" style="width:400"></td>
    <td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Separador</td>
    <td>
    	<input type="text" name="Separador" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" onFocus="xLetra(this)" style="width:15"
        style="font:normal normal small-caps 22px Arial, Helvetica, sans-serif" maxlength="1">	
    </td>
</tr>
<tr align="center">
	<td colspan="6"><strong>Usuarios en Transito</strong> <input type="checkbox" name="UsuTran"></td>
</tr>
<tr>
	
    <td align="center" colspan="5">
    	<input type="submit" name="Cargar" value="Cargar">
    </td>
</tr>
</table>
<input type="hidden" name="Aux">
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>    
</body>
</html>
