<?
	if($DatNameSID){session_name("$DatNameSID");}	
	session_start();
	$ND=getdate();
	include("Funciones.php");
	function QuitaSignos($Contenido)
	{
		$Contenido=str_replace("Ñ","N",$Contenido);
		$Contenido=str_replace("ñ","n",$Contenido);
		$Contenido=str_replace("á","a",$Contenido);
		$Contenido=str_replace("é","e",$Contenido);
		$Contenido=str_replace("í","i",$Contenido);
		$Contenido=str_replace("ó","o",$Contenido);
		$Contenido=str_replace("ú","u",$Contenido);
		$Contenido=str_replace("°","o",$Contenido);
		$Contenido=str_replace("º","o",$Contenido);
		$Contenido=str_replace("ª","a",$Contenido);
		$Contenido=str_replace("Á","A",$Contenido);
		$Contenido=str_replace("É","E",$Contenido);
		$Contenido=str_replace("Í","I",$Contenido);
		$Contenido=str_replace("Ó","O",$Contenido);
		$Contenido=str_replace("Ú","U",$Contenido);
		$Contenido=str_replace("Ü","U",$Contenido);
		$Contenido=str_replace("ü","u",$Contenido);
		$Contenido=str_replace("Ð","N",$Contenido);
		$Contenido=str_replace("¾","O",$Contenido);
		$Contenido=str_replace("±","N",$Contenido);
		$Contenido=str_replace("à","a",$Contenido);
		$Contenido=str_replace("è","e",$Contenido);
		$Contenido=str_replace("ì","i",$Contenido);
		$Contenido=str_replace("ò","o",$Contenido);
		$Contenido=str_replace("ù","u",$Contenido);

		$Contenido=str_replace("À","A",$Contenido);
		$Contenido=str_replace("È","E",$Contenido);
		$Contenido=str_replace("Ì","I",$Contenido);
		$Contenido=str_replace("Ò","O",$Contenido);
		$Contenido=str_replace("Ù","U",$Contenido);

		$Contenido=str_replace("½","1/2",$Contenido);
		$Contenido=str_replace("¾","3/4",$Contenido);
		$Contenido=str_replace("´",".",$Contenido);
		$Contenido=str_replace("–"," ",$Contenido);
		$Contenido=str_replace("ô","o",$Contenido);
		$Contenido=str_replace("¨"," ",$Contenido);
		$Contenido=str_replace("-","",$Contenido);
		$Contenido=str_replace("/"," ",$Contenido);
		$Contenido=str_replace("ò","o",$Contenido);
		$Contenido=str_replace("à","a",$Contenido);
		$Contenido=str_replace("Ç","a",$Contenido);
		$Contenido=str_replace("!","a",$Contenido);
		$Contenido=str_replace("¡","a",$Contenido);
		$Contenido=str_replace("ö","o",$Contenido);
		$Contenido=str_replace("ï","i",$Contenido);
		$Contenido=str_replace("•","*",$Contenido);
		$Contenido=str_replace("\" ","",$Contenido);
		$Contenido=str_replace("\\","",$Contenido);

		return $Contenido;
	}
?>
<head></head>
<?	
	$conex = pg_connect("dbname=sistema user=apache password=Server*1982") or die ('no establecida');
	$cons="select numinforme from reportes3047.inc_bd where compania='$Compania[0]' order by numinforme desc";
	$res=pg_query($conex,$cons);
	$fila=pg_fetch_row($res);
	$NumInf=$fila[0]+1;		

	$cons = "Select Identificacion,Primape,Segape,Primnom,Segnom from Central.Terceros where Tipo='Asegurador' and Compania = '$Compania[0]' 
	group by Identificacion,Primape,Segape,Primnom,Segnom order by Primape,Segape,Primnom,Segnom";				
	$res = pg_query($conex,$cons); 
	
	while($fila=pg_fetch_row($res)){
		$EPS[$fila[0]]=array($fila[0],$fila[1]." ".$fila[2]." ".$fila[3]." ".$fila[4]);
	}
	$cons = "Select codigo,cobertura from reportes3047.coberturasalud order by cobertura";				
	$res = pg_query($conex,$cons); 
	while($fila=pg_fetch_row($res)){
		$Cobertura[$fila[0]]=array($fila[0],$fila[1]);
	}
	$cons = "Select codigo,tipodoc from central.tiposdocumentos order by tipodoc";				
	$res = pg_query($conex,$cons); 
	while($fila=pg_fetch_row($res)){
		$TiposDocument[$fila[0]]=array($fila[0],$fila[1]);
	}	
	
	if($Guardar){
		$conex = mysql_connect('localhost','root','Server*1492') or die ('no establecida');
		mysql_select_db("BDAfiliados", $conex);
		$cons="select Primer_Apellido,Segundo_Nombre,Primer_Nombre,Segundo_Nombre,Tipo_Documento,Fecha_Nacimiento,Dpto_Declaracion,Muni_Declaracion
		from Afiliados where Identificacion='$Identificacion'";
		$res=mysql_query($cons,$conex);
		$fila=mysql_fetch_row($res);
		$Primape=QuitaSignos($fila[0]); $Segmape=QuitaSignos($fila[1]); $Primnom=QuitaSignos($fila[2]); $Segnom=QuitaSignos($fila[3]); $TipoDoc=$fila[4];  
		$Dpto=$fila[6]; $Muni=$fila[7];
		$FechaNac=explode("/",$fila[5]);
		$FecNac="'$FechaNac[2]-$FechaNac[1]-$FechaNac[0]'";
		if($fila[5]==""){$FecNac="null";}
		//echo $cons;
		
		if($MalPrimApe=='on'){$MalPrimApe="1";}else{$MalPrimApe="0";}
		if($MalSegApe=='on'){$MalSegApe="1";}else{$MalSegApe="0";}
		if($MalPrimNom=='on'){$MalPrimNom="1";}else{$MalPrimNom="0";}
		if($MalSegNom=='on'){$MalSegNom="1";}else{$MalSegNom="0";}
		if($MalTipoDoc=='on'){$MalTipoDoc="1";}else{$MalTipoDoc="0";}
		if($MalIdentificacion=='on'){$MalIdentificacion="1";}else{$MalIdentificacion="0";}
		if($MalFecNac=='on'){$MalFecNac="1";}else{$MalFecNac="0";}
		$FecNacFisico="$AnioNacFisico-$MesNacFisico-$DiaNacFisico";
		
		$cons="insert into reportes3047.inc_bd (compania,usuario,fecha,numinforme,entidad,tipodocumento,tipoinconsistencia,primape,segape,primnom,segnom,identificacion
		,fecnac,departamento,municipio,coberturasalud,primapefisico,segapefisico,primnomfisico,segnomfisico,fecnacfisico,observaciones,malprimape,malsegape,malprimnom,malsegnom
		,tipodocfisico,identificacionfisico,maltipodoc,malidentificacion,malfecnac) values
		('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$NumInf,'$Entidad','$TipoDoc','$TipoIncon','$Primape','$Segmape','$Primnom'
		,'$Segnom','$Identificacion',$FecNac,'$Dpto','$Muni','$CoberturaSalud','$PrimApeFisico','$SegApeFisico','$PrimNomFisico','$SegNomFisico','$FecNacFisico','$Observaciones'
		,'$MalPrimApe','$MalSegApe','$MalPrimNom','$MalSegNom','$TipodDoc','$IdentificacionFisico','$MalTipoDoc','$MalIdentificacion','$MalFecNac')";
		$conex = pg_connect("dbname=sistema user=apache password=Server*1982") or die ('no establecida');			
		$res=ExQuery($cons); echo pg_last_error($conex);
		//echo $cons;
?>		<script language="javascript">
			location.href="BusquedaAfiliados.php?Buscar=1&DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>";
			open('InformePDFIncBD.php?DatNameSID=<? echo $DatNameSID?>&NumInf=<? echo $NumInf?>&Identificacion=<? echo $Identificacion?>','','width=1100,height=600');
			//open('InformePDFIncBD.php?DatNameSID=<? echo $DatNameSID?>&NumInf=2&Identificacion=<? echo $Identificacion?>','','width=1100,height=600');
       	</script><?
	}
?>
<html>
<head>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.PrimApeFisico.value==""){alert("Debe digitar el Primer Apellido!!!"); return false;}
		if(document.FORMA.PrimNomFisico.value==""){alert("Debe digitar el Primer Nombre!!!"); return false;}
		if(document.FORMA.IdentificacionFisico.value==""){alert("Debe digitar el Numero de Identificacion!!!"); return false;}
		if(document.FORMA.FecNacFisico.value==""){alert("Debe seleccionar la fecha de nacimiento!!!"); return false;}
		if(document.FORMA.TipodDoc.value==""){alert("Debe seleccionar el tipo de Identificacion!!!"); return false;}
		
	}
	
	function pulsar(e,txt) {
		//alert();
		if (navigator.appName == "Netscape") tecla = e.which;
		else tecla = e.keyCode;
		if (tecla == 13) return false;
		else return true;
	}

</script>
</head>

<body background="/Imgs/Fondo.jpg"> 
<form name="FORMA" method="post"  onSubmit="return Validar()">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="1">
<tr>	
   	<td align="right" colspan="10"><strong>Informe No.</strong> &nbsp;<? echo $NumInf?></td>
    <input type="hidden" name="NumInf" value="<? echo $NumInf?>">
</tr>
<tr>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="right">Entidad</td>
    <td colspan="3">
    	<select name="Entidad">
   	<?	foreach($EPS as $fila){
			if($fila[0]==$Entidad){
				echo "<option value='$fila[0]' selected>$fila[1]</option>";
			}
			else{
				echo "<option value='$fila[0]'>$fila[1]</option>";
			}
		}?>
        </select>
    </td>
</tr> 
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="right">Tipo de Inconsistencia</td>
	<td colspan="3">
<?	if($NoFind==""){?>    
    	<select name="TipoIncon">
        	<option selected value="Los datos de usuario no corresponden con los del documento de identificacion presentado">
            	Los datos de usuario no corresponden con los del documento de identificacion presentado
           	</option>
   	<?	if($TipoIncon=="El usuario no existe en la base de datos"){?>    		
            <option  value="El usuario no existe en la base de datos" selected>El usuario no existe en la base de datos</option>
	<?	}else{?>
			<option  value="El usuario no existe en la base de datos">El usuario no existe en la base de datos</option>
	<?	}?>
        </select>
<?	}
	else{?>
		<select name="TipoIncon">
        	<option  value="El usuario no existe en la base de datos" selected>El usuario no existe en la base de datos</option>
		</select>            
<?	}?>        
    </td>
</tr>  
<tr>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="right">Cobertura en Salud</td>
    <td colspan="3">
    	<select name="CoberturaSalud">
   	<?	foreach($Cobertura as $fila){
			if($fila[0]==$CoberturaSalud){
				echo "<option value='$fila[0]' selected>$fila[1]</option>";
			}
			else{
				echo "<option value='$fila[0]'>$fila[1]</option>";
			}
		}?>
        </select>
    </td>
</tr> 
<tr>
	 <td bgcolor="#e5e5e5" colspan="10" style="font-weight:bold" align="CENTER">INFORMACION DE LA POSIBLE INCONSISTENCIA</td>
</tr>
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="CENTER">Variable Presuntamente Incorrecta</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="CENTER" colspan="2">Datos Segun Documento De Identificacion (fisico)</td>    
</tr>
<tr>
	<td><input type="checkbox" name="MalPrimApe"> Primer Apellido</td>
    <td><strong>Primer Apellido</strong></td>
    <td><input type="text" name="PrimApeFisico" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" onFocus="xLetra(this)" value="<? echo $PrimApeFisico?>"></td>
</tr>
<tr>
	<td> <input type="checkbox" name="MalSegApe"> Segundo Apellido</td>
	<td><strong>Segundo Apellido</strong></td><td><input type="text" name="SegApeFisico" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" onFocus="xLetra(this)"></td>
</tr>
<tr>
	<td><input type="checkbox" name="MalPrimNom"> Primer Nombre</td>
 	<td><strong>Primer Nombre</strong></td>
    <td><input type="text" name="PrimNomFisico" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" onFocus="xLetra(this)" value="<? echo $PrimNomFisico?>"></td>
</tr>
<tr>
	<td><input type="checkbox" name="MalSegNom"> Segundo Nombre</td>
	<td><strong>Segundo Nombre</strong></td><td><input type="text" name="SegNomFisico" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" onFocus="xLetra(this)"></td>
</tr>
<tr>
	<td><input type="checkbox" name="MalTipoDoc"> Tipo Documento Identificacion</td>
	<td><strong>Tipo Documento de Identificacion</strong></td>
    <td>    	
    	<select name="TipodDoc"><option></option>
   	<?	foreach($TiposDocument as $fila){
			if($fila[0]==$TipodDoc){
				echo "<option value='$fila[0]' selected>$fila[1]</option>";
			}
			else{
				echo "<option value='$fila[0]'>$fila[1]</option>";
			}
		}?>
     	</select>    
    </td>
</tr>
<tr>
	<td><input type="checkbox" name="MalIdentificacion"> Numero Documento Identificacion</td>
	<td><strong>Numero Documento de Identificacion</strong></td>
    <td><input type="text" name="IdentificacionFisico" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" onFocus="xLetra(this)" value="<? echo $IdentificacionFisico?>"></td>
</tr>
<tr>
	<td><input type="checkbox" name="MalFecNac"> Fecha de Nacimiento</td>
	<td><strong>Fecha de Nacimiento</strong></td>
    <td><select name="AnioNacFisico">
   	<?	for($i=$ND[year];$i>$ND[year]-110;$i--){
			echo "<option value='$i'>$i</option>";
		}?>
    	</select>-
        <select name="MesNacFisico">
   <? 	for($i=1;$i<=12;$i++)
		{			
			echo "<option value=$i>".$NombreMesC[$i]."</option>";
		}?>
        </select>-
         <select name="DiaNacFisico">
    <? 	for($i=1;$i<=31;$i++)
		{
			echo "<option value='$i'>$i</option>";
		}
	?>
    </select>    
    </td>
</tr>
<tr>
	<td colspan="10" bgcolor="#e5e5e5" style="font-weight:bold" align="CENTER">Observaciones</td>
</tr>
<tr>
	<td colspan="10"align="CENTER"><textarea name="Observaciones" rows="4" style="width:100%" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" onFocus="xLetra(this)"
	onkeypress="return pulsar(event,this.value)" ></textarea></td>
</tr>
<tr>
	<td align="center"colspan="10" > 
        <input type="submit" value="Guardar" name="Guardar">
        <input type="button" value="Cancelar" onClick="location.href='BusquedaAfiliados.php?Buscar=1&DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>'">
    </td>
</tr>
</table>
<input type="hidden" name="Identificacion" value="<? echo $Identificacion?>">
<input type="hidden" name="NoFind" value="<? echo $NoFind?>">
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>            
</body>
</html>
