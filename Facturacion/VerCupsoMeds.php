<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
	function Asignar(C,N,G,T,V,Gen,P)
	{
		//alert(V);
		if(document.FORMA.TipoNuevo.value=="Cup"){
			
			parent.document.FORMA.Codigo.value=C;
			parent.document.FORMA.Nombre.value=N;
			if(document.FORMA.OpcPaquete.value!=1){
				parent.document.FORMA.VrUnd.value=V;
				parent.document.FORMA.Grupo.value=G;
			}
			parent.document.FORMA.Tipo.value=T;
		}
		else{			
			parent.document.FORMA.Codigo.value=C;
			//parent.document.FORMA.Nombre.value=N+" "+P+" "+Gen;
			parent.document.FORMA.Nombre.value=N;
			parent.document.FORMA.VrUnd.value=V;
			parent.document.FORMA.Grupo.value=G;
			parent.document.FORMA.Tipo.value='Medicamentos';
			parent.document.FORMA.Generico.value=N;
			parent.document.FORMA.Presentacion.value=Gen;
			parent.document.FORMA.Forma.value=P;
		}
		parent.document.FORMA.submit();
		CerrarThis();
		
	}
</script>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<input type="hidden" name="TipoNuevo" value="<? echo $TipoNuevo?>">
<input type="hidden" name="OpcPaquete" value="<? echo $OpcPaquete?>">
<?	
if($Codigo!=''||$Nombre!='')
{
	if($TipoNuevo=="Cup")
	{
		$cons2="select planbeneficios,plantarifario from contratacionsalud.contratos where entidad='$Pagador' and contrato='$Contrato' and numero='$NoContrato' and compania='$Compania[0]'";	
		$res2=ExQuery($cons2);echo ExError();
		//echo $cons2;
		$fila2=ExFetch($res2);
		if($fila2[0]==''){$fila2[0]='-2';}
		if($IdPaquete)
		{
			$CodsPac="and codigo not in (select codigo
			from contratacionsalud.itemsxpaquete where compania='$Compania[0]' and idpaq=$IdPaquete
			and tipo='CUP')";	
		}
		if($Nombre==''){					
			$cons3="select cups.codigo,cups.nombre,cups.grupo,cups.tipo,cupsxplanes.valor,reqvobo,facturable,minimos,maximos
			from contratacionsalud.cupsxplanservic,contratacionsalud.cups,contratacionsalud.cupsxplanes 			
			where codigo=cupsxplanservic.cup and cupsxplanservic.cup=cupsxplanes.cup and codigo ilike '$Codigo%' and cupsxplanes.compania='$Compania[0]' and cups.compania='$Compania[0]'
			and cupsxplanservic.compania='$Compania[0]' and cupsxplanservic.autoid=$fila2[0] and cupsxplanes.autoid=$fila2[1] and cupsxplanservic.clase='CUPS'
			$CodsPac";
		}
		else{
			if($Codigo==''){		
				$cons3="select cups.codigo,cups.nombre,cups.grupo,cups.tipo,cupsxplanes.valor,reqvobo,facturable,minimos,maximos
				from contratacionsalud.cupsxplanservic,contratacionsalud.cups,contratacionsalud.cupsxplanes 			
				where codigo=cupsxplanservic.cup and cupsxplanservic.cup=cupsxplanes.cup and nombre ilike '%$Nombre%' and cupsxplanes.compania='$Compania[0]' and cups.compania='$Compania[0]'
				and cupsxplanservic.compania='$Compania[0]' and cupsxplanservic.autoid=$fila2[0] and cupsxplanes.autoid=$fila2[1] and cupsxplanservic.clase='CUPS'
				$CodsPac";							
			}
			else{
				$cons3="select cups.codigo,cups.nombre,cups.grupo,cups.tipo,cupsxplanes.valor,reqvobo,facturable,minimos,maximos
				from contratacionsalud.cupsxplanservic,contratacionsalud.cups,contratacionsalud.cupsxplanes 			
				where codigo=cupsxplanservic.cup and cupsxplanservic.cup=cupsxplanes.cup and codigo ilike '$Codigo%' and nombre ilike '%$Nombre%'
				and cupsxplanes.compania='$Compania[0]' and cups.compania='$Compania[0]'
				and cupsxplanservic.compania='$Compania[0]' and cupsxplanservic.autoid=$fila2[0] and cupsxplanes.autoid=$fila2[1] and cupsxplanservic.clase='CUPS'
				$CodsPac";
			}
		}			
		$res3=ExQuery($cons3);echo ExError();?>
		<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;' align="center"> 
    		<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td>Codigo</td><td>Nombre</td></tr>			
  <?		while($fila3=ExFetch($res3)){
  				if($fila3[2]!=''){?>
					<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" 
                onClick="Asignar('<? echo $fila3[0]?>','<? echo $fila3[1]?>','<? echo $fila3[2]?>','<? echo $fila3[3]?>','<? echo $fila3[4]?>','<? echo $fila3[3]?>','<? echo $fila3[3]?>')">
           <?	}
			   	else{?>
					<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" onClick="alert('Este CUP no tiene un grupo definido!!!')">
			<?	}?>
            		<td><? echo $fila3[0]?></td><td><? echo $fila3[1]?></td>
            	</tr>
<?			}?>
		</table><?
		
	}
	else
	{		
		$cons1 = "Select PlanServMeds,plantarifameds from ContratacionSalud.Contratos where Numero='$NoContrato' and Entidad='$Pagador' and contrato='$Contrato' 
		and Compania='$Compania[0]'";
		$res1 = ExQuery($cons1);
		//echo $cons1."<br>";
		if(ExNumRows($res1)>0)
		{
			$fila1 = ExFetch($res1);
			$cons2 = "Select Codigo from ContratacionSalud.MedsxPlanServic where MedsxPlanServic.AutoId='$fila1[0]' and MedsxPlanServic.Compania='$Compania[0]'";
			//echo $cons2;
			$res2 = ExQuery($cons2);			
			if(ExNumRows($res2)>0)
			{
				while($fila2 = ExFetch($res2))
				{	
					$MedicamentoS[$fila2[0]] = $fila2[0];
				}
			}			
		}		
		if($Nombre==''){
			$cons3 = "Select Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,valorventa 
			from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto
			where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null and Tarifario='$fila1[1]' and TarifasxProducto.Compania='$Compania[0]'
			and TarifasxProducto.autoid=CodProductos.autoid
			and CodProductos.Compania='$Compania[0]' and TiposdeProdxFormulacion.AlmacenPpal='$AlmacenPpal' and CodProductos.Anio=$ND[year] and Codigo1 like '$Codigo%'
			group by Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa
			order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";
			//echo $cons;
		}
		else{
			if($Codigo==''){
				$cons3 = "Select Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,valorventa 
				from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto
				where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null and Tarifario='$fila1[1]' and TarifasxProducto.Compania='$Compania[0]'
				and TarifasxProducto.autoid=CodProductos.autoid
				and CodProductos.Compania='$Compania[0]' and TiposdeProdxFormulacion.AlmacenPpal='$AlmacenPpal' and CodProductos.Anio=$ND[year] 
				and ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) ilike '%$Nombre%' 
				group by Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa
				order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";				
				//echo $cons3;
			}
			else{
				$cons3 = "Select Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,valorventa 
				from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto
				where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null and Tarifario='$fila1[1]' and TarifasxProducto.Compania='$Compania[0]'
				and TarifasxProducto.autoid=CodProductos.autoid
				and CodProductos.Compania='$Compania[0]' and TiposdeProdxFormulacion.AlmacenPpal='$AlmacenPpal' and CodProductos.Anio=$ND[year]  and Codigo1 like '$Codigo%'
				and ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) ilike '%$Nombre%' 
				group by Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa
				order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";	
			}
		}
		//echo $cons3;
		$res3=ExQuery($cons3);echo ExError();?>
		<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;' align="center"> 
    		<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td>Codigo</td><td>Nombre</td></tr>			
  <?		while($fila3=ExFetch($res3)){?>
				<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" onClick="Asignar('<? echo $fila3[0]?>','<? echo $fila3[1]?>','<? echo $fila3[2]?>','<? echo $fila3[3]?>','<? echo $fila3[6]?>','<? echo $fila3[4]?>','<? echo $fila3[5]?>')">
            		<td><? echo $fila3[0]?></td><td><? echo "$fila3[1]  $fila3[5] $fila3[4]"?></td>
            	</tr>
<?			}?>
		</table><?				
	}
	//echo $cons3;	
}?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>    
</body>
</html>
