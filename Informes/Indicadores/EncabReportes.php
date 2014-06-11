<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Opciones)
	{
		$cons="Select Tipo,Archivo from Central.Reportes where Id=$Opciones and Clase='$Clase' and Modulo='Estadistica'";			
		$res=ExQuery($cons);		
		$fila=ExFetch($res);
		$Tipo=$fila[0];
		$NomArchivo=$fila[1];
	}
	$cons="Select Id,Nombre from Central.Reportes where Modulo='Estadistica' and Clase='$Clase' order by Clase,Nombre";
	$res=ExQuery($cons);
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
  	<?	if($Tipo==2)
		{
			if(!$AnioIni){$AnioIni=$ND[year];}
			if(!$MesIni){$MesIni=$ND[mon];}	
			if(!$DiaIni){$DiaIni=$ND[mday];}	
			
			$first_of_month = mktime (0,0,0, $Mes, 1, $Anio); 
			$LastDay = date('t', $first_of_month); 			
			if(!$MesFin){$MesFin=$ND[mon];}	
			if(!$DiaFin){$DiaFin=$LastDay;}
			?>
        	<form name="FORMA1" method="post" >
           	<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
        	<td>
            	<table cellpadding="0" cellspacing="0" border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;' align="center">
 	          	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">                             
 					<td bgcolor="#e5e5e5" style="font-weight:bold" >A&ntilde;o</td>
                    <td bgcolor="#e5e5e5" style="font-weight:bold" colspan="2">Desde</td>
                    <td bgcolor="#e5e5e5" style="font-weight:bold" colspan="2">Hasta</td>
                    </tr>
                    <tr bgcolor="#e5e5e5" style="font-weight:bold" >                    
                    <td align="center">
                        <select name="AnioIni" onChange="FORMA1.submit();" style="font-size:11px">
                        <?
                        $cons = "Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio";
                        $res = ExQuery($cons);
                        while($fila=ExFetch($res))					
                        {
                            if($AnioIni == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
                            else{echo "<option value='$fila[0]'>$fila[0]</option>";}
                        }?> 
                        </select>
                    </td>
                    <td align="center">
                        <select name="MesIni" onChange="FORMA1.submit();" style="font-size:11px">                	
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
                    	<select name="DiaIni">
                 	<?	for($i=1;$i<=$LastDay;$i++)
						{
							if($DiaIni==$i)
							{echo "<option value='$i' selected>$i</option>";}
							else
							{echo "<option value='$i'>$i</option>";}
						}?>
                        </select>
                    </td>        
                    <td align="center">
                        <select name="MesFin" onChange="FORMA1.submit();" style="font-size:11px">                	
                        <?					
                        $cons = "Select Mes,Numero from Central.Meses";
                        $res = ExQuery($cons);
                        while($fila=ExFetch($res))					
                        {
                            if($MesFin == $fila[1]){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
                            else{echo "<option value='$fila[1]'>$fila[0]</option>";}
                        }
                        ?>
                        </select>          
          			</td>
                    <td>
                    	<select name="DiaFin">
                 	<?	for($i=1;$i<=$LastDay;$i++)
						{
							if($DiaFin==$i)
							{echo "<option value='$i' selected>$i</option>";}
							else
							{echo "<option value='$i'>$i</option>";}
						}?>
                        </select>
                    </td>                                    
              	</tr>
               	</table>
            </td>
            </form>
             </form>
            <form name="FORMA" action="<? echo $NomArchivo?>" target="Abajo">
            <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
            <input type="hidden" name="AnioIni" value="<? echo $AnioIni?>">
            <input type="hidden" name="MesIni" value="<? echo $MesIni?>" />
            <input type="hidden" name="DiaIni" value="<? echo $DiaIni?>" />
            <input type="hidden" name="MesFin" value="<? echo $MesFin?>" />
            <input type="hidden" name="DiaFin" value="<? echo $DiaFin?>" />
    <?	}?>
    	<td> <input type="submit" name="Ver" value="Ver" /> </td>      
        </form>
	</tr>
</table>            
</body>
</html>
