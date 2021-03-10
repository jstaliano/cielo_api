<!--  a:link {
        text-decoration: none;
        color: white;
    }

    a:visited {
        text-decoration: none;
        color: white;
    }

    a:hover {
        text-decoration: none;
        color: white;
    }

    a:active {
        text-decoration: none;
        color: white;
    }-->
<?php
date_default_timezone_set('America/Sao_Paulo');

/*$pedido = 2021030001;
$origem = 'Av. Paulista, 1502, Jardins, SP';
$destino = 'Aeroporto Internacional de São Paulo Guarulhos/SP GRU Airport';
$valor = 150.06;
$email = 'julio.staliano@guarucoop.com.br';
$emailAdd = 'fjoelma912@gmail.com';*/
//Recebe dados via POST

$op = $_POST['oper'];

if ($op == 1) :
    $linkUrl =              $_POST['modalForm_link'];
    $customer_ordernumber = $_POST['customer_order'];
    $customer_date =        $_POST['customer_date'];
    $customer_hour =        $_POST['customer_hour'];
    $customer_from =        $_POST['customer_origin'];
    $customer_from_num =    $_POST['customer_origin_num'];
    $customer_to =          $_POST['customer_to'];
    $customer_to_num =      $_POST['customer_to_num'];
    $valor =                $_POST['customer_price'];
    $customer_identity =    $_POST['customer_identity'];
    $customer_name =        $_POST['customer_name'];
    $customer_email =       $_POST['customer_email'];
    $customer_phone =       $_POST['customer_phone'];
    $customer_id =          $_POST['bge_idcustomerInput'];
elseif ($op == 2) :
    //$resultado = json_encode(["stat" => "true", "msg" => "Pedido Chegou!"]);
    //print_r($resultado);
    include 'conexao.php';
    $singleOrder = $_POST['customer_order'];
    $conexao = conexao::getInstance();
    $sql = 'SELECT id_customer,id_order,id_pedido,price,book_date,book_time,addr_from,addr_to,num_from,num_to,link_url FROM tab_order WHERE id_pedido=:idpedido';
    try {
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':idpedido', $singleOrder);
        $stm->execute();
        $retorno = $stm->fetch(PDO::FETCH_OBJ);
        $customer = $retorno->id_customer;
        $linkUrl = $retorno->link_url;
        $customer_ordernumber = $retorno->id_pedido;
        $customer_date = $retorno->book_date;
        $customer_hour = $retorno->book_time;
        $customer_from = $retorno->addr_from;
        $customer_from_num = $retorno->addr_from_num;
        $customer_to = $retorno->addr_to;
        $customer_to_num = $retorno->addr_to_num;
        $valor = $retorno->price;
        //busca email do cliente
        $sqlCustomer = 'SELECT email FROM tab_customer where id_customer=:idcustomer';
        try {
            $stmcustomer = $conexao->prepare($sqlCustomer);
            $stmcustomer->bindValue(':idcustomer', $customer);
            $stmcustomer->execute();
            $retornoCustomer = $stmcustomer->fetch(PDO::FETCH_OBJ);
            $customer_email = $retornoCustomer->email;
        } catch (PDOException $e) {
            //echo 'Algum Erro<br>';
            //echo 'ERROR: ' . $e->getMessage();
            die($e->getMessage());
        }
    } catch (PDOException $e) {
        //echo 'Algum Erro<br>';
        //echo 'ERROR: ' . $e->getMessage();
        die($e->getMessage());
    }
endif;

$dataForm = date('d/m/Y', strtotime($customer_date));
$horaForm = $customer_hour;

//configurando PHP Mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
$mail = new PHPMailer(true);
$mail->isSMTP();
//$mail->SMTPDebug = 0;
$mail->setLanguage('pt_br');
$mail->CharSet    = 'UTF-8';
$mail->Host       = 'host';
$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
$mail->Username   = 'user';                     // SMTP username
$mail->Password   = 'pass';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port       = 587;
$mail->setFrom('email', 'Central de Atendimento Guarucoop');
$mail->addAddress($customer_email);
$mail->addReplyTo($customer_email);
$mail->Subject = 'Central de Atendimento Guarucoop';
$mail->AddEmbeddedImage('./img/guarucooppadrao60.jpg', 'logo', 'guarucooppadrao.jpg');

//montagem do html do corpo do email

$msg = '';
$msg = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Atendimento Guarucoop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style type="text/css">
    .ExternalClass {
        width: 100%;
    }

    .ExternalClass,
    .ExternalClass td {
        line-height: 100%;
    }

    table {
        border-collapse: collapse;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 20px;
    }

    table .main {
        background-color: rgba(0, 191, 255, .05);
    }

    table td {
        border-collapse: collapse;
    }

    td {
        margin: 2px;
        padding: 10px;
        border: none;
    }

    td .tdContentFirst {
        padding-left: 15px;
        border-width: 2px;
        border-color: black;
        /*border-style: solid;*/
        display: inline-block;
        border-top-left-radius: 30px;
        border-top-right-radius: 30px;
        background-color: rgba(255, 255, 255, .90);

    }

    td .tdContentSecond {
        padding-left: 15px;
        border-width: 2px;
        /*border-color: black;        */
        color: gray;
        display: inline-block;
        border-bottom-left-radius: 30px;
        border-bottom-right-radius: 30px;
        background-color: rgba(255, 255, 0, .2);
    }

    td .titulo {
        display: inline-flexbox;
        margin: 10px;
        padding: 10px;
        font-size: 40px;
        background-color: white;
        align-items: center;
        color: white;
        line-height: 35px;
    }

   
    </style>
</head>

<body>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width="auto">
                <!--Content wrapper-->
                <table class="main" width="auto" cellpadding="0" cellspacing="0" border="1" align="center" class="content-wrap">
                    <tr>
                        <td class="titulo" width="100%" align="center">
                            <!--<img src="./img/logopinheiro.png" width="50px" height="50px" alt="Logotipo" srcset=""> -->
                            <img src="cid:logo" width="250px" height="40px" alt="Logotipo" srcset="">
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <table class="conteudo" width="auto" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="font-weight:900; font-size:40px; color:navy">Importante!</td>
                                </tr>
                                <tr class="cotentLine">
                                    <td class="tdContentFirst" align="justify" width="90%" style="color:black;">
                                        <!-- Content -->
                                        <p>Prezado(a) cliente,</p>
                                        <p>Obrigado(a) por escolher a GUARUCOOP.</p>
                                        <p>Segue abaixo o seu WebLink de pagamento do serviço
                                            solicitado.</p>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="tdContentSecond" width=90%>
                                        <p align="center" style="font-weight:900;">Detalhes da sua reserva:</p>
                                        <label>N° Pedido: <strong>';
$msg .= $customer_ordernumber;
$msg .= '
</strong></label><br>
<label>Data: <strong>';
$msg .= $dataForm;
$msg .= '</strong>-Hora: <strong>';
$msg .= $horaForm;
$msg .= '</strong></label><br>
<label>Origem/Embarque: <strong>';
$msg .= $customer_from;
$msg .= '</strong></label><br>
<label>Destino/Desembarque: <strong>';
$msg .= $customer_to;
$msg .= '</label><br>
<p>Valor R$: <strong>';
$msg .= $valor;
$msg .= '</label>
<hr />
<p style="font-size:18px;text-align:center;">Clique no botão para continuar com o Agendamento / Reserva</p>
<p align="center"><button style="background-color:navy;color:white;"><a style="font-size:20px;" href=';
$msg .= $linkUrl;
$msg .= '>Efetuar Pagamento!</a></button></p>
<p style="font-weight:400;font-size:16px;text-align:center;">Após a confirmação do pagamento, enviaremos o seu Voucher.</p>
</td>
</tr>
</table>
</td>
</tr>
<tr style="height:50px;background-color:white;">
    <td width="100%" align="center">
        <img src="cid:logo" width="150px" height="20px" alt="Logotipo" srcset="">
    </td>
<tr>
    <td style="width:auto;text-align:center;font-size:17px;color:gray;">
        <span style="padding:0px;">Rua Vitor Costa, 510 - Jardim Paraventi </span>
        <span>Guarulhos/SP - CEP: 07123-010</span>
        <p>CNPJ: 52.378.239/0001-01</p>
        <p><a href="http://www.guarucoop.com.br">http://www.guarucoop.com.br</a></p>
    </td>
</tr>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>';
//enviando email
$mail->isHTML(true);
$mail->Body    = $msg;
if (!$mail->Send()) :
    //echo "Ops! Algo deu errado!";
    //echo "Mailer Error: " . $mail->ErrorInfo;
    $resultado = json_encode(["stat" => "false", "msg" => $mail->ErrorInfo]);
    //print_r($resultado);
    exit;
else :
    $resultado = json_encode(["stat" => "true", "msg" => "Pedido:" . $customer_ordernumber . " - E-Mail Enviado!"]);
//print_r($resultado);
endif;
//$resultado = json_encode(["stat" => "true", "msg" => "ok!"]);
print_r($resultado);

?>