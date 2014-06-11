<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();	
	include("Funciones.php");
	/*echo round(substr($Fecha,0,4),0);
	echo round(substr($Fecha,5,2),0);
	echo round(substr($Fecha,8,2),0);*/
	$ND=getdate();
	if($ND[mon]<10){$Mes="0".$ND[mon];}else{$Mes=$ND[mon];}	
	if($ND[mday]<10){$Dia="0".$ND[mday];}else{$Dia=$ND[mday];}
	$HoraHoy="$ND[hours]:$ND[minutes]:$ND[seconds]";
	if(round(substr($Fecha,0,4),0)==round($ND[year],0)&&round(substr($Fecha,5,2),0)==round($Mes,0)&&round(substr($Fecha,8,2),0)==number_format($Dia,0))
	{$Deshabilitar="";}
	else{$Deshabilitar="Disabled";}
	$Cuadrante=substr($Diente,0,1);
	if($GT)
	{
		/*$cons="Select ZonaD from odontologia.odontogramaproc where OdontogramaProc.Compania='$Compania[0]' and Identificacion='$Paciente[1]'
		and Cuadrante='$Cuadrante' and Diente='$Diente' and ZonaD='$ParteD' and Fecha='$Fecha'";	
		$res=ExQuery($cons);		
		if(ExNumRows($res)==0)
		{*/
			
			//Modificado por requerimientos
			if($Cuadrante>4){$Denticion="Temporal";}else{$Denticion="Permanente";}	
			$cons="Select ZonaD from odontologia.tmpodontogramaproc where TmpOdontogramaProc.Compania='$Compania[0]' and Tmpcod='$TMPCOD' 
			and Identificacion='$Paciente[1]' and Cuadrante='$Cuadrante' and Diente='$Diente' and ZonaD='$ParteD' and Fecha='$Fecha'";	
			$res=ExQuery($cons);
			if(ExNumRows($res)==0)
			{				
				$cons1="Insert Into Odontologia.tmpodontogramaproc (Compania,TmpCod,Identificacion,Cuadrante,Diente,ZonaD,
				Procedimiento,Fecha,TipoOdonto,Denticion,Edicion,fechaant) values('$Compania[0]','$TMPCOD','$Paciente[1]','$Cuadrante',
				'$Diente','$ParteD',-1,'$Fecha','$TipoOdonto','$Denticion','1','$Fecha $HoraHoy')";
				$res1=ExQuery($cons1);
				$ProcFalso=1;
				/*?><script language="javascript">
				ParteD="<? echo $ParteD;?>";			
				if(ParteD=="A"){Ruta="/Imgs/Odontologia/D3.gif";}
				else
				{
					if(ParteD=="B"){Ruta="/Imgs/Odontologia/D1.gif";}
					else
					{
						if(ParteD=="C"){Ruta="/Imgs/Odontologia/D4.gif";}
						else
						{
							if(ParteD=="D"){Ruta="/Imgs/Odontologia/D2.gif";}
							else
							{
								if(ParteD=="E"){Ruta="/Imgs/Odontologia/D5.gif";}				
							}		
						}
					}
				}
				ID=ParteD+ParteD;		
				parent.document.getElementById(ID).value=Ruta;
				parent.document.getElementById(ParteD).src=Ruta;
				alert("Primero debe agregar por lo menos un Procedimiento para la parte del diente!!!");						
				</script><?
				$RutaC="";*/
			}			
			else
			{
				?><script language="javascript">
				parent.document.FORMA.Color.value="<? echo $RutaC?>";
				parent.document.FORMA.G.value=1;
				parent.GG();
				</script><?
			 //hasta aqui
			}
			if($ProcFalso)
			{
				?><script language="javascript">
				parent.document.FORMA.Color.value="<? echo $RutaC?>";
				parent.document.FORMA.G.value=1;
				parent.GG();
				</script><?
			}
		/*}
		else
		{
			/*$cons="Update Odontologia.TmpOdontogramaProc set ImagenZona='$RutaC' where Compania='$Compania[0]' and TMPCOD='$TMPCOD' and
			Identificacion='$Paciente[1]' and Cuadrante='$Cuadrante' and Diente='$Diente' and ZonaD='$ParteD' and Fecha='$Fecha'";
			//echo $cons;
			$res=ExQuery($cons);*/
			/*?><script language="javascript">
			parent.document.FORMA.Color.value="<? echo $RutaC?>";
            parent.document.FORMA.G.value=1;
			parent.GG();
            </script><?
		}*/
	}
	
	$cons="Select codigo, nombre, tipo, ruta, cup from odontologia.procedimientosimgs  where Compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$MatProcedimientos[$fila[0]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4]);
	}
	$cons="Select color, ncolor, ruta, descripcion from odontologia.colorconvenciones  where Compania='$Compania[0]' order by ncolor";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$MatColores[$fila[0]]=array($fila[0],$fila[1],$fila[2],$fila[3]);		
	}
	if($RutaC=="/Imgs/Odontologia/D3.gif"||$RutaC=="/Imgs/Odontologia/D1.gif"||$RutaC=="/Imgs/Odontologia/D2.gif"||$RutaC=="/Imgs/Odontologia/D4.gif"||$RutaC=="/Imgs/Odontologia/D5.gif"||$RutaC=="")
	{
		$Colores="#FFFFFF";		
	}
	else
	{
		if($RutaC=="/Imgs/Odontologia/DAC3.GIF"||$RutaC=="/Imgs/Odontologia/DAC1.GIF"||$RutaC=="/Imgs/Odontologia/DAC2.GIF"||$RutaC=="/Imgs/Odontologia/DAC4.GIF"||$RutaC=="/Imgs/Odontologia/DAC5.GIF")
		{
			$Colores="#2A9FFF";
		}
		elseif($RutaC=="/Imgs/Odontologia/DACR3.GIF"||$RutaC=="/Imgs/Odontologia/DACR1.GIF"||$RutaC=="/Imgs/Odontologia/DACR2.GIF"||$RutaC=="/Imgs/Odontologia/DACR4.GIF"||$RutaC=="/Imgs/Odontologia/DACR5.GIF")
		{	
			$Colores="#2A9FFFR";	
		}
		else
		{
			if($RutaC=="/Imgs/Odontologia/DV3.GIF"||$RutaC=="/Imgs/Odontologia/DV1.GIF"||$RutaC=="/Imgs/Odontologia/DV2.GIF"||$RutaC=="/Imgs/Odontologia/DV4.GIF"||$RutaC=="/Imgs/Odontologia/DV5.GIF")
			{
				$Colores="#2ADF00";
			}
			elseif($RutaC=="/Imgs/Odontologia/DVR3.GIF"||$RutaC=="/Imgs/Odontologia/DVR1.GIF"||$RutaC=="/Imgs/Odontologia/DVR2.GIF"||$RutaC=="/Imgs/Odontologia/DVR4.GIF"||$RutaC=="/Imgs/Odontologia/DVR5.GIF")
			{
				$Colores="#2ADF00R";
			}
			else
			{
				if($RutaC=="/Imgs/Odontologia/DR3.GIF"||$RutaC=="/Imgs/Odontologia/DR1.GIF"||$RutaC=="/Imgs/Odontologia/DR2.GIF"||$RutaC=="/Imgs/Odontologia/DR4.GIF"||$RutaC=="/Imgs/Odontologia/DR5.GIF")
				{
					$Colores="#FF0000";
				}
				elseif($RutaC=="/Imgs/Odontologia/DRC3.GIF"||$RutaC=="/Imgs/Odontologia/DRC1.GIF"||$RutaC=="/Imgs/Odontologia/DRC2.GIF"||$RutaC=="/Imgs/Odontologia/DRC4.GIF"||$RutaC=="/Imgs/Odontologia/DRC5.GIF")
				{
					$Colores="#FF0000C";	
				}
				elseif($RutaC=="/Imgs/Odontologia/DRCP3.GIF"||$RutaC=="/Imgs/Odontologia/DRCP1.GIF"||$RutaC=="/Imgs/Odontologia/DRCP2.GIF"||$RutaC=="/Imgs/Odontologia/DRCP4.GIF"||$RutaC=="/Imgs/Odontologia/DRCP5.GIF")
				{
					$Colores="#FF0000CP";	
				}	
				elseif($RutaC=="/Imgs/Odontologia/DREP3.GIF"||$RutaC=="/Imgs/Odontologia/DREP1.GIF"||$RutaC=="/Imgs/Odontologia/DREP2.GIF"||$RutaC=="/Imgs/Odontologia/DREP4.GIF"||$RutaC=="/Imgs/Odontologia/DREP5.GIF")
				{
					$Colores="#FF0000EP";	
				}
				elseif($RutaC=="/Imgs/Odontologia/DRX3.GIF"||$RutaC=="/Imgs/Odontologia/DRX1.GIF"||$RutaC=="/Imgs/Odontologia/DRX2.GIF"||$RutaC=="/Imgs/Odontologia/DRX4.GIF"||$RutaC=="/Imgs/Odontologia/DRX5.GIF")
				{
					$Colores="#FF0000X";	
				}
				elseif($RutaC=="/Imgs/Odontologia/DRF3.GIF"||$RutaC=="/Imgs/Odontologia/DRF1.GIF"||$RutaC=="/Imgs/Odontologia/DRF2.GIF"||$RutaC=="/Imgs/Odontologia/DRF4.GIF"||$RutaC=="/Imgs/Odontologia/DRF5.GIF")
				{
					$Colores="#FF0000F";	
				}
				elseif($RutaC=="/Imgs/Odontologia/DRFD3.GIF"||$RutaC=="/Imgs/Odontologia/DRFD1.GIF"||$RutaC=="/Imgs/Odontologia/DRFD2.GIF"||$RutaC=="/Imgs/Odontologia/DRFD4.GIF"||$RutaC=="/Imgs/Odontologia/DRFD5.GIF")
				{
					$Colores="#FF0000FD";	
				}
				elseif($RutaC=="/Imgs/Odontologia/DRMPD3.GIF"||$RutaC=="/Imgs/Odontologia/DRMPD1.GIF"||$RutaC=="/Imgs/Odontologia/DRMPD2.GIF"||$RutaC=="/Imgs/Odontologia/DRMPD4.GIF"||$RutaC=="/Imgs/Odontologia/DRMPD5.GIF")
				{
					$Colores="#FF0000MPD";	
				}
				elseif($RutaC=="/Imgs/Odontologia/DRMB3.GIF"||$RutaC=="/Imgs/Odontologia/DRMB1.GIF"||$RutaC=="/Imgs/Odontologia/DRMB2.GIF"||$RutaC=="/Imgs/Odontologia/DRMB4.GIF"||$RutaC=="/Imgs/Odontologia/DRMB5.GIF")
				{
					$Colores="#FF0000MB";	
				}
				elseif($RutaC=="/Imgs/Odontologia/DRR3.GIF"||$RutaC=="/Imgs/Odontologia/DRR1.GIF"||$RutaC=="/Imgs/Odontologia/DRR2.GIF"||$RutaC=="/Imgs/Odontologia/DRR4.GIF"||$RutaC=="/Imgs/Odontologia/DRR5.GIF")
				{
					$Colores="#FF0000R";	
				}	
				else
				{
					if($RutaC=="/Imgs/Odontologia/DAO3.GIF"||$RutaC=="/Imgs/Odontologia/DAO1.GIF"||$RutaC=="/Imgs/Odontologia/DAO2.GIF"||$RutaC=="/Imgs/Odontologia/DAO4.GIF"||$RutaC=="/Imgs/Odontologia/DAO5.GIF")
					{
						$Colores="#0000FF";
					}	
					elseif($RutaC=="/Imgs/Odontologia/DAOA3.GIF"||$RutaC=="/Imgs/Odontologia/DAOA1.GIF"||$RutaC=="/Imgs/Odontologia/DAOA2.GIF"||$RutaC=="/Imgs/Odontologia/DAOA4.GIF"||$RutaC=="/Imgs/Odontologia/DAOA5.GIF")
					{
						$Colores="#0000FFA";	
					}
					elseif($RutaC=="/Imgs/Odontologia/DAOCO3.GIF"||$RutaC=="/Imgs/Odontologia/DAOCO1.GIF"||$RutaC=="/Imgs/Odontologia/DAOCO2.GIF"||$RutaC=="/Imgs/Odontologia/DAOCO4.GIF"||$RutaC=="/Imgs/Odontologia/DAOCO5.GIF")
					{
						$Colores="#0000FFCO";	
					}
					elseif($RutaC=="/Imgs/Odontologia/DAODOI3.GIF"||$RutaC=="/Imgs/Odontologia/DAODOI1.GIF"||$RutaC=="/Imgs/Odontologia/DAODOI2.GIF"||$RutaC=="/Imgs/Odontologia/DAODOI4.GIF"||$RutaC=="/Imgs/Odontologia/DAODOI5.GIF")
					{
						$Colores="#0000FFDOI";	
					}
					elseif($RutaC=="/Imgs/Odontologia/DAOHP3.GIF"||$RutaC=="/Imgs/Odontologia/DAOHP1.GIF"||$RutaC=="/Imgs/Odontologia/DAOHP2.GIF"||$RutaC=="/Imgs/Odontologia/DAOHP4.GIF"||$RutaC=="/Imgs/Odontologia/DAOHP5.GIF")
					{
						$Colores="#0000FFHP";	
					}
					elseif($RutaC=="/Imgs/Odontologia/DAOIMP3.GIF"||$RutaC=="/Imgs/Odontologia/DAOIMP1.GIF"||$RutaC=="/Imgs/Odontologia/DAOIMP2.GIF"||$RutaC=="/Imgs/Odontologia/DAOIMP4.GIF"||$RutaC=="/Imgs/Odontologia/DAOIMP5.GIF")
					{
						$Colores="#0000FFIMP";	
					}
					elseif($RutaC=="/Imgs/Odontologia/DAOINC3.GIF"||$RutaC=="/Imgs/Odontologia/DAOINC1.GIF"||$RutaC=="/Imgs/Odontologia/DAOINC2.GIF"||$RutaC=="/Imgs/Odontologia/DAOINC4.GIF"||$RutaC=="/Imgs/Odontologia/DAOINC5.GIF")
					{
						$Colores="#0000FFINC";	
					}
					elseif($RutaC=="/Imgs/Odontologia/DAOPM3.GIF"||$RutaC=="/Imgs/Odontologia/DAOPM1.GIF"||$RutaC=="/Imgs/Odontologia/DAOPM2.GIF"||$RutaC=="/Imgs/Odontologia/DAOPM4.GIF"||$RutaC=="/Imgs/Odontologia/DAOPM5.GIF")
					{
						$Colores="#0000FFPM";	
					}
					elseif($RutaC=="/Imgs/Odontologia/DAOFP3.GIF"||$RutaC=="/Imgs/Odontologia/DAOFP1.GIF"||$RutaC=="/Imgs/Odontologia/DAOFP2.GIF"||$RutaC=="/Imgs/Odontologia/DAOFP4.GIF"||$RutaC=="/Imgs/Odontologia/DAOFP5.GIF")
					{
						$Colores="#0000FFFP";	
					}	
					elseif($RutaC=="/Imgs/Odontologia/DAOPR3.GIF"||$RutaC=="/Imgs/Odontologia/DAOPR1.GIF"||$RutaC=="/Imgs/Odontologia/DAOPR2.GIF"||$RutaC=="/Imgs/Odontologia/DAOPR4.GIF"||$RutaC=="/Imgs/Odontologia/DAOPR5.GIF")
					{
						$Colores="#0000FFPR";	
					}	
					elseif($RutaC=="/Imgs/Odontologia/DAOSE3.GIF"||$RutaC=="/Imgs/Odontologia/DAOSE1.GIF"||$RutaC=="/Imgs/Odontologia/DAOSE2.GIF"||$RutaC=="/Imgs/Odontologia/DAOSE4.GIF"||$RutaC=="/Imgs/Odontologia/DAOSE5.GIF")
					{
						$Colores="#0000FFSE";	
					}
					elseif($RutaC=="/Imgs/Odontologia/DAOSR3.GIF"||$RutaC=="/Imgs/Odontologia/DAOSR1.GIF"||$RutaC=="/Imgs/Odontologia/DAOSR2.GIF"||$RutaC=="/Imgs/Odontologia/DAOSR4.GIF"||$RutaC=="/Imgs/Odontologia/DAOSR5.GIF")
					{
						$Colores="#0000FFSR";	
					}		
					elseif($RutaC=="/Imgs/Odontologia/DAOTC3.GIF"||$RutaC=="/Imgs/Odontologia/DAOTC1.GIF"||$RutaC=="/Imgs/Odontologia/DAOTC2.GIF"||$RutaC=="/Imgs/Odontologia/DAOTC4.GIF"||$RutaC=="/Imgs/Odontologia/DAOTC5.GIF")
					{
						$Colores="#0000FFTC";	
					}		
					else
					{
						if($RutaC=="/Imgs/Odontologia/DN3.GIF"||$RutaC=="/Imgs/Odontologia/DN1.GIF"||$RutaC=="/Imgs/Odontologia/DN2.GIF"||$RutaC=="/Imgs/Odontologia/DN4.GIF"||$RutaC=="/Imgs/Odontologia/DN5.GIF")
						{
							$Colores="#000000";
						}
						elseif($RutaC=="/Imgs/Odontologia/DAM3.GIF"||$RutaC=="/Imgs/Odontologia/DAM1.GIF"||$RutaC=="/Imgs/Odontologia/DAM2.GIF"||$RutaC=="/Imgs/Odontologia/DAM4.GIF"||$RutaC=="/Imgs/Odontologia/DAM5.GIF")
						{
							$Colores="#FFE401";	
						}
						elseif($RutaC=="/Imgs/Odontologia/DAMR3.GIF"||$RutaC=="/Imgs/Odontologia/DAMR1.GIF"||$RutaC=="/Imgs/Odontologia/DAMR2.GIF"||$RutaC=="/Imgs/Odontologia/DAMR4.GIF"||$RutaC=="/Imgs/Odontologia/DAMR5.GIF")
						{
							$Colores="#FFE401R";	
						}
						elseif($RutaC=="/Imgs/Odontologia/DVDOR3.GIF"||$RutaC=="/Imgs/Odontologia/DVDOR1.GIF"||$RutaC=="/Imgs/Odontologia/DVDOR2.GIF"||$RutaC=="/Imgs/Odontologia/DVDOR4.GIF"||$RutaC=="/Imgs/Odontologia/DVDOR5.GIF")
						{
							$Colores="#2ADF00DOR";	
						}
						elseif($RutaC=="/Imgs/Odontologia/DACDOA3.GIF"||$RutaC=="/Imgs/Odontologia/DACDOA1.GIF"||$RutaC=="/Imgs/Odontologia/DACDOA2.GIF"||$RutaC=="/Imgs/Odontologia/DACDOA4.GIF"||$RutaC=="/Imgs/Odontologia/DACDOA5.GIF")
						{
							$Colores="#2A9FFFDOA";	
						}
																			
					}
				}
			}
		}
	}	
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript"> 
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
	function AsignaColor()
	{
		parent.document.FORMA.submit();
		CerrarThis();
	}
	function DescCol(MatCol)
	{
		alert(MatCol);
	}
	function CambiarParte(Coloress,ParteD)
	{	
		switch(Coloress.value)
		{
			//Blanco
			case "#FFFFFF":	//alert("<? echo $MatColores['#FFFFFF'][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#FFFFFF"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/D3.gif";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/D1.gif";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/D4.gif";}
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/D2.gif";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/D5.gif";}				
										}		
									}
								}
							}																	
							break;
			//azul claro
			case "#2A9FFF":	//alert("<? echo $MatColores["#2A9FFF"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#2A9FFF"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DAC3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DAC1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DAC4.GIF";}	
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DAC2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DAC5.GIF";}
										}
									}
								}
							}						
							break;
			//azul claro R
			case "#2A9FFFR":	//alert("<? echo $MatColores["#2A9FFFR"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#2A9FFFR"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DACR3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DACR1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DACR4.GIF";}	
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DACR2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DACR5.GIF";}
										}
									}
								}
							}						
							break;
			//azul claro doa
			case "#2A9FFFDOA":	//alert("<? echo $MatColores["#2A9FFF"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#2A9FFFDOA"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DACDOA3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DACDOA1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DACDOA4.GIF";}	
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DACDOA2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DACDOA5.GIF";}
										}
									}
								}
							}						
							break;
			//VERDE
			case "#2ADF00":	//alert("<? echo $MatColores["#2ADF00"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#2ADF00"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DV3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DV1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DV4.GIF";}
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DV2.GIF";}	
										else
										{											
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DV5.GIF";}
										}
									}
								}	
							}							
							break;
			//VERDE
			case "#2ADF00R":	//alert("<? echo $MatColores["#2ADF00R"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#2ADF00"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DVR3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DVR1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DVR4.GIF";}
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DVR2.GIF";}	
										else
										{											
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DVR5.GIF";}
										}
									}
								}	
							}							
							break;
			//VERDE DOR
			case "#2ADF00DOR":	//alert("<? echo $MatColores["#2ADF00DOR"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#2ADF00DOR"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DVDOR3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DVDOR1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DVDOR4.GIF";}
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DVDOR2.GIF";}	
										else
										{											
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DVDOR5.GIF";}
										}
									}
								}	
							}							
							break;
			//ROJO
			case "#FF0000":	//alert("<? echo $MatColores["#FF0000"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#FF0000"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DR3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DR1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DR4.GIF";}
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DR2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DR5.GIF";}
										}		
									}		
								}	
							}												
							break;
			//ROJO C
			case "#FF0000C":	//alert("<? echo $MatColores["#FF0000"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#FF0000C"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DRC3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DRC1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DRC4.GIF";}
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DRC2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DRC5.GIF";}
										}		
									}		
								}	
							}												
							break;
			//ROJO CP
			case "#FF0000CP":	//alert("<? echo $MatColores["#FF0000"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#FF0000CP"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DRCP3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DRCP1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DRCP4.GIF";}
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DRCP2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DRCP5.GIF";}
										}		
									}		
								}	
							}												
							break;
			//ROJO EP
			case "#FF0000EP":	//alert("<? echo $MatColores["#FF0000"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#FF0000EP"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DREP3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DREP1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DREP4.GIF";}
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DREP2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DREP5.GIF";}
										}		
									}		
								}	
							}												
							break;
			//ROJO X
			case "#FF0000X":	//alert("<? echo $MatColores["#FF0000"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#FF0000X"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DRX3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DRX1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DRX4.GIF";}
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DRX2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DRX5.GIF";}
										}		
									}		
								}	
							}												
							break;
			//ROJO F
			case "#FF0000F":	//alert("<? echo $MatColores["#FF0000"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#FF0000F"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DRF3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DRF1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DRF4.GIF";}
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DRF2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DRF5.GIF";}
										}		
									}		
								}	
							}												
							break;
			//ROJO FD
			case "#FF0000FD":	//alert("<? echo $MatColores["#FF0000FD"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#FF0000FD"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DRFD3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DRFD1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DRFD4.GIF";}
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DRFD2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DRFD5.GIF";}
										}		
									}		
								}	
							}												
							break;
			//ROJO MPD
			case "#FF0000MPD":	//alert("<? echo $MatColores["#FF0000"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#FF0000MPD"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DRMPD3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DRMPD1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DRMPD4.GIF";}
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DRMPD2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DRMPD5.GIF";}
										}		
									}		
								}	
							}												
							break;
			//ROJO R
			case "#FF0000R":	//alert("<? echo $MatColores["#FF0000"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#FF0000R"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DRR3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DRR1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DRR4.GIF";}
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DRR2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DRR5.GIF";}
										}		
									}		
								}	
							}												
							break;
			//ROJO MB
			case "#FF0000MB":	//alert("<? echo $MatColores["#FF0000"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#FF0000MB"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DRMB3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DRMB1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DRMB4.GIF";}
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DRMB2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DRMB5.GIF";}
										}		
									}		
								}	
							}												
							break;
			//AZUL OSC
			case "#0000FF":	//alert("<? echo $MatColores["#0000FF"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#0000FF"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DAO3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DAO1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DAO4.GIF";}									
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DAO2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DAO5.GIF";}														
										}			
									}
								}	
							}						
							break;
			//AZUL OSC A
			case "#0000FFA":	//alert("<? echo $MatColores["#0000FF"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#0000FFA"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DAOA3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DAOA1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DAOA4.GIF";}									
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DAOA2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DAOA5.GIF";}														
										}			
									}
								}	
							}						
							break;
			//AZUL OSC CO
			case "#0000FFCO":	//alert("<? echo $MatColores["#0000FF"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#0000FFCO"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DAOCO3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DAOCO1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DAOCO4.GIF";}									
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DAOCO2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DAOCO5.GIF";}														
										}			
									}
								}	
							}						
							break;
			//AZUL OSC CO
			case "#0000FFDOI":	//alert("<? echo $MatColores["#0000FF"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#0000FFDOI"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DAODOI3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DAODOI1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DAODOI4.GIF";}									
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DAODOI2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DAODOI5.GIF";}														
										}			
									}
								}	
							}						
							break;
			//AZUL OSC HP
			case "#0000FFHP":	//alert("<? echo $MatColores["#0000FFHP"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#0000FFHP"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DAOHP3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DAOHP1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DAOHP4.GIF";}									
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DAOHP2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DAOHP5.GIF";}														
										}			
									}
								}	
							}						
							break;
			//AZUL OSC HP
			case "#0000FFIMP":	//alert("<? echo $MatColores["#0000FFHP"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#0000FFIMP"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DAOIMP3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DAOIMP1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DAOIMP4.GIF";}									
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DAOIMP2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DAOIMP5.GIF";}														
										}			
									}
								}	
							}						
							break;
			//AZUL OSC INC
			case "#0000FFINC":	//alert("<? echo $MatColores["#0000FFHP"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#0000FFINC"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DAOINC3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DAOINC1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DAOINC4.GIF";}									
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DAOINC2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DAOINC5.GIF";}														
										}			
									}
								}	
							}						
							break;
			//AZUL OSC PM
			case "#0000FFPM":	//alert("<? echo $MatColores["#0000FFHP"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#0000FFPM"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DAOPM3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DAOPM1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DAOPM4.GIF";}									
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DAOPM2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DAOPM5.GIF";}														
										}			
									}
								}	
							}						
							break;							
			//AZUL OSC FP			
			case "#0000FFFP":	//alert("<? echo $MatColores["#0000FFFP"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#0000FFFP"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DAOFP3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DAOFP1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DAOFP4.GIF";}									
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DAOFP2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DAOFP5.GIF";}														
										}			
									}
								}	
							}						
							break;
			//AZUL OSC PR			
			case "#0000FFPR":	//alert("<? echo $MatColores["#0000FFPR"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#0000FFPR"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DAOPR3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DAOPR1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DAOPR4.GIF";}									
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DAOPR2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DAOPR5.GIF";}														
										}			
									}
								}	
							}						
							break;
			//AZUL OSC SE			
			case "#0000FFSE":	//alert("<? echo $MatColores["#0000FFSE"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#0000FFSE"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DAOSE3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DAOSE1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DAOSE4.GIF";}									
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DAOSE2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DAOSE5.GIF";}														
										}			
									}
								}	
							}						
							break;
			//AZUL OSC SR			
			case "#0000FFSR":	//alert("<? echo $MatColores["#0000FFSR"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#0000FFSR"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DAOSR3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DAOSR1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DAOSR4.GIF";}									
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DAOSR2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DAOSR5.GIF";}														
										}			
									}
								}	
							}						
							break;
			//AZUL OSC SR			
			case "#0000FFTC":	//alert("<? echo $MatColores["#0000FFTC"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#0000FFTC"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DAOTC3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DAOTC1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DAOTC4.GIF";}									
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DAOTC2.GIF";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DAOTC5.GIF";}														
										}			
									}
								}	
							}						
							break;
			case "#000000":	//alert("<? echo $MatColores["#000000"][0]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#000000"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DN3.GIF";	}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DN1.GIF"; }
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DN4.GIF"; }
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DN2.GIF";}		
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DN5.GIF"; }	
										}
									}											
								}	
							}
							break;
			case "#FFE401":	//alert("<? echo $MatColores["#FFE401"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#FFE401"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DAM3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DAM1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DAM4.GIF";}
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DAM2.GIF";}		
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DAM5.GIF";}	
										}
									}											
								}	
							}							
							break;
			case "#FFE401R":	//alert("<? echo $MatColores["#FFE401R"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#FFE401"][3]?>";
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/DAMR3.GIF";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/DAMR1.GIF";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/DAMR4.GIF";}
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/DAMR2.GIF";}		
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/DAMR5.GIF";}	
										}
									}											
								}	
							}							
							break;
			default:		//alert("<? echo $MatColores["#FFFFFF"][3]?>");
							document.FORMA.Descripcion.value="<? echo $MatColores["#FFFFFF"][3]?>";	
							if(ParteD=="A"){Ruta="/Imgs/Odontologia/D3.gif";}
							else
							{
								if(ParteD=="B"){Ruta="/Imgs/Odontologia/D1.gif";}
								else
								{
									if(ParteD=="C"){Ruta="/Imgs/Odontologia/D4.gif";}
									else
									{
										if(ParteD=="D"){Ruta="/Imgs/Odontologia/D2.gif";}
										else
										{
											if(ParteD=="E"){Ruta="/Imgs/Odontologia/D5.gif";}				
										}		
									}
								}
							}
							break;
		}
		ID=ParteD+ParteD;		
		parent.document.getElementById(ID).value=Ruta;
		parent.document.getElementById(ParteD).src=Ruta;		
		document.FORMA.RutaC.value=Ruta;
		document.FORMA.GT.value=1;
		document.FORMA.submit();
	}	
</script>
</head>
<body background="/Imgs/Fondo.jpg" style="width:100%">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>"/>
<input type="hidden" name="Fecha" value="<? echo $Fecha?>"/>
<input type="hidden" name="Diente" value="<? echo $Diente?>"/>
<input type="hidden" name="ParteD" value="<? echo $ParteD?>"/>
<input type="hidden" name="RutaC" value="<? echo $RutaC?>"/>
<input type="hidden" name="GT" value="<? echo $GT?>"/>
<!--<input type="hidden" name="Descripcion" value="<? echo $Descripcion?>"/>-->
<table border="1" cellspacing="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;' width="100%"> 	
<tr align="center">
    <td  bgcolor="#e5e5e5" style="font-weight:bold">Parte Diente: <font color="#2A5FFF" size="+1"><? echo $ParteD?></font></td>
</tr>
<tr >        
    <td bgcolor="#e5e5e5" style="font-weight:bold">Color:<select name="Colores" onChange="CambiarParte(this,'<? echo $ParteD?>');" style=" font-size:11px; width:98px; " >
	<?    
	foreach($MatColores as $Color)
	{
		if(substr($Color[0],0,7)=="#0000FF"||substr($Color[0],0,7)=="#000000"){$CL="#FFFFFF";}else{$CL="#000000";}	        
		if(($Colores==""&&$Color[1]=="BLANCO")||$Color[0]==$Colores)
		{
			?> <option selected value="<? echo $Color[0]?>" style="background-color:<? echo substr($Color[0],0,7)?>; color:<? echo $CL?>; font-weight:bold" ><? $Descripcion=$Color[3]; echo $Color[1] ?></option><? }	
		elseif($Deshabilitar==""){?> <option value="<? echo $Color[0]?>" style="background-color:<? echo substr($Color[0],0,7)?>; color:<? echo $CL?>; font-weight:bold;" ><? echo $Color[1]; ?></option><? }
	}
	?>              
    </select>       
  </td>    
</tr>
<tr>
<td >     
    <textarea name="Descripcion" style="width:137px; height:72px; font-size:11px" readonly><? echo $Descripcion?></textarea>
</td>
</td>
</tr>
</table>
</form>    
</body>
</html>