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
	function Total()
	{
		document.FORMA.VrTotal.value=parseInt(document.FORMA.Cantidad.value)*parseInt(document.FORMA.VrUnd.value);
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
	function AsitenteNew(T)	
	{		
		frames.FrameOpener.location.href="VerCupsoMeds.php?DatNameSID=<? echo $DatNameSID?>&Codigo="+document.FORMA.Codigo.value+"&Nombre="+document.FORMA.Nombre.value+"&Pagador=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&NoContrato=<? echo $NoContrato?>&TipoNuevo="+T+"&AlmacenPpal="+document.FORMA.Almacenppal.value;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=130;
		document.getElementById('FrameOpener').style.left=150;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='750px';
		document.getElementById('FrameOpener').style.height='350px';
		
		st = parent.document.body.scrollTop;
		//parent.frames.FrameOpener.location.href="NewFactura.php?NumServicio=<? echo $NumServicio?>&DatNameSID=<? echo $DatNameSID?>&Edit=1&TMPCOD=<? echo $TMPCOD?>&Tipo=Medicamentos&CedPac=<? echo $CedPac?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&NumServ=<? echo $NumServ?>";
	}
	function Validar()
	{
		if(document.FORMA.Cantidad.value==""||parseInt(document.FORMA.Cantidad.value)<1){alert("Debe digirar una cantdad mayor a cero!!!");return false;}
		if(document.FORMA.VrUnd.value==""){alert("Debe haber un valor unitario!!!");return false;}		
		
	}
</script>
<?
	if($Guardar)
	{
		if(!$Edit) {
		
			$cons="insert into facturacion.tmpcupsomeds (tmpcod,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,generico,presentacion,forma,almacenppal,fecha,dxppal,nofacturable
			,compania,finalidad,causaext,tipodxppal)
			values 
	('$TMPCOD','$Grupo','$Tipo','$Codigo','$Nombre','$Cantidad','$VrUnd','$VrTotal','$Generico','$Presentacion','$Forma','$AlmacenPpal','$Fecha 00:00:00','$CodDiagnostico1','1','$Compania[0]','$FinalidadProc','$CausaExterna','$TipoDx')";
			//echo $cons;
			$res=ExQuery($cons);
			?>
			<script language="javascript">
				
				//location.href='EditarFactura.php?NoFac=<? echo $NoFac?>&TMPCOD=<? echo $TMPCOD?>&BanProd=1&AuxVer=1';
				parent.document.FORMA.AuxVer.value=1;
				parent.document.FORMA.Ver.value="";
				parent.document.FORMA.BanProd.value=1;
				parent.location.href='EditarFactura.php?NoFac=<? echo $NoFac?>&TMPCOD=<? echo $TMPCOD?>&BanProd=1&AuxVer=1&Eliminar=2&DatNameSID=<? echo $DatNameSID?>';
				//parent.document.FORMA.submit();
			</script>
<?      }
	}
?>
<html>
<head>
<script language='javascript' src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<form name="FORMA" method="post" onSubmit="return Validar()">  
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<input type="hidden" name="AlmacenPpal" value="<? echo $Almacenppal?>">
<input type="hidden" name="NoFac" value="<? echo $NoFac?>">
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="VerPagador" value="<? echo $VerPagador?>">   
<input type="hidden" name="NumServ" value="<? echo $NumServ?>">
<input type="hidden" name="TipoServcio" value="<? echo $TipoServcio?>">
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>">
<input type="hidden" name="Entidad" value="<? echo $Entidad?>">
<input type="hidden" name="Contrato" value="<? echo $Contrato?>">
<input type="hidden" name="NoContrato" value="<? echo $NoContrato?>">
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
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
	<tr>
    <?	if($Tipo!="Medicamentos"){$TipoNuevo='Cup';}else{$TipoNuevo='Medicamentos';} ?>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold" align="center">Codigo</td>
            <td><input type="text" <? if($Edit==1){?> readonly<? }?> name="Codigo" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);AsitenteNew('<? echo $TipoNuevo?>')" 
        	onKeyPress="AsitenteNew('<? echo $TipoNuevo?>')" onFocus="AsitenteNew('<? echo $TipoNuevo?>')" style="width:90px" value="<? echo $Codigo?>"></td>    
    	<td  bgcolor="#e5e5e5" style="font-weight:bold"  align="center">Nombre</td>
        <td colspan="5"><input type="text" <? if($Edit==1){?> readonly<? }?> name="Nombre" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);AsitenteNew('<? echo $TipoNuevo?>')" 
        	onKeyPress="AsitenteNew('<? echo $TipoNuevo?>')" onFocus="AsitenteNew('<? echo $TipoNuevo?>')" style="width:580px"  value="<? echo $Nombre?>"></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Cantidad</td>
    	<td><input type="text" name="Cantidad" value="<? echo $Cantidad?>" style="width:80; text-align:right"
        	onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this);Total()"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Vr Unidad</td>
    	<td><input type="text" readonly name="VrUnd" value="<? echo $VrUnd?>" style="width:80; text-align:right"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Vr Total</td>
  	<?	if(!$VrTotal){$VrTotal=$VrUnd*$Cantidad;}?>
    	<td><input type="text" readonly name="VrTotal" value="<? echo $VrTotal?>" style="width:80; text-align:right"></td>
    <?	if(!$Fecha){$Fecha=$FechFin;}?>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Fecha</td>
    	<td><input type="text" readonly name="Fecha" onClick="popUpCalendar(this, FORMA.Fecha, 'yyyy-mm-dd')" value="<? echo $Fecha?>" style="width:80; text-align:right">
    </tr>
    <tr>    
<?	if($Tipo=="Medicamentos"){?>
		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Almacen Principal</td>
	<?	$cons2="select almacenppal from consumo.almacenesppales where compania='$Compania[0]' and ssfarmaceutico=1";
		$res2=ExQuery($cons2);?>
        <td colspan="7">
            <select name="Almacenppal">
        <?	while($fila2=ExFetch($res2)){
                if($fila2[0]==$Almacenppal){
                    echo "<option value='$fila2[0]' selected>$fila2[0]</option>";
                }
                else{
                    echo "<option value='$fila2[0]'>$fila2[0]</option>";
                }
            }?>
            </select>
        </td>
<?	}
	else{?>
    	<input type="hidden" name="Almacenppal">
	<?  $cons="select tipodiagnost,codigo from salud.tiposdiagnostico where compania='$Compania[0]'";
        $res=ExQuery($cons);?>
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
        </tr>
   	<?	$cons="select dxserv,diagnostico from salud.servicios,salud.cie where compania='$Compania[0]' and cedula='$CedPac' and numservicio=$NumServ and cie.codigo=dxserv";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		if(!$CodDiagnostico1){$CodDiagnostico1=$fila[0];}
		if(!$NomDiagnostico1){$NomDiagnostico1=$fila[1];}?>
        <tr>
        <?	if($CodDiagnostico1=="NoCod"){$CodDiagnostico1="";}?>
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
        <tr align="center">
        	<td colspan="11">
            	<input type="submit" name="Guardar" value="Guardar">
            </td>
        </tr>
<?	}?>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>  
<iframe scrolling="yes" id="FrameOpener2" name="FrameOpene2" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>     
</body>
</html>
