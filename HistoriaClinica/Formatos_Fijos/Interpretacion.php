<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>
<script language="javascript"> 
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
</script>
<?	

	if($Guardar){
	    

		
		$cons="select interpretacion,Detalle,FechaInterpretacion,Usuario,fechaini from salud.plantillaprocedimientos where cedula='$Paciente[1]' and compania='$Compania[0]' and numservicio=$Numserv and numprocedimiento=$NumProced";
//echo $cons;
$res=ExQuery($cons);
$fila=ExFetch($res);

	if(!$fila[2]){$fila[2]="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";}
	$date2="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
	$s = strtotime($date2)-strtotime($fila[2]);
	$d = intval($s/86400);
	$s -= $d*86400;
	$d = $d*1440;
	$m = intval($s/60) + $d;
	if($m>60){$ReadOnly=" readonly ";}
	else{$ReadOnly="";
		
		$cons_="select fechainterpretacion  from salud.plantillaprocedimientos where cedula='$Paciente[1]' and compania='$Compania[0]' and numservicio=$Numserv and numprocedimiento=$NumProced";
		$res_=ExQuery($cons_); $fila_=ExFetch($res_);
		if(!$fila_[0]){
        $cons="update salud.plantillaprocedimientos set fechainterpretacion='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]', 
		interpretacion='$Interpretacion',usuariointerpretacion='$usuario[1]'
		where cedula='$Paciente[1]' and compania='$Compania[0]' and numservicio=$Numserv and numprocedimiento=$NumProced";
		//echo $cons;
		$res=ExQuery($cons);
        }
else{
$cons="update salud.plantillaprocedimientos set
		interpretacion='$Interpretacion',usuariointerpretacion='$usuario[1]'
		where cedula='$Paciente[1]' and compania='$Compania[0]' and numservicio=$Numserv and numprocedimiento=$NumProced";
		//echo $cons;
		$res=ExQuery($cons);
}		
		}
		
		$cons="select tipoformato,formato,id_item from salud.formatolabext where compania='$Compania[0]'";
		$res=ExQuery($cons); $fila=ExFetch($res);
		$TF=$fila[0]; $Formato=$fila[1]; $Id_Item=$fila[2];	
		$NumCampo="CMP".substr("00000",0,5-strlen($Id_Item)).$Id_Item;
		$cons="select tblformat from historiaclinica.formatos where compania='$Compania[0]' and formato='$Formato' and tipoformato='$TF'";
		$res=ExQuery($cons); $fila=ExFetch($res);
		$Tbl=$fila[0];
		
		if($Tbl){
			$cons="select * from salud.plantillaprocedimientos where cedula='$Paciente[1]' and compania='$Compania[0]' and numservicio=$Numserv and numprocedimiento=$NumProced";
			$res=ExQuery($cons);
			$row=ExFetchArray($res);
			if($row['numorden']){
				$cons="select cargo from salud.medicos where compania='$Compania[0]' and usuario='".$row['usuario']."'";
				$res=ExQuery($cons); $fila=ExFetch($res); $CargoMed=$fila[0];
				
					$cons3="select id_historia from histoclinicafrms.tbl00004 where compania='$Compania[0]' and cedula='$Paciente[1]' order by id_historia desc";
					$res3=ExQuery($cons3); 
					$fila3=ExFetch($res3); 
					$Id_h2=$fila3[0]+1;	
					//echo $Id_h2;
					$cupatencion="890601- CUIDADO (MANEJO) INTRAHOSPITALARIO POR MEDICINA GENERAL";
					$cons2="insert into histoclinicafrms.tbl00004 
					(formato,tipoformato,id_historia,usuario,cargo,fecha,hora,cedula,ambito,numservicio,compania,dx1,tipodx,numproced,cmp00007,finalidadconsult,causaexterna) 
					values
					('NOTAS EVOLUCION','$TF',$Id_h2,'$usuario[1]','Medico General','$ND[year]-$ND[mon]-$ND[mday]','$ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]','".$row['ambitoreal']."',$Numserv,'$Compania[0]','".$row['diagnostico']."','".$row['tipodx']."',$NumProced,'<br><b>Interpretacion Laboratorio : </b><br>$cupatencion.<br>$Interpretacion','10','15')";
					//echo $cons;
					$res2=ExQuery($cons2);
				
				$cons="Select * from Salud.PacientesxPabellones where Cedula='$Paciente[1]' and Estado='AC' and Compania='$Compania[0]'";
				$res=ExQuery($cons,$conex);
				$fila=ExFetchArray($res);
				$Unidad=$fila['pabellon'];
				
				$cons="select id_historia from histoclinicafrms.$Tbl where compania='$Compania[0]' and numproced=$NumProced and cedula='$Paciente[1]'";				
				//echo $cons;
				$res=ExQuery($cons); if(ExNumRows($res)){$BanUpdate=1;}
				if($BanUpdate){
					$cons="update histoclinicafrms.$Tbl 
					set $NumCampo='$Interpretacion',usuario='$usuario[1]',fecha='$ND[year]-$ND[mon]-$ND[mday]',hora='$ND[hours]:$ND[minutes]:$ND[seconds]' 
					where compania='$Compania[0]' and cedula='$Paciente[1]' and numproced=$NumProced";
					$res=ExQuery($cons);
				}
				else{

					
					$cons="select id_historia from histoclinicafrms.$Tbl where compania='$Compania[0]' and cedula='$Paciente[1]' order by id_historia desc";
					$res=ExQuery($cons); 
					$fila=ExFetch($res); 
					$Id_h=$fila[0]+1;								
					$cons="insert into histoclinicafrms.$Tbl 
					(formato,tipoformato,id_historia,usuario,cargo,fecha,hora,cedula,ambito,numservicio,compania,dx1,tipodx,numproced,$NumCampo,finalidadconsult,causaexterna) 
					values
					('$Formato','$TF',$Id_h,'$usuario[1]','Medico General','$ND[year]-$ND[mon]-$ND[mday]','$ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]','".$row['ambitoreal']."',$Numserv,'$Compania[0]','".$row['diagnostico']."','".$row['tipodx']."',$NumProced,'$Interpretacion','10','15')";
					//('$Formato','$TF',$Id_h,'$usuario[1]','$CargoMed','$ND[year]-$ND[mon]-$ND[mday]','$ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]','".$row['ambitoreal']."',$Numserv,'$Compania[0]','".$row['diagnostico']."','".$row['tipodx']."',$NumProced,'$Interpretacion','10','15')";
					//echo $cons;
					$res=ExQuery($cons);
					$cons="insert into histoclinicafrms.cupsxfrms 
					(formato,tipoformato,id_historia,cedula,compania,numservicio,cup,id_item) values
					('$Formato','$TF',$Id_h,'$Paciente[1]','$Compania[0]',$Numserv,'".$row['cup']."',$Id_Item)";
					$res=ExQuery($cons);
					

				}
			}
		}
		
		$cons2="insert into histoclinicafrms.cupsxfrms (formato,tipoformato,id_historia,cedula,compania,numservicio,cup,id_item)";
		if($Laboratorio){
			$cons1="select interpretacion from histoclinicafrms.ayudaxformatos where compania='$Compania[0]' and cedula='$Paciente[1]' and id_historia=$Id_Historia and formato='$Formato'
			and tipoformato='$TipoFormato' and numservicio=$Numserv and numproced=$NumProced";
			//echo $cons1."<br>";
			$res1=ExQuery($cons1);
			if(ExNumRows($res1)>0){
				$cons="update histoclinicafrms.ayudaxformatos set fechainterpretacion='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',usuario='$usuario[1]'
				,interpretacion='$Interpretacion' where compania='$Compania[0]' and cedula='$Paciente[1]' and id_historia=$Id_Historia and formato='$Formato'
				and tipoformato='$TipoFormato' and numservicio=$Numserv and numproced=$NumProced";
			}
			else{
				$cons="insert into histoclinicafrms.ayudaxformatos (compania,usuario,fecha,formato,tipoformato,id_historia,cedula,numservicio,fechainterpretacion,interpretacion,numproced)
				values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Formato','$TipoFormato',$Id_Historia,'$Paciente[1]'
				,$Numserv,'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Interpretacion',$NumProced)";				
			}
			$res=ExQuery($cons);?>
			<script language="javascript">//CerrarThis();parent.document.FORMA.submit();</script><?
		}
		else{
			?><script language="javascript">parent.location.href='AyudasDiagnosticas.php?DatNameSID=<? echo $DatNameSID?>';</script><?
		}
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
</head>

<body background="/Imgs/Fondo.jpg">
<?
$cons="select interpretacion,Detalle,FechaInterpretacion,Usuario,fechaini from salud.plantillaprocedimientos where cedula='$Paciente[1]' and compania='$Compania[0]' and numservicio=$Numserv and numprocedimiento=$NumProced";
//echo $cons;
$res=ExQuery($cons);
$fila=ExFetch($res);

	if(!$fila[2]){$fila[2]="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";}
	$date2="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
	$s = strtotime($date2)-strtotime($fila[2]);
	$d = intval($s/86400);
	$s -= $d*86400;
	$d = $d*1440;
	$m = intval($s/60) + $d;
	if($m>60){$ReadOnly=" readonly ";}
	else{$ReadOnly="";}
	
	$cons2="Select Nombre from Central.usuarios where Usuario='$fila[3]'";
	$res2=ExQuery($cons2);
	$fila2=ExFetch($res2);


if(!$fila[0]){$fila[0]="$fila[1] (Solicitado x $fila2[0] el $fila[4])<br> \n";}
?>
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<form name="FORMA" method="post">
<table border="1" bordercolor="#e5e5e5"  align="center" style='font : normal normal small-caps 13px Tahoma;'>    	
  	<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
     	<td>Interpretacion Clinica</td>
 	</tr>
    <tr><td><textarea <? echo $ReadOnly?> name="Interpretacion" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" cols="50" rows="13"><? echo $fila[0]?></textarea></td></tr>
    <tr align="center"><td><input type="submit" name="Guardar" value="Guardar"></td></tr>
</table>    
<input type="hidden" name="Numserv" value="<? echo $Numserv?>">
<input type="hidden" name="NumProced" value="<? echo $NumProced?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Formato" value="<? echo $Formato?>">
<input type="hidden" name="TipoFormato" value="<? echo $TipoFormato?>">
<input type="hidden" name="Id_Historia" value="<? echo $Id_Historia?>">
<input type="hidden" name="Laboratorio" value="<? echo $Laboratorio?>">
</form>       
</body>
</html>
