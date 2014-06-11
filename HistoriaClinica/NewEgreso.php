<?php


	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
	
	if($ND[mon]<10){$cero='0';}else{$cero='';}
	if($ND[mday]<10){$cero1='0';}else{$cero1='';}
	$FechaComp="$ND[year]-$cero$ND[mon]-$cero1$ND[mday]";
	
	$consCargo = "SELECT cargo FROM salud.medicos WHERE usuario = '$usuario[1]'";
	$resCargo = ExQuery($consCargo);
	$filaCargo = ExFetch($resCargo);
	$cargo = $filaCargo[0]; 
	
	$parametro2 = $_GET["par"];
	
	if($cargo == 'AUXILIAR DE ENFERMERIA' || $cargo == 'JEFE DE ENFERMERIA' ){
		$area = "ASISTENCIAL";
		$egreso = "AND egreso =1";
	}
	
	if($cargo == 'SIAU' ){
		$area = "ADMINISTRATIVA";
		//$egreso = "AND egreso = (SELECT CASE WHEN usuinicio IS NULL AND tbl00030.cedula = ordenesmedicas.cedula  THEN 0 ELSE 3 END )";
		
		if($parametro2 == '1'){
			$egreso = "AND egreso = 1 AND ordenesmedicas.estado = 'AC'";
			$t = 1;
		}
		else if($parametro2 == '3'){
		   $egreso = "AND egreso = 3 AND ordenesmedicas.estado = 'AN' ";
		   $t = 3;
		}else{
			$egreso = "AND egreso = 0 AND ordenesmedicas.estado = 'AC' ";
			$t = 0;
		}
	}
	
	if($Salida){
		if($cargo == 'SIAU' ){
			$egreso = $yy;
		}
		if($cargo == 'AUXILIAR DE ENFERMERIA' || $cargo == 'JEFE DE ENFERMERIA' ){
			$egreso = 2;
			$t = 2;
		}
		
		if($Ambito){$Amb="and tiposervicio='$Ambito'";}	
		$cons="select numservicio from salud.servicios where cedula='$Ced' and compania='$Compania[0]' and estado='AC' $Amb";
		$res = ExQuery($cons);echo ExError($res);
		$fila = ExFetch($res);
		 $NumServ=$fila[0];		
				
		$cons2="select elemento from salud.elementoscustodia where cedula='$Ced' and numservicio=$NumServ and compania='$Compania[0]' and fechasalida is null";
		$cons2;
		$res2 = ExQuery($cons2);echo ExError($res2);
				
		if(ExNumRows($res2)>0)
		{
			$NoSalida=1;
		}
		else{	
		
			    $cons="update salud.servicios SET egreso=$yy where cedula='$Ced' and compania='$Compania[0]' and estado='AC' and numservicio='$NumServ'";
				//echo "$cons<br>";
			    $res=ExQuery($cons);echo ExError();	
			if($cargo == 'SIAU' ){				
				$cons="update salud.servicios set fechaegr='$FechaRealEgr $HoraEgr:$MinEgr:$SegsEgr',notasegreso='$Notas',estado='AN',usuegreso='$usuario[1]', egreso=$yy
				where cedula='$Ced' and compania='$Compania[0]' and estado='AC' and numservicio='$NumServ'";
				//echo "$cons<br>";
				$res=ExQuery($cons);echo ExError();	
				
				$cons="update salud.pacientesxpabellones set estado='AN',fechae='$ND[year]-$ND[mon]-$ND[mday]',horae='$ND[hours]:$ND[minutes]:$ND[seconds]',idcama=0 where
				cedula='$Ced' and compania='$Compania[0]' and estado='AC' and numservicio=$NumServ";
				echo "$cons<br>";
				$res=ExQuery($cons);echo ExError();	
			
				$cons="update salud.plantillainterprogramas set estado='AN' where compania='$Compania[0]' and cedula='$Ced' and numservicio=$NumServ and estado='AC'";
				//echo "$cons<br>";
				$res=ExQuery($cons);echo ExError();	
			
				$cons="update salud.plantillaprocedimientos set estado='AN',fechafin='$ND[year]-$ND[mon]-$ND[mday]' where compania='$Compania[0]' and cedula='$Ced' and numservicio=$NumServ and 
				estado='AC'";
				//echo "$cons<br>";
				$res=ExQuery($cons);echo ExError();	
			
				$cons="update salud.plantilladietas set fechafin='$FechaRealEgr',estado='AN' where compania='$Compania[0]' and cedula='$Ced' and numservicio=$NumServ and 
				estado='AC'";
				//echo "$cons<br>";
				$res=ExQuery($cons);echo ExError();	
			
				$cons="update salud.plantillanotas set fechafin='$FechaRealEgr',estado='AN' where compania='$Compania[0]' and cedula='$Ced' and numservicio=$NumServ and 
				estado='AC'";		
				//echo "$cons<br>";
				$res=ExQuery($cons);echo ExError();	
			
				$cons="update salud.plantillamedicamentos set fechafin='$FechaRealEgr',estado='AN' where compania='$Compania[0]' and cedpaciente='$Ced' and numservicio=$NumServ 
				and estado='AC'"; 
				$res=ExQuery($cons);echo ExError();	
					
				$cons="update salud.ordenesmedicas set estado='AN',acarreo=0 where compania='$Compania[0]' and cedula='$Ced' and estado='AC'";
				$res=ExQuery($cons);
				//echo $cons;
				//$DiaAlerta=$ND[mday]-1;
				$cons="update salud.pagadorxservicios set fechafin='$ND[year]-$ND[mon]-$ND[mday]' where compania='$Compania[0]' and numservicio=$NumServ and fechafin is null";
				$res=ExQuery($cons);
				
				$cons="update salud.pacientesxpabellones set estado='AN',fechae='$ND[year]-$ND[mon]-$ND[mday]' where compania='$Compania[0]' and cedula='$Ced' and estado='AC'";
				$res=ExQuery($cons);
				
				$cons="update salud.alertasingreso 
				set fechafin='$FechaRealEgr',usuariomod='$usuario[1]',fechamod='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
				where compania='$Compania[0]' and cedula='$Ced'";
				$res=ExQuery($cons);echo ExError();	
			}
			
			while(list($cad,$val) = each($Pregunta)){
				if($val){
					$cons ="insert into salud.checkeos (compania,cedula,usuario,tipo,fecha,pregunta,numservicio,respuesta) values
					('$Compania[0]','$Ced','$usuario[1]','Egreso','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$cad',$NumServ,'$val')";				
					$res = ExQuery($cons);echo ExError();
				}
			}
			
			
			if($area == "ADMINISTRATIVA"){
		?>	<script language="javascript">
				open('BoletaEgreso.php?DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $Ced?>&NumServ=<? echo $NumServ?>&Ambito=<? echo $Ambito?>','','width=500,height=350');
				location.href='Egreso.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>';
	        </script><?
		}else{
		?>	<script language="javascript">
				location.href='Egreso.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>';
	        </script><?
		}
		}
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
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
	
	//alert(document.getElementById('Aux'+c).value);
	//alert(document.FORMA.FechaRealEgr.value);
	/*if(document.FORMA.FechaRealEgr.value=="")
		{
			alert("Diligencie la Fecha de Egreso");
			document.FORMA.FechaRealEgr.focus();			
			return false;
		}*/
	if(document.FORMA.FecEpic.value>document.FORMA.FechaRealEgr.value){
		alert("La fecha de egreso debe ser mayor o igual a la fecha de la epicriisis!!!");return false;
	}
	if(document.FORMA.FechaRealEgr.value==""){
		alert("El campo de la fecha debe ser obligatorio!!");return false;
	}
	
	var c=1;
	
	for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
	{ 				
		var elemento = document.forms[0].elements[i]; 
		//alert(elemento.type+" "+elemento.value);
		if (elemento.type == "select-one") 
		{ 
			if(document.getElementById('Aux'+c).value=="1"&&document.getElementById('Pregunta'+c).value==''){
				alert("La pregunta "+document.getElementById('Aux'+c).name+" es obligatoria"); return false;
			}	
			c++;
		} 
	} 
	
}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="FecEpic" value="<? echo $FecEpic?>">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4">
	 <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>Cedula</td><td>Nombre</td><td>Aseguradora</td><td>Contrato</td><td>No. Contrato</td></tr>
<? 	

	if($Ambito){$Amb="and tiposervicio='$Ambito'";}
	$cons="select primnom,segnom,primape,segape,identificacion,servicios.numservicio 
	from salud.ordenesmedicas,salud.servicios,central.terceros, histoclinicafrms.tbl00030 
	where ordenesmedicas.compania='$Compania[0]' 
	and servicios.compania='$Compania[0]' 
	and terceros.compania='$Compania[0]' 
	and tipoorden='Orden Egreso' 
	and servicios.estado='AC' $Amb 
	and ordenesmedicas.cedula=servicios.cedula
	and ordenesmedicas.cedula=terceros.identificacion 
and ordenesmedicas.numservicio = servicios.numservicio
and ordenesmedicas.cedula = tbl00030.cedula
	and identificacion='$Ced'  $egreso";
	//echo $cons;
	$res=ExQuery($cons); 
	$fila=ExFetch($res);
	 $cons2="select primnom,segnom,primape,segape,contrato,nocontrato from central.terceros,salud.pagadorxservicios 
	where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and terceros.identificacion=pagadorxservicios.entidad 
	and pagadorxservicios.numservicio= '$fila[5]' and '$FechaComp'>=fechaini and '$FechaComp'<=fechafin";	
	$res2=ExQuery($cons2);	
	//echo $cons2;  		  	
	if(ExNumRows($res2)>0){
		$fila2=ExFetch($res2);
		$EPS="$fila2[0] $fila2[1] $fila2[2] $fila2[3]"; $Contra="$fila2[4]"; $NoContra="$fila2[5]";
	}
	else{			
		$cons3="select primnom,segnom,primape,segape,contrato,nocontrato,fechafin from central.terceros,salud.pagadorxservicios 
		where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and terceros.identificacion=pagadorxservicios.entidad and 
		pagadorxservicios.numservicio= '$fila[5]' and '$FechaComp'>=fechaini";
		$res3=ExQuery($cons3);	
		//echo $cons3;
		if(ExNumRows($res3)>0){				
			$fila3=ExFetch($res3);
			if(!$fila3[6]){
				$EPS="$fila3[0] $fila3[1] $fila3[2] $fila3[3]"; $Contra="$fila3[4]"; $NoContra="$fila3[5]";	
			}
			else{
				$EPS=""; $Contra=""; $NoContra="";
			}
		}
		else{
			$EPS=""; $Contra=""; $NoContra="";
		}
	}
	?>
	<tr align='center'><? 
	echo "<td>$fila[4]</td><td>$fila[0] $fila[1] $fila[2] $fila[3]</td>";
	if($EPS){echo "<td>$EPS</td><td>$Contra</td><td>$NoContra</td></tr>";}else{echo "<td colspan='3'> - Sin Asegurador Activo - </td></tr>";}?>
	<tr>
    	<td colspan="5"><strong>Fecha Real Egreso</strong>
       	<?	if(!$FechaRealEgr){$FechaRealEgr=$FecEpic;}?>
        	<input type="text" name="FechaRealEgr"readonly onClick="popUpCalendar(this, FORMA.FechaRealEgr, 'yyyy-mm-dd')" value="<? echo $FechaRealEgr?>" style="width:80">
            <strong>Hora</strong>
            <select name="HoraEgr">
            <?	for($i=0;$i<24;$i++){					
					echo "<option value='$i'>$i</option>";
				}?>
            </select>:
            <select name="MinEgr">
            <?	for($i=0;$i<60;$i++){
					if($i<10){$cero1="0";}else{$cero1="";}
					echo "<option value='$i'>$cero1$i</option>";
				}?>
            </select>:
            <select name="SegsEgr">
            <?	for($i=0;$i<60;$i++){
					if($i<10){$cero1="0";}else{$cero1="";}
					echo "<option value='$i'>$cero1$i</option>";
				}?>
            </select>
    	</td>
  	</tr>
    <tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="5">Lista de Verificacion de Egreso</td></tr>
<?	$cons="select pregunta,obligatorio from salud.preguntasegreso where compania='$Compania[0]' AND area = '$area'";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){?>
	<?	$cont=1;
		while($fila=ExFetch($res)){?>
			<tr><td colspan="3"><? echo $fila[0]?></td>
            <td colspan="2" align="center">
               <select name="Pregunta[<? echo $fila[0]?>]" id="Pregunta<? echo $cont?>">	
            	<option></option>
                <option value="Si">Si</option>
                <option value="No">No</option>
           		</select>
           	</td>
            </tr>
            <input type="hidden" name="<? echo $fila[0]?>" id="Aux<? echo $cont?>" value="<? echo $fila[1]?>"> 
<?			$cont++;
		}
	}
	else{?>    
	    <tr><td colspan="5">No sen han ingresado preguntas de verificacion de egreso</td></tr>
<?	}
//echo $egreso;
if($egreso == 'AND egreso =1'){
?>
<br>
<table bordercolor="#e5e5e5" border="1"  align="center" style='font : normal normal small-caps 12px Tahoma;' cellpadding="4" style="width:70%" >
<tr ><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="8" style="width:50%">Devoluciones Pendientes a Farmacia</td></tr>
<tr><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">#</td>
    <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Fecha Entrega</td>
    <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Medicamento</td>
    <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Lote</td>
    <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Cum</td>
    <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Entrego Farmacia</td>
    <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Suministradas</td>
    <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Devolver</td></tr>
<?php 
$cons="SELECT a.fechadespacho, CONCAT (c.nombreprod1, ' ', c.presentacion, ' ', c.unidadmedida) as medicamento, 
a.lote, a.cum, a.cantidad as salio_farmacia, b.cantidad as entr_paciente,
(a.cantidad - b.cantidad) as devolver   
FROM consumo.movimiento a, salud.registromedicamentos b, consumo.codproductos c
WHERE a.cedula = '$Ced'
AND a.estado = 'AC'
AND b.cedula = a.cedula 
AND a.autoid = b.autoid
AND a.numorden = b.numorden
AND a.cum = c.cum ";

$res=ExQuery($cons);
if(ExNumRows($res)>0){
$cont=1;
$tot=0;
while($fila=ExFetch($res)){?>
<tr>
<td><? echo $cont ?></td>
<td><? echo $fila[0]?></td>
<td><? echo $fila[1]?></td>
<td><? echo $fila[2]?></td>
<td><? echo $fila[3]?></td>
<td><? echo $fila[4]?></td>
<td><? echo $fila[5]?></td>
<td><? echo $fila[6]?></td>
</tr>
<?
$tot += $fila[6];
$cont++;
}
?>
<tr><td align="left"  bgcolor="#FFFFCC" style="font-weight:bold" colspan=7>TOTAL A DEVOLVER </td><td bgcolor="#FFFFCC"><strong><?php echo $tot?></strong></td></tr>
<?
}

}
?>  
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4" style="width:70%">  
    <tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="5">Notas </td></tr>
    <tr><td colspan="5"><textarea name="Notas" style="width:100%" rows="6" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)"><? echo $Notas?></textarea></td></tr>
    <tr><td align="center" colspan="5"><input type="submit" value="Dar Salida" name="Salida"><input type="button" value="Cancelar" onClick="location.href='Egreso.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>'" ></td></tr>
</table>

<input type="hidden" name="Ced" value="<? echo $Ced?>">
<input type="hidden" name="Ambito" value="<? echo $Ambito?>">
<input type="hidden" name="NoSalida" value="">
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<?
if(!$NoSalida){$NoSalida="0";}
?>
<script language="javascript">
	if(<? echo $NoSalida?>==1)alert("Paciente con elementos en custodia activos!!!");
</script>
</form>    
</body>
</html>
