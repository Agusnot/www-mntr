<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Clase=="IndicadoresxHC")
	{
		$Modulo="Estadistica";		
	}
	else
	{
		$Modulo="Historia Clinica";	
	}
	$cons="Select Id,Nombre from Central.Reportes where Modulo='$Modulo' and Clase='$Clase' order by Clase, Nombre";
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
		if(document.FORMA.Anio==""){alert("Digite el Año!!!");return false;}
		if(document.FORMA1.PDF1.checked){document.FORMA.PDF.value=1;}else{document.FORMA.PDF.value="";}
	}
	function Validar5()
	{
		/*if(document.FORMA1.Encabezados1.value==""||document.FORMA1.Encabezados1.value=="0"){document.FORMA.Encabezados.value="0";}
		else{document.FORMA.Encabezados.value=document.FORMA1.Encabezados1.value; }*/
		if(document.FORMA1.PDF1.checked){document.FORMA.PDF.value=1;}else{document.FORMA.PDF.value="";}
		if(document.FORMA.PDF.value!=""&&document.FORMA.NomArchivo.value=="/Informes/Predial/Reportes/ComunicadoMorosos.php")
		{document.FORMA.EncPie.value=1;}
		if(document.FORMA1.IntervaloV1.value!=""&&parseFloat(document.FORMA1.Valor.value)>0)
		{		
			document.FORMA.IntervaloV.value = "<? echo $IntervaloV1?>";
			document.FORMA.Valor.value = document.FORMA1.Valor.value;		
		}		
		if(document.FORMA1.IntervaloA1.value!=""&&document.FORMA1.Anio1.value==""){alert("Por Favor Seleccione un numero de Años!!!"); return false;}
		if(document.FORMA1.Anio1.value!=""&&document.FORMA1.IntervaloA1.value==""){alert("Por Favor Seleccione un parametro de busqueda de Años!!!"); return false;}
		if(document.FORMA1.IntervaloV1.value!=""&&document.FORMA1.Valor.value==""){alert("Por Favor Digite un monto en Pesos!!!"); return false;}
		if(document.FORMA1.Valor.value!=""&&document.FORMA1.IntervaloV1.value==""){alert("Por Favor Seleccione un parametro de busqueda del Valor!!!"); return false;}	
	}
	
</script>
<body background="/Imgs/Fondo.jpg">
<table border="1" bordercolor="#FFFFFF">
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
			$cons="Select Tipo,Archivo,nombre from Central.Reportes where Id=$Opciones and Clase='$Clase' and Modulo='$Modulo'";			
			$res=ExQuery($cons);		
			$fila=ExFetch($res);
			$Tipo=$fila[0];
			$NomArchivo=$fila[1];
			$NomInforme=$fila[2];
			if($Tipo==1)//solito sin nada mas
			{ ?>            
				<form name="FORMA" action="<? echo $NomArchivo?>" target="Abajo" >
                <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
               <td><table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;'>
               <tr bgcolor="#e5e5e5" style="font-weight:bold">
               <td>PDF</td>
               </tr>
               <tr><td><input type="checkbox" name="PDF"/></td></tr>
               </table>
               </td>
              <?  
			}
			if($Tipo==2)//rango fechas
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
                <td>
                <form name="FORMA1" method="post">
                <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
                <table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
                <tr bgcolor="#e5e5e5" style="font-weight:bold">
                	<td>A&ntilde;o</td>
                    <td>Mes Inicio</td>
                    <td>Dia Inicio</td>
                    <td>Mes Fin</td>
                    <td>Dia Fin</td>
                </tr>
                <tr>                	
                	<td >
                    <select name="Anio" onChange="FORMA1.submit();">
					<?
                    $cons = "Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio";
					$res = ExQuery($cons);
					while($fila=ExFetch($res))					
					{
						if($Anio == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
						else{echo "<option value='$fila[0]'>$fila[0]</option>";}
					}?>                    
                    <td>
                    <select name="MesIni" onchange="CambiarValores('MesIni',this);FORMA1.submit();">                	
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
					$cons = "Select NumDias from Central.Meses where Numero=$MesIni";
					//echo $cons;					
					$res = ExQuery($cons);
					$fila=ExFetch($res);													
					for($i=1;$i<=$fila[0];$i++)
					{						
						if($DiaIni == $i){echo "<option selected value=$i>$i</option>";}
						else{echo "<option value=$i>$i</option>";}
						
					}
					?>
                	</select>          
         			</td>
                    <td>
                    <select name="MesFin" onChange="CambiarValores('MesFin',this);FORMA1.submit();"  >                	
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
					$cons = "Select NumDias from Central.Meses where Numero=$MesFin";					
					$res = ExQuery($cons);
					$fila=ExFetch($res);													
					for($i=1;$i<=$fila[0];$i++)
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
                </table>
	                <td>
                    	<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;'>
                           <tr bgcolor="#e5e5e5" style="font-weight:bold">
                           <td>PDF</td>
                           </tr>
                           <tr><td><input type="checkbox" name="PDF1"/></td></tr>
                        </table>
                    </td>   
                </form>
                <form name="FORMA" action="<? echo $NomArchivo?>" target="Abajo" onsubmit="return Validar()">
                <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
                <input type="hidden" name="PDF" <? if($PDF1){ echo "value=1";}?>/>
                <input type="hidden" name="FechaIni" value="<? echo $Anio."-".$MesIni."-".$DiaIni?>" />
                <input type="hidden" name="FechaFin" value="<? echo $Anio."-".$MesFin."-".$DiaFin?>" />
                
                </td>	
			<? 
			}
			if($Tipo==3)//rango fechas y tipo convencion
			{
				if(!$Anio)
				{					
					$Anio=$ND[year];				
				}
				if(!$AnioFin){$AnioFin=$Anio;}
				if($AnioFin<$Anio){$AnioFin=$Anio;}
				if(!$MesIni){$MesIni=1;}
				if(!$MesFin){$MesFin=$ND[mon];}
				if(!$DiaIni){$DiaIni=1;}
				if(!$DiaFin){if($MesFin==$ND[mon]){$DiaFin=$ND[mday];}else{$DiaFin=1;}}			
				?>  
                <form name="FORMA1" method="post">
                <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
                <td>
                	<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
                    <tr>
                    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Tipo</td>
                    </tr>
                    <tr>                    
                    <td>
                    <?
					if($NomInforme=="Informe COP")
					{?>
                        <select name="COP" onChange="FORMA1.submit();">
                        <option value=""></option>
                        <? 
                        $cons = "Select Color,NColor from odontologia.colorconvenciones where Compania='$Compania[0]' order by NColor";
                        $res = ExQuery($cons);
                        while($fila=ExFetch($res))					
                        {
                            if($COP == $fila[0]){echo "<option selected value='$fila[0]'>$fila[1]</option>";}
                            else{echo "<option value='$fila[0]'>$fila[1]</option>";}
                        }?>  
                        </select>
                        <?
					}
					else
					{?>
                    	<select name="Morbilidad" onChange="FORMA1.submit();">
                        <option value=""></option>
                        <? 
                        $cons = "Select Codigo,Nombre from odontologia.procedimientosimgs where Compania='$Compania[0]' order by Nombre";
                        $res = ExQuery($cons);
                        while($fila=ExFetch($res))					
                        {
                            if($Morbilidad == $fila[0]){echo "<option selected value='$fila[0]'>$fila[1]</option>";}
                            else{echo "<option value='$fila[0]'>$fila[1]</option>";}
                        }?>  
                        </select>
                    <?
					}?>
                    </td>
                    </tr>
                    </table>
                </td>          	                
                <td>                
                <table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
                <tr bgcolor="#e5e5e5" style="font-weight:bold">
                	<td>A&ntilde;o Ini</td>
                    <td>Mes Inicio</td>
                    <td>Dia Ini</td>
                    <td>A&ntilde;o Fin</td>
                    <td>Mes Fin</td>
                    <td>Dia Fin</td>
                </tr>
                <tr>                	
                	<td >
                    <select name="Anio" onChange="FORMA1.submit();">
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
                    <td>
                    <select name="MesIni" onChange="CambiarValores('MesIni',this);FORMA1.submit();">                	
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
					$cons = "Select NumDias from Central.Meses where Numero=$MesIni";
					//echo $cons;					
					$res = ExQuery($cons);
					$fila=ExFetch($res);													
					for($i=1;$i<=$fila[0];$i++)
					{						
						if($DiaIni == $i){echo "<option selected value=$i>$i</option>";}
						else{echo "<option value=$i>$i</option>";}
						
					}
					?>
                	</select>          
         			</td>
                    <td >
                    <select name="AnioFin" onChange="FORMA1.submit();">
					<?
                    $cons = "Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio";
					$res = ExQuery($cons);
					while($fila=ExFetch($res))					
					{
						if($AnioFin == $fila[0] && $fila[0]>=$Anio){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
						elseif($fila[0]>=$Anio){echo "<option value='$fila[0]'>$fila[0]</option>";}
					
					}?>    
                    </select>
                    </td>                                    
                    <td>
                    <select name="MesFin" onChange="CambiarValores('MesFin',this);FORMA1.submit();"  >                	
                    <?					
					$cons = "Select Mes,Numero from Central.Meses";
					$res = ExQuery($cons);
					while($fila=ExFetch($res))					
					{
						if($AnioFin==$Anio)
						{
							if($MesFin == $fila[1]&&$fila[1]>=$MesIni){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
							else{if($fila[1]>=$MesIni){echo "<option value='$fila[1]'>$fila[0]</option>";}}
						}
						else
						{
							if($MesFin == $fila[1]){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
							else{echo "<option value='$fila[1]'>$fila[0]</option>";}
						}
					}
					?>
               		</select>          
          			</td>
                    <td>
                    <select name="DiaFin" onChange="CambiarValores('DiaFin',this);FORMA1.submit();">                	
                    <?					
					$cons = "Select NumDias from Central.Meses where Numero=$MesFin";					
					$res = ExQuery($cons);
					$fila=ExFetch($res);													
					for($i=1;$i<=$fila[0];$i++)
					{						
						if($AnioFin==$Anio&&$MesIni==$MesFin)
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
                </tr>
                </table>
                </form>
                <form name="FORMA" action="<? echo $NomArchivo?>" target="Abajo" onsubmit="return Validar()">
                <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
                <input type="hidden" name="FechaIni" value="<? echo $Anio."-".$MesIni."-".$DiaIni?>" />
                <input type="hidden" name="FechaFin" value="<? echo $AnioFin."-".$MesFin."-".$DiaFin?>" />
                <input type="hidden" name="COP" value="<? echo $COP?>" />
                <input type="hidden" name="Morbilidad" value="<? echo $Morbilidad?>" />
                
                </td>	
			<? 
			}
			if($Tipo==4)//por filas nada mas
			{
            	if(!$Anio)
				{					
					$Anio=$ND[year];				
				}
				if(!$AnioFin){$AnioFin=$Anio;}
				if($AnioFin<$Anio){$AnioFin=$Anio;}
				if(!$MesIni){$MesIni=$ND[mon];}
				if(!$MesFin){$MesFin=$ND[mon];}
				if(!$DiaIni){$DiaIni=1;}
				if(!$DiaFin){if($MesFin==$ND[mon]){$DiaFin=$ND[mday];}else{$DiaFin=1;}}	
				$UDI=UltimoDia($Anio,$MesIni);
				$UDF=UltimoDia($AnioFin,$MesFin);		
				?>  
                <form name="FORMA1" method="post">
                <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>                          	                
                <td>                
                <table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
                <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
                	<td>Indicador</td>
                    <td colspan="3">Desde</td>
                    <td colspan="3">Hasta</td>
                    <td>Ambito</td>                   
                </tr>
                <tr>                	
                	<td>
                    <?
                     $cons="SELECT indicador FROM historiaclinica.indicadoresxhc where Compania='$Compania[0]' group by Indicador order by Indicador";
					$res=ExQuery($cons);
					if(ExNumRows($res)==0){$Anch="width:80px;";}else{$Anch="width:200px;";}
					?>
                    <select name="Indicador" onChange="FORMA1.submit();" style=" <? echo $Anch ?>; max-width:200px;">
                    <option value=""></option>
                    <?                   
					while($fila=ExFetch($res))
					{
						if($fila[0]==$Indicador)
						{	echo "<option value='$fila[0]' selected>$fila[0]</option>";}
						else{echo "<option value='$fila[0]'>$fila[0]</option>";}
					
					}
  					?>
                    </select>                    
                    </td>
                    <td >
                    <select name="Anio" onChange="FORMA1.submit();">
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
                    <td>
                    <select name="MesIni" onChange="CambiarValores('MesIni',this);FORMA1.submit();">                	
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
					for($i=1;$i<=$UDI;$i++)
					{						
						if($DiaIni == $i){echo "<option selected value=$i>$i</option>";}
						else{echo "<option value=$i>$i</option>";}
						
					}
					?>
                	</select>          
         			</td>
                    <td >
                    <select name="AnioFin" onChange="FORMA1.submit();">
					<?
                    $cons = "Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio";
					$res = ExQuery($cons);
					while($fila=ExFetch($res))					
					{
						if($AnioFin == $fila[0] && $fila[0]>=$Anio){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
						elseif($fila[0]>=$Anio){echo "<option value='$fila[0]'>$fila[0]</option>";}
					
					}?>    
                    </select>
                    </td>                                    
                    <td>
                    <select name="MesFin" onChange="CambiarValores('MesFin',this);FORMA1.submit();"  >                	
                    <?					
					$cons = "Select Mes,Numero from Central.Meses";
					$res = ExQuery($cons);
					while($fila=ExFetch($res))					
					{
						if($AnioFin==$Anio)
						{
							if($MesFin == $fila[1]&&$fila[1]>=$MesIni){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
							else{if($fila[1]>=$MesIni){echo "<option value='$fila[1]'>$fila[0]</option>";}}
						}
						else
						{
							if($MesFin == $fila[1]){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
							else{echo "<option value='$fila[1]'>$fila[0]</option>";}
						}
					}
					?>
               		</select>          
          			</td>
                    <td>
                    <select name="DiaFin" onChange="CambiarValores('DiaFin',this);FORMA1.submit();">                	
                    <?																						
					for($i=1;$i<=$UDF;$i++)
					{						
						if($AnioFin==$Anio&&$MesIni==$MesFin)
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
                    <td>                    
                    <select name="Ambito" onChange="document.FORMA1.submit()">
                    <option value=""></option>    
                    <?
					$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";	
                    $res=ExQuery($cons);echo ExError();	
                    while($fila = ExFetch($res))
					{
                        if($fila[0]==$Ambito){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
                        else{echo "<option value='$fila[0]'>$fila[0]</option>";}
                    }?>
                    </select>
                    </td>                                       
                </tr>
                </table>
                </form>
                <form name="FORMA" action="<? echo $NomArchivo?>" target="Abajo" onsubmit="return Validar()">
                <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
                <input type="hidden" name="FechaIni" value="<? echo $Anio."-".$MesIni."-".$DiaIni?>" />
                <input type="hidden" name="FechaFin" value="<? echo $AnioFin."-".$MesFin."-".$DiaFin?>" />
                <input type="hidden" name="Ambito" value="<? echo $Ambito?>" />
                <input type="hidden" name="Indicador" value="<? echo $Indicador?>">
                </td>	            	
			<? }		
		}		
		else
		{
			?><script language="javascript">parent.frames.document.getElementById("Abajo").src="blanco.php?DatNameSID=<? echo $DatNameSID?>";</script><?
		}		
		?> 
    	<td> <input type="submit" name="Ver" value="Ver" /></td>      
    </tr >
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
</form>