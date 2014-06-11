<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	session_register($NoEmpleado);
	include("Funciones.php");
//	$NoEmpleado=-1;
	$ND=getdate();
	$Mes="$ND[mon]";
	$Dia="$ND[mday]";
	if($Mes<10){$Mes="0$ND[mon]";}
	if($Dia<10){$Dia="0$ND[mday]";}
	$fecha="$ND[year]-$Mes-$Dia";
//	echo $fecha;
?>
<html>
<head>
<style>
a{color:blue;text-decoration:none;}
a:hover{text-decoration:underline;}
</style>
</head>
<body background="/Imgs/Fondo.jpg">
<?
if($Buscar)
{	
	if($PrimApe){$PA="and primape ilike '$PrimApe%'";}
	if($SegApe){$SA="and segape ilike '$SegApe%'";}
	if($PrimNom){$PN="and primnom ilike '$PrimNom%'";}	
	if($SegNom){$SN="and segnom ilike '$SegNom%'";}
	if($Identificacion){$C="and terceros.identificacion ilike '$Identificacion'";}
    if($Cargo){$Ca="and cargo ilike '$Cargo%'";}
	if($Codigo){$Ca="and contratos.cargo ilike '$Codigo%'";}
	if($Estado){$Es="and contratos.estado='$Estado'";}
	if($Vinculacion){$Vi="and contratos.tipovinculacion='$Vinculacion'";}
	if($Es!="")
	{
		$cons="select contratos.identificacion, primape, segape, primnom, segnom,cargos.cargo,contratos.seccion,contratos.fecfin from nomina.contratos, central.terceros, nomina.cargos, nomina.tiposvinculacion where contratos.compania=
'$Compania[0]' and contratos.compania=terceros.compania and contratos.identificacion=terceros.identificacion and contratos.tipovinculacion=tiposvinculacion.codigo and contratos.cargo=cargos.codigo and cargos.vinculacion=contratos.tipovinculacion and contratos.fecinicio<='$fecha' $PA $SA $PN $SN $C $Es $Ca $Vi and (tipo='Empleado' or regimen='Empleado') order by primape, segape, primnom, segnom";
//		echo $cons;
	}
	else
	{
		$cons="select identificacion,primape,segape,primnom,segnom from central.terceros where (tipo='Empleado' or regimen='Empleado' )and compania='$Compania[0]' $PA $SA $PN $SN $C $Es order by primape,segape,primnom,segnom";
//		echo $cons;
	}
	$res=ExQuery($cons);
	if(ExNumRows($res)==0)
	{			
		echo "<center>";
		echo "<font size=5 color=blue><em>No existen registros coincidentes con el criterio de busqueda";
	}
//echo $Estado;
//	echo $NoEmpleado;
	while($fila=ExFetch($res))
	{
		$Empleados[$fila[0]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7]);
	}		
	if($Empleados)
	{?>
		<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center" width="100%">
		<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td colspan="2" align="left">EMPLEADOS ENCONTRADOS     <? echo ExNumRows($res);?></td>
    	</tr>
		<? 		
		$Raiz=$_SERVER['DOCUMENT_ROOT'];
		foreach($Empleados as $Auto)
		{
			$NoEmpleado++;
			$consCar="";
			//echo $Auto[0];
                $Identificacion=$Auto[0];
                $direcc=$Raiz."/Fotos/Empleados/".$Identificacion.".jpg";
                $foto="/Fotos/Empleados/".$Identificacion.".jpg";				
                ?>
               	<tr>
                <td style="width:60">
				<? 	
      				if(file_exists($direcc))
					{
						?><a href='/Nomina/ResultBusHojaVida.php?DatNameSID=<? echo $DatNameSID;?>&Identificacion=<? echo $Identificacion?>&Buscar=1&NoEmpleado=<? echo $NoEmpleado;?>&Estado=<? echo $Estado?>'>
						<img border="0" src='<? echo $foto?>' style='width:60; height:80'>
						</a>
					<?           
					}
                    else
					{?>
						<a href='/Nomina/ResultBusHojaVida.php?DatNameSID=<? echo $DatNameSID;?>&Identificacion=<? echo $Identificacion?>&Buscar=1&NoEmpleado=<? echo $NoEmpleado;?>&Estado=<? echo $Estado?>'>
						<img border="0" src="/Imgs/Logo.jpg" style="width:60; height:80">
						</a>
					<?
            		}
					
                    ?>
               </td>
               <td><a href="/Nomina/ResultBusHojaVida.php?DatNameSID=<? echo $DatNameSID;?>&Identificacion=<? echo $Identificacion?>&Buscar=1&NoEmpleado=<? echo $NoEmpleado;?>&Estado=<? echo $Estado?>"><? echo $Auto[1]." ".$Auto[2]." ".$Auto[3]." ".$Auto[4]; ?> </a><br>Documento No.: <? echo $Identificacion; if($Auto[5]!=""){echo "<br>Cargo: ".$Auto[5];} if($Auto[6]!=""){echo "<br>Seccion: ".$Auto[6];} if($Auto[7]!=""){echo "<br>Finalizacion Contrato: ".$Auto[7];}?></a></td>
               </tr>                    
				<?
				     		     
         }
		 ?>
		 </table>
		 <?
	}
	if($Buscar==1)
	{		
		?>
		<script language='JavaScript'>
//		alert("<? echo $NoEmpleado;?>");
		parent.parent.location.href='HojadeVida.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NoEmpleado=<? echo $NoEmpleado?>&Estado=<? echo $Estado?>';
		</script>
<?	}
}	
?>
</body>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
</html>