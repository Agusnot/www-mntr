<?
	if($DatNameSID){session_name("$DatNameSID");}	
	session_start();
	if(!$PosScroll){$PosScroll="0";}
	
	if($Homologar&&!$NoDatos){
		//--------------------------------------------------------------------------------Encontramos la entidad con la q estamos trabajando
		$conex = mysql_connect('localhost','root','Server*1492') or die ('no establecida');
		mysql_select_db("BDAfiliados", $conex);
		$cons="select Entidad from AuxAfiliados group by Entidad";		
		$res=mysql_query($cons,$conex)	;	
		$fila=mysql_fetch_row($res);
		
		//----------------------------------------------------------------Encontramos el regimen de la entidad
		$conex = pg_connect("dbname=sistema user=apache password=Server*1982") or die ('no establecida');
		$cons = "Select Identificacion,regimen from Central.Terceros where Tipo='Asegurador' and Compania = '$Compania[0]' and Identificacion='$fila[0]'
		group by Identificacion,regimen";	
		$res = pg_query($conex,$cons);
		$fila=pg_fetch_row($res);
		$Identf=$fila[0]; $Reg=$fila[1];		
		/*OPCION PARA LA ACTUALIZACION DE TERCEROS
		$cons="select identificacion from central.terceros where compania='$Compania[0]'";
		$res = pg_query($conex,$cons);
		while($fila=pg_fetch_row($res)){
			$Terceros[$fila[0]]=$fila[0];
		}*/				
		//--------------------------------------------------------------Eliminamos los usurios q tengan la misma entidad de la tabla Afiliados
		$conex = mysql_connect('localhost','root','Server*1492') or die ('no establecida');
		mysql_select_db("BDAfiliados", $conex);
		//Varificamos si los usuarios estan en tramite
		$cons="select EnTransito from Transito";
		$res=mysql_query($cons,$conex);	
		$fila=mysql_fetch_row($res);
		$EnTran=$fila[0]; 
		if($EnTran!="Si"){
			$cons="delete from Afiliados where Entidad='$Identf'";			
			$res=mysql_query($cons,$conex);
		}
		
		//---------------------------------------------------------------Insertamos los datos en la tabla Afiliados
		
		//Encontramos el consecutivo
		$cons="select id from Afiliados order by id desc"; 
		$res=mysql_query($cons,$conex);	
		$fila=mysql_fetch_row($res);
		$AutoId=$fila[0]+1;
		//echo $AutoId;		
		
		
		$cons="DESCRIBE AuxAfiliados";		
		$res=mysql_query($cons,$conex);	
		$cons3="select ";
		$cont=0;
		while($fila=mysql_fetch_array($res)){
			if($fila[0]!='entidad'&&$_POST["C_".$fila[0]]!="Omitir"){
				$cons3=$cons3."$fila[0],";				
				$cont++;
			}
		}
		
		$cons3=$cons3."entidad from AuxAfiliados";
		$res3=mysql_query($cons3,$conex);	
		while($fila3=mysql_fetch_row($res3)){
			$cons="DESCRIBE AuxAfiliados";		
			$res=mysql_query($cons,$conex);	
			$Values=" values ('$EnTran',$AutoId,'$Reg','$Identf'";	
			$cons2="insert into Afiliados (En_Transito,id,Regimen,Entidad";	
			
			while($fila=mysql_fetch_row($res)){
				if($_POST["C_".$fila[0]]!="Omitir"&&$fila[0]!='entidad'){
					$cons2=$cons2.",".$_POST["C_".$fila[0]];					
				}
			}
			for($i=0; $i<$cont;$i++){
				$Values=$Values.",'$fila3[$i]'";
			}
			$Values=$Values.")";
			$cons2=$cons2.")".$Values;
			//echo "<br>$cons2";
			$res2=mysql_query($cons2,$conex); echo mysql_error($conex);
			$AutoId++;
			$Values="";
			$cons2="";
		}
		
		//---------Actualizar terceros
		/*$cons3=""; $Actualizados="";
		foreach($Terceros as $usuarioTer){		
			$conex = mysql_connect('localhost','root','Server*1492') or die ('no establecida');	
			mysql_select_db("BDAfiliados", $conex);
			$cons="select identificacion,entidad from Afiliados where Identificacion='$usuarioTer' order by identificacion";			
			$res=mysql_query($cons,$conex); echo mysql_error($conex);
			
			if(mysql_num_rows($res)>0){
				$fila2=mysql_fetch_row($res);		
				$cons3="update central.terceros set eps='$fila2[1]' where identificacion='$usuarioTer' and compania='$Compania[0]'";
				//echo "<br>$cons3";
			}	
			if($cons3){
				//echo "<br>$cons3";
				$conex = pg_connect("dbname=sistema user=apache password=Server*1982") or die ('no establecida');				
				$res = pg_query($conex,$cons3);
				$cons3="";
				$Actualizados=$Actualizados."$usuarioTer,";
			}
			//echo $usuarioTer."<br>";	
		}*/
		//echo $Actualizados;
		
		//---------Encontrar Repetidos
		$conex = mysql_connect('localhost','root','Server*1492') or die ('no establecida');
		mysql_select_db("BDAfiliados", $conex);
		$cons="select count(Identificacion) as numero,Identificacion,Primer_Apellido,Segundo_Apellido,Primer_Nombre,Segundo_Nombre from Afiliados 
		group by Identificacion,Primer_Apellido,Segundo_Apellido,Primer_Nombre,Segundo_Nombre having count( Identificacion ) >1";
		//echo $cons;
		$res=mysql_query($cons,$conex);	
		echo mysql_error($conex);		
		while($fila=mysql_fetch_row($res)){
			//echo "$fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5]<br>";
			$Repetidos=$Repetidos."$fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5];";
		}
		
		$conex = mysql_connect('localhost','root','Server*1492') or die ('no establecida');
		mysql_select_db("BDAfiliados", $conex);
		$cons="drop table AuxAfiliados";
		$res=mysql_query($cons,$conex);	echo mysql_error($conex);
		$cons="delete from Transito";
		$res=mysql_query($cons,$conex);	echo mysql_error($conex);
		//echo $Repetidos;
		?>
		<script language="javascript">alert("Homologacion realizada exitosamente");</script>
		<?
	}	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function GuardarScroll(){		
		document.FORMA.PosScroll.value= document.body.scrollLeft;		
		document.FORMA.submit();
	}
</script>
</head>
<? if($_POST["C_c1"]){$PosScroll=$PosScroll+170;}?>
<body background="/Imgs/Fondo.jpg" onLoad="document.body.scrollLeft=<? echo $PosScroll?>"> 

<form name="FORMA" method="post"  enctype="multipart/form-data">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">

<?
	if($Homologar){
		echo "<tr><td colspan='100' align='center'>";
		if($Repetidos){?>       	                
                <input type="button" value="Ver Repetidos" onClick="open('RepetidosHomolg.php?DatNameSID=<? echo $DatNameSID?>&Repetidos=<? echo $Repetidos?>','','width=1100,height=600')">
                           
		<? /*	$UsuRep=explode(";",$Repetidos);
			foreach($UsuRep as $VerRep){
				$RepIndividual=explode(",",$VerRep);
				if($RepIndividual[0]){ ?>
                    <tr>            	
                        <td>
                        <?  echo $RepIndividual[0];?>Ver Repetidos
                        </td>
                    </tr>
	<?         	}
			}*/
		}
		if($Actualizados){?>			 
       		<input type="button" value="Ver Actualizados" 
            onClick="open('ActualizadosHomolg.php?DatNameSID=<? echo $DatNameSID?>&Actualizados=<? echo $Actualizados?>','','width=1100,height=600')">          
	<?	}
		echo "</td></tr>";	
	}
$conex = mysql_connect('localhost','root','Server*1492') or die ('no establecida');
mysql_select_db("BDAfiliados", $conex);
$cons="DESCRIBE AuxAfiliados";
$res=mysql_query($cons,$conex);

if(mysql_error($conex)!="Table 'BDAfiliados.AuxAfiliados' doesn't exist"){
	$ban=1;
}
if($ban==1){?>
	<tr>
        <td align="left" colspan="100">
            <input type="submit" name="Homologar" value="Homologar">
        </td>
	</tr>
	<tr>
<?  
	  
	$res=mysql_query($cons,$conex);
	while($fila=mysql_fetch_row($res)){
		if($fila[0]!='entidad'){
			
			$cons3="DESCRIBE AuxAfiliados";
			$res3=mysql_query($cons3,$conex);		
			$cons2="select Campo from CmpAfiliados ";
			if($_POST["C_".$fila[0]]){
				$cons2=$cons2."where Campo not in (";
				//echo $_POST["C_".$fila3[0]];
				while($fila3=mysql_fetch_row($res3)){			
					if($_POST["C_".$fila3[0]]!='Omitir'&&$fila3[0]!='entidad'&&$fila3[0]!=$fila[0]){
						$cons2=$cons2."'".$_POST["C_".$fila3[0]]."',";
						//echo $_POST["C_".$fila3[0]];
					}				
				}	
				$cons2=$cons2."'999999')";
				//echo $cons2;
			}
			$res2=mysql_query($cons2,$conex);?>
			<td>       
			<select name="<? echo "C_$fila[0]"?>" onChange="GuardarScroll()"><option value="Omitir">Omitir</option>
		<?	while($fila2=mysql_fetch_row($res2)){
				$Mostrar=str_replace("_"," ",$fila2[0]);
				if($_POST["C_".$fila[0]]==$fila2[0]){
					echo "<option value='$fila2[0]' selected>$Mostrar</option>";
				}
				else{
					echo "<option value='$fila2[0]'>$Mostrar</option>";
				}
			}?>      
			</select>
			</td>
	<?	}
	}?>
	</tr>
	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
	<?
	$res=mysql_query($cons,$conex);
	while($fila=mysql_fetch_row($res)){
		if($fila[0]!='entidad'){
			echo "<td>$fila[0]</td>";
		}
	}
	?>	
	</tr>
	<?
	
	$res=mysql_query($cons,$conex);
	$fila=mysql_fetch_row($res);
	$cons2="select $fila[0]";
	while($fila=mysql_fetch_row($res)){
		if($fila[0]!='entidad'){
			$cons2=$cons2.",$fila[0]";
		}
	}
	$cons2=$cons2." from AuxAfiliados limit 20";
	$res2=mysql_query($cons2,$conex);
	while($fila2=mysql_fetch_row($res2)){
		echo "<tr align=center>";
		$res=mysql_query($cons,$conex);	
		$cont=0;
		while($fila=mysql_fetch_row($res)){
			if($fila[0]!='entidad'){
				echo "<td>$fila2[$cont]&nbsp;</td>";
				$cont++;
			}
		}
		echo "</tr>";	
	}
	
	
	
	
}
else{
?>
<input type="hidden" name="NoDatos">
<script language="javascript">document.FORMA.NoDatos.value=1;</script>
<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
	<td colspan="100">
    	AUN NO SE HAN CARGADOS DATOS PARA SER HOMOLOGADOS
    </td>
</tr>
<?
}?>
</table>
<input type="hidden" name="PosScroll">
<input type="hidden" name="Identf" value="<? echo $Identf?>">
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
