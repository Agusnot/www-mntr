<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
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
	}
	function Colocar(Indice)
	{
		switch(document.FORMA.Cantidad.value){
			case '0.25':parent.document.FORMA.Notas.value=parent.document.FORMA.Notas.value+document.FORMA.Nota.value+', Dosis de 0.25 A las <? echo $Indice?>:00 \n';
			break;
			case '0.5':parent.document.FORMA.Notas.value=parent.document.FORMA.Notas.value+document.FORMA.Nota.value+', Dosis de 0.5 A las <? echo $Indice?>:00 \n';
			break;
			case '0.75':parent.document.FORMA.Notas.value=parent.document.FORMA.Notas.value+document.FORMA.Nota.value+', Dosis de 0.75 A las <? echo $Indice?>:00 \n';
			break;
			case '1.25':parent.document.FORMA.Notas.value=parent.document.FORMA.Notas.value+document.FORMA.Nota.value+', Dosis de 1.25 A las <? echo $Indice?>:00 \n';
			break;
			case '1.5':parent.document.FORMA.Notas.value=parent.document.FORMA.Notas.value+document.FORMA.Nota.value+', Dosis de 1.5 A las <? echo $Indice?>:00 \n';
			break;
			case '1.75':parent.document.FORMA.Notas.value=parent.document.FORMA.Notas.value+document.FORMA.Nota.value+', Dosis de 1.75 A las <? echo $Indice?>:00 \n';
			break;
		}
		switch(document.FORMA.Cantidad.value){
			case '0.25':document.FORMA.Cantidad.value=1;
			break;
			case '0.5':document.FORMA.Cantidad.value=1;
			break;
			case '0.75':document.FORMA.Cantidad.value=1;
			break;
			case '1.25':document.FORMA.Cantidad.value=2;
			break;
			case '1.5':document.FORMA.Cantidad.value=2;
			break;
			case '1.75':document.FORMA.Cantidad.value=2;
			break;
		}
		parent.document.getElementById('Cantidad[<? echo $Indice?>]').value = '('+document.FORMA.Cantidad.value+')';
		parent.document.getElementById('Cantidad[<? echo $Indice?>]').title = document.FORMA.Nota.value;
		parent.document.getElementById('Nota[<? echo $Indice?>]').value = document.FORMA.Nota.value;
		parent.document.FORMA.submit();
		CerrarThis();	
	}
		function Colocar2(Indice)
	{
		parent.document.getElementById('Cantidad[<? echo $Indice?>]').value = '('+document.FORMA.Cantidad.value+')';
		parent.document.getElementById('Cantidad[<? echo $Indice?>]').title = document.FORMA.Nota.value;
		parent.document.getElementById('Nota[<? echo $Indice?>]').value = document.FORMA.Nota.value;
		parent.document.FORMA.submit();
		CerrarThis();	
	}
	function Quitar()
	{
		parent.document.getElementById('Hora[<? echo $Indice?>]').checked = false;
		parent.document.FORMA.submit();
		CerrarThis();
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
	<table width="100%" border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;'>
    	<tr>
        	<td bgcolor="#e5e5e5" colspan="2" align="center">Hora: <? echo $Indice?></td>
        </tr>
        <tr>
        	<td width="50%" bgcolor="#e5e5e5">
            	Cantidad:
            </td>
            <td width="50%">
            <?	if(!$Cantidad){$Cantidad=1;}?>
            	<select name="Cantidad" style="width:100%">
                	<option value = '0.25'>0.25</option>
					<option value = '0.5'>0.5</option>
					<option value = '0.75'>0.75</option>
					<option value = '1' selected>1</option>
					<option value = '1.25'>1.25</option>
					<option value = '1.5'>1.5</option>
					<option value = '1.75'>1.75</option>
					<option value = '2'>2</option>
					<option value = '3'>3</option>
					<option value = '4'>4</option>
					<option value = '5'>5</option>
					<option value = '6'>6</option>
					<option value = '7'>7</option>
					<option value = '8'>8</option>
					<option value = '9'>9</option>
					<option value = '10'>10</option>
                </select>
            </td>
        </tr>
        <tr>
        	<td colspan="2" bgcolor="#e5e5e5" align="center">Nota:</td>
        </tr>
        <tr>
        	<td colspan="2">
            	<textarea name="Nota" style="width:100%" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></textarea>
            </td>
        </tr>
    </table>
<img src="/Imgs/b_drop.png" title="cancelar" onClick="Quitar()" style="cursor:hand" />
<?php 
if($tipopresenta=="AMPOLLA"){
?>
<img src="/Imgs/b_check.png" title="Guardar" onClick="Colocar(<? echo $Indice?>)" style="cursor:hand"/>
<?php 
} else{
?>
<img src="/Imgs/b_check.png" title="Guardar" onClick="Colocar2(<? echo $Indice?>)" style="cursor:hand"/>
<?php 
} 
?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>

