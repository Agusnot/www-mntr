<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
if($Modifica){	
$consm="Update facturacion.motivoglosa 
Set estadorecepcion='$ArguAceptado', valorrecepcion=$ValorAceptado where  tipoglosa='$TipoGlosa'
 and nufactura='$NoFac' ";
$resu=ExQuery($consm); 

					?>
						<script language="JavaScript">
						 alert ("informacion Registrada correptamente!!");
					   </script>  
					<?

echo ExError($resu);		
}			 
if($Gtodo){
$tiempo = strftime("%Y-%m-%d %H:%M:%S",time());	
$consm="Update facturacion.respuestaglosa
Set totalrecepcion=$TotalRecep, usuariorecepcion='$usuario[1]',fecharecepcion='$tiempo' where nufactura='$NoFac'";
$resu=ExQuery($consm);

					?>
						<script language="JavaScript">
						 alert ("informacion Registrada correptamente!!");
					   </script>  
					<? 
echo ExError($resu);	
}		 

?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
function Validar(){	
if(document.FORMA.''.value==""){alert(""); 
document.FORMA.''.focus();return false;}

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
</script>



</head>
<body background="/Imgs/Fondo.jpg">
<input type="button" value=" X " onClick="parent.document.getElementById('Fac'+<? echo $NoFac?>).checked=false;CerrarThis()" style="position:absolute;top:1px;right:1px;" 
title="Cerrar esta ventana">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table width="774" border="2" align="center" cellpadding="2" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;'>
<tr>
<td  align="center" colspan="9" bgcolor="#e5e5e5" style="font-weight:bold">      
<font size="4">RECEPCION DE RESPUESTA</font>
</td>
</tr>

<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"> 
<td width="69" >Codigo Glosa</td>  
<td width="54" >Clase Glosa</td> 
<td width="88" >Observacion</td> 
<td width="65" >Valor Glosa</td> 
<td width="87" >valor Glosa Aceptada</td> 
<td width="131" >Observacion Glosa Aceptada</td> 
<td width="93" >Estado</td> 
<td width="84" >valor</td> 
<td width="25"></td>
</tr> 
<?
$cons="select tipoglosa,claseglosa,observacionglosa,vrglosa,aceptaglosa,obseraceptado FROM facturacion.motivoglosa WHERE compania='$Compania[0]' AND  nufactura='$NoFac'";
$res=ExQuery($cons);
while($fila=ExFetch($res)){  
?>  
<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
<td ><? echo $fila[0] ?></td>
<td ><? echo $fila[1] ?></td> 
<td ><? echo $fila[2] ?></td> 
<td ><? echo number_format ($fila[3],2) ?></td> 
<td ><? echo number_format ($fila[4],2) ?></td> 
<td ><? echo $fila[5] ?></td> 
<? 
$conexion="SELECT estadorecepcion,valorrecepcion FROM facturacion.motivoglosa WHERE compania='$Compania[0]' and tipoglosa='$fila[0]' and nufactura='$NoFac'";
$respuesta=ExQuery($conexion);
while ($fil=ExFetch($respuesta)){
?>
<td align="center">	 
<?

if($fila[3]==$fila[4]) {echo "";}
else { 
echo "<font color='green'><b>".$fil[0]."</b></font>";
?>	
<select name="ArguAceptado_<? echo $fila[0]?>" id="ArguAceptado_<? echo $fila[0]?>">                 
<option value="Reiterada">Reitero Glosa</option>                 
<option value="Aceptada">Aceptada</option>
</select><? } ?>
</td>
<td>
<?
if($fila[3]==$fila[4]) {echo "";}
else { ?>
<input  name="ValorAceptado_<? echo $fila[0]?>" type="text"  onKeyDown="xNumero(this)"  size="8" onKeyUp="xNumero(this)"
value="<? echo $fil[1];}} ?>" >
</td>
<td>
<?
if($fila[3]==$fila[4]) {echo "";}
else { ?>	
<button onClick="        
if((document.FORMA.ValorAceptado_<? echo $fila[0]?>.value!='') &
(document.FORMA.ArguAceptado_<? echo $fila[0]?>.value!='')
)
{   
if(confirm('Desea registra el valor?'))
{	
location.href='Report.php?DatNameSID=<? echo $DatNameSID?>&Modifica=1&TipoGlosa=<? echo $fila[0]?>&NoFac=<? echo $NoFac?>&ValorAceptado='+document.FORMA.ValorAceptado_<? echo $fila[0]?>.value+'&ArguAceptado='+document.FORMA.ArguAceptado_<? echo $fila[0]?>.value;
}
}                                                        
else{
alert('Atencion: El campo esta vacion o el valor Ingresado Excede el valor de la glosa');
document.FORMA.ValorAceptado_<? echo $fila[0]?>.value=''
document.FORMA.ValorAceptado_<? echo $fila[0]?>.focus();
}"
><img src="../Imgs/vobo.jpg" width="17" height="17"></button><? } ?>
</td>
  </tr>  
  <? }  ?> 
<tr>
<td colspan="6"></td>
<td bgcolor="#e5e5e5" style="font-weight:bold">total</td>
<td>
<?
$Tot=0;
$cone="SELECT valorrecepcion FROM facturacion.motivoglosa WHERE compania='$Compania[0]' and nufactura='$NoFac'";
$resp=ExQuery($cone);
while ($fi=ExFetch($resp)){
if ($fi[0]>=1){
$total=$fi[0];
$Tot = $Tot + $total;
}   }          
echo "<input name='TotalRecep' type='hidden' value='$Tot'>";
echo number_format ($Tot,2); 
?>    
</td>
</tr>
<tr>
<td align="center" colspan="10">
<button name="Gtodo" type="submit" 
onClick="location.href=VerRecepRta.php.reload()" >Guardar </button>  
</td>
</tr> </table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
