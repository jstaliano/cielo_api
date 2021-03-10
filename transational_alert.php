<?php
date_default_timezone_set('America/Sao_Paulo');
define('IdCielo', '<pedir na cielo');
//log de requisições POST
/*$flipped = serialize($_POST);
$arquivo = fopen('log.txt', 'a', 0);
$texto = date('d/m/Y H:i:s') . '=>' . $flipped . chr(13) . chr(10);
fwrite($arquivo, $texto, strlen($texto));
fclose($arquivo);*/
require 'conexao.php';
$cielourl = (isset($_POST['Url'])) ? $_POST['Url'] : '';
$MerchantId = (isset($_POST['MerchantId'])) ? $_POST['MerchantId'] : '';
$MerchantOrderNumber = (isset($_POST['MerchantOrderNumber'])) ? $_POST['MerchantOrderNumber'] : '';
if ($MerchantId <> IdCielo) :
	$errMerchant = 'MerchantId Incorreto';
	echo 'MerchantId Incorreto';
//exit;
else :
	$errMerchant = 'MerchantId CORRETO';
	$datareceived = date('Y-m-d');
	$timereceived = date('H:i:s');
	$conexao = conexao::getInstance();
	$sql = 'INSERT INTO tab_notificacao_cielo (cielo_url,merchantid,merchantordernumber,date_received,time_received) VALUES(:cielourl,:merchantid,:merchantordernumber,:datereceived,:timereceived)';
	try {
		$stm = $conexao->prepare($sql);
		$stm->bindValue(':cielourl', $cielourl);
		$stm->bindValue(':merchantid', $MerchantId);
		$stm->bindValue(':merchantordernumber', $MerchantOrderNumber);
		$stm->bindValue(':datereceived', $datareceived);
		$stm->bindValue(':timereceived', $timereceived);
		$retorno = $stm->execute();
	} catch (PDOException $e) {
		// Se ocorrer algum erro na conexão
		echo 'ERROR: ' . $e->getMessage();
		die($e->getMessage());
	}
	if ($retorno) :
		echo "True";
	else :
		echo "False";
	endif;
endif;
$flipped = serialize($_POST);
$arquivo = fopen('log.txt', 'a', 0);
$texto = date('d/m/Y H:i:s') . ' - ' . $errMerchant . ' => ' . $flipped . chr(13) . chr(10);
fwrite($arquivo, $texto, strlen($texto));
fclose($arquivo);