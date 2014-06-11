<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	if($Guardar)
	{
		$ND=getdate();
		$cons="update historiaclinica.regpacienteseg 
		set accinseg1='$AccInseg1',accinseg2='$AccInseg2',accinseg3='$AccInseg3',accinseg4='$AccInseg4',accinseg5='$AccInseg5',accinseg6='$AccInseg6',accinseg7='$AccInseg7'
		,faccontrib1='$FacContrib1',faccontrib2='$FacContrib2',faccontrib3='$FacContrib3',faccontrib4='$FacContrib4',faccontrib5='$FacContrib5',faccontrib6='$FacContrib6'
		,faccontrib7='$FacContrib7',clasificfinal='$ClasificFinal',reganalisis='1',fechareganalisis='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
		where compania='$Compania[0]' and numrep=$NumRep";
		$res=ExQuery($cons);?>
		<script language="javascript">
			parent.document.FORMA.submit();
		</script>
<?	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
		//parent.document.FORMA.submit();
	}
	function Validar()
	{
		if(document.FORMA.AccInseg1.value==""){
			alert("Debe digitar almenos una Accion Insegura!!!");return false;
		}
		else{
			if(document.FORMA.FacContrib1.value==""){alert("Debe digitar almenos un Factor Contributivo!!!");return false;}
		}
		if(document.FORMA.AccInseg2.value!=""&&document.FORMA.FacContrib2.value==""){alert("Cada Accion Insegura debe tener su Factor Contributivo!!!");return false;}
		if(document.FORMA.AccInseg3.value!=""&&document.FORMA.FacContrib3.value==""){alert("Cada Accion Insegura debe tener su Factor Contributivo!!!");return false;}
		if(document.FORMA.AccInseg4.value!=""&&document.FORMA.FacContrib4.value==""){alert("Cada Accion Insegura debe tener su Factor Contributivo!!!");return false;}
		if(document.FORMA.AccInseg5.value!=""&&document.FORMA.FacContrib5.value==""){alert("Cada Accion Insegura debe tener su Factor Contributivo!!!");return false;}
		if(document.FORMA.AccInseg6.value!=""&&document.FORMA.FacContrib6.value==""){alert("Cada Accion Insegura debe tener su Factor Contributivo!!!");return false;}
		if(document.FORMA.AccInseg7.value!=""&&document.FORMA.FacContrib7.value==""){alert("Cada Accion Insegura debe tener su Factor Contributivo!!!");return false;}
	}
</script>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" align="center">
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td></td><td>Acciones Inseguras</td><td>Factores Contributivos</td>
	</tr>
    <tr>
    	<td><strong>1.</strong></td>
        <td><input type="text" name="AccInseg1" onkeydown="xLetra(this)" onkeypress="xLetra(this)" onkeypress="xLetra(this)" style="width:300"/></td>
        <td><input type="text" name="FacContrib1" onkeydown="xLetra(this)" onkeypress="xLetra(this)" onkeypress="xLetra(this)" style="width:300"/></td>
   	</tr>
    <tr>
    	<td><strong>2.</strong></td>
        <td><input type="text" name="AccInseg2" onkeydown="xLetra(this)" onkeypress="xLetra(this)" onkeypress="xLetra(this)" style="width:300"/></td>
        <td><input type="text" name="FacContrib2" onkeydown="xLetra(this)" onkeypress="xLetra(this)" onkeypress="xLetra(this)" style="width:300"/></td>
   	</tr>
    <tr>
    	<td><strong>3.</strong></td>
        <td><input type="text" name="AccInseg3" onkeydown="xLetra(this)" onkeypress="xLetra(this)" onkeypress="xLetra(this)" style="width:300"/></td>
        <td><input type="text" name="FacContrib3" onkeydown="xLetra(this)" onkeypress="xLetra(this)" onkeypress="xLetra(this)" style="width:300"/></td>
   	</tr>
    <tr>
    	<td><strong>4.</strong></td>
        <td><input type="text" name="AccInseg4" onkeydown="xLetra(this)" onkeypress="xLetra(this)" onkeypress="xLetra(this)" style="width:300"/></td>
        <td><input type="text" name="FacContrib4" onkeydown="xLetra(this)" onkeypress="xLetra(this)" onkeypress="xLetra(this)" style="width:300"/></td>
   	</tr>
    <tr>
    	<td><strong>5.</strong></td>
        <td><input type="text" name="AccInseg5" onkeydown="xLetra(this)" onkeypress="xLetra(this)" onkeypress="xLetra(this)" style="width:300"/></td>
        <td><input type="text" name="FacContrib5" onkeydown="xLetra(this)" onkeypress="xLetra(this)" onkeypress="xLetra(this)" style="width:300"/></td>
   	</tr>
    <tr>
    	<td><strong>6.</strong></td>
        <td><input type="text" name="AccInseg6" onkeydown="xLetra(this)" onkeypress="xLetra(this)" onkeypress="xLetra(this)" style="width:300"/></td>
        <td><input type="text" name="FacContrib6" onkeydown="xLetra(this)" onkeypress="xLetra(this)" onkeypress="xLetra(this)" style="width:300"/></td>
   	</tr>
    <tr>
    	<td><strong>7.</strong></td>
        <td><input type="text" name="AccInseg7" onkeydown="xLetra(this)" onkeypress="xLetra(this)" onkeypress="xLetra(this)" style="width:300"/></td>
        <td><input type="text" name="FacContrib7" onkeydown="xLetra(this)" onkeypress="xLetra(this)" onkeypress="xLetra(this)" style="width:300"/></td>
   	</tr>
    <tr>
    	<td colspan="3">
        	<strong>Clasificacion Final</strong>
            <select name="ClasificFinal">
            	<option value="Complicacion">Complicacion</option>
                <option value="Evento Adverso">Evento Adverso</option>
            	<option value="Incidente">Incidente</option>                
            </select>
        </td>
    </tr>
    <tr align="center">
    	<td colspan="3">
        	<input type="submit" value="Guardar" name="Guardar"/><input type="button" value="Cancelar" onclick="CerrarThis()"/>
        </td>
    </tr>
</table>    
</body>
</html>
