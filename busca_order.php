<?php
include 'access.php';


//RECEBE ID_PEDIDO VIA POST
$ordernumber = $_GET['order'];

//FAZER BUSCA DO ID_PEDIDO PARA DECIDIR SE BUSCA NA API CIELO OU BANCO DE DADOS O RESULTADO DA TRANSAÇÃO CASO EXISTA

//CASO AINDA NÃO FOI FEITA A BUSCA PELAS INFORMAÇÕES DA TRANSAÇÃO INICIAR COMUNICAÇÃO COM API CIELO
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://cieloecommerce.cielo.com.br/api/public/v2/merchantOrderNumber/' . $ordernumber,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer ' . $res['access_token'],
    'MerchantId: <pedir na cielo>'
  ),
));
//RECEBE CHECKOUTORDERNUMBER
$response = curl_exec($curl);
curl_close($curl);
$res = json_decode($response, true);
if (!$res) :
  // RETURN PARA QUANDO O CLIENTE NÃO PAGOU    
  exit;
endif;
$tam_res = count($res);
for ($it = 0; $it < $tam_res; $it++) {
  $cielo_ordernumber[$it] = $res[$it]['checkoutOrderNumber'];
}
//RECEBE A QUANTIDADE DE PAGAMENTOS NUMA MESMA ORDEM

// ININICIA A BUSCA PELAS VENDAS EXISTENTES DA MESMA ORDEM
for ($i = 0; $i < count($cielo_ordernumber); $i++) {
  include 'access.php';
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://cieloecommerce.cielo.com.br/api/public/v2/orders/' . $cielo_ordernumber[$i],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'Authorization: Bearer ' . $res['access_token'],
      'MerchantId: <pedir na cielo>'
    ),
  ));
  $httpResp = http_response_code();
  //verifica se houve erro de conexão HTTP com servidor da Cielo  
  $response_data = curl_exec($curl);
  curl_close($curl);
  //variavel com a resposta da Cielo
  //print_r($response_data);
  //Gravar dados na tabela tab_transaction_cielo
  /*campos
      id_transaction
      id_pedido
      doc_nsu
      autorization
      status_payment
      code_payment
      tid
      cielo_order_number
      descriptionfraude
      cardmasknumber
      brand
      type
      antifraudresult
      id_notificacao
      date_received
      time_received
      obs
  */
  $order_data = json_decode($response_data, true);
  echo '<br>*****response json data************<br>';
  print_r($order_data);
  echo '<br>';
  $status_payment = $order_data['payment']['status'];
  echo '<br>STATUS DO PAGAMENTO.:' . $status_payment . '<br> <hr \>';
  if ($status_payment == 'Paid') :
    $nsu_cielo = $order_data['payment']['nsu'];
    $aut_cielo = $order_data['payment']['authorizationCode'];
    echo '<br><br>NSU.:' . $nsu_cielo . '<br>Autorização.:' . $aut_cielo;
  else :
    $nsu_cielo = $order_data['payment']['nsu'];
    echo '<br><br>NSU.:' . $nsu_cielo . ' Autorização.:NEGADA';
  endif;
}