<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Nombre.value==""){alert("Debe llenar el campo <? echo $Campo; ?>");return false;}
	}
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
</script>
<?
	if($Guardar)
	{
		if(!$Editar)
                {
                    $cons = "Select * from Consumo.$Tabla Where Compania='$Compania[0]' and $Campo='$Nombre'";
                    $res = ExQuery($cons);
                    if(ExNumRows($res)>0)
                    {
                        $Mensajecons="El dato de $Campo, $Nombre ya se encuentra ingresado";
                    }
                    $cons = "Insert into Consumo.".$Tabla."(".$Campo.",Compania) values('$Nombre','$Compania[0]')";

                }
		else
		{	$cons = "Update Consumo.".$Tabla." set ".$Campo."='$Nombre'
			where ".$Campo."='$Nombrex' and Compania='$Compania[0]'";	
		}
                if(!$Mensajecons){$res=ExQuery($cons);}
		if(ExError()){echo "";}
		else
		{
			if($VienedeOtro)
			{
				?>
					<script language="javascript">
        				CerrarThis();
						parent.document.getElementById('<? echo $Objeto?>').focus();
                	</script>
				<?	
			}
			else
			{
				?>
					<script language="javascript">
						location.href="AdministrarTablasConsumo.php?DatNameSID=<? echo $DatNameSID?>&Tabla=<? echo $Tabla;?>&Campo=<? echo  $Campo;?>";
                    </script>
				<?
			}
		}
	}
?>
<body background="/Imgs/Fondo.jpg" onLoad="document.FORMA.Nombre.focus();">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
	<tr bgcolor="#e5e5e5" style="font-weight:bold">
    	<td colspan="2" align="center"><? echo $AlmacenPpal;?></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">Nombre <? echo $Campo;?></td>
        <td><input type="text" name="Nombre" value="<? echo $Nombre;?>" maxlength="100" size="50"  
        	onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"/></td>
    </tr>
</table>
<input type="Hidden" name="Tabla" value="<? echo $Tabla; ?>"  />
<input type="Hidden" name="Campo" value="<? echo $Campo; ?>"  />
<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal; ?>"  />
<input type="Hidden" name="Nombrex" value="<? echo $Nombre; ?>"  />
<input type="Hidden" name="Editar" value="<? echo $Editar; ?>"  />
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />

<input type="text" name="Nada" value="1" style="visibility:hidden;width:1px;" />
<input type="submit" name="Guardar" value="Guardar" />
<input type="button" name="Cancelar" value="Cancelar" 
onClick="<?
		if($VienedeOtro)
		{ echo "CerrarThis()";}
		else
		{?>location.href='AdministrarTablasConsumo.php?DatNameSID=<? echo $DatNameSID?>&Tabla=<? echo $Tabla;?>&Campo=<? echo $Campo;?>'<? } ?> " />
</form>
</body>