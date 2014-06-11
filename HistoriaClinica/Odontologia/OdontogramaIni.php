<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	$contReplace=1;
	include("Funciones.php");
	include("FuncionesUnload.php");
	$raiz=$_SERVER['DOCUMENT_ROOT'];	
	@require_once ("$raiz/xajax/xajax_core/xajax.inc.php");	
	$obj = new xajax(); 
	$obj->registerFunction("Borrar_Temporales");
	$obj->registerFunction("Quitar_TransaccionTmp");
	$obj->processRequest(); 
	$ND=getdate();
	$Anio=$ND[year];
	if($ND[mon]<10){$Mes="0".$ND[mon];}else{$Mes=$ND[mon];}	
	if($ND[mday]<10){$Dia="0".$ND[mday];}else{$Dia=$ND[mday];}	
	$HoraHoy="$ND[hours]:$ND[minutes]:$ND[seconds]";
	if($Guardar)
	{
		$Guardar="";$Nuevo="";
		$cons="Select identificacion, cuadrante, diente, zonad, procedimiento, fecha, tipoodonto, denticion, imagenzona, imagenproc, 
		transacciontmp,cup,Eliminar, Diagnostico1, Diagnostico2, Diagnostico3, Diagnostico4, Diagnostico5,fechaant
		from odontologia.procedimientosimgs, odontologia.tmpodontogramaproc where
		ProcedimientosImgs.Compania='$Compania[0]' and TmpOdontogramaProc.Compania='$Compania[0]' and 
		ProcedimientosImgs.Codigo=TmpOdontogramaProc.Procedimiento and TmpCod='$TMPCOD' and 
		Identificacion='$Paciente[1]' and Fecha='$Fecha' order by cuadrante,diente,zonad,procedimiento";  		
	  $res=ExQuery($cons);
		if(ExNumRows($res)>0)
		{
			while($fila=ExFetch($res))
			{						
				$cons1="Select ZonaD,procedimiento, fecha, tipoodonto, denticion, imagenzona
				from Odontologia.OdontogramaProc where Compania='$Compania[0]' and Identificacion='$fila[0]' 
				and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]' and Procedimiento=$fila[4] and Fecha='$fila[5]' 
				and TransaccionTmp='1'";
				//echo $cons1;
				$res1=ExQuery($cons1);
				if(ExNumRows($res1)==0)
				{				
					if(!$fila[12])
					{				
						$cons2="Insert Into Odontologia.odontogramaproc (Compania,Identificacion,Cuadrante,Diente,ZonaD,
						Procedimiento,Fecha,TipoOdonto,Denticion,imagenzona,ImagenProc,Cup,Medico,NumServicio,Diagnostico1,Diagnostico2,Diagnostico3,
						Diagnostico4,Diagnostico5,fechaant)
						values('$Compania[0]','$fila[0]','$fila[1]',
						'$fila[2]','$fila[3]',$fila[4],'$fila[5]','$fila[6]','$fila[7]','$fila[8]','$fila[9]','$fila[11]','$usuario[1]',$Servicio,'$fila[13]','$fila[14]',
						'$fila[15]','$fila[16]','$fila[17]','$fila[18]')";
						$res2=ExQuery($cons2);
					}
					$cons2="Delete from Odontologia.Tmpodontogramaproc where Compania='$Compania[0]' and TMPCOD='$TMPCOD' 
					and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]' 
					and Procedimiento=$fila[4] and Fecha='$fila[5]'";
					$res2=ExQuery($cons2);
				}
				else
				{
					if($fila[13]){$upddiag1=", Diagnostico1='$fila[13]'";}
					if($fila[14]){$upddiag2=", Diagnostico2='$fila[14]'";}
					if($fila[15]){$upddiag3=", Diagnostico3='$fila[15]'";}
					if($fila[16]){$upddiag4=", Diagnostico4='$fila[16]'";}
					if($fila[17]){$upddiag5=", Diagnostico5='$fila[17]'";}
					$cons2="Update Odontologia.OdontogramaProc set ImagenZona='$fila[8]', ImagenProc='$fila[9]', TransaccionTmp=NULL, 
					medico='$usuario[1]', NumServicio=$Servicio $upddiag1 $upddiag2 $upddiag3 $upddiag4 $upddiag5, fechaant='$fila[18]' where Compania='$Compania[0]'
					 and Identificacion='$fila[0]' and Cuadrante='$fila[1]' 
					and Diente='$fila[2]' and ZonaD='$fila[3]' and Procedimiento=$fila[4]	and Fecha='$fila[5]'";
					$res2=ExQuery($cons2);
					$cons2="Delete from Odontologia.Tmpodontogramaproc where Compania='$Compania[0]' and TMPCOD='$TMPCOD' 
					and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]' 
					and Procedimiento=$fila[4] and Fecha='$fila[5]'";
					$res2=ExQuery($cons2);
				}			
			}
			//---
			$cons="Select identificacion, cuadrante, diente, zonad, procedimiento, fecha, tipoodonto, denticion, imagenzona, imagenproc, 
			transacciontmp,Eliminar, Diagnostico1, Diagnostico2, Diagnostico3, Diagnostico4, Diagnostico5,fechaant
			from odontologia.tmpodontogramaproc where
			TmpOdontogramaProc.Compania='$Compania[0]' and TmpOdontogramaProc.Procedimiento=-1 and TmpCod='$TMPCOD' and 
			Identificacion='$Paciente[1]' and Fecha='$Fecha' order by cuadrante,diente,zonad,procedimiento";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{						
				$cons1="Select ZonaD,procedimiento, fecha, tipoodonto, denticion, imagenzona
				from Odontologia.OdontogramaProc where Compania='$Compania[0]' and Identificacion='$fila[0]' 
				and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]' and Procedimiento=$fila[4] and Fecha='$fila[5]' 
				and TransaccionTmp='1'";
				//echo $cons1;
				$res1=ExQuery($cons1);
				if(ExNumRows($res1)==0)
				{				
					if(!$fila[11])
					{				
						$cons2="Insert Into Odontologia.odontogramaproc (Compania,Identificacion,Cuadrante,Diente,ZonaD,
						Procedimiento,Fecha,TipoOdonto,Denticion,imagenzona,ImagenProc,Cup,Medico,NumServicio,Diagnostico1,Diagnostico2,Diagnostico3,
						Diagnostico4,Diagnostico5,fechaant)
						values('$Compania[0]','$fila[0]','$fila[1]',
						'$fila[2]','$fila[3]',$fila[4],'$fila[5]','$fila[6]','$fila[7]','$fila[8]','$fila[9]','','$usuario[1]',$Servicio,'$fila[12]','$fila[13]',
						'$fila[14]','$fila[15]','$fila[16]','$fila[17]')";
						$res2=ExQuery($cons2);
					}
					$cons2="Delete from Odontologia.Tmpodontogramaproc where Compania='$Compania[0]' and TMPCOD='$TMPCOD' 
					and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]' 
					and Procedimiento=$fila[4] and Fecha='$fila[5]'";
					$res2=ExQuery($cons2);
				}
				else
				{
					if($fila[12]){$upddiag1=", Diagnostico1='$fila[12]'";}
					if($fila[13]){$upddiag2=", Diagnostico2='$fila[13]'";}
					if($fila[14]){$upddiag3=", Diagnostico3='$fila[14]'";}
					if($fila[15]){$upddiag4=", Diagnostico4='$fila[15]'";}
					if($fila[16]){$upddiag5=", Diagnostico5='$fila[16]'";}
					$cons2="Update Odontologia.OdontogramaProc set ImagenZona='$fila[8]', ImagenProc='$fila[9]', TransaccionTmp=NULL, 
					medico='$usuario[1]', NumServicio=$Servicio $upddiag1 $upddiag2 $upddiag3 $upddiag4 $upddiag5, fechaant='$fila[17]' where Compania='$Compania[0]' and Identificacion='$fila[0]' and Cuadrante='$fila[1]' 
					and Diente='$fila[2]' and ZonaD='$fila[3]' and Procedimiento=$fila[4]	and Fecha='$fila[5]'";
					$res2=ExQuery($cons2);
					$cons2="Delete from Odontologia.Tmpodontogramaproc where Compania='$Compania[0]' and TMPCOD='$TMPCOD' 
					and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]' 
					and Procedimiento=$fila[4] and Fecha='$fila[5]'";
					$res2=ExQuery($cons2);
				}			
			}
		}
		else
		{
			$cons="Select identificacion, cuadrante, diente, zonad, procedimiento, fecha, tipoodonto, denticion, imagenzona, imagenproc, 
			transacciontmp,Eliminar, Diagnostico1, Diagnostico2, Diagnostico3, Diagnostico4, Diagnostico5,fechaant
			from odontologia.tmpodontogramaproc where
			TmpOdontogramaProc.Compania='$Compania[0]' and TmpOdontogramaProc.Procedimiento=-1 and TmpCod='$TMPCOD' and 
			Identificacion='$Paciente[1]' and Fecha='$Fecha' order by cuadrante,diente,zonad,procedimiento";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{						
				$cons1="Select ZonaD,procedimiento, fecha, tipoodonto, denticion, imagenzona
				from Odontologia.OdontogramaProc where Compania='$Compania[0]' and Identificacion='$fila[0]' 
				and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]' and Procedimiento=$fila[4] and Fecha='$fila[5]' 
				and TransaccionTmp='1'";
				//echo $cons1;
				$res1=ExQuery($cons1);
				if(ExNumRows($res1)==0)
				{				
					if(!$fila[11])
					{				
						$cons2="Insert Into Odontologia.odontogramaproc (Compania,Identificacion,Cuadrante,Diente,ZonaD,
						Procedimiento,Fecha,TipoOdonto,Denticion,imagenzona,ImagenProc,Cup,Medico,NumServicio,Diagnostico1,Diagnostico2,Diagnostico3,
						Diagnostico4,Diagnostico5,fechaant)
						values('$Compania[0]','$fila[0]','$fila[1]',
						'$fila[2]','$fila[3]',$fila[4],'$fila[5]','$fila[6]','$fila[7]','$fila[8]','$fila[9]','','$usuario[1]',$Servicio,'$fila[12]','$fila[13]',
						'$fila[14]','$fila[15]','$fila[16]','$fila[17]')";
						$res2=ExQuery($cons2);
					}
					$cons2="Delete from Odontologia.Tmpodontogramaproc where Compania='$Compania[0]' and TMPCOD='$TMPCOD' 
					and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]' 
					and Procedimiento=$fila[4] and Fecha='$fila[5]'";
					$res2=ExQuery($cons2);
				}
				else
				{
					if($fila[12]){$upddiag1=", Diagnostico1='$fila[12]'";}
					if($fila[13]){$upddiag2=", Diagnostico2='$fila[13]'";}
					if($fila[14]){$upddiag3=", Diagnostico3='$fila[14]'";}
					if($fila[15]){$upddiag4=", Diagnostico4='$fila[15]'";}
					if($fila[16]){$upddiag5=", Diagnostico5='$fila[16]'";}
					$cons2="Update Odontologia.OdontogramaProc set ImagenZona='$fila[8]', ImagenProc='$fila[9]', TransaccionTmp=NULL, 
					medico='$usuario[1]', NumServicio=$Servicio $upddiag1 $upddiag2 $upddiag3 $upddiag4 $upddiag5, fechaant='$fila[17]' where Compania='$Compania[0]' and Identificacion='$fila[0]' and Cuadrante='$fila[1]' 
					and Diente='$fila[2]' and ZonaD='$fila[3]' and Procedimiento=$fila[4]	and Fecha='$fila[5]'";
					$res2=ExQuery($cons2);
					$cons2="Delete from Odontologia.Tmpodontogramaproc where Compania='$Compania[0]' and TMPCOD='$TMPCOD' 
					and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]' 
					and Procedimiento=$fila[4] and Fecha='$fila[5]'";
					$res2=ExQuery($cons2);
				}			
			}	
		}
		$cons1="Delete from Odontologia.OdontogramaProc where Compania='$Compania[0]' and Identificacion='$Paciente[1]' 
		and Fecha='$Fecha' and Eliminar IS NOT NULL";
		$res1=ExQuery($cons1);
		?><script language="javascript">parent.Modifico=false;parent.Trabajando=false;</script><?
	}	
	//echo $TipoOdontograma;
	if($TipoOdontograma=="Inicial")
	{
		$consini="and TipoOdonto='Inicial'";
		$DisaN="disabled";
		$DisaG="disabled";
		$cons="Select fecha from odontologia.odontogramaproc where Compania='$Compania[0]' and Identificacion='$Paciente[1]' order by fecha asc 
		limit 1";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$FechaOdontograma=$fila[0];	
		if($FechaOdontograma){$consultaoi="and Fecha='$FechaOdontograma'";}	
		//$ffff=$ND[year]."-".$Mes."-".$Dia;
		//if(substr($FechaOdontograma,0,4)==$ND[year]&&substr($FechaOdontograma,5,2)==$Mes&&substr($FechaOdontograma,8,2)==$Dia)
		if(number_format(substr($FechaOdontograma,0,4),0)==number_format($ND[year],0)&&number_format(substr($FechaOdontograma,5,2),0)==number_format($Mes,0)&&number_format(substr($FechaOdontograma,8,2),0)==number_format($Dia,0))
		{
			$FO_FA=1;	//echo "epa";
		}
		else
		{
			$FO_FA=""; //echo "sueter";
		}		
	}
	else
	{		
		$consini="";
		$DisaN="";
		$DisaG="disabled";
		$cons="Select fecha from odontologia.odontogramaproc where Compania='$Compania[0]' and Identificacion='$Paciente[1]' order by fecha desc 
		limit 1";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		if(!$FechaOdontograma)$FechaOdontograma=$fila[0];	
		if(!$Fecha)$Fecha=$FechaOdontograma;
		//echo $FechaOdontograma;
		if(number_format(substr($FechaOdontograma,0,4),0)==number_format($ND[year],0)&&number_format(substr($FechaOdontograma,5,2),0)==number_format($Mes,0)&&number_format(substr($FechaOdontograma,8,2),0)==number_format($Dia,0))
		{
			$FO_FA=1;	//echo "epa";
		}
		else
		{
			$FO_FA=""; //echo "sueter";
		}			
	}	

	//echo $FechaOdontograma."-> ".$FO_FA;	
	if($TipoOdontograma=="Inicial")
	{		
		if(!empty($FechaOdontograma)){$MatFechas[$FechaOdontograma]=$FechaOdontograma;}
	}
	else
	{	
		$cons="Select Fecha from odontologia.odontogramaproc where Compania='$Compania[0]' and Identificacion='$Paciente[1]'	 
		order by fecha";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$MatFechas[$fila[0]]=$fila[0];
			$ultfecha=$fila[0];
		}
	}
	if(number_format(substr($Fecha,0,4),0)==number_format($ND[year],0)&&number_format(substr($Fecha,5,2),0)==number_format($Mes,0)&&number_format(substr($Fecha,8,2),0)==number_format($Dia,0)&&!$NuevaFecha)
	{		
		$MatFechas[$Fecha]=$Fecha;	//echo "epa";
		$NuevaFecha=$Fecha;
		$FechaOdontograma=$Fecha;					
	}
	if($Nuevo)
	{
		$FechaOdontograma=$NuevaFecha;
		$MatFechas[$FechaOdontograma]=$FechaOdontograma;
		$Fecha=$NuevaFecha;		
		$DisaN="disabled";
		$DisaG="disabled";
		$cons="Select identificacion, cuadrante, diente, zonad, procedimiento, fecha, tipoodonto, denticion, imagenzona, imagenproc,fechaant
		from odontologia.odontogramaproc where Compania='$Compania[0]' and Identificacion='$Paciente[1]' and Fecha='$ultfecha' 
		order by fechaant,fecha, cuadrante, diente, zonad";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($fila[10]){$FAnt=",'$fila[10]'";}else{$FAnt=",'$fila[5] $HoraHoy'";}
			$cons1="Insert Into odontologia.Tmpodontogramaproc (Compania, TmpCod, identificacion, cuadrante, diente, zonad, 
			procedimiento, fecha, tipoodonto, denticion, imagenzona, imagenproc,fechaant) values('$Compania[0]','$TMPCOD','$fila[0]',
			'$fila[1]','$fila[2]','$fila[3]',$fila[4],'$Fecha','$TipoOdontograma','$fila[7]','$fila[8]','$fila[9]' $FAnt)";
			$res1=ExQuery($cons1);	
		}
		?><script language="javascript">parent.Modifico=true;//alert(parent.Modifico);</script><?			
	}
	//if(!$FechaOdontograma||$FO_FA==1)
	//{		
		$cons="Select identificacion, cuadrante, diente, zonad, procedimiento, fecha, tipoodonto, denticion, imagenzona, imagenproc,fechaant
		from odontologia.odontogramaproc where Compania='$Compania[0]' and Identificacion='$Paciente[1]' $consini and TransaccionTmp is NULL 
		and Eliminar is NULL $consultaoi order by fechaant,fecha, cuadrante, diente, zonad";
		$res=ExQuery($cons);
		//echo $cons;
		while($fila=ExFetch($res))
		{			
			if($fila[1]=="1")
			{				
				$TmpMatCuadrante1[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
				if($fila[10]){$FAnt=",'$fila[10]'";}else{$FAnt=",'$fila[5] $HoraHoy'";}
				$cons1="Insert Into odontologia.Tmpodontogramaproc (Compania, TmpCod, identificacion, cuadrante, diente, zonad, 
				procedimiento, fecha, tipoodonto, denticion, imagenzona, imagenproc,TransaccionTmp,fechaant) values('$Compania[0]','$TMPCOD','$fila[0]',
				'$fila[1]','$fila[2]','$fila[3]',$fila[4],'$fila[5]','$fila[6]','$fila[7]','$fila[8]','$fila[9]','1' $FAnt)";
				$res1=ExQuery($cons1);
				$cons1="Update Odontologia.OdontogramaProc set TransaccionTmp='1' where Compania='$Compania[0]' 
				and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]' 
				and Procedimiento=$fila[4] and Fecha='$fila[5]'";
				$res1=ExQuery($cons1);
				
			}
			else
			{
				if($fila[1]=="2")
				{
					$TmpMatCuadrante2[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
				if($fila[10]){$FAnt=",'$fila[10]'";}else{$FAnt=",'$fila[5] $HoraHoy'";}
					$cons1="Insert Into odontologia.Tmpodontogramaproc (Compania, TmpCod, identificacion, cuadrante, diente, zonad, 
					procedimiento, fecha, tipoodonto, denticion, imagenzona, imagenproc,TransaccionTmp,fechaant) 
					values('$Compania[0]','$TMPCOD','$fila[0]',
					'$fila[1]','$fila[2]','$fila[3]',$fila[4],'$fila[5]','$fila[6]','$fila[7]','$fila[8]','$fila[9]','1' $FAnt)";
					$res1=ExQuery($cons1);
					$cons1="Update Odontologia.OdontogramaProc set TransaccionTmp='1' where Compania='$Compania[0]' 
					and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]' 
					and Procedimiento=$fila[4] and Fecha='$fila[5]'";
					$res1=ExQuery($cons1);
				}
				else
				{
					if($fila[1]=="3")
					{
						$TmpMatCuadrante3[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
				if($fila[10]){$FAnt=",'$fila[10]'";}else{$FAnt=",'$fila[5] $HoraHoy'";}
						$cons1="Insert Into odontologia.Tmpodontogramaproc (Compania, TmpCod, identificacion, cuadrante, diente, zonad, 
						procedimiento, fecha, tipoodonto, denticion, imagenzona, imagenproc,TransaccionTmp,fechaant) 
						values('$Compania[0]','$TMPCOD','$fila[0]',
						'$fila[1]','$fila[2]','$fila[3]',$fila[4],'$fila[5]','$fila[6]','$fila[7]','$fila[8]','$fila[9]','1' $FAnt)";
						$res1=ExQuery($cons1);
						$cons1="Update Odontologia.OdontogramaProc set TransaccionTmp='1' where Compania='$Compania[0]' 
						and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]' 
						and Procedimiento=$fila[4] and Fecha='$fila[5]'";
						$res1=ExQuery($cons1);
					}	
					else
					{
						if($fila[1]=="4")
						{							
							$TmpMatCuadrante4[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
				if($fila[10]){$FAnt=",'$fila[10]'";}else{$FAnt=",'$fila[5] $HoraHoy'";}
							$cons1="Insert Into odontologia.Tmpodontogramaproc (Compania, TmpCod, identificacion, cuadrante, diente, zonad, 
							procedimiento, fecha, tipoodonto, denticion, imagenzona, imagenproc,TransaccionTmp,fechaant) 
							values('$Compania[0]','$TMPCOD','$fila[0]',
							'$fila[1]','$fila[2]','$fila[3]',$fila[4],'$fila[5]','$fila[6]','$fila[7]','$fila[8]','$fila[9]','1' $FAnt)";
							$res1=ExQuery($cons1);
							$cons1="Update Odontologia.OdontogramaProc set TransaccionTmp='1' where Compania='$Compania[0]' 
							and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]' 
							and Procedimiento=$fila[4] and Fecha='$fila[5]'";
							$res1=ExQuery($cons1);
						}
						else
						{
							if($fila[1]=="5")
							{								
								$TmpMatCuadrante5[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
				if($fila[10]){$FAnt=",'$fila[10]'";}else{$FAnt=",'$fila[5] $HoraHoy'";}
								$cons1="Insert Into odontologia.Tmpodontogramaproc (Compania, TmpCod, identificacion, cuadrante, diente, zonad, 
								procedimiento, fecha, tipoodonto, denticion, imagenzona, imagenproc,TransaccionTmp,fechaant) 
								values('$Compania[0]','$TMPCOD','$fila[0]',
								'$fila[1]','$fila[2]','$fila[3]',$fila[4],'$fila[5]','$fila[6]','$fila[7]','$fila[8]','$fila[9]','1' $FAnt)";
								$res1=ExQuery($cons1);
								$cons1="Update Odontologia.OdontogramaProc set TransaccionTmp='1' where Compania='$Compania[0]' 
								and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]' 
								and Procedimiento=$fila[4] and Fecha='$fila[5]'";
								$res1=ExQuery($cons1);
							}	
							else
							{
								if($fila[1]=="6")
								{									
									$TmpMatCuadrante6[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
				if($fila[10]){$FAnt=",'$fila[10]'";}else{$FAnt=",'$fila[5] $HoraHoy'";}
									$cons1="Insert Into odontologia.Tmpodontogramaproc (Compania, TmpCod, identificacion, cuadrante, 
									diente, zonad, procedimiento, fecha, tipoodonto, denticion, imagenzona, imagenproc,TransaccionTmp,fechaant) 	
									values('$Compania[0]','$TMPCOD','$fila[0]',
									'$fila[1]','$fila[2]','$fila[3]',$fila[4],'$fila[5]','$fila[6]','$fila[7]','$fila[8]','$fila[9]','1' $FAnt)";
									$res1=ExQuery($cons1);
									$cons1="Update Odontologia.OdontogramaProc set TransaccionTmp='1' where Compania='$Compania[0]' 
									and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]' 
									and Procedimiento=$fila[4] and Fecha='$fila[5]'";
									$res1=ExQuery($cons1);
								}	
								else
								{
									if($fila[1]=="7")
									{										
										$TmpMatCuadrante7[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
				if($fila[10]){$FAnt=",'$fila[10]'";}else{$FAnt=",'$fila[5] $HoraHoy'";}
										$cons1="Insert Into odontologia.Tmpodontogramaproc (Compania, TmpCod, identificacion, cuadrante, 
										diente, zonad, procedimiento, fecha, tipoodonto, denticion, imagenzona, imagenproc,TransaccionTmp,fechaant) 	
										values('$Compania[0]','$TMPCOD','$fila[0]',
										'$fila[1]','$fila[2]','$fila[3]',$fila[4],'$fila[5]','$fila[6]','$fila[7]','$fila[8]','$fila[9]','1' $FAnt)";
										$res1=ExQuery($cons1);
										$cons1="Update Odontologia.OdontogramaProc set TransaccionTmp='1' where Compania='$Compania[0]' 
										and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]' 
										and Procedimiento=$fila[4] and Fecha='$fila[5]'";
										$res1=ExQuery($cons1);
									}	
									else
									{
										if($fila[1]=="8")
										{											
											$TmpMatCuadrante8[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
				if($fila[10]){$FAnt=",'$fila[10]'";}else{$FAnt=",'$fila[5] $HoraHoy'";}
											$cons1="Insert Into odontologia.Tmpodontogramaproc (Compania, TmpCod, identificacion, cuadrante, 
											diente, zonad, procedimiento, fecha, tipoodonto, denticion, imagenzona, imagenproc,TransaccionTmp,fechaant) 	
											values('$Compania[0]','$TMPCOD','$fila[0]',
										   '$fila[1]','$fila[2]','$fila[3]',$fila[4],'$fila[5]','$fila[6]','$fila[7]','$fila[8]','$fila[9]',
										   '1' $FAnt)";
										    $res1=ExQuery($cons1);
											$cons1="Update Odontologia.OdontogramaProc set TransaccionTmp='1' where Compania='$Compania[0]' 
											and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]' 
											and Procedimiento=$fila[4] and Fecha='$fila[5]'";
											$res1=ExQuery($cons1);
										}
									}
								}
								
							}
						}	
					}
				}		
			}
		}	
	//}		
	$cons="Select identificacion, cuadrante, diente, zonad, procedimiento, fecha, tipoodonto, denticion, imagenzona, imagenproc,TransaccionTmp,fechaant
	from odontologia.tmpodontogramaproc where Compania='$Compania[0]' and TmpCod='$TMPCOD' and Identificacion='$Paciente[1]' 
	$consultaoi $consini and Eliminar is NULL order by fechaant,fecha, cuadrante, 	diente, zonad";
	//echo $cons;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		//if($fila[5]!=""){$TmpMatFechas[$fila[5]]=$fila[5];}
		if($fila[1]=="1")
		{			
			if(!$fila[10]){$DisaG="";}
			$TmpMatCuadrante1[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
			$TmpMatCuadrante1Proc[$fila[5]][$fila[2]][$fila[3]][$fila[4]]=$fila[9];
		}
		else
		{
			if($fila[1]=="2")
			{				
				if(!$fila[10]){$DisaG="";}
				$TmpMatCuadrante2[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
				$TmpMatCuadrante2Proc[$fila[5]][$fila[2]][$fila[3]][$fila[4]]=$fila[9];
			}
			else
			{
				if($fila[1]=="3")
				{					
					if(!$fila[10]){$DisaG="";}
					$TmpMatCuadrante3[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
					$TmpMatCuadrante3Proc[$fila[5]][$fila[2]][$fila[3]][$fila[4]]=$fila[9];
				}	
				else
				{
					if($fila[1]=="4")
					{						
						if(!$fila[10]){$DisaG="";}
						$TmpMatCuadrante4[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
						$TmpMatCuadrante4Proc[$fila[5]][$fila[2]][$fila[3]][$fila[4]]=$fila[9];
					}
					else
					{
						if($fila[1]=="5")
						{							
							if(!$fila[10]){$DisaG="";}
							$TmpMatCuadrante5[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
							$TmpMatCuadrante5Proc[$fila[5]][$fila[2]][$fila[3]][$fila[4]]=$fila[9];
						}	
						else
						{
							if($fila[1]=="6")
							{								
								if(!$fila[10]){$DisaG="";}
								$TmpMatCuadrante6[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
								$TmpMatCuadrante6Proc[$fila[5]][$fila[2]][$fila[3]][$fila[4]]=$fila[9];
							}	
							else
							{
								if($fila[1]=="7")
								{									
									if(!$fila[10]){$DisaG="";}
									$TmpMatCuadrante7[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
									$TmpMatCuadrante7Proc[$fila[5]][$fila[2]][$fila[3]][$fila[4]]=$fila[9];
								}	
								else
								{
									if($fila[1]=="8")
									{										
										if(!$fila[10]){$DisaG="";}
										$TmpMatCuadrante8[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
										$TmpMatCuadrante8Proc[$fila[5]][$fila[2]][$fila[3]][$fila[4]]=$fila[9];
									}
								}
							}							
						}
					}	
				}
			}		
		}	
	}	
	$cons="Select identificacion, cuadrante, diente, zonad, procedimiento, fecha, tipoodonto, denticion, imagenzona, imagenproc,TransaccionTmp,fechaant
	from odontologia.tmpodontogramaproc where Compania='$Compania[0]' and TmpCod='$TMPCOD' and Identificacion='$Paciente[1]' 
	$consultaoi and Eliminar is not NULL";	
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){$DisaG="";?><script language="javascript">parent.Modifico=true;</script><? }
	if(!empty($MatFechas))
	{	
		foreach($MatFechas as $fff)
		{
			if(number_format(substr($fff,0,4),0)==number_format($ND[year],0)&&number_format(substr($fff,5,2),0)==number_format($Mes,0)&&number_format(substr($fff,8,2),0)==number_format($Dia,0))
			{
				$DisaN="Disabled";
				break;
			}
		}
	}
	if($Fecha!=$ND[year]."-".$Mes."-".$Dia){$DisaG="Disabled";}			
?>		
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? $obj->printJavascript("/xajax");?>

<script language='javascript' src="/Funciones.js"></script>
<script type="text/javascript">
	//alert("Trabajando "+parent.Trabajando+" Modifico "+parent.Modifico);  	
	function MensajeAlerta()
	{
		window.onbeforeunload = confirmExit; 	   	
	}
	function confirmExit() 
	{ 
		return "Usted desea salir de la pagina. Si ha realizado algun cambio y desea guardarlo pulse en Cancelar y presione en el boton guardar, de lo contrario se perderan los cambios.";    
	}
//--
	function raton(e) 
	{ 
		x = e.clientX; 
		y = e.clientY; 	
		frames.FrameOpener.location.href="Diente_P.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Fecha="+document.FORMA.Fecha.value;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=y;
		document.getElementById('FrameOpener').style.left=x;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='400';
		document.getElementById('FrameOpener').style.height='500';
	}
	function AbrirVentana(Diente)
	{
		//parent.document.getElementById('Info').disabled=true;
		parent.frames.FrameOpener.location.href="Diente_P.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Fecha="+document.FORMA.Fecha.value+"&Diente="+Diente+"&TipoOdontograma=<? echo $TipoOdontograma?>";
		parent.frames.FrameFondo.location.href="Framefondo.php";
				
		parent.document.getElementById('FrameFondo').style.position='absolute';
		parent.document.getElementById('FrameFondo').style.top='1px';
		parent.document.getElementById('FrameFondo').style.left='1px';
		parent.document.getElementById('FrameFondo').style.display='';
		parent.document.getElementById('FrameFondo').style.width='100%';
		parent.document.getElementById('FrameFondo').style.height='95%';		
		//--
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='5%';
		parent.document.getElementById('FrameOpener').style.left=document.getElementById('TABLA').clientWidth/3.3;
		parent.document.getElementById('FrameOpener').style.display='';
		parent.document.getElementById('FrameOpener').style.width='420';
		parent.document.getElementById('FrameOpener').style.height='510';		
	}
	function tamtabla()
	{
		//alert("ancho -> "+document.getElementById('TABLA').style.width+" alto -> "+document.getElementById('TABLA').style.height);
		alert("ancho -> "+document.getElementById('TABLA').clientWidth+" alto -> "+document.getElementById('TABLA').clientHeight);
		
	}	
	function CreaEtiquetaH(Nombre,Tipo)
	{		
		AnchoVentana=parseInt(document.body.clientWidth);		
		AnchoTabla=parseInt(document.getElementById('TABLA').clientWidth);
		ValInc=0;
		if(AnchoVentana>AnchoTabla+30)
		{
			ValInc=(AnchoVentana-AnchoTabla)/2;
		}
		Alto=0;Ancho=0;		
		if(Tipo==2)
		{			
			Alto=parseInt((document.getElementById('TABLA').clientHeight+50)/2);
			Ancho=parseInt((document.getElementById('TABLA').clientWidth)/3)+ValInc;
			document.getElementById('Etiq2').style.position='absolute';
			document.getElementById('Etiq2').style.top=Alto;
			document.getElementById('Etiq2').style.left=Ancho;
			document.getElementById('Etiq2').style.display='';
			document.getElementById('Etiq2').style.width='10px';		
			document.FORMA.Etiq2.value=Nombre;			
		}
		if(Tipo==4)
		{			
			Alto=parseInt((document.getElementById('TABLA').clientHeight+50)/2);
			Ancho=parseInt((document.getElementById('TABLA').clientWidth)/1.7)+ValInc;			
			document.getElementById('Etiq4').style.position='absolute';
			document.getElementById('Etiq4').style.top=Alto;
			document.getElementById('Etiq4').style.left=Ancho;
			document.getElementById('Etiq4').style.display='';
			document.getElementById('Etiq4').style.width='10px';		
			document.FORMA.Etiq4.value=Nombre;			
		}
		if(Tipo==6)
		{			
			Alto=parseInt((document.getElementById('TABLA').clientHeight)/document.getElementById('TABLA').clientHeight)+20;
			Ancho=parseInt((document.getElementById('TABLA').clientWidth)/2.21)+ValInc;			
			document.getElementById('Etiq6').style.position='absolute';
			document.getElementById('Etiq6').style.top=Alto;
			document.getElementById('Etiq6').style.left=Ancho;
			document.getElementById('Etiq6').style.display='';
			document.getElementById('Etiq6').style.width='10px';		
			document.FORMA.Etiq6.value=Nombre;			
		}
		if(Tipo==7)
		{			
			Alto=parseInt((document.getElementById('TABLA').clientHeight)/1)+30;
			Ancho=parseInt((document.getElementById('TABLA').clientWidth)/2.21)+ValInc;			
			document.getElementById('Etiq7').style.position='absolute';
			document.getElementById('Etiq7').style.top=Alto;
			document.getElementById('Etiq7').style.left=Ancho;
			document.getElementById('Etiq7').style.display='';
			document.getElementById('Etiq7').style.width='10px';		
			document.FORMA.Etiq7.value=Nombre;			
		}				
		
	}	
	function DienteNo(ID)
	{
		alert(ID);	
	}
	function PoX(Tipo)
	{
		Ancho =0;
		switch(Tipo)
		{
			case 3: Ancho=(document.getElementById('TABLA').clientWidth/2)-100;	
					alert(Ancho);					
					break;
			default:
					break;
		}	
		return Ancho;
	}
	function PoY(Tipo)
	{
		Alto=0;
		switch(Tipo)		
		{
			case 3: Alto=(document.getElementById('TABLA').clientHeight/2)-50;	
					alert(Alto);					
					break;
			default:
					break;
		}	
		return Alto;
	}
function MensajeNuevoAlerta()
{	
	//alert(parent.Modifico);
	if(parent.Modifico)
	{
		if(confirm("Va a salir de la Pagina!!!. Si ha realizado algun cambio y desea guardarlo pulse en Cancelar y presione en el boton Guardar, de lo contrario se perderan los cambios.\nDesea Continuar?"))
		{			
			
			xajax_Borrar_Temporales('Odontologia.tmpodontogramaproc','<? echo $TMPCOD?>','Odontologia.odontogramaproc');
			//xajax_Quitar_TransaccionTmp('Odontologia.odontogramaproc');
			//CambiarSrcFecha(document.FORMA.Fecha.value);
			parent.Modifico=false;parent.Trabajando=false;
			//alert(document.FORMA.Fecha.value);					
			document.location.href="OdontogramaIni.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&TipoOdontograma=<? echo $TipoOdontograma?>&Fecha="+document.FORMA.Fecha.value+"&FechaOdontograma="+document.FORMA.Fecha.value;
		}
		else
		{
			document.FORMA.Fecha.value=FechaInfo;
			return false;
		}
	}
	else
	{
		//CambiarSrc(document.FORMA.Fecha.value);
		document.location.href="OdontogramaIni.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Fecha="+document.FORMA.Fecha.value+"&TipoOdontograma=<? echo $TipoOdontograma?>";
	}
}
function CamFechaInfo(valor)
{
	FechaInfo=valor;
	//alert(FechaInfo);
}
</script>
</head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../css/all.css" rel="stylesheet" type="text/css" />
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD;?>"/>
<input type="hidden" name="TipoOdontograma" value="<? echo $TipoOdontograma?>"/>
<input type="hidden" name="NuevaFecha" value="<? echo $NuevaFecha?>"/>
<input type="hidden" name="Guardar" value="<? echo $Guardar?>"/>
<input type="hidden" name="Nuevo" value="<? echo $Nuevo?>"/>
<input type="hidden" name="Servicio" value="<? echo $Servicio?>"/>
<?
if($FechaOdontograma!=""||$TipoOdontograma=="Inicial")
{	
?>
<b>Fecha:</b>
<select name="Fecha" title="Seleccione la fecha del Odontograma" onFocus="CamFechaInfo(this.value);" onChange="MensajeNuevoAlerta();">
<?
if(!empty($MatFechas))
{
	foreach($MatFechas as $Fechas)
	{	        
		if($Fecha==""||$Fechas==$Fecha)
		{	
			$Fecha=$Fechas;			
			?> <option selected value="<? echo $Fechas?>" <? if($NuevaFecha){?>style="color:#007100;font-weight:bold;"<? }?> ><? echo $Fechas ?></option><? }	
		else{?> <option value="<? echo $Fechas?>" ><? echo $Fechas; ?></option><? }
	}
}
else
{	
	$Fecha=$Anio."-".$Mes."-".$Dia;
	$NuevaFecha=$Anio."-".$Mes."-".$Dia;
	?><script language="javascript">document.FORMA.NuevaFecha.value="<? echo $NuevaFecha;?>";</script><?
	?> <option value="<? echo $Anio."-".$Mes."-".$Dia?>" selected o><? echo "$Anio-$Mes-$Dia"; ?></option><? 
}
?>              
</select> 
<?
if(!$DisaN){$CursorN=";cursor:hand;";}
if(!$DisaG){$CursorG=";cursor:hand;";}elseif(!$NuevaFecha){?><script language="javascript">parent.Modifico=false;</script><? }
//echo "<br>".$DisaG;
?>
<button name="NuevoO" style="position:absolute;top:14px; left:155px<? echo $CursorN?>" onClick="if(confirm('Se Crear&aacute; un Nuevo Odontograma con la Fecha de Hoy!!!')){document.FORMA.NuevaFecha.value='<? echo $ND[year]."-".$Mes."-".$Dia?>';document.FORMA.Nuevo.value=1;FORMA.submit();}" title="Crear Odontograma" <? echo $DisaN?> ><img src="/Imgs/Odontologia/ico-dental.png" style="width:16px; height:16px"/></button>
<button name="GuardarT" style="position:absolute;top:14px; left:180px<? echo $CursorG?>" onClick="if(confirm('¿Esta seguro de Guardar Todos los Cambios Realizados en el Odontograma?\nPulse Aceptar para Guardar Todo o Cancelar para continuar con el odontograma')){document.FORMA.Guardar.value=1;FORMA.submit();}" title="Guardar Todo" <? echo $DisaG?>><img src="/Imgs/Odontologia/file-save-icon.png" style="width:16px; height:16px"/></button>
<button name="Imprimir" style="position:absolute;top:14px; left:205px" <? if($DisaG){?> onClick="open('/HistoriaClinica/Odontologia/ImprimeOdontograma.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Paciente[1]?>&Fecha=<? echo $Fecha?>&TipoOdontograma=<? echo $TipoOdontograma?>','','width=1180,height=700,scrollbars=yes');"<? }else{ echo "Disabled";}?> title="Imprimir Odontograma" ><img src="/Imgs/b_print.png" style="width:16px; height:16px"/></button>
<button name="Convenciones" style="position:absolute;top:14px; left:230px" title="Ver Convenciones" onClick="open('/HistoriaClinica/Odontologia/Convenciones.php?DatNameSID=<? echo $DatNameSID?>','','width=600,height=500,scrollbars=yes');" ><img src="/Imgs/b_docs.png" style="width:16px; height:16px"/></button>
<table border="0"  bordercolor="#333333" style='font: normal normal small-caps 13px Tahoma;' align="center" id="TABLA"> 
<tr>
    <td>
        <table border="0" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;' align="center"> 	
            <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
                <td>18</td>
                <td>17</td><td>16</td><td>15</td><td>14</td><td>13</td><td>12</td><td>11</td>
            </tr>
            <tr>            
            <?
			if($TmpMatCuadrante1)
			{
				for($d=18;$d>10;$d--)
				{
					?><td id="<? echo "D".$d?>" style="cursor:hand" onClick="AbrirVentana(this.id);" onMouseOver="this.bgColor='#E4EDF8'" onMouseOut="this.bgColor=''">                        	
					<?							
					for($ii=65;$ii<=69;$ii++)
					{
						$Let=chr($ii);	
						if($TmpMatCuadrante1[$Fecha][$d][$Let][7])
						{
							$NameIMG=explode("/",$TmpMatCuadrante1[$Fecha][$d][$Let][7]);
							$xxi=count($NameIMG);
							$NameIMG[$xxi]="P".substr($NameIMG[$xxi-1],1,strlen($NameIMG[$xxi-1]));
							
							$TmpMatCuadrante1[$Fecha][$d][$Let][7]=str_replace($NameIMG[$xxi-1],$NameIMG[$xxi],$TmpMatCuadrante1[$Fecha][$d][$Let][7]);//aki							
							?><img src="<? echo $TmpMatCuadrante1[$Fecha][$d][$Let][7]?>" style="position:absolute;"  height="65" width="65"><?									
						}
						else
						{								
							if($Let=="A")
							{
								?><img src="/Imgs/Odontologia/P3.gif" style="position:absolute;" height="65" width="65"><?
							}
							else
							{									
								if($Let=="B")
								{
									?><img src="/Imgs/Odontologia/P1.gif" style="position:absolute;" height="65" width="65"><?
								}	
								else
								{										
									if($Let=="C")
									{
										?><img src="/Imgs/Odontologia/P4.gif" style="position:absolute;" height="65" width="65"><?
									}	
									else
									{											
										if($Let=="D")
										{
											?><img src="/Imgs/Odontologia/P2.gif" style="position:absolute;" height="65" width="65"><?	
										}	
										else
										{
											if($Let=="E")
											{
												?><img src="/Imgs/Odontologia/P5.gif" style="position:absolute;" height="65" width="65"><?
											}
										}
									}
								}
							}
						}												
					}
					for($ii=65;$ii<=69;$ii++)
					{
						$Let=chr($ii);
						if(!empty($TmpMatCuadrante1Proc[$Fecha][$d][$Let]))
						{
							foreach($TmpMatCuadrante1Proc[$Fecha][$d][$Let] as $ImgProc)
							{
								if($ImgProc!="")
								{
									?><img src="<? echo $ImgProc?>" style="position:absolute;" height="65" width="65"><?										
								}
							}
						}
					}
					?>  
					<img src="/Imgs/Odontologia/fondo.gif" height="65" width="65">                      						
					</td>
					<?
				}
			}
			else
			{
				for($d=18;$d>10;$d--)
				{
					?><td id="<? echo "D".$d?>" style="cursor:hand" onClick="AbrirVentana(this.id);" onMouseOver="this.bgColor='#E4EDF8'" onMouseOut="this.bgColor=''">						
						<img src="/Imgs/Odontologia/Diente_P.gif" style="position:absolute; " height="65" width="65">                   
						<img src="/Imgs/Odontologia/fondo.gif" height="65" width="65">
					</td>
					<?
				}
			}
			?>
            </tr>            
            <tr style='font: normal normal small-caps 11px Tahoma;' >
                <td></td><td></td><td></td>
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">55</td><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">54</td>
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">53</td><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">52</td>
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">51</td>                    
            </tr>            
            <tr align="center">
            <?
			if($TmpMatCuadrante5)
			{
				for($d=58;$d>50;$d--)
				{
					?><td <? if($d<56){?> id="<? echo "D".$d?>" style="cursor:hand" onClick="AbrirVentana(this.id);" onMouseOver="this.bgColor='#E4EDF8'" onMouseOut="this.bgColor=''"<? } ?>>
					<? if($d<56){							
					for($ii=65;$ii<=69;$ii++)
					{
						$Let=chr($ii);	
						if($TmpMatCuadrante5[$Fecha][$d][$Let][7])
						{
							$NameIMG=explode("/",$TmpMatCuadrante5[$Fecha][$d][$Let][7]);
							$xxi=count($NameIMG);
							$NameIMG[$xxi]="P".substr($NameIMG[$xxi-1],1,strlen($NameIMG[$xxi-1]));
							
							$TmpMatCuadrante5[$Fecha][$d][$Let][7]=str_replace($NameIMG[$xxi-1],$NameIMG[$xxi],$TmpMatCuadrante5[$Fecha][$d][$Let][7]);//aki							
							?><img src="<? echo $TmpMatCuadrante5[$Fecha][$d][$Let][7]?>" style="position:absolute;"  height="55" width="55"><?	
						}
						else
						{								
							if($Let=="A")
							{
								?><img src="/Imgs/Odontologia/P3.gif" style="position:absolute;" height="55" width="55"><?
							}
							else
							{									
								if($Let=="B")
								{
									?><img src="/Imgs/Odontologia/P1.gif" style="position:absolute;" height="55" width="55"><?
								}	
								else
								{										
									if($Let=="C")
									{
										?><img src="/Imgs/Odontologia/P4.gif" style="position:absolute;" height="55" width="55"><?
									}	
									else
									{											
										if($Let=="D")
										{
											?><img src="/Imgs/Odontologia/P2.gif" style="position:absolute;" height="55" width="55"><?	
										}	
										else
										{
											if($Let=="E")
											{
												?><img src="/Imgs/Odontologia/P5.gif" style="position:absolute;" height="55" width="55"><?
											}
										}
									}
								}
							}
						}						
					}
					for($ii=65;$ii<=69;$ii++)
					{
						$Let=chr($ii);
						if(!empty($TmpMatCuadrante5Proc[$Fecha][$d][$Let]))
						{
							foreach($TmpMatCuadrante5Proc[$Fecha][$d][$Let] as $ImgProc)
							{
								if($ImgProc!="")
								{
									?><img src="<? echo $ImgProc?>" style="position:absolute;" height="55" width="55"><?										
								}
							}
						}
					}						
					?>
					<img src="/Imgs/Odontologia/fondo.gif" height="55" width="55">	
					<?
					}
					elseif($d==58)
					{
						?><p align="left"><font face='Tahoma' color='#0066FF' size='-1' style="writing-mode:tb-rl;filter:flipH() flipV()" ><b>Distal</b></font></p><?
					}
					?>						
					</td>
					<?
				}
			}
			else
			{				
				for($d=58;$d>50;$d--)
				{
					?><td <? if($d<56){?> id="<? echo "D".$d?>" style="cursor:hand;" onClick="AbrirVentana(this.id);" onMouseOver="this.bgColor='#E4EDF8'" onMouseOut="this.bgColor=''"<? } ?>>
						<? if($d<56){?>						
						<img src="/Imgs/Odontologia/Diente_P.gif" style="position:absolute; " height="55" width="55">                  
						<img src="/Imgs/Odontologia/fondo.gif" height="55" width="55">
						<? 
						}
						elseif($d==58)
						{
							?><p align="left"><font face='Tahoma' color='#0066FF' size='-1' style="writing-mode:tb-rl;filter:flipH() flipV()" ><b>Distal</b></font></p><?
						}
						
						 ?>
					   </td>
					<?
				}
			}
			?>
            </tr>            
        </table>
    </td>
    <td rowspan="3" bgcolor="#000000" style="width:1px;"> </td>    <!-- linea verical-->    
    <td>
        <table border="0" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;' align="center"> 	
            <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
                <td>21</td><td>22</td><td>23</td><td>24</td><td>25</td><td>26</td><td>27</td><td>28</td>
            </tr>
            <tr>
            <?
			if($TmpMatCuadrante2)
			{
				for($d=21;$d<29;$d++)
				{
					?><td id="<? echo "D".$d?>" style="cursor:hand" onClick="AbrirVentana(this.id);" onMouseOver="this.bgColor='#E4EDF8'" onMouseOut="this.bgColor=''">
					<?							
					for($ii=65;$ii<=69;$ii++)
					{
						$Let=chr($ii);	
						if($TmpMatCuadrante2[$Fecha][$d][$Let][7])
						{
							$NameIMG=explode("/",$TmpMatCuadrante2[$Fecha][$d][$Let][7]);
							$xxi=count($NameIMG);
							$NameIMG[$xxi]="P".substr($NameIMG[$xxi-1],1,strlen($NameIMG[$xxi-1]));
							
							$TmpMatCuadrante2[$Fecha][$d][$Let][7]=str_replace($NameIMG[$xxi-1],$NameIMG[$xxi],$TmpMatCuadrante2[$Fecha][$d][$Let][7]);//aki							
							?><img src="<? echo $TmpMatCuadrante2[$Fecha][$d][$Let][7]?>" style="position:absolute;"  height="65" width="65"><?	
						}
						else
						{								
							if($Let=="A")
							{
								?><img src="/Imgs/Odontologia/P3.gif" style="position:absolute;" height="65" width="65"><?
							}
							else
							{									
								if($Let=="B")
								{
									?><img src="/Imgs/Odontologia/P1.gif" style="position:absolute;" height="65" width="65"><?
								}	
								else
								{										
									if($Let=="C")
									{
										?><img src="/Imgs/Odontologia/P4.gif" style="position:absolute;" height="65" width="65"><?
									}	
									else
									{											
										if($Let=="D")
										{
											?><img src="/Imgs/Odontologia/P2.gif" style="position:absolute;" height="65" width="65"><?	
										}	
										else
										{
											if($Let=="E")
											{
												?><img src="/Imgs/Odontologia/P5.gif" style="position:absolute;" height="65" width="65"><?
											}
										}
									}
								}
							}
						}						
					}	
					for($ii=65;$ii<=69;$ii++)
					{
						$Let=chr($ii);
						if(!empty($TmpMatCuadrante2Proc[$Fecha][$d][$Let]))
						{
							foreach($TmpMatCuadrante2Proc[$Fecha][$d][$Let] as $ImgProc)
							{
								if($ImgProc!="")
								{
									?><img src="<? echo $ImgProc?>" style="position:absolute;" height="65" width="65"><?										
								}
							}
						}
					}					
					?>
					<img src="/Imgs/Odontologia/fondo.gif" height="65" width="65">							
					</td>
					<?
				}
			}
			else
			{
				for($d=21;$d<29;$d++)
				{
					?><td id="<? echo "D".$d?>" style="cursor:hand" onClick="AbrirVentana(this.id);" onMouseOver="this.bgColor='#E4EDF8'" onMouseOut="this.bgColor=''">						
						<img src="/Imgs/Odontologia/Diente_P.gif" style="position:absolute; " height="65" width="65">                    
						<img src="/Imgs/Odontologia/fondo.gif" height="65" width="65">
					</td>
					<?
				}
			}
			?>
            </tr>            
            <tr style='font: normal normal small-caps 11px Tahoma;'>               	
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">61</td><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">62</td>
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">63</td><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">64</td>
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">65</td>                    
            </tr>
            <tr align="center">
            <?
			if($TmpMatCuadrante6)
			{
				for($d=61;$d<69;$d++)
				{
					?><td <? if($d<66){?> id="<? echo "D".$d?>" style="cursor:hand" onClick="AbrirVentana(this.id);" onMouseOver="this.bgColor='#E4EDF8'" onMouseOut="this.bgColor=''"<? } ?>>
					<? if($d<66)
					{							
					for($ii=65;$ii<=69;$ii++)
					{
						$Let=chr($ii);	
						if($TmpMatCuadrante6[$Fecha][$d][$Let][7])
						{
							$NameIMG=explode("/",$TmpMatCuadrante6[$Fecha][$d][$Let][7]);
							$xxi=count($NameIMG);
							$NameIMG[$xxi]="P".substr($NameIMG[$xxi-1],1,strlen($NameIMG[$xxi-1]));
							
							$TmpMatCuadrante6[$Fecha][$d][$Let][7]=str_replace($NameIMG[$xxi-1],$NameIMG[$xxi],$TmpMatCuadrante6[$Fecha][$d][$Let][7]);//aki							
							?><img src="<? echo $TmpMatCuadrante6[$Fecha][$d][$Let][7]?>" style="position:absolute;"  height="55" width="55"><?	
						}
						else
						{								
							if($Let=="A")
							{
								?><img src="/Imgs/Odontologia/P3.gif" style="position:absolute;" height="55" width="55"><?
							}
							else
							{									
								if($Let=="B")
								{
									?><img src="/Imgs/Odontologia/P1.gif" style="position:absolute;" height="55" width="55"><?
								}	
								else
								{										
									if($Let=="C")
									{
										?><img src="/Imgs/Odontologia/P4.gif" style="position:absolute;" height="55" width="55"><?
									}	
									else
									{											
										if($Let=="D")
										{
											?><img src="/Imgs/Odontologia/P2.gif" style="position:absolute;" height="55" width="55"><?	
										}	
										else
										{
											if($Let=="E")
											{
												?><img src="/Imgs/Odontologia/P5.gif" style="position:absolute;" height="55" width="55"><?
											}
										}
									}
								}
							}
						}						
					}	
					for($ii=65;$ii<=69;$ii++)
					{
						$Let=chr($ii);
						if(!empty($TmpMatCuadrante6Proc[$Fecha][$d][$Let]))
						{
							foreach($TmpMatCuadrante6Proc[$Fecha][$d][$Let] as $ImgProc)
							{
								if($ImgProc!="")
								{
									?><img src="<? echo $ImgProc?>" style="position:absolute;" height="55" width="55"><?										
								}
							}
						}
					}					
					?>
					<img src="/Imgs/Odontologia/fondo.gif" height="55" width="55">	
					<?
					}
					elseif($d==68)
					{
						?><p align="right"><font face='Tahoma' color='#0066FF' size='-1' style="writing-mode:tb-rl;" ><b>Distal</b></font></p><?
					}
					?>						
					</td>
					<?
				}
			}
			else
			{
				for($d=61;$d<69;$d++)
				{
					?><td <? if($d<66){?> id="<? echo "D".$d?>" style="cursor:hand" onClick="AbrirVentana(this.id);" onMouseOver="this.bgColor='#E4EDF8'" onMouseOut="this.bgColor=''"<? } ?>>
						<? if($d<66){?>						
						<img src="/Imgs/Odontologia/Diente_P.gif" style="position:absolute; " height="55" width="55">                     
						<img src="/Imgs/Odontologia/fondo.gif" height="55" width="55">
						<? } 
						elseif($d==68)
						{
							?><p align="right"><font face='Tahoma' color='#0066FF' size='-1' style="writing-mode:tb-rl;" ><b>Distal</b></font></p><?
						}
						?>
					   </td>
					<?
				}
			}
			?>
            </tr>           
        </table>
    </td>
    
</tr>
<tr>
<td colspan="3" style="height:3px" bgcolor="#000000"></td> <!-- linea horizontal-->
</tr>
<tr>
    <td>
        <table border="0" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;' align="center"> 
            <tr align="center">
            <?
			if($TmpMatCuadrante8)
			{
				for($d=88;$d>80;$d--)
				{
					?><td <? if($d<86){?> id="<? echo "D".$d?>" style="cursor:hand" onClick="AbrirVentana(this.id);" onMouseOver="this.bgColor='#E4EDF8'" onMouseOut="this.bgColor=''"<? } ?>>
					<? if($d<86){							
					for($ii=65;$ii<=69;$ii++)
					{
						$Let=chr($ii);	
						if($TmpMatCuadrante8[$Fecha][$d][$Let][7])
						{
							$NameIMG=explode("/",$TmpMatCuadrante8[$Fecha][$d][$Let][7]);
							$xxi=count($NameIMG);
							$NameIMG[$xxi]="P".substr($NameIMG[$xxi-1],1,strlen($NameIMG[$xxi-1]));
							
							$TmpMatCuadrante8[$Fecha][$d][$Let][7]=str_replace($NameIMG[$xxi-1],$NameIMG[$xxi],$TmpMatCuadrante8[$Fecha][$d][$Let][7]);//aki							
							?><img src="<? echo $TmpMatCuadrante8[$Fecha][$d][$Let][7]?>" style="position:absolute;"  height="55" width="55"><?	
						}
						else
						{								
							if($Let=="A")
							{
								?><img src="/Imgs/Odontologia/P3.gif" style="position:absolute;" height="55" width="55"><?
							}
							else
							{									
								if($Let=="B")
								{
									?><img src="/Imgs/Odontologia/P1.gif" style="position:absolute;" height="55" width="55"><?
								}	
								else
								{										
									if($Let=="C")
									{
										?><img src="/Imgs/Odontologia/P4.gif" style="position:absolute;" height="55" width="55"><?
									}	
									else
									{											
										if($Let=="D")
										{
											?><img src="/Imgs/Odontologia/P2.gif" style="position:absolute;" height="55" width="55"><?	
										}	
										else
										{
											if($Let=="E")
											{
												?><img src="/Imgs/Odontologia/P5.gif" style="position:absolute;" height="55" width="55"><?
											}
										}
									}
								}
							}
						}						
					}	
					for($ii=65;$ii<=69;$ii++)
					{
						$Let=chr($ii);
						if(!empty($TmpMatCuadrante8Proc[$Fecha][$d][$Let]))
						{
							foreach($TmpMatCuadrante8Proc[$Fecha][$d][$Let] as $ImgProc)
							{
								if($ImgProc!="")
								{
									?><img src="<? echo $ImgProc?>" style="position:absolute;" height="55" width="55"><?										
								}
							}							
						}
					}					
					?>
					<img src="/Imgs/Odontologia/fondo.gif" height="55" width="55">	
					<?
					}
					elseif($d==88)
					{
						?><p align="left"><font face='Tahoma' color='#0066FF' size='-1' style="writing-mode:tb-rl;filter:flipH() flipV()" ><b>Distal</b></font></p><?
					}
					?>						
					</td>
					<?
				}
			}
			else
			{				
				for($d=88;$d>80;$d--)
				{
					?><td <? if($d<86){?> id="<? echo "D".$d?>" style="cursor:hand" onClick="AbrirVentana(this.id);" onMouseOver="this.bgColor='#E4EDF8'" onMouseOut="this.bgColor=''"<? } ?>>
						<? if($d<86){?>						
						<img src="/Imgs/Odontologia/Diente_P.gif" style="position:absolute; " height="55" width="55">                   
						<img src="/Imgs/Odontologia/fondo.gif" height="55" width="55">
						<? }
						elseif($d==88)
						{
							?><p align="left"><font face='Tahoma' color='#0066FF' size='-1' style="writing-mode:tb-rl;filter:flipH() flipV()" ><b>Distal</b></font></p><?
						}
						 ?>
					   </td>
					<?
				}
			}
			?>
            </tr>
            <tr style='font: normal normal small-caps 11px Tahoma;' >
                <td></td><td></td><td></td>
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">85</td><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">84</td>
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">83</td><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">82</td>
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">81</td>                    
            </tr>             
            <tr>
            <?
			if($TmpMatCuadrante4)
			{
				for($d=48;$d>40;$d--)
				{
					?><td id="<? echo "D".$d?>" style="cursor:hand" onClick="AbrirVentana(this.id);" onMouseOver="this.bgColor='#E4EDF8'" onMouseOut="this.bgColor=''">
					<?							
					for($ii=65;$ii<=69;$ii++)
					{
						$Let=chr($ii);	
						if($TmpMatCuadrante4[$Fecha][$d][$Let][7])
						{
							$NameIMG=explode("/",$TmpMatCuadrante4[$Fecha][$d][$Let][7]);
							$xxi=count($NameIMG);
							$NameIMG[$xxi]="P".substr($NameIMG[$xxi-1],1,strlen($NameIMG[$xxi-1]));
							
							$TmpMatCuadrante4[$Fecha][$d][$Let][7]=str_replace($NameIMG[$xxi-1],$NameIMG[$xxi],$TmpMatCuadrante4[$Fecha][$d][$Let][7]);//aki							
							?><img src="<? echo $TmpMatCuadrante4[$Fecha][$d][$Let][7]?>" style="position:absolute;"  height="65" width="65"><?	
						}
						else
						{								
							if($Let=="A")
							{
								?><img src="/Imgs/Odontologia/P3.gif" style="position:absolute;" height="65" width="65"><?
							}
							else
							{									
								if($Let=="B")
								{
									?><img src="/Imgs/Odontologia/P1.gif" style="position:absolute;" height="65" width="65"><?
								}	
								else
								{										
									if($Let=="C")
									{
										?><img src="/Imgs/Odontologia/P4.gif" style="position:absolute;" height="65" width="65"><?
									}	
									else
									{											
										if($Let=="D")
										{
											?><img src="/Imgs/Odontologia/P2.gif" style="position:absolute;" height="65" width="65"><?	
										}	
										else
										{
											if($Let=="E")
											{
												?><img src="/Imgs/Odontologia/P5.gif" style="position:absolute;" height="65" width="65"><?
											}
										}
									}
								}
							}
						}						
					}	
					for($ii=65;$ii<=69;$ii++)
					{
						$Let=chr($ii);
						if(!empty($TmpMatCuadrante4Proc[$Fecha][$d][$Let]))
						{
							foreach($TmpMatCuadrante4Proc[$Fecha][$d][$Let] as $ImgProc)
							{
								if($ImgProc!="")
								{
									?><img src="<? echo $ImgProc?>" style="position:absolute;" height="65" width="65"><?										
								}
							}
						}
					}					
					?>
					<img src="/Imgs/Odontologia/fondo.gif" height="65" width="65">							
					</td>
					<?
				}
			}
			else
			{
				for($d=48;$d>40;$d--)
				{
					?><td id="<? echo "D".$d?>" style="cursor:hand" onClick="AbrirVentana(this.id);" onMouseOver="this.bgColor='#E4EDF8'" onMouseOut="this.bgColor=''">						
						<img src="/Imgs/Odontologia/Diente_P.gif" style="position:absolute; " height="65" width="65">                   
						<img src="/Imgs/Odontologia/fondo.gif" height="65" width="65">
					</td>
					<?
				}
			}
			?>
            </tr>
            <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
                <td>48</td><td>47</td><td>46</td><td>45</td><td>44</td><td>43</td><td>42</td><td>41</td>
            </tr>                   
        </table>
    </td>
    
    <td>
        <table border="0" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;' align="center"> 	
            <tr align="center">
            <?
			if($TmpMatCuadrante7)
			{
				for($d=71;$d<79;$d++)
				{
					?><td <? if($d<76){?> id="<? echo "D".$d?>" style="cursor:hand" onClick="AbrirVentana(this.id);" onMouseOver="this.bgColor='#E4EDF8'" onMouseOut="this.bgColor=''"<? } ?>>
					<? if($d<76){							
					for($ii=65;$ii<=69;$ii++)
					{
						$Let=chr($ii);							
						if($TmpMatCuadrante7[$Fecha][$d][$Let][7])
						{
							$NameIMG=explode("/",$TmpMatCuadrante7[$Fecha][$d][$Let][7]);
							$xxi=count($NameIMG);
							$NameIMG[$xxi]="P".substr($NameIMG[$xxi-1],1,strlen($NameIMG[$xxi-1]));							
							$TmpMatCuadrante7[$Fecha][$d][$Let][7]=str_replace($NameIMG[$xxi-1],$NameIMG[$xxi],$TmpMatCuadrante7[$Fecha][$d][$Let][7]);//aki							
							?><img src="<? echo $TmpMatCuadrante7[$Fecha][$d][$Let][7]?>" style="position:absolute;"  height="55" width="55"><?	
						}
						else
						{								
							if($Let=="A")
							{
								?><img src="/Imgs/Odontologia/P3.gif" style="position:absolute;" height="55" width="55"><?
							}
							else
							{									
								if($Let=="B")
								{
									?><img src="/Imgs/Odontologia/P1.gif" style="position:absolute;" height="55" width="55"><?
								}	
								else
								{										
									if($Let=="C")
									{
										?><img src="/Imgs/Odontologia/P4.gif" style="position:absolute;" height="55" width="55"><?
									}	
									else
									{											
										if($Let=="D")
										{
											?><img src="/Imgs/Odontologia/P2.gif" style="position:absolute;" height="55" width="55"><?	
										}	
										else
										{
											if($Let=="E")
											{
												?><img src="/Imgs/Odontologia/P5.gif" style="position:absolute;" height="55" width="55"><?
											}
										}
									}
								}
							}
						}						
					}	
					for($ii=65;$ii<=69;$ii++)
					{
						$Let=chr($ii);
						if(!empty($TmpMatCuadrante7Proc[$Fecha][$d][$Let]))
						{
							foreach($TmpMatCuadrante7Proc[$Fecha][$d][$Let] as $ImgProc)
							{
								if($ImgProc!="")
								{
									?><img src="<? echo $ImgProc?>" style="position:absolute;" height="55" width="55"><?										
								}
							}
						}
					}					
					?>
					<img src="/Imgs/Odontologia/fondo.gif" height="55" width="55">	
					<?
					}
					elseif($d==78)
					{
						?><p align="right"><font face='Tahoma' color='#0066FF' size='-1' style="writing-mode:tb-rl;" ><b>Distal</b></font></p><?
					}
					?>						
					</td>
					<?
				}
			}
			else
			{	
				for($d=71;$d<79;$d++)
				{
					?><td <? if($d<76){?> id="<? echo "D".$d?>" style="cursor:hand" onClick="AbrirVentana(this.id);" onMouseOver="this.bgColor='#E4EDF8'" onMouseOut="this.bgColor=''"<? } ?>>
						<? if($d<76){?>						
						<img src="/Imgs/Odontologia/Diente_P.gif" style="position:absolute; " height="55" width="55">                   
						<img src="/Imgs/Odontologia/fondo.gif" height="55" width="55">
						<? } 
						elseif($d==78)
						{
							?><p align="right"><font face='Tahoma' color='#0066FF' size='-1' style="writing-mode:tb-rl;" ><b>Distal</b></font></p><?
						}
						?>
					   </td>
					<?
				}	
			}
			?>
            </tr>
            <tr style='font: normal normal small-caps 11px Tahoma;'>               	
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">71</td><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">72</td>
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">73</td><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">74</td>
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">75</td>                    
            </tr>            
            <tr>
            <?
			if($TmpMatCuadrante3)
			{
				for($d=31;$d<39;$d++)
				{
					?><td id="<? echo "D".$d?>" style="cursor:hand" onClick="AbrirVentana(this.id);" onMouseOver="this.bgColor='#E4EDF8'" onMouseOut="this.bgColor=''">
					<?							
					for($ii=65;$ii<=69;$ii++)
					{
						$Let=chr($ii);	
						if($TmpMatCuadrante3[$Fecha][$d][$Let][7])
						{
							$NameIMG=explode("/",$TmpMatCuadrante3[$Fecha][$d][$Let][7]);
							$xxi=count($NameIMG);
							$NameIMG[$xxi]="P".substr($NameIMG[$xxi-1],1,strlen($NameIMG[$xxi-1]));
							
							$TmpMatCuadrante3[$Fecha][$d][$Let][7]=str_replace($NameIMG[$xxi-1],$NameIMG[$xxi],$TmpMatCuadrante3[$Fecha][$d][$Let][7]);//aki
							?><script language="javascript"></script><img src="<? echo $TmpMatCuadrante3[$Fecha][$d][$Let][7]?>" style="position:absolute;"  height="65" width="65"><?	
						}
						else
						{								
							if($Let=="A")
							{
								?><img src="/Imgs/Odontologia/P3.gif" style="position:absolute;" height="65" width="65"><?
							}
							else
							{									
								if($Let=="B")
								{
									?><img src="/Imgs/Odontologia/P1.gif" style="position:absolute;" height="65" width="65"><?
								}	
								else
								{										
									if($Let=="C")
									{
										?><img src="/Imgs/Odontologia/P4.gif" style="position:absolute;" height="65" width="65"><?
									}	
									else
									{											
										if($Let=="D")
										{
											?><img src="/Imgs/Odontologia/P2.gif" style="position:absolute;" height="65" width="65"><?	
										}	
										else
										{
											if($Let=="E")
											{
												?><img src="/Imgs/Odontologia/P5.gif" style="position:absolute;" height="65" width="65"><?
											}
										}
									}
								}
							}
						}						
					}	
					for($ii=65;$ii<=69;$ii++)
					{
						$Let=chr($ii);
						if(!empty($TmpMatCuadrante3Proc[$Fecha][$d][$Let]))
						{
							foreach($TmpMatCuadrante3Proc[$Fecha][$d][$Let] as $ImgProc)
							{
								if($ImgProc!="")
								{
									?><img src="<? echo $ImgProc?>" style="position:absolute;" height="65" width="65"><?										
								}
							}
						}
					}					
					?>
					<img src="/Imgs/Odontologia/fondo.gif" height="65" width="65">							
					</td>
					<?
				}
			}
			else
			{
				for($d=31;$d<39;$d++)
				{
					?><td id="<? echo "D".$d?>" style="cursor:hand" onClick="AbrirVentana(this.id);" onMouseOver="this.bgColor='#E4EDF8'" onMouseOut="this.bgColor=''">						
						<img src="/Imgs/Odontologia/Diente_P.gif" style="position:absolute; " height="65" width="65">                   
						<img src="/Imgs/Odontologia/fondo.gif" height="65" width="65">
					</td>
					<?
				}	
			}
			?>
            </tr>
            <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
                <td>31</td><td>32</td><td>33</td><td>34</td><td>35</td><td>36</td><td>37</td><td>38</td>
            </tr>           
        </table>
    </td>       
    
</tr>
</table>
<div id="Etiq2" style="position:absolute;background:none;"><input type="text" name="Etiq2" style=" background-color:transparent; border:thin; font : normal normal small-caps 14px Tahoma;font-weight:bold; color:#2A7FFF;" size="4" readonly/></div>
<div id="Etiq4" style="position:absolute;background:none;"><input type="text" name="Etiq4" style=" background-color:transparent; border:thin; font : normal normal small-caps 14px Tahoma;font-weight:bold; color:#2A7FFF;" size="4" readonly/></div>
<div id="Etiq6" style="position:absolute;background:none;"><input type="text" name="Etiq6" style=" background-color:transparent; border:thin; font : normal normal small-caps 14px Tahoma;font-weight:bold; color:#2A7FFF;" size="10" readonly/></div>
<div id="Etiq7" style="position:absolute;background:none;"><input type="text" name="Etiq7" style=" background-color:transparent; border:thin; font : normal normal small-caps 14px Tahoma;font-weight:bold; color:#2A7FFF;" size="10" readonly/></div>
</form>	
<script language="javascript" type="text/javascript">
window.onresize = window.onload = function ()
{		
	CreaEtiquetaH("Lingual",2);CreaEtiquetaH("Lingual",4);CreaEtiquetaH("Vestibular",6);CreaEtiquetaH("Vestibular",7);
	document.FORMA.Servicio.value=parent.document.FORMA.Servicio.value;
}
</script>
<?
}
else
{
echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>No Existen registros anteriores, por favor diligencie el Odontograma Inicial!!!</b></font></center>";
}
?>
</body>
</html>