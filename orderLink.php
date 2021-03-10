<?php
date_default_timezone_set('America/Sao_Paulo');
define('IdCielo', 'pedir para cielo');
//receber dados post do formulario de solicitacao de pedido
$customer_ordernumber =    intval((isset($_POST['customer_order'])) ? $_POST['customer_order'] : '');
$customer_date =           (isset($_POST['customer_date'])) ? $_POST['customer_date'] : '';
$customer_hour =           (isset($_POST['customer_hour'])) ? $_POST['customer_hour'] : '';
$customer_from =           (isset($_POST['customer_origin'])) ? $_POST['customer_origin'] : '';
$customer_from_num =       (isset($_POST['customer_origin_num'])) ? $_POST['customer_origin_num'] : '';
$customer_to =             (isset($_POST['customer_to'])) ? $_POST['customer_to'] : '';
$customer_to_num =         (isset($_POST['customer_to_num'])) ? $_POST['customer_to_num'] : '';
$valor =                   (isset($_POST['customer_price'])) ? $_POST['customer_price'] : '';
$customer_identity =       (isset($_POST['customer_identity'])) ? $_POST['customer_identity'] : '';
$customer_name =           (isset($_POST['customer_name'])) ? $_POST['customer_name'] : '';
$customer_email =          (isset($_POST['customer_email'])) ? $_POST['customer_email'] : '';
$customer_phone =          (isset($_POST['customer_phone'])) ? $_POST['customer_phone'] : '';
$customer_id =             (isset($_POST['bge_idcustomerInput'])) ? $_POST['bge_idcustomerInput'] : '';
//verificando preenchimento dos campos antes de enviar para Cielo
$valor = str_replace(',', '.', $valor);
if (
   empty($customer_ordernumber) ||
   empty($customer_date) ||
   empty($customer_hour) ||
   empty($valor) ||
   empty($customer_identity) ||
   empty($customer_phone) ||
   empty($customer_email) ||
   empty($customer_name) ||
   empty($customer_from) ||
   empty($customer_id) ||
   empty($customer_to)
) :
   $campos = 1;
else :
   $campos = 0;
endif;
if ($campos <= 0) :
   //configurando dados para envio para Cielo
   $service_name = "Origem: " . $customer_from . "  " . $customer_from_num . "|" . "Destino: " . $customer_to . "  " . $customer_to_num;
   $service_price = intval(preg_replace("/[^0-9]/", "", $valor));
   $data = array(
      "orderNumber" => $customer_ordernumber,
      "SoftDescriptor" => "TAXIGUARUCOOP",
      "Cart" => array(
         "Items" => [array(
            "Name" => $service_name,
            "Description" => $service_name,
            "UnitPrice" => $service_price,
            "Quantity" => 1,
            "Type" => "Payment",
            "Sku" => $customer_ordernumber,
         )]
      ),
      "Shipping" => array(
         "name" => "Serviço de Transporte",
         "Type" => "WithoutShipping",
      ),
      "Payment" => array(
         "Installments" => null,
         "MaxNumberOfInstallments" => null
      ),
      "Customer" => array(
         "Identity" => $customer_identity,
         "FullName" => $customer_name,
         "Email" => $customer_email,
         "Phone" => $customer_phone
      ),
      "Options" => array(
         "AntifraudEnabled" => true,
         "ReturnUrl" => "https://www.guarucoop.com.br"
      ),
      "Settings" => null
   );
   $data_order_link = json_encode($data);
   //construindo envio para Cielo
   $curl = curl_init('https://cieloecommerce.cielo.com.br/api/public/v1/orders');
   curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($curl, CURLOPT_POSTFIELDS, $data_order_link);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'MerchantId:pedir para cielo'));
   curl_setopt($curl, CURLOPT_POST, true);
   $response = curl_exec($curl);
   curl_close($curl);
   //recebendo a resposta da Cielo e enviando para o formOrder.php
   $res = json_decode($response, true);
   if ($res['merchantId'] == IdCielo) :
      //Gravar DB com o weblink   
      require 'conexao.php';
      $conexao = conexao::getInstance();
      $datacreate = date('Y-m-d');
      $timecreate = date('H:i:s');
      $sql = 'INSERT INTO tab_order (id_pedido,id_customer,price,book_date,book_time,addr_from,addr_to,num_from,num_to,link_url,date_create,time_create) 
                              VALUES(:id_pedido,:id_customer,:price,:book_date,:book_time,:addr_from,:addr_to,:num_from,:num_to,:link_url,:datecreate,:timecreate)';
      try {
         $stm = $conexao->prepare($sql);
         $stm->bindValue(':id_pedido', $customer_ordernumber);
         $stm->bindValue(':id_customer', $customer_id);
         $stm->bindValue(':price', $valor);
         $stm->bindValue(':book_date', $customer_date);
         $stm->bindValue(':book_time', $customer_hour);
         $stm->bindValue(':addr_from', $customer_from);
         $stm->bindValue(':addr_to', $customer_to);
         $stm->bindValue(':num_from', $customer_from_num);
         $stm->bindValue(':num_to', $customer_to_num);
         $stm->bindValue(':link_url', $res['settings']['checkoutUrl']);
         $stm->bindValue(':datecreate', $datacreate);
         $stm->bindValue(':timecreate', $timecreate);
         $retorno = $stm->execute();
      } catch (PDOException $e) {
         // Se ocorrer algum erro na conexão         
         $resultado = json_encode(["stat" => "false", "msg" => "ERROR:" . $e->getMessage()]);
         print_r($resultado);
         die($e->getMessage());
      }
      $resultado = json_encode(["stat" => "true", "msg" => "WEBLINK GERADO...", "orderUrl" => "<a class='orderUrl' id='copyurl' href='" . $res['settings']['checkoutUrl'] . "'>LinkURL</a><p>" . $res['settings']['checkoutUrl'] . "</p>"]);
      print_r($resultado);
   else :
      $resultado = json_encode(["stat" => "false", "msg" => "ERRO!!! Código de Cadastro da CIELO com erro!!!"]);
      print_r($resultado);
   endif;
//Verificar erro de comunicação e recuperar valores de retorno da API Cielo
else :
   $resultado = json_encode(["stat" => "false", "msg" => "ERRO!!! WEBLINK NÃO FOI GERADO...HÁ CAMPOS EM BRANCO - VERIFIQUE!!!"]);
   print_r($resultado);
endif;