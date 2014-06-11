<?
	include("Funciones.php");
	if($Borrar)
	{
		$cont=1;		
		do
		{
			echo "<center><font color='#009933'>INTENTO NUMERO $cont!!!<BR></font></center>";
			//--PRIORIDAD
			$Err='';
			//-- Los demas esquemas
			$cons="Select table_schema FROM information_schema.columns where table_schema!='information_schema' and table_schema!='pg_catalog'
			group By table_schema Order By table_schema";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				echo "<br>".$fila[0];			
				$cons1="Select table_name FROM information_schema.columns where table_schema='$fila[0]' group by table_name Order By table_name";
				$res1=ExQuery($cons1);
				while($fila1=ExFetch($res1))
				{				
					echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$fila1[0];	
					$cons2="select column_name from information_schema.columns where table_schema='$fila[0]' and table_name = '$fila1[0]' 
					and column_name='compania' Order By ordinal_position";			
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)>0)
					{
						$cons3="Delete from $fila[0].$fila1[0] where compania='$Compania'";
						$res3=ExQuery($cons3);
						echo "<br>".$cons3;
						if(ExError($res3))
						{
							$Err=1;
							echo "   <font color='#CC0033'>Borrado Paila!!!</font>";
							
						}
						else
						{
							echo "   <font color='#009933'>Borrado Ok!!!</font>";								
						}					
					}
				}
			}			
		}while($Err);
		if(!$Err)
		{
			//$cons="Delete from central.compania where nombre='$Compania'";	
			//$res=ExQuery($cons);
			echo "<BR><BR>   <center><font color='#009933'>LOS DATOS DE LA COMPANIA $Compania SE ELIMINARON POR COMPLETO!!!<BR>AHORA DEBE ELIMINAR EL REGISTRO DESDE GESTION DE COMPANIAS EN EL SISTEMA</font></center>";	
			?>
			<script language="javascript">alert("LOS DATOS DE LA COMPANIA <? echo $Compania?> SE ELIMINARON POR COMPLETO!!!\nAHORA DEBE ELIMINAR EL REGISTRO DESDE GESTION DE COMPANIAS EN EL SISTEMA");</script>
			<?
		}
		else
		{
			echo "<br>Debe volver a correr el script!!!";	
		}
	}
	if($Probar)
	{
		$cons="Select table_schema FROM information_schema.columns where table_schema!='information_schema' and table_schema!='pg_catalog'
		group By table_schema Order By table_schema";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			echo "<br>".$fila[0];			
			$cons1="Select table_name FROM information_schema.columns where table_schema='$fila[0]' group by table_name Order By table_name";
			$res1=ExQuery($cons1);
			while($fila1=ExFetch($res1))
			{				
				echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$fila1[0];	
				$cons2="select column_name from information_schema.columns where table_schema='$fila[0]' and table_name = '$fila1[0]' 
				and column_name='compania' Order By ordinal_position";			
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)>0)
				{
					$cons3="Select * from $fila[0].$fila1[0] where compania='$Compania'";
					$res3=ExQuery($cons3);
					if(ExNumRows($res3)>0)
					{
						echo "   <font color='#CC0033'>Aun Tiene Datos!!!</font>";	
					}
					else
					{
						echo "   <font color='#009933'>Sin Datos!!!</font>";	
					}									
				}
			}
		}	
	}
?>
<body>
<form name="FORMA" method="post" onSubmit="if(document.FORMA.Compania.value==''){alert('Debe seleccionar y llenar todos los campos!!!');return false;}" >
<?
if(!$Compania)
{?>
	<b>Seleccione Compania:</b>
    <select name="Compania">
	<option value=""></option>
	<?
	$cons="Select nombre from central.compania order by nombre";
	$res=ExQuery($cons);
	
	while($fila=ExFetch($res))
	{	
		if($fila[0]==$Compania)
		{
			?>
			<option value="<? echo $fila[0]?>" selected><? echo $fila[0]?></option>
			<?
		}
		else
		{?>
			<option value="<? echo $fila[0]?>"><? echo $fila[0]?></option>
		<?
		}	
	}
	?>
    </select>		
    <br />
    <input type="submit" name="Borrar" value="Borrar Datos Compania" />
    <input type="submit" name="Probar" value="Comprobar el Proceso" />
	<?
}
?>
</form>
</body>