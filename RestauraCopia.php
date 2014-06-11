<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();

	if($Generar)
	{
		/////COPIAMOS LA INFORMACION A LAS CARPETAS DESTINO /////////
		while (list($val,$cad) = each ($SelDB)) 
		{
			$BaseDatos=strtolower($cad);
			$cons1="Select table_name FROM information_schema.columns where table_schema='$BaseDatos' group by table_name Order By table_name ;";
			$res1=pg_query($cons1);
			while ($row = ExFetch($res1))
			{
				$Tabla=strtolower("$BaseDatos.$row[0]");
				if(is_file("$RutaCopia/$Tabla.csv"))
				{
					$cons10="TRUNCATE $Tabla CASCADE";
					$res10=ExQuery($cons10);
					sleep(10);
				}
			}
			$res1=pg_query($cons1);
			while ($row = ExFetch($res1))
			{
				$Tabla=strtolower("$BaseDatos.$row[0]");
				$cons2="Select conname,nspname,conrelid,confrelid,conkey,confkey,pg_class.relname as referencia1,pg2.relname as referencia2,unique_constraint_schema,
				constraint_schema
				from pg_constraint,pg_class,pg_class as pg2,pg_namespace,information_schema.referential_constraints
				where pg_class.oid=conrelid and pg2.oid=confrelid
				and pg_namespace.oid=connamespace
				and pg_class.relname='$row[0]' and nspname='$BaseDatos'
				and constraint_name=conname;";
				$res2=ExQuery($cons2);
				while($fila2=ExFetch($res2))
				{
					$cons3="Select * from $fila2[8].$fila2[7]";
					$res3=ExQuery($cons3);
					if(ExNumRows($res3)==0)
					{

						$cons44="Select conname,nspname,conrelid,confrelid,conkey,confkey,pg_class.relname as referencia1,pg2.relname as referencia2,unique_constraint_schema,
						constraint_schema
						from pg_constraint,pg_class,pg_class as pg2,pg_namespace,information_schema.referential_constraints
						where pg_class.oid=conrelid and pg2.oid=confrelid
						and pg_namespace.oid=connamespace
						and pg_class.relname='$fila2[7]' and nspname='$fila2[8]'
						and constraint_name=conname;";
						$res44=ExQuery($cons44);
						while($fila44=ExFetch($res44))
						{
							$cons30="Select * from $fila44[8].$fila44[7]";
							$res30=ExQuery($cons30);
							if(ExNumRows($res30)==0)
							{
								$cons20="COPY $fila44[8].$fila44[7] from '$RutaCopia/$fila44[8].$fila44[7].csv' USING DELIMITERS ';' WITH NULL AS 'NULL';";
								echo "<font color='gray'><strong>$cons20</strong></font><br>";
								$res20=ExQuery($cons20);
								sleep(10);
							}
						}

						$cons20="COPY $fila2[8].$fila2[7] from '$RutaCopia/$fila2[8].$fila2[7].csv' USING DELIMITERS ';' WITH NULL AS 'NULL';";
						echo "<font color='green'><strong>$cons20</strong></font><br>";
						$res20=ExQuery($cons20);
						sleep(10);
					}
				}
				
				if(is_file("$RutaCopia/$Tabla.csv"))
				{
					$cons3="Select * from $Tabla";
					$res3=ExQuery($cons3);
					if(ExNumRows($res3)==0)
					{
						$cons20="COPY $Tabla from '$RutaCopia/$Tabla.csv' USING DELIMITERS ';' WITH NULL AS 'NULL';";
						echo $cons20."<br>";
						$res20=ExQuery($cons20);
						sleep(10);
					}
				}
				else{echo "<em><font color='#0000ff'>No existe el archivo $Tabla, omitido</font></em><br>";}
			}
		}
?>
		<script language="JavaScript">
			alert("Proceso Finalizado");
//			location.href='RestauraCopia.php'
		</script>
<?
	}
	if($ND[hours]<10){$Horas="0".$ND[hours];}else{$Horas=$ND[hours];}
	if($ND[minutes]<10){$Minutos="0".$ND[minutes];}else{$Minutos=$ND[minutes];}
	$cons="Select Ruta from Central.RutaCopias where Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$RutaInc=$fila[0];
?>
<body background="/Imgs/Fondo.jpg">
<?if(!$Generar){?>
<br><br><br><br><br><center>
<form name="FORMA">
<table cellpadding="6"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr><td rowspan="2" style="color:white" bgcolor="<?echo $Estilo[1]?>"><strong>Restaurar Copia</td>
<td colspan="3">De <input type="Text" style="width:400px;" name="RutaCopia" value="<?echo $RutaInc?>/<?echo "$ND[year]$ND[mon]$ND[mday]"?>/<?echo "$Horas$Minutos"?>/">
</td>
<tr>

<td>Configuraciones<input type="Checkbox" name="SelDB[0]" value="Central" checked></td>
<td>Contabilidad <input type="Checkbox" name="SelDB[1]" value="Contabilidad" checked></td>
<td>Presupuesto<input type="Checkbox" name="SelDB[2]" value="Presupuesto" checked></td>
</tr>
<tr><td colspan="4" align="center"><input type="Submit" name="Generar" value="Restaurar Copia"></td></tr>
</table>
<em><font color="#0000ff">
Atenci&oacute;n, este proceso puede eliminar la informaci&oacute;n registrada despues de la copia a restaurar,<br> se recomienda realizar copia preliminar antes de iniciar!!!
</em></font>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body><?}?>