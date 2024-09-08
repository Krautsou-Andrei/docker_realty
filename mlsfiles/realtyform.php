<?php

$type = $_GET['type'];
$where = '/' . $type . '/?';

   if(isset($_GET['city'])&&$_GET['city']!='')
	   switch ($_GET['city']){
		   case 'Краснодар':$where .= '&city=2301';break;
		   case 'Новороссийск':$where .= '&city=2306';break;
	   }
	   
   if(isset($_GET['area'])&&$_GET['area']!='')
	   switch ($_GET['area']){
		   case 'до 35':$where .= '&area_do=35';break;
		   case '35-55':$where .= '&area_ot=35&area_do=55';break;
		   case 'больше 55':$where .= '&area_ot=55';break;
	   }	

   if(isset($_GET['area'])&&$_GET['area']!='')
	   switch ($_GET['area']){
		   case 'до 35':$where .= '&area_do=35';break;
		   case '35-55':$where .= '&area_ot=35&area_do=55';break;
		   case 'больше 55':$where .= '&area_ot=55';break;
	   }	
	   

   if(isset($_GET['rooms'])&&$_GET['rooms']!='') $where .= '&rooms[]='.$_GET['rooms'];
   if(isset($_GET['price_from'])&&$_GET['price_from']!='') $where .= '&price_ot='.$_GET['price_from'];
   if(isset($_GET['price_to'])&&$_GET['price_to']!='') $where .= '&price_do='.$_GET['price_to'];
   
   if($where == '/kvartiry/?') $where = '/kvartiry/';
   
   if($where == '/novostrojki/?')$where = '/novostrojki/';

   header('Location: '.$where);