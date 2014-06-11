<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	$ND=getdate();
	if(!$Anio){$Anio=$ND[year];}
	include("$dirl"."Funciones.php");
	if($Cancelar){
		?><script language="javascript">
		       location.href='Contratos.php?DatNameSID=<? echo $DatNameSID?>';
        	</script> <?
	}
	else{
		if($Guardar)
		{
			unset($VieneRestric);
			$VieneRestric="";
			if(!$ConsumContra){$ConsumContra="0";}
			if($PrimDia=="on"){$PrimDia="1";}else{$PrimDia="0";} 
			if($UltDia=="on"){$UltDia="1";}else{$UltDia="0";} 
			if($EnviarXML=="on"){$EnviarXML="1";}else{$EnviarXML="0";}
			if($RestrPago=="on"){$RestrPago="1";}else{$RestrPago="0";}
			if($Fechafin){$FecF1=",Fechafin"; $FecF2=",'$Fechafin'"; $FecF3=",Fechafin='$Fechafin'";} 
			if(!$Edit)
			{				
				$cons="Insert into Contratacionsalud.Contratos(Compania,Entidad,Contrato,Numero,Fechaini,Tipocontrato,Plantarifario,Plantarifameds,Planbeneficios,Planservmeds,Objeto,Observac,Vrpublicac,Vrotros,Monto,Formapago,Interventoria,Tipofactura,Estado,msjfactura,formato,primdia,ultdia,consumcontra,cuentacont,nomcuenta,compfacturacion,cdp,numpublicacion,nomresppago,copago,CuotaMod,comprobantecaja,enviarxml,cuentacaja,nomcuentacaja,cuentadeposito,nomcuentadepositos,restriccioncobro,ambitocontrato $FecF1,cuentarad,nomcuentarad,diascartera) 
				values ('$Compania[0]','$Entidad','$Contrato','$Numero','$Fechaini','$Tipocontrato','$Plantarifario','$Plantarifameds','$Planbeneficio','$Planservmeds','$Objeto','$Observac','$Vrpublicac','$Vrotros','$Monto','$Formapago','$Interventoria','$Tipofactura','$Estado','$MsjFactura','$Formato',$PrimDia,$UltDia,$ConsumContra,'$CuentaContable','$NomCuenta','$CompFac'
				,'$CDP','$NumPublicacion','$NomEntImp','$Copago','$CuotaMod','$CompCaja',$EnviarXML,'$CuentaCaja','$NomCuenta2','$CuentaDepositos'
				,'$NomCuenta3',$RestrPago,'$AmbitoContrato' $FecF2, '$CuentaRadicacion', '$NomCuentaRad',$diascartera)";	
				$Edit=1;
			}
			else{ 				
				if($RestrPago!=1){
					$cons="delete from contratacionsalud.restriccionescobro 
					where compania='$Compania[0]' and entidad='$Entidad' and Contrato='$Contrato' and nocontrato='$Numero'";
					$res=ExQuery($cons);
				}
				$cons="Update contratacionsalud.contratos set Entidad='$Entidad',Contrato='$Contrato',Numero='$Numero',Fechaini='$Fechaini ',
				Tipocontrato='$Tipocontrato',Plantarifario='$Plantarifario',Plantarifameds='$Plantarifameds',Planbeneficios='$Planbeneficio',msjfactura='$MsjFactura',formato='$Formato',
				Planservmeds='$Planservmeds',Objeto='$Objeto',Observac='$Observac',Vrpublicac='$Vrpublicac',ultdia=$UltDia,primdia=$PrimDia,
				Vrotros='$Vrotros',Monto='$Monto',Formapago='$Formapago',Interventoria='$Interventoria',Tipofactura='$Tipofactura',Estado='$Estado',consumcontra=$ConsumContra
				,cuentacont='$CuentaContable',nomcuenta='$NomCuenta',compfacturacion='$CompFac',cdp='$CDP',numpublicacion='$NumPublicacion',nomresppago='$NomEntImp'				
				,copago='$Copago',CuotaMod='$CuotaMod',comprobantecaja='$CompCaja',enviarxml=$EnviarXML,cuentacaja='$CuentaCaja',nomcuentacaja='$NomCuenta2'
				,cuentadeposito='$CuentaDepositos',nomcuentadepositos='$NomCuenta3',restriccioncobro=$RestrPago,ambitocontrato='$AmbitoContrato' $FecF3
				,cuentarad='$CuentaRadicacion',nomcuentarad='$NomCuentaRad', diascartera=$diascartera where Entidad='$EntidadAnt' and Contrato='$ContratoAnt' and Numero='$NumeroAnt' and Compania='$Compania[0]'";		
			}
	        
			//echo $cons;
			$res=ExQuery($cons);echo ExError();			
			?>
	        <script language="javascript">
		      //location.href='Contratos.php?DatNameSID=<? echo $DatNameSID?>';
        	</script>   
<?		}
	}
	if($Edit)
	{
		$cons="select nofactura from facturacion.facturascredito where compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato' and nocontrato='$Numero' and estado='AC'";
		$res=ExQuery($cons);
		$fila=ExFetch($res); if($fila[0]){$Bloquear=1;}
		$cons="Select * from ContratacionSalud.Contratos where Entidad='$Entidad' and Contrato='$Contrato' and Numero='$Numero' and Compania='$Compania[0]'";		
		//echo $cons;
		$res=ExQuery($cons);		
		$fila=ExFetchArray($res);		
		
		//echo $fila['plantarifario'];		
	}
	?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function salir(){		
		       location.href='Contratos.php?DatNameSID=<? echo $DatNameSID?>';
	}
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='140px';
		document.getElementById('Busquedas').style.left='42%';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
	function Validar()
	{		
			if(document.FORMA.Contrato.value==""){alert("Debe digitar el nombre del contrato");return false;}
			if(document.FORMA.Numero.value==""){alert("Debe digitar el numero del contrato");return false;}
			if(document.FORMA.NomEntImp.value==""){alert("Debe digitar el nombre de la entidad que aparecera en las impresiones");return false;}
			if(document.FORMA.Fechaini.value==""){alert("Debe digitar la fecha incial");return false;}
			if(document.FORMA.Fechafin.value!=""){
				if(document.FORMA.Fechaini.value>document.FORMA.Fechafin.value){
					alert("La Fecha Inicial debe ser menor o igual a la Fecha Final");return false;
				}
			}
			if(document.FORMA.Plantarifario.value==""){alert("Debe seleccionar un plan tarifario para los procedimientos");return false;}
			if(document.FORMA.Plantarifameds.value==""){alert("Debe seleccionar un plan tarifario para los medicamentos");return false;}
			if(document.FORMA.Objeto.value==""){alert("Favor digitar el objeto del contrato");return false;}
			//if(document.FORMA.Observac.value==""){alert("");return false;}
			if(document.FORMA.Monto.value==""){alert("Debe digitar el valor del total del contrato");return false;}			
			if(document.FORMA.NumPublicacion.value==""){alert("Debe digitar el numero de la publicacion");return false;}
			if(document.FORMA.Vrpublicac.value==""){alert("Debe digitar el valor de la publicacion");return false;}
			if(document.FORMA.Vrotros.value==""){alert("Debe digitar el valor de otros gastos");return false;}		
			if(document.FORMA.ConsumContra.value!=""){
				if(parseInt(document.FORMA.ConsumContra.value)>=parseInt(document.FORMA.Monto.value)){
					alert("El valor del consumo del contrato debe ser menor al valor total del contrato");return false;
				}
			}				
			if(document.FORMA.Formapago.value==""){alert("Debe digitar la forma de pago");return false;}
			if(document.FORMA.diascartera.value==""){alert("Debe digitar los diás de cartera");return false;}
			if(document.FORMA.Interventoria.value==""){alert("Debe digitar la interventoria");return false;}			
			if(document.FORMA.Formato.value==""){alert("Debe digitar el formato de la factura");return false;}	
			if(document.FORMA.CuentaCont.value==""){alert("Debe seleccionar una Cuenta Contable!!!");return false;}
	}
	function BuscarImgC(e,Entidad,Contrato,NoContrato)
	{	
		y = e.clientY; 
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="BuscarImgContra.php?DatNameSID=<? echo $DatNameSID?>&Entidad="+Entidad+"&Contrato="+Contrato+"&NoContrato="+NoContrato;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=y-20;
		document.getElementById('FrameOpener').style.left=373;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='450px';
		document.getElementById('FrameOpener').style.height='90px';		
	}	

</script>
<script language='javascript' src="/Funciones.js"></script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">

<table  BORDER=1  style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	<tr>
    	<td colspan="8" bgcolor="#e5e5e5" style="font-weight:bold" align="center">DATOS BASICOS</td>        
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="1">Entidad</td>
        <td colspan="3">
        <select name="Entidad" <? if($Bloquear){?>onFocus="document.FORMA.Contrato.focus();Ocultar();" onChange="document.FORMA.Entidad.value='<? echo $fila['entidad']?>'"<? }else{?> onFocus="Ocultar();"<? }?>>
        	<? 	
			$result=ExQuery("Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom)  from Central.Terceros where Tipo='Asegurador' and Compania='$Compania[0]' order by primape");
			while($row = ExFetch($result))
			{
				if($fila['entidad']==$row[0])
				{ ?>				
                	<option value="<? echo $row[0]?>" selected><? echo $row[1]?></option>
             <? }
			 	else
				{
				?>
                	<option value="<? echo $row[0]?>"><? echo $row[1]?></option>
              <? }
			  }?>
             </select>
        </td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="1">Ambito del Contrato</td>
        <td>
        <?	if(!$AmbitoContrato){$AmbitoContrato=$fila['ambitocontrato'];} ?>
        	<select name="AmbitoContrato">
                <option value="Recuperacion" <? if($AmbitoContrato=="Recuperacion"){?> selected <? }?>>Recuperacion</option>
                <option value="PyP" <? if($AmbitoContrato=="PyP"){?> selected <? }?>>P y P</option>
            </select>
        </td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Contrato</td>
        <td><input type="text" name="Contrato" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila['contrato']?>"  <? if($Bloquear){?> readonly<? }?> onFocus="Ocultar()">
        </td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Numero</td>
        <td><input type="text" name="Numero" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila['numero']?>" <? if($Bloquear){?> readonly<? }?> onFocus="Ocultar()"></td>                
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Modalidad</td>
        <td><select name="Tipocontrato" onFocus="Ocultar()">
        	<? 	
			$result=ExQuery("Select * from Contratacionsalud.Tiposcontrato order by tipo");
			while($row = ExFetchArray($result))
			{ if($fila['tipocontrato']==$row['tipo'])
				{?>				
                <option value="<? echo $row['tipo']?>" selected><? echo $row['tipo']?></option>
             <? }
			 else
			 	{?>	
             	<option value="<? echo $row['tipo']?>"><? echo $row['tipo']?></option>
             <? }
			 }?>
             </select>
        </td>
    </tr>    
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Nombre Ent. Imprimir</td>
        <td colspan="5">
        	<input type="text" name="NomEntImp" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" value="<? echo $fila['nomresppago']?>" 
            style="width:100%"
            onFocus="Ocultar()">
        </td>        
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Copago</td>
         <td><select name="Copago" onFocus="Ocultar()">
        <?	echo "<option value='0'>No</option>";
			if($fila['copago']==1){
				echo "<option value='1' selected>Si</option>";
			}else{
				echo "<option value='1'>Si</option>";
			}?>
        	</select>
        </td>  
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Cuota Moderadora</td>        
        <td><select name="CuotaMod" onFocus="Ocultar()">
        <?	echo "<option value='0'>No</option>";
			if($fila['cuotamod']==1){
				echo "<option value='1' selected>Si</option>";
			}else{
				echo "<option value='1'>Si</option>";
			}?>
        	</select>
        </td>
        <td>
        <?	if($VieneRestric==1){$fila['restriccioncobro']=1;unset($VieneRestric);}?>
        	Restriccion de Cobro 
            <input type="checkbox" onFocus="Ocultar()" name="RestrPago" <? if($fila['restriccioncobro']==1){?> checked <? }?>
            onClick="if(document.FORMA.RestrPago.checked==true){document.FORMA.ConfRestr.disabled=false;}else{document.FORMA.ConfRestr.disabled=true;}">
      	</td>
        <td align="center">
     <?	if($Edit==1){?>
            <input type="button" value="Configurar" name="ConfRestr" <? if($fila['restriccioncobro']!=1){?> disabled <? }?>
            onClick="
	            if(document.FORMA.RestrPago.checked==true)
                {
		            location.href='NewRestricPago.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>';    
    	       	}"
         	>
    <?	}?> &nbsp;
        </td> 
    </tr>   
    <tr>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold" align="center">Vigencia </td><td colspan="1">Desde</td>
        <td><input type="text" name="Fechaini" value="<? echo $fila['fechaini']?>" readonly <? if(!$Bloquear){?> onClick="popUpCalendar(this, FORMA.Fechaini, 'yyyy-mm-dd')" <? }?> onFocus="Ocultar()">
        </td>
        <td>Hasta</td>
        <td colspan="2">
        	<input type="text" name="Fechafin" value="<? echo $fila['fechafin']?>" readonly onClick="popUpCalendar(this, FORMA.Fechafin, 'yyyy-mm-dd')" onFocus="Ocultar()"></td>        
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold"></td><td  bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Procedimientos</td><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="3">Medicamentos</td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="1">Plan Tarifario</td><td colspan="2" align="center">
        <?
			$cns="Select * from Contratacionsalud.Planestarifas where Estado='AC' and Compania='$Compania[0]' and usuaprobado is not null and fechaaprobado is not null 
			order by nombreplan";
			//echo $fila['plantarifario'];
			//echo $cns;
			$result=ExQuery($cns);
			
		?>
        	<select name="Plantarifario" onFocus="Ocultar()">
        	<? 	
			
			while($row = ExFetchArray($result))
			{
				if($fila['plantarifario']==$row['autoid'])
				{?>				
                	<option value="<? echo $row['autoid']?>" selected><? echo $row['nombreplan']?></option>
             <? }
			 	else
			 	{?>
             		<option value="<? echo $row['autoid']?>"><? echo $row['nombreplan']?></option>
                <? } 
			}?>
             </select>
        </td>
        <td colspan="3" align="center">
        	<select name="Plantarifameds" onFocus="Ocultar()">
        	<? 	

			$result=ExQuery("Select Tarifario from Consumo.TarifariosVenta,Consumo.AlmacenesPpales 
			where TarifariosVenta.AlmacenPpal=AlmacenesPpales.AlmacenPpal
			and AlmacenesPpales.SSFarmaceutico=1 and
			TarifariosVenta.Compania='$Compania[0]' and AlmacenesPpales.Compania='$Compania[0]' and usuaprobado is not null and fechaaprobado is not null order by Tarifario");
			while($row = ExFetchArray($result))
			{
				if($fila['plantarifameds']==$row['tarifario'])
				{?>				
                	<option value="<? echo $row['tarifario']?>" selected><? echo $row['tarifario']?></option>
             <? }
			 	else
				{?>	
                	<option value="<? echo $row['tarifario']?>"><? echo $row['tarifario']?></option>
                <? }
			}?>                
             </select>
        </td>
    </tr>    
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="1">Plan Servicios</td><td colspan="2" align="center">
        	<select name="Planbeneficio" onFocus="Ocultar()">
        	<? 	
			$result=ExQuery("Select * from Contratacionsalud.Planeservicios where Clase='CUPS' and Compania='$Compania[0]' order by nombreplan");
			while($row = ExFetchArray($result))
			{
				if($fila[8]==$row['autoid'])
				{?>				
                	<option value="<? echo $row['autoid']?>" selected><? echo $row['nombreplan']?></option>
             <? }else{			 	
				?>
                	<option value="<? echo $row['autoid']?>"><? echo $row['nombreplan']?></option>
              <? }	
			 } ?>
             </select>
        </td>
        <td colspan="3" align="center">
        	<select name="Planservmeds" onFocus="Ocultar()">
        	<? 	
			$result=ExQuery("Select * from Contratacionsalud.Planeservicios where Clase='Medicamentos' and Compania='$Compania[0]' order by nombreplan");
			while($row = ExFetchArray($result))
			{
				if($fila['planservmeds']==$row['autoid'])
				{?>				
                	<option value="<? echo $row['autoid']?>" selected><? echo $row['nombreplan']?></option>
             <? }
			 	else
				{?>	
                	<option value="<? echo $row['autoid']?>"><? echo $row['nombreplan']?></option>
             <? }
			 } ?>
             </select>
        </td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="1">Objeto</td>
        <td colspan="2"><textarea name="Objeto" cols="40" rows="6" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" onFocus="Ocultar()"><? echo $fila['objeto'];?></textarea></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="1">Observaciones</td>
        <td colspan="2"><textarea name="Observac" cols="40" rows="6" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" onFocus="Ocultar()"><? echo $fila['observac'];?></textarea></td>
    </tr>
    <tr>
    	<td colspan="8" bgcolor="#e5e5e5" style="font-weight:bold" align="center">GARANTIAS Y LEGALIZACION</td>        
    </tr>    
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" >No. Publicacion</td>
        <td><input type="text" name="NumPublicacion" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila['numpublicacion']?>" onFocus="Ocultar()"></td>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" >Valor Publicacion</td>
        <td ><input type="text" name="Vrpublicac" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" value="<? echo $fila['vrpublicac']?>" onFocus="Ocultar()"></td>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="1">Otros Gastos</td>
        <td><input type="text" name="Vrotros" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" value="<? echo $fila['vrotros']?>" onFocus="Ocultar()"></td>        
    </tr>
    <tr> 
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="1">Forma de Pago y Soportes</td>
        <td><input type="text" name="Formapago" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila['formapago']?>" onFocus="Ocultar()"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="1">Interventoria</td>
        <td><input type="text" name="Interventoria" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila['interventoria']?>" onFocus="Ocultar()"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="1">Tipo de Factura</td>
        <td><select name="Tipofactura" onFocus="Ocultar()">
        	<? $result=ExQuery("Select * from Contratacionsalud.Tiposfactura order by tipo");
			while($row = ExFetchArray($result))
			{
				if($fila['tipofactura']==$row['tipo'])
				{?>				
	                <option value="<? echo $row['tipo']?>" selected><? echo $row['tipo']?></option>
             <? }
			 	else{?>	
                	<option value="<? echo $row['tipo']?>"><? echo $row['tipo']?></option>
             <? } 
			 }?>
             </select>
        </td>
   </tr>   
   <tr> 
   		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Cobro Primer Dia</td>
        <td><input type="checkbox" name="PrimDia" <? if($fila['primdia']==1){?> checked <? }?> onFocus="Ocultar()"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Cobro Ultimo Dia</td>        
        <td><input type="checkbox" name="UltDia" <? if($fila['ultdia']==1){?> checked <? }?> onFocus="Ocultar()"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="1">CDP</td>
        <td><input type="text" name="CDP" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila['cdp']?>" onFocus="Ocultar()"></td>
   	</tr> 
    <tr align="center">
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="1">Estado</td>           
    	<td> 
        	<select name="Estado" onFocus="Ocultar()"> 
        	<? if($fila['estado']=='AN')
				{?>
				<option value="AC"> Activo</option>
				<option value="AN" selected> Inactivo</option>            
				<? }else { ?>            
				<option value="AC" selected> Activo</option>
				<option value="AN"> Inactivo</option>            
            <? } ?>            
        	</select>
     	</td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="1">Consumo del Contrato</td>       
        <td><input type="text" name="ConsumContra" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" value="<? echo $fila['consumcontra']?>" <? if($Bloquear){?> readonly<? }?> onFocus="Ocultar()"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="1">Valor Total Contrato</td>
        <td align="left"><input type="text" name="Monto" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" value="<? echo $fila['monto']?>" <? if($Bloquear){?><? }?> onFocus="Ocultar()"></td> 
    </tr>   
    <tr>    
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="1">Mensaje</td>     
        <td colspan="3"><textarea name="MsjFactura" cols="41" rows="4" onFocus="Ocultar()"><? echo $fila['msjfactura']?></textarea></td>  
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="1">Formato</td >
  	<?	if(!$fila['formato']){$fila['formato']="Factura.php";}?> 
        <td><input type="text" name="Formato" value="<? echo $fila['formato']?>" onFocus="Ocultar()"></td>            
    </tr>
    <tr>
    	<td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Cuenta Contable</td>
  	<? 	if(!$CuentaContable){$CuentaContable=$fila['cuentacont'];}
		if($fila['cuentacont']){$CuentaCont=$fila['cuentacont'];}
		if(!$NomCuenta){$NomCuenta=$fila['nomcuenta'];}?>
        <td colspan="3" align="left" bgcolor="#e5e5e5" style="font-weight:bold">
        	<input type="text" name="CuentaContable" value="<? echo $CuentaContable?>"
			onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaContable&Objeto1=NomCuenta&Objeto2=CuentaCont&SigObjeto=CompFac&Cuenta='+this.value+'&Anio=<? echo $Anio?>';" 
			
            onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaContable&Objeto1=NomCuenta&Objeto2=CuentaCont&SigObjeto=CompFac&Cuenta='+this.value+'&Anio=<? echo $Anio?>';ValCuenta.value=0" 
            
            onKeyDown="xNumero(this)" onBlur="campoNumero(this)"
            
             onChange="document.FORMA.CuentaCont.value='';document.FORMA.NomCuenta.value=''" size="10"/>
       	
        	<input type="text" name="NomCuenta" value="<? echo $NomCuenta?>" style="border:thin" onFocus="Ocultar();" readonly style="width:400"/>
      	</td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Comprobante Facturacion</td>        
        <td>
        <?	if(!$CompFac){$CompFac=$fila['compfacturacion'];}
			$consCFac="select comprobante from contabilidad.comprobantes where compania='$Compania[0]' and UPPER(tipocomprobant)='FACTURAS'";
			$resCF=ExQuery($consCFac);?>
        	<select name="CompFac" onFocus="Ocultar()">
            <?	while($filaCFac=ExFetch($resCF)){
					if($filaCFac[0]==$CompFac){?>
						<option value='<? echo $filaCFac[0]?>' selected><? echo $filaCFac[0]?></option>                        
				<?	}
					else{?>
						<option value='<? echo $filaCFac[0]?>'><? echo $filaCFac[0]?></option>                        
				<?	}
				}?>
            </select>
        </td>
    </tr>
    <tr>
    	<td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Cuenta Caja</td>
        	<? 	if(!$CuentaCaja){$CuentaCaja=$fila['cuentacaja'];}
		if($fila['cuentacaja']){$CuentaCaja=$fila['cuentacaja'];}
		if(!$NomCuenta2){$NomCuenta2=$fila['nomcuentacaja'];}?>
        <td colspan="3" bgcolor="#e5e5e5" style="font-weight:bold">
        	<input type="text" name="CuentaCaja" value="<? echo $CuentaCaja?>"
			onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaCaja&Objeto1=NomCuenta2&Objeto2=CuentaCajaAux&SigObjeto=CuentaDepositos&Cuenta='+this.value+'&Anio=<? echo $Anio?>';" 
			
            onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaCaja&Objeto1=NomCuenta2&Objeto2=CuentaCajaAux&SigObjeto=CuentaDepositos&Cuenta='+this.value+'&Anio=<? echo $Anio?>';ValCuenta.value=0" 
            
            onKeyDown="xNumero(this)" onBlur="campoNumero(this)"
            
            onChange="document.FORMA.CuentaCajaAux.value='';document.FORMA.NomCuenta2.value=''" size="10"/>
       	
        	<input type="text" name="NomCuenta2" value="<? echo $NomCuenta2?>" style="border:thin" onFocus="Ocultar();" readonly style="width:400"/>
     	</td>
        <td style='font-weight:bold;' bgcolor="#e5e5e5" rowspan="2">Comprobante de Caja</td>
        <td align="center">
        <?	if(!$CompCaja){$CompCaja=$fila['comprobantecaja'];}
			$consCC="select comprobante from contabilidad.comprobantes where compania='$Compania[0]' and tipocomprobant='Ingreso'";
			$resCC=ExQuery($consCC);?>
        	<select name="CompCaja">
            <?	while($filaCC=ExFetch($resCC)){
					if($filaCC[0]==$CompCaja){?>
						<option value='<? echo $filaCC[0]?>' selected><? echo $filaCC[0]?></option>                        
				<?	}
					else{?>
						<option value='<? echo $filaCC[0]?>'><? echo $filaCC[0]?></option>                        
				<?	}
				}?>
            </select>
        </td>
    </tr>
    
     <tr>
    	<td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Cuenta de Depositos</td>
        	<? 	if(!$CuentaDepositos){$CuentaDepositos=$fila['cuentadeposito'];}
		if($fila['cuentadeposito']){$CuentaDepositosAux=$fila['cuentadeposito'];}
		if(!$NomCuenta3){$NomCuenta3=$fila['nomcuentadepositos'];}?>
        <td colspan="3" bgcolor="#e5e5e5" style="font-weight:bold">
        	<input type="text" name="CuentaDepositos" value="<? echo $CuentaDepositos?>"
			onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaDepositos&Objeto1=NomCuenta3&Objeto2=CuentaDepositosAux&SigObjeto=RutI&Cuenta='+this.value+'&Anio=<? echo $Anio?>';" 
			
            onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaDepositos&Objeto1=NomCuenta3&Objeto2=CuentaDepositosAux&SigObjeto=RutI&Cuenta='+this.value+'&Anio=<? echo $Anio?>';ValCuenta.value=0" 
            
            onKeyDown="xNumero(this)" onBlur="campoNumero(this)"
            
            onChange="document.FORMA.CuentaDepositosAux.value='';document.FORMA.NomCuenta3.value=''" size="10"/>
       	
        	<input type="text" name="NomCuenta3" value="<? echo $NomCuenta3?>" style="border:thin" onFocus="Ocultar();" readonly style="width:400"/>
     	</td>
        
        <td align="center" style="font-weight:bold">
        	Enviar XML <input type="checkbox" name="EnviarXML" <? if($fila['enviarxml']==1){?> checked <? }?> onFocus="Ocultar()">
        </td>        
    </tr>
<?	if($Edit){?>    
        <tr>
        	<input type="hidden" name="RutI" id="RutI" value="<? echo $fila['imgcontrato']?>" onFocus="Ocultar()">
            <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">
            	Imagen Contrato</td>        
            <td colspan="3">
            	<input type="text" readonly id="RutaImg" value="<? echo $fila['imgcontrato']?>" style="width:500" onFocus="Ocultar()">
            </td>
            <td>
                <input type="button" value="..." title="Cargar Documento"  onFocus="Ocultar()"
                onClick="BuscarImgC(event,'<? echo $fila['entidad']?>','<? echo $fila['contrato']?>','<? echo $fila['numero']?>')">
                <input type="button" value="Ver Contrato" onFocus="Ocultar()" onClick="if(document.FORMA.RutI.value){open(document.FORMA.RutI.value,'','');}">
            </td>
        </tr> 
<?	}?>  





        <tr>
    	<td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Cuenta Radicaci&oacute;n</td>
        	<? 	if(!$CuentaRadicacion){$CuentaRadicacion=$fila['cuentarad'];}
		if($fila['cuentarad']){$CuentaRadicacion=$fila['cuentarad'];}
		if(!$NomCuentaRad){$NomCuentaRad=$fila['nomcuentarad'];}?>
        <td colspan="3" bgcolor="#e5e5e5" style="font-weight:bold">
        	<input type="text" name="CuentaRadicacion" value="<? echo $CuentaRadicacion?>"
			onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaRadicacion&Objeto1=NomCuentaRad&Objeto2=CuentaCajaAux&SigObjeto=CuentaDepositos&Cuenta='+this.value+'&Anio=<? echo $Anio?>';" 
			
            onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaRadicacion&Objeto1=NomCuentaRad&Objeto2=CuentaCajaAux&SigObjeto=CuentaDepositos&Cuenta='+this.value+'&Anio=<? echo $Anio?>';ValCuenta.value=0" 
            
            onKeyDown="xNumero(this)" onBlur="campoNumero(this)"
            
            onChange="document.FORMA.CuentaCajaAux.value='';document.FORMA.NomCuentaRad.value=''" size="10"/>
       	
        	<input type="text" name="NomCuentaRad" value="<? echo $NomCuentaRad?>" style="border:thin" onFocus="Ocultar();" readonly style="width:400"/>
     	</td>
		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Numero de días cartera</td>
        <td><input type="text" name="diascartera" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila['diascartera']?>"  onFocus="Ocultar()"></td>  
        
        </tr>









       
    <tr>  
    	<td colspan="8" align="center"><input type="submit" value="Guardar" name="Guardar" onFocus="Ocultar()">          
    <?	if($Edit==1){?>
    		<input type="button" value="Polizas"
            onClick="location.href='NewPolizas.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>'" onFocus="Ocultar()">
    		<input type="button" value="Estancia"             
            onClick="location.href='ConfEstancia.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>'" onFocus="Ocultar()">
            <input type="button" value="P y P" 
            onClick="location.href='AgregarPyP.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>'" onFocus="Ocultar()">
             <input type="button" value="Agenda Interna" 
            onClick="location.href='AgendaInterna.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>'" 
            onFocus="Ocultar()">
          	<input type="button" value="Regresar" name="Cancelar" onClick="salir()" onFocus="Ocultar()"> 
  	<?	}else{?>
	        <input type="button" value="Cancelar" name="Cancelar" onClick="salir()" onFocus="Ocultar()"></td>  
  	<?	}?> 
    </tr>     
</table>
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="EntidadAnt" value="<? echo $Entidad?>"> 
<input type="hidden" name="ContratoAnt" value="<? echo $Contrato?>">
<input type="hidden" name="NumeroAnt" value="<? echo $Numero?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">  
<input type="hidden" name="CuentaCont" value="<? echo $CuentaCont?>"/>
<input type="hidden" name="CuentaCajaAux" value="<? echo $CuentaCajaAux?>"/>
<input type="hidden" name="CuentaDepositosAux" value="<? echo $CuentaDepositosAux?>"/>
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="" frameborder="0" height="400"></iframe>
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe> 
</body>
</html>
