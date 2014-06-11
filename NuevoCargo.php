<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
	if($Guardar)
	{
		if($Nuevo)
		{
			$cons="Select Categoria,Cargo,Identificacion from Central.CargosxCompania where Compania='$Entidad' and Categoria='$Categoria' and Cargo='$Cargo'
			and Identificacion='$Identificacion'";
			$res=ExQuery($cons);
			if(ExNumRows($res)==0)
			{
				$cons="Insert into Central.CargosxCompania (Compania,Categoria,Cargo,Nombre,Identificacion,FechaIni,FechaFin,ciudad,departamento,direccion,telefono,lugarexp) values
				('$Entidad','$Categoria','$Cargo','$Nombre','$Identificacion','$FechaIni','$FechaFin','$Ciudad','$Departamento','$Direccion','$Telefono','$LugarExp')";				
				$res=ExQuery($cons);	
				?>
					<script language="javascript">location.href='ListadoCargos.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>';</script>
				<?			
			}
			else
			{?>
            	<script language="javascript">alert("El Registro que intenta ingresar ya Existe!!!");</script>
			<?	
			}
		}
		if($Editar)
		{						
			if($CategoriaA==$Categoria&&$CargoA==$Cargo&&$IdentificacionA==$Identificacion)
			{
				
				$cons="Update Central.CargosxCompania set Nombre='$Nombre', FechaIni='$FechaIni', FechaFin='$FechaFin', Ciudad='$Ciudad',
				Departamento='$Departamento', Direccion='$Direccion', Telefono='$Telefono', LugarExp='$LugarExp' where Compania='$Entidad' 
				and Categoria='$Categoria' and Cargo='$Cargo' and Identificacion='$Identificacion'";				
				$res=ExQuery($cons);
				?>
				<script language="javascript">location.href='ListadoCargos.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>';</script>
                <?	
			}
			else
			{
				$cons="Select Categoria,Cargo,Identificacion from Central.CargosxCompania where Compania='$Entidad' and Categoria='$Categoria' and Cargo='$Cargo'
				and Identificacion='$Identificacion'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				if(ExNumRows($res)>0)
				{?>
					<script language="javascript">alert("El Registro No se puede actualizar ,debido a que existe otra Persona con esos dator!!!");</script>	
                <?
				}
				else
				{
					$cons="Update Central.CargosxCompania set Categoria='$Categoria', Cargo='$Cargo', Identificacion='$Identificacion',
					Nombre='$Nombre', FechaIni='$FechaIni', FechaFin='$FechaFin', Ciudad='$Ciudad',	Departamento='$Departamento',
					Direccion='$Direccion', Telefono='$Telefono', LugarExp='$LugarExp' where Compania='$Entidad' 
					and Categoria='$CategoriaA' and Cargo='$CargoA' and Identificacion='$IdentificacionA'";
					$res=ExQuery($cons);
					?>
					<script language="javascript">location.href='ListadoCargos.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>';</script>
					<?	
				}	
			}
					
		}		
	}	
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function CambiarValores(Nombre,Objeto)	
	{
		if(Nombre=="AnioIni")
		{
			Objeto.value=document.FORMA.AnioIni.value;
			document.FORMA.AnioFin.value=Objeto.value;
		}
		if(Nombre=="MesIni")
		{
			Objeto.value=document.FORMA.MesIni.value;
			document.FORMA.MesFin.value=Objeto.value;
		}	
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">

<?
if($Entidad)
{
	if($Editar)
	{
		if(!($Categoria&&$Cargo&&$Nombre&&$Identificacion))
		{
			//echo "hola";
			$cons="Select Categoria,Cargo,Nombre,Identificacion,FechaIni,FechaFin,Ciudad,Departamento,Direccion,Telefono,LugarExp from Central.CargosxCompania 
			where Compania='$Entidad' and Categoria='$Categoria' and Cargo='$Cargo' and Identificacion='$Identificacion'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);			
			$Categoria=$fila[0];$Cargo=$fila[1];$Nombre=$fila[2];$Identificacion=$fila[3];
			$CategoriaA=$fila[0];$CargoA=$fila[1];$IdentificacionA=$fila[3];
			$AnioIni=substr($fila[4],0,4);$MesIni=substr($fila[4],5,2);	$DiaIni=substr($fila[4],8,2);		
			$AnioFin=substr($fila[5],0,4);$MesFin=substr($fila[5],5,2);	$DiaFin=substr($fila[5],8,2);
			$Ciudad=$fila[6];$Departamento=$fila[7];$Direccion=$fila[8];$Telefono=$fila[9];$LugarExp=$fila[10];
		}
	}
	if(!$AnioIni){$AnioIni=$ND[year];}
	if(!$AnioFin){$AnioFin=$AnioIni;}
	if(!$MesIni){$MesIni=$ND[mon];}
	if(!$MesFin){$MesFin=$MesIni;}
	if(!$DiaIni){$DiaIni=$ND[mday];}
	if(!$DiaFin){$DiaFin=$DiaIni;}	
?>
<input type="hidden" name="Entidad" value="<? echo $Entidad?>"/>    
<input type="hidden" name="FechaIni" value="<? echo $AnioIni."-".$MesIni."-".$DiaIni?>"/>
<input type="hidden" name="FechaFin" value="<? echo $AnioFin."-".$MesFin."-".$DiaFin?>"/>   
<input type="hidden" name="CategoriaA" value="<? echo $CategoriaA?>"/>   
<input type="hidden" name="CargoA" value="<? echo $CargoA?>"/>
<input type="hidden" name="IdentificacionA" value="<? echo $IdentificacionA?>"/>
	<select name="Categoria" onChange="FORMA.submit();" title="Seleccione una Categoria">
        <option value="" ></option>
    <?	if($Categoria=="Contador"){?>
            <option value="Contador" selected>Contador</option>
    <?	}
        else{?>
            <option value="Contador">Contador</option>
    <?	}
    if($Categoria=="Presupuesto"){?>	                		
        <option value="Presupuesto" selected>Presupuesto</option>	
    <?	}
        else{?>
            <option value="Presupuesto">Presupuesto</option>
    <?	}
    if($Categoria=="Representante"){?>	                    		
        <option value="Representante" selected>Representante</option>	
    <?	}
        else{?>
            <option value="Representante">Representante</option>
    <?	}
    if($Categoria=="Revisor"){?>	                     		
        <option value="Revisor" selected >Revisor</option>
    <?	}
        else{?>
            <option value="Revisor">Revisor</option>
    <?	}
    if($Categoria=="Tesorero"){?>				
        <option value="Tesorero" selected>Tesorero</option>	
    <?	}
        else{?>
            <option value="Tesorero">Tesorero</option>
    <?	}?>		
   </select>
   <?
   if($Categoria)
   {?>
   		<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;'>
		<tr >
        	<td bgcolor="#e5e5e5" style="font-weight:bold">Nombre</td><td colspan="3"><input type="Text" name="Nombre" value="<? echo $Nombre?>" style="width:100%"/></td>
        </tr>
        <tr >
        	<td bgcolor="#e5e5e5" style="font-weight:bold">No. Identificacion</td><td><input type="Text" name="Identificacion" value="<? echo $Identificacion?>" size="10"/></td>
            <td bgcolor="#e5e5e5" style="font-weight:bold">Lugar Exp.</td><td><input type="text" name="LugarExp" value="<? echo $LugarExp?>" onKeyDown="ExLetra(this)" onKeyUp="ExLetra(this)" size="8" title="Ciudad"></td>            
        </tr>        
        <tr>
        	<td bgcolor="#e5e5e5" style="font-weight:bold">Ciudad Residencia</td><td><input type="text" name="Ciudad" value="<? echo $Ciudad?>" onKeyDown="ExLetra(this)" onKeyUp="ExLetra(this)" size="8" title="Ciudad"></td>
            <td bgcolor="#e5e5e5" style="font-weight:bold">Departamento</td><td><input type="text" name="Departamento" value="<? echo $Departamento?>" onKeyDown="ExLetra(this)" onKeyUp="ExLetra(this)" size="10" title="Departamento"></td>
        </tr>
        <tr>
        	<td bgcolor="#e5e5e5" style="font-weight:bold">Dirección</td><td colspan="3"><input type="text" name="Direccion" value="<? echo $Direccion?>" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" style="width:100%"></td>
        </tr>
        <tr>
        	<td bgcolor="#e5e5e5" style="font-weight:bold">Telefono</td><td><input type="text" name="Telefono" value="<? echo $Telefono?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" size="10"></td>
           <td bgcolor="#e5e5e5" style="font-weight:bold">Cargo</td><td><input type="Text" name="Cargo" value="<? echo $Cargo?>" style="width:100%"/></td>            
        </tr>
        <tr>
            <td bgcolor="#e5e5e5" style="font-weight:bold">Fecha Inicio</td>                           	
            <td >                
                <select name="AnioIni" onChange="CambiarValores('AnioIni',this);FORMA.submit();">
                <?
                $cons = "Select Anio from Central.Anios where Compania='$Entidad' order by Anio";
                $res = ExQuery($cons);
                while($fila=ExFetch($res))					
                {
                    if($AnioIni == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
                    else{echo "<option value='$fila[0]'>$fila[0]</option>";}
                }?>     
                </select>               
               <select name="MesIni" onChange="CambiarValores('MesIni',this);FORMA.submit();">                	
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
                <select name="DiaIni" onChange="CambiarValores('DiaIni',this);FORMA.submit();">                	
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
      
      		<td bgcolor="#e5e5e5" style="font-weight:bold">Fecha Fin</td> 
            <td>
            	<select name="AnioFin" onChange="CambiarValores('AnioFin',this);FORMA.submit();">
                <?
                $cons = "Select Anio from Central.Anios where Compania='$Entidad' order by Anio";
                $res = ExQuery($cons);
                while($fila=ExFetch($res))					
                {
                    if($AnioFin == $fila[0]&&$fila[0]>=$AnioIni){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
                    else{if($fila[0]>=$AnioIni){echo "<option value='$fila[0]'>$fila[0]</option>";}}
                }?> 
                </select>               
                <select name="MesFin" onChange="CambiarValores('MesFin',this);FORMA.submit();"  >                	
                <?					
                $cons = "Select Mes,Numero from Central.Meses";
                $res = ExQuery($cons);
                while($fila=ExFetch($res))					
                {
					if($AnioIni==$AnioFin)
					{
						if($MesFin == $fila[1]&&$fila[1]>=$MesIni){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
                    	else{if($fila[1]>=$MesIni){echo "<option value='$fila[1]'>$fila[0]</option>";}}
					}
					else
					{
						if($MesFin == $fila[1]){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
                    	else{echo "<option value='$fila[1]'>$fila[0]</option>";}
					}					
                    /*if($MesFin == $fila[1]&&$fila[1]>=$MesIni){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
                    else{if($fila[1]>=$MesIni){echo "<option value='$fila[1]'>$fila[0]</option>";}}*/
                }
                ?>
                </select>                       
                <select name="DiaFin" onChange="CambiarValores('DiaIni',this);FORMA.submit();">                	
                <?					
                $cons = "Select NumDias from Central.Meses where Numero=$MesFin";
                //echo $cons;					
                $res = ExQuery($cons);
                $fila=ExFetch($res);													
                for($i=1;$i<=$fila[0];$i++)
                {	
					if($MesIni==$MesFin&&$AnioIni==$AnioFin)
					{					
						if($DiaFin == $i&&$i>=$DiaIni){echo "<option selected value=$i>$i</option>";}
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
   <?
   }
   else
   {
   		$Deshab="disabled";
   }
}?>
    <input type="submit" name="Guardar" value="Guardar" <? echo $Deshab?>/>
    <input type="button" name="Cancelar" value="Cancelar" onClick="location.href='ListadoCargos.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>'"/>
    </form>	
</body>