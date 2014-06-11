<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	if(!$Ambito2){$Ambito2=$Ambito;}
	$cons="select grupo,grupofact from consumo.grupos where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$GruposMeds[$fila[0]]=$fila[1];
	}
	if($Guardar){
		if($TMPCOD=='')
		{
			$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);
			$BanTMP=1;
		}
		if($Edit){
			$cons2="delete from facturacion.tmpcupsomeds where compania='$Compania[0]' and cedula='$CedPac' and codigo='$Codigo' and tmpcod='$TMPCOD'";
			$res=ExQuery($cons2);
		}
			$cons2="select codigo,cantidad,vrund from facturacion.tmpcupsomeds where compania='$Compania[0]' and cedula='$CedPac' and codigo='$Codigo' and tmpcod='$TMPCOD'";
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)>0){				
				?><script language="javascript">//alert("Este codigo ya ha sido registrado!!!");</script><?
				$fila2=ExFetch($res2);
				//$Cantidad=$Cantidad+$fila2[1];
				$VrTot=$Cantidad*$fila2[2];
				$cons="update facturacion.tmpcupsomeds set cantidad=$Cantidad,vrtotal=$VrTot,ambito=1,fecha='$Fecha 00:00:00'
				where compania='$Compania[0]' and cedula='$CedPac' and codigo='$Codigo' and tmpcod='$TMPCOD'";
				//$res=ExQuery($cons);
			}
			else{
				if($TipoNuevo=="Cup"){
					$cons="insert into facturacion.tmpcupsomeds (compania,tmpcod,cedula,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,ambito,finalidad,causaext,dxppal,tipodxppal,fecha) 
					values ('$Compania[0]','$TMPCOD','$CedPac','$Grupo','$Tipo','$Codigo','$Nombre',$Cantidad,$VrUnd,$VrTotal,1,'$FinalidadProc','$CausaExterna'
					,'$CodDiagnostico1','$TipoDx','$Fecha 00:00:00')";
				}
				else{
					$Grupo=$GruposMeds[$Grupo];
					$cons="insert into facturacion.tmpcupsomeds 
					(compania,tmpcod,cedula,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,generico,presentacion,forma,almacenPpal,ambito,fecha) values 
					('$Compania[0]','$TMPCOD','$CedPac','$Grupo','Medicamentos','$Codigo','$Nombre',$Cantidad,$VrUnd,$VrTotal,'$Generico','$Presentacion','$Forma','$AlmacenPpal',
					1,'$Fecha 00:00:00')";
				}//Cambiar el ambito xq se deja 1 pero debe revisarse el ajuste para poner $Ambito
				
				//echo $cons;				
			}
			$res=ExQuery($cons);
			?><script language="javascript">
			location.href='ServxConsolidxPac.php?DatNameSID=<? echo $DatNameSID?>&NumServM=<? echo $NumServM?>&NoLiq=<? echo $NoLiq?>&CedPac=<? echo $CedPac?>&Ambito=<? echo $Ambito2?>&PagaM=<? echo $PagaM?>&ContraM=<? echo $ContraM?>&NoContraM=<? echo $NoContraM?>&TMPCOD2=<? echo $TMPCOD?>&Valordescuento=<? echo $Valordescuento?>&NomPac=<? echo $NomPac?>&NomPac=<? echo $NomPac?>&VrCopato=<? echo $VrCopato?>&FechaIni=<? echo $FecIniLiq?>';
			</script><?
			
		//}
			
	}
	
	if($Edit){	
		if($TipoNuevo=="Cup"){
			//Verificar cuando diferente dx
			$Mas=",finalidad,causaext,dxppal,tipodxppal";
			//$Mas2=" order by finalidad,causaext,dxppal,tipodxppal";
		}
		$cons="select grupo,cantidad,vrund,vrtotal,generico,presentacion,forma,almacenppal,tipo $Mas from facturacion.tmpcupsomeds 
		where tmpcod='$TMPCOD' and cedula='$CedPac' and compania='$Compania[0]' and codigo='$CodCoM' $Mas2";		
		//echo $cons;
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Codigo=$CodCoM; $Nombre=$NomCoM ; $Cantidad=$fila[1]; $VrUnd=$fila[2]; $VrTotal=$fila[3]; $Generico=$fila[4]; $Presentacion=$fila[5]; $Forma=$fila[6]; $Grupo=$fila[0];
		$Tipo=$fila[8]; $AlmacenPpal=$fila[7];
		$FinalidadProc=$fila[9]; $CausaExterna=$fila[10]; $TipoDx=$fila[12]; $CodDiagnostico1=$fila[11];
		
		if($fila[11]){$cons="select diagnostico from salud.cie where codigo='$fila[11]'"; $res=ExQuery($cons); $fila=ExFetch($res); $NomDiagnostico1=$fila[0];}
		
		$consCant="select codigo,sum(cantidad),sum(vrtotal) from facturacion.tmpcupsomeds where tmpcod='$TMPCOD' and cedula='$CedPac' and compania='$Compania[0]' and codigo='$CodCoM'
		group by codigo";
		//echo $consCant;
		$resCant=ExQuery($consCant);
		$filaCant=ExFetch($resCant); $Cantidad=$filaCant[1]; $VrTotal=$filaCant[2];
	}
	$consAmb="select consultaextern,hospitalizacion,hospitaldia,pyp,urgencias from salud.servicios,salud.ambitos
	where servicios.compania='$Compania[0]' and servicios.cedula='$CedPac' and servicios.numservicio=$NumServM and ambitos.compania='$Compania[0]'
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
		frames.FrameOpener.location.href="VerCupsoMeds.php?DatNameSID=<? echo $DatNameSID?>&Codigo="+document.FORMA.Codigo.value+"&Nombre="+document.FORMA.Nombre.value+"&Pagador=<? echo $PagaM?>&Contrato=<? echo $ContraM?>&NoContrato=<? echo $NoContraM?>&TipoNuevo=<? echo $TipoNuevo?>&AlmacenPpal="+document.FORMA.AlmacenPpal.value;
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
		if(document.FORMA.VrUnd.value==""){alert("Debe haber un valor unitario!!!");return false;}
		if(document.FORMA.VrTotal.value=="0"||document.FORMA.VrTotal.value=="NaN"){alert("El valor total debe ser un numero mayor a cero!!!");return false;}
		if(document.FORMA.Fecha.value==""){alert("Debe seleccionar una fecha!!!");return false;}
		if(document.FORMA.Fecha.value>document.FORMA.FecFinLiq2.value||document.FORMA.Fecha.value<document.FORMA.FecIniLiq.value){
			alert("La fecha debe estar dentro del periodo seleccionado");return false;
		}
		if(document.FORMA.CodDiagnostico1.value==""){alert("Debe seleccionar un diagnostico!!!");return false;}
	}
	function Total()
	{
		document.FORMA.VrTotal.value=document.FORMA.Cantidad.value*document.FORMA.VrUnd.value;
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">

<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="VerPagador" value="<? echo $VerPagador?>">   
<input type="hidden" name="NumServM" value="<? echo $NumServM?>">
<input type="hidden" name="TipoServcio" value="<? echo $TipoServcio?>">
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>">
<input type="hidden" name="PagaM" value="<? echo $PagaM?>">
<input type="hidden" name="ContraM" value="<? echo $ContraM?>">
<input type="hidden" name="NoContraM" value="<? echo $NoContraM?>">
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
<input type="hidden" name="NomPac" value="<? echo $NomPac?>">


<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center"> 
	<tr align="center">
    	<td  bgcolor="#e5e5e5" style="font-weight:bold" colspan="8">Nuevo <? echo $TipoNuevo?> - Fecha Inicio: <? echo $FecIniLiq?> Fecha Fin <? echo $FecFinLiq2?> 
        </td>
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
        <td><input type="text" name="VrUnd" readonly style="width:80px" onBlur="Total()"  value="<? echo $VrUnd?>"></td>

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
        	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Proceso</td>
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
			$cons="select dxserv,diagnostico from salud.servicios,salud.cie where compania='$Compania[0]' and numservicio=$NumServM and cie.codigo=dxserv";
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
        onclick="location.href='ServxConsolidxPac.php?DatNameSID=<? echo $DatNameSID?>&NumServM=<? echo $NumServM?>&NoLiq=<? echo $NoLiq?>&CedPac=<? echo $CedPac?>&Ambito=<? echo $Ambito2?>&PagaM=<? echo $PagaM?>&ContraM=<? echo $ContraM?>&NoContraM=<? echo $NoContraM?>&TMPCOD2=<? echo $TMPCOD?>&Valordescuento=<? echo $Valordescuento?>&VrCopato=<? echo $VrCopato?>&NomPac=<? echo $NomPac?>&FechaIni=<? echo $FecIniLiq?>'"/></td>
    </tr>
   
</table>
<input type="hidden" name="TipoNuevo" value="<? echo $TipoNuevo?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="NumServM" value="<? echo $NumServM?>">
<input type="hidden" name="NoLiq" value="<? echo $NoLiq?>">
<input type="hidden" name="CedPac" value="<? echo $CedPac?>">
<input type="hidden" name="NomPac" value="<? echo $NomPac?>">
<input type="hidden" name="Ambito" value="<? echo $Ambito?>">
<input type="hidden" name="Ambito2" value="<? echo $Ambito2?>">
<input type="hidden" name="PagaM" value="<? echo $PagaM?>">
<input type="hidden" name="ContraM" value="<? echo $ContraM?>">
<input type="hidden" name="NoContraM" value="<? echo $NoContraM?>">
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>">
<input type="hidden" name="Valordescuento" value="<? echo $Valordescuento?>">
<input type="hidden" name="VrCopato" value="<? echo $VrCopato?>">
<input type="hidden" name="FecIniLiq" value="<? echo $FecIniLiq?>">
<input type="hidden" name="FecFinLiq2" value="<? echo $FecFinLiq2?>">
</form>
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>  
<iframe scrolling="no" id="FrameOpener2" name="FrameOpene2" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe> 
</body>
</html>
