<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons="Delete from Salud.Medicos where usuario='$Usuario' and Compania='$Compania[0]'";
		$res=ExQuery($cons);echo ExError();		
	}
	if($Especialidad){$Esp=" and especialidad='$Especialidad' ";}else{$Esp="";}
	
	$result=ExQuery("Select especialidad from Salud.Especialidades where Compania='$Compania[0]' $Esp Group By Especialidad order by especialidad");
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	<tr>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold" align="center">Especialidad</td>    
    	<td>
        <?	$consE="Select especialidad from Salud.Especialidades where Compania='$Compania[0]' Group By Especialidad order by especialidad";
			$resE=ExQuery($consE);?>
        	<select name="Especialidad" onChange="document.FORMA.submit()">
            	<option></option>
          	<?	while($filaE=ExFetch($resE))
				{
					if($filaE[0]==$Especialidad){echo "<option value='$filaE[0]' selected>$filaE[0]</option>";}
					else{echo "<option value='$filaE[0]'>$filaE[0]</option>";}
				}?>
            </select>
        </td>
        <td  bgcolor="#e5e5e5" style="font-weight:bold" align="center">Cargo</td>    
        <td>
        <?	$consE="Select cargos from Salud.cargos where Compania='$Compania[0]' Group By cargos order by cargos";
		
			$resE=ExQuery($consE);?>
        	<select name="Cargo" onChange="document.FORMA.submit()">
            	<option></option>
          	<?	while($filaE=ExFetch($resE))
				{
					if($filaE[0]==$Cargo){echo "<option value='$filaE[0]' selected>$filaE[0]</option>";}
					else{echo "<option value='$filaE[0]'>$filaE[0]</option>";}
				}?>
            </select>
        </td>
    </tr>
</table>
    
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">     
<?	if($Cargo){$Carg=" and cargo='$Cargo'";}else{$Carg="";}
	while($row = ExFetchArray($result))
	{
		?>
        	<TR bgcolor="#e5e5e5" style="font-weight:bold">
            	<? 
					$subcons="Select usuarios.usuario, nombre,cedula,rm,cargo,telefono,direccion,Medicos.usuario as usu,asistencial,estadomed
					from Salud.Medicos,central.usuarios,salud.cargos 
					where Especialidad='".$row['especialidad']."' and Medicos.usuario=usuarios.usuario and Medicos.Compania='$Compania[0]' and cargos.compania='$Compania[0]'
					and cargos.cargos=medicos.cargo $Carg order by Usuario ASC, Nombre ASC";
					
					$subresult=ExQuery($subcons);			
					
					if(ExNumRows($subresult)>0){
					echo "<td colspan='9' align='center'>".$row['especialidad'];?>  <img title="Disponibilidad Grupal" src="/Imgs/s_process.png" style="cursor:hand" onClick="location.href='NewDispoGrupalMed.php?DatNameSID=<? echo $DatNameSID?>&Especialidad=<? echo $row['especialidad']?>'">
					<? echo "</td></tr>";?>
                <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
                	<td>Nombre</td><td>Cedula<td>Registro Medico</td><td>Cargo</td><td>Telefono</td><td>Direccion</td><td>Estado</td><td colspan="3"></td>
                 <?	
					while($subrow=ExFetchArray($subresult))
					{?>
                    	<tr align='center'><a name="<? echo $subrow['usu']?>">
					<?	echo "<td>".$subrow['usuario']."</td><td>".$subrow['cedula']."</td><td>".$subrow['rm']."&nbsp;</td><td>".$subrow['cargo']."</td><td>".$subrow['telefono']."&nbsp;</td><td>".$subrow['direccion']."&nbsp;</td><td>".$subrow['estadomed']."</td>";?>
                            <td>
                            	<button type="button" title="Editar" onClick="location.href='NewConfMedicos.php?DatNameSID=<? echo $DatNameSID?>&accion=editar&usuario=<? echo $subrow['usu']?>'">
                                	<img src="/Imgs/b_edit.png" style="cursor:hand" >	
                             	</button>
                            </td>
                            <td>
                            	<button type="button" title="Eliminar" onClick="if(confirm('Desea eliminar este registro?')){location.href='ConfMedicos.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Usuario=<? echo $subrow['usu']?>';}">
                                	<img  style="cursor:hand"  src="/Imgs/b_drop.png">
                               	</button>
                            </td>
                	<?	if($subrow['asistencial']==1){?>
                            <td> 
                            	<button type="button" title="Disponibilidad" 
                                	onClick="location.href='DisponibilidadMedicos.php?DatNameSID=<? echo $DatNameSID?>&Medico=<? echo $subrow['usu']?>'">
	                                <img  src="/Imgs/s_process.png" style="cursor:hand" >
                              	</button>                              
                            </td>
                	<?	}
						else{?>
                        	<td> 
                            	<button type="button" >
                            		<img title="No Aplica" src="/Imgs/s_process_gray.png" style="cursor:hand">
                              	</button>
                            </td> 
                  	<?	}?>
                    	</a>
                        </tr>
					<? }?>
                    
			<tr> <td colspan="6"></td></tr><? }?>
	<? }?>
          <tr>
          	<td align="center" colspan="9"><input type="button" onClick="location.href='NewConfMedicos.php?DatNameSID=<? echo $DatNameSID?>&accion=crear'" value="Nuevo"></td>
          </tr>      
</table><br>
</form>
</body>
</body>
</html>
