<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($DxElim)
	{
		$cons="delete from contratacionsalud.dxrestriccups where compania='$Compania[0]' and cup='$Codigo' and dx='$DxElim'";	
		$res=ExQuery($cons);
	}
	if($Guardar)
	{
		if(!$Grupo){$Grupo = "NULL";}else{$Grupo = "'".$Grupo."'";}
		if(!$Tipo){$Tipo = "NULL";}else{$Tipo = "'".$Tipo."'";}
		if($SOAT==""){$SOAT = "NULL";}else{$SOAT = "'".$SOAT."'";}
		if(!$DetSOAT){$DetSOAT = "NULL";}else{$DetSOAT = "'".$DetSOAT."'";}
		if(!$Notas){$Notas = "NULL";}else{$Notas = "'".$Notas."'";}
		if(!$Clasificacion){$Clasificacion="0";}		
		if(!$Editar)
		{
			if($EdadIni){$EI1=",edadini"; $EI2="$EdadIni";}
			if($EdadFin){$EF1=",edadfin"; $EF2="$EdadFin";}
			$cons = "Insert into ContratacionSalud.Cups (Compania,Codigo,Nombre,Grupo,Tipo,SOAT,DetalleSOAT,Notas,nopos,FinalidadCup,tipofinalidad
			,CausaExternaCup,dxcup,sexo,ambitocup $EI1 $EF1)	values('$Compania[0]','$Codigo','$Nombre',$Grupo,$Tipo,$SOAT,$DetSOAT,$Notas,$Clasificacion,'$FinalidadCup',$TipFina
			,'$CausaExternaCup','$CodDiagnostico1','$Sexo','$Amb' $EI1 $EF1)";
		}
		else
		{
			if($EdadIni){$EI=",edadini=$EdadIni";}
			if($EdadFin){$EF=",edadfin=$EdadFin";}
			if($TipFina!=NULL){$TF=",tipofinalidad=$TipFina";}
			$cons = "Update ContratacionSalud.Cups set Codigo = '$Codigo', Nombre = '$Nombre', Grupo = $Grupo, Tipo = $Tipo, SOAT = $SOAT,
			DetalleSOAT = $DetSOAT, Notas = $Notas, nopos=$Clasificacion, FinalidadCup='$FinalidadCup' $TF ,CausaExternaCup='$CausaExternaCup'
			,dxcup='$CodDiagnostico1',sexo='$Sexo',ambitocup='$Amb' $EI $EF where Compania = '$Compania[0]' and Codigo = '$CodigoX'";
		}
		$res = ExQuery($cons);
		//echo $cons;
		?>	<script language="javascript">
				location.href="Cups.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $AntCodigo?>&Nombre=<? echo $AntNombre?>&Grupo=<? echo $Grupo?>&Tipo=<? echo $Tipo?>&Clasificacion=<? echo $Clasificacion?>";
         	</script><?
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Codigo.value==""){alert("Debe digitar el Codigo!!!");return false;}
		if(document.FORMA.Nombre.value==""){alert("Debe digitar el Nombre!!!");return false;}
		if(document.FORMA.EdadIni.value!="")
		{
			if(document.FORMA.EdadIni.value<0){alert("La edad inicial debe ser mayor igual a cero!!!");return false;}	
			if(document.FORMA.EdadFin.value!=""){
				if($document.FORMA.EdadIni.value>document.FORMA.EdadFin.value)
				{alert("La edad final debe ser mayor o igual a la edad inicial!!!"); return false;}
			}
		}
		if(document.FORMA.EdadFin.value!=""){
			if(document.FORMA.EdadFin.value<0){alert("La edad final debe ser mayor igual a cero!!!");return false;}	
		}
	}
	function DxRestric(e,cup)
	{		
		x = e.clientX; 
		y = e.clientY; 	
		st = document.body.scrollTop;
		frames.FrameOpener2.location.href="AgregarDxRestric.php?DatNameSID=<? echo $DatNameSID?>&CodCup="+cup;
		document.getElementById('FrameOpener2').style.position='absolute';
		document.getElementById('FrameOpener2').style.top=y+st-200;
		document.getElementById('FrameOpener2').style.left='60px';
		document.getElementById('FrameOpener2').style.display='';
		document.getElementById('FrameOpener2').style.width='800px';
		document.getElementById('FrameOpener2').style.height='400px';
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">

<input type="submit" name="Guardar" value="Guardar" />
<input type="button" name="Cancelar" value="Cancelar" onClick="location.href='Cups.php?DatNameSID=<? echo $DatNameSID?>'" />
<input type="hidden" name="Editar" value="<? echo $Editar?>" />
<input type="hidden" name="CodigoX" value="<? echo $Codigo?>" />
<?
	if($Editar)
	{
		$cons = "Select Nombre,Grupo,Tipo,SOAT,DetalleSOAT,Notas,nopos,FinalidadCup,CausaExternaCup,dxcup,sexo,edadini,edadfin,ambitocup
		from ContratacionSalud.Cups where Compania='$Compania[0]' and Codigo = '$Codigo'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		if(!$Nombre){$Nombre = $fila[0];}
		if(!$Grupo){$Grupo = $fila[1]; }
		if(!$Tipo&&!$RefrescaTipo){$Tipo = $fila[2]; }
		if(!$SOAT){$SOAT = $fila[3]; }
		if(!$DetSOAT){$DetSOAT = $fila[4];}
		if(!$Notas){$Notas = $fila[5]; }
		if(!$Clasificacion){$Clasificacion=$fila[6];}
		if(!$FinalidadCup){$FinalidadCup=$fila[7];}
		if(!$CausaExternaCup){$CausaExternaCup=$fila[8];}
		if(!$CodDiagnostico1){			
			$CodDiagnostico1=$fila[9];
			$cons2="select diagnostico from salud.cie where codigo='$fila[9]'";	
			//echo $cons2;
			$res2=ExQuery($cons2); $fila2=ExFetch($res2);
			$NomDiagnostico1=$fila2[0];
		}
		if(!$Sexo){$Sexo=$fila[10];}
		if(!$EdadIni){$EdadIni=$fila[11];}
		if(!$EdadFin){$EdadFin=$fila[12];}
		if(!$Amb){$Amb=$fila[13];}
	}
?>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2">
	<tr>
    	<td bgcolor="#e5e5e5">Codigo:</td>
        <td><input type="text" name="Codigo" value="<? echo $Codigo?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" style="width:90"
        <? if($Editar){?> readonly<? }?>/></td>    
    	<td bgcolor="#e5e5e5">Nombre CUP:</td>
        <td><input type="text" name="Nombre" value="<? echo $Nombre?>" style="width:400" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" /></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">SOAT:</td> 
        <td><input type="text" name="SOAT" value="<? echo $SOAT?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" style="width:90"/></td> 
        <td bgcolor="#e5e5e5">Detalle SOAT:</td> 
        <td><input type="text" name="DetSOAT" value="<? echo $DetSOAT?>" style="width:400" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" /></td> 
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">Grupo:</td>
        <td colspan="3"><select name="Grupo">
        	<option value=""></option>
            <?
            	$cons = "Select Codigo,Grupo from ContratacionSalud.GruposServicio where Compania = '$Compania[0]'";
				$res = ExQuery($cons);
				while($fila = ExFetch($res))
				{
					if($Grupo==$fila[0]){echo "<option selected value='$fila[0]'>$fila[1]</option>";}
					else{echo "<option value='$fila[0]'>$fila[1]</option>";}
				}
			?>
        </select></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">Tipo:</td>
        <td><select name="Tipo" onChange="document.FORMA.RefrescaTipo.value=1;document.FORMA.submit()">
        	<option></option>
            <?
            	$cons = "Select Codigo,Tipo from ContratacionSalud.TiposServicio where Compania = '$Compania[0]'";
				$res = ExQuery($cons);
				while($fila = ExFetch($res))
				{
					if($Tipo==$fila[0]){echo "<option selected value='$fila[0]'>$fila[1]</option>";}
					else{echo "<option value='$fila[0]'>$fila[1]</option>";}
				}
			?>
        	</select>
        </td>
         <td bgcolor="#e5e5e5">Sexo:</td>
        <td>
        	<select name="Sexo">
            	<option value=""></option>
                <option value="F" <? if($Sexo=="F"){?> selected<? }?>>Femenino</option>
                <option value="M" <? if($Sexo=="M"){?> selected<? }?>>Masculino</option>
            </select>
        </td>
    </tr>
    <tr>	
    	<td bgcolor="#e5e5e5">Clasificaion:</td>
        <td>
        	<select name="Clasificacion">
            <option value="">POS</option>
            <option value="1" <? if($Clasificacion==1){?> selected<? }?>>No POS</option>
        </td>       
        <td bgcolor="#e5e5e5">Causa Externa</td> 
        <td>
  	<?	$cons="select causa,codigo from salud.causaexterna order by causa";
		$res=ExQuery($cons);?>
        <select name="CausaExternaCup">
			<option></option><?
			while($fila=ExFetch($res)){
				if($CausaExternaCup==$fila[1]){
					echo "<option value='$fila[1]' selected>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[1]'>$fila[0]</option>";
				}			
			}
	?>	</select>
    	</td>
    </tr>        
    <tr>
    	<td bgcolor="#e5e5e5">Finalidad</td>        
  	<?	if($Tipo=='00004'){
            $TipoFinalidad="1";
        }
        elseif($Tipo){
            $TipoFinalidad="2";
        }
		else{
			$TipoFinalidad="-1";
		}
        $cons="select finalidad,codigo from salud.finalidadesact where tipo=$TipoFinalidad order by finalidad";	
        $res=ExQuery($cons);?>
        <input type="hidden" name="TipFina" value="<? echo $TipoFinalidad?>">
        <td colspan="3">
        	<select name="FinalidadCup">
				<option></option><?
				while($fila=ExFetch($res)){
					if($FinalidadCup==$fila[1]){
						echo "<option value='$fila[1]' selected>$fila[0]</option>";
					}
					else{
						echo "<option value='$fila[1]'>$fila[0]</option>";
					}			
				}
		?>	</select>
        </td>
   	</tr>     
    <tr>
    	<td bgcolor="#e5e5e5">Edad Incial</td>
        <td><input type="text" name="EdadIni" onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this)" value="<? echo $EdadIni?>"
        	style="width:35;" maxlength="2"></td>
        <td bgcolor="#e5e5e5">Edad Final</td>
        <td><input type="text" name="EdadFin" onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this)" value="<? echo $EdadFin?>"
        style="width:35;" maxlength="3"></td>
   	</tr> 
    <tr>
    	<td bgcolor="#e5e5e5">Proceso</td>
        <td>
        	<select name="Amb">
            	<option></option>
                <option value="PyP" <? if($Amb=="PyP"){?> selected<? }?>>P Y P</option>
                <option value="Recuperacion" <? if($Amb=="Recuperacion"){?> selected<? }?>>Recuperacion</option>
            </select>
        </td>
    </tr>
    <tr> 
    	<td bgcolor="#e5e5e5" colspan="4">Notas</td> 
    </tr> 
    <tr>
    	<td colspan="4"><textarea name="Notas" style="width:100%" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $Notas?></textarea></td>
    </tr>
<?	if($Editar)
	{?>
    	<tr>
        	<td colspan="4">
            <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">
            <tr> 
                <td bgcolor="#e5e5e5" colspan="3" align="center"><strong>Resriccion de Diagnosticos</strong></td> 
            </tr>     
            <tr> 
            	<td colspan="4" align="center"><input type="button" value="Agregar" onClick="DxRestric(event,'<? echo $Codigo?>')"></td> 
        	</tr>     
            <? 	$cons="select dx,diagnostico from contratacionsalud.dxrestriccups,salud.cie where dxrestriccups.compania='$Compania[0]' and dx=codigo
				and cup='$Codigo'";
                $res=ExQuery($cons);
                if(ExNumRows($res)>0)
                {?>
                    <tr  bgcolor="#e5e5e5"><td>Codigo</td><td>Nombre</td><td></td></tr>	
            <?	}
                while($fila=ExFetch($res))
                {?>
                    <tr>
                        <td><? echo $fila[0]?></td><td><? echo $fila[1]?></td>
                        <td>
                            <img style="cursor:hand" title="Eliminar" src="/Imgs/b_drop.png"
                            onClick="if(confirm('Esta seguro de eliminar este registro?')){location.href='NuevoCUP.php?DatNameSID=<? echo $DatNameSID?>&DxElim=<? echo $fila[0]?>&Codigo=<? echo $Codigo?>&Editar=1';}">
                        </td>
                    </tr>
            <?	}
                /*
                <td bgcolor="#e5e5e5">Dx por Defecto</td> 
                <td align="left" colspan="7"><input style="width:100" type="text" readonly name="CodDiagnostico1" 
                    onFocus="ValidaDiagnostico2(this,NomDiagnostico1)"  onKeyUp="ValidaDiagnostico2(this,NomDiagnostico1);xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico1?>">
            Nombre <input type="text" style="width:435px" name="NomDiagnostico1" readonly 
                    onFocus="ValidaDiagnostico2(CodDiagnostico1,this)" onKeyUp="ValidaDiagnostico2(CodDiagnostico1,this);xLetra(this)" onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico1?>">*/?>
       		</table>
       	</td>
		</tr>        
<?	}?>    
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="AntNombre" value="<? echo $AntNombre?>">
<input type="hidden" name="AntCodigo" value="<? echo $AntCodigo?>">
<input type="hidden" name="RefrescaTipo" value="">
<input type="hidden" name="DxElim" value="">
</form>
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe> 
<iframe scrolling="no" id="FrameOpener2" name="FrameOpene2" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe> 
</body>