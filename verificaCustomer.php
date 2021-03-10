<?php
date_default_timezone_set('America/Sao_Paulo');
require 'conexao.php';
$op = $_POST['op'];
$identity = $_POST['identity'];
$resultado = [];
if ($op == 0) :
    $conexao = conexao::getInstance();
    $sql = 'SELECT id_customer,cidentity,phone,email,cname FROM tab_customer where cidentity = :cidentity';
    try {
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':cidentity', $identity);
        $stm->execute();
        $retorno = $stm->fetch(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        // Se ocorrer algum erro na conexão
        echo 'ERROR: ' . $e->getMessage();
        die($e->getMessage());
    }
    //Cadastro LOCALIZADO ou NÃO LOCALIZADO;        
    if ($retorno) :
        $cid = $retorno->id_customer;
        $name = $retorno->cname;
        $phone = $retorno->phone;
        $email = $retorno->email;
        $resultado = json_encode(["stat" => "true", "msg" => "Cliente Encontrado. Preenchimento Automático...", "cname" => $name, "cphone" => $phone, "cemail" => $email, "cid" => str_pad($cid, 4, '0', STR_PAD_LEFT)]);
        print_r($resultado);
    else :
        //busca último resgistro para obter o próximo código de cliente
        $sql = 'SELECT id_customer FROM tab_customer ORDER BY id_customer DESC LIMIT 1';
        try {
            $stm = $conexao->prepare($sql);
            $stm->bindValue(':cidentity', $identity);
            $stm->execute();
            $retorno = $stm->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            // Se ocorrer algum erro na conexão
            echo 'ERROR: ' . $e->getMessage();
            die($e->getMessage());
        }
        $name = '';
        $phone = NULL;
        $email = '';
        $cid = 1 + $retorno->id_customer;
        $resultado = json_encode(["stat" => "false", "msg" => "Cliente Não Encontrado. Preenchimento Obrigatório para Salvar dados...", "cname" => $name, "cphone" => $phone, "cemail" => $email, "cid" => str_pad($cid, 4, '0', STR_PAD_LEFT)]);
        print_r($resultado);
    endif;
elseif ($op == 1) :
    $codcli = $_POST['codcli'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $datareceived = date('Y-m-d');
    $timereceived = date('H:i:s');
    $conexao = conexao::getInstance();
    $sql = 'INSERT INTO tab_customer (id_customer,cidentity,phone,email,cname,date_create,time_create) VALUES(:codcli,:cidentity,:phone,:email,:cname,:datereceived,:timereceived)';
    try {
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':codcli', $codcli);
        $stm->bindValue(':cidentity', $identity);
        $stm->bindValue(':phone', $phone);
        $stm->bindValue(':email', $email);
        $stm->bindValue(':cname', $name);
        $stm->bindValue(':datereceived', $datareceived);
        $stm->bindValue(':timereceived', $timereceived);
        $retorno = $stm->execute();
    } catch (PDOException $e) {
        // Se ocorrer algum erro na conexão
        //echo 'ERROR: ' . $e->getMessage();
        $resultado = json_encode(["stat" => "false", "msg" => "ERROR:" . $e->getMessage()]);
        print_r($resultado);
        die($e->getMessage());
    }
    if ($retorno) :
        $resultado = json_encode(["stat" => "true", "msg" => "Cliente Novo Cadastrado", "cname" => $name, "cphone" => $phone, "cemail" => $email, "cid" => str_pad($codcli, 4, '0', STR_PAD_LEFT)]);
        print_r($resultado);
    else :
        $resultado = json_encode(["stat" => "false", "msg" => "ERRO AO SALVAR REGISTRO!"]);
        print_r($resultado);
    endif;
endif;