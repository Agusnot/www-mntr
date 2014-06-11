<? 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	//echo $Recarga;
?>
<script language="javascript">
function Salir()
{
	parent.document.getElementById('FrameOpener').style.position='absolute';
	parent.document.getElementById('FrameOpener').style.top='0';
	parent.document.getElementById('FrameOpener').style.left='0';
	parent.document.getElementById('FrameOpener').style.display='';
	parent.document.getElementById('FrameOpener').style.width='0';
	parent.document.getElementById('FrameOpener').style.height='0';	
}
function recargar()
{	
	//alert("<? echo $Recarga.' --> '.$_REQUEST['Recarga']?>");
	<?
	if($Recarga>0)
	{
		$Recarga--;		
		$_SESSION['Recarga']=$Recarga;		?>		
		document.location.reload();		
	<?
	}
	else
	{?>		
		setTimeout('Salir()',2000);		
	<?
	}?>	
}
</script>
<body onLoad="setTimeout('recargar()',1);">
<input type="image" name="Imagen" src="<? echo $Imagen?>" style="position:absolute; top:0; left:0;" width="<? echo $AnchoLogo?>"  height="<? echo $AltoLogo?>">
<!--<img name="Imagen" src="<? echo $Imagen?>" style="width:80px; height:80px; position:absolute; top:0; left:0;" border="0" >-->
</body>
<script> </script>