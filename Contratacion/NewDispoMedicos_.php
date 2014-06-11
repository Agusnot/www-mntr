<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();		
	include("Funciones.php");			
	@require_once ("xajax/xajax_core/xajax.inc.php"); 	
	$cons="select codigo,nombre from contratacionsalud.cups where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=Exfetch($res))
	{
		$Cups[$fila[0]]=$fila[1];	
	}
	function LimTmp($Comp,$TMP){
		//global $Compania;
		//global $TMPCOD;
		$cons="delete from salud.tempdispomedsxgrup where compania='$Comp' and tmpcod='$TMP'";		
		$res=ExQuery($cons);
		$cons="delete from salud.tempconsexterna where compania='$Comp' and tmpcod='$TMP'";		
		$res=ExQuery($cons);
		
		$respuesta = new xajaxResponse(); 
		//$respuesta->alert('Hola');
		//$respuesta->script("alert('Los campos son validos')");
		$respuesta->addAssign("respuesta","innerHTML",$arg); 		
		/*?><script language="javascript">alert();</script><?*/
	   	//tenemos que devolver la instanciación del objeto xajaxResponse 
   		return $respuesta; 		
	}
	$obj = new xajax(); 
	$obj->registerFunction("LimTmp"); 
	$obj->processRequest(); 
	$ND=getdate();
	if($TMPCOD==''){$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}
	function InsertarAll($day,$fecha){		
		global $Compania;
		global $Medico;
		global $Grupal;
		global $TMPCOD;
		$ND=getdate();	
		if($Grupal==''){
			$cons="Delete from salud.dispoconsexterna where compania='$Compania[0]' and fecha='$fecha' and usuario='$Medico'";
			$res = ExQuery($cons);echo ExError();
			$id=1;
			$cons="Select  horaini,minsinicio,horasfin,minsfin,idhora,cuppermitido from salud.tempconsexterna 
			where dia='$day' and tmpcod='$TMPCOD' and compania='$Compania[0]' order by idhora";
			$res=ExQuery($cons);echo ExError();			
			while($fila=ExFetch($res)){
				$cons2="insert into salud.dispoconsexterna (horaini,minsinicio,horasfin,minsfin,idhorario,usuario,fecha,compania,cuppermitido,usuariomodif,fechamodif) values 
						  ($fila[0],$fila[1],$fila[2],$fila[3],$id,'$Medico','$fecha','$Compania[0]','$fila[5]','$Medico','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]')";
				$res2 = ExQuery($cons2);echo ExError();				
				$id=$id++;
			}
		}
		else{			
			$cons3="Select usuario from salud.tempdispomedsxgrup where compania='$Compania[0]' and tmpcod='$TMPCOD'";
			$res3=ExQuery($cons3);echo ExError();		
			while($fila3=ExFetch($res3)){			
				$cons="Delete from salud.dispoconsexterna where compania='$Compania[0]' and fecha='$fecha' and usuario='$fila3[0]'";
				$res = ExQuery($cons);echo ExError();				
				$id=1;
				$cons="Select  horaini,minsinicio,horasfin,minsfin,idhora from salud.tempconsexterna where dia='$day' and tmpcod='$TMPCOD' and compania='$Compania[0]' order by idhora";
				$res=ExQuery($cons);echo ExError();			
				while($fila=ExFetch($res)){
					$cons2="insert into salud.dispoconsexterna (horaini,minsinicio,horasfin,minsfin,idhorario,usuario,fecha,compania,cuppermitido,usuariomodif,fechamodif) values 
						  ($fila[0],$fila[1],$fila[2],$fila[3],$id,'$fila3[0]','$fecha','$Compania[0]','$fila[5]','$Medico','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]')";
					$res2 = ExQuery($cons2);echo ExError();					
					$id++;
				}
			}
		}

	}
	if($Guardar!=''){
		$cons = "Select anio from central.anios where  compania='$Compania[0]' order by anio desc";
		$res = ExQuery($cons);
		$fila = ExFetch($res);		
		$UltimoAnio = $fila[0];		
		$HoursInDay = 24;
		$MinutesInHour = 60;
		$SecondsInMinutes = 60;
		$SecondsInDay = (($SecondsInMinutes*$MinutesInHour)*$HoursInDay );
		$DateFrom=$AnioIni."-".$MesIni."-".$DiaIni;
		$DateTo=$AnioFin."-".$MesFin."-".$DiaFinal;
		$dif= intval(abs(strtotime($DateFrom) - strtotime($DateTo))/$SecondsInDay);	//Cantidad de dias entre las dos fechas			
		$dif++;
		$d=date('w',mktime(0,0,0,$MesIni,$DiaIni,$AnioIni));		
		$cont=$DiaIni; 		
		for($i=1;$i<=$dif;$i++){			
			$d=date('w',mktime(0,0,0,$MesIni,$DiaIni,$AnioIni));	
			switch($d){
				case 0: $day="Domingo";break;
				case 1: $day="Lunes";break;
				case 2: $day="Martes"; break;
				case 3: $day="Miercoles";break;
				case 4: $day="Jueves"; break;
				case 5: $day="Viernes";break;
				case 6: $day="Sabado";break;				
			}
			$first_of_month = mktime (0,0,0, $MesIni, 1, $AnioIni); 
			$LastDay = date('t', $first_of_month); 			
			if($cont==$LastDay){
				if($MesIni!=12){
					$fecha=$AnioIni."-".$MesIni."-".$DiaIni;
					InsertarAll($day,$fecha);	
					$cont=1;
					$MesIni++;
					$DiaIni=1;
				}
				else{
					if($AnioIni!=$UltimoAnio){
						$fecha=$AnioIni."-".$MesIni."-".$DiaIni;
						InsertarAll($day,$fecha);	
						$MesIni=1;
						$cont=1;
						$DiaIni=1;
						$AnioIni++;
					}
				}
			}
			else{
				$fecha=$AnioIni."-".$MesIni."-".$DiaIni;
				InsertarAll($day,$fecha);	
				$cont++;
				$DiaIni++;
			}
			
		}
		if($Grupal==''){?>        
			<script language="javascript">
				location.href='DisponibilidadMedicos.php?DatNameSID=<? echo $DatNameSID?>&Medico=<? echo $Medico?>';
			</script> <?
		}else{?>
			<script language="javascript">
				location.href='ConfMedicos.php?DatNameSID=<? echo $DatNameSID?>';
			</script> 
	<?	}
	}
	
	/*if($Primero=="1"){				
		$cons="Delete from salud.tempconsexterna";
		$res = ExQuery($cons);echo ExError();	
	}*/
	
	if($Eliminar){

		switch($Eliminar){
			case 1:	$DiaElim='Lunes';break;
			case 2:	$DiaElim='Martes';break;
			case 3:	$DiaElim='Miercoles';break;
			case 4:	$DiaElim='Jueves';break;
			case 5:	$DiaElim='Viernes';break;
			case 6:	$DiaElim='Sabado';break;
			case 0:	$DiaElim='Domingo';break;
		}
		$cons = "Select Idhora from salud.tempconsexterna where  dia='$DiaElim' and tmpcod='$TMPCOD' and compania='$Compania[0]' order by Idhora desc";
		$res = ExQuery($cons);
		$fila = ExFetch($res);		
		$Idhora = $fila[0];
		$cons="Delete from salud.tempconsexterna where dia='$DiaElim' and idhora=$Idhora and tmpcod='$TMPCOD' and compania='$Compania[0]'";
		$res = ExQuery($cons);echo ExError();
	}
	if($DiaIns!=''){
		switch($DiaIns){
			case 1:	$DiaInsert='Lunes';break;
			case 2:	$DiaInsert='Martes';break;
			case 3:	$DiaInsert='Miercoles';break;
			case 4:	$DiaInsert='Jueves';break;
			case 5:	$DiaInsert='Viernes';break;
			case 6:	$DiaInsert='Sabado';break;
			case 0:	$DiaInsert='Domingo';break;
		}
		$cons = "Select Idhora from salud.tempconsexterna where  dia='$DiaInsert' and tmpcod='$TMPCOD' and compania='$Compania[0]' order by Idhora desc";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$Idhora = $fila[0] +1;
		switch($DiaIns){
			case 1:	$cons="insert into salud.tempconsexterna (horaini,minsinicio,horasfin,minsfin,dia,idhora,compania,tmpcod) values 
						  ($HoraIniL,$MinsInicioL,$HorasFinL,$MinsFinL,'$DiaInsert',$Idhora,'$Compania[0]','$TMPCOD')";$MinsInicioL='';$HoraIniL='';break;
			case 2:	$cons="insert into salud.tempconsexterna (horaini,minsinicio,horasfin,minsfin,dia,idhora,compania,tmpcod) values 
						  ($HoraIniM,$MinsInicioM,$HorasFinM,$MinsFinM,'$DiaInsert',$Idhora,'$Compania[0]','$TMPCOD')";$MinsInicioM='';$HoraIniM='';break;
			case 3:	$cons="insert into salud.tempconsexterna (horaini,minsinicio,horasfin,minsfin,dia,idhora,compania,tmpcod) values 
						  ($HoraIniW,$MinsInicioW,$HorasFinW,$MinsFinW,'$DiaInsert',$Idhora,'$Compania[0]','$TMPCOD')";$MinsInicioW='';$HoraIniW='';break;
			case 4:	$cons="insert into salud.tempconsexterna (horaini,minsinicio,horasfin,minsfin,dia,idhora,compania,tmpcod) values 
						  ($HoraIniJ,$MinsInicioJ,$HorasFinJ,$MinsFinJ,'$DiaInsert',$Idhora,'$Compania[0]','$TMPCOD')";$MinsInicioJ='';$HoraIniJ='';break;
			case 5:	$cons="insert into salud.tempconsexterna (horaini,minsinicio,horasfin,minsfin,dia,idhora,compania,tmpcod) values 
						  ($HoraIniV,$MinsInicioV,$HorasFinV,$MinsFinV,'$DiaInsert',$Idhora,'$Compania[0]','$TMPCOD')";$MinsInicioV='';$HoraIniV='';break;
			case 6:	$cons="insert into salud.tempconsexterna (horaini,minsinicio,horasfin,minsfin,dia,idhora,compania,tmpcod) values 
						  ($HoraIniS,$MinsInicioS,$HorasFinS,$MinsFinS,'$DiaInsert',$Idhora,'$Compania[0]','$TMPCOD')";$MinsInicioS='';$HoraIniS='';break;
			case 0:	$cons="insert into salud.tempconsexterna (horaini,minsinicio,horasfin,minsfin,dia,idhora,compania,tmpcod) values 
						  ($HoraIniD,$MinsInicioD,$HorasFinD,$MinsFinD,'$DiaInsert',$Idhora,'$Compania[0]','$TMPCOD')";$MinsInicioD='';$HoraIniD='';break;
		}	
		//echo $cons;			
		$res = ExQuery($cons);
	}
		
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? $obj->printJavascript("../xajax");?>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Limpiar(Comp,TMP)
	{			
		if(document.FORMA.Guardar.value!=1){
			if(document.FORMA.NoSubmit.value!=1){							
				xajax_LimTmp(Comp,TMP);	
			}
		}
	}
function diasem($Dia){
	document.FORMA.DiaIns.value=$Dia;
	document.FORMA.NoSubmit.value=1;			
	document.FORMA.submit();
}
function eliminar($Elim){
	document.FORMA.Eliminar.value=$Elim;
	document.FORMA.NoSubmit.value=1;			
	document.FORMA.submit();
}

function salir(){
	if(document.FORMA.Grupal.value==''){
		Comp='<? echo $Compania[0]?>';
		TMP='<? echo $TMPCOD?>';
		document.FORMA.NoSubmit.value=0;			
		location.href='DisponibilidadMedicos.php?DatNameSID=<? echo $DatNameSID?>&Medico=<? echo $Medico?>';
	}
	else{
		document.FORMA.NoSubmit.value=0;			
		location.href='ConfMedicos.php?DatNameSID=<? echo $DatNameSID?>';
	}
}

function validar(){
		if(document.FORMA.AnioIni.value==""||document.FORMA.MesIni.value==""||document.FORMA.DiaIni.value==""||document.FORMA.AnioFin.value==""||document.FORMA.MesFin.value==""||document.FORMA.DiaFinal.value=="")
		{
			alert("No deben quedar espacios en blanco el la Fecha Inicial o en la Fecha Final!!!");return false;
		}
		else{
			document.FORMA.Guardar.value=1;
			document.FORMA.NoSubmit.value=1;			
			document.FORMA.submit();
		}
}
	
	function Restringir(Restric) { 
		frames.FrameOpener2.location.href="CupBloqueoxHorario.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Restric="+Restric;
		document.getElementById('FrameOpener2').style.position='absolute';
		document.getElementById('FrameOpener2').style.top=200;
		document.getElementById('FrameOpener2').style.left=10;
		document.getElementById('FrameOpener2').style.display='';
		document.getElementById('FrameOpener2').style.width='860px';
		document.getElementById('FrameOpener2').style.height='300px';
	} 
</script>
</head>

<body background="/Imgs/Fondo.jpg" onUnload="Limpiar('<? echo $Compania[0]?>','<? echo $TMPCOD?>')">
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="8" align="center">
 	<tr><? if($Grupal==''){
				$res=ExQuery("Select nombre,especialidad,Medicos.usuario as usu from Salud.Medicos,central.usuarios 
					where Medicos.usuario=usuarios.usuario and Medicos.usuario='$Medico' and Compania='$Compania[0]'");
				$r=ExFetchArray($res);
		?>
		    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="14"><? echo $r[0]?> - <? echo $r[1]?></td>
    <?  	}else{?>
    			<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="14">Disponibilidad por Grupo: <? echo $Especialidad?></td>	
        <? 	}?>
    </tr>
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Fecha de Inicio</td>
        <td>Año</td><td><? 
		$res=ExQuery("Select anio from central.anios
		where Compania='$Compania[0]' order by anio");?>
        <select name="AnioIni" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";><option></option><?		
		while($row = ExFetchArray($res))
		{	
			if($AnioIni==$row[0])
			{  $AnioIS=$row[0];?>
				<option value="<? echo $row['anio']?>" selected><? echo $row['anio']?></option>		
	<?		}
			else{?>
				<option value="<? echo $row['anio']?>"><? echo $row['anio']?></option>		
	<?		}
			$AnioIF=$row[0];
		}
		?>
        </select></td>
        <td>Mes</td><td><? $res=ExQuery("Select numero,mes from  central.meses");?>
	    <select name="MesIni" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";><option></option><?
		if($AnioIni!=''){			
			while($row = ExFetchArray($res))
			{
				if($MesIni==$row[0])
				{?> 
				<option value="<? echo $row['numero']?>" selected><? echo $row['mes']?></option>		
		<?		}
				else{?>
				<option value="<? echo $row['numero']?>"><? echo $row['mes']?></option>		
			<?	}				
			}
		}	?>        
        </select></td>
        <td>Dia</td><td><? 
		if($MesIni!=''){
			$first_of_month = mktime (0,0,0, $MesIni, 1, $AnioIni); 
			$DiaFin = date('t', $first_of_month); 
		}
		else{
			$DiaFin=31;
		}
		?>
		<select name="DiaIni" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";><option></option>
      <?  if($MesIni!=''){
	  		for($i=1;$i<=$DiaFin;$i++){
				if($DiaIni==$i){
			?>	<option value="<? echo $i?>" selected><? echo $i?></option><?
				}
				else{?>
				<option value="<? echo $i?>"><? echo $i?></option><?
				}
			}
	  	}?>
      	
        </select></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Fecha Final</td> 
        <? ?>
        <td>Año</td><td><? 
		$res=ExQuery("Select anio from central.anios where Compania='$Compania[0]'");?>
        <select name="AnioFin" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";><option></option>
     <? if($DiaIni!=''){ 	 		 	
			while($row = ExFetchArray($res)){ 
				if($MesIni==12&&$DiaIni==$DiaFin){	//el mes inicial es el ultimo mes del año y dia inicial es el ultimo dia del año		
					if($AnioIF!=$AnioIS){// el año inicial no es el ultimo de la tabla años
						if($row['anio']>$AnioIni){ //el año final tiene q se menor al inicial
							if($row['anio']==$AnioFin){?>                          
		        			<option value="<? echo $row['anio']?>" selected><? echo $row['anio']?></option>	
      <? 					}
			  				else{?>
        	            	<option value="<? echo $row['anio']?>"><? echo $row['anio']?></option>	
	  					<?	}
                		}	  								
					}//en este caso no deben haber años para elegir
				}
				else{//el año final puede ser igual o mayor al año inicial
					if($row['anio']>=$AnioIni){ 
						if($row['anio']==$AnioFin){?>                          
		        		<option value="<? echo $row['anio']?>" selected><? echo $row['anio']?></option>	
      <? 				}
			  			else{?>
        	           	<option value="<? echo $row['anio']?>"><? echo $row['anio']?></option>	
	  				<?	}
                	}	
				}
			}
	  	}?>		
        </select></td>
        <td>Mes</td><td>
        <? $res=ExQuery("Select numero,mes from  central.meses");?>
        <select name="MesFin" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";><option></option>
  <?     if($AnioFin!=''){		
			while($row = ExFetchArray($res))
			{	
				if($AnioFin==$AnioIni){	
					if($DiaIni!=$DiaFin){
						if($row[0]>=$MesIni){			
							if($MesFin==$row[0])
							{?>
							<option value="<? echo $row['numero']?>" selected><? echo $row['mes']?></option>		
						<?	}
							else{?>
							<option value="<? echo $row['numero']?>"><? echo $row['mes']?></option>		
				<?			}
						}
					}
					else{
						if($row[0]>$MesIni){			
							if($MesFin==$row[0])
							{?>
							<option value="<? echo $row['numero']?>" selected><? echo $row['mes']?></option>		
						<?	}
							else{?>
							<option value="<? echo $row['numero']?>"><? echo $row['mes']?></option>		
				<?			}
						}
					}
				}
				else{								
					if($MesFin==$row[0])
					{?>
					<option value="<? echo $row['numero']?>" selected><? echo $row['mes']?></option>		
				<?	}
					else{?>
					<option value="<? echo $row['numero']?>"><? echo $row['mes']?></option>		
			<?		}					
				}
			}	
		}?>
        </select></td>
        <td>Dia</td><td>
        <? if($MesFin!=''&&$AnioFin!=''){
			$first_of_month = mktime (0,0,0, $MesFin, 1, $AnioFin); 
			$DF = date('t', $first_of_month); 
			if($AnioFin==$AnioIni&&$MesFin==$MesIni){
				$DiaF=$DiaIni+1;
			}		
			else{
				$DiaF=1;
			}
		}		
		?>
        <select name="DiaFinal" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";><option></option>
   	<?  if($MesFin!=''){
	  		for($i=$DiaF;$i<=$DF;$i++){
				if($DiaFinal==$i){
			?>	<option value="<? echo $i?>" selected><? echo $i?></option><?
				}
				else{?>
				<option value="<? echo $i?>"><? echo $i?></option><?
				}
			}
	  	}?>
        </select></td>
    </tr> 
</table>


<table>
<tr><td>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5"  align="center">    
    <tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center"> 
    	<td colspan="4" >Lunes</td> 
  	</tr>
   	<tr>
	   	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Hora inicio</td><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Hora Fin</td>
    </tr>
    <?
 	$cons="Select  horaini,minsinicio,horasfin,minsfin,idhora,cuppermitido from salud.tempconsexterna where dia='Lunes' and compania='$Compania[0]' and tmpcod='$TMPCOD' order by idhora";
	$res=ExQuery($cons);		
	$n=ExNumRows($res);
	$n2=ExNumRows($res);
		while($fila=ExFetch($res))
		{	if($fila[1]==0){$Cero2=0;}else{$Cero2='';}
			if($fila[3]==0){$Cero3=0;}else{$Cero3='';}				
			if($n==1)
			{
				 $HorIL=$fila[0]; $MinIL=$fila[1]; $HorFL=$fila[2]; $MinFL=$fila[3];				 ?>
              	<tr align='center' <? if($fila[5]){ echo "bgcolor='#FF6600' title='resitringidop al cup $fila[5] - ".$Cups[$fila[5]]."'";}?>>
			<?	echo "<td colspan='2'>$fila[0]:$fila[1]$Cero2</td><td colspan='2'>$fila[2]:$fila[3]$Cero3</td><td>";?>				
					<img title="Eliminar" style="cursor:hand" onClick="eliminar(1)" src="/Imgs/b_drop.png"></td>
                <td><img title="Restrigir" style="cursor:hand" onClick="Restringir(1)" src="/Imgs/s_process.png"></td></tr>
               
         <? }
			else
			{?>
				<tr align="center" <? if($fila[5]){ echo "bgcolor='#FF6600' title='resitringidop al cup $fila[5] - ".$Cups[$fila[5]]."'";}?>>
			<?	echo "<td colspan='2'>$fila[0]:$fila[1]$Cero2</td><td colspan='2'>$fila[2]:$fila[3]$Cero3</td></tr>";
			}
			$n--;
			
		}
	
	if($HorFL!=21)
	{
		if($n2>0)
		{ ?>
        	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    			<td >Hora</td><td>Minutos</td><td>Hora</td><td>Minutos</td>
    		</tr>
	    	<tr align="center">
    	       	<td> 
        	    <select name="HoraIniL" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";><option></option>
	         <? for($j=$HorFL;$j<21;$j++) {
					if($j==$HoraIniL){ ?>
	    	    		<option value="<? echo $j?>" selected><? echo $j?></option>
	          <?	}
					else{?>
        	        	<option value="<? echo $j?>"><? echo $j?></option>
	          <?	} 
			  	}?>        		
	        	</select>     
	          	</td>
    	    	<td><? if($HoraIniL==$HorFL){
							$MIL=$MinFL;
						}
						else{							
							$MIL=0;
						}?>
        	     <select name="MinsInicioL" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";> <option></option>
	        <? if($HoraIniL!=''){ 
					for($k=$MIL;$k<60;$k+=10) { 
						if($k==$MinsInicioL&&$MinsInicioL!=''){ ?>
	    	    			<option value="<? echo $k?>" selected><? echo $k?></option>
	            	<? }else{?>
        	       		<option value="<? echo $k?>"><? echo $k?></option>
		          <?	} 
				  	}
				}?>
	    	    </select>
            	</td>
	            <td><? if($MinsInicioL==50){$HoraIL=$HoraIniL+1;}else{$HoraIL=$HoraIniL;}?>           
    	         <select name="HorasFinL" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
        	 	<?	 
				 if($MinsInicioL!=''){
		 			for($j=$HoraIL;$j<22;$j++){   
                		if($j==$HorasFinL&&HorasFinL>$HoraIniL) { $Ban1L=1;?>            								 	
                  			<option value="<? echo $j?>" selected><? echo $j?></option>
	             <? 	}
				 		else{?>
        	            	<option value="<? echo $j?>"><? echo $j?></option>
					<?	}
			 		}				 
				}?>        		
		        </select>  
        	    <td><?
            		if($Ban1L==1){
						if($HorasFinL!=$HoraIniL){
							$MinsIL=0;//Los minutos iniciales son 50 pero las horas finales son mayores a las horas iniciales
							$BanMFL=1;				
						}
						else{
							if($MinsFinL>$MinsInicioL)
							{							
								$BanMFL=1;
							}
							$MinsIL=$MinsInicioL+10;					
						}
					}
					else{					
						$MinsIL=$MinsInicioL+10;
						if($HoraIniL==($HoraIL-1)&&$MinsInicioL==50){
							$MinsIL=0;
						}					
					}?>
	        	    <select name="MinsFinL">
    	    <?	if($MinsInicioL!=''){
					if($HorasFinL!=21){	
						if($MinsInicioL!=50||$HoraIniL!=20){
							for($k=$MinsIL;$k<60;$k+=10) {  
        	        	    	if($BanMFL==1&&$MinsFinL==$k&&$MinsFinL!=''){?>		
									<option value="<? echo $k?>" selected><? echo $k?></option> 
	                	      <?  }
    	                	    else
								{?>
            	             		<option value="<? echo $k?>"><? echo $k?></option>        		 
					  	<?		}
							} 
						}
						else
						{?>
            	    			<option value="0">0</option>
		           <? } 
				 	}
					else{?>
						<option value="0">0</option>
			<?		}
				}?>				
		        </select> 
    	        <td>  <? if($MinsInicioL!=''){?><img title="Correcto" onClick="diasem(1)" src="/Imgs/b_check.png" style="cursor:hand" > <? }?></td>          
                
    		    </td>   
	        </tr>
 <? 	}
 		else
		{?>		
        <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	    	<td>Hora</td><td>Minutos</td><td>Hora</td><td>Minutos</td>
    	</tr>
        <tr align="center">
           	<td><select name="HoraIniL" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
         <? for($j=6;$j<21;$j++) {
				if($j==$HoraIniL){?>
	        		<option value="<? echo $j?>" selected><? echo $j?></option>
          <?	}
				else{?>
                	<option value="<? echo $j?>"><? echo $j?></option>
          <?	} 
		  	}?>        		
	        </select>
          	</td>
        	<td>
             <select name="MinsInicioL" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";> <option></option>
        <?  for($k=0;$k<60;$k+=10) { 
				if($k==$MinsInicioL&&$MinsInicioL!=''){?>
	        		<option value="<? echo $k?>" selected><? echo $k?></option>
            <? }else
				{?>
                	<option value="<? echo $k?>"><? echo $k?></option>
          <?	} 
		  	}?>
	        </select>
            </td>
            <td><? if($MinsInicioL==50){$HoraIL=$HoraIniL+1;}else{$HoraIL=$HoraIniL;}  ?>           
             <select name="HorasFinL" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
         	<?	 
			 if($MinsInicioL!=''){
		 		for($j=$HoraIL;$j<22;$j++){   
                	if($j==$HorasFinL&&HorasFinL>$HoraIniL) { $Ban1L=1;?>            								 	
                  		<option value="<? echo $j?>" selected><? echo $j?></option>
             <? 	}
			 		else{?>
                    	<option value="<? echo $j?>"><? echo $j?></option>
				<?	}
			 	}				 
			}?>        		
	        </select>  
            <td><?
            	if($Ban1L==1){
					if($HorasFinL!=$HoraIniL){
						$MinsIL=0;//Los minutos iniciales son 50 pero las horas finales son mayores a las horas iniciales
						$BanMFL=1;				
					}
					else{
						if($MinsFinL>$MinsInicioL)
						{							
							$BanMFL=1;
						}						
						$MinsIL=$MinsInicioL+10;					
					}
				}
				else{					
					$MinsIL=$MinsInicioL+10;
					if($HoraIniL==($HoraIL-1)&&$MinsInicioL==50){
						$MinsIL=0;
					}					
				}
				?>
            <select name="MinsFinL">
        <?	if($MinsInicioL!=''){
				if($HorasFinL!=21){	
					if($MinsInicioL!=50||$HoraIniL!=20){
						for($k=$MinsIL;$k<60;$k+=10) {  
        	            	if($BanMFL==1&&$MinsFinL==$k&&$MinsFinL!=''){?>		
								<option value="<? echo $k?>" selected><? echo $k?></option> 
                	      <?  }
                    	    else
							{?>
                         		<option value="<? echo $k?>"><? echo $k?></option>        		 
				  	<?		}
						} 
					}
					else
					{?>
                			<option value="0">0</option>
	           <? } 
			 	}
				else{?>
					<option value="0">0</option>
		<?		}
			}?>
				
	        </select> 
            <td>  <? if($MinsInicioL!=''){?><img title="Correcto" onClick="diasem(1)" src="/Imgs/b_check.png" style="cursor:hand" > <? }?></td>          
    	    </td>   
        </tr>
 <?		}
 	}?>  
</table> 
</td>
<td>  
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5"  align="center">
  <tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center"> 
		<td colspan="4" align="center">Martes</td>
  </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Hora inicio</td><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Hora Fin</td>
    </tr>
    <?
 	$cons="Select  horaini,minsinicio,horasfin,minsfin,idhora,cuppermitido from salud.tempconsexterna 
	where dia='Martes' and compania='$Compania[0]' and tmpcod='$TMPCOD' order by idhora";
	$res=ExQuery($cons);		
	$nm=ExNumRows($res);
	$nm2=ExNumRows($res);
		while($fila=ExFetch($res))
		{	if($fila[1]==0){$Cero2=0;}else{$Cero2='';}
			if($fila[3]==0){$Cero3=0;}else{$Cero3='';}				
			if($nm==1)
			{
				 $HorIM=$fila[0]; $MinIM=$fila[1]; $HorFM=$fila[2]; $MinFM=$fila[3];?>			 
				<tr align='center' <? if($fila[5]){ echo "bgcolor='#FF6600' title='resitringidop al cup $fila[5] - ".$Cups[$fila[5]]."'";}?>>
          	<?	echo "<td colspan='2'>$fila[0]:$fila[1]$Cero2</td><td colspan='2'>$fila[2]:$fila[3]$Cero3</td><td>";?>				
				<img title="Eliminar" style="cursor:hand" onClick="eliminar(2)" src="/Imgs/b_drop.png"></td>
                <td><img title="Restrigir" style="cursor:hand" onClick="Restringir(2)" src="/Imgs/s_process.png"></td>			
				  <div align="right">
				</tr>			
				    <div align="right">
				
               
         <? }
			else
			{?>
            	<tr align="center" <? if($fila[5]){ echo "bgcolor='#FF6600' title='resitringidop al cup $fila[5] - ".$Cups[$fila[5]]."'";}?>>
			<?	echo "<td colspan='2'>$fila[0]:$fila[1]$Cero2</td><td colspan='2'>$fila[2]:$fila[3]$Cero3</td></tr>";
			}
			$nm--;
			
		}
	
	if($HorFM!=21)
	{
		if($nm2>0)
		{ ?>
        	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    			<td>Hora</td><td>Minutos</td><td>Hora</td><td>Minutos</td>
    		</tr>
	    	<tr align="center">
    	       	<td>      	          
        	    <select name="HoraIniM" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";><option></option>
        	            <? for($j=$HorFM;$j<21;$j++) {
					if($j==$HoraIniM){ ?>
        	            <option value="<? echo $j?>" selected><? echo $j?></option>
        	            <?	}
					else{?>
        	            <option value="<? echo $j?>"><? echo $j?></option>
        	            <?	} 
			  	}?>        		
      	            </select>     
              </td>
    	     <td>
    	       
    	         <? if($HoraIniM==$HorFM){
							$MIM=$MinFM;
					}
					else{							
							$MIM=0;
					}?>
    	         <select name="MinsInicioM" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";> <option></option>                   
   	           <? if($HoraIniM!=''){
				   	for($k=$MIM;$k<60;$k+=10) { 
						if($k==$MinsInicioM&&$MinsInicioM!=''){ ?>
    		        	 	<option value="<? echo $k?>" selected><? echo $k?></option>
    	    	   <?  	}else{?>
	    	           		<option value="<? echo $k?>"><? echo $k?></option>
    		        <?	} 
				  	}
				}?>
  	            </select>
              </td>
	            <td>
	              
	                <? if($MinsInicioM==50){$HoraIM=$HoraIniM+1;}else{$HoraIM=$HoraIniM;}?>           
	                <select name="HorasFinM" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
	                  <?	 
				 if($MinsInicioM!=''){
		 			for($j=$HoraIM;$j<22;$j++){   
                		if($j==$HorasFinM&&HorasFinM>$HoraIniM) { $Ban1M=1;?>            								 	
	                  <option value="<? echo $j?>" selected><? echo $j?></option>
	                  <? 	}
				 		else{?>
	                  <option value="<? echo $j?>"><? echo $j?></option>
	                  <?	}
			 		}				 
				}?>        		
                    </select>  
                  
	            <td>
	              
	                <?
            		if($Ban1M==1){
						if($HorasFinM!=$HoraIniM){
							$MinsIM=0;//Los minutos iniciales son 50 pero las horas finales son mayores a las horas iniciales
							$BanMFM=1;				
						}
						else{
							if($MinsFinM>$MinsInicioM)
							{							
								$BanMFM=1;
							}
							$MinsIM=$MinsInicioM+10;					
						}
					}
					else{					
						$MinsIM=$MinsInicioM+10;
						if($HoraIniM==($HoraIM-1)&&$MinsInicioM==50){
							$MinsIM=0;
						}					
					}?>
	                <select name="MinsFinM">
	                  <?	if($MinsInicioM!=''){
					if($HorasFinM!=21){	
						if($MinsInicioM!=50||$HoraIniM!=20){
							for($k=$MinsIM;$k<60;$k+=10) {  
        	        	    	if($BanMFM==1&&$MinsFinM==$k&&$MinsFinM!=''){?>		
	                  <option value="<? echo $k?>" selected><? echo $k?></option> 
	                  <?  }
    	                	    else
								{?>
	                        <option value="<? echo $k?>"><? echo $k?></option>        		 
                        <?		}
							} 
						}
						else
						{?>
	                    <option value="0">0</option>
                      <? } 
				 	}
					else{?>
	                  <option value="0">0</option>
                      <?		}
				}?>				
                    </select> 
                  
	            <td>  
	              
	                <? if($MinsInicioM!=''){?>
	                <img title="Correcto" onClick="diasem(2)" src="/Imgs/b_check.png" style="cursor:hand" > 
	                <? }?>
              </td>          
    		    </td>   
	        </tr>
 <? 	}
 		else
		{?>		
        <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	    	<td>Hora</td><td>Minutos</td><td>Hora</td><td>Minutos</td>
    	</tr>
        <tr align="center">
           	<td>
           	  
           	    <select name="HoraIniM" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
           	      <? for($j=6;$j<21;$j++) {
				if($j==$HoraIniM){?>
           	      <option value="<? echo $j?>" selected><? echo $j?></option>
           	      <?	}
				else{?>
           	      <option value="<? echo $j?>"><? echo $j?></option>
           	      <?	} 
		  	}?>        		
       	        </select>
          </td>
        	<td>
              
              
                  <select name="MinsInicioM" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";> 
                    <option></option>
                    <?  for($k=0;$k<60;$k+=10) { 
				if($k==$MinsInicioM&&$MinsInicioM!=''){?>
                    <option value="<? echo $k?>" selected><? echo $k?></option>
                    <? }else
				{?>
                    <option value="<? echo $k?>"><? echo $k?></option>
                    <?	} 
		  	}?>
                  </select>
          </td>
         <td>
           
             <? if($MinsInicioM==50){$HoraIM=$HoraIniM+1;}else{$HoraIM=$HoraIniM;}  ?>           
             <select name="HorasFinM" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
               <?	 
			 if($MinsInicioM!=''){
		 		for($j=$HoraIM;$j<22;$j++){   
                	if($j==$HorasFinM&&HorasFinM>$HoraIniM) { $Ban1M=1;?>            								 	
               <option value="<? echo $j?>" selected><? echo $j?></option>
               <? 	}
			 		else{?>
               <option value="<? echo $j?>"><? echo $j?></option>
               <?	}
			 	}				 
			}?>        		
             </select>  
             
         <td>
           
             <?
            	if($Ban1M==1){
					if($HorasFinM!=$HoraIniM){
						$MinsIM=0;//Los minutos iniciales son 50 pero las horas finales son mayores a las horas iniciales
						$BanMFM=1;				
					}
					else{
						if($MinsFinM>$MinsInicioM)
						{							
							$BanMFM=1;
						}						
						$MinsIM=$MinsInicioM+10;					
					}
				}
				else{					
					$MinsIM=$MinsInicioM+10;
					if($HoraIniM==($HoraIM-1)&&$MinsInicioM==50){
						$MinsIM=0;
					}					
				}
				?>
             <select name="MinsFinM">
               <?	if($MinsInicioM!=''){
				if($HorasFinM!=21){	
					if($MinsInicioM!=50||$HoraIniM!=20){
						for($k=$MinsIM;$k<60;$k+=10) {  
        	            	if($BanMFM==1&&$MinsFinM==$k&&$MinsFinM!=''){?>		
               <option value="<? echo $k?>" selected><? echo $k?></option> 
               <?  }
                    	    else
							{?>
                     <option value="<? echo $k?>"><? echo $k?></option>        		 
               <?		}
						} 
					}
					else
					{?>
                 <option value="0">0</option>
                <? } 
			 	}
				else{?>
               <option value="0">0</option>
               <?		}
			}?>
             </select> 
             
         <td>  
           
             <? if($MinsInicioM!=''){?>
             <img title="Correcto" onClick="diasem(2)" src="/Imgs/b_check.png" style="cursor:hand" > 
             <? }?>
          </td>          
    	    </td>   
        </tr>
 <?		}
 	}?> 
</table> 
</td>
<td>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5"  align="center"> 
  <tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">            
	  <td colspan="4" >Miercoles</td> 
  </tr>    	
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Hora inicio</td><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Hora Fin</td>
    </tr>
     <?
 	$cons="Select  horaini,minsinicio,horasfin,minsfin,idhora,cuppermitido from salud.tempconsexterna 
	where dia='Miercoles'  and compania='$Compania[0]' and tmpcod='$TMPCOD' order by idhora";
	$res=ExQuery($cons);		
	$nw=ExNumRows($res);
	$nw2=ExNumRows($res);
		while($fila=ExFetch($res))
		{	if($fila[1]==0){$Cero2=0;}else{$Cero2='';}
			if($fila[3]==0){$Cero3=0;}else{$Cero3='';}				
			if($nw==1)
			{
				 $HorIW=$fila[0]; $MinIW=$fila[1]; $HorFW=$fila[2]; $MinFW=$fila[3];	?>
				<tr align="center" <? if($fila[5]){ echo "bgcolor='#FF6600' title='resitringidop al cup $fila[5] - ".$Cups[$fila[5]]."'";}?>>
			<?	echo "<td colspan='2'>$fila[0]:$fila[1]$Cero2</td><td colspan='2'>$fila[2]:$fila[3]$Cero3</td><td>";?>				
				<img title="Eliminar" style="cursor:hand" onClick="eliminar(3)" src="/Imgs/b_drop.png"></td>
                <td><img title="Restrigir" style="cursor:hand" onClick="Restringir(3)" src="/Imgs/s_process.png"></td></tr>
               
         <? }
			else
			{?>
            	<tr align="center" <? if($fila[5]){ echo "bgcolor='#FF6600' title='resitringidop al cup $fila[5] - ".$Cups[$fila[5]]."'";}?>>
			<?	echo "<td colspan='2'>$fila[0]:$fila[1]$Cero2</td><td colspan='2'>$fila[2]:$fila[3]$Cero3</td></tr>";
			}
			$nw--;
			
		}
	
	if($HorFW!=21)
	{
		if($nw2>0)
		{ ?>
        	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    			<td>Hora</td><td >Minutos</td><td>Hora</td><td >Minutos</td>
    		</tr>
	    	<tr align="center">
    	       	<td> 
        	    <select name="HoraIniW" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";><option></option>
	         <? for($j=$HorFW;$j<21;$j++) {
					if($j==$HoraIniW){ ?>
	    	    		<option value="<? echo $j?>" selected><? echo $j?></option>
	          <?	}
					else{?>
        	        	<option value="<? echo $j?>"><? echo $j?></option>
	          <?	} 
			  	}?>        		
	        	</select>     
	          	</td>
    	    	<td ><? if($HoraIniW==$HorFW){
							$MIW=$MinFW;
						}
						else{							
							$MIW=0;
						}?>
        	     <select name="MinsInicioW" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";> <option></option>
	        <? if($HoraIniW!=''){  
					for($k=$MIW;$k<60;$k+=10) { 
						if($k==$MinsInicioW&&$MinsInicioW!=''){ ?>
	    	    			<option value="<? echo $k?>" selected><? echo $k?></option>
            	<? 		}else{?>
                			<option value="<? echo $k?>"><? echo $k?></option>
	          <?		} 
			  		}
				}?>
	    	    </select>
            	</td>
	            <td><? if($MinsInicioW==50){$HoraIW=$HoraIniW+1;}else{$HoraIW=$HoraIniW;}?>           
    	         <select name="HorasFinW" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
        	 	<?	 
				 if($MinsInicioW!=''){
		 			for($j=$HoraIW;$j<22;$j++){   
                		if($j==$HorasFinW&&HorasFinW>$HoraIniW) { $Ban1W=1;?>            								 	
                  			<option value="<? echo $j?>" selected><? echo $j?></option>
	             <? 	}
				 		else{?>
        	            	<option value="<? echo $j?>"><? echo $j?></option>
					<?	}
			 		}				 
				}?>        		
		        </select>  
        	    <td ><?
            		if($Ban1W==1){
						if($HorasFinW!=$HoraIniW){
							$MinsIW=0;//Los minutos iniciales son 50 pero las horas finales son mayores a las horas iniciales
							$BanMFW=1;				
						}
						else{
							if($MinsFinW>$MinsInicioW)
							{							
								$BanMFW=1;
							}
							$MinsIW=$MinsInicioW+10;					
						}
					}
					else{					
						$MinsIW=$MinsInicioW+10;
						if($HoraIniW==($HoraIW-1)&&$MinsInicioW==50){
							$MinsIW=0;
						}					
					}?>
	        	    <select name="MinsFinW">
    	    <?	if($MinsInicioW!=''){
					if($HorasFinW!=21){	
						if($MinsInicioW!=50||$HoraIniW!=20){
							for($k=$MinsIW;$k<60;$k+=10) {  
        	        	    	if($BanMFW==1&&$MinsFinW==$k&&$MinsFinW!=''){?>		
									<option value="<? echo $k?>" selected><? echo $k?></option> 
	                	      <?  }
    	                	    else
								{?>
            	             		<option value="<? echo $k?>"><? echo $k?></option>        		 
					  	<?		}
							} 
						}
						else
						{?>
            	    			<option value="0">0</option>
		           <? } 
				 	}
					else{?>
						<option value="0">0</option>
			<?		}
				}?>				
		        </select> 
    	        <td>  <? if($MinsInicioW!=''){?><img title="Correcto" onClick="diasem(3)" src="/Imgs/b_check.png" style="cursor:hand" > <? }?></td>          
    		    </td>   
	        </tr>
 <? 	}
 		else
		{?>		
        <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	    	<td>Hora</td><td >Minutos</td><td>Hora</td><td >Minutos</td>
    	</tr>
        <tr align="center">
           	<td ><select name="HoraIniW" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
         <? for($j=6;$j<21;$j++) {
				if($j==$HoraIniW){?>
	        		<option value="<? echo $j?>" selected><? echo $j?></option>
          <?	}
				else{?>
                	<option value="<? echo $j?>"><? echo $j?></option>
          <?	} 
		  	}?>        		
	        </select>
          	</td>
        	<td >
             <select name="MinsInicioW" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";> <option></option>
        <?  for($k=0;$k<60;$k+=10) { 
				if($k==$MinsInicioW&&$MinsInicioW!=''){?>
	        		<option value="<? echo $k?>" selected><? echo $k?></option>
            <? }else
				{?>
                	<option value="<? echo $k?>"><? echo $k?></option>
          <?	} 
		  	}?>
	        </select>
            </td>
            <td><? if($MinsInicioW==50){$HoraIW=$HoraIniW+1;}else{$HoraIW=$HoraIniW;}  ?>           
             <select name="HorasFinW" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
         	<?	 
			 if($MinsInicioW!=''){
		 		for($j=$HoraIW;$j<22;$j++){   
                	if($j==$HorasFinW&&HorasFinW>$HoraIniW) { $Ban1W=1;?>            								 	
                  		<option value="<? echo $j?>" selected><? echo $j?></option>
             <? 	}
			 		else{?>
                    	<option value="<? echo $j?>"><? echo $j?></option>
				<?	}
			 	}				 
			}?>        		
	        </select>  
            <td ><?
            	if($Ban1W==1){
					if($HorasFinW!=$HoraIniW){
						$MinsIW=0;//Los minutos iniciales son 50 pero las horas finales son mayores a las horas iniciales
						$BanMFW=1;				
					}
					else{
						if($MinsFinW>$MinsInicioW)
						{							
							$BanMFW=1;
						}						
						$MinsIW=$MinsInicioW+10;					
					}
				}
				else{					
					$MinsIW=$MinsInicioW+10;
					if($HoraIniW==($HoraIW-1)&&$MinsInicioW==50){
						$MinsIW=0;
					}					
				}
				?>
            <select name="MinsFinW">
        <?	if($MinsInicioW!=''){
				if($HorasFinW!=21){	
					if($MinsInicioW!=50||$HoraIniW!=20){
						for($k=$MinsIW;$k<60;$k+=10) {  
        	            	if($BanMFW==1&&$MinsFinW==$k&&$MinsFinW!=''){?>		
								<option value="<? echo $k?>" selected><? echo $k?></option> 
                	      <?  }
                    	    else
							{?>
                         		<option value="<? echo $k?>"><? echo $k?></option>        		 
				  	<?		}
						} 
					}
					else
					{?>
                			<option value="0">0</option>
	           <? } 
			 	}
				else{?>
					<option value="0">0</option>
		<?		}
			}?>
				
	        </select> 
            <td>  <? if($MinsInicioW!=''){?><img title="Correcto" onClick="diasem(3)" src="/Imgs/b_check.png" style="cursor:hand" > <? }?></td>          
    	    </td>   
        </tr>
 <?		}
 	}?> 
</table>    
</td>
<td>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5"  align="center"> 
  <tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">            
		<td colspan="4" >Jueves</td> 
  </tr>    	
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Hora inicio</td><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Hora Fin</td>
    </tr>
     <?
 	$cons="Select  horaini,minsinicio,horasfin,minsfin,idhora,cuppermitido from salud.tempconsexterna 
	where dia='Jueves' and compania='$Compania[0]' and tmpcod='$TMPCOD' order by idhora";
	$res=ExQuery($cons);		
	$nj=ExNumRows($res);
	$nj2=ExNumRows($res);
		while($fila=ExFetch($res))
		{	if($fila[1]==0){$Cero2=0;}else{$Cero2='';}
			if($fila[3]==0){$Cero3=0;}else{$Cero3='';}				
			if($nj==1)
			{
				$HorIJ=$fila[0]; $MinIJ=$fila[1]; $HorFJ=$fila[2]; $MinFJ=$fila[3];?>
				<tr align="center" <? if($fila[5]){ echo "bgcolor='#FF6600' title='resitringidop al cup $fila[5] - ".$Cups[$fila[5]]."'";}?>>
			<?	echo "<td colspan='2'>$fila[0]:$fila[1]$Cero2</td><td colspan='2'>$fila[2]:$fila[3]$Cero3</td><td>";?>				
				<img title="Eliminar" style="cursor:hand" onClick="eliminar(4)" src="/Imgs/b_drop.png"></td>
                <td><img title="Restrigir" style="cursor:hand" onClick="Restringir(4)" src="/Imgs/s_process.png"></td></tr>
               
         <? }
			else
			{?>
            	<tr align="center" <? if($fila[5]){ echo "bgcolor='#FF6600' title='resitringidop al cup $fila[5] - ".$Cups[$fila[5]]."'";}?>>
			<?	echo "<td colspan='2'>$fila[0]:$fila[1]$Cero2</td><td colspan='2'>$fila[2]:$fila[3]$Cero3</td></tr>";
			}
			$nj--;
			
		}
	
	if($HorFJ!=21)
	{
		if($nj2>0)
		{ ?>
        	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    			<td>Hora</td><td >Minutos</td><td>Hora</td><td >Minutos</td>
    		</tr>
	    	<tr align="center">
    	       	<td> 
        	    <select name="HoraIniJ" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";><option></option>
	         <? for($j=$HorFJ;$j<21;$j++) {
					if($j==$HoraIniJ){ ?>
	    	    		<option value="<? echo $j?>" selected><? echo $j?></option>
	          <?	}
					else{?>
        	        	<option value="<? echo $j?>"><? echo $j?></option>
	          <?	} 
			  	}?>        		
	        	</select>     
	          	</td>
    	    	<td ><? if($HoraIniJ==$HorFJ){
							$MIJ=$MinFJ;
						}
						else{							
							$MIJ=0;
						}?>
        	     <select name="MinsInicioJ" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";> <option></option>
	        <?   if($HoraIniJ!=''){	
					for($k=$MIJ;$k<60;$k+=10) { 
						if($k==$MinsInicioJ&&$MinsInicioJ!=''){ ?>
	    	    			<option value="<? echo $k?>" selected><? echo $k?></option>
	            	<? }else{?>
                			<option value="<? echo $k?>"><? echo $k?></option>
                  <?   } 
			  		}
				}?>
	    	    </select>
            	</td>
	            <td><? if($MinsInicioJ==50){$HoraIJ=$HoraIniJ+1;}else{$HoraIJ=$HoraIniJ;}?>           
    	         <select name="HorasFinJ" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
        	 	<?	 
				 if($MinsInicioJ!=''){
		 			for($j=$HoraIJ;$j<22;$j++){   
                		if($j==$HorasFinJ&&HorasFinJ>$HoraIniJ) { $Ban1J=1;?>            								 	
                  			<option value="<? echo $j?>" selected><? echo $j?></option>
	             <? 	}
				 		else{?>
        	            	<option value="<? echo $j?>"><? echo $j?></option>
					<?	}
			 		}				 
				}?>        		
		        </select>  
        	    <td ><?
            		if($Ban1J==1){
						if($HorasFinJ!=$HoraIniJ){
							$MinsIJ=0;//Los minutos iniciales son 50 pero las horas finales son mayores a las horas iniciales
							$BanMFJ=1;				
						}
						else{
							if($MinsFinJ>$MinsInicioJ)
							{							
								$BanMFJ=1;
							}
							$MinsIJ=$MinsInicioJ+10;					
						}
					}
					else{					
						$MinsIJ=$MinsInicioJ+10;
						if($HoraIniJ==($HoraIJ-1)&&$MinsInicioJ==50){
							$MinsIJ=0;
						}					
					}?>
	        	    <select name="MinsFinJ">
    	    <?	if($MinsInicioJ!=''){
					if($HorasFinJ!=21){	
						if($MinsInicioJ!=50||$HoraIniJ!=20){
							for($k=$MinsIJ;$k<60;$k+=10) {  
        	        	    	if($BanMFJ==1&&$MinsFinJ==$k&&$MinsFinJ!=''){?>		
									<option value="<? echo $k?>" selected><? echo $k?></option> 
	                	      <?  }
    	                	    else
								{?>
            	             		<option value="<? echo $k?>"><? echo $k?></option>        		 
					  	<?		}
							} 
						}
						else
						{?>
            	    			<option value="0">0</option>
		           <? } 
				 	}
					else{?>
						<option value="0">0</option>
			<?		}
				}?>				
		        </select> 
    	        <td>  <? if($MinsInicioJ!=''){?><img title="Correcto" onClick="diasem(4)" src="/Imgs/b_check.png" style="cursor:hand" > <? }?></td>          
    		    </td>   
	        </tr>
 <? 	}
 		else
		{?>		
        <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	    	<td >Hora</td><td >Minutos</td><td>Hora</td><td >Minutos</td>
    	</tr>
        <tr align="center">
           	<td><select name="HoraIniJ" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
         <? for($j=6;$j<21;$j++) {
				if($j==$HoraIniJ){?>
	        		<option value="<? echo $j?>" selected><? echo $j?></option>
          <?	}
				else{?>
                	<option value="<? echo $j?>"><? echo $j?></option>
          <?	} 
		  	}?>        		
	        </select>
          	</td>
        	<td >
             <select name="MinsInicioJ" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";> <option></option>
        <?  for($k=0;$k<60;$k+=10) { 
				if($k==$MinsInicioJ&&$MinsInicioJ!=''){?>
	        		<option value="<? echo $k?>" selected><? echo $k?></option>
            <? }else
				{?>
                	<option value="<? echo $k?>"><? echo $k?></option>
          <?	} 
		  	}?>
	        </select>
            </td>
            <td><? if($MinsInicioJ==50){$HoraIJ=$HoraIniJ+1;}else{$HoraIJ=$HoraIniJ;}  ?>           
             <select name="HorasFinJ" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
         	<?	 
			 if($MinsInicioJ!=''){
		 		for($j=$HoraIJ;$j<22;$j++){   
                	if($j==$HorasFinJ&&HorasFinJ>$HoraIniJ) { $Ban1J=1;?>            								 	
                  		<option value="<? echo $j?>" selected><? echo $j?></option>
             <? 	}
			 		else{?>
                    	<option value="<? echo $j?>"><? echo $j?></option>
				<?	}
			 	}				 
			}?>        		
	        </select>  
            <td ><?
            	if($Ban1J==1){
					if($HorasFinJ!=$HoraIniJ){
						$MinsIJ=0;//Los minutos iniciales son 50 pero las horas finales son mayores a las horas iniciales
						$BanMFJ=1;				
					}
					else{
						if($MinsFinJ>$MinsInicioJ)
						{							
							$BanMFJ=1;
						}						
						$MinsIJ=$MinsInicioJ+10;					
					}
				}
				else{					
					$MinsIJ=$MinsInicioJ+10;
					if($HoraIniJ==($HoraIJ-1)&&$MinsInicioJ==50){
						$MinsIJ=0;
					}					
				}
				?>
            <select name="MinsFinJ">
        <?	if($MinsInicioJ!=''){
				if($HorasFinJ!=21){	
					if($MinsInicioJ!=50||$HoraIniJ!=20){
						for($k=$MinsIJ;$k<60;$k+=10) {  
        	            	if($BanMFJ==1&&$MinsFinJ==$k&&$MinsFinJ!=''){?>		
								<option value="<? echo $k?>" selected><? echo $k?></option> 
                	      <?  }
                    	    else
							{?>
                         		<option value="<? echo $k?>"><? echo $k?></option>        		 
				  	<?		}
						} 
					}
					else
					{?>
                			<option value="0">0</option>
	           <? } 
			 	}
				else{?>
					<option value="0">0</option>
		<?		}
			}?>
				
	        </select> 
            <td>  <? if($MinsInicioJ!=''){?><img title="Correcto" onClick="diasem(4)" src="/Imgs/b_check.png" style="cursor:hand" > <? }?></td>          
    	    </td>   
        </tr>
 <?		}
 	}?>   
</table>    
</td>
<td>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5"  align="center"> 
  <tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">            
		<td colspan="4" >Viernes</td>  
  </tr>    	
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Hora inicio</td><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Hora Fin</td>
    </tr>
    <?
 	$cons="Select  horaini,minsinicio,horasfin,minsfin,idhora,cuppermitido from salud.tempconsexterna 
	where dia='Viernes' and compania='$Compania[0]' and tmpcod='$TMPCOD' order by idhora";
	$res=ExQuery($cons);		
	$nv=ExNumRows($res);
	$nv2=ExNumRows($res);
		while($fila=ExFetch($res))
		{	if($fila[1]==0){$Cero2=0;}else{$Cero2='';}
			if($fila[3]==0){$Cero3=0;}else{$Cero3='';}				
			if($nv==1)
			{
				 $HorIV=$fila[0]; $MinIV=$fila[1]; $HorFV=$fila[2]; $MinFV=$fila[3];?>		 
               	<tr align="center" <? if($fila[5]){ echo "bgcolor='#FF6600' title='resitringidop al cup $fila[5] - ".$Cups[$fila[5]]."'";}?>>
			<?	echo "<td colspan='2'>$fila[0]:$fila[1]$Cero2</td><td colspan='2'>$fila[2]:$fila[3]$Cero3</td><td>";?>				
				<img title="Eliminar" style="cursor:hand" onClick="eliminar(5)" src="/Imgs/b_drop.png"></td>
                <td><img title="Restrigir" style="cursor:hand" onClick="Restringir(5)" src="/Imgs/s_process.png"></td></tr>
               
         <? }
			else
			{?>
            	<tr align="center" <? if($fila[5]){ echo "bgcolor='#FF6600' title='resitringidop al cup $fila[5] - ".$Cups[$fila[5]]."'";}?>>
			<?	echo "<td colspan='2'>$fila[0]:$fila[1]$Cero2</td><td colspan='2'>$fila[2]:$fila[3]$Cero3</td></tr>";
			}
			$nv--;
			
		}
	
	if($HorFV!=21)
	{
		if($nv2>0)
		{ ?>
        	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    			<td>Hora</td><td >Minutos</td><td>Hora</td><td >Minutos</td>
    		</tr>
	    	<tr align="center">
    	       	<td> 
        	    <select name="HoraIniV" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";><option></option>
	         <? for($j=$HorFV;$j<21;$j++) {
					if($j==$HoraIniV){ ?>
	    	    		<option value="<? echo $j?>" selected><? echo $j?></option>
	          <?	}
					else{?>
        	        	<option value="<? echo $j?>"><? echo $j?></option>
	          <?	} 
			  	}?>        		
	        	</select>     
	          	</td>
    	    	<td ><? if($HoraIniV==$HorFV){
							$MIV=$MinFV;
						}
						else{							
							$MIV=0;
						}?>
        	     <select name="MinsInicioV" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";> <option></option>
	       <? 	if($HoraIniV!=''){
					for($k=$MIV;$k<60;$k+=10) { 
						if($k==$MinsInicioV&&$MinsInicioV!=''){ ?>
	    	    			<option value="<? echo $k?>" selected><? echo $k?></option>
            	<? 		}else
						{?>
                			<option value="<? echo $k?>"><? echo $k?></option>
	          <?		} 
			  		}
				}?>
	    	    </select>
            	</td>
	            <td><? if($MinsInicioV==50){$HoraIV=$HoraIniV+1;}else{$HoraIV=$HoraIniV;}?>           
    	         <select name="HorasFinV" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
        	 	<?	 
				 if($MinsInicioV!=''){
		 			for($j=$HoraIV;$j<22;$j++){   
                		if($j==$HorasFinV&&HorasFinV>$HoraIniV) { $Ban1V=1;?>            								 	
                  			<option value="<? echo $j?>" selected><? echo $j?></option>
	             <? 	}
				 		else{?>
        	            	<option value="<? echo $j?>"><? echo $j?></option>
					<?	}
			 		}				 
				}?>        		
		        </select>  
        	    <td ><?
            		if($Ban1V==1){
						if($HorasFinV!=$HoraIniV){
							$MinsIV=0;//Los minutos iniciales son 50 pero las horas finales son mayores a las horas iniciales
							$BanMFV=1;				
						}
						else{
							if($MinsFinV>$MinsInicioV)
							{							
								$BanMFV=1;
							}
							$MinsIV=$MinsInicioV+10;					
						}
					}
					else{					
						$MinsIV=$MinsInicioV+10;
						if($HoraIniV==($HoraIV-1)&&$MinsInicioV==50){
							$MinsIV=0;
						}					
					}?>
	        	    <select name="MinsFinV">
    	    <?	if($MinsInicioV!=''){
					if($HorasFinV!=21){	
						if($MinsInicioV!=50||$HoraIniV!=20){
							for($k=$MinsIV;$k<60;$k+=10) {  
        	        	    	if($BanMFV==1&&$MinsFinV==$k&&$MinsFinV!=''){?>		
									<option value="<? echo $k?>" selected><? echo $k?></option> 
	                	      <?  }
    	                	    else
								{?>
            	             		<option value="<? echo $k?>"><? echo $k?></option>        		 
					  	<?		}
							} 
						}
						else
						{?>
            	    			<option value="0">0</option>
		           <? } 
				 	}
					else{?>
						<option value="0">0</option>
			<?		}
				}?>				
		        </select> 
    	        <td>  <? if($MinsInicioV!=''){?><img title="Correcto" onClick="diasem(5)" src="/Imgs/b_check.png" style="cursor:hand" > <? }?></td>          
    		    </td>   
	        </tr>
 <? 	}
 		else
		{?>		
        <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	    	<td>Hora</td><td >Minutos</td><td>Hora</td><td >Minutos</td>
    	</tr>
        <tr align="center">
           	<td><select name="HoraIniV" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
         <? for($j=6;$j<21;$j++) {
				if($j==$HoraIniV){?>
	        		<option value="<? echo $j?>" selected><? echo $j?></option>
          <?	}
				else{?>
                	<option value="<? echo $j?>"><? echo $j?></option>
          <?	} 
		  	}?>        		
	        </select>
          	</td>
        	<td >
             <select name="MinsInicioV" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";> <option></option>
        <?  for($k=0;$k<60;$k+=10) { 
				if($k==$MinsInicioV&&$MinsInicioV!=''){?>
	        		<option value="<? echo $k?>" selected><? echo $k?></option>
            <? }else
				{?>
                	<option value="<? echo $k?>"><? echo $k?></option>
          <?	} 
		  	}?>
	        </select>
            </td>
            <td><? if($MinsInicioV==50){$HoraIV=$HoraIniV+1;}else{$HoraIV=$HoraIniV;}  ?>           
             <select name="HorasFinV" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
         	<?	 
			 if($MinsInicioV!=''){
		 		for($j=$HoraIV;$j<22;$j++){   
                	if($j==$HorasFinV&&HorasFinV>$HoraIniV) { $Ban1V=1;?>            								 	
                  		<option value="<? echo $j?>" selected><? echo $j?></option>
             <? 	}
			 		else{?>
                    	<option value="<? echo $j?>"><? echo $j?></option>
				<?	}
			 	}				 
			}?>        		
	        </select>  
            <td ><?
            	if($Ban1V==1){
					if($HorasFinV!=$HoraIniV){
						$MinsIV=0;//Los minutos iniciales son 50 pero las horas finales son mayores a las horas iniciales
						$BanMFV=1;				
					}
					else{
						if($MinsFinV>$MinsInicioV)
						{							
							$BanMFV=1;
						}						
						$MinsIV=$MinsInicioV+10;					
					}
				}
				else{					
					$MinsIV=$MinsInicioV+10;
					if($HoraIniV==($HoraIV-1)&&$MinsInicioV==50){
						$MinsIV=0;
					}					
				}
				?>
            <select name="MinsFinV">
        <?	if($MinsInicioV!=''){
				if($HorasFinV!=21){	
					if($MinsInicioV!=50||$HoraIniV!=20){
						for($k=$MinsIV;$k<60;$k+=10) {  
        	            	if($BanMFV==1&&$MinsFinV==$k&&$MinsFinV!=''){?>		
								<option value="<? echo $k?>" selected><? echo $k?></option> 
                	      <?  }
                    	    else
							{?>
                         		<option value="<? echo $k?>"><? echo $k?></option>        		 
				  	<?		}
						} 
					}
					else
					{?>
                			<option value="0">0</option>
	           <? } 
			 	}
				else{?>
					<option value="0">0</option>
		<?		}
			}?>
				
	        </select> 
            <td>  <? if($MinsInicioV!=''){?><img title="Correcto" onClick="diasem(5)" src="/Imgs/b_check.png" style="cursor:hand" > <? }?></td>          
    	    </td>   
        </tr>
 <?		}
 	}?>  
</table>    
</td>
</tr>
<tr>
<td colspan="2" align="center">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5"  align="center"> 
  <tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">            
		 <td colspan="4" >Sabado</td>  
  </tr>    	
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Hora inicio</td><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Hora Fin</td>
    </tr>
    <?
 	$cons="Select  horaini,minsinicio,horasfin,minsfin,idhora,cuppermitido from salud.tempconsexterna 
	where dia='Sabado' and compania='$Compania[0]' and tmpcod='$TMPCOD' order by idhora";
	$res=ExQuery($cons);		
	$ns=ExNumRows($res);
	$ns2=ExNumRows($res);
		while($fila=ExFetch($res))
		{	if($fila[1]==0){$Cero2=0;}else{$Cero2='';}
			if($fila[3]==0){$Cero3=0;}else{$Cero3='';}				
			if($ns==1)
			{
				 $HorIS=$fila[0]; $MinIS=$fila[1]; $HorFS=$fila[2]; $MinFS=$fila[3];?>
				<tr align="center" <? if($fila[5]){ echo "bgcolor='#FF6600' title='resitringidop al cup $fila[5] - ".$Cups[$fila[5]]."'";}?>>
			<?	echo "<td colspan='2'>$fila[0]:$fila[1]$Cero2</td><td colspan='2'>$fila[2]:$fila[3]$Cero3</td><td>";?>				
				<img title="Eliminar" style="cursor:hand" onClick="eliminar(6)" src="/Imgs/b_drop.png"></td>
                <td><img title="Restrigir" style="cursor:hand" onClick="Restringir(6)" src="/Imgs/s_process.png"></td></tr>
               
         <? }
			else
			{?>
            	<tr align="center" <? if($fila[5]){ echo "bgcolor='#FF6600' title='resitringidop al cup $fila[5] - ".$Cups[$fila[5]]."'";}?>>
			<?	echo "<td colspan='2'>$fila[0]:$fila[1]$Cero2</td><td colspan='2'>$fila[2]:$fila[3]$Cero3</td></tr>";
			}
			$ns--;
			
		}
	
	if($HorFS!=21)
	{
		if($ns2>0)
		{ ?>
        	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    			<td>Hora</td><td >Minutos</td><td>Hora</td><td >Minutos</td>
    		</tr>
	    	<tr align="center">
    	       	<td> 
        	    <select name="HoraIniS" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";><option></option>
	         <? for($j=$HorFS;$j<21;$j++) {
					if($j==$HoraIniS){ ?>
	    	    		<option value="<? echo $j?>" selected><? echo $j?></option>
	          <?	}
					else{?>
        	        	<option value="<? echo $j?>"><? echo $j?></option>
	          <?	} 
			  	}?>        		
	        	</select>     
	          	</td>
    	    	<td ><? if($HoraIniS==$HorFS){
							$MIS=$MinFS;
						}
						else{							
							$MIS=0;
						}?>
        	     <select name="MinsInicioS" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";> <option></option>
	        <?   if($HoraIniS!=''){
					for($k=$MIS;$k<60;$k+=10) { 
						if($k==$MinsInicioS&&$MinsInicioS!=''){ ?>
	    	    			<option value="<? echo $k?>" selected><? echo $k?></option>
	            	<? }else{?>
                			<option value="<? echo $k?>"><? echo $k?></option>
	          <?		} 
			  		}
				}?>
	    	    </select>
            	</td>
	            <td><? if($MinsInicioS==50){$HoraIS=$HoraIniS+1;}else{$HoraIS=$HoraIniS;}?>           
    	         <select name="HorasFinS" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
        	 	<?	 
				 if($MinsInicioS!=''){
		 			for($j=$HoraIS;$j<22;$j++){   
                		if($j==$HorasFinS&&HorasFinS>$HoraIniS) { $Ban1S=1;?>            								 	
                  			<option value="<? echo $j?>" selected><? echo $j?></option>
	             <? 	}
				 		else{?>
        	            	<option value="<? echo $j?>"><? echo $j?></option>
					<?	}
			 		}				 
				}?>        		
		        </select>  
        	    <td ><?
            		if($Ban1S==1){
						if($HorasFinS!=$HoraIniS){
							$MinsIS=0;//Los minutos iniciales son 50 pero las horas finales son mayores a las horas iniciales
							$BanMFS=1;				
						}
						else{
							if($MinsFinS>$MinsInicioS)
							{							
								$BanMFS=1;
							}
							$MinsIS=$MinsInicioS+10;					
						}
					}
					else{					
						$MinsIS=$MinsInicioS+10;
						if($HoraIniS==($HoraIS-1)&&$MinsInicioS==50){
							$MinsIS=0;
						}					
					}?>
	        	    <select name="MinsFinS">
    	    <?	if($MinsInicioS!=''){
					if($HorasFinS!=21){	
						if($MinsInicioS!=50||$HoraIniS!=20){
							for($k=$MinsIS;$k<60;$k+=10) {  
        	        	    	if($BanMFS==1&&$MinsFinS==$k&&$MinsFinS!=''){?>		
									<option value="<? echo $k?>" selected><? echo $k?></option> 
	                	      <?  }
    	                	    else
								{?>
            	             		<option value="<? echo $k?>"><? echo $k?></option>        		 
					  	<?		}
							} 
						}
						else
						{?>
            	    			<option value="0">0</option>
		           <? } 
				 	}
					else{?>
						<option value="0">0</option>
			<?		}
				}?>				
		        </select> 
    	        <td>  <? if($MinsInicioS!=''){?><img title="Correcto" onClick="diasem(6)" src="/Imgs/b_check.png" style="cursor:hand" > <? }?></td>          
    		    </td>   
	        </tr>
 <? 	}
 		else
		{?>		
        <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	    	<td>Hora</td><td >Minutos</td><td>Hora</td><td >Minutos</td>
    	</tr>
        <tr align="center">
           	<td><select name="HoraIniS" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
         <? for($j=6;$j<21;$j++) {
				if($j==$HoraIniS){?>
	        		<option value="<? echo $j?>" selected><? echo $j?></option>
          <?	}
				else{?>
                	<option value="<? echo $j?>"><? echo $j?></option>
          <?	} 
		  	}?>        		
	        </select>
          	</td>
        	<td >
             <select name="MinsInicioS" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";> <option></option>
        <?  for($k=0;$k<60;$k+=10) { 
				if($k==$MinsInicioS&&$MinsInicioS!=''){?>
	        		<option value="<? echo $k?>" selected><? echo $k?></option>
            <? }else
				{?>
                	<option value="<? echo $k?>"><? echo $k?></option>
          <?	} 
		  	}?>
	        </select>
            </td>
            <td><? if($MinsInicioS==50){$HoraIS=$HoraIniS+1;}else{$HoraIS=$HoraIniS;}  ?>           
             <select name="HorasFinS" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
         	<?	 
			 if($MinsInicioS!=''){
		 		for($j=$HoraIS;$j<22;$j++){   
                	if($j==$HorasFinS&&HorasFinS>$HoraIniS) { $Ban1S=1;?>            								 	
                  		<option value="<? echo $j?>" selected><? echo $j?></option>
             <? 	}
			 		else{?>
                    	<option value="<? echo $j?>"><? echo $j?></option>
				<?	}
			 	}				 
			}?>        		
	        </select>  
            <td ><?
            	if($Ban1S==1){
					if($HorasFinS!=$HoraIniS){
						$MinsIS=0;//Los minutos iniciales son 50 pero las horas finales son mayores a las horas iniciales
						$BanMFS=1;				
					}
					else{
						if($MinsFinS>$MinsInicioS)
						{							
							$BanMFS=1;
						}						
						$MinsIS=$MinsInicioS+10;					
					}
				}
				else{					
					$MinsIS=$MinsInicioS+10;
					if($HoraIniS==($HoraIS-1)&&$MinsInicioS==50){
						$MinsIS=0;
					}					
				}
				?>
            <select name="MinsFinS">
        <?	if($MinsInicioS!=''){
				if($HorasFinS!=21){	
					if($MinsInicioS!=50||$HoraIniS!=20){
						for($k=$MinsIS;$k<60;$k+=10) {  
        	            	if($BanMFS==1&&$MinsFinS==$k&&$MinsFinS!=''){?>		
								<option value="<? echo $k?>" selected><? echo $k?></option> 
                	      <?  }
                    	    else
							{?>
                         		<option value="<? echo $k?>"><? echo $k?></option>        		 
				  	<?		}
						} 
					}
					else
					{?>
                			<option value="0">0</option>
	           <? } 
			 	}
				else{?>
					<option value="0">0</option>
		<?		}
			}?>
				
	        </select> 
            <td>  <? if($MinsInicioS!=''){?><img title="Correcto" onClick="diasem(6)" src="/Imgs/b_check.png" style="cursor:hand" > <? }?></td>          
    	    </td>   
        </tr>
 <?		}
 	}?>   
</table>    
</td>
<td colspan="2" align="center">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5"  align="center"> 
  <tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">            
		 <td colspan="4" >Domingo</td>  
  </tr>    	
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Hora inicio</td><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Hora Fin</td>
    </tr>
    <?
 	$cons="Select  horaini,minsinicio,horasfin,minsfin,idhora,cuppermitido from salud.tempconsexterna 
	where dia='Domingo' and compania='$Compania[0]' and tmpcod='$TMPCOD' order by idhora";
	$res=ExQuery($cons);		
	$nd=ExNumRows($res);
	$nd2=ExNumRows($res);
		while($fila=ExFetch($res))
		{	if($fila[1]==0){$Cero2=0;}else{$Cero2='';}
			if($fila[3]==0){$Cero3=0;}else{$Cero3='';}				
			if($nd==1)
			{
				 $HorID=$fila[0]; $MinID=$fila[1]; $HorFD=$fila[2]; $MinFD=$fila[3];	?>
                 <tr align="center" <? if($fila[5]){ echo "bgcolor='#FF6600' title='resitringidop al cup $fila[5] - ".$Cups[$fila[5]]."'";}?>>			 
			<?	echo "<tr align='center'><td colspan='2'>$fila[0]:$fila[1]$Cero2</td><td colspan='2'>$fila[2]:$fila[3]$Cero3</td><td>";?>				
				<img title="Eliminar" style="cursor:hand" onClick="eliminar(0)" src="/Imgs/b_drop.png"></td>
                <td><img title="Restrigir" style="cursor:hand" onClick="Restringir(0)" src="/Imgs/s_process.png"></td></tr>
               
         <? }
			else
			{?>
            	<tr align="center" <? if($fila[5]){ echo "bgcolor='#FF6600' title='resitringidop al cup $fila[5] - ".$Cups[$fila[5]]."'";}?>>
			<?	echo "<td colspan='2'>$fila[0]:$fila[1]$Cero2</td><td colspan='2'>$fila[2]:$fila[3]$Cero3</td></tr>";
			}
			$nd--;
			
		}
	
	if($HorFD!=21)
	{
		if($nd2>0)
		{ ?>
        	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    			<td>Hora</td><td >Minutos</td><td>Hora</td><td >Minutos</td>
    		</tr>
	    	<tr>
    	       	<td> 
        	    <select name="HoraIniD" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";><option></option>
	         <? for($j=$HorFD;$j<21;$j++) {
					if($j==$HoraIniD){ ?>
	    	    		<option value="<? echo $j?>" selected><? echo $j?></option>
	          <?	}
					else{?>
        	        	<option value="<? echo $j?>"><? echo $j?></option>
	          <?	} 
			  	}?>        		
	        	</select>     
	          	</td>
    	    	<td ><? if($HoraIniD==$HorFD){
							$MID=$MinFD;
						}
						else{							
							$MID=0;
						}?>
        	     <select name="MinsInicioD" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";> <option></option>
	        <?  if($HoraIniD!=''){ 
					 for($k=$MID;$k<60;$k+=10) { 
						if($k==$MinsInicioD&&$MinsInicioD!=''){ ?>
	    		    		<option value="<? echo $k?>" selected><? echo $k?></option>
            	<? 		}else{?>
                			<option value="<? echo $k?>"><? echo $k?></option>
	          <?		} 
			  		}
				}?>
	    	    </select>
            	</td>
	            <td><? if($MinsInicioD==50){$HoraID=$HoraIniD+1;}else{$HoraID=$HoraIniD;}?>           
    	         <select name="HorasFinD" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
        	 	<?	 
				 if($MinsInicioD!=''){
		 			for($j=$HoraID;$j<22;$j++){   
                		if($j==$HorasFinD&&HorasFinD>$HoraIniD) { $Ban1D=1;?>            								 	
                  			<option value="<? echo $j?>" selected><? echo $j?></option>
	             <? 	}
				 		else{?>
        	            	<option value="<? echo $j?>"><? echo $j?></option>
					<?	}
			 		}				 
				}?>        		
		        </select>  
        	    <td ><?
            		if($Ban1D==1){
						if($HorasFinD!=$HoraIniD){
							$MinsID=0;//Los minutos iniciales son 50 pero las horas finales son mayores a las horas iniciales
							$BanMFD=1;				
						}
						else{
							if($MinsFinD>$MinsInicioD)
							{							
								$BanMFD=1;
							}
							$MinsID=$MinsInicioD+10;					
						}
					}
					else{					
						$MinsID=$MinsInicioD+10;
						if($HoraIniD==($HoraID-1)&&$MinsInicioD==50){
							$MinsID=0;
						}					
					}?>
	        	    <select name="MinsFinD">
    	    <?	if($MinsInicioD!=''){
					if($HorasFinD!=21){	
						if($MinsInicioD!=50||$HoraIniD!=20){
							for($k=$MinsID;$k<60;$k+=10) {  
        	        	    	if($BanMFD==1&&$MinsFinD==$k&&$MinsFinD!=''){?>		
									<option value="<? echo $k?>" selected><? echo $k?></option> 
	                	      <?  }
    	                	    else
								{?>
            	             		<option value="<? echo $k?>"><? echo $k?></option>        		 
					  	<?		}
							} 
						}
						else
						{?>
            	    			<option value="0">0</option>
		           <? } 
				 	}
					else{?>
						<option value="0">0</option>
			<?		}
				}?>				
		        </select> 
    	        <td>  <? if($MinsInicioD!=''){?><img title="Correcto" onClick="diasem(0)" src="/Imgs/b_check.png" style="cursor:hand" > <? }?></td>          
    		    </td>   
	        </tr>
 <? 	}
 		else
		{?>		
        <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	    	<td>Hora</td><td >Minutos</td><td>Hora</td><td >Minutos</td>
    	</tr>
        <tr align="center">
           	<td><select name="HoraIniD" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
         <? for($j=6;$j<21;$j++) {
				if($j==$HoraIniD){?>
	        		<option value="<? echo $j?>" selected><? echo $j?></option>
          <?	}
				else{?>
                	<option value="<? echo $j?>"><? echo $j?></option>
          <?	} 
		  	}?>        		
	        </select>
          	</td>
        	<td >
             <select name="MinsInicioD" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";> <option></option>
        <?  for($k=0;$k<60;$k+=10) { 
				if($k==$MinsInicioD&&$MinsInicioD!=''){?>
	        		<option value="<? echo $k?>" selected><? echo $k?></option>
            <? }else
				{?>
                	<option value="<? echo $k?>"><? echo $k?></option>
          <?	} 
		  	}?>
	        </select>
            </td>
            <td><? if($MinsInicioD==50){$HoraID=$HoraIniD+1;}else{$HoraID=$HoraIniD;}  ?>           
             <select name="HorasFinD" onChange="document.FORMA.NoSubmit.value=1;document.FORMA.submit()";>
         	<?	 
			 if($MinsInicioD!=''){
		 		for($j=$HoraID;$j<22;$j++){   
                	if($j==$HorasFinD&&HorasFinD>$HoraIniD) { $Ban1D=1;?>            								 	
                  		<option value="<? echo $j?>" selected><? echo $j?></option>
             <? 	}
			 		else{?>
                    	<option value="<? echo $j?>"><? echo $j?></option>
				<?	}
			 	}				 
			}?>        		
	        </select>  
            <td ><?
            	if($Ban1D==1){
					if($HorasFinD!=$HoraIniD){
						$MinsID=0;//Los minutos iniciales son 50 pero las horas finales son mayores a las horas iniciales
						$BanMFD=1;				
					}
					else{
						if($MinsFinD>$MinsInicioD)
						{							
							$BanMFD=1;
						}						
						$MinsID=$MinsInicioD+10;					
					}
				}
				else{					
					$MinsID=$MinsInicioD+10;
					if($HoraIniD==($HoraID-1)&&$MinsInicioD==50){
						$MinsID=0;
					}					
				}
				?>
            <select name="MinsFinD">
        <?	if($MinsInicioD!=''){
				if($HorasFinD!=21){	
					if($MinsInicioD!=50||$HoraIniD!=20){
						for($k=$MinsID;$k<60;$k+=10) {  
        	            	if($BanMFD==1&&$MinsFinD==$k&&$MinsFinD!=''){?>		
								<option value="<? echo $k?>" selected><? echo $k?></option> 
                	      <?  }
                    	    else
							{?>
                         		<option value="<? echo $k?>"><? echo $k?></option>        		 
				  	<?		}
						} 
					}
					else
					{?>
                			<option value="0">0</option>
	           <? } 
			 	}
				else{?>
					<option value="0">0</option>
		<?		}
			}?>
				
	        </select> 
            <td>  <? if($MinsInicioD!=''){?><img title="Correcto" onClick="diasem(0)" src="/Imgs/b_check.png" style="cursor:hand" > <? }?></td>          
    	    </td>   
        </tr>
 <?		}
 	}?>    
</table>    
</td>
</tr>
<tr><p>&nbsp;</p></tr>
<tr align="center">
	<td colspan="5"><input type="button" value="Guardar" onClick="validar()"><input type="button" value="Cancelar" onClick="salir()"></td>
</tr>
</table>    
<input type="hidden" name="DiaIns" value="">
<input type="hidden" name="Eliminar" value="">
<input type="hidden" name="NoSubmit" value="">
<input type="hidden" name="Guardar" value="">
<input type="hidden" name="Medico" value="<? echo $Medico?>">
<input type="hidden" name="Grupal" value="<? echo $Grupal?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>">
</form>
<iframe scrolling="no" id="FrameOpener2" name="FrameOpener2" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>    
</body>
</html>
