<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	
	require_once "classes/_Var.php";
    $Var=_Var::getInstance();
    $Var->__autoload("Connection","Cursor","Sql");
    $Sql=Sql::getInstance();
			
	$Connection=Connection::getInstance();
    $Cursor=Cursor::getInstance();
    $Connection->connect();
			
	include("$dirl"."Funciones.php");;
	$ND=getdate();
	if(!$MesI){$MesI=$ND[mon];}
	if(!$MesF){$MesF=$ND[mon];}
	if(!$Anio){$Anio=$ND[year];}
        if(!$DiaI){$DiaI="01";}
        if(!$DiaF){$DiaF=$ND[mday];}
    $CuentaDesctos="4175101005";
	$fec="$ND[year]-$ND[mon]-$ND[mday]";

/*	$cons="Select * from Contabilidad.Movimiento where month(Fecha)=$Mes and Comprobante='$Comprobante' and Estado='AC' and year(Fecha)=$Anio";
	$res=mysql_query($cons);
	if(mysql_num_rows($res)>1){echo "<strong><font color='#ff0000'><em>Datos ya transferidos para este periodo. Favor retire movimiento o anule documentos</em></strong></font>";$NoRegistro=1;}
	else{$NoRegistro=0;}*/

	$Comprobante='Venta de servicios';
	
	function CargarArchivo($AutoId,$Fecha,$Comprobante,$Numero,$Identificacion,$Detalle,$Cuenta,$Debe,$Haber,$CC,$DocSoporte,$Compania,$DetConcepto)
	{
            global $usuario;global $ND;global $NoRegistro;
            if(!$NoRegistro)
            {
	$FechaTmp=strtotime("$Fecha +30 day");
	$XS=getdate($FechaTmp);
	
	$FechaDoc= "$XS[year]-$XS[mon]-$XS[mday]";		
		$cons1="Insert into Contabilidad.Movimiento(AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,Compania,UsuarioCre,FechaCre,FechaDocumento,Anio)
		values($AutoId,'$Fecha','$Comprobante',$Numero,'$Identificacion','$Detalle','$Cuenta','$Debe','$Haber','$CC','$DocSoporte','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','$FechaDoc','$XS[year]')";
		$res1=ExQuery($cons1);
                $cons98="Update Facturacion.FacturasCredito set CompContable='$Comprobante',NoCompContable='$Numero' where NoFactura='$DocSoporte' and Compania='$Compania[0]'";
                $res98=ExQuery($cons98);
            }
	}
?>
<html>
<body background="/Imgs/Fondo.jpg" style="font-size:11px;">
<form name="FORMA">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;'>
<tr bgcolor='#e5e5e5' align='center'><td colspan="3">Periodo Inicial</td><td colspan="2">Periodo Final</td><td>Entidad</td><td>Facturas</td></tr>
<tr>
<td>
<select name="Anio">
<?
	for($i=2013;$i<=2200;$i++)
	{
		echo "<option value=$i>$i</option>";
	}
?>
</select>
</td>


<td>
<select name="MesI">
<?
	$cons="Select * from Central.Meses";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($fila[1]==$MesI){echo "<option selected value=$fila[1]>$fila[0]</option>";}
		else{echo "<option value=$fila[1]>$fila[0]</option>";}
		
	}
?>
</select>
</td>
<td><input type='text' name='DiaI' value='<? echo $DiaI?>' style='width:20px;'/></td>

<td>
<select name="MesF">
<?
	$cons="Select * from Central.Meses";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($fila[1]==$MesF){echo "<option selected value=$fila[1]>$fila[0]</option>";}
		else{echo "<option value=$fila[1]>$fila[0]</option>";}
		
	}
?>
</select>
</td>
<td><input type='text' name='DiaF' value='<? echo $DiaF?>' style='width:20px;' /></td>

<td>
    <select name='EntidadSel' style='width=400px;'><option></option>
        <?
            $cons="Select PrimApe,Identificacion from Central.Terceros where Tipo='Asegurador' and Compania='$Compania[0]' order by PrimApe";
            $res=ExQuery($cons);
            while($fila=ExFetch($res))
            {
                if($EntidadSel==$fila[1]){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
                else{echo "<option value='$fila[1]'>$fila[0]</option>";}
            }
        ?>
        
    </select>
    
</td>
<td>
    <input type='text' name='FacIni' style='width:50px;' value="<? echo $FacIni?>"/>
    <input type='text' name='FacFin' style='width:50px;' value="<? echo $FacFin?>"/>
          
    
</td>

</tr>
<td colspan=9><input type="Submit" name="Ver" value="Previsualizar" style="width:100px;"/></td>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<?	
	if($Ver || $Subir)
	{
            $NoSubir=0;
            if($EntidadSel){$CondEntidad=" and Entidad='$EntidadSel' ";}
            if($FacIni && $FacFin){$CondFacturas=" and NoFactura>='$FacIni' and NoFactura<='$FacFin' ";}
            if($MesI<10){$MesI="0".$MesI;}
            if($MesF<10){$MesF="0".$MesF;}
	$cons="Select fechacrea,NoFactura,Total,Entidad,Copago,descuento,FechaIni,FechaFin,
	0,0,0,0,0,Ambito,Estado,0,0,Contrato,NoContrato,CompContable
	from Facturacion.FacturasCredito where date_part('year',FechaCrea)>=$Anio and date_part('mon',FechaCrea)>=$MesI 
	and date_part('day',FechaCrea)>=$DiaI and date_part('year',FechaCrea)<=$Anio and date_part('mon',FechaCrea)<=$MesF and
	date_part('day',FechaCrea)<=$DiaF  $CondEntidad $CondFacturas
	Order By NoFactura";
	//echo $cons;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		//Primero hago el total... es decir el debito!!!.
		$FechaIni=$fila[6];
		$FechaIni2=$fila[6];
		$FechaFin=$fila[7];
		$FechaIng=$fila[8];
		$VrCopago=$fila[4];
		$VrDescto1=$fila[5];
		$NoFac=$fila[1];
		$Estado=$fila[14];

		$cons45="Select TipoAsegurador from Central.Terceros where Identificacion='$fila[3]' and Compania='$Compania[0]'";
		
		$res45=ExQuery($cons45);
		$fila45=ExFetch($res45);
		$Regimen=$fila45[0];

		$cons85="Select CuentaCont,CompFacturacion,NomRespPago,CuentaDeposito from ContratacionSalud.Contratos where Entidad='$fila[3]' and Contrato='$fila[17]' and numero='$fila[18]' and Compania='$Compania[0]'";
		$res85=ExQuery($cons85);
		$fila85=ExFetch($res85);
		$CuentaDeb=$fila85[0];$CuentaCopagos=$fila85[3];
		$Ambito=$fila[13];
                if(!$fila[19]){$EstadoConta="Pendiente";}else{$EstadoConta="Contabilizado";}

		if($Regimen=="Particular")
		{
			$cons44="Select Cedula,PrimApe,SegApe,PrimNom,SegNom from Facturacion.Liquidacion,Central.Terceros 
			where Terceros.Identificacion=Liquidacion.Cedula 
			and Terceros.Compania='$Compania[0]'
			and NoFactura='$NoFac' and Liquidacion.Compania='$Compania[0]'";
			
			$res44=ExQuery($cons44);
			$fila44=ExFetch($res44);
			$Nit=$fila44[0];
			$Nombre="$fila44[1] $fila44[2] $fila44[3] $fila44[4]";
		}
		else
		{
			$Nit=$fila[3];
			$Nombre=strtoupper(substr($fila85[2],0,100));
		}
		

		$i++;
		$Anio=substr($fila[0],0,4);
		$Mes=substr($fila[0],5,2);
		$Dia=substr($fila[0],8,2);
		$FecFac="$Anio-$Mes-$Dia";
		$CentroCostos="000"; // Porque se afecta la cuenta 13!
		
		$VrFactura=round($fila[2]);//+$VrCopago;
		
		if($Estado=="AN"){$VrFactura="0";$Nombre=$Nombre . "(Anulada)";}
		$Consec=substr("000",1,3-strlen($i)) . $i;
                $MatrizDatos[$i]=array ($NoFac,$Nombre,$Ambito,'Cliente',$CuentaDeb,$VrFactura,0,$EstadoConta,$Regimen);
                if($Subir)
                {
                    CargarArchivo($Consec,$FecFac,$Comprobante,$NoFac,$Nit,"$Nombre",$CuentaDeb,$VrFactura,0,$CentroCostos,$NoFac,$Compania,$Nombre);
                }
		if($Estado=="AC"){

		$cons2="Select Grupo,sum(VrTotal), sum(Cantidad),Nombre from Facturacion.DetalleFactura 
		where NoFactura='$fila[1]' Group By Grupo,Nombre Order By Grupo Desc";
		$res2=ExQuery($cons2);
		while($fila2=ExFetch($res2))
		{
                    
                        $cons98="Select Grupo from contratacionsalud.gruposservicio where Codigo='$fila2[0]'";
                        $res98=ExQuery($cons98);
                        $fila98=ExFetch($res98);
                        $Grupo=$fila98[0];
                    
			$cons3="Select CuentaConta from ContratacionSalud.CuentaxGrupos
			where Codigo='$fila2[0]' and TipoAseg='$Regimen' and Compania='$Compania[0]'";
			$res3=ExQuery($cons3);
			$fila3=ExFetch($res3);
			$Cuenta=$fila3[0];
			if(!$Cuenta)
			{
				$cons44="Select CuentaConta from ContratacionSalud.CuentaxGrupos where Grupo='MEDICAMENTOS' and Compania='$Compania[0]' and TipoAseg='$Regimen'";
				$res44=ExQuery($cons44);
				$fila44=ExFetch($res44);
				$Cuenta=$fila44[0];
			}
			//if(!$Cuenta){echo "Error en Factura No. $NoFac<br>";}
			$CentroCostos="000";
			$Valor=$fila2[1];

			if($fila2[1]>0){
			$i++;
			$Consec=substr("000",1,3-strlen($i)) . $i;
                        if($Subir)
                        {
                            CargarArchivo($Consec,$FecFac,$Comprobante,$NoFac,$Nit,"$Nombre",$Cuenta,0,$Valor,$CentroCostos,$NoFac,$Compania,$Nombre);
                        }
                        $MatrizDatos[$i]=array($NoFac,$Nombre,$Ambito,$Grupo,$Cuenta,0,$Valor,$EstadoConta,$Regimen);
                        $ValCredito=$ValCredito+round($Valor);
			}
		}
		
		
		if($VrCopago>0)
		{
			$CentroCostos="000";
			$i++;$VrFactura=$VrFactura+$VrCopago;
                        $MatrizDatos[$i]=array($NoFac,$Nombre,$Ambito,'COPAGOS/CUOTAS MODERADORAS',$CuentaCopagos,$VrCopago,0,$EstadoConta,$Regimen);

                        if($Subir)
                        {
                            CargarArchivo($Consec,$FecFac,$Comprobante,$NoFac,$Nit,"$Nombre",$CuentaCopagos,$VrCopago,0,$CentroCostos,$NoFac,$Compania,$Nombre);
                        }
		}

		if($VrDescto1>0)
		{
			$CentroCostos="000";
			$i++;$VrFactura=$VrFactura+$VrDescto1;
                        $MatrizDatos[$i]=array($NoFac,$Nombre,$Ambito,'DESCUENTOS',$CuentaDesctos,$VrDescto1,0,$EstadoConta,$Regimen);

                        if($Subir)
                        {
                            CargarArchivo($Consec,$FecFac,$Comprobante,$NoFac,$Nit,"$Nombre",$CuentaDesctos,$VrDescto1,0,$CentroCostos,$NoFac,$Compania,$Nombre);
                        }
		}
		
		
                if(round($ValCredito)!=round($VrFactura)){$FacSinCoinc[$NoFac]=array($NoFac,$VrFactura,$ValCredito);}
                $ValCredito=0;
	 }
	}      
        if(!$Subir)
        {
            echo "<table border=1 bordercolor='#e5e5e5' style='font : normal normal small-caps 10px Tahoma;'>";
            echo "<tr bgcolor='#e5e5e5' style='font-weight:bold'><td>Factura</td><td>Tercero</td><td>Regimen</td><td>Proceso</td><td>Servicio</td><td>Cuenta</td><td>Debitos</td><td>Creditos</td><td>Estado</td></tr>";
            if(count($MatrizDatos)>0){
            $FacturaCentinel=NULL;
            echo $ValorMatriz[4];
			foreach($MatrizDatos as $ValorMatriz)
            {
                if(!$ValorMatriz[4]){$FC="red";$ColFte="white";$NoSubir=1;}else{$FC="";$ColFte="black";}
                if($ValorMatriz[7]=="Contabilizado"){$ColFte="green";}else{$ColFte="black";}
				
				if($ValorMatriz[7]!="Contabilizado"){
				   $Cursor->consultExecute($Sql->setSentence("10000",$ValorMatriz[0]));
                   $Cursor->next($Cursor->get());
                   if(!$Cursor->getParameter("numradicacion")&&!$Cursor->getParameter("fecharasis"))
					  $Cursor->consultExecute($Sql->setSentence("10001",$ValorMatriz[0],$ValorMatriz[0]));
					  else
					       $Cursor->consultExecute($Sql->setSentence("10002",$ValorMatriz[0],$ValorMatriz[0]));
				   }//End if
				
				echo "<tr bgcolor='$FC' style='color:$ColFte'>";?>
               	<td style="cursor:hand" title="Ver Factura" onClick="open('IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $ValorMatriz[0]?>&Estado=<? echo "AC"?>&Impresion=<? echo $Impresion?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES,resizable=1')">
			<?	echo $ValorMatriz[0]."</td><td>".$ValorMatriz[1]."</td><td>".$ValorMatriz[8]."</td><td>".$ValorMatriz[2]."</td><td>".strtoupper($ValorMatriz[3])."</td><td>".$ValorMatriz[4]."</td><td align='right'>".number_format($ValorMatriz[5],0)."</td><td align='right'>".number_format($ValorMatriz[6],0)."</td><td>".$ValorMatriz[7]."</td></tr>";
                $TotDebitos=$TotDebitos+round($ValorMatriz[5]);
                $TotCreditos=$TotCreditos+round($ValorMatriz[6]);
            }
            echo "<tr><td colspan=6 align='right'>SUMAS</td><td>".number_format($TotDebitos,0)."</td><td>".number_format($TotCreditos,0)."</td>";}
        }
		if($Subir)
        {
            echo "<table border=1 bordercolor='#e5e5e5' style='font : normal normal small-caps 10px Tahoma;'>";
            echo "<tr bgcolor='#e5e5e5' style='font-weight:bold'><td>Factura</td><td>Tercero</td><td>Regimen</td><td>Proceso</td><td>Servicio</td><td>Cuenta</td><td>Debitos</td><td>Creditos</td><td>Estado</td></tr>";
            if(count($MatrizDatos)>0){
            $FacturaCentinel=NULL;
            
			foreach($MatrizDatos as $ValorMatriz)
            {
                if(!$ValorMatriz[4]){$FC="red";$ColFte="white";$NoSubir=1;}else{$FC="";$ColFte="black";}
                if($ValorMatriz[7]=="Contabilizado"){$ColFte="green";}else{$ColFte="black";}
				
				if($ValorMatriz[7]!="Contabilizado"){
				   $Cursor->consultExecute($Sql->setSentence("10000",$ValorMatriz[0]));
                   $Cursor->next($Cursor->get());
                   if(!$Cursor->getParameter("numradicacion")&&!$Cursor->getParameter("fecharasis")){
					  $Cursor->consultExecute($Sql->setSentence("10001",$ValorMatriz[0],$ValorMatriz[0]));
					  if($ValorMatriz[4]>=1305000000&&$ValorMatriz[4]<1307000000)$Cursor->consultExecute($Sql->setSentence("00046",$ValorMatriz[0],$ValorMatriz[4],$fec));	
					  }else{ 
					      $Cursor->consultExecute($Sql->setSentence("10002",$ValorMatriz[0],$ValorMatriz[0]));
					      if($ValorMatriz[4]>=1306000000&&$ValorMatriz[4]<1307000000)$Cursor->consultExecute($Sql->setSentence("00047",$ValorMatriz[0],$ValorMatriz[4],$Cursor->getParameter("fecharadic"),$fec));
						  }//End if	
				   }//End if
				
				//echo "<tr bgcolor='$FC' style='color:$ColFte'>";
				echo "<tr bgcolor='$FC' style='color:green'>";?>
               	<td style="cursor:hand" title="Ver Factura" onClick="open('IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $ValorMatriz[0]?>&Estado=<? echo "AC"?>&Impresion=<? echo $Impresion?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES,resizable=1')">
			<?	echo $ValorMatriz[0]."</td><td>".$ValorMatriz[1]."</td><td>".$ValorMatriz[8]."</td><td>".$ValorMatriz[2]."</td><td>".strtoupper($ValorMatriz[3])."</td><td>".$ValorMatriz[4]."</td><td align='right'>".number_format($ValorMatriz[5],0)."</td><td align='right'>".number_format($ValorMatriz[6],0)."</td><td>".$ValorMatriz[7]."</td></tr>";
                $TotDebitos=$TotDebitos+round($ValorMatriz[5]);
                $TotCreditos=$TotCreditos+round($ValorMatriz[6]);
            }
            echo "<tr><td colspan=6 align='right'>SUMAS</td><td>".number_format($TotDebitos,0)."</td><td>".number_format($TotCreditos,0)."</td>";}
        }
	}
        echo "</table>";

    if(count($FacSinCoinc)>0)
    {
        echo "<table border=1 bordercolor='#e5e5e5' style='font : normal normal small-caps 10px Tahoma;'>";
        echo "<tr bgcolor='#e5e5e5'><td colspan=3>REVISAR DEBITOS VS CREDITOS (".count($FacSinCoinc).")</td></tr>";
        echo "<tr bgcolor='#e5e5e5'><td>Factura</td><td>Debitos</td><td>Creditos</td></tr>";
        $NoSubir=1;
        foreach($FacSinCoinc as $ListarFacs)
        {
            echo "<tr><td>$ListarFacs[0]</td><td>$ListarFacs[1]</td><td>$ListarFacs[2]</td></tr>";
        }
        echo "</table>";
        
    }

        
        if($NoSubir==1)
    {
        echo "<em>No es posible contabilizar el movimiento mientras falten configuraciones!</em>";
    }
    else
    {
        if($Ver){
        echo "<input type='submit' name='Subir' value='Contabilizar'>";}
    }

$Cursor->freeResult();
$Connection->close();
$Var->release($Connection,$Cursor,$Sql,$Request,$Var);
?>
</form>
</body>
</html>
