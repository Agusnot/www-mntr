<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Guardar){
		$cons="select numservicio from salud.servicios where cedula='$Paciente[1]' and compania='$Compania[0]' and estado='AC'";
		$res = ExQuery($cons);echo ExError($res);
		$fila = ExFetch($res);
		$NumServ=$fila[0];		
		$cons="select numorden from salud.ordenesmedicas where cedula='$Paciente[1]' and compania='$Compania[0]' and idescritura='$IdEscritura' order by numorden desc";
		//echo $cons;
		$res = ExQuery($cons);echo ExError($res);
		if(ExNumRows($res)>0){
			$fila = ExFetch($res);		
			$AutoId = $fila[0]+1;
		}
		else{
			$AutoId=1;
		}
		$Aux2=$Aux;
		while( list($cad,$val) = each($Aux2)){
			//echo "cad=$cad val=$val <br>";			
			$c='';$v='';
			$ban='0';
			$Interprog2=$Interprog;
			if($Interprog2!=''){
				while(list($c,$v)=each($Interprog2)){
					//echo "c=$c <br>\n";
					if($c==$cad){
						if($val==2){
							$Detalle="Remision Interprogramas:$cad";
							$cons="insert into salud.plantillainterprogramas(compania,usuario,cedula,fechaini,interprograma,detalle,numservicio,estado) values        
					  		('$Compania[0]','$usuario[1]','$Paciente[1]','$ND[year]-$ND[mon]-$ND[mday]','$cad','$Detalle',$NumServ,'AC')";
							$res = ExQuery($cons);echo ExError($res);
							//echo "$cons<br>";
							$cons="insert into salud.ordenesmedicas(compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo) 
							values ('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] 
							$ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$NumServ,'$Detalle',$IdEscritura,$AutoId,'$usuario[1]','Interprograma','AC',1)";				
							$res = ExQuery($cons);echo ExError($res);
							//echo "$cons<br>\n";
							$AutoId++;						
						}
						$ban=1;
					}				
				}
			}
			//echo "ban=$ban";
			if($ban==0&&$val==1){
				$cons="update salud.plantillainterprogramas set fechafin='$ND[year]-$ND[mon]-$ND[mday]',estado='AN' where compania='$Compania[0]' and cedula='$Paciente[1]' and 	 								 				numservicio='$NumServ' and estado='AC' and interprograma='$cad'";
				$res = ExQuery($cons);echo ExError($res);
				//echo "$cons<br>";
				$Detalle="Suspender Remision Interprogramas:$cad";
				$cons="insert into salud.ordenesmedicas(compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo) values
				('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$NumServ,'$Detalle',$IdEscritura,$AutoId,'$usuario[1]','Interprograma','AC',0)";				
				$res = ExQuery($cons);echo ExError($res);
				//echo "$cons<br>\n";
				$AutoId++;	
			}		
		}
		?>
		<script language="javascript">
			location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>';
		</script>
	<?
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
function ChequearTodos(chkbox) { 
	for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
	{ 
		var elemento = document.forms[0].elements[i]; 
		if (elemento.type == "checkbox") 
		{ 
			elemento.checked = chkbox.checked 
		} 
	} 
}

</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" >
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
<?	$cons="select interprograma from salud.interprogramas where compania='$Compania[0]' group by interprograma order by interprograma";
	$res=ExQuery($cons);echo ExError();
	$cons3="select numservicio from salud.servicios where cedula='$Paciente[1]' and compania='$Compania[0]' and estado='AC'";
	$res3 = ExQuery($cons3);echo ExError();
	//echo $cons3;
	$fila3 = ExFetch($res3);
	$NumServ=$fila3[0];	

	if(ExNumRows($res)>0){?>		<tr><td colspan="2"  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Interconsultas</td></tr>
        <tr><td align="left"><input type="checkbox" name="Todos" onClick="ChequearTodos(this);" title="Seleccionar Todos"> SELECCIONAR TODOS</td></tr>
<?		while($fila=ExFetch($res)){
			
			$cons2="select interprograma from salud.plantillainterprogramas where compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio=$NumServ and estado='AC' and         		
			interprograma='$fila[0]'";
			$res2 = ExQuery($cons2);echo ExError();
			$ban=0;
			if(ExNumRows($res2)>0){$ban=1;}?>
			<tr><td><input type="checkbox" name="Interprog[<? echo $fila[0]?>]" <? if($ban){?> checked <? }?>> <? echo $fila[0]?></td></tr>
            <input type="hidden" name="Aux[<? echo $fila[0]?>]" value="<? if($ban){ echo '1';}else{echo '2';}?>">
<?		}?>
		<tr><td align="center" colspan="2"><input type="submit" name="Guardar" value="Guardar"><input type="button" value="Cancelar" onClick="location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"></tr>
<?	}
	else{?>	
    	<tr><td colspan="2"  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">No se ha ingresado Interconsulta</td></tr>
		<tr><td align="center" colspan="2"><input type="button" value="Cancelar" onClick="location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"></tr>
<?	}?>
</table>
<input type="hidden" name="Tiene" value="<? echo $Tiene?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
