<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");?>
    <script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
		
		parent.document.getElementById('FrameFondo').style.position='absolute';
		parent.document.getElementById('FrameFondo').style.top='1px';
		parent.document.getElementById('FrameFondo').style.left='1px';
		parent.document.getElementById('FrameFondo').style.width='1';
		parent.document.getElementById('FrameFondo').style.height='1';
		parent.document.getElementById('FrameFondo').style.display='none';
		//parent.document.FORMA.submit();
	}
	</script>	

<?	if($Guardar)
	{
		$cons="update histoclinicafrms.cupsxfrms set finalidadproced='$Finalidad',formarealizacion='$FormaActo',causaextern='$CausaExtern'
		where compania='$Compania[0]' and formato='$Formato' and tipoformato='$TipoFormato' and id_historia='$IdHistoria' and numservicio=$NumServicio and cedula='$Paciente[1]'
		and cup='$CUP'";
		//echo $cons;
		$res=ExQuery($cons);
		$cons="select codigo,liquidacion.noliquidacion from facturacion.detalleliquidacion,facturacion.liquidacion
		where liquidacion.compania='$Compania[0]' and numservicio=$NumServicio and estado='AC' and detalleliquidacion.compania='$Compania[0]'
		and detalleliquidacion.noliquidacion=liquidacion.noliquidacion and tipo='00004' 
		and (dxppal is null or dxppal='' or finalidad='' or finalidad is null or causaext is null or causaext='')";					
		$res=ExQuery($cons);
		if(ExNumRows($res)>0)
		{?>
        	<script language="JavaScript">	
				parent.document.getElementById('FrameOpener').style.left='1';
				parent.document.getElementById('FrameOpener').style.width='100%';
				location.href="CompletaCupsFacs.php?DatNameSID=<? echo $DatNameSID?>&SFFormato=<? echo $SFFormato?>&IdHistoOrigen=<? echo $IdHistoOrigen?>&SFTF=<? echo $SFTF?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&SoloUno=<? echo $SoloUno?>&NumServicio=<? echo $NumServicio?>&Frame=<? echo $Frame?>";
			</script>
	<?	}
		else
		{?>
			<script language="JavaScript">			
				CerrarThis();
				//parent.location.href='Datos.php?DatNameSID=<? echo $DatNameSID?>&SFFormato=<? echo $SFFormato?>&IdHistoOrigen=<? echo $IdHistoOrigen?>&SFTF=<? echo $SFTF?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&SoloUno=<? echo $SoloUno?>';
			</script>	              
<?		}
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<?
$cons="select tipo,quirurgico,finalidadcup,nombre from contratacionsalud.cups where compania='$Compania[0]' and codigo='$CUP'";
$res=ExQuery($cons);
$fila=ExFetch($res);

if($fila[0]=='00004'){
	$Consulta=1;
	$TipoFindalidad=1;
	$Ban=1;
}
elseif($fila[0]=='00005')
{
	$Procedimiento=1;
	if($fila[1])
	{$Quirugico=$fila[1];}
	$TipoFindalidad=2;
	$Ban=1;
}
?>
<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Cup <? echo "$CUP - $fila[3]"?> Cargado</td>        
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Finalidad del Procedimiento</td>        
    </tr>
    <tr>
    <?	$cons="select finalidad,codigo,pordefecto from salud.finalidadesact where tipo=$TipoFindalidad";
		$res=ExQuery($cons);?>
    	<td align="center">
        	<select name="Finalidad">
      	<?	while($fila=ExFetch($res))
			{
				if(!$Finalidad&&$fila[2]==1)
						{
							echo "<option value='$fila[1]' selected>$fila[0]</option>";	
						}
						else
						{	
							echo "<option value='$fila[1]'>$fila[0]</option>";	
						}
				//echo "<option value='$fila[1]'>$fila[0]</option>";
			}?>
            </select>
        </td>
    </tr>
<?	if($Consulta==1)
	{?>
		<tr>
        	 <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Causa Externa</td>        
        </tr>	
        <tr align="center">
        <?	$cons="select codigo,causa,pordefecto from salud.causaexterna order by causa";
			$res=ExQuery($cons);?>
        	<td>
            	<select name="CausaExtern">
                <?	while($fila=ExFetch($res))
					{
						if(!$CausaExter&&$fila[2]==1)
						{
							echo "<option value='$fila[0]' selected>$fila[1]</option>";	
						}
						else
						{	
							echo "<option value='$fila[0]'>$fila[1]</option>";	
						}
					}?>
                </select>
            </td>
        </tr>
<?	}
	if($Quirugico){?>
		<tr>
            <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Forma de Realizacion Acto Quirurgico</td>        
        </tr>
        <tr align="center">
        <?	$cons="select forma,codigo from salud.formarquirurgico";
            $res=ExQuery($cons);?>
            <td align="center">
                <select name="FormaActo">
            <?	while($fila=ExFetch($res))
                {
                    echo "<option value='$fila[1]'>$fila[0]</option>";
                }?>
                </select>
            </td>
        </tr>
<?	}?> 
	<tr>
    	<td align="center"><input type="submit" name="Guardar" value="Guardar"></td>
    </tr>   
</table>
<?
	if(!$Ban)
	{?>
		<script language="JavaScript">			
			//CerrarThis();	
		</script>
<?	}
	
?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Formato" value="<? echo $Formato?>">
<input type="hidden" name="TipoFormato" value="<? echo $TipoFormato?>">
<input type="hidden" name="IdHistoria" value="<? echo $IdHistoria?>">
<input type="hidden" name="NumServicio" value="<? echo $NumServicio?>">
<input type="hidden" name="Quirugico" value="<? echo $Quirugico?>">
<input type="hidden" name="SFFormato" value="<? echo $SFFormato?>">
<input type="hidden" name="IdHistoOrigen" value="<? echo $IdHistoOrigen?>">
<input type="hidden" name="SFTF" value="<? echo $SFTF?>">
<input type="hidden" name="SoloUno" value="<? echo $SoloUno?>">
<input type="hidden" name="CUP" value="<? echo $CUP?>">
</form>
</body>
</html>
