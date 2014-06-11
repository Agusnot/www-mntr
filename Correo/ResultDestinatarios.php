<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");?>

<?	
	if($Destinatarios)
	{ 
		$AuxUsus=explode(";",$Destinatarios);
		foreach($AuxUsus as $AU)
		{				
			$Usus[$AU]=$AU;
		}
		
	}
	if($Agregar)	{
		
		if($Usus){
			
			while( list($cad,$val) = each($Usus)){
				
				$UsusDes=$UsusDes.";".$_POST["$cad"];
				$UsuDcorto=$UsuDcorto.";$cad" ;
//				echo $_POST["$cad"]."<br>";
			}			
		}?>
		<script language="javascript">
			parent.parent.document.FORMA.Para.value="<? echo $UsusDes?>";
			parent.parent.document.FORMA.AuxPara.value="<? echo $UsuDcorto?>";
			parent.CerrarThis();
		</script>	
<?	}
	
	$cons="select usuario,cargo,especialidad from salud.medicos where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Meds[$fila[0]]=array($fila[1],$fila[2]);		
	}
	
	if($Nombre){$Nom="and nombre ilike '%$Nombre%'";}
	$cons="select nombre,usuario from central.usuarios where nombre is not null $Nom order by nombre";
	$res=ExQuery($cons);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">	
	function ChequearTodos(chkbox) { 
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

<body background="/Imgs/Fondo.jpg" >
<form name="FORMA" method="post">
<table cellpadding="1"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">	
    <tr>
    	<td colspan="10" align="center"><input type="submit" value="Agregar" name="Agregar"></td>
    </tr><!---
    <tr>
    	<td>
    		<input type="text" name="NomUsu" value="<? echo $NomUsu?>" style="width:250" nKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this);document.FORMA.submit();">
      	</td>
    </tr>-->
	<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
    	<td>Nombre</td><td>Usuario</td><td>Cargo</td><td>Especialidad</td>
        <td><input type="checkbox" name="Todos" onClick="ChequearTodos(this);" title="Seleccionar Todos"></td>
    </tr>
<?	while($fila=ExFetch($res)){
		$BanNo=0;
		if($Cargo){ 
			if($Cargo!=$Meds[$fila[1]][0])			
			{ 
				$BanNo=1;
			}			
		}
		if($Espec){ 
			if($Espec!=$Meds[$fila[1]][1])
			{
				$BanNo=1;
			}			
		}
		if($BanNo!=1){?>
            <tr>
                <td><? echo $fila[0]?></td>
				<td><? echo $fila[1]?></td><td><? echo $Meds[$fila[1]][0]?>&nbsp;</td><td><? echo $Meds[$fila[1]][1]?>&nbsp;</td>
					<?php $nombreUsuario = str_replace( " ","_",$fila[1]);?>
                <td align="center"><input type="checkbox" name="Usus[<? echo $nombreUsuario; ?>]" <? if($Usus[$nombreUsuario]==$nombreUsuario){?> checked<? }?>>
                    <input type="hidden" name="<? echo $fila[1]?>" value="<? echo $fila[0]?>">
                </td>
            </tr>
<?		}
	}?>    
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>    
</body>
</html>