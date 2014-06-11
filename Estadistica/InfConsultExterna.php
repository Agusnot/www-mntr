<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
	if($Opciones)
	{
		$cons="Select Tipo,Archivo from Central.Reportes where Id=$Opciones and Clase='$Clase' and Modulo='Consulta Externa'";			
		$res=ExQuery($cons);	
		//echo $cons;
		$fila=ExFetch($res);
		$Tipo=$fila[0]; 
		$NomArchivo=$fila[1];
	}
	$cons="Select Id,Nombre from Central.Reportes where Modulo='Consulta Externa' and Clase='$Clase' order by Clase,Nombre";
	$res=ExQuery($cons);
	//echo $cons;
	while($fila=ExFetch($res))
	{
		$MatNombres[$fila[0]]=array($fila[0],$fila[1]);
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" >
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' >	
    <tr>
    <td rowspan="2">
        <select name="Opciones" onChange="location.href='InfConsultExterna.php?DatNameSID=<? echo $DatNameSID?>&Opciones='+this.value+'&Clase=<? echo $Clase?>'">
        <option value=""></option>
		<?
        	foreach($MatNombres as $Opcion)
			{
				if($Opcion[0]==$Opciones){echo "<option selected value='$Opcion[0]'>$Opcion[1]</option>";}
				else{echo "<option value='$Opcion[0]'>$Opcion[1]</option>";}
			}
		?>              
    	</select>    
  	</td> 
<?	
	if($Tipo==1){
		if(!$Anio){$Anio=$ND[year];}
		if(!$MesIni){$MesIni=$ND[mon];}
		if(!$MesFin){$MesFin=$ND[mon];}
		if(!$DiaIni){$DiaIni=1;}
		if(!$DiaFin){$DiaFin=$ND[mday];}?>
			<td bgcolor="#e5e5e5" colspan="3" align="center">Periodo Inicial</td><td bgcolor="#e5e5e5" colspan="2" align="center">Periodo Final</td>
            <td bgcolor="#e5e5e5">Especialidad</td><td bgcolor="#e5e5e5"></td></tr>
        <tr>        
       		<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
        	<td>
            <?	$cons="select anio from central.anios where compania='$Compania[0]'";
				$res=ExQuery($cons); ?>
            	<select name="Anio" onChange="document.FORMA.submit()"><option></option>
            <?	while($fila=ExFetch($res)){
					if($fila[0]==$Anio){?>
						<option value="<? echo $fila[0]?>" selected="selected"><? echo $fila[0]?></option>
			<?		}
					else{?>
						<option value="<? echo $fila[0]?>"><? echo $fila[0]?></option>
				<?	}
				}?>
   				</select>     	
            </td>
            <td><select name="MesIni" onChange="document.FORMA.submit()"><option></option>
            <?	$cons="select numero,mes from central.meses";
				$res=ExQuery($cons);
				while($fila=ExFetch($res)){
					if($fila[0]==$MesIni){?>
						<option value="<? echo $fila[0]?>" selected="selected"><? echo $fila[1]?></option>
			<?		}
					else{?>
						<option value="<? echo $fila[0]?>"><? echo $fila[1]?></option>
				<?	}
				}?>
            	</select>
            </td>
            <td><select name="DiaIni" onChange="document.FORMA.submit()"><option></option>
            <?	if($Anio!=''&&$MesIni!=''){					
					$first_of_month = mktime (0,0,0, $MesIni, 1, $Anio); 
					$Dias = date('t', $first_of_month);
					for($i=1;$i<=$Dias;$i++){
						if($i==$DiaIni){?>
							<option value="<? echo $i?>" selected="selected"><? echo $i?></option>
				<?		}
						else{?>
							<option value="<? echo $i?>"><? echo $i?></option>
					<?	}						
					}
				}?>
            	</select>
            </td>
             <td><select name="MesFin" onChange="document.FORMA.submit()"><option></option>
            <?	if($MesIni!=''){
					if($MesFin==''){$MesFin=$ND[mon];}
					$cons="select numero,mes from central.meses where numero>=$MesIni";
					$res=ExQuery($cons);
					while($fila=ExFetch($res)){
						if($fila[0]==$MesFin){?>
							<option value="<? echo $fila[0]?>" selected="selected"><? echo $fila[1]?></option>
				<?		}
						else{?>
							<option value="<? echo $fila[0]?>"><? echo $fila[1]?></option>
					<?	}					
					}
				}?>
            	</select>
            </td>
            <td><select name="DiaFin" onChange="document.FORMA.submit()"><option></option>
            <?	if($Anio!=''&&$MesFin!=''){					
					if($MesIni==$MesFin){$Inicio=$DiaIni;}else{$Inicio=1;}
					$first_of_month = mktime (0,0,0, $MesIni, 1, $Anio); 
					$Dias = date('t', $first_of_month);
					for($i=$Inicio;$i<=$Dias;$i++){
						if($i==$DiaFin){?>
							<option value="<? echo $i?>" selected="selected"><? echo $i?></option>
				<?		}
						else{?>
							<option value="<? echo $i?>"><? echo $i?></option>
					<?	}						
					}
				}?>
            	</select>
            </td>  
            <td>
            <?	$cons="select especialidad from salud.especialidades where compania='$Compania[0]' order by especialidad";
				$res=ExQuery($cons);?>
            	<select name="Especialidad" onChange="document.FORMA.submit()">
                	<option value="Todas">Todas</option>
              	<?	while($fila=ExFetch($res))
					{
						if($Especialidad==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
						else{echo "<option value='$fila[0]'>$fila[0]</option>";}
					}?>
                </select>
            </td>
            <td rowspan="2"><input type="button" name="Ver" value="Ver" <? if($DiaFin!=''){?> onClick="frames.VerInforme.location.href='<? echo $NomArchivo?>?Anio=<? echo $Anio?>&DiaIni=<? echo $DiaIni?>&MesIni=<? echo $MesIni?>&DiaFin=<? echo $DiaFin?>&MesFin=<? echo $MesFin?>&Especialidad=<? echo $Especialidad?>&DatNameSID=<? echo $DatNameSID?>'" <? }?>/></td>
     	</tr>
<?	}
	elseif($Tipo==2)
	{
		if(!$Anio){$Anio=$ND[year];}
		if(!$MesIni){$MesIni=$ND[mon];}
		if(!$MesFin){$MesFin=$ND[mon];}
		if(!$DiaIni){$DiaIni=1;}
		if(!$DiaFin){$DiaFin=$ND[mday];}?>
			<td bgcolor="#e5e5e5" colspan="3" align="center">Periodo Inicial</td><td bgcolor="#e5e5e5" colspan="2" align="center">Periodo Final</td>
            <td bgcolor="#e5e5e5"></td>
       	<tr>        
       		<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
        	<td>
            <?	$cons="select anio from central.anios where compania='$Compania[0]'";
				$res=ExQuery($cons); ?>
            	<select name="Anio" onChange="document.FORMA.submit()"><option></option>
            <?	while($fila=ExFetch($res)){
					if($fila[0]==$Anio){?>
						<option value="<? echo $fila[0]?>" selected="selected"><? echo $fila[0]?></option>
			<?		}
					else{?>
						<option value="<? echo $fila[0]?>"><? echo $fila[0]?></option>
				<?	}
				}?>
   				</select>     	
            </td>
            <td><select name="MesIni" onChange="document.FORMA.submit()"><option></option>
            <?	$cons="select numero,mes from central.meses";
				$res=ExQuery($cons);
				while($fila=ExFetch($res)){
					if($fila[0]==$MesIni){?>
						<option value="<? echo $fila[0]?>" selected="selected"><? echo $fila[1]?></option>
			<?		}
					else{?>
						<option value="<? echo $fila[0]?>"><? echo $fila[1]?></option>
				<?	}
				}?>
            	</select>
            </td>
            <td><select name="DiaIni" onChange="document.FORMA.submit()"><option></option>
            <?	if($Anio!=''&&$MesIni!=''){					
					$first_of_month = mktime (0,0,0, $MesIni, 1, $Anio); 
					$Dias = date('t', $first_of_month);
					for($i=1;$i<=$Dias;$i++){
						if($i==$DiaIni){?>
							<option value="<? echo $i?>" selected="selected"><? echo $i?></option>
				<?		}
						else{?>
							<option value="<? echo $i?>"><? echo $i?></option>
					<?	}						
					}
				}?>
            	</select>
            </td>
             <td><select name="MesFin" onChange="document.FORMA.submit()"><option></option>
            <?	if($MesIni!=''){
					if($MesFin==''){$MesFin=$ND[mon];}
					$cons="select numero,mes from central.meses where numero>=$MesIni";
					$res=ExQuery($cons);
					while($fila=ExFetch($res)){
						if($fila[0]==$MesFin){?>
							<option value="<? echo $fila[0]?>" selected="selected"><? echo $fila[1]?></option>
				<?		}
						else{?>
							<option value="<? echo $fila[0]?>"><? echo $fila[1]?></option>
					<?	}					
					}
				}?>
            	</select>
            </td>
            <td><select name="DiaFin" onChange="document.FORMA.submit()"><option></option>
            <?	if($Anio!=''&&$MesFin!=''){					
					if($MesIni==$MesFin){$Inicio=$DiaIni;}else{$Inicio=1;}
					$first_of_month = mktime (0,0,0, $MesIni, 1, $Anio); 
					$Dias = date('t', $first_of_month);
					for($i=$Inicio;$i<=$Dias;$i++){
						if($i==$DiaFin){?>
							<option value="<? echo $i?>" selected="selected"><? echo $i?></option>
				<?		}
						else{?>
							<option value="<? echo $i?>"><? echo $i?></option>
					<?	}						
					}
				}?>
            	</select>
            </td>  
      		<td rowspan="2">
            	<input type="button" name="Ver" value="Ver" <? if($DiaFin!=''){?> onClick="frames.VerInforme.location.href='<? echo $NomArchivo?>?Anio=<? echo $Anio?>&DiaIni=<? echo $DiaIni?>&MesIni=<? echo $MesIni?>&DiaFin=<? echo $DiaFin?>&MesFin=<? echo $MesFin?>&Especialidad=<? echo $Especialidad?>&DatNameSID=<? echo $DatNameSID?>'" <? }?>/>
           	</td>
     	</tr>
<?	}
	elseif($Tipo==3)
	{
		if(!$Anio){$Anio=$ND[year];}
		if(!$MesIni){$MesIni=$ND[mon];}
		if(!$MesFin){$MesFin=$ND[mon];}
		if(!$DiaIni){$DiaIni=1;}
		if(!$DiaFin){$DiaFin=$ND[mday];}?>
			<td bgcolor="#e5e5e5" colspan="3" align="center">Periodo Inicial</td><td bgcolor="#e5e5e5" colspan="2" align="center">Periodo Final</td>
            <td bgcolor="#e5e5e5" colspan="3" align="center">Entidad</td><td bgcolor="#e5e5e5"></td>
       	<tr>        
       		<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
        	<td>
            <?	$cons="select anio from central.anios where compania='$Compania[0]'";
				$res=ExQuery($cons); ?>
            	<select name="Anio" onChange="document.FORMA.submit()"><option></option>
            <?	while($fila=ExFetch($res)){
					if($fila[0]==$Anio){?>
						<option value="<? echo $fila[0]?>" selected="selected"><? echo $fila[0]?></option>
			<?		}
					else{?>
						<option value="<? echo $fila[0]?>"><? echo $fila[0]?></option>
				<?	}
				}?>
   				</select>     	
            </td>
            <td><select name="MesIni" onChange="document.FORMA.submit()"><option></option>
            <?	$cons="select numero,mes from central.meses";
				$res=ExQuery($cons);
				while($fila=ExFetch($res)){
					if($fila[0]==$MesIni){?>
						<option value="<? echo $fila[0]?>" selected="selected"><? echo $fila[1]?></option>
			<?		}
					else{?>
						<option value="<? echo $fila[0]?>"><? echo $fila[1]?></option>
				<?	}
				}?>
            	</select>
            </td>
            <td><select name="DiaIni" onChange="document.FORMA.submit()"><option></option>
            <?	if($Anio!=''&&$MesIni!=''){					
					$first_of_month = mktime (0,0,0, $MesIni, 1, $Anio); 
					$Dias = date('t', $first_of_month);
					for($i=1;$i<=$Dias;$i++){
						if($i==$DiaIni){?>
							<option value="<? echo $i?>" selected="selected"><? echo $i?></option>
				<?		}
						else{?>
							<option value="<? echo $i?>"><? echo $i?></option>
					<?	}						
					}
				}?>
            	</select>
            </td>
             <td><select name="MesFin" onChange="document.FORMA.submit()"><option></option>
            <?	if($MesIni!=''){
					if($MesFin==''){$MesFin=$ND[mon];}
					$cons="select numero,mes from central.meses where numero>=$MesIni";
					$res=ExQuery($cons);
					while($fila=ExFetch($res)){
						if($fila[0]==$MesFin){?>
							<option value="<? echo $fila[0]?>" selected="selected"><? echo $fila[1]?></option>
				<?		}
						else{?>
							<option value="<? echo $fila[0]?>"><? echo $fila[1]?></option>
					<?	}					
					}
				}?>
            	</select>
            </td>
            <td><select name="DiaFin" onChange="document.FORMA.submit()"><option></option>
            <?	if($Anio!=''&&$MesFin!=''){					
					if($MesIni==$MesFin){$Inicio=$DiaIni;}else{$Inicio=1;}
					$first_of_month = mktime (0,0,0, $MesIni, 1, $Anio); 
					$Dias = date('t', $first_of_month);
					for($i=$Inicio;$i<=$Dias;$i++){
						if($i==$DiaFin){?>
							<option value="<? echo $i?>" selected="selected"><? echo $i?></option>
				<?		}
						else{?>
							<option value="<? echo $i?>"><? echo $i?></option>
					<?	}						
					}
				}?>
            	</select>
            </td>  
            <td>
            <?	$cons="select identificacion,primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and tipo='Asegurador' 
				order by primape,segape,primnom,segnom";
				$res=ExQuery($cons);?>
                <select name="Entidad" onChange="document.FORMA.submit()">
                	<option></option>
              	<?	while($fila=ExFetch($res))
					{
						if($fila[0]==$Entidad){echo "<option value='$fila[0]' selected>$fila[1] $fila[2] $fila[3] $fila[4]</option>";}	
						else{echo "<option value='$fila[0]'>$fila[1] $fila[2] $fila[3] $fila[4]</option>";}	
					}?>
                </select>
            <td>
      		<td rowspan="2">
            	<input type="button" name="Ver" value="Ver" <? if($DiaFin!=''){?> onClick="frames.VerInforme.location.href='<? echo $NomArchivo?>?Anio=<? echo $Anio?>&DiaIni=<? echo $DiaIni?>&MesIni=<? echo $MesIni?>&DiaFin=<? echo $DiaFin?>&MesFin=<? echo $MesFin?>&Entidad=<? echo $Entidad?>&DatNameSID=<? echo $DatNameSID?>'" <? }?>/>
           	</td>
     	</tr>
<?	}?>        
</tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>    
<iframe frameborder="0" id="VerInforme" src="VerIngresos.php" width="100%" height="80%"></iframe>
</body>
</html>