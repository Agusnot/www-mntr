<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	$cons="select grupo,grupofact from consumo.grupos where compania='$Compania[0]' and anio='$ND[year]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$GruposMeds[$fila[0]]=$fila[1];
	}
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
		if($TMPCOD=='')
		{
			$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);
			$BanTMP=1;
		}
		if($Edit){
			$cons2="delete from facturacion.tmpcupsomeds where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$Codigo' and tmpcod='$TMPCOD'";
			$res=ExQuery($cons2);
		}
			$cons2="select codigo,cantidad,vrund from facturacion.tmpcupsomeds where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$Codigo' and tmpcod='$TMPCOD'";
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)>0){				
				?><script language="javascript">//alert("Este codigo ya ha sido registrado!!!");</script><?
				$fila2=ExFetch($res2);
				//$Cantidad=$Cantidad+$fila2[1];
				$VrTot=$Cantidad*$fila2[2];
				$cons="update facturacion.tmpcupsomeds set cantidad=$Cantidad,vrtotal=$VrTot,ambito=$Ambito,fecha='$Fecha 00:00:00'
				where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$Codigo' and tmpcod='$TMPCOD'";
				//$res=ExQuery($cons);
			}
			else{
				if($TipoNuevo=="Cup"){
					$cons="insert into facturacion.tmpcupsomeds (compania,tmpcod,cedula,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,ambito,finalidad,causaext,dxppal,tipodxppal,fecha) 
					values ('$Compania[0]','$TMPCOD','$Paciente[1]','$Grupo','$Tipo','$Codigo','$Nombre',$Cantidad,$VrUnidad,$VrTotal,$Ambito,'$FinalidadProc','$CausaExterna'
					,'$CodDiagnostico1','$TipoDx','$Fecha 00:00:00')";
					/*$cons="insert into facturacion.tmpcupsomeds (compania,tmpcod,cedula,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,ambito,finalidad,causaext,dxppal,tipodxppal,fecha) 
					values ('$Compania[0]','$TMPCOD','$Paciente[1]','$Grupo','$Tipo','$Codigo','$Nombre',$Cantidad,$VrUnidad,$VrTotal,$Ambito,'$FinalidadProc','$CausaExterna'
					,'$CodDiagnostico1','$TipoDx','$Fecha 00:00:00')";*/
				}
				else{
					//Selección del Grupo
					echo $consGrupo1="select grupo,unidadmedida,presentacion,cum,codigo2 from consumo.codproductos where codigo1='$Codigo' and almacenppal='FARMACIA'";
					$resGrupo1=ExQuery($consGrupo1);
					$filaGrupo1=ExFetch($resGrupo1);
					$consGrupo2="select codigo,grupomeds from contratacionsalud.gruposservicio where grupo='$filaGrupo1[0]'";
					$resGrupo2=ExQuery($consGrupo2);
					$filaGrupo2=ExFetch($resGrupo2);
					//Selección de CUM
					$consCUM1="select reginvima from consumo.lotes where tipo='Entradas Farmacia' and autoid='$Codigo' order by numero desc limit 1";
					$resCUM1=ExQuery($consCUM1);
					$filaCUM1=ExFetch($resCUM1);
					$consCUM2="select cum from consumo.cumsxproducto where reginvima='$filaCUM1[0]' and autoid='$Codigo' limit 1";
					$resCUM2=ExQuery($consCUM2);
					$filaCUM2=ExFetch($resCUM2);
					if($Ambito==2){
						$newAmbito="Hospitalizacion";
					}
					$cons="insert into facturacion.tmpcupsomeds (compania,tmpcod,cedula,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,generico,presentacion,forma,almacenppal,fecha,noentregado,labnointerp,nofacturable,ambito,cum,atc) values('$Compania[0]','$TMPCOD','$Paciente[1]','$filaGrupo2[0]','$filaGrupo2[1]','$Codigo','$Nombre','$Cantidad','$VrUnidad','$VrTotal','$Nombre','$filaGrupo1[1]','$filaGrupo1[2]','FARMACIA','$Fecha 00:00:00','1','0','0','$newAmbito','$filaCUM2[0]','$filaGrupo1[4]') ";
				}
				//$cons;				
			}
			$res=ExQuery($cons);
			?><script language="javascript">
			parent.document.FORMA.NoEnvia.value=1;
			parent.document.FORMA.TMPCOD2.value=<? echo $TMPCOD?>;
			parent.document.FORMA.submit();</script><?
			
		//}
			
	}
	
	if($Edit){	
		if($TipoNuevo=="Cup"){
			//Verificar cuando diferente dx
			$Mas=",finalidad,causaext,dxppal,tipodxppal";
			//$Mas2=" order by finalidad,causaext,dxppal,tipodxppal";
		}
		$cons="select grupo,cantidad,vrund,vrtotal,generico,presentacion,forma,almacenppal,tipo $Mas from facturacion.tmpcupsomeds 
		where tmpcod='$TMPCOD' and cedula='$Paciente[1]' and compania='$Compania[0]' and codigo='$CodCoM' $Mas2";		
		//echo $cons;
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Codigo=$CodCoM; $Nombre=$NomCoM; $Cantidad=$fila[1]; $VrUnidad=$fila[2]; $VrTotal=$fila[3]; $Generico=$fila[4]; $Presentacion=$fila[5]; $Forma=$fila[6]; $Grupo=$fila[0];
		$Tipo=$fila[8]; $AlmacenPpal=$fila[7];
		$FinalidadProc=$fila[9]; $CausaExterna=$fila[10]; $TipoDx=$fila[12]; $CodDiagnostico1=$fila[11];
		
		if($fila[11]){$cons="select diagnostico from salud.cie where codigo='$fila[11]'"; $res=ExQuery($cons); $fila=ExFetch($res); $NomDiagnostico1=$fila[0];}
		
		$consCant="select codigo,sum(cantidad),sum(vrtotal) from facturacion.tmpcupsomeds where tmpcod='$TMPCOD' and cedula='$Paciente[1]' and compania='$Compania[0]' and codigo='$CodCoM'
		group by codigo";
		//echo $consCant;
		$resCant=ExQuery($consCant);
		$filaCant=ExFetch($resCant); $Cantidad=$filaCant[1]; $VrTotal=$filaCant[2];
	}
	$consAmb="select consultaextern,hospitalizacion,hospitaldia,pyp,urgencias from salud.servicios,salud.ambitos
	where servicios.compania='$Compania[0]' and servicios.cedula='$Paciente[1]' and servicios.numservicio=$NumServ and ambitos.compania='$Compania[0]'
	and tiposervicio=ambito";
	//echo $consAmb;
	$resAmb=ExQuery($consAmb);
	$filaAmb=ExFetch($resAmb);
	if($filaAmb[0]==1||$filaAmb[2]==1||$filaAmb[3]==1){
		$Ambito="1";
	}
	if($filaAmb[1]=="1"){
		$Ambito="2";
	}
	if($filaAmb[4]=="1"){
		$Ambito="3";
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function AsitenteNew(T)	
	{		
		frames.FrameOpener.location.href="VerCupsoMeds.php?DatNameSID=<? echo $DatNameSID?>&Codigo="+document.FORMA.Codigo.value+"&Nombre="+document.FORMA.Nombre.value+"&Pagador=<? echo $Paga?>&Contrato=<? echo $PagaCont?>&NoContrato=<? echo $PagaNocont?>&TipoNuevo=<? echo $TipoNuevo?>&AlmacenPpal="+document.FORMA.AlmacenPpal.value;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=130;
		document.getElementById('FrameOpener').style.left=150;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='750px';
		document.getElementById('FrameOpener').style.height='350px';
	}
	function ValidaDiagnostico2(Objeto1,Objeto2)
	{		
		frames.FrameOpener2.location.href="ValidaDiagnostico2.php?DatNameSID=<? echo $DatNameSID?>&NameCod="+Objeto1.name+"&NameNom="+Objeto2.name;
		document.getElementById('FrameOpener2').style.position='absolute';
		document.getElementById('FrameOpener2').style.top='50%';
		document.getElementById('FrameOpener2').style.left='50px';
		document.getElementById('FrameOpener2').style.display='';
		document.getElementById('FrameOpener2').style.width='800px';
		document.getElementById('FrameOpener2').style.height='200px';
	}
	function Validar()
	{
		if(document.FORMA.Codigo.value==""){alert("Debe digitar el codigo!!!");return false;}
		if(document.FORMA.Nombre.value==""){alert("Debe digitar el Nombre!!!");return false;}
		if(document.FORMA.Cantidad.value==""){alert("Debe Cantidad la Cantidad!!!");return false;}
		if(document.FORMA.VrUnidad.value==""){alert("Debe haber un valor unitario!!!");return false;}
		//if(document.FORMA.VrTotal.value=="0"||document.FORMA.VrTotal.value=="NaN"){alert("El valor total debe ser un numero mayor a cero!!!");return false;}
		if(document.FORMA.Fecha.value==""){alert("Debe seleccionar una fecha!!!");return false;}
		if(document.FORMA.Fecha.value>document.FORMA.FecFinLiq2.value||document.FORMA.Fecha.value<document.FORMA.FecIniLiq.value){
			alert("La fecha debe estar dentro del periodo seleccionado");return false;
		}
		if(document.FORMA.CodDiagnostico1.value==""){alert("Debe seleccionar un diagnostico!!!");return false;}
	}
	function Total()
	{
		document.FORMA.VrTotal.value=document.FORMA.Cantidad.value*document.FORMA.VrUnidad.value;
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">

<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="VerPagador" value="<? echo $VerPagador?>">   
<input type="hidden" name="NumServ" value="<? echo $NumServ?>">
<input type="hidden" name="TipoServcio" value="<? echo $TipoServcio?>">
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>">
<input type="hidden" name="Paga" value="<? echo $Paga?>">
<input type="hidden" name="PagaCont" value="<? echo $PagaCont?>">
<input type="hidden" name="PagaNocont" value="<? echo $PagaNocont?>">
<input type="hidden" name="Grupo" value="<? echo $Grupo?>">
<input type="hidden" name="Tipo" value="<? echo $Tipo?>">
<input type="hidden" name="Generico" value="<? echo $Generico?>">
<input type="hidden" name="Presentacion" value="<? echo $Presentacion?>">
<input type="hidden" name="Forma" value="<? echo $Forma?>">
<input type="hidden" name="CodigoAnt" value="<? echo $Codigo?>">
<input type="hidden" name="AlmacenPpalAnt" value="<? echo $AlmacenPpal?>">
<input type="hidden" name="Ambito" value="<? echo $Ambito?>">
<input type="hidden" name="FecIniLiq" value="<? echo $FecIniLiq?>">
<input type="hidden" name="FecFinLiq2" value="<? echo $FecFinLiq2?>">

<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center"> 
	<tr align="center">
    	<td  bgcolor="#e5e5e5" style="font-weight:bold" colspan="8">Nuevo <? echo $TipoNuevo?></td>
    </tr>
	<tr>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold" align="center">Codigo</td>
        <td><input type="text" name="Codigo" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);AsitenteNew('<? echo $TipoNuevo?>')" 
        	onKeyPress="AsitenteNew('<? echo $TipoNuevo?>')" onFocus="AsitenteNew('<? echo $TipoNuevo?>')" style="width:90px" value="<? echo $Codigo?>"></td>    
    	<td  bgcolor="#e5e5e5" style="font-weight:bold"  align="center">Nombre</td>
        <td colspan="5"><input type="text" name="Nombre" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);AsitenteNew('<? echo $TipoNuevo?>')" 
        	onKeyPress="AsitenteNew('<? echo $TipoNuevo?>')" onFocus="AsitenteNew('<? echo $TipoNuevo?>')" style="width:580px"  value="<? echo $Nombre?>"></td>
    </tr>
    <tr>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold"  align="center">Cantidad</td>
        <td><input type="text" name="Cantidad" onKeyDown="xNumero(this)" onKeyUp="xNumero(this);Total()" onKeyPress="Total()" style="width:80px" value="<? echo $Cantidad?>"></td>
    
    	<td  bgcolor="#e5e5e5" style="font-weight:bold" align="center">Vr Unidad</td>
        <td><input type="text" name="VrUnidad" style="width:80px" onBlur="Total()"  value="<? echo $VrUnidad?>"></td>

    	<td  bgcolor="#e5e5e5" style="font-weight:bold" align="center">Vr Total</td>
        <td><input type="text" name="VrTotal" readonly style="width:90px"  value="<? echo $VrTotal?>"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Fecha</td>
    	<td><input type="Text" name="Fecha"  readonly onClick="popUpCalendar(this, FORMA.Fecha, 'yyyy-mm-dd')" value="<? echo $Fecha?>" style="width:80"></td>
    </tr>
<?	if($TipoNuevo!="Cup"){
		$cons2="select almacenppal from consumo.almacenesppales where compania='$Compania[0]' and ssfarmaceutico=1";
		$res2=ExQuery($cons2);?>
        <tr>
			<td  bgcolor="#e5e5e5" style="font-weight:bold" colspan="1">Almacen Principal</td>
    	    <td colspan="7"><select name="AlmacenPpal">
       	<?	while($fila2=ExFetch($res2)){
				if($fila2[0]==$AlmacenPpal){
					echo "<option value='$fila2[0]' selected>$fila2[0]</option>";
				}
				else{
					echo "<option value='$fila2[0]'>$fila2[0]</option>";
				}
			}?>
            </select></td>
       </tr>
<?	}
	else{?>
		<input type="hidden" name="AlmacenPpal">
<?	}
	if($TipoNuevo=="Cup"&&$Codigo){?> 
    	<tr>
        <?	/*
        	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Ambito</td>
        	<td>
            <?	$cons="select ambito,codigo from salud.ambitosprocedimientos order by ambito";
				$res=ExQuery($cons);?>	
            	<select name="AmbitoRealiz"><?
				while($fila=ExFetch($res)){
					if($AmbitoRealiz==$fila[0]){
						echo "<option value='$fila[1]' selected>$fila[0]</option>";
					}
					else{
						echo "<option value='$fila[1]'>$fila[0]</option>";
					}			
				}
			?>	</select>
            </td>*/?>
            <?  $cons="select tipodiagnost,codigo from salud.tiposdiagnostico where compania='$Compania[0]'";
				$res=ExQuery($cons);?>
				<tr>
					<td bgcolor="#e5e5e5" style="font-weight:bold">Tipo de Diagnostico</td>
					<td>
						<select name="TipoDx"><?
							while($fila=ExFetch($res)){
								if($TipoDx==$fila[1]){
									echo "<option value='$fila[1]' selected>$fila[0]</option>";
								}
								else{
									echo "<option value='$fila[1]'>$fila[0]</option>";
								}			
							}
					?>	</select>
            </td>
         <?	$cons="select tipo from contratacionsalud.tiposservicio where compania='$Compania[0]' and codigo='$Tipo'";	
			$res=ExQuery($cons); $fila=ExFetch($res);	
			if($fila[0]=='Consulta'){
				$TipoFinalidad="1";
			}
			else{
				$TipoFinalidad="2";
			}
			$cons="select finalidad,codigo from salud.finalidadesact where tipo=$TipoFinalidad";	
			$res=ExQuery($cons);?>
			<td bgcolor="#e5e5e5" style="font-weight:bold">Finalidad Procedimiento</td>
            <td colspan="3">
				<select name="FinalidadProc"><?
				while($fila=ExFetch($res)){
					if($FinalidadProc==$fila[1]){
						echo "<option value='$fila[1]' selected>$fila[0]</option>";
					}
					else{
						echo "<option value='$fila[1]'>$fila[0]</option>";
					}	
				}?>
        		</select>
        	</td>
            <?	//if($TipoFinalidad=="1"){    
			$cons="select causa,codigo from salud.causaexterna order by causa";
			$res=ExQuery($cons);?>		
			<td bgcolor="#e5e5e5" style="font-weight:bold">Causa Externa</td>
            <td>
				<select name="CausaExterna"><?
					while($fila=ExFetch($res)){
						if($CausaExterna==$fila[1]){
							echo "<option value='$fila[1]' selected>$fila[0]</option>";
						}
						else{
							echo "<option value='$fila[1]'>$fila[0]</option>";
						}			
					}
			?>	</select>
			</td>
     	<tr>
        <?	
			$cons="select dxserv,diagnostico from salud.servicios,salud.cie where compania='$Compania[0]' and numservicio=$NumServ and cie.codigo=dxserv";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$CodDiagnostico1=$fila[0];
			if(!$CodDiagnostico1||$CodDiagnostico1=="NoCod"){}
			if(!$NomDiagnostico1){$NomDiagnostico1=$fila[1];}
			if($CodDiagnostico1=="NoCod"){$CodDiagnostico1="";}?>
        	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Codigo Dx</td>
        	<td>
            	<input style="width:100" type="text" readonly name="CodDiagnostico1" 
        	onFocus="ValidaDiagnostico2(this,NomDiagnostico1)"  onKeyUp="ValidaDiagnostico2(this,NomDiagnostico1);xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico1?>">
            </td>
            <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Nombre Dx</td>
            <td colspan="5">
            	<input type="text" style="width:580px" name="NomDiagnostico1" readonly 
        	onFocus="ValidaDiagnostico2(CodDiagnostico1,this)" onKeyUp="ValidaDiagnostico2(CodDiagnostico1,this);xLetra(this)" onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico1?>">
         	</td>		
   		</tr>    
<?	}
	else{?>
    	<input type="hidden" name="CodDiagnostico1" value="NoCod">
<?	}?>      
    <tr>
    	<td colspan="8" align="center"><input type="submit" value="Guardar" name="Guardar"/><input type="button" value="Cancelar"
        onclick="CerrarThis()"/></td>
    </tr>
</table>
<input type="hidden" name="TipoNuevo" value="<? echo $TipoNuevo?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>  
<iframe scrolling="no" id="FrameOpener2" name="FrameOpene2" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe> 
</body>
</html>
