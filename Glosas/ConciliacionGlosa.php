<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>
<html>
<?	
if($Modifica){	
	$consm="Update facturacion.motivoglosa 
    Set valorconciliado=$ValorAceptado, observacionconciliado='$ArguAceptado'
	 where  tipoglosa='$TipoGlosa' and nufactura='$NoFac'";
	$resu=ExQuery($consm); 
	echo ExError($resu);		
	         }	
			 
if($Gtodo){
$tiempo = strftime("%Y-%m-%d",time());	
$consm="Update facturacion.respuestaglosa
 Set aceptaglosa=$TotalConci, usuarioconciliacion='$usuario[1]',fechaconciliacion='$tiempo',pagaipsglosa=$Objetado,pagarips='$ValorEPS'
		
		where nufactura='$NoFac'";
	   $resu=ExQuery($consm);	
	   echo ExError($resu);	
         }					 	 
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">

function Validar()
{
if(document.FORMA.VAceptado.value==""){	alert("Ingrese un valor de Glosa Aceptado");document.FORMA.ValorGlosa.focus();return false;}
if(document.FORMA.ELIMINA.button=true){ document.FORMA.ValorGlosa.disabled= true;}
}
</script>
<script language="javascript">
	function CerrarThis()
	{
		for (i=0;i<parent.document.FORMA.elements.length;i++){
			if(parent.document.FORMA.elements[i].type == "checkbox"){
				parent.document.FORMA.elements[i].disabled = false;
			} 
		}
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
		parent.location.reload();
	}
	
	function ChequearTodos(chkbox) 
	{ 
		for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
		{ 
			var elemento = document.forms[0].elements[i]; 
			if (elemento.type == "checkbox") 
			{ 
				elemento.checked = chkbox.checked 
			} 
		} 
	}
	function GuardarRad(){
		if(document.FORMA.FechaRad!=null){
			if(document.FORMA.FechaRad.value>document.FORMA.FechaAtc.value){
				alert("La fecha de Radicacion no puede ser mayor a la actual!!!");
			}
			else{
				document.FORMA.Guardar.value=1;
				document.FORMA.submit();
			}
		}
		else{
			document.FORMA.Guardar.value=1;
			document.FORMA.submit();
		}
	}	
</script>
</head>
<body background="/Imgs/Fondo.jpg">
        <input type="button" value=" X " onClick="parent.document.getElementById('Fac'+<? echo $NoFac?>).checked=false;CerrarThis()" style="position:absolute;top:1px;right:1px;" 
title="Cerrar esta ventana">
<form name="FORMA" method="post" onSubmit="return Validar()">
<? 
$cons="SELECT tipoglosa,claseglosa,vrglosa,aceptaglosa,observacionglosa,estadoconciliado ,estadorecepcion,valorrecepcion,valorconciliado,observacionconciliado
FROM facturacion.motivoglosa  
where Compania='$Compania[0]' and nufactura='$NoFac' and estadorecepcion!='Aceptada'";
$res=ExQuery($cons);  
if(ExNumRows($res)>0){    
?>

<table  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 	  
    <tr>    
    <td  align="center" bgcolor="#e5e5e5" style="font-weight:bold" colspan="10">
 <div align="left" style="font-weight:bold"> Factura Nº:   <? echo $NoFac;?></div>
    Respuesta De Glosa</td></tr>
    <tr align="center">
    <td width="17" bgcolor="#e5e5e5" style="font-weight:bold">Nº</td>
    	<td width="52" bgcolor="#e5e5e5" style="font-weight:bold">Codigo Glosa </td>
        <td width="74" bgcolor="#e5e5e5" style="font-weight:bold">Tipo Auditoria </td>      
        <td width="46" bgcolor="#e5e5e5" style="font-weight:bold">valor Glosa </td>
        <td width="74" bgcolor="#e5e5e5" style="font-weight:bold">valor Glosa Aceptada</td>
        <td width="119" bgcolor="#e5e5e5" style="font-weight:bold">Observacion del valor Aceptado</td>
     <td width="99" bgcolor="#e5e5e5" style="font-weight:bold">Estado Recepcion</td>
	  <td width="99" bgcolor="#e5e5e5" style="font-weight:bold">valor recepcion</td>
        <td width="105" bgcolor="#e5e5e5" style="font-weight:bold">Valor Conciliacion</td>
		<td width="197" bgcolor="#e5e5e5" style="font-weight:bold">Observacion de conciliacion</td>
    </tr>    
    <? 
	 while($fila=ExFetch($res))
		{ 		
		$i++; 
	 ?>       
<tr align="center">    
<td> <? $cont++; echo $cont ?></td>
<td><? echo $fila[0]?> </td>
<td><? echo $fila[1]?> </td>            
<td><? echo number_format ($fila[2],2)?> </td>
<td><? echo number_format ($fila[3],2)?></td>
<td><? echo $fila[4]?> </td>
<td><? echo $fila[6]?> </td>
<td><? echo number_format ($fila[7],2)?> </td>
      <td>
     <input name="ValorAceptado_<? echo $fila[0]?>" type="text" size="7" value="<? echo $fila[8]?>"  onKeyDown="xNumero(this)"  onKeyUp="xNumero(this)"></td>
	 <td>
		  <textarea name="ArguAceptado_<? echo $fila[0]?>" cols="30" rows="2"><?  echo $fila[9]?></textarea>
	  </td>
	  <td width="25">
	   <button onClick="        
      if((document.FORMA.ValorAceptado_<? echo $fila[0]?>.value!='') & 
	  (document.FORMA.ValorAceptado_<? echo $fila[0]?>.value<=<? echo $fila[7]?>) &
	  (document.FORMA.ArguAceptado_<? echo $fila[0]?>.value!='')
	  )
      {   	if(confirm('Desea registra el valor?'))
            {	
  location.href='ConciliacionGlosa.php?DatNameSID=<? echo $DatNameSID?>&Modifica=1&TipoGlosa=<? echo $fila[0]?>&NoFac=<? echo $NoFac?>&ValorAceptado='+document.FORMA.ValorAceptado_<? echo $fila[0]?>.value+'&ArguAceptado='+document.FORMA.ArguAceptado_<? echo $fila[0]?>.value;
           	}
    	}                                                        
        else{
        	alert('Atencion: El campo esta vacion o el valor Ingresado Excede el valor de la glosa');
            document.FORMA.ValorAceptado_<? echo $fila[0]?>.value=''
            document.FORMA.ValorAceptado_<? echo $fila[0]?>.focus();
       	}"
       	><img src="../Imgs/vobo.jpg" width="17" height="17"></button>
	  </td>        
</tr> 
  <?  } ?>
<tr>
<td align="center" colspan="7"></td>
<td align="center" bgcolor="#e5e5e5" style="font-weight:bold" >Total</td>
<td>

<?
$Tot =0;
$cone="SELECT valorconciliado FROM facturacion.motivoglosa WHERE compania='$Compania[0]' and nufactura='$NoFac'";
$resp=ExQuery($cone);
while ($fi=ExFetch($resp)){
if ($fi[0]>=1){
$Tot = $Tot + $fi[0];
} }	
echo "<input name='TotalConci' type='hidden' value='$Tot'>";
echo $Tot;
$conx= "SELECT vrtotal,vrglosa,aceptaglosa FROM facturacion.motivoglosa where  nufactura='$NoFac' ";
					$resx= ExQuery($conx);
					while($fil=ExFetch($resx))
					{	if ($fil[0]>=1)	{
						
						$Total = $Total + $fil[1];																	 
                        $Objetado= $Total  - $Tot;
						$valoreps= $fil[0] - $Tot;
						 				
						}}										
					?>  
<input name="VAceptado" type="hidden" size="8" value=" <? echo $Tot ?>">      

<input name="Objetado" type="hidden" size="5"  value="<? echo $Objetado ?>">
<input name="ValorEPS" type="hidden" size="5"  value="<? echo $valoreps ?>">
	  </td>
</tr>
 <tr>
  <td align="center" colspan="10">
  </td>
  </tr>
  <tr>
  <td align="center" colspan="10">
<button name="Gtodo" type="submit">Guardar </button>
  </td>
  </tr>
</table>
<? }	else{?>
    	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td>No hay Glosas que cumpan con los parametros de la busqueda</td></tr></table><? } ?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Guardar" value="">
</form>
</body>
</html>
