<?php
date_default_timezone_set('America/Sao_Paulo');
//busca todoas ordens
require 'conexao.php';
$conexao = conexao::getInstance();
$sql = 'SELECT tab_order.id_order,tab_order.order_status,tab_order.id_pedido,tab_customer.cname,tab_order.price,tab_order.book_date,tab_order.book_time,tab_order.addr_from,tab_order.addr_to,tab_order.num_from,tab_order.num_to,tab_order.link_url 
FROM tab_order INNER JOIN tab_customer ON tab_order.id_customer = tab_customer.id_customer 
WHERE tab_order.order_status>=1 
ORDER BY tab_order.date_create DESC ';
try {
    //    $status = 1;
    $stm = $conexao->prepare($sql);
    //  $stm->bindValue(':statuss', $status);
    $stm->execute();
    $orders = $stm->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    // Se ocorrer algum erro na conexão         
    $ordersFail = json_encode(["stat" => "false", "msg" => "ERROR:" . $e->getMessage()]);
    print_r($ordersFail);
    die($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <link rel="icon" href="../../img/desenho logo favicon.ico">
    <title>Sol. Link de Pagamnento</title>
    <!--SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" integrity="D515CB16-F462-43A7-BD48-E92FC82B14A3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.css" />
</head>

<body>
    <?php include "mainNavbar.php"; ?>
    <div class="alert" id="alert" role="alert"></div>
    <div class="container-fluid">
        <section>MENU</section>
        <section class='mt-1'>
            <table class="table table-striped table-sm" id="tableList">
                <thead>
                    <tr>
                        <th class='text-center'>STATUS</th>
                        <th># PEDIDO</th>
                        <th>NOME CLIENTE</th>
                        <th>AGENDAMENTO</th>
                        <th>LOCAL DE ORGIEM</th>
                        <th>LOCAL DE DESTINO</th>
                        <th>ALTERAR</th>
                        <th class='text-center'>E-MAIL</th>
                        <th class='text-center'>COMANDOS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($orders as $orderOne) { ?>
                    <tr>
                        <?php
                            if ($orderOne->order_status == 1) :
                                $classe = ' btn-primary ';
                                $estilo = ' ';
                            elseif ($orderOne->order_status == 2) :
                                $classe = ' btn-info ';
                                $estilo = ' ';
                            elseif ($orderOne->order_status == 3) :
                                $classe = ' btn-success ';
                                $estilo = ' ';
                            elseif ($orderOne->order_status == 4) :
                                $classe = ' ';
                                $estilo = ' style="background-color:#5F04B4;" ';
                            elseif ($orderOne->order_status == 5) :
                                $classe = ' btn-danger ';
                                $estilo = ' ';
                            endif;
                            ?>
                        <td class="text-center"><button class="btn<?= $classe ?>text-white" <?= $estilo ?>><i class="fas fa-traffic-light"></i></button></td>
                        <td><strong>
                                <?= $orderOne->id_pedido ?>
                            </strong></td>
                        <td>
                            <?= $orderOne->cname ?>
                        </td>
                        <td>
                            <?= date('d/m/Y', strtotime($orderOne->book_date)) . " - " . $orderOne->book_time ?>
                        </td>
                        <td>
                            <?= $orderOne->addr_from . ", " . $orderOne->addr_from_num ?>
                        </td>
                        <td>
                            <?= $orderOne->addr_to . ", " . $orderOne->addr_to_num ?>
                        </td>
                        <td class="text-center"><button class="btn btn-warning text-white" title="Alterar&nbsp;<?= $orderOne->id_pedido ?>"><i class="fas fa-th-large"></i></button></td>
                        <td class="text-center"><button class="btn btn-secondary text-white sendEmail" id="btnEmail" title="Enviar E-Mail para:&nbsp;<?= $orderOne->id_pedido ?>"
                                onclick="sendEmail(<?= $orderOne->id_pedido ?>)"><i class="fas fa-share"></i></button></td>
                        <td class="text-center">
                            <button class="btn btn-warning text-white" title="Verificar Pagamento:&nbsp;<?= $orderOne->id_pedido ?>" onclick="verifyOrder(<?= $orderOne->id_pedido ?>)"><i
                                    class=" fas fa-receipt"></i></button>
                            <button class="btn<?= $classe ?>text-white" <?= $estilo ?>><i class="fas fa-sitemap"></i></button>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>STATUS</th>
                        <th># PEDIDO</th>
                        <th>NOME CLIENTE</th>
                        <th>AGENDAMENTO</th>
                        <th>LOCAL DE ORGIEM</th>
                        <th>LOCAL DE DESTINO</th>
                        <th>ALTERAR</th>
                        <th>E-MAIL</th>
                        <th>COMANDOS</th>
                    </tr>
                </tfoot>
            </table>
        </section>

    </div>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.23/datatables.min.js"></script>
    <script>
    $(document).ready(function() {
        $('table').DataTable()
    });

    function sendEmail(singleOrder) {
        //console.log(singleOrder)
        let data = "customer_order=" + singleOrder + "&oper=2"
        console.log(data)
        $.ajax({
            url: 'emailLink.php',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(result) {
                if (result.stat == 'false') {
                    $('#alert').trigger("reset");
                    $('#alert').addClass("alert-danger");
                    $('#alert').fadeIn().html(result.msg);
                } else {
                    $('#alert').trigger("reset");
                    $('#alert').removeClass('alert-danger')
                    $('#alert').addClass("alert-primary");
                    $('#alert').fadeIn().html(result.msg);
                }
                setTimeout(function() {
                    $('#alert').fadeOut('Slow');
                    $('#alert').removeClass('alert-danger')
                    $('#alert').removeClass('alert-primary')
                    $('#alert').removeClass('alert-success')
                }, 3000)
            }
        })
    }
    </script>
</body>

</html>