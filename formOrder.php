<?php
date_default_timezone_set('America/Sao_Paulo');
//buscar próximo código do pedido
require_once('findOrderFunc.php');
$returnFindOrder = json_decode(findOrder());
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
</head>

<body>
    <?php include "mainNavbar.php"; ?>
    <div class="alert" id="alert" role="alert"></div>
    <!-- Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="orderModalLabel">Confirmação de Envio de WebLink Cielo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="formOrderModal">
                    <div class="modal-body">
                        <div class="row g-2" id="divCopy">
                            <label for="modalForm_link">Web Link</label>
                            <div class="col-12">
                                <div class="input-group mb-3">
                                    <input class="btn btn-success" type="button" id="modalForm_button" value="Copiar" onclick="copyToClipboard()"></input>
                                    <input type="text" id="modalForm_link" name="modalForm_link" class="form-control" placeholder="" aria-label="Example text with button addon"
                                        aria-describedby="button-addon1">
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-3">
                                <label for="modalForm_order">N° Pedido</label>
                                <input disabled type="text" class="form-control alert-danger" aria-label="modalForm_order" name="modalForm_order" id="modalForm_order">
                            </div>
                            <div class="col-3">
                                <label for="modalForm_data">Data Agendada</label>
                                <input disabled type="date" class="form-control alert-success" placeholder="Data" aria-label="modalForm_date" name="modalForm_date" id="modalForm_date" required
                                    autofocus>
                            </div>
                            <div class="col-3">
                                <label for="modalForm_hora">Hora Agendada</label>
                                <input disabled type="time" class="form-control alert-success" placeholder="Hora" aria-label="modalForm_hour" name="modalForm_hour" id="modalForm_hour" required>
                            </div>
                            <div class="col-3">
                                <label for="modalForm_price">Valor da Corrida </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="bge_price">R$</span>
                                    <input disabled type="currency" aria-describedby="bge_price" class="form-control alert-success" placeholder="Valor" aria-label="modalForm_price"
                                        name="modalForm_price" id="modalForm_price" required>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-3">
                                <label for="modalForm_identity">CPF/CNPJ</label>
                                <input disabled type="number" class="form-control" placeholder="CPF/CNPJ" aria-label="modalForm_identity" name="modalForm_identity" id="modalForm_identity">
                            </div>
                            <div class="col-3">
                                <label for="modalForm_phone">Telefone</label>
                                <input disabled type="number" class="form-control" placeholder="Telefone" aria-label="modalForm_phone" name="modalForm_phone" id="modalForm_phone">
                            </div>
                            <div class="col-6">

                                <label for="modalForm_email">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="bge_email">@</span>
                                    <input disabled type="email" aria-describedby="bge_email" class="form-control" placeholder="E-mail" aria-label="modalForm_email" name="modalForm_email"
                                        id="modalForm_email" required>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2">
                            <label for="modalForm_name">Nome</label>
                            <div class="col-12">
                                <input disabled type="text" class="form-control" placeholder="Nome do Cliente" aria-label="modalForm_name" name="modalForm_name" id="modalForm_name" required>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-10">
                                <label for="modalForm_origin">Local de Embarque [Origem]</label>
                                <input disabled type="text" class="form-control" placeholder="Local de Origem" aria-label="modalForm_origin" name="modalForm_origin" id="modalForm_origin">
                            </div>
                            <div class="col-2">
                                <label for="modalForm_origin_num">Número</label>
                                <input disabled type="text" class="form-control" placeholder="Número" aria-label="modalForm_origin_num" name="modalForm_origin_num" id="modalForm_origin_num">
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-10">
                                <label for="modalForm_origin">Local de Desembarque [Destino]</label>
                                <input disabled type="text" class="form-control" placeholder="Local de Destino" aria-label="modalForm_to" name="modalForm_to" id="modalForm_to" required>
                            </div>
                            <div class="col-2">
                                <label for="modalForm_origin_num">Número</label>
                                <input disabled type="text" class="form-control" placeholder="Número" aria-label="modalForm_to_num" name="modalForm_to_num" id="modalForm_to_num">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <?= $returnFindOrder->idOrder ?>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary" id="modalMail" value="Enviar E-Mail" onclick="sEmail(<?= $returnFindOrder->idOrder ?>)">Enviar E-Mail</button>
                        <button type="button" id="formButtonWhats" onclick="execWhats()" class="btn btn-light"><img src="./img/WhatsApp1.png" alt="" width="35px" height="35px"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Form Principal -->
    <div class="container">
        <div class="row">
            <div class="col">
                <h2 class="bg-primary text-light text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-cloud-plus" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5z" />
                        <path
                            d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
                    </svg>
                    Cadastrar WebLink Cielo
                </h2>
                <form action="" method="post" id="createOrder">
                    <div class="row g-3">
                        <div class="col-3">
                            <label for="customer_order">N° Pedido</label>
                            <input type="text" class="form-control alert-danger" aria-label="customer_order" name="customer_order" id="customer_order" value="<?= $returnFindOrder->idOrder ?>"
                                readonly>
                        </div>
                        <div class="col-3">
                            <label for="customer_data">Data Agendada</label>
                            <input type="date" class="form-control alert-success" placeholder="Data" aria-label="customer_date" name="customer_date" id="customer_date" required autofocus>
                        </div>
                        <div class="col-3">
                            <label for="customer_hora">Hora Agendada</label>
                            <input type="time" class="form-control alert-success" placeholder="Hora" aria-label="customer_hour" name="customer_hour" id="customer_hour" required>
                        </div>
                        <div class="col-3">
                            <label for="customer_price">Valor da Corrida </label>
                            <div class="input-group">
                                <span class="input-group-text" id="bge_price">R$</span>
                                <input type="currency" aria-describedby="bge_price" class="form-control alert-success" placeholder="Valor" aria-label="customer_price" name="customer_price"
                                    id="customer_price" required>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-3">
                            <label for="customer_identity">CPF/CNPJ</label>
                            <div class="input-group">
                                <span class="input-group-text" id="bge_idcustomer">--</span>
                                <input type="hidden" class="form-control" name="bge_idcustomerInput" id="bge_idcustomerInput">
                                <input type="number" class="form-control" placeholder="CPF/CNPJ" aria-label="customer_identity" name="customer_identity" id="customer_identity"
                                    onBlur="customerSearch()">
                            </div>
                        </div>
                        <div class=" col-3">
                            <label for="customer_phone">Telefone</label>
                            <input type="number" class="form-control" placeholder="Telefone" aria-label="customer_phone" name="customer_phone" id="customer_phone">
                        </div>
                        <div class="col-6">

                            <label for="customer_email">Email</label>
                            <div class="input-group">
                                <span class="input-group-text" id="bge_email">@</span>
                                <input type="email" aria-describedby="bge_email" class="form-control" placeholder="E-mail" aria-label="customer_email" name="customer_email" id="customer_email"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2">
                        <label for="customer_name">Nome</label>
                        <div class="col-12">
                            <input type="text" class="form-control" placeholder="Nome do Cliente" aria-label="customer_name" name="customer_name" id="customer_name" onblur="customerRecord()">
                        </div>
                    </div>
                    <div class=" row g-2">
                        <div class="col-10">
                            <label for="customer_origin">Local de Embarque [Origem]</label>
                            <input type="text" class="form-control" placeholder="Local de Origem" aria-label="customer_origin" name="customer_origin" id="customer_origin">
                        </div>
                        <div class="col-2">
                            <label for="customer_origin_num">Número</label>
                            <input type="text" class="form-control" placeholder="Número" aria-label="customer_origin_num" name="customer_origin_num" id="customer_origin_num">
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-10">
                            <label for="customer_origin">Local de Desembarque [Destino]</label>
                            <input type="text" class="form-control" placeholder="Local de Destino" aria-label="customer_to" name="customer_to" id="customer_to" required>
                        </div>
                        <div class="col-2">
                            <label for="customer_origin_num">Número</label>
                            <input type="text" class="form-control" placeholder="Número" aria-label="customer_to_num" name="customer_to_num" id="customer_to_num">
                        </div>
                    </div>
                    <div class="row g-3" id="buttonDiv">
                        <div class="col-12">
                            <a href="javascript:history.back()" class="btn btn-danger p-3 m-2">Cancelar</a>
                            <input type="button" value="Gerar WebLink" name="submitOrder" id="submitOrder" class="btn btn-success p-3 m-2" onclick="sendOrder()"></input>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0"
        crossorigin="anonymous">
    </script>
    <script src="./js/func_formOrder.js?<?php echo date('Y-m-d_H:i:s'); ?>"></script>
</body>

</html>