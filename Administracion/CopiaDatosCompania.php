<?
	include("Funciones.php");
	if($Reemplazar)
	{
		$conscc="Insert into central.Compania(Select '$NuevaCompania','999999999',Direccion,Estilo,Telefonos,FirmaPresupuesto,NomFirmPres 
		from central.compania Where nombre = '$Compania')";
		$rescc=ExQuery($conscc);
		//--PRIORIDAD
		//--central
		$cons1="Select table_name FROM information_schema.columns where table_schema='central' group by table_name order by table_name";
		$res1=ExQuery($cons1);
		while($fila1=ExFetch($res1))
		{
			echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$fila1[0];	
			$cons2="select column_name from information_schema.columns where table_schema='central' and table_name = '$fila1[0]' 
			and column_name='compania' Order By ordinal_position";			
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)>0)
			{
				$cons2="select column_name from information_schema.columns where table_schema='central' and table_name = '$fila1[0]' 
				Order By ordinal_position";			
				$res2=ExQuery($cons2);
				$cons3="Insert into central.$fila1[0]( Select ";
				while($fila2=ExFetch($res2))
				{
					if($fila2[0]=="compania")
					{
						$cons3=$cons3."'$NuevaCompania', ";
					}
					else
					{
						$cons3=$cons3."$fila2[0], ";	
					}									
				}
				$cons3=substr($cons3,0,strlen($cons3)-2);
				$cons3=$cons3."  from central.$fila1[0] Where compania = '$Compania')";
				$res3=ExQuery($cons3);
				echo "<br><br>".$cons3."<br><br>";
			}
		}
		//--CONTABILIDAD		
		//--presupuesto.comprobantes
		$cons2="select column_name from information_schema.columns where table_schema='presupuesto' and table_name = 'comprobantes' 
		Order By ordinal_position";			
		$res2=ExQuery($cons2);
		$cons3="Insert into presupuesto.comprobantes( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";	
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from presupuesto.comprobantes Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";
		//---contabilidad.comprobantes
		$cons2="select column_name from information_schema.columns where table_schema='contabilidad' and table_name = 'comprobantes' 
		Order By ordinal_position";
		$res2=ExQuery($cons2);
		$cons3="Insert into contabilidad.comprobantes( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from contabilidad.comprobantes Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";
		//---		
		$cons2="select column_name from information_schema.columns where table_schema='contabilidad' and table_name = 'plancuentas' 
		Order By ordinal_position";			
		$res2=ExQuery($cons2);
		$cons3="Insert into contabilidad.plancuentas( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";	
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from contabilidad.plancuentas Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";		
		//---contabilidad.conceptospago
		$cons2="select column_name from information_schema.columns where table_schema='contabilidad' and table_name = 'conceptospago' 
		Order By ordinal_position";
		$res2=ExQuery($cons2);
		$cons3="Insert into contabilidad.conceptospago( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from contabilidad.conceptospago Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";
		//---Demas Tablas Contabilidad		
		$cons1="Select table_name FROM information_schema.columns where table_schema='contabilidad' and table_name!='comprobantes'
		and table_name!='plancuentas' and table_name!='conceptospago'  group by table_name";
		$res1=ExQuery($cons1);
		while($fila1=ExFetch($res1))
		{
			echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$fila1[0];	
			$cons2="select column_name from information_schema.columns where table_schema='contabilidad' and table_name = '$fila1[0]' 
			and column_name='compania' Order By ordinal_position";			
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)>0)
			{
				$cons2="select column_name from information_schema.columns where table_schema='contabilidad' and table_name = '$fila1[0]' 
				Order By ordinal_position";			
				$res2=ExQuery($cons2);
				$cons3="Insert into contabilidad.$fila1[0]( Select ";
				while($fila2=ExFetch($res2))
				{
					if($fila2[0]=="compania")
					{
						$cons3=$cons3."'$NuevaCompania', ";
					}
					else
					{
						$cons3=$cons3."$fila2[0], ";	
					}									
				}
				$cons3=substr($cons3,0,strlen($cons3)-2);
				$cons3=$cons3."  from contabilidad.$fila1[0] Where compania = '$Compania')";
				$res3=ExQuery($cons3);
				echo "<br><br>".$cons3."<br><br>";
			}
		}
		//--PRESUPUESTO		
		$cons2="select column_name from information_schema.columns where table_schema='presupuesto' and table_name = 'plancuentas' 
		Order By ordinal_position";			
		$res2=ExQuery($cons2);
		$cons3="Insert into presupuesto.plancuentas( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";	
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from presupuesto.plancuentas Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";
		//---
		$cons1="Select table_name FROM information_schema.columns where table_schema='presupuesto' and table_name!='comprobantes'
		and table_name!='plancuentas' 
		group by table_name order by table_name";
		$res1=ExQuery($cons1);
		while($fila1=ExFetch($res1))
		{
			echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$fila1[0];	
			$cons2="select column_name from information_schema.columns where table_schema='presupuesto' and table_name = '$fila1[0]' 
			and column_name='compania' Order By ordinal_position";			
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)>0)
			{
				$cons2="select column_name from information_schema.columns where table_schema='presupuesto' and table_name = '$fila1[0]' 
				Order By ordinal_position";			
				$res2=ExQuery($cons2);
				$cons3="Insert into presupuesto.$fila1[0]( Select ";
				while($fila2=ExFetch($res2))
				{
					if($fila2[0]=="compania")
					{
						$cons3=$cons3."'$NuevaCompania', ";
					}
					else
					{
						$cons3=$cons3."$fila2[0], ";	
					}									
				}
				$cons3=substr($cons3,0,strlen($cons3)-2);
				$cons3=$cons3."  from presupuesto.$fila1[0] Where compania = '$Compania')";
				$res3=ExQuery($cons3);
				echo "<br><br>".$cons3."<br><br>";
			}
		}		
		//--CONSUMO
		//---consumo.almacenesppales
		$cons2="select column_name from information_schema.columns where table_schema='consumo' and table_name = 'almacenesppales' 
		Order By ordinal_position";
		$res2=ExQuery($cons2);
		$cons3="Insert into consumo.almacenesppales( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from consumo.almacenesppales Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";
		//---consumo.bodegas
		$cons2="select column_name from information_schema.columns where table_schema='consumo' and table_name = 'bodegas' 
		Order By ordinal_position";
		$res2=ExQuery($cons2);
		$cons3="Insert into consumo.bodegas( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from consumo.bodegas Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";
		//---consumo.grupos
		$cons2="select column_name from information_schema.columns where table_schema='consumo' and table_name = 'grupos' 
		Order By ordinal_position";
		$res2=ExQuery($cons2);
		$cons3="Insert into consumo.grupos( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from consumo.grupos Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";
		//---consumo.presentacionproductos
		$cons2="select column_name from information_schema.columns where table_schema='consumo' and table_name = 'presentacionproductos' 
		Order By ordinal_position";
		$res2=ExQuery($cons2);
		$cons3="Insert into consumo.presentacionproductos( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from consumo.presentacionproductos Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";
		//---consumo.tiposproducto
		$cons2="select column_name from information_schema.columns where table_schema='consumo' and table_name = 'tiposproducto' 
		Order By ordinal_position";
		$res2=ExQuery($cons2);
		$cons3="Insert into consumo.tiposproducto( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from consumo.tiposproducto Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";
		//---consumo.unidadmedida
		$cons2="select column_name from information_schema.columns where table_schema='consumo' and table_name = 'unidadmedida' 
		Order By ordinal_position";
		$res2=ExQuery($cons2);
		$cons3="Insert into consumo.unidadmedida( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from consumo.unidadmedida Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";
		//---consumo.codproductos
		$cons2="select column_name from information_schema.columns where table_schema='consumo' and table_name = 'codproductos' 
		Order By ordinal_position";
		$res2=ExQuery($cons2);
		$cons3="Insert into consumo.codproductos( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from consumo.codproductos Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";
		//---consumo.comprobantes
		$cons2="select column_name from information_schema.columns where table_schema='consumo' and table_name = 'comprobantes' 
		Order By ordinal_position";
		$res2=ExQuery($cons2);
		$cons3="Insert into consumo.comprobantes( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from consumo.comprobantes Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";
		//---Demas tablas consumo
		$cons1="Select table_name FROM information_schema.columns where table_schema='consumo' and table_name!='comprobantes' 
		and table_name!='almacenesppales' and table_name!='codproductos' and table_name!='bodegas' and table_name!='presentacionproductos'
		and table_name!='grupos' and table_name!='tiposproducto' and table_name!='unidadmedida' group by table_name Order By table_name ;";
		$res1=ExQuery($cons1);
		while($fila1=ExFetch($res1))
		{
			echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$fila1[0];	
			$cons2="select column_name from information_schema.columns where table_schema='consumo' and table_name = '$fila1[0]' 
			and column_name='compania' Order By ordinal_position";			
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)>0)
			{
				$cons2="select column_name from information_schema.columns where table_schema='consumo' and table_name = '$fila1[0]' 
				Order By ordinal_position";			
				$res2=ExQuery($cons2);
				$cons3="Insert into consumo.$fila1[0]( Select ";
				while($fila2=ExFetch($res2))
				{
					if($fila2[0]=="compania")
					{
						$cons3=$cons3."'$NuevaCompania', ";
					}
					else
					{
						$cons3=$cons3."$fila2[0], ";	
					}									
				}
				$cons3=substr($cons3,0,strlen($cons3)-2);
				$cons3=$cons3."  from consumo.$fila1[0] Where compania = '$Compania')";
				$res3=ExQuery($cons3);
				echo "<br><br>".$cons3."<br><br>";
			}
		}
		//--
		//--contratacionsalud
		$cons1="Select table_name FROM information_schema.columns where table_schema='contratacionsalud' group by table_name Order By table_name ;";
		$res1=ExQuery($cons1);
		while($fila1=ExFetch($res1))
		{
			echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$fila1[0];	
			$cons2="select column_name from information_schema.columns where table_schema='contratacionsalud' and table_name = '$fila1[0]' 
			and column_name='compania' Order By ordinal_position";			
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)>0)
			{
				$cons2="select column_name from information_schema.columns where table_schema='contratacionsalud' and table_name = '$fila1[0]' 
				Order By ordinal_position";			
				$res2=ExQuery($cons2);
				$cons3="Insert into contratacionsalud.$fila1[0]( Select ";
				while($fila2=ExFetch($res2))
				{
					if($fila2[0]=="compania")
					{
						$cons3=$cons3."'$NuevaCompania', ";
					}
					else
					{
						$cons3=$cons3."$fila2[0], ";	
					}									
				}
				$cons3=substr($cons3,0,strlen($cons3)-2);
				$cons3=$cons3."  from contratacionsalud.$fila1[0] Where compania = '$Compania')";
				$res3=ExQuery($cons3);
				echo "<br><br>".$cons3."<br><br>";
			}
		}
		//--salud
		$cons1="Select table_name FROM information_schema.columns where table_schema='salud' group by table_name Order By table_name ;";
		$res1=ExQuery($cons1);
		while($fila1=ExFetch($res1))
		{
			echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$fila1[0];	
			$cons2="select column_name from information_schema.columns where table_schema='salud' and table_name = '$fila1[0]' 
			and column_name='compania' Order By ordinal_position";			
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)>0)
			{
				$cons2="select column_name from information_schema.columns where table_schema='salud' and table_name = '$fila1[0]' 
				Order By ordinal_position";			
				$res2=ExQuery($cons2);
				$cons3="Insert into salud.$fila1[0]( Select ";
				while($fila2=ExFetch($res2))
				{
					if($fila2[0]=="compania")
					{
						$cons3=$cons3."'$NuevaCompania', ";
					}
					else
					{
						$cons3=$cons3."$fila2[0], ";	
					}									
				}
				$cons3=substr($cons3,0,strlen($cons3)-2);
				$cons3=$cons3."  from salud.$fila1[0] Where compania = '$Compania')";
				$res3=ExQuery($cons3);
				echo "<br><br>".$cons3."<br><br>";
			}
		}
		//---infraestructura.gruposdeelementos
		$cons2="select column_name from information_schema.columns where table_schema='infraestructura' and table_name = 'gruposdeelementos' 
		Order By ordinal_position";
		$res2=ExQuery($cons2);
		$cons3="Insert into infraestructura.gruposdeelementos( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from infraestructura.gruposdeelementos Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";
		//---predial.destinaciones
		$cons2="select column_name from information_schema.columns where table_schema='predial' and table_name = 'destinaciones' 
		Order By ordinal_position";
		$res2=ExQuery($cons2);
		$cons3="Insert into predial.destinaciones( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from predial.destinaciones Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";
		//---predial.estratos
		$cons2="select column_name from information_schema.columns where table_schema='predial' and table_name = 'estratos' 
		Order By ordinal_position";
		$res2=ExQuery($cons2);
		$cons3="Insert into predial.estratos( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from predial.estratos Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";
		//---predial.sectores
		$cons2="select column_name from information_schema.columns where table_schema='predial' and table_name = 'sectores' 
		Order By ordinal_position";
		$res2=ExQuery($cons2);
		$cons3="Insert into predial.sectores( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from predial.sectores Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";
		//---predial.zonas
		$cons2="select column_name from information_schema.columns where table_schema='predial' and table_name = 'zonas' 
		Order By ordinal_position";
		$res2=ExQuery($cons2);
		$cons3="Insert into predial.zonas( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from predial.zonas Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";
		//---predial.predios
		$cons2="select column_name from information_schema.columns where table_schema='predial' and table_name = 'predios' 
		Order By ordinal_position";
		$res2=ExQuery($cons2);
		$cons3="Insert into predial.predios( Select ";
		while($fila2=ExFetch($res2))
		{
			if($fila2[0]=="compania")
			{
				$cons3=$cons3."'$NuevaCompania', ";
			}
			else
			{
				$cons3=$cons3."$fila2[0], ";
			}									
		}
		$cons3=substr($cons3,0,strlen($cons3)-2);
		$cons3=$cons3."  from predial.predios Where compania = '$Compania')";
		$res3=ExQuery($cons3);
		echo "<br><br>".$cons3."<br><br>";		
		//-- Los demas esquemas
		$cons="Select table_schema FROM information_schema.columns
		where table_schema!='information_schema' and table_schema!='pg_catalog' and table_schema!='contabilidad' and table_schema!='consumo'
		and table_schema!='contratacionsalud' and table_schema!='salud' and table_schema!='central' and table_schema!='presupuesto' 
		and table_schema!='histoclinicafrms'
		Group By table_schema Order By table_schema";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			echo "<br>".$fila[0];			
			$cons1="Select table_name FROM information_schema.columns where table_schema='$fila[0]' group by table_name Order By table_name ;";
			$res1=ExQuery($cons1);
			while($fila1=ExFetch($res1))
			{
				if(($fila[0]=="infraestructura"&&$fila1[0]=="gruposdeelementos")||($fila[0]=="predial"&&$fila1[0]=="predios")||($fila[0]=="predial"&&$fila1[0]=="destinaciones")||($fila[0]=="predial"&&$fila1[0]=="sectores")||($fila[0]=="predial"&&$fila1[0]=="zonas")||($fila[0]=="estratos"&&$fila1[0]=="estratos")){break;}
				echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$fila1[0];	
				$cons2="select column_name from information_schema.columns where table_schema='$fila[0]' and table_name = '$fila1[0]' 
				and column_name='compania' Order By ordinal_position";			
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)>0)
				{
					$cons2="select column_name from information_schema.columns where table_schema='$fila[0]' and table_name = '$fila1[0]' 
					Order By ordinal_position";			
					$res2=ExQuery($cons2);
					$cons3="Insert into $fila[0].$fila1[0]( Select ";
					while($fila2=ExFetch($res2))
					{
						if($fila2[0]=="compania")
						{
							$cons3=$cons3."'$NuevaCompania', ";
						}
						else
						{
							$cons3=$cons3."$fila2[0], ";	
						}						
						/*$cons3="SELECT table_name,table_schema,constraint_name FROM information_schema.table_constraints
						where constraint_type='FOREIGN KEY' and table_schema='$fila[0]' and table_name='$fila1[0]'";
						$res3=ExQuery($cons3);
						while($fila3=ExFetch($res3))
						{
							echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$fila3[2];	
							$FKLocales[$fila4[0]][$fila4[1]][$fila4[2]]=array($fila4[0],$fila4[1],$fila4[2]);
						}*/
						
						//echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$fila2[0];
						/*$conscambio="update $fila[0].$fila1[0] set compania='$NuevaCompania' where compania='$Compania'";
						echo "<br><br>".$conscambio."<br><br>";
						$rescambio=ExQuery($conscambio);
						if(ExError($rescambio)){echo ExError($rescambio);}*/
					}
					$cons3=substr($cons3,0,strlen($cons3)-2);
					$cons3=$cons3."  from $fila[0].$fila1[0] Where compania = '$Compania')";
					$res3=ExQuery($cons3);
					echo "<br><br>".$cons3."<br><br>";
				}
			}
		}
	}	
?>
<body>
<form name="FORMA" method="post" onSubmit="if(document.FORMA.Compania.value==''||document.FORMA.NuevaCompania.value==''){alert('Debe seleccionar y llenar todos los campos!!!');return false;}" >
<?
if(!$Compania)
{?>
	<b>Seleccione Compania:</b>
    <select name="Compania">
	<option value=""></option>
	<?
	$cons="Select nombre from central.compania order by nombre";
	$res=ExQuery($cons);
	
	while($fila=ExFetch($res))
	{	
		if($fila[0]==$Compania)
		{
			?>
			<option value="<? echo $fila[0]?>" selected><? echo $fila[0]?></option>
			<?
		}
		else
		{?>
			<option value="<? echo $fila[0]?>"><? echo $fila[0]?></option>
		<?
		}	
	}
	?>
    </select>		
    <b>Reemplazar por:</b>
    <input type="text" name="NuevaCompania" style="width:300px"  />
    <br />
    <input type="submit" name="Reemplazar" value="Reemplazar Compania" />
	<?
}
?>
</form>
</body>