<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$cons="Select Id,Nombre from Central.Reportes where Modulo='Estadistica' and Clase='$Clase' order by Clase,Nombre";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$MatNombres[$fila[0]]=array($fila[0],$fila[1]);
	}	
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function CambiarValores(Nombre,Objeto)	
	{
		if(Nombre=="MesIni")
		{
			Objeto.value=document.FORMA1.MesIni.value;
			document.FORMA1.MesFin.value=Objeto.value;
		}	
	}
	function Validar()
	{
		if(document.FORMA.Entidad.value==""){}
		else
		{
			if(document.FORMA.Contrato.value==""){alert("Seleccione el Contrato!!!");return false;}
			if(document.FORMA.NumContrato.value==""){alert("Seleccione el Numero de Contrato!!!");return false;}		
		}
	}
	function Borrar()
	{
		document.FORMA1.Contrato.value="";
		document.FORMA1.NumContrato.value="";
		document.FORMA.Contrato.value="";
		document.FORMA.NumContrato.value="";
	}
	
</script>
<body background="/Imgs/Fondo.jpg">
<table cellpadding="0"  bordercolor="#FFFFFF">
	<tr>
    	<td><select name="Opciones" onChange="location.href='EncabReportes.php?DatNameSID=<? echo $DatNameSID?>&Opciones='+this.value+'&Clase=<? echo $Clase?>'">
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
        if($Opciones)
		{
			?><script language="javascript">parent.frames.document.getElementById("Abajo").src="blanco.php?DatNameSID=<? echo $DatNameSID?>";</script><?					
			$cons="Select Tipo,Archivo from Central.Reportes where Id=$Opciones and Clase='$Clase' and Modulo='Estadistica'";			
			$res=ExQuery($cons);		
			$fila=ExFetch($res);
			$Tipo=$fila[0];
			$NomArchivo=$fila[1];
			if($Tipo==1)//solito sin nada mas
			{ ?>            
				<form name="FORMA" action="<? echo $NomArchivo?>" target="Abajo" >
                <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
               <td>
               <table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;'>
               <tr bgcolor="#e5e5e5" style="font-weight:bold">
               <td>PDF</td>
               </tr>
               <tr><td><input type="checkbox" name="PDF"/></td></tr>
               </table>
               </td>
              <?  
			}
			if($Tipo==2)
			{
				if(!$Anio){$Anio=$ND[year];}
				if(!$Mes){$Mes=$ND[mon];}					
				?>  
                <form name="FORMA1" method="post" >
                <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
                <td>
                	<table cellpadding="0" cellspacing="0" border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;' align="center">
                    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">                             
 					<td bgcolor="#e5e5e5" style="font-weight:bold" >A&ntilde;o</td>
                    <td bgcolor="#e5e5e5" style="font-weight:bold" >Mes </td>                                          
                    </tr>
                    <tr bgcolor="#e5e5e5" style="font-weight:bold" >                    
                    <td align="center">
                    <select name="Anio" onChange="FORMA1.submit();" style="font-size:11px">
					<?
                    $cons = "Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio";
					$res = ExQuery($cons);
					while($fila=ExFetch($res))					
					{
						if($Anio == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
						else{echo "<option value='$fila[0]'>$fila[0]</option>";}
					}?> 
                    </select>
                    </td>
                    <td align="center">
                    <select name="Mes" onChange="FORMA1.submit();" style="font-size:11px">                	
                    <?					
					$cons = "Select Mes,Numero from Central.Meses";
					$res = ExQuery($cons);
					while($fila=ExFetch($res))					
					{
						if($Mes == $fila[1]){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
						else{echo "<option value='$fila[1]'>$fila[0]</option>";}
					}
					?>
               		</select>          
          			</td>                                      
                    </tr>
                    </table>
                </td>                 
                </form>
                <form name="FORMA" action="<? echo $NomArchivo?>" target="Abajo">
                <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
                <input type="hidden" name="Anio" value="<? echo $Anio?>">
                <input type="hidden" name="Mes" value="<? echo $Mes?>" />                               	
			<? 
			}			
			if($Tipo==3)//rango fechas e impuesto
			{
				if(!$Anio)
				{					
					$Anio=$ND[year];				
				}
				if(!$MesIni){$MesIni=1;}
				if(!$MesFin){$MesFin=$ND[mon];}
				if(!$DiaIni){$DiaIni=1;}
				if(!$DiaFin){if($MesFin==$ND[mon]){$DiaFin=$ND[mday];}else{$DiaFin=1;}}							
				?>  
                <form name="FORMA1" method="post" >
                <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
                <td>
                	<table cellpadding="0" cellspacing="0" border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;' align="center">
                    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
                    <td colspan="5">Entidad</td>                    
 					<td>Contrato</td> 
                    <td>Numero Contrato</td>                                                           
                    </tr>
                    <tr bgcolor="#e5e5e5" style="font-weight:bold" >                    
                    <td align="center" colspan="5">
					<select name="Entidad" onChange="Borrar();FORMA1.submit();" style="font-size:11px">
                    <option value=""></option>
					<? 
	               	$cons = "Select Identificacion,PrimApe from Central.Terceros where Compania='$Compania[0]' and tipo='Asegurador' order by PrimApe";
					$res = ExQuery($cons);
					while($fila=ExFetch($res))					
					{
						if($Entidad == $fila[0]){echo "<option selected value='$fila[0]'>$fila[1]</option>";}
						else{echo "<option value='$fila[0]'>$fila[1]</option>";}
					}?>  
                    </select>
                    </td>                                      
                    <td>
                    <select name="Contrato" onChange="FORMA1.submit();" style="font-size:11px">
                    <option value=""></option>
					<? 
	               	$cons = "Select Contrato from ContratacionSalud.Contratos where Compania='$Compania[0]' and Entidad='$Entidad' order by Contrato";
					$res = ExQuery($cons);
					while($fila=ExFetch($res))					
					{
						if($Contrato == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
						else{echo "<option value='$fila[0]'>$fila[0]</option>";}
					}?>  
                    </select>
                    </td>   
                    <td colspan="3">
                    <select name="NumContrato" onChange="FORMA1.submit();" style="font-size:11px">
                    <option value=""></option>
					<? 
	               	$cons = "Select Numero from ContratacionSalud.Contratos where Compania='$Compania[0]' and Entidad='$Entidad' and Contrato='$Contrato' order by Numero";
					$res = ExQuery($cons);
					while($fila=ExFetch($res))					
					{
						if($NumContrato == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
						else{echo "<option value='$fila[0]'>$fila[0]</option>";}
					}?>  
                    </select>
                    </td>                     
                    </tr>
                    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
                    <td bgcolor="#e5e5e5" style="font-weight:bold" >A&ntilde;o</td>
                    <td bgcolor="#e5e5e5" style="font-weight:bold" >Mes Inicio</td>
                    <td bgcolor="#e5e5e5" style="font-weight:bold" >Dia Ini</td>
                    <td bgcolor="#e5e5e5" style="font-weight:bold" >Mes Fin</td>                    
                    <td bgcolor="#e5e5e5" style="font-weight:bold" >Dia Fin</td>
                    <td colspan="2" align="center">PDF</td>   
                    </tr>                    
                    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
                    <td align="center">
                    <select name="Anio" onChange="FORMA1.submit();" style="font-size:11px">
					<?
                    $cons = "Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio";
					$res = ExQuery($cons);
					while($fila=ExFetch($res))					
					{
						if($Anio == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
						else{echo "<option value='$fila[0]'>$fila[0]</option>";}
					}?> 
                    </select>
                    </td>
                    <td align="center">
                    <select name="MesIni" onChange="CambiarValores('MesIni',this);FORMA1.submit();" style="font-size:11px">                	
                    <?					
					$cons = "Select Mes,Numero from Central.Meses";
					$res = ExQuery($cons);
					while($fila=ExFetch($res))					
					{
						if($MesIni == $fila[1]){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
						else{echo "<option value='$fila[1]'>$fila[0]</option>";}
					}
					?>
               		</select>          
          			</td>                   
                    <td>
                    <select name="DiaIni" onChange="CambiarValores('DiaIni',this);FORMA1.submit();">                	
                    <?					
					$NDI=UltimoDia($Anio,$MesIni);						
					for($i=1;$i<=$NDI;$i++)
					{						
						if($DiaIni == $i){echo "<option selected value=$i>$i</option>";}
						else{echo "<option value=$i>$i</option>";}
						
					}
					?>
                	</select>      
                    </td>
                    <td align="center">
                    <select name="MesFin" onChange="CambiarValores('MesFin',this);FORMA1.submit();" style="font-size:11px" >                	
                    <?					
					$cons = "Select Mes,Numero from Central.Meses";
					$res = ExQuery($cons);
					while($fila=ExFetch($res))					
					{
						if($MesFin == $fila[1]&&$fila[1]>=$MesIni){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
						else{if($fila[1]>=$MesIni){echo "<option value='$fila[1]'>$fila[0]</option>";}}
					}
					?>
               		</select>          
          			</td> 
                    <td>
                    <select name="DiaFin" onChange="CambiarValores('DiaFin',this);FORMA1.submit();">                	
                    <?					
					$NDF=UltimoDia($Anio,$MesFin);						
					for($i=1;$i<=$NDF;$i++)
					{						
						if($MesIni==$MesFin)
						{
							if($DiaFin == $i && $i>=$DiaIni){echo "<option selected value=$i>$i</option>";}
							else{if($i>=$DiaIni){echo "<option value=$i>$i</option>";}}
						}
						else
						{
							if($DiaFin == $i){echo "<option selected value=$i>$i</option>";}
							else{echo "<option value=$i>$i</option>";}
						}						
					}
					?>
                	</select> 
                    </td>
                    <td colspan="2" align="center"><input type="checkbox" name="PDF1" onClick="if(this.checked){document.FORMA.PDF.value=1;}else{document.FORMA.PDF.value=''}"/>
                    </td>
                    </tr>
                    <tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
                    <td colspan="7" align="center">Paciente &nbsp;<select name="CedPaciente" onChange="FORMA1.submit();" style="font-size:11px">
                    <option value=""></option>
					<?
					if($Entidad){$pconsEnt=" and PagadorXServicios.Entidad='$Entidad'";}
					if($Contrato){$pconsCont=" and PagadorxServicios.Contrato='$Contrato'";}
					if($NumContrato){$pconsNCont=" and PagadorxServicios.NoContrato='$NumContrato'";} 
	               	$cons = "Select Cedula,(PrimApe || ' ' || SegApe  || ' ' || PrimNom  || ' ' || SegNom) as Nombre from Salud.Servicios,Salud.PagadorxServicios,
					Central.Terceros where Servicios.Compania='$Compania[0]' and Servicios.Compania=PagadorxServicios.Compania and 
					Servicios.Compania=Terceros.Compania  and Servicios.Cedula=Terceros.Identificacion and
					Servicios.NumServicio=PagadorxServicios.NumServicio $pconsEnt $pconsCont $pconsNCont group by Cedula,Nombre order by Nombre";
					//echo $cons;
					$res = ExQuery($cons);
					//echo $cons;
					while($fila=ExFetch($res))					
					{
						if($CedPaciente == $fila[0]){echo "<option selected value='$fila[0]'>$fila[1]</option>";}
						else{echo "<option value='$fila[0]'>$fila[1] -- $fila[0]</option>";}
					}?>  
                    </select>
                   	</td>
                    </tr>
                    </table>
                </td>                 
                </form>
                <form name="FORMA" action="<? echo $NomArchivo?>" target="Abajo" onSubmit="return Validar()">
                <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
                <input type="hidden" name="Anio" value="<? echo $Anio?>">
                <input type="hidden" name="MesIni" value="<? echo $MesIni?>" />         
                <input type="hidden" name="DiaIni" value="<? echo $DiaIni?>" />                               
                <input type="hidden" name="MesFin" value="<? echo $MesFin?>" />
                <input type="hidden" name="DiaFin" value="<? echo $DiaFin?>" />         
                <input type="hidden" name="Entidad" value="<? echo $Entidad?>" />
                <input type="hidden" name="Contrato" value="<? echo $Contrato?>" />
                <input type="hidden" name="NumContrato" value="<? echo $NumContrato?>" />               
                <input type="hidden" name="CedPaciente" value="<? echo $CedPaciente?>" />               
                <input type="hidden" name="PDF" value="<? echo $PDF?>">                	
			<? 
			}			
		}		
		else
		{
			?><script language="javascript">parent.frames.document.getElementById("Abajo").src="blanco.php?DatNameSID=<? echo $DatNameSID?>";</script><?
		}
		
		?> 
    	<td> <input type="submit" name="Ver" value="Ver" /> </td>      
        <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
		</form>
    </tr >
</table>
