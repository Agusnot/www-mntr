<?
	if($DatNameSID){session_name("$DatNameSID");}
	else{$Compania[0]='Clinica San Juan de Dios';}
	session_start();
	include("../Funciones.php");
	$ND=getdate();
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Consolidado Hist&oacute;rico de Consumo</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #000000;
}
body {
	margin-left: 0px;
	margin-right: 0px;
	background-image: url(../Imgs/Fondo.jpg);
	margin-top: 0px;
	margin-bottom: 0px;
}
select,input{background-color:transparent;
border-color:#000000;
border-style:solid;
border-width:thin;
color:#000000;
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:10px;
font-weight:bold;
}

-->
</style>
<script type="text/JavaScript">
<!--
function Valida()
{
document.FORMA.FechaIni.value=document.FORMA.fi.value;
//alert('Fecha1: '+document.FORMA.FechaIni.value+'');
location.href='?DatNameSID=<? echo $DatNameSID?>&FechaIni='+document.FORMA.FechaIni.value+'&almacen='+document.FORMA.almacen.value+'';
}
//-->
</script>
</head>
<?php
		$FechaIni=$_GET['FechaIni'];
		//echo $FechaIni;
		if($FechaIni==NULL){
			if($ND[mon]<10){$C1="0";}else{$C1="";}
			if($ND[mday]<10){$C2="0";}else{$C2="";}			
			$FechaIni="$ND[year]";
		}
		$aaaa=$FechaIni;
		
		$almacenn=$_GET['almacen'];
		$qa="and almacenppal='$almacen'";
		if($almacen==NULL){$qa="and almacenppal='FARMACIA'";}
		
?>
<body>
<form id="FORMA" name="FORMA" method="post" action="">
  <div align="center">
    <table width="200" border="1" cellpadding="5" bordercolor="#EEEEEE" background="../Imgs/Fondo.jpg" bgcolor="#FFFFFF">
      <tr>
        <td colspan="16" nowrap="nowrap"><table width="200" align="left" cellpadding="5">
          <tr>
            <td nowrap="nowrap"><div align="left"><strong>CONSOLIDADO HIST&Oacute;RICO DE CONSUMO  </strong></div></td>
            </tr>
          <tr>
            <td nowrap="nowrap">&nbsp;</td>
            </tr>
          <tr>
            <td nowrap="nowrap" bgcolor="#DDDDDD"><div align="left"><strong>ALMAC&Eacute;N:
              <select name="almacen" id="almacen">
                  <?php
				  $conspp="select almacenppal from consumo.almacenesppales order by almacenppal asc";
				  //echo "$consxx </br></br>";
				  $respp=ExQuery($conspp);
				  while($filapp=ExFetch($respp)){
				  	echo'<option value="'.$filapp[0].'"';
					if($almacen==$filapp[0]){
						echo' selected="selected"';
					}
					echo'>'.$filapp[0].'</option>';				  	
				  }
				  ?>
              </select>
            </strong></div></td>
          </tr>
          <tr>
            <td height="10" nowrap="nowrap" bgcolor="#FFFFFF"><input name="FechaIni" type="hidden" id="FechaIni" /></td>
          </tr>
          <tr>
            <td nowrap="nowrap" bgcolor="#DDDDDD"><div align="center"><strong>A&Ntilde;O DE CONSULTA: </strong>
              <select name="fi" id="fi">
                <?php
				  $consaa="select anio from consumo.grupos order by anio asc";
				  $resaa=ExQuery($consaa);
				  $provaa=0;
				  while($filaaa=ExFetch($resaa)){
				  if($filaaa[0]!=$provaa){
				  	echo'<option value="'.$filaaa[0].'"';
					if($aaaa==$filaaa[0]){
						echo' selected="selected"';
					}
					echo'>'.$filaaa[0].'</option>';
					$provaa=$filaaa[0];
				  }
				 }
				  ?>
              </select>
              <input name="Enviar" type="button" id="Enviar" onclick="Valida()" value="CONSULTAR" />
            </div></td>
            </tr>
			
        </table>         </td>
      </tr>
<?php
$cons="select grupo from consumo.grupos where anio = '$aaaa' $qa order by grupo";
//echo "$cons </br></br>";
$res=ExQuery($cons);
while($fila=ExFetch($res)){
	echo'<tr>
        <td colspan="16" nowrap="nowrap" bgcolor="#FFFFFF">&nbsp;</td>
      </tr><tr>
        <td colspan="16" nowrap="nowrap" bgcolor="#7B99BB"><div align="left"><strong>';$grupo= strtoupper("$fila[0]");echo"$grupo".'</strong></div></td>
      </tr>
      <tr>
        <td nowrap="nowrap" bgcolor="#EEEEEE">#</td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>C&Oacute;DIGO </strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>PRODUCTO</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>UNIDAD</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>ENE</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>FEB</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>MAR</strong></td>
        <td nowrap="nowrap" bgcolor="#EEEEEE"><strong>ABR</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>MAY</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>JUN</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>JUL</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>AGO</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>SEP</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>OCT</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>NOV</strong></td>
		<td nowrap="nowrap" bgcolor="#EEEEEE"><strong>DIC</strong></td>
      </tr>';
	$ln=1;
	$cons2="select autoid,codigo1,nombreprod1,presentacion,unidadmedida from consumo.codproductos where grupo='$fila[0]' and anio='$aaaa' $qa order by nombreprod1";	
	//echo "$cons2 </br></br>";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2)){
		$cons3="select fecha,cantidad,tipocomprobante from consumo.movimiento where anio='$aaaa' $qa and grupo='$fila[0]' and autoid='$fila2[0]'";
		$res3=ExQuery($cons3);
		$tot01=0;$tot02=0;$tot03=0;$tot04=0;$tot05=0;$tot06=0;$tot07=0;$tot08=0;$tot09=0;$tot10=0;$tot11=0;$tot12=0;
		while($fila3=ExFetch($res3)){
			
			
			$aaaa1=substr("$fila3[0]", -10,4);
			$mm1=substr("$fila3[0]", -5,2);
			if($mm1<10){$mm1=str_replace("0","","$mm1");}					
			$dd1=substr("$fila3[0]", -2);
			if($dd1<10){$dd1=str_replace("0","","$dd1");}
			//echo "$aaaa1,$mm1,$dd1";
			
			switch($mm1){
				case"01":
					if($fila3[2]=="Salidas"){$tot01=$tot01+$fila3[1];}
					if($fila3[2]=="Devoluciones"){$tot01=$tot01-$fila3[1];}
				break;
				case"02":
					if($fila3[2]=="Salidas"){$tot02=$tot02+$fila3[1];}
					if($fila3[2]=="Devoluciones"){$tot02=$tot02-$fila3[1];}
				break;
				case"03":
					if($fila3[2]=="Salidas"){$tot03=$tot03+$fila3[1];}
					if($fila3[2]=="Devoluciones"){$tot03=$tot03-$fila3[1];}
				break;
				case"04":
					if($fila3[2]=="Salidas"){$tot04=$tot04+$fila3[1];}
					if($fila3[2]=="Devoluciones"){$tot04=$tot04-$fila3[1];}
				break;
				case"05":
					if($fila3[2]=="Salidas"){$tot05=$tot05+$fila3[1];}
					if($fila3[2]=="Devoluciones"){$tot05=$tot05-$fila3[1];}
				break;
				case"06":
					if($fila3[2]=="Salidas"){$tot06=$tot06+$fila3[1];}
					if($fila3[2]=="Devoluciones"){$tot06=$tot06-$fila3[1];}
				break;
				case"07":
					if($fila3[2]=="Salidas"){$tot07=$tot07+$fila3[1];}
					if($fila3[2]=="Devoluciones"){$tot07=$tot07-$fila3[1];}
				break;
				case"08":
					if($fila3[2]=="Salidas"){$tot08=$tot08+$fila3[1];}
					if($fila3[2]=="Devoluciones"){$tot08=$tot08-$fila3[1];}
				break;
				case"09":
					if($fila3[2]=="Salidas"){$tot09=$tot09+$fila3[1];}
					if($fila3[2]=="Devoluciones"){$tot09=$tot09-$fila3[1];}
				break;
				case"10":
					if($fila3[2]=="Salidas"){$tot10=$tot10+$fila3[1];}
					if($fila3[2]=="Devoluciones"){$tot10=$tot10-$fila3[1];}
				break;
				case"11":
					if($fila3[2]=="Salidas"){$tot11=$tot11+$fila3[1];}
					if($fila3[2]=="Devoluciones"){$tot11=$tot11-$fila3[1];}
				break;
				case"12":
					if($fila3[2]=="Salidas"){$tot12=$tot12+$fila3[1];}
					if($fila3[2]=="Devoluciones"){$tot12=$tot12-$fila3[1];}
				break;
			}
		}
		
		echo'<tr>
        <td nowrap="nowrap" bgcolor="#EEEEEE">'.$ln.'</td>
        <td nowrap="nowrap" bgcolor="#EEEEEE">'.$fila2[1].'</td>
        <td width="300" bgcolor="#EEEEEE"><div align="left">'.$fila2[2].'</div></td>
		<td width="200" bgcolor="#EEEEEE">'.$fila2[4].'</strong></div></td>
        <td nowrap="nowrap" bgcolor="#DEE6EE">';if($tot01==0){$tot01="";}echo"$tot01";echo'</td>
        <td nowrap="nowrap" bgcolor="#DEE6EE">';if($tot02==0){$tot02="";}echo"$tot02";echo'</td>
        <td nowrap="nowrap" bgcolor="#DEE6EE">';if($tot03==0){$tot03="";}echo"$tot03";echo'</td>
        <td nowrap="nowrap" bgcolor="#DEE6EE">';if($tot04==0){$tot04="";}echo"$tot04";echo'</td>
		<td nowrap="nowrap" bgcolor="#DEE6EE">';if($tot05==0){$tot05="";}echo"$tot05";echo'</td>
		<td nowrap="nowrap" bgcolor="#DEE6EE">';if($tot06==0){$tot06="";}echo"$tot06";echo'</td>
		<td nowrap="nowrap" bgcolor="#DEE6EE">';if($tot07==0){$tot07="";}echo"$tot07";echo'</td>
		<td nowrap="nowrap" bgcolor="#DEE6EE">';if($tot08==0){$tot08="";}echo"$tot08";echo'</td>
		<td nowrap="nowrap" bgcolor="#DEE6EE">';if($tot09==0){$tot09="";}echo"$tot09";echo'</td>
		<td nowrap="nowrap" bgcolor="#DEE6EE">';if($tot10==0){$tot10="";}echo"$tot10";echo'</td>
		<td nowrap="nowrap" bgcolor="#DEE6EE">';if($tot11==0){$tot11="";}echo"$tot11";echo'</td>
		<td nowrap="nowrap" bgcolor="#DEE6EE">';if($tot12==0){$tot12="";}echo"$tot12";echo'</td>
      </tr>';
	  $ln++;
	 }
}
?>
    </table>
  </div>
</form>
</body>
</html>
