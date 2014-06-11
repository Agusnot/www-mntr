<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$Anio=$ND[year];
	if($ND[mon]<10){$Mes="0".$ND[mon];}else{$Mes=$ND[mon];}	
	if($ND[mday]<10){$Dia="0".$ND[mday];}else{$Dia=$ND[mday];}				
	//---
	//echo $usuario[1];
	if($CedImpMasv){$Identificacion=$CedImpMasv;}
	//if($Identificacion){$Identificacion=$Identificacion;}
	if($Identificacion!='')
	{
		//echo "entra $Identificacion";
		$cons9="Select * from Central.Terceros where Identificacion='$Identificacion' and compania='$Compania[0]'";
		//echo $cons9;
		$res9=ExQuery($cons9);echo ExError();
		$fila9=ExFetch($res9);

		//$Identificacion=$fila9[0];
		$n=1;
		for($i=1;$i<=ExNumFields($res9);$i++)
		{
			$n++;
			$Pac[$n]=$fila9[$i];
			//echo "<br>$n=$Pac[$n]";
		}
		//echo $Pac[47];
		//session_register("Paciente");
	}
	$cons="Select fecha from odontologia.odontogramaproc where Compania='$Compania[0]' and Identificacion='$Identificacion' order by fecha asc 
	limit 1";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$FechaPriReg=$fila[0];	
	$cons="Select primape, segape, primnom, segnom, tipodoc, sexo, fecnac, direccion, Departamentos.departamento, municipio, Telefono, eps 
	from Central.Terceros, Central.Departamentos where Compania='$Compania[0]' and Identificacion='$Identificacion' 
	and Departamentos.Departamento=Terceros.Departamento";
	//echo $cons;
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Paci="$fila[0] $fila[1] $fila[2] $fila[3]";
	//echo $Paci;
	$TipoId=$fila[4];$Genero=$fila[5];$FechaNac=$fila[6];$Direccion=$fila[7];$Departamento=$fila[8];$Municipio=$fila[9];$Telefono=$fila[10];
	$Entidad=$fila[11];
	$A=substr($FechaNac,0,4);$M=substr($FechaNac,5,2);$D=substr($FechaNac,8,2);
	$cons="Select Primape from central.terceros where compania='$Compania[0]' and identificacion='$Entidad'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Entidad=$fila[0];
	$cons="Select Nombre,Cedula from Central.Usuarios,Odontologia.OdontogramaProc where Compania='$Compania[0]' 
	and Identificacion='$Identificacion' and Odontogramaproc.Medico=Usuarios.Usuario and fecha='$Fecha' limit 1";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Medico=$fila[0]; $Cedula=$fila[1];
	if($TipoOdontograma=="Inicial"){ $PartCon="and tipoodonto='$TipoOdontograma'"; }
	$cons="Select identificacion, cuadrante, diente, zonad, procedimiento, fecha, tipoodonto, denticion, imagenzona, imagenproc,fechaant
	from odontologia.odontogramaproc where Compania='$Compania[0]' and Identificacion='$Identificacion' and Fecha='$Fecha' $PartCon
	and Eliminar is NULL order by fechaant,fecha, cuadrante, diente, zonad";
	$res=ExQuery($cons);	
	while($fila=ExFetch($res))
	{
		//if($fila[5]!=""){$TmpMatFechas[$fila[5]]=$fila[5];}
		if($fila[1]=="1")
		{			
			if(!$fila[10]){$DisaG="";}
			$TmpMatCuadrante1[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9]);
			$TmpMatCuadrante1Proc[$fila[5]][$fila[2]][$fila[3]][$fila[4]]=$fila[9];
		}
		else
		{
			if($fila[1]=="2")
			{				
				if(!$fila[10]){$DisaG="";}
				$TmpMatCuadrante2[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9]);
				$TmpMatCuadrante2Proc[$fila[5]][$fila[2]][$fila[3]][$fila[4]]=$fila[9];
			}
			else
			{
				if($fila[1]=="3")
				{					
					if(!$fila[10]){$DisaG="";}
					$TmpMatCuadrante3[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9]);
					$TmpMatCuadrante3Proc[$fila[5]][$fila[2]][$fila[3]][$fila[4]]=$fila[9];
				}	
				else
				{
					if($fila[1]=="4")
					{						
						if(!$fila[10]){$DisaG="";}
						$TmpMatCuadrante4[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9]);
						$TmpMatCuadrante4Proc[$fila[5]][$fila[2]][$fila[3]][$fila[4]]=$fila[9];
					}
					else
					{
						if($fila[1]=="5")
						{							
							if(!$fila[10]){$DisaG="";}
							$TmpMatCuadrante5[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9]);
							$TmpMatCuadrante5Proc[$fila[5]][$fila[2]][$fila[3]][$fila[4]]=$fila[9];
						}	
						else
						{
							if($fila[1]=="6")
							{								
								if(!$fila[10]){$DisaG="";}
								$TmpMatCuadrante6[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9]);
								$TmpMatCuadrante6Proc[$fila[5]][$fila[2]][$fila[3]][$fila[4]]=$fila[9];
							}	
							else
							{
								if($fila[1]=="7")
								{									
									if(!$fila[10]){$DisaG="";}
									$TmpMatCuadrante7[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9]);
									$TmpMatCuadrante7Proc[$fila[5]][$fila[2]][$fila[3]][$fila[4]]=$fila[9];
								}	
								else
								{
									if($fila[1]=="8")
									{										
										if(!$fila[10]){$DisaG="";}
										$TmpMatCuadrante8[$fila[5]][$fila[2]][$fila[3]]=array($fila[5],$fila[1],$fila[2],$fila[3],$fila[4],$fila[6],$fila[7],$fila[8],$fila[9]);
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
?>		
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
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
			Alto=parseInt((document.getElementById('TABLA').clientHeight+158));
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
			Alto=parseInt((document.getElementById('TABLA').clientHeight+158));
			Ancho=parseInt((document.getElementById('TABLA').clientWidth)/1.59)+ValInc;			
			document.getElementById('Etiq4').style.position='absolute';
			document.getElementById('Etiq4').style.top=Alto;
			document.getElementById('Etiq4').style.left=Ancho;
			document.getElementById('Etiq4').style.display='';
			document.getElementById('Etiq4').style.width='10px';		
			document.FORMA.Etiq4.value=Nombre;			
		}
		if(Tipo==6)
		{			
			Alto=parseInt((document.getElementById('TABLA').clientHeight)/document.getElementById('TABLA').clientHeight)+300;
			Ancho=parseInt((document.getElementById('TABLA').clientWidth)/2.15)+ValInc;			
			document.getElementById('Etiq6').style.position='absolute';
			document.getElementById('Etiq6').style.top=Alto;
			document.getElementById('Etiq6').style.left=Ancho;
			document.getElementById('Etiq6').style.display='';
			document.getElementById('Etiq6').style.width='10px';		
			document.FORMA.Etiq6.value=Nombre;			
		}
		if(Tipo==7)
		{			
			Alto=parseInt((document.getElementById('TABLA').clientHeight)/1)+310;
			Ancho=parseInt((document.getElementById('TABLA').clientWidth)/2.15)+ValInc;			
			document.getElementById('Etiq7').style.position='absolute';
			document.getElementById('Etiq7').style.top=Alto;
			document.getElementById('Etiq7').style.left=Ancho;
			document.getElementById('Etiq7').style.display='';
			document.getElementById('Etiq7').style.width='10px';		
			document.FORMA.Etiq7.value=Nombre;			
		}				
		
	}
	function CalculaEdad(A,M,D,AA,MA,DA)
	{
		var Edad;
		if(A!=""&&M!=""&&D!="")
		{		
			Edad=AA-A;
			if(MA==M)
			{
				if(DA<D)
				{
					Edad=Edad-1;
				}
			}
			else
			{
				if(MA<M)
				{
					Edad=Edad-1;
				}
			}
			if(Edad>100){Edad="";}
			document.FORMA.Edad.value=Edad+' Años';
			//alert(Edad);
		}
	}		
	</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" >
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD;?>"/>
<input type="hidden" name="TipoOdontograma" value="<? echo $TipoOdontograma?>"/>
<input type="hidden" name="Fecha" value="<? echo $Fecha?>"/>
<table border="0" bordercolor="#e5e5e5" style='font : normal normal small-caps 15px Tahoma;' align="center" width="100%">
<tr>
<td><img src="/Imgs/Logo.jpg" alt="" width="50" height="50" ></td>
<td colspan="3" align="center" width="85%" >
	<center><font style="font : 16px Tahoma;font-weight:bold">
    <? echo strtoupper($Compania[0])?><br /></font>
    <font style="font : 13px Tahoma;">
    <? echo $Compania[1]?><br /><? echo "$Compania[2] $Compania[3]"?><br /></font>
    </center>
</td>
<td>
 <table border="0" style='font : normal normal small-caps 13px Tahoma;' align="center">
    <tr align="center" ><td bgcolor="#e5e5e5" style="font-weight:bold">Historia No.</td></tr>
    <tr align="center"><td align="center" style="font-weight:bold"><? echo $Pac[21]?></td></tr>
    <tr align="center" ><td bgcolor="#e5e5e5" style="font-weight:bold">Fecha de Apertura</td></tr>
    <tr align="center"><td align="center" style="font-weight:bold"><? echo $FechaPriReg?></td></tr>                
 </table>
</td>
</tr>
</table>
<table border="0" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center" width="100%">
<tr>
<td colspan="5" align="center" bgcolor="#e5e5e5" style="font-weight:bold">Datos del Paciente</td>
</tr>
<tr>
<td colspan="5"><strong>Apellidos y Nombres del Paciente:</strong>&nbsp;<? echo $Paci;?></td>
</tr>
<tr>
<td ><strong>Identificaci&oacute;n:</strong> &nbsp;<? echo $Identificacion?></td>
<td><strong>Tipo:</strong> &nbsp;<? echo $TipoId?></td>
<td><strong>Genero:</strong> &nbsp;<? echo $Genero?></td>
<td ><strong>Fecha Nacimiento:</strong> &nbsp;<? echo $FechaNac?></td>
<td ><strong>Edad:</strong> &nbsp;<input type="text" name="Edad" size="6" style="font : normal normal small-caps 11px Tahoma;border-color:transparent; background-color:transparent" readonly/></td>
</tr>
<tr>
<td colspan="3"><strong>Direcci&oacute;n:</strong>&nbsp; <? echo $Direccion;?></td>
<td ><strong>Departamento:</strong>&nbsp; <? echo $Departamento;?></td>
<td ><strong>Municipio:</strong>&nbsp; <? echo $Municipio;?></td>
</tr>
<tr>
<td ><strong>Telefono:</strong>&nbsp; <? echo $Telefono;?></td>
<td colspan="4" ><strong>Entidad:</strong>&nbsp; <? echo $Entidad;?></td>
</tr>
<tr>
<td colspan="5" align="center" bgcolor="#e5e5e5" style="font-weight:bold">Datos del Odontologo</td>
</tr>
<tr>
<td colspan="3"><strong>Nombres y Apellidos:</strong>&nbsp;<? echo $Medico;?></td>
<td colspan="2"><strong>Identificacion:</strong>&nbsp;<? echo $Cedula;?></td>
</tr>
<tr>
<td colspan="5" align="center" bgcolor="#e5e5e5" style="font-weight:bold">Datos del Odontograma</td>
</tr>
<tr>
<td colspan="3"><strong>Tipo:</strong>&nbsp;<? echo $TipoOdontograma;?></td>
<td colspan="2"><strong>Fecha Odontograma:</strong>&nbsp;<? echo $Fecha;?></td>
</tr>
</table>
<br />
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
					?><td id="<? echo "D".$d?>"  >                        	
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
							?><img src="<? echo $TmpMatCuadrante1[$Fecha][$d][$Let][7]?>" style="position:absolute;"  height="60" width="60"><?											
						}
						else
						{								
							if($Let=="A")
							{
								?><img src="/Imgs/Odontologia/P3.gif" style="position:absolute;" height="60" width="60"><?
							}
							else
							{									
								if($Let=="B")
								{
									?><img src="/Imgs/Odontologia/P1.gif" style="position:absolute;" height="60" width="60"><?
								}	
								else
								{										
									if($Let=="C")
									{
										?><img src="/Imgs/Odontologia/P4.gif" style="position:absolute;" height="60" width="60"><?
									}	
									else
									{											
										if($Let=="D")
										{
											?><img src="/Imgs/Odontologia/P2.gif" style="position:absolute;" height="60" width="60"><?	
										}	
										else
										{
											if($Let=="E")
											{
												?><img src="/Imgs/Odontologia/P5.gif" style="position:absolute;" height="60" width="60"><?
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
									?><img src="<? echo $ImgProc?>" style="position:absolute;" height="60" width="60"><?										
								}
							}							
						}
					}
					?>  
					<img src="/Imgs/Odontologia/fondo.gif" height="60" width="60">                      						
					</td>
					<?
				}
			}
			else
			{
				for($d=18;$d>10;$d--)
				{
					?><td id="<? echo "D".$d?>"  >						
						<img src="/Imgs/Odontologia/Diente_P.gif" style="position:absolute; " height="60" width="60">                   
						<img src="/Imgs/Odontologia/fondo.gif" height="60" width="60">
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
					?><td <? if($d<56){?> id="<? echo "D".$d?>"  <? } ?>>
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
							?><img src="<? echo $TmpMatCuadrante5[$Fecha][$d][$Let][7]?>" style="position:absolute;"  height="50" width="50"><?								
						}
						else
						{								
							if($Let=="A")
							{
								?><img src="/Imgs/Odontologia/P3.gif" style="position:absolute;" height="50" width="50"><?
							}
							else
							{									
								if($Let=="B")
								{
									?><img src="/Imgs/Odontologia/P1.gif" style="position:absolute;" height="50" width="50"><?
								}	
								else
								{										
									if($Let=="C")
									{
										?><img src="/Imgs/Odontologia/P4.gif" style="position:absolute;" height="50" width="50"><?
									}	
									else
									{											
										if($Let=="D")
										{
											?><img src="/Imgs/Odontologia/P2.gif" style="position:absolute;" height="50" width="50"><?	
										}	
										else
										{
											if($Let=="E")
											{
												?><img src="/Imgs/Odontologia/P5.gif" style="position:absolute;" height="50" width="50"><?
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
									?><img src="<? echo $ImgProc?>" style="position:absolute;" height="50" width="50"><?										
								}
							}
						}
					}						
					?>
					<img src="/Imgs/Odontologia/fondo.gif" height="50" width="50">	
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
					?><td <? if($d<56){?> id="<? echo "D".$d?>" <? } ?>>
						<? if($d<56){?>						
						<img src="/Imgs/Odontologia/Diente_P.gif" style="position:absolute; " height="50" width="50">                  
						<img src="/Imgs/Odontologia/fondo.gif" height="50" width="50">
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
    <td rowspan="3" style="width:1px;"><div id="lv" style="border-left:3px solid black; height:310px; background-color:transparent">&nbsp;</div> </td>    <!-- linea vertical-->    
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
					?><td id="<? echo "D".$d?>"  >
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
							?><img src="<? echo $TmpMatCuadrante2[$Fecha][$d][$Let][7]?>" style="position:absolute;"  height="60" width="60"><?	
						}
						else
						{								
							if($Let=="A")
							{
								?><img src="/Imgs/Odontologia/P3.gif" style="position:absolute;" height="60" width="60"><?
							}
							else
							{									
								if($Let=="B")
								{
									?><img src="/Imgs/Odontologia/P1.gif" style="position:absolute;" height="60" width="60"><?
								}	
								else
								{										
									if($Let=="C")
									{
										?><img src="/Imgs/Odontologia/P4.gif" style="position:absolute;" height="60" width="60"><?
									}	
									else
									{											
										if($Let=="D")
										{
											?><img src="/Imgs/Odontologia/P2.gif" style="position:absolute;" height="60" width="60"><?	
										}	
										else
										{
											if($Let=="E")
											{
												?><img src="/Imgs/Odontologia/P5.gif" style="position:absolute;" height="60" width="60"><?
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
									?><img src="<? echo $ImgProc?>" style="position:absolute;" height="60" width="60"><?										
								}
							}
						}
					}					
					?>
					<img src="/Imgs/Odontologia/fondo.gif" height="60" width="60">							
					</td>
					<?
				}
			}
			else
			{
				for($d=21;$d<29;$d++)
				{
					?><td id="<? echo "D".$d?>"  >						
						<img src="/Imgs/Odontologia/Diente_P.gif" style="position:absolute; " height="60" width="60">                    
						<img src="/Imgs/Odontologia/fondo.gif" height="60" width="60">
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
					?><td <? if($d<66){?> id="<? echo "D".$d?>"  <? } ?>>
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
							?><img src="<? echo $TmpMatCuadrante6[$Fecha][$d][$Let][7]?>" style="position:absolute;"  height="50" width="50"><?	
						}
						else
						{								
							if($Let=="A")
							{
								?><img src="/Imgs/Odontologia/P3.gif" style="position:absolute;" height="50" width="50"><?
							}
							else
							{									
								if($Let=="B")
								{
									?><img src="/Imgs/Odontologia/P1.gif" style="position:absolute;" height="50" width="50"><?
								}	
								else
								{										
									if($Let=="C")
									{
										?><img src="/Imgs/Odontologia/P4.gif" style="position:absolute;" height="50" width="50"><?
									}	
									else
									{											
										if($Let=="D")
										{
											?><img src="/Imgs/Odontologia/P2.gif" style="position:absolute;" height="50" width="50"><?	
										}	
										else
										{
											if($Let=="E")
											{
												?><img src="/Imgs/Odontologia/P5.gif" style="position:absolute;" height="50" width="50"><?
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
									?><img src="<? echo $ImgProc?>" style="position:absolute;" height="50" width="50"><?										
								}
							}
						}
					}					
					?>
					<img src="/Imgs/Odontologia/fondo.gif" height="50" width="50">	
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
					?><td <? if($d<66){?> id="<? echo "D".$d?>"  <? } ?>>
						<? if($d<66){?>						
						<img src="/Imgs/Odontologia/Diente_P.gif" style="position:absolute; " height="50" width="50">                     
						<img src="/Imgs/Odontologia/fondo.gif" height="50" width="50">
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
<td colspan="3" style="height:3px" ><div id="lh" style=" border-bottom:3px solid black; width::100%; background-color:transparent"></div><!--<hr width="100%" size="3" color="#000000" style="color:#000000"/>--></td> <!-- linea horizontal-->
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
					?><td <? if($d<86){?> id="<? echo "D".$d?>"  <? } ?>>
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
							?><img src="<? echo $TmpMatCuadrante8[$Fecha][$d][$Let][7]?>" style="position:absolute;"  height="50" width="50"><?	
						}
						else
						{								
							if($Let=="A")
							{
								?><img src="/Imgs/Odontologia/P3.gif" style="position:absolute;" height="50" width="50"><?
							}
							else
							{									
								if($Let=="B")
								{
									?><img src="/Imgs/Odontologia/P1.gif" style="position:absolute;" height="50" width="50"><?
								}	
								else
								{										
									if($Let=="C")
									{
										?><img src="/Imgs/Odontologia/P4.gif" style="position:absolute;" height="50" width="50"><?
									}	
									else
									{											
										if($Let=="D")
										{
											?><img src="/Imgs/Odontologia/P2.gif" style="position:absolute;" height="50" width="50"><?	
										}	
										else
										{
											if($Let=="E")
											{
												?><img src="/Imgs/Odontologia/P5.gif" style="position:absolute;" height="50" width="50"><?
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
									?><img src="<? echo $ImgProc?>" style="position:absolute;" height="50" width="50"><?										
								}
							}							
						}
					}					
					?>
					<img src="/Imgs/Odontologia/fondo.gif" height="50" width="50">	
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
					?><td <? if($d<86){?> id="<? echo "D".$d?>"  <? } ?>>
						<? if($d<86){?>						
						<img src="/Imgs/Odontologia/Diente_P.gif" style="position:absolute; " height="50" width="50">                   
						<img src="/Imgs/Odontologia/fondo.gif" height="50" width="50">
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
					?><td id="<? echo "D".$d?>"  >
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
							?><img src="<? echo $TmpMatCuadrante4[$Fecha][$d][$Let][7]?>" style="position:absolute;"  height="60" width="60"><?	
						}
						else
						{								
							if($Let=="A")
							{
								?><img src="/Imgs/Odontologia/P3.gif" style="position:absolute;" height="60" width="60"><?
							}
							else
							{									
								if($Let=="B")
								{
									?><img src="/Imgs/Odontologia/P1.gif" style="position:absolute;" height="60" width="60"><?
								}	
								else
								{										
									if($Let=="C")
									{
										?><img src="/Imgs/Odontologia/P4.gif" style="position:absolute;" height="60" width="60"><?
									}	
									else
									{											
										if($Let=="D")
										{
											?><img src="/Imgs/Odontologia/P2.gif" style="position:absolute;" height="60" width="60"><?	
										}	
										else
										{
											if($Let=="E")
											{
												?><img src="/Imgs/Odontologia/P5.gif" style="position:absolute;" height="60" width="60"><?
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
									?><img src="<? echo $ImgProc?>" style="position:absolute;" height="60" width="60"><?										
								}
							}
						}
					}					
					?>
					<img src="/Imgs/Odontologia/fondo.gif" height="60" width="60">							
					</td>
					<?
				}
			}
			else
			{
				for($d=48;$d>40;$d--)
				{
					?><td id="<? echo "D".$d?>"  >						
						<img src="/Imgs/Odontologia/Diente_P.gif" style="position:absolute; " height="60" width="60">                   
						<img src="/Imgs/Odontologia/fondo.gif" height="60" width="60">
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
					?><td <? if($d<76){?> id="<? echo "D".$d?>"  <? } ?>>
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
							?><img src="<? echo $TmpMatCuadrante7[$Fecha][$d][$Let][7]?>" style="position:absolute;"  height="50" width="50"><?	
						}
						else
						{								
							if($Let=="A")
							{
								?><img src="/Imgs/Odontologia/P3.gif" style="position:absolute;" height="50" width="50"><?
							}
							else
							{									
								if($Let=="B")
								{
									?><img src="/Imgs/Odontologia/P1.gif" style="position:absolute;" height="50" width="50"><?
								}	
								else
								{										
									if($Let=="C")
									{
										?><img src="/Imgs/Odontologia/P4.gif" style="position:absolute;" height="50" width="50"><?
									}	
									else
									{											
										if($Let=="D")
										{
											?><img src="/Imgs/Odontologia/P2.gif" style="position:absolute;" height="50" width="50"><?	
										}	
										else
										{
											if($Let=="E")
											{
												?><img src="/Imgs/Odontologia/P5.gif" style="position:absolute;" height="50" width="50"><?
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
									?><img src="<? echo $ImgProc?>" style="position:absolute;" height="50" width="50"><?										
								}
							}
						}
					}					
					?>
					<img src="/Imgs/Odontologia/fondo.gif" height="50" width="50">	
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
					?><td <? if($d<76){?> id="<? echo "D".$d?>"  <? } ?>>
						<? if($d<76){?>						
						<img src="/Imgs/Odontologia/Diente_P.gif" style="position:absolute; " height="50" width="50">                   
						<img src="/Imgs/Odontologia/fondo.gif" height="50" width="50">
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
					?><td id="<? echo "D".$d?>"  >
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
							?><script language="javascript"></script><img src="<? echo $TmpMatCuadrante3[$Fecha][$d][$Let][7]?>" style="position:absolute;"  height="60" width="60"><?	
						}
						else
						{								
							if($Let=="A")
							{
								?><img src="/Imgs/Odontologia/P3.gif" style="position:absolute;" height="60" width="60"><?
							}
							else
							{									
								if($Let=="B")
								{
									?><img src="/Imgs/Odontologia/P1.gif" style="position:absolute;" height="60" width="60"><?
								}	
								else
								{										
									if($Let=="C")
									{
										?><img src="/Imgs/Odontologia/P4.gif" style="position:absolute;" height="60" width="60"><?
									}	
									else
									{											
										if($Let=="D")
										{
											?><img src="/Imgs/Odontologia/P2.gif" style="position:absolute;" height="60" width="60"><?	
										}	
										else
										{
											if($Let=="E")
											{
												?><img src="/Imgs/Odontologia/P5.gif" style="position:absolute;" height="60" width="60"><?
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
									?><img src="<? echo $ImgProc?>" style="position:absolute;" height="60" width="60"><?										
								}
							}
						}
					}					
					?>
					<img src="/Imgs/Odontologia/fondo.gif" height="60" width="60">							
					</td>
					<?
				}
			}
			else
			{
				for($d=31;$d<39;$d++)
				{
					?><td id="<? echo "D".$d?>"  >						
						<img src="/Imgs/Odontologia/Diente_P.gif" style="position:absolute; " height="60" width="60">                   
						<img src="/Imgs/Odontologia/fondo.gif" height="60" width="60">
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
<center><font style="font : 10px Tahoma; font-style:oblique;">Impreso el <? echo "$Dia/$Mes/$Anio"?></font></center>
<div id="Etiq2" style="position:absolute;background:none;"><input type="text" name="Etiq2" style=" background-color:transparent; border:thin; font : normal normal small-caps 14px Tahoma;font-weight:bold; color:#2A7FFF;" size="4" readonly/></div>
<div id="Etiq4" style="position:absolute;background:none;"><input type="text" name="Etiq4" style=" background-color:transparent; border:thin; font : normal normal small-caps 14px Tahoma;font-weight:bold; color:#2A7FFF;" size="4" readonly/></div>
<div id="Etiq6" style="position:absolute;background:none;"><input type="text" name="Etiq6" style=" background-color:transparent; border:thin; font : normal normal small-caps 14px Tahoma;font-weight:bold; color:#2A7FFF;" size="10" readonly/></div>
<div id="Etiq7" style="position:absolute;background:none;"><input type="text" name="Etiq7" style=" background-color:transparent; border:thin; font : normal normal small-caps 14px Tahoma;font-weight:bold; color:#2A7FFF;" size="10" readonly/></div>
</form>	
<script language="javascript" type="text/javascript">

window.onresize = window.onload = function ()
{		
	CalculaEdad('<? echo $A?>','<? echo $M?>','<? echo $M?>','<? echo $Anio?>','<? echo $Mes?>','<? echo $Dia?>');
	//CreaEtiquetaH("Lingual",2);CreaEtiquetaH("Lingual",4);CreaEtiquetaH("Vestibular",6);CreaEtiquetaH("Vestibular",7);
}
</script>
</body>
</html>