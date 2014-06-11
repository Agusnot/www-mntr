<?
include("Funciones.php");
$Cons="select anio from central.anios where compania='Clinica Bellatriz S.A.S.' order by anio desc";
$Res=ExQuery($Cons);
while($fila=ExFetch($Res))
{
//	echo $fila[0]."<br>";
	$LastYear=$fila[0];	
}
//echo " --> ".$LastYear." <-- ";
$YearStart=1900;
while($YearStart<$LastYear)
{
$Cons1="Insert into central.anios (anio,compania) values ('$YearStart','Hospital San Rafael de Pasto')";
$Res1=ExQuery($Cons1);
echo $Cons1."<br>";
$YearStart++;
}
?>