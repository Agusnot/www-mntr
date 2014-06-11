<? 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	if($CerrarSesion)
	{
		session_destroy();
		?>
        <script language="javascript">location.href='/';</script>
        <?
	}
	else
	{
		echo "Lo Sentimos, No puede acceder a la Pagina Solicitada!!!<br>";
		echo "<a href='#' onclick='window.history.back()'>Salir</a>";
	}
?>