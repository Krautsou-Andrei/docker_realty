<?
function okr($a)
{
  return number_format(abs($a), 2, ',', ' ');
}

function do_c($a)
{
  $a = preg_replace('/[^0-9,\.]/u', '', $a);
  $a = str_replace(',', '.', $a);
  $a = floatval($a);
  return $a;
}

function m_an($sum, $proc, $srok)
{
  $all_summa_platega = 0;
  $all_osn_dolg = 0;
  $all_nach_procent = 0;

  $p = $proc / 100 / 12;

  $koef = $p * pow((1 + $p), $srok) / (pow((1 + $p), $srok) - 1);

  ob_start();

  for ($i = 1; $i <= $srok; $i++) {
    $ostatok = $sum;
    $summa_platega = $koef * $sum;
    $nach_procent = $ostatok * $proc / 12 / 100;
    $osn_dolg = $summa_platega - $nach_procent;
    $ostatok = $ostatok - $osn_dolg;

    $all_summa_platega = $all_summa_platega + $summa_platega;
    $all_osn_dolg = $all_osn_dolg + $osn_dolg;
    $all_nach_procent = $all_nach_procent + $nach_procent;
  }

  $buf = ob_get_contents();
  ob_end_clean();

  $price = $all_summa_platega / $srok;

  echo  number_format(floatval($price), 0, '.', ' ') . ' ₽';
  echo $buf;
}



$err = '';
$sum = do_c($_POST['sum']);
$perv = do_c($_POST['perv']);
$sum = $sum - $perv;

$proc = do_c($_POST['proc']);

$srok = abs(12 * intval($_POST['srok']));


if (!$sum || !$srok || !$srok || !$srok)
  $err .= 'Заполни всю форму</br>';

if ($perv > $sum)
  $err .= 'Первоначальный взнос не может быть больше стоимости недвижимости';

if ($err)
  echo $err;
else {
  m_an($sum, $proc, $srok);
}
