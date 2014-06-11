<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
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
</script>
<?	
	if($Guardar)
	{
		if($Regimenes){
			$ban=0;
			while( list($cad,$val) = each($Regimenes))
			{
				if($ban==0)
				{
					$Regs=$cad;
					$ban=1;
				}
				else
				{
					$Regs=$Regs.";".$cad;
				}
			}
		}
		$Incluye="";?>
        <script language="javascript">
			parent.document.FORMA.AuxRegimenes.value="<? echo $Regs?>";
			if(parent.document.FORMA.AuxIncluir.value!=""){
				parent.document.FORMA.AuxIncluir.value="";
				alert("Se deben volver a seleccionar los pagadores a ser incluidos o excluidos!!!");
			}
			CerrarThis();
		</script>
<?	}

	if($Regimenes)
	{ 
		$AuxRegms=explode(";",$Regimenes);
		foreach($AuxRegms as $AR)
		{				
			$Regs[$AR]=$AR;
		}
		
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
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
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  border="1" bordercolor="#e5e5e5" cellpadding="4" style="font : normal normal small-caps 12px Tahoma">	
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
	<tr d bgcolor="#e5e5e5" style="font-weight:bold" align="center">	
    	<td>Regimenes A seleccionar</td><td><input type="checkbox" name="Todos" title="Seleccionar Todos" onClick="ChequearTodos(this);"/></td>
   	</tr>
<?	$cons="select tipo from central.tiposaseguramiento ";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
        	<td><? echo $fila[0]?></td>
            <td><input type="checkbox" name="Regimenes[<? echo $fila[0]?>]" <? if($Regs[$fila[0]]){?> checked <? }?>/></td>
        </tr>	
<?	}?>    
    <tr>
    	<td align="center" colspan="2"><input type="submit" name="Guardar" value="Guardar"/></td>
    </tr>
</table>
</form>
</body>
</html>
