<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Aprobar){
		while( list($cad,$val) = each($Elmt))
		{
			//echo "cad=$cad val=$val ";
			if($TipoPlan=="Medicamentos"){
				$cons="update Consumo.TarifariosVenta set usuaprobado='$usuario[1]',fechaaprobado='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
				where compania='$Compania[0]' and Tarifario='$cad' ";
				//echo $cons;				
			}
			else{
				$cons="update Contratacionsalud.planestarifas set estado='AC',usuaprobado='$usuario[1]',fechaaprobado='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
				where compania='$Compania[0]' and nombreplan='$cad' ";
			}
			$res=ExQuery($cons); 
		}
		?><script language="javascript">location.href="ConfAprobPlanTarif.php?DatNameSID=<? echo $DatNameSID?>";</script><?
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function ChequearTodos(chkbox) 
	{ 
		for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
		{ 
			var elemento = document.forms[0].elements[i]; 
			if (elemento.type == "checkbox") 
			{ 
				elemento.checked = chkbox.checked 
			} 
		} 
	}
	function Validar()
	{
		var ban=0;
		for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
		{ 
			var elemento = document.forms[0].elements[i]; 
			if (elemento.type == "checkbox") 
			{ 
				if(elemento.checked&&elemento.name!='TodosM'&&elemento.name!='TodosC'){
					ban=1
				}
			} 	
		} 
		if(ban==0){
			alert("Debe seleccionar almenos un Elemento!!!");return false;
		}
		else{
			document.FORMA.Guardar.value=1;
			document.FORMA.submit();		
		}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">   
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td colspan="3">Tipo De Plan</td>
    </tr>
    <tr align="center">
    	<td colspan="3">
        	<select name="TipoPlan" onChange="document.FORMA.submit()"><option></option>            	
                <option value="Medicamentos" <? if($TipoPlan=='Medicamentos'){ echo "selected";}?>>Medicamentos</option>
                <option value="Procedimientos" <? if($TipoPlan=='Procedimientos'){ echo "selected";}?>>Procedimientos</option>
        	</select>
     	</td>
    </tr>    
<?	if($TipoPlan=="Medicamentos"){		
		$cons="Select Tarifario from Consumo.TarifariosVenta where Compania='$Compania[0]' and usuaprobado is null and fechaaprobado is null order by Tarifario";
		$res=ExQuery($cons);
		if(ExNumRows($res)>0){?>            	
           	<TR bgcolor="#e5e5e5" style="font-weight:bold" align="center">
				<TD>Plan</TD><td title="Seleccionar Todos"><input type="checkbox" name="TodosM" onClick="ChequearTodos(this);"></td>
           	</TR>	
        	<?	while($fila=ExFetch($res)){?>
				<tr onMouseOver="this.bgColor='#AAD4FF'"  onMouseOut="this.bgColor=''" align="center">
                	<td><? echo $fila[0]?></td><td><input type="checkbox" name="Elmt[<? echo $fila[0]?>]"></td>
   	            </tr>
            <?	}
		}
		else{
			echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='center'><td>No Se Encontraron Planes De Medicamentos</td></tr>";
		}	
	}
	elseif($TipoPlan=="Procedimientos"){
		$cons="Select nombreplan from Contratacionsalud.Planestarifas where Compania='$Compania[0]' and usuaprobado is null and fechaaprobado is null 
		order by nombreplan";
		$res=ExQuery($cons);
		if(ExNumRows($res)>0){?> 
        	<TR bgcolor="#e5e5e5" style="font-weight:bold" align="center">
				<TD>Plan</TD><td title="Seleccionar Todos"><input type="checkbox" name="TodosC" onClick="ChequearTodos(this);"></td>
           	</TR>
            <?	while($fila=ExFetch($res)){?>
				<tr onMouseOver="this.bgColor='#AAD4FF'"  onMouseOut="this.bgColor=''" align="center">
                	<td><? echo $fila[0]?></td><td><input type="checkbox" name="Elmt[<? echo $fila[0]?>]"></td>
   	            </tr>
            <?	}
		}
		else{
			echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='center'><td>No Se Encontraron Planes De Procedimientos</td></tr>";
		}
	}?>
    <tr align="center">           
    	<td colspan="4"><input type="submit" value="Aprobar" name="Aprobar"><input type="button" value="Cancelar" onClick="location.href='ConfAprobPlanTarif.php?DatNameSID=<? echo $DatNameSID?>'"></td>
   	</tr>
</table>
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>
