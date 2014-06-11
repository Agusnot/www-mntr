<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	if($Guardar){
		$types=array("jpeg","gif","png","pjpeg");
		$Aux2=str_replace('\\','/',$Aux);		
		
		if(!$Edit)
		{
			$cons="select codigo from odontologia.procedimientosimgs where compania='$Compania[0]' order by Codigo desc";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$Codigo=$fila[0];
			if(!$Codigo){$Codigo=1;}else{$Codigo++;}			
			$cons="select nombre from odontologia.procedimientosimgs where compania='$Compania[0]' and nombre='$Nombre'";
			$res=ExQuery($cons);
			if(ExNumRows($res)>0)
			{
				?><script language="javascript">alert("Este nombre ya ha sido registrado!!!");</script><?
			}
			else
			{
				$cons="select Cup from odontologia.procedimientosimgs where compania='$Compania[0]' and cup='$Cup'";
				$res=ExQuery($cons);
				if(ExNumRows($res)>0)
				{
					?><script language="javascript">alert("Este Cup ya ha sido registrado!!!");</script><?
				}
				else
				{
					$cons="select ruta from odontologia.rutaimgs where compania='$Compania[0]'";
					$res=ExQuery($cons); 					
					$fila=ExFetch($res);
					$Rr=$fila[0];
					$cons1="select ruta from odontologia.procedimientosimgs where compania='$Compania[0]' 
					and ruta='".$fila[0].$_FILES['Ruta']['name']."'";				
					$res1=ExQuery($cons1);
					if(ExNumRows($res1)>0)
					{
						?><script language="javascript">alert("Esta imagen ya ha sido registrada!!!");</script><?
					}				
					else
					{	
						if($Rr)
						{														
							if (is_uploaded_file($_FILES['Ruta']['tmp_name'])) 
							{
								$filetype = $_FILES['Ruta']['type'];
								$type = substr($filetype, (strpos($filetype,"/"))+1);	
								$ne=explode(".",$_FILES['Ruta']['name']);
								$cne=count($ne);							
								$ext=$ne[$cne-1];
								if(in_array($type, $types))
								{													
									copy($_FILES['Ruta']['tmp_name'], $_FILES['Ruta']['name']); 	
									$serv=$_SERVER['DOCUMENT_ROOT'];			
									//copy("$serv/HistoriaClinica/Odontologia/".$_FILES['Ruta']['name'],"$serv".$Rr.$_FILES['Ruta']['name']);
									copy("$serv/HistoriaClinica/Odontologia/".$_FILES['Ruta']['name'],"$serv".$Rr.$Cup.".".$ext);
									//echo "/var/www/html".$fila[0].$_FILES['Ruta']['name'];
									unlink("$serv/HistoriaClinica/Odontologia/".$_FILES['Ruta']['name']);					
									//$Aux2=$Rr.$_FILES['Ruta']['name'];
									$Aux2=$Rr.$Cup.".".$ext;
									if(!$FormaRealizacion)$FormaRealizacion=0;
									$cons="insert into odontologia.procedimientosimgs(compania,codigo,nombre,tipo,ruta,usuario,fechacrea,cup,formarealizacion,finalidadprocedimiento,estadoimg) 
									values ('$Compania[0]','$Codigo','$Nombre','Procedimiento','$Aux2','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Cup',$FormaRealizacion,$FinalidadProcedimiento,'$Estado')";							
									$res=ExQuery($cons);
									//echo "aux --> ".$Aux;
									?><script language="javascript">location.href="ImgsOdontologia.php?DatNameSID=<? echo $DatNameSID?>";</script><?
								}
								else
								{
									?><script language="javascript">alert("El archivo no es tipo jpg!!!");</script><?
								}
							}
							else
							{
								?><script language="javascript">alert("No se pudo subir el archivo!!!");</script><?
							}
						}
						else
						{
							?><script language="javascript">alert("No se ha configurado la ruta destino de las imagenes odontologicas!!!");</script><?
						}									
					}
				}
			}			
		}
		else
		{				
			$cons="select nombre from odontologia.procedimientosimgs where compania='$Compania[0]' and nombre='$Nombre' and Nombre!='$NomAnt'";
			$res=ExQuery($cons);
			if(ExNumRows($res)>0)
			{
				?><script language="javascript">alert("Este nombre ya ha sido registrado!!!");</script><?
			}
			else
			{
				$cons="select Cup from odontologia.procedimientosimgs where compania='$Compania[0]' and cup='$Cup' and Cup!='$CupAnt'";
				$res=ExQuery($cons);
				if(ExNumRows($res)>0)
				{
					?><script language="javascript">alert("Este Cup ya ha sido registrado!!!");</script><?
				}
				else
				{
					if($Ruta)
					{
						$cons="select ruta from odontologia.rutaimgs where compania='$Compania[0]'";
						$res=ExQuery($cons); 					
						$fila=ExFetch($res);
						$Rr=$fila[0];
						$cons1="select ruta from odontologia.procedimientosimgs where compania='$Compania[0]' and 
						ruta='".$fila[0].$_FILES['Ruta']['name']."' and ruta!='$RutaAnt'";
						//echo $RutaAnt;		
						$res1=ExQuery($cons1);
						if(ExNumRows($res1)>0)
						{
							?><script language="javascript">alert("Esta imagen ya ha sido registrada!!!");</script><?
						}
						else
						{						
							if($Rr)
							{							
								if (is_uploaded_file($_FILES['Ruta']['tmp_name'])) 
								{
									$filetype = $_FILES['Ruta']['type'];
									$type = substr($filetype, (strpos($filetype,"/"))+1);
									$ne=explode(".",$_FILES['Ruta']['name']);
									$cne=count($ne);							
									$ext=$ne[$cne-1];
									//strpos($_FILES['Ruta']['type'], "image")
									//echo $type;
									if(in_array($type, $types))
									{	
										$serv=$_SERVER['DOCUMENT_ROOT'];											
										if (strtoupper(substr(PHP_OS,0, 3)) == "WIN") 
										{
											unlink("$serv/$RutaAnt");					
										}
										else
										{
											unlink("$serv/$RutaAnt");
										}
										//aki
										copy($_FILES['Ruta']['tmp_name'], $_FILES['Ruta']['name']); 
																					
										//copy("$serv/HistoriaClinica/Odontologia/".$_FILES['Ruta']['name'],"$serv".$Rr.$_FILES['Ruta']['name']);										
										copy("$serv/HistoriaClinica/Odontologia/".$_FILES['Ruta']['name'],"$serv".$Rr.$Cup.".".$ext);										
										unlink("$serv/HistoriaClinica/Odontologia/".$_FILES['Ruta']['name']);															
										
										//$Aux2=$Rr.$_FILES['Ruta']['name'];
										$Aux2=$Rr.$Cup.".".$ext;
										//echo "aux2 --> ".$Aux2;
										if(!$FormaRealizacion)$FormaRealizacion=0;
										$cons="update odontologia.procedimientosimgs set nombre='$Nombre',
										ruta='$Aux2',usuariomod='$usuario[1]',
										fechamod='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]', Cup='$Cup',
										FormaRealizacion=$FormaRealizacion, FinalidadProcedimiento=$FinalidadProcedimiento, estadoimg='$Estado'
										where codigo='$Codigo' and nombre='$NomAnt'";
										//echo "<br>$cons";
										$res=ExQuery($cons);
										?><script language="javascript">location.href="ImgsOdontologia.php?DatNameSID=<? echo $DatNameSID?>";</script><?
									}
									else
									{
										?><script language="javascript">alert("El archivo no es tipo jpg!!!");</script><?
									}
								}
								else
								{
									?><script language="javascript">alert("No se pudo subir el archivo!!!");</script><?
								}
							}
							else
							{
								?><script language="javascript">alert("No se ha configurado la ruta destino de las imagenes odontologicas!!!");</script><?
							}
						}				
					}
					else
					{
						if(!$FormaRealizacion)$FormaRealizacion=0;
						$cons="update odontologia.procedimientosimgs set nombre='$Nombre',
						usuariomod='$usuario[1]',fechamod='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
						Cup='$Cup', FormaRealizacion=$FormaRealizacion, FinalidadProcedimiento=$FinalidadProcedimiento, estadoimg='$Estado' 
						where codigo='$Codigo' and nombre='$NomAnt'";
						//echo "<br>$cons";
						$res=ExQuery($cons);
						?><script language="javascript">location.href="ImgsOdontologia.php?DatNameSID=<? echo $DatNameSID?>";</script><?
					}										
				}
			}
		}		
	}		
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Nombre.value==""){alert("Debe digitar un nombre!!!"); return false;}
		if(document.FORMA.Cup.value==""){alert("Ingrese un Codigo de Cup!!!"); return false;}
		if(document.FORMA.QoNQ.value=="")
		{			
			
		}
		else
		{
			//alert(document.FORMA.QoNQ.value);
			if(document.FORMA.QoNQ.value!="0")
			if(document.FORMA.FormaRealizacion.value==""){alert("Seleccione la Forma de Realizacion!!!"); return false;}
		}
		if(document.FORMA.FinalidadProcedimiento.value==""){alert("Seleccione la Finalidad del Procedimiento!!!"); return false;}
		if(document.FORMA.Edit.value=="")
		{
			if(document.FORMA.Ruta.value==""){alert("Debe seleccionar una imagen!!!"); return false;}
			else{
				document.FORMA.Aux.value=document.FORMA.Ruta.value;
				document.FORMA.Guardar.value='1';
				document.FORMA.submit();
			}
		}
		else
		{
			if(document.FORMA.Ruta.value=="")
			{
				document.FORMA.Aux.value=document.FORMA.RutaAnt.value;
				document.FORMA.Guardar.value='1';
				document.FORMA.submit();
			}
			else{
				document.FORMA.Aux.value=document.FORMA.Ruta.value;
				document.FORMA.Guardar.value='1';
				document.FORMA.submit();
			}
		}
	}
	function BuscarCup(Cod,Nom)
	{
		frames.FrameOpener.location.href="BuscarCup.php?DatNameSID=<? echo $DatNameSID?>&Codigo="+Cod+"&Nombre="+Nom;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=130;
		document.getElementById('FrameOpener').style.left=10;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='100%';
		document.getElementById('FrameOpener').style.height='80%';
	}
	function CambiarTamTxt(Objeto)
	{
		if(Objeto.value.length>25){Objeto.size=Objeto.value.length+9;}
		else{Objeto.size=20;}		
	}
</script>
</head>
<body background="/Imgs/Fondo.jpg" onLoad="CambiarTamTxt(document.FORMA.NomCup);">
<form name="FORMA" method="post" enctype="multipart/form-data" onSubmit="return Validar()">
<?
if($Edit)
{
	$cons="select procedimientosimgs.codigo,procedimientosimgs.nombre,ruta,cup,cups.nombre,FormaRealizacion,FinalidadProcedimiento,
	Quirurgico,estadoimg from 
	odontologia.procedimientosimgs,contratacionsalud.cups where procedimientosimgs.compania='$Compania[0]' and cups.Compania='$Compania[0]'
	and Procedimientosimgs.Compania=Cups.Compania and cups.codigo=procedimientosimgs.cup and procedimientosimgs.codigo='$Codigo'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	//echo $cons;
	if(!$Codigo){$Codigo=$fila[0];}
	if(!$Nombre){$Nombre=$fila[1];}
	if(!$NomAnt){$NomAnt=$fila[1];}
	if(!$RutaAnt){$RutaAnt=$fila[2];}
	if(!$Cup){$Cup=$fila[3];}
	if(!$CupAnt){$CupAnt=$fila[3];}
	if(!$NomCup){$NomCup=$fila[4];}
	if(!$FormaRealizacion){$FormaRealizacion=$fila[5];}
	if(!$FinalidadProcedimiento){$FinalidadProcedimiento=$fila[6];}
	if(!$QoNQ){$QoNQ=$fila[7];}
	if(!$Estado){$Estado=$fila[8];}
	$Nota="<font face='Tahoma' color='#0066FF' size='-1' >Nota: si No desea Cambiar la Imagen, No seleccione ninguna!!!</font><br>";
	$Titulo="Editar Procedimiento";	
}
else
{
	$Titulo="Nuevo Procedimiento";
}
?>
<input type="hidden" name="QoNQ" value="<? echo $QoNQ?>"/>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<input type="hidden" name="Edit" value="<? echo $Edit?>"/>
<input type="hidden" name="Codigo" value="<? echo $Codigo?>"/>
    <input type="hidden" name="RutaAnt" value="<? echo $RutaAnt?>"/>
    <input type="hidden" name="NomAnt" value="<? echo $NomAnt?>"/>
    <input type="hidden" name="CupAnt" value="<? echo $CupAnt?>"/>
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">	
    <tr><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="2"><? echo $Titulo?></td></tr>
    <tr><td  bgcolor="#e5e5e5" style="font-weight:bold">Nombre</td>
    	<td><input type="text" name="Nombre" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" value="<? echo $Nombre?>" style="width:100%"/></td>
	</tr>    
    <tr><td  bgcolor="#e5e5e5" style="font-weight:bold">CUP</td>
    	<td><input type="text" name="Cup" value="<? echo $Cup?>" size="8" onFocus="BuscarCup(this.value,NomCup.value)" onKeyDown="xNumero(this);BuscarCup(this.value,NomCup.value)" 
                onKeyUp="xNumero(this);BuscarCup(this.value,NomCup.value)" readonly/>
        	<input type="text" name="NomCup" onFocus="CambiarTamTxt(this);" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" value="<? echo $NomCup?>" size="25" onChange="CambiarTamTxt(this);" readonly/>
        </td>
	</tr>
    <?
	if($QoNQ)
	{
		//echo "si quirurgico";
		?>
    <tr><td bgcolor="#e5e5e5" style="font-weight:bold">Forma de Realizaci&oacute;n</td>
    <td>
    <?
    $cons="Select";
	?>
    <select name="FormaRealizacion">
    <option value=""></option>
    <?
    $cons="Select codigo,forma from salud.formarquirurgico order by Forma";
	$res=ExQuery($cons);	
	while($fila=ExFetch($res))
	{
		if($fila[0]==$FormaRealizacion)
		{
			echo "<option value='$fila[0]' selected>$fila[1]</option>";
		}
		else
		{
			echo "<option value='$fila[0]' >$fila[1]</option>";	
		}
	}
	?>
    </select>
    </td>
    </tr>
    <?
	}?>
    <tr><td bgcolor="#e5e5e5" style="font-weight:bold">Finalidad</td>
    <td>    
    <select name="FinalidadProcedimiento">    
    <option value=""></option>
    <?
    $cons="Select codigo,finalidad from salud.finalidadesact where tipo=2 order by Finalidad";
	$res=ExQuery($cons);	
	while($fila=ExFetch($res))
	{
		if($fila[0]==$FinalidadProcedimiento)
		{
			echo "<option value='$fila[0]' selected>$fila[1]</option>";
		}
		else
		{
			echo "<option value='$fila[0]' >$fila[1]</option>";	
		}
	}
	?>
    </select>
    </td>
    </tr>
    <tr><td    bgcolor="#e5e5e5" style="font-weight:bold">Imagen</td>
    	<td><input type="file" name="Ruta" style="width:100%" />
	        <input type="hidden" name="Aux">
        </td>
    </tr>  
    <tr>
    <td    bgcolor="#e5e5e5" style="font-weight:bold">Estado</td>
    	<td>
        <select name="Estado">
        <option value="Activo" <? if($Estado=="Activo"||$Estado==""){ echo "selected";}?>>Activo</option>
        <option value="Inactivo" <? if($Estado=="Inactivo"){ echo "selected";}?>>Inactivo</option>
        </select>
        </td>
    </tr>    
</table>
<center>
<? 
echo $Nota;
?>
<input type="submit" value="Guardar" />
<input type="button" value="Cancelar" onClick="location.href='ImgsOdontologia.php?DatNameSID=<? echo $DatNameSID?>'"/>
</center>

<input type="hidden" name="Guardar">
</form>    
</body>
</html>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe> 
