<?
session_start();
include("../Funciones.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body><div id="print">
<table align="center" class="ui-state-default">
<tr><td align="center" class="ui-state-default">
     <span style="cursor:pointer;" id="sendPrint"><img style="scroll: 10px center;" src="Imgs/b_print.png" title="Imprimir" alt="Imprimir" onClick="Text.print('print','sendPrint');"/></span>
    </td>
 </tr>
 <tr>
  <td width="697">
     <table>
       <tr align="center">
        <td width="132" rowspan="4">
	     <img src="../Imgs/Logo.jpg" width="88" height="78"></td>
        <td width="439" rowspan="4">
		  <?
			$cond="SELECT  nombre,codsgsss,nit,direccion,telefonos 
				   FROM central.compania where central.compania.nombre='$Compania'";
			$red=ExQuery($cond);
			while($filad = ExFetch($red)){		  
				  echo "<font size='4' >".$filad[0]."</font>";
				  echo "<br>CODIGO ".$filad[17]."";
				  echo "<br>".$filad[1]."</br>";
				  echo "".$filad[2]." - ";
				  echo " TELEFONOS ".$filad[3]."";	
				  }
		  ?>	
	    </td>
		  <? 
			$cons= "SELECT numeroinforme,fecharasis FROM  facturacion.informerespuesglosa WHERE numeroinforme='$NReport'";
			$res= ExQuery($cons); 
			$fi=ExFetch($res);
		  ?> 
        <td width="108" height="18" class="ui-state-default"><font size="3"><b>Documento Nº</b></font> </td>
      </tr>
      <tr>
        <td height="15" align="center"><font size="3"><b><? echo $fi[0] ?></b></font></td>
      </tr>
      <tr>
        <td height="16" align="center" class="ui-state-default">Fecha</td>
      </tr>
      <tr>
		<td align="center"><? $fechara= substr($fi[1],0,11); echo $fechara;?></td>
      </tr> 
     </table>
<br>
     <table class="ui-state-default  ui-corner-all">
      <tr>
        <td width="685" align="center"><font size="4"><b>FORMATO DE RESPUESTA DE GLOSA</b></font><br></br>
         <? 
		   $conex="SELECT  primape,contrato,nocontrato 
		   FROM  facturacion.facturascredito ,central.terceros 
		   where facturascredito.nofactura='$NoFac' and 
		   terceros.compania='$Compania' and facturascredito.entidad= terceros.identificacion";
		   $re=ExQuery($conex);
		   while($fila = ExFetch($re)){		
		   echo " <b>ENTIDAD:</b> ".$fila[0]."  <br>"; 
		   echo "<b>CONTRATO :</b>".$fila[1]."<br>";
		   echo "<b>N° CONTRATO:</b> ".$fila[2];
		   }
		?>   
        </td>
      </tr>
     </table>  
  </td>
 </tr>
 <tr>   
  <td height="41" colspan="9"> 
    <?
	$cons="SELECT encabezado,firma   FROM facturacion.informerespuesglosa where numeroinforme='$NReport' LIMIT 1";
	$res=ExQuery($cons);
	while($ro=ExFetch($res)){
	      echo $ro[0];?>
          <table class="ui-state-default  ui-corner-all" align="center">
          <?
		 $cons="SELECT nufactura FROM facturacion.informerespuesglosa where compania='$Compania' and numeroinforme='$NReport' ";
		 $res=ExQuery($cons);
		 while($fila=ExFetch($res)){
			   $factu=$fila[0];
			   //$cons="select vrtotal,vrglosatotal,pagaipsglosa,aceptaglosa,pagarips ,restante FROM facturacion.respuestaglosa  where compania='$Compania' and nufactura='$NoFac'";
			   $cons="SELECT total,SUM(facturacion.motivoglosa.vrglosa),SUM(facturacion.respuestaglosa.aceptaglosa)--,vrtotal,vrglosatotal,pagaipsglosa,aceptaglosa,pagarips ,restante 
                           FROM facturacion.respuestaglosa,facturacion.facturascredito 
						   LEFT JOIN facturacion.motivoglosa ON nofactura=facturacion.motivoglosa.nufactura
						   WHERE facturacion.facturascredito.nofactura=facturacion.respuestaglosa.nufactura and
						   facturacion.respuestaglosa.compania='$Compania' and facturacion.respuestaglosa.nufactura='$NoFac'
						   GROUP BY total";
			   $resx=ExQuery($cons);
			   while($row=Exfetch($resx)){
			         ?>
			         <tr>
					  <td colspan="6">&nbsp;&nbsp;&nbsp;&nbsp;</td>
					 </tr>
					 <tr class="ui-state-default" align="center">
					  <td width="56">Nº Factura</td>
					  <td width="56">Valor Factura</td>
					  <td width="114">Valor Total Glosa</td>
					  <td width="148">Valor Aceptado IPS</td>
					  <td width="123">Valor Objetado No aceptado IPS</td>
					  <td width="138">Valor a Pagar EPS</td>
					</tr>
				    <tr class="ui-widget-content">
					  <td><? echo $factu ?></td>
					  <td><? echo  number_format ($row[0],2)?></td>
					  <td><? echo  number_format ($row[1],2)?></td>
					  <td><? echo  number_format ($row[2],2)?></td>
					  <td><? echo  number_format ($row[1]-$row[2],2)?></td>
					  <td><? echo  number_format ($row[0]-$row[2],2)  ?></td>
				    </tr>   
					<tr>
					  <td colspan="6"> 
						<?   
						$cons1="select tipoglosa,claseglosa,observacionglosa,vrglosa,aceptaglosa,obseraceptado,codigo,detalle FROM facturacion.motivoglosa 
						 inner join facturacion.codrespuestaglosa on motivoglosa.codrespuestaglosa=codrespuestaglosa.codigo 
						 WHERE motivoglosa.compania='$Compania' and nufactura='$NoFac'";
						$resx1=ExQuery($cons1);
						?>
						<br><table class="ui-widget-content  ui-corner-all" align="right">
						<tr class="ui-state-default" align="center">
						<td>Nº</td>
						<td width="69">Codigo Glosa</td>
						<td width="73">Clase Glosa</td>
						<td width="102">Observaciones</td>
						<td width="57">Valor Glosa</td>
						<td width="101">Valor Aceptado</td>
						<td width="101">Respuesta Glosa</td>
						<td width="244">Observacion Aceptadado</td>
						</tr>
						<?
						while($row1=Exfetch($resx1)){   
						?>
						<tr>
						<td><? $cont++; echo $cont?></td>
						<td><? echo $row1[0]?></td>
						<td><? echo $row1[1]?></td>
						<td><? echo $row1[2]?></td>
						<td><? echo  number_format ($row1[3],2)?></td>
						<td><? echo  number_format ($row1[4],2)?></td>
						<td><? echo "cod. ".$row1[6]."<br>".$row1[7]?></td>
						<td><? echo $row1[5]?></td>
						</tr>
						<? } ?>
						</table>
						<p></p>					 
					  </td>
                    </tr>
                   <?}//End while 
			   }//End while ?> 
         </table> 
		 <div align="left">	  
        <?   echo $ro[1];}//End while  ?>
		 </div>
		 <table class="ui-widget-content  ui-corner-all" align="center">
		  <tr>
		    <td align="center" class="ui-state-default">
		    <? 
			$conexion="SELECT nombre,cedula,usuario FROM central.usuarios where usuario='$User' ";
			$respuesta= ExQuery($conexion);
			$fiz=ExFetch($respuesta); 
			$nombre=$fiz[0];	 
			$user=$fiz[2]; 
			$cons="select rm,cargo from salud.medicos where   usuario='$User'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$RM=$fila[0];
			$Cargo=$fila[1]; 
			echo "<br><b>".$nombre."</b>"; 
			?>
		    </td>
		  </tr>
		  <tr>
		    <td>
			<? 
			//if (file_exists($_SERVER['DOCUMENT_ROOT']."/Firmas/$fiz[1].GIF")){?>      	
			<!--<img src="/Firmas/<? //echo $fiz[1]?>.GIF" width="158" height="63">--><? //} ?> 	  
			</td>
		  </tr>
		  <tr>
			<td align="center"><? echo "<b>".$Cargo."</b>" ?> </td>
		  </tr>
		  <tr>	
			<td height="24" align="center"><b>INFORME CONCILIACION . <? echo $RM ?></b></td>
		  </tr>
		</table>
   </td>
  </tr>	
</table></div>
</body>
</html>
