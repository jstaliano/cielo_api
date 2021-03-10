<?php

//use Mpdf\Tag\Pre;

//use function Psy\debug;

date_default_timezone_set('America/Sao_Paulo');
function findOrder()
{
    require 'conexao.php';
    $conexao = conexao::getInstance();
    //criando um id unico
    $status = 0;
    $datareceived = date('Y-m-d');
    $dateToday = date('Y-m-d');
    $timereceived = date('H:i:s');
    $ano = date('Y');
    $mes = date('m');
    $sql = 'SELECT id_order,date_create,id_pedido FROM tab_order WHERE date_create<:datetoday AND order_status=:statuss order by id_order DESC LIMIT 1';
    try {
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':datetoday', $dateToday);
        $stm->bindValue(':statuss', $status);
        $stm->execute();
        $retorno = $stm->fetch(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        echo 'Algum Erro<br>';
        echo 'ERROR: ' . $e->getMessage();
        die($e->getMessage());
    }
    if (!$retorno) :
        $sql = 'INSERT INTO tab_order (order_status,date_create,time_create) VALUES (:statuss,:datereceived,:timereceived)';
        //$sql = 'INSERT INTO tab_order (order_status) VALUES(:statuss)';
        try {
            $stmInc = $conexao->prepare($sql);
            $stmInc->bindValue(':statuss', $status);
            $stmInc->bindValue(':datereceived', $datareceived);
            $stmInc->bindValue(':timereceived', $timereceived);
            $stmInc->execute();
            $retorno = $conexao->lastInsertId();
        } catch (PDOException $e) {
            echo 'Algum Erro<br>';
            echo 'ERROR: ' . $e->getMessage();
            die($e->getMessage());
        }
        $sequencia = $retorno;
        $idOrder = intval($ano . $mes . str_pad($sequencia, 4, '0', STR_PAD_LEFT));
        $result_final = json_encode(["stat" => "true", "msg" => "Pedido Criado...", "idOrder" => $idOrder]);
    else :
        //$idOrder = $retorno->id_pedido;
        $sequencia = $retorno->id_order;
        //echo 'sequencia:' . $retorno->id_order . '<br>';
        $idOrder = intval($ano . $mes . str_pad($sequencia, 4, '0', STR_PAD_LEFT));
        //echo 'NEWidOrder:' . $idOrder . '<br>';
        $result_final = json_encode(["stat" => "true", "msg" => "Último pedido encontrado.", "idOrder" => $idOrder]);
    endif;
    return $result_final;
}
/*
ANTERIOR
//unset($stmInc);

    //buscando ultimo id criado
    /*
    $sql = 'SELECT id_order FROM tab_order ORDER BY id_order DESC LIMIT 1';
    try {
        $stm = $conexao->prepare($sql);
        $stm->execute();
        $retorno = $stm->fetch(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        // Se ocorrer algum erro na conexão
        echo 'ERROR: ' . $e->getMessage();
        die($e->getMessage());
    }
    //Cadastro LOCALIZADO ou NÃO LOCALIZADO;        
    if ($retorno) :

        $anoAtual = date('Y');
        $mesAtual = date('m');
        $ano = substr($retorno->id_pedido, 0, 4);
        $mes = substr($retorno->id_pedido, 4, 2);
        if (($anoAtual == $ano) && ($mesAtual == $mes)) :
            $sequencia = intval(substr($retorno->id_pedido, 6, 4)) + 1;
            $idOrder = intval($ano . $mes . str_pad($sequencia, 4, '0', STR_PAD_LEFT));
        elseif (($anoAtual <> $ano) && ($mesAtual <> $mes)) :
            $ano = $anoAtual;
            $mes = $mesAtual;
            $sequencia = 1;
            $idOrder = intval($ano . $mes . str_pad($sequencia, 4, '0', STR_PAD_LEFT));
        elseif ($mesAtual > $mes && $anoAtual == $anoAtual) :
            $mes = $mesAtual;
            $sequencia = 1;
            $idOrder = intval($ano . $mes . str_pad($sequencia, 4, '0', STR_PAD_LEFT));
        endif;
        //$idOrder = $retorno->id_pedido;
        $resultado = json_encode(["stat" => "true", "msg" => "Último pedido encontrado", "idOrder" => $idOrder]);
        return $resultado;
    else :
        $yearOrder = date('Y');
        $monthOrder = date('m');
        $sequencial_Number = '1';
        $newOrderNumber = intval($yearOrder . $monthOrder . str_pad($sequencial_Number, 4, '0', STR_PAD_LEFT));
        $resultado = json_encode(["stat" => "true", "msg" => "Não Existe Pedidos", "idOrder" => $newOrderNumber]);
        return $resultado;
    endif;
    */