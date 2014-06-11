<?
	session_start();
	include("Funciones.php");
?>
<body background="/Imgs/Fondo.jpg">
<script language="javascript">
//	alert("MUCHA ATENCION: esta version fue diseñada para actualizar codigos SIA, si ya tiene amarrados sus codigos no la utilice. Puede perder su amarre!!!");
</script>
<?
	$lev=error_reporting ('Err'); 

	if(!$TipoActualiza)
	{?>
        <form name="FORMA">
        <table border="1" align="center" bordercolor="#e5e5e5" cellpadding="8" style='font : normal normal small-caps 12px Tahoma;'>
        <tr bgcolor="#e5e5e5"><td colspan="2"><strong>Modo de Actualizacion</td></tr>
        <tr><td>Local</td><td align="center"><input type="radio" name="TipoActualiza" value="Local" onClick="document.FORMA.submit();"></td></tr>
        <tr><td>Remoto</td><td align="center"><input type="radio" name="TipoActualiza" value="Remoto" onClick="document.FORMA.submit();"></td></tr>
		
<?	}
	
	if(!$TipoActualiza){exit;}
	if($TipoActualiza=="Local"){$Servidor="localhost";}
	if(!$Servidor)
	{?>
        <table border="1" align="center" bordercolor="#e5e5e5" cellpadding="8" style='font : normal normal small-caps 12px Tahoma;'>
        <tr><td>Servidor:<td><input type="text" name="Servidor"></td></tr>
        <tr><td colspan="2" align="center"><input type="submit" name="Guardar" value="Continuar >>"></td></tr>
        </table>
        </td>
        
        
<?	}
	else
	{
	function hex2bin($hexdata)
	{
		for ($i=0;$i<strlen($hexdata);$i+=2){ 
		$bindata.=chr(hexdec(substr($hexdata,$i,2)));}
		return $bindata; 
	}

	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		$RutaVerifica=GETENV("windir")."/Release.ini";
		}
	else
	{
		$RutaVerifica="/etc/CompuConta/Release.ini";
	}

	$fichero = @fopen($RutaVerifica, "r") or die;
	while (!feof($fichero)) {
	  $contenido=fread($fichero,8192);
	}
	$DatosLic=explode("\r\n",$contenido);
	$Entidad=strtoupper(hex2bin($DatosLic[0]));
	$NoId=strtoupper(hex2bin($DatosLic[1]));
	$NoLic=strtoupper(hex2bin($DatosLic[2]));

?>
<font size="5">
<center><em>
Bienvenido al Modulo de Actualizacion en Linea
</font><br />
Esta operaci&oacute;n puede tardar varios minutos, es necesario que exista conexion a Internet.
</em>
<br />
<br />
</center>

<?php
	if($Iniciar)
	{
/*

	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		$RutaVerifica=GETENV("windir")."/Release.ini";
		}
	else
	{
		$RutaVerifica="/etc/CompuConta/Release.ini";
	}

	$fichero = @fopen($RutaVerifica, "r") or die;
	while (!feof($fichero)) {
	  $contenido=fread($fichero,8192);
	}
	$DatosLic=explode("\r\n",$contenido);
	$Entidad=strtoupper(hex2bin($DatosLic[0]));
	$NoId=strtoupper(hex2bin($DatosLic[1]));
	$NoLic=strtoupper(hex2bin($DatosLic[2]));

	$result=ftp_connect("$Servidor");
	$conftp=ftp_login($result,"jacamon","jacamon21");

	$conex3=pg_connect("host=$Servidor port=5432 dbname=software user=postgres password=Server*1982");
	$conex4=pg_connect("host=$Servidor port=5432 dbname=sistema user=postgres password=Server*1982");
	$cons="Select * from licencias where licencia='$NoLic'";		
	$res=pg_query($conex3,$cons);
	$fila=pg_fetch_row($res);
	$fila[1]=$fila[1].",Generales";
	$Modulos=explode(",",$fila[1]);
	for($i=0;$i<=count($Modulos);$i++)
	{
		$cons2="Select ruta from Central.ValidaArchivos where Modulo='$Modulos[$i]' Group By Ruta";
		$res2=pg_query($conex4,$cons2);

		while($fila2=pg_fetch_row($res2))
		{
			$RutaRoot=$_SERVER['DOCUMENT_ROOT'];
			chdir($RutaRoot);
			$Carpeta=explode("/",$fila2[0]);
			for($ij=0;$ij<=count($Carpeta)-1;$ij++)
			{
				if(is_dir($Carpeta[$ij])){chdir ("$Carpeta[$ij]");}
				else{mkdir ("$Carpeta[$ij]");chdir ("$Carpeta[$ij]");}
			}
			
		}
		$cons2="Select ruta,archivo,vigencia from Central.ValidaArchivos where Modulo='$Modulos[$i]'";
		$res2=pg_query($conex4,$cons2);
		while($fila2=pg_fetch_row($res2))
		{
			$ArchivoEvaluar=$fila2[1];$FechaRegistro=$fila2[2];
			$Directorio=$fila2[0];
			if($Directorio){$DirBusq=$Directorio."/".$ArchivoEvaluar;}
			else{$DirBusq=$ArchivoEvaluar;}
			if(is_file($_SERVER['DOCUMENT_ROOT']."/$DirBusq"))
			{
				$FechaArchivo=date("Ymd", filemtime($_SERVER['DOCUMENT_ROOT']."/$DirBusq"));
				$BusExt=explode(".",$DirBusq);
				if($BusqExt=="php" || $BusqExt=="js")
				{
					if($FechaRegistro!=$FechaArchivo)
					{
						$s++;
						$fichero = fopen($_SERVER['DOCUMENT_ROOT']."/$DirBusq", "w+");
						$descarga=ftp_fget($result,$fichero,$DirBusq,FTP_ASCII,0);
						fclose($fichero);
						$Log[$s]=array("ACTUALIZA ARCHIVO ",$Modulos[$i],$DirBusq,$fichero,$Descarga);
					}
				}
			}
			else
			{
					$s++;
					$fichero = fopen($_SERVER['DOCUMENT_ROOT']."/$DirBusq", "w+");
					$descarga=ftp_fget($result,$fichero,$DirBusq,FTP_ASCII,0);
					fclose($fichero);
					$Log[$s]=array("CARGA ARCHIVO ",$Modulos[$i],$DirBusq,$fichero,$Descarga);
			}
		}
	}
*/

	if($TipoActualiza=="Local"){$ConexString="dbname=sistemaact user=postgres password=Server*1982";}
	else{$ConexString="host=$Servidor port=5432 dbname=sistema user=postgres password=Server*1982";}
	$conex2=pg_connect("$ConexString");
	$conex=pg_connect("dbname=sistema user=postgres password=Server*1982");

///////////////////////////////////////////VERIFICACION Y CREACION DE NUEVAS TABLAS SI NO EXISTEN //////////////////////////////////////////////

		$cons="Select table_schema FROM information_schema.columns
		where table_schema!='information_schema' and table_schema!='histoclinicafrms' and table_schema!='pg_catalog'
		Group By table_schema Order By table_schema";
		$res=pg_query($conex2,$cons);
		while($fila=ExFetch($res))
		{

			$cons9="Select table_schema FROM information_schema.columns
			where table_schema!='information_schema' and table_schema!='pg_catalog'
			AND table_schema='$fila[0]'";
			$res9=pg_query($conex,$cons9);
			if(pg_num_rows($res9)==0) ////// SI NO EXISTE EL ESQUEMA EN EL CLIENTE, SE CREA
			{
				$s++;
				$cons6="CREATE SCHEMA $fila[0]";
				$res6=pg_query($conex,$cons6);
				$Log[$s]=array("CREA ESCHEMA",$fila[0],$fila1[0],$cons6,$res6,pg_last_error());
			}
			
			$cons1="Select table_name FROM information_schema.columns where table_schema='$fila[0]' group by table_name Order By table_name ;";
			$res1=pg_query($conex2,$cons1);
			while($fila1=ExFetch($res1))
			{
				$cons2="Select table_name FROM information_schema.columns where table_schema='$fila[0]' and table_name='$fila1[0]'";
				$res2=pg_query($conex,$cons2);
				if(pg_num_rows($res2)==0) /// LA TABLA SE CREO EN EL SERVER Y DEBEMOS CREARLA EN EL CLIENTE, SE CREA SI DATOS PORQUE MAS ABAJO SE ANALIZA Y CONFIGURA
				{
					$s++;
					$cons3="CREATE TABLE $fila[0].$fila1[0] (a integer)";
					$res3=pg_query($conex,$cons3);
					$Log[$s]=array("CREA TABLA",$fila[0],$fila1[0],$cons6,$res6,pg_last_error());
				}
			}
		}
		
////////////////////////AJUSTE DE CAMPOS Y VERIFICACION DE LLAVES ///////////////////////////////////

		$cons4="SELECT column_name,column_default,data_type,character_maximum_length,ordinal_position,is_nullable,table_name,table_schema 
		FROM information_schema.columns 
		where table_schema!='information_schema' and table_schema!='pg_catalog'
		Order By ordinal_position";
		$res4=pg_query($conex,$cons4);
		while($fila4=ExFetch($res4))
		{
			$CamposLocales[$fila4[6]][$fila4[7]][$fila4[0]]=array($fila4[0],$fila4[1],$fila4[2],$fila4[3],$fila4[4],$fila4[5],$fila4[6],$fila4[7]);
		}

		$cons4="SELECT column_name,column_default,data_type,character_maximum_length,ordinal_position,is_nullable,table_name,table_schema 
		FROM information_schema.columns 
		where table_schema!='information_schema' and table_schema!='pg_catalog'
		Order By ordinal_position";
		$res4=pg_query($conex2,$cons4);
		while($fila4=ExFetch($res4))
		{
			$CamposRemotos[$fila4[6]][$fila4[7]][$fila4[0]]=array($fila4[0],$fila4[1],$fila4[2],$fila4[3],$fila4[4],$fila4[5],$fila4[6],$fila4[7]);
			$OrdinalCMP[$fila4[4]][$fila4[6]][$fila4[7]]=array($fila4[0],$fila4[1],$fila4[2],$fila4[3],$fila4[4],$fila4[5],$fila4[6],$fila4[7]);
		}


		$cons4="SELECT table_name,table_schema,constraint_name
		FROM information_schema.table_constraints
		where constraint_type='FOREIGN KEY'";
		$res4=pg_query($conex,$cons4);
		while($fila4=ExFetch($res4))
		{
			$FKLocales[$fila4[0]][$fila4[1]][$fila4[2]]=array($fila4[0],$fila4[1],$fila4[2]);
		}

		$cons4="SELECT table_name,table_schema,constraint_name
		FROM information_schema.table_constraints
		where constraint_type='FOREIGN KEY'";
		$res4=pg_query($conex2,$cons4);
		while($fila4=ExFetch($res4))
		{
			$FKRemotos[$fila4[0]][$fila4[1]][$fila4[2]]=array($fila4[0],$fila4[1],$fila4[2]);
		}

		$cons4="SELECT table_name,table_schema,constraint_name
		FROM information_schema.table_constraints
		where constraint_type='PRIMARY KEY'";
		$res4=pg_query($conex,$cons4);
		while($fila4=ExFetch($res4))
		{
			$PKLocales[$fila4[0]][$fila4[1]][$fila4[2]]=array($fila4[0],$fila4[1],$fila4[2]);
		}

		$cons4="SELECT table_name,table_schema,constraint_name
		FROM information_schema.table_constraints
		where constraint_type='PRIMARY KEY'";
		$res4=pg_query($conex2,$cons4);
		while($fila4=ExFetch($res4))
		{
			$PKRemotos[$fila4[0]][$fila4[1]][$fila4[2]]=array($fila4[0],$fila4[1],$fila4[2]);
		}

		

		$cons4="Select table_name,table_schema,constraint_name,column_name
		FROM information_schema.constraint_column_usage";
		$res4=pg_query($conex,$cons4);
		while($fila4=ExFetch($res4))
		{
			$LlavesLocal[$fila4[0]][$fila4[1]][$fila4[2]][$fila4[3]]=array($fila4[0],$fila4[1],$fila4[2],$fila4[3]);
		}

		$cons4="Select table_name,table_schema,constraint_name,column_name
		FROM information_schema.constraint_column_usage";
		$res4=pg_query($conex2,$cons4);
		while($fila4=ExFetch($res4))
		{
			$LlavesRemoto[$fila4[0]][$fila4[1]][$fila4[2]][$fila4[3]]=array($fila4[0],$fila4[1],$fila4[2],$fila4[3]);
		}


		$cons4="Select unique_constraint_name,unique_constraint_schema,constraint_schema,constraint_name
		FROM information_schema.referential_constraints";
		$res4=pg_query($conex,$cons4);
		while($fila4=ExFetch($res4))
		{
			$ReferenciaLocal[$fila4[1]][$fila4[0]][$fila4[3]]=array($fila4[0],$fila4[1],$fila4[2],$fila4[3]);
		}

		$cons4="Select table_name,table_schema,constraint_name
		FROM information_schema.table_constraints";
		$res4=pg_query($conex,$cons4);
		while($fila4=ExFetch($res4))
		{
			$EstructConstraintLoc[$fila4[2]]=array($fila4[0],$fila4[1],$fila4[2]);
		}
	
	
		$cons4="Select conname,connamespace,conrelid,confrelid,conkey,confkey from pg_constraint";
		$res4=pg_query($conex2,$cons4);
		while($fila4=ExFetch($res4))
		{
			$EstrFKRemoto[$fila4[0]][$fila4[1]]=array($fila4[0],$fila4[1],$fila4[2],$fila4[3],$fila4[4],$fila4[5]);
		}
		
		$cons4="Select relname,relnamespace,oid from pg_class";
		$res4=pg_query($conex2,$cons4);
		while($fila4=ExFetch($res4))
		{
			$OIDTables[$fila4[0]][$fila4[1]]=array($fila4[0],$fila4[1],$fila4[2]);
			$NOIDTables[$fila4[2]]=array($fila4[0],$fila4[1],$fila4[2]);
		}


		$cons4="Select nspname,oid from pg_namespace";
		$res4=pg_query($conex2,$cons4);
		while($fila4=ExFetch($res4))
		{
			$OIDBD[$fila4[0]]=$fila4[1];
			$NOIDBD[$fila4[1]]=$fila4[0];
		}
		
		
		$cons="Select table_schema FROM information_schema.columns
		where table_schema!='information_schema' and table_schema!='pg_catalog' and table_schema!='histoclinicafrms'
		Group By table_schema Order By table_schema";
		$res=pg_query($conex,$cons);
		while($fila=ExFetch($res))
		{
			$cons1="Select table_name FROM information_schema.columns where table_schema='$fila[0]' and table_schema!='histoclinicafrms' group by table_name Order By table_name ;";
			$res1=pg_query($conex,$cons1);
			while($fila1=ExFetch($res1))
			{
				$cons2="Select table_name FROM information_schema.columns where table_schema='$fila[0]' and table_name='$fila1[0]'";
				$res2=pg_query($conex2,$cons2);
				if(pg_num_rows($res2)==0) /// LA TABLA YA NO ES NECESARIA; SE ELIMINA
				{
					$s++;
					$cons3="Drop table $fila[0].$fila1[0]";
					$res3=pg_query($conex,$cons3);
					$Log[$s]=array("ELIMINA TABLA",$fila[0],$fila1[0],$cons3,$res3,pg_last_error());
				}
				else
				{
					foreach($CamposLocales[$fila1[0]][$fila[0]] as $Locales)
					{
						if(!$CamposRemotos[$fila1[0]][$fila[0]][$Locales[0]])/// EL CAMPO YA NO ES NECESARIO, SE ELIMINA
						{
							$s++;
							$cons6="ALTER TABLE $fila[0].$fila1[0] DROP COLUMN $Locales[0] ";
							$res6=pg_query($conex,$cons6);
							$Log[$s]=array("ELIMINA CAMPO",$fila[0],$fila1[0],$cons6,$res6,pg_last_error());
						}
					}

					foreach($CamposRemotos[$fila1[0]][$fila[0]] as $Remotos)
					{
						if(!$CamposLocales[$fila1[0]][$fila[0]][$Remotos[0]])/// SE CREA EL CAMPO NUEVO QUE NO EXISTA
						{
							$s++;
							if($Remotos[1] != NULL){$DEF=" DEFAULT $Remotos[1]";}else{$DEF="";}
							if($Remotos[3]){$LargDato="($Remotos[3])";}else{$LargDato="";}
							if($Remotos[5]=="NO")
							{
								$NOTNULL="NOT NULL";
								if($DEF=="")
								{
									$cons10="Select * from $fila[0].$fila1[0]";
									$res10=pg_query($conex,$cons10);
									if(pg_num_rows($res10)>0)
									{
										if($Remotos[2]=="date"){$DEF="DEFAULT '1980-04-17'";}
										else{$DEF="DEFAULT 0";}
									}
								}
							}
							else{$NOTNULL="";}
							$cons6="Alter table $fila[0].$fila1[0] ADD COLUMN $Remotos[0] $Remotos[2] $LargDato $NOTNULL $DEF";
							$res6=pg_query($conex,$cons6);
							$Log[$s]=array("AGREGAR CAMPO",$fila[0],$fila1[0],$cons6,$res6,pg_last_error());
						}
						else ///SI EL CAMPO EXISTE, VERIFICAMOS SI SU ESTRUCTURA HA CAMBIADO
						{
							

							if($CamposLocales[$fila1[0]][$fila[0]][$Remotos[0]][2]!=$Remotos[2]) //TIPO DE DATOS
							{
								$s++;
								if($Remotos[3]){$LargDato="($Remotos[3])";}else{$LargDato="";}
								$cons6="ALTER TABLE $fila[0].$fila1[0] ALTER COLUMN $Remotos[0] TYPE $Remotos[2] $LargDato ";
								$res6=pg_query($conex,$cons6);
								$Log[$s]=array("MODIFICA DATA TYPE",$fila[0],$fila1[0],$cons6,$res6,pg_last_error());
							}

							if($CamposLocales[$fila1[0]][$fila[0]][$Remotos[0]][3]!=$Remotos[3]) //LONGITUD DE DATOS
							{
								$s++;
								if($Remotos[3]){$LargDato="($Remotos[3])";}else{$LargDato="";}
								$cons6="ALTER TABLE $fila[0].$fila1[0] ALTER COLUMN $Remotos[0] TYPE $Remotos[2] $LargDato ";
								$res6=pg_query($conex,$cons6);
								$Log[$s]=array("MODIFICA LONGITUD",$fila[0],$fila1[0],$cons6,$res6,pg_last_error());
							}

							if($CamposLocales[$fila1[0]][$fila[0]][$Remotos[0]][1]!=$Remotos[1]) //DEFAULT VALUE
							{
								$s++;
								if(!$Remotos[1]){$cons6="ALTER TABLE $fila[0].$fila1[0] ALTER COLUMN $Remotos[0] Drop DEFAULT";}
								else{$cons6="ALTER TABLE $fila[0].$fila1[0] ALTER COLUMN $Remotos[0] SET DEFAULT $Remotos[1]";}
								$res6=pg_query($conex,$cons6);
								$Log[$s]=array("MODIFICA DEFAULT",$fila[0],$fila1[0],$cons6,$res6,pg_last_error());
							}


							if($CamposLocales[$fila1[0]][$fila[0]][$Remotos[0]][5]!=$Remotos[5]) //NOT NULL
							{
								$s++;
								if($Remotos[5]=="YES"){$cons6="ALTER TABLE $fila[0].$fila1[0] ALTER COLUMN $Remotos[0] Drop NOT NULL";}
								else{
									$cons7="Select * from $fila[0].$fila1[0] where $Remotos[0] IS NULL";
									$res7=pg_query($conex,$cons7);
									if(pg_num_rows($res7)>0)
									{
										if($Remotos[1]){$cons10="Update $fila[0].$fila1[0] set $Remotos[0]='$Remotos[1]' where $Remotos[0] IS NULL";}
										else{$cons10="Update $fila[0].$fila1[0] set $Remotos[0]=0 where $Remotos[0] IS NULL";}
									}
									$res10=pg_query($conex,$cons10);
									$s++;
									$Log[$s]=array("PONER VALORES DEFECTO X NULIDAD",$fila[0],$fila1[0],$cons10,$res10,pg_last_error());
									$cons6="ALTER TABLE $fila[0].$fila1[0] ALTER COLUMN $Remotos[0] SET NOT NULL";
								}
								$res6=pg_query($conex,$cons6);
								$Log[$s]=array("MODIFICA NULLS",$fila[0],$fila1[0],$cons6,$res6,pg_last_error());
							}


						}
					}
					////////////////////////VALIDACION DE PRIMARY KEYS ///////////////////////////////

					if(count($PKLocales[$fila1[0]][$fila[0]]))
					{
						foreach($PKLocales[$fila1[0]][$fila[0]] as $Locales) //////VERIFICAMOS SI EL PK AUN ESTA VIGENTE, DE LO CONTRARIO LO ELIMINAMOS
						{
							if(!$PKRemotos[$fila1[0]][$fila[0]][$Locales[2]])
							{
								$s++;
								$cons6="ALTER TABLE $fila[0].$fila1[0] DROP CONSTRAINT \"$Locales[2]\"";
								$res6=pg_query($conex,$cons6);
								$Log[$s]=array("ELIMINA PK",$fila[0],$fila1[0],$cons6,$res6,pg_last_error());
							}
						}
					}

					if(count($PKRemotos[$fila1[0]][$fila[0]]))
					{
						foreach($PKRemotos[$fila1[0]][$fila[0]] as $Remotos)////VERIFICAMOS QUE LOS INDICES REMOTOS EXISTAN
						{
							if(!$PKLocales[$fila1[0]][$fila[0]][$Remotos[2]])////EL INDICE NO EXISTE, SE CREA
							{
								$Campos="";
								foreach($LlavesRemoto[$fila1[0]][$fila[0]][$Remotos[2]] as $CmpKeys)
								{
									$Campos=$Campos.$CmpKeys[3].",";
								}
								$Campos=substr($Campos,0,strlen($Campos)-1);
								$s++;
								$cons6="ALTER TABLE $fila[0].$fila1[0] ADD CONSTRAINT \"$Remotos[2]\" PRIMARY KEY ($Campos)";
								$res6=pg_query($conex,$cons6);
								$Log[$s]=array("ELIMINA FK X MODIFICACION DE PK",$fila[0],$fila1[0],$cons6,$res6,pg_last_error());
							}
							else ////VERIFICAMOS SI EL INDICE SE MANTIENE IGUAL O SE HA VARIADO SU ESTRUCTURA
							{
								if( count($LlavesRemoto[$fila1[0]][$fila[0]][$Remotos[2]]) != count($LlavesLocal[$fila1[0]][$fila[0]][$Remotos[2]])  ) //SI LOS CAMPOS HAN CAMBIADO, INTENTAMOS REACOMODARLOS
								{
//									echo "<hr>".$ReferenciaLocal[$fila[0]][$Remotos[2]][3]."<hr>";
									foreach($ReferenciaLocal[$fila[0]][$Remotos[2]] as $DatFK) ///VALIDAMOS SI EXISTEN LLAVES FORANEAS DEPENDIENTES DE ESTA PK Y LAS ELIMINAMOS
									{
										$EschemCT=$EstructConstraintLoc[$DatFK[3]][1];
										$TableCT=$EstructConstraintLoc[$DatFK[3]][0];
										$FKCT=$EstructConstraintLoc[$DatFK[3]][2];
										$s++;
										$cons6="ALTER TABLE $EschemCT.$TableCT DROP CONSTRAINT \"$FKCT\"";
										$res6=pg_query($conex,$cons6);
										$Log[$s]=array("ELIMINA PK X MODIFICACION",$fila[0],$fila1[0],$cons6,$res6,pg_last_error());
									}

									
									$s++;
									$cons6="ALTER TABLE $fila[0].$fila1[0] DROP CONSTRAINT \"$Remotos[2]\"";
									$res6=pg_query($conex,$cons6);
									$Log[$s]=array("ELIMINA PK X MODIFICACION",$fila[0],$fila1[0],$cons6,$res6,pg_last_error());

									$Campos="";
									foreach($LlavesRemoto[$fila1[0]][$fila[0]][$Remotos[2]] as $CmpKeys)
									{
										$Campos=$Campos.$CmpKeys[3].",";
									}
									$Campos=substr($Campos,0,strlen($Campos)-1);
									$s++;
									$cons6="ALTER TABLE $fila[0].$fila1[0] ADD CONSTRAINT \"$Remotos[2]\" PRIMARY KEY ($Campos)";
									$res6=pg_query($conex,$cons6);
									$Log[$s]=array("CREAR PK X MODIFICACION",$fila[0],$fila1[0],$cons6,$res6,pg_last_error());
									
								}
							}
						}
					}
					
					///////////////////////////////////////VALIDACION DE FOREIGN KEYS ///////////////////////////////////
					if(count($FKLocales[$fila1[0]][$fila[0]]))
					{
						foreach($FKLocales[$fila1[0]][$fila[0]] as $Locales) //////VERIFICAMOS SI EL FK AUN ESTA VIGENTE, DE LO CONTRARIO LO ELIMINAMOS
						{
							if(!$FKRemotos[$fila1[0]][$fila[0]][$Locales[2]])
							{
								$s++;
								$cons6="ALTER TABLE $fila[0].$fila1[0] DROP CONSTRAINT \"$Locales[2]\"";
								$res6=pg_query($conex,$cons6);
								$Log[$s]=array("ELIMINA FK",$fila[0],$fila1[0],$cons6,$res6,pg_last_error());
							}
						}
					}
					
					if(count($FKRemotos[$fila1[0]][$fila[0]]))
					{
						foreach($FKRemotos[$fila1[0]][$fila[0]] as $Remotos)////VERIFICAMOS QUE LOS INDICES REMOTOS EXISTAN
						{
							if(!$FKLocales[$fila1[0]][$fila[0]][$Remotos[2]])////EL INDICE NO EXISTE, SE CREA
							{
								$TablaFK=$EstrFKRemoto[$Remotos[2]][$OIDBD[$fila[0]]][3];
								
								$NomTablaFK=$NOIDTables[$TablaFK][0];
								$BDTableFK=$NOIDBD[$NOIDTables[$TablaFK][1]];
								

								$CamposPK=$EstrFKRemoto[$Remotos[2]][$OIDBD[$fila[0]]][5];

								$CamposFK=$EstrFKRemoto[$Remotos[2]][$OIDBD[$fila[0]]][4];
								
								$CamposFK=str_replace("{","",$CamposFK);
								$CamposFK=str_replace("}","",$CamposFK);

								$CamposPK=str_replace("{","",$CamposPK);
								$CamposPK=str_replace("}","",$CamposPK);
								
								$DatCmpFK=explode(",",$CamposFK);
								$Campos1="";$Campos2="";
								foreach($DatCmpFK as $DatCmpFKRes)
								{
									$Campos1=$Campos1.$OrdinalCMP[$DatCmpFKRes][$fila1[0]][$fila[0]][0].",";
								}
								$Campos1=substr($Campos1,0,strlen($Campos1)-1);
								
								$DatCmpPK=explode(",",$CamposPK);
								foreach($DatCmpPK as $DatCmpPKRes)
								{
									$Campos2=$Campos2.$OrdinalCMP[$DatCmpPKRes][$NomTablaFK][$BDTableFK][0].",";
								}
								$Campos2=substr($Campos2,0,strlen($Campos2)-1);
								$s++;
								$cons6="ALTER TABLE $fila[0].$fila1[0] ADD CONSTRAINT \"$Remotos[2]\" FOREIGN KEY ($Campos1)
										REFERENCES $BDTableFK.$NomTablaFK ($Campos2) MATCH SIMPLE ON UPDATE CASCADE ON DELETE RESTRICT";
								$res6=pg_query($conex,$cons6);
								$Log[$s]=array("CREA FK",$fila[0],$fila1[0],$cons6,$res6,pg_last_error());
							}
							else ////VERIFICAMOS SI EL INDICE SE MANTIENE IGUAL O SE HA VARIADO SU ESTRUCTURA
							{
								if( count($LlavesRemoto[$fila1[0]][$fila[0]][$Remotos[2]]) != count($LlavesLocal[$fila1[0]][$fila[0]][$Remotos[2]])  ) //SI LOS CAMPOS HAN CAMBIADO, INTENTAMOS REACOMODARLOS
								{
									$s++;
									$cons6="ALTER TABLE $fila[0].$fila1[0] DROP CONSTRAINT \"$Remotos[2]\"";
									$res6=pg_query($conex,$cons6);
									$Log[$s]=array("ELIMINA FK X MODIFICACION",$fila[0],$fila1[0],$cons6,$res6,pg_last_error());
										
									$TablaFK=$EstrFKRemoto[$Remotos[2]][$OIDBD[$fila[0]]][3];
									
									$NomTablaFK=$NOIDTables[$TablaFK][0];
									$BDTableFK=$NOIDBD[$NOIDTables[$TablaFK][1]];
									
	
									$CamposPK=$EstrFKRemoto[$Remotos[2]][$OIDBD[$fila[0]]][5];
	
									$CamposFK=$EstrFKRemoto[$Remotos[2]][$OIDBD[$fila[0]]][4];
									
									$CamposFK=str_replace("{","",$CamposFK);
									$CamposFK=str_replace("}","",$CamposFK);
	
									$CamposPK=str_replace("{","",$CamposPK);
									$CamposPK=str_replace("}","",$CamposPK);
									
									$DatCmpFK=explode(",",$CamposFK);
									$Campos1="";$Campos2="";
									foreach($DatCmpFK as $DatCmpFKRes)
									{
										$Campos1=$Campos1.$OrdinalCMP[$DatCmpFKRes][$fila1[0]][$fila[0]][0].",";
									}
									$Campos1=substr($Campos1,0,strlen($Campos1)-1);
									
									$DatCmpPK=explode(",",$CamposPK);
									foreach($DatCmpPK as $DatCmpPKRes)
									{
										$Campos2=$Campos2.$OrdinalCMP[$DatCmpPKRes][$NomTablaFK][$BDTableFK][0].",";
									}
									$Campos2=substr($Campos2,0,strlen($Campos2)-1);
									$s++;
									$cons6="ALTER TABLE $fila[0].$fila1[0] ADD CONSTRAINT \"$Remotos[2]\" FOREIGN KEY ($Campos1)
											REFERENCES $BDTableFK.$NomTablaFK ($Campos2) MATCH SIMPLE ON UPDATE CASCADE ON DELETE RESTRICT";
									$res6=pg_query($conex,$cons6);
									$Log[$s]=array("CREA FK",$fila[0],$fila1[0],$cons6,$res6,pg_last_error());
								}
							}
						}
					}					
				}
			}
		}
		
/////////////////////////////ACTUALIZAR ACCESO X MODULOS /////////////////////////////////////
		$cons="Delete from Central.AccesoxModulos";
		$res=pg_query($conex,$cons);

		$cons="Select * from Central.AccesoxModulos";
		$res=pg_query($conex2,$cons);
		while($fila=pg_fetch_row($res))
		{
			$cons2="Insert into Central.AccesoxModulos(Id,Perfil,Nivel,Madre,Ruta,Frame,ModuloGr)
			values('$fila[0]','$fila[1]','$fila[2]','$fila[3]','$fila[4]','$fila[5]','$fila[6]')";
			$res2=pg_query($conex,$cons2);
		}

		$cons="Delete from Central.Reportes";
		$res=pg_query($conex,$cons);

		$cons="Select * from Central.Reportes";
		$res=pg_query($conex2,$cons);
		while($fila=pg_fetch_row($res))
		{
			$cons2="Insert into Central.Reportes(Modulo,Id,Nombre,Tipo,Archivo,Clase)
			values('$fila[0]','$fila[1]','$fila[2]','$fila[3]','$fila[4]','$fila[5]')";
			$res2=pg_query($conex,$cons2);
		}
/*
		$cons="Update Presupuesto.PlanCuentas set SIA=NULL";
		$res=pg_query($conex,$cons);


		$cons="Delete from Presupuesto.CodigosSIA";
		$res=pg_query($conex,$cons);


		$cons="Select * from Presupuesto.CodigosSIA";
		$res=pg_query($conex2,$cons);
		while($fila=pg_fetch_row($res))
		{
			$cons2="Insert into Presupuesto.CodigosSIA(Codigo,Detalle,Tipo,Clase)
			values('$fila[0]','$fila[1]','$fila[2]','$fila[3]')";
			$res2=pg_query($conex,$cons2);
		}

*/
		echo "<table border='1' bordercolor='#e5e5e5' style='font-size:10px;font-family:tahoma;'>";
		echo "<tr><td colspan=6 bgcolor='#e5e5e5'><strong><center>RESUMEN DE ACTUALIZACIONES</td></tr>";
		for($h=1;$h<=count($Log)-1;$h++)
		{
			echo "<tr><td>".$Log[$h][0]."</td><td>".$Log[$h][1]."</td><td>".$Log[$h][2]."</td><td>".$Log[$h][3]."</td><td>".$Log[$h][4]."</td><td>".$Log[$h][5]."</td></tr>";
		}
		echo "</table>";
		echo "PROCESO FINALIZADO ADECUADAMENTE";
	}
	else{

	$ConexInternet="";
	$ConexInternet=file_get_contents("http://www.google.com/");
$ConexInternet=1;
	if($ConexInternet){$Internet="<img src='/Imgs/VoBo.jpg' style='width:20px;'>";$ConexInt=1;}
	else{$Internet="<img src='/Imgs/No.png' style='width:20px;'>";$ConexInt=0;}
?>
<form name="FORMA">
<table border="1" align="center" bordercolor="#e5e5e5" cellpadding="8" style='font : normal normal small-caps 12px Tahoma;'>

<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Tarea</td><td>Resultado</td></tr>
<?	if($TipoActualiza!='Local'){ $ConexString="host=$Servidor port=5432 dbname=software user=postgres password=Server*1982";?>
<tr><td>Conexi&oacute;n a Internet</td><td align="center"><? echo  $Internet; ?></td></tr>
<?	if(!$ConexInt){exit;}}
	else{
		$ConexString="dbname=sistemaact user=postgres password=Server*1982";
		
	}
?>
<tr><td>Acceso al Servidor</td><td align="center"><? if($conex = pg_connect("$ConexString")){echo "<img src='/Imgs/VoBo.jpg' style='width:20px;'>";$ConexServ=1;}else{echo "<img src='/Imgs/No.png' style='width:20px;'>";$ConexServ=0;}?></td></tr>
<?	if(!$ConexServ){exit;}?>
<tr><td colspan="2" bgcolor="#e5e5e5" style="font-weight:bold" align="center">Productos Licenciados</td></tr>
<?
	if($TipoActualiza!='Local')
	{
		$cons="Select Modulos,Entidad,Nit from licencias where licencia='$NoLic'";		
		$res=pg_query($conex,$cons);
		$fila=pg_fetch_row($res);
	
		if(strtoupper($fila[1])!=strtoupper($Entidad) || strtoupper($fila[2])!=strtoupper($NoId))
		{
			echo "<tr><td align='right'><font color='red'>Licencia NO valida o vencida</font></td><td align='center'><img src='/Imgs/No.png' style='width:20px;'></td></tr>";
			exit;
		}
		
		$Modulos=explode(",",$fila[0]);
		for($i=0;$i<=count($Modulos)-1;$i++)
		{
			echo "<tr><td align='right'>$Modulos[$i]</td><td align='center'><img src='/Imgs/VoBo.jpg' style='width:20px;'></td></tr>";
		}
	}
?>
<tr><td colspan="2" align="center">
<input type="button" name="Iniciar" value="Actualizar" onClick="open('ActualizaEnLinea.php?DatNameSID=<? echo $DatNameSID?>&TipoActualiza=Local&Servidor=<? echo $Servidor?>&Iniciar=1','','width=800,height=600,scrollbars=yes')" /></td></tr>
<!-- -->
</table>
<input type="hidden" name="Servidor" value="<? echo $Servidor?>">
</form>
</body>
<?	}}?>
