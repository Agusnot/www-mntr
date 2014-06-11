<html>
<?php
$j=2012004202;
for($i=2013003976;$i<=2013004097;$i++){
echo'update contabilidad.movimiento set numero='."'$j'".'where comprobante='."'Cuentas x pagar'".'and numero='."'$i'".';</br>';
$j++;
}
?>
</html>