<?php
//Vivaldo Sidanez Papa Neto
//papaneto@hotmail.com
//03-6-2017

#Define servidores que terão acesso - neste caso 
header('Access-Control-Allow-Origin: *');

#define tipo de dados que serão enviados e codificação
header('Content-Type: application/json; charset=utf8');

#chamando conexão com o banco de dados
require 'config/conexao.php';

#metodo utilizado para fazer a requisição
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        #busca usuario especifico
        if (isset($_GET["id"])) {
            $id = $_GET["id"];

            $sql = "SELECT * FROM usuario WHERE id = $id";
            #executa query
            $resultado = $conexao->query($sql);
            #verifica se deu erro na query
            if(!$resultado){
                http_response_code(400);
                echo json_encode(array("mensagem"=>"Este id não é valido."));
            }else{
                 #transforma para array associativo
                 $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);
                 #verifica se esta vazio
                if(count($usuarios) > 0){
                    #indica estado HTTP
                    http_response_code(200);
                    #imprime array convertido em json
                    echo json_encode($usuarios);
                }else{
                    http_response_code(404);
                    echo json_encode(array("mensagem"=>"Usuario não encontrado!"));
                }
            } 
            
        #busca usuarios
        } else {
            $sql = "SELECT * FROM usuario";
            #executa query
            $resultado = $conexao->query($sql);
            #transforma para array associativo
            $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);
            http_response_code(200);
            echo json_encode($usuarios);
        }
        break;
    case 'POST':
        if(count($_POST) > 0){
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $cpf = $_POST['cpf'];

            $sql = "INSERT INTO usuario (nome, email, cpf) VALUES ('$nome', '$email', '$cpf');";
            $resultado = $conexao->query($sql);
            if (!$resultado) {
                http_response_code(500);
                echo json_encode(array("Mensagem"=>"Não foi possivel cadastrar usuario."));
            } else {
                http_response_code(203);
                $novo_id = $conexao->insert_id;
                echo json_encode(array("Mensagem"=>"Usuario cadastrado."));
            }
            
        }else{
            http_response_code(400);
            echo json_encode(array("É necessario informar nome, cpf ou email."));
        }
        break;
    case 'PUT':
        parse_str(file_get_contents("php://input"), $post_vars);
        
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $sql = "SELECT * FROM usuario WHERE id = $id";
            $resultado = $conexao->query($sql);
            if (!$resultado) {
                http_response_code(500);
                echo json_encode(array("Mensagem"=>"Não foi possivel alterar usuario."));
            }else{
                if($resultado->num_rows == 0){
                    http_response_code(400);
                    echo json_encode(array("Mensagem"=>"Esse uauario não existe no banco de dados."));
                }else{
                    if(count($post_vars) > 0){
                        $nome = $post_vars['nome'];
                        $email = $post_vars['email'];
                        $cpf = $post_vars['cpf'];

                        $sql = "UPDATE usuario SET nome = '$nome', email = '$email', cpf = '$cpf' WHERE id = $id;";
                        $resultado = $conexao->query($sql);
                        if (!$resultado) {
                            http_response_code(500);
                            echo json_encode(array("Mensagem"=>"Não foi possivel alterar usuario."));
                        } else {
                            http_response_code(203);
                            $novo_id = $conexao->insert_id;
                            echo json_encode(array("Mensagem"=>"Usuario atualizado."));
                        }
                    }else{
                        http_response_code(400);
                        echo json_encode(array("É necessario informar nome, cpf ou email."));
                    }
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(array("É necessario informar ID do usuario."));            
        }
        
        break;
    case 'DELETE':
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $sql = "SELECT * FROM usuario WHERE id = $id";
            $resultado = $conexao->query($sql);
            if (!$resultado) {
                http_response_code(500);
                echo json_encode(array("Mensagem"=>"Não foi possivel deletar usuario."));
            }else{
                if($resultado->num_rows == 0){
                    http_response_code(400);
                    echo json_encode(array("Mensagem"=>"Esse uauario não existe no banco de dados."));
                }else{
                    $sql = "DELETE FROM usuario WHERE id = $id;";
                    $resultado = $conexao->query($sql);
                    if (!$resultado) {
                        http_response_code(500);
                        echo json_encode(array("Mensagem"=>"Não foi possivel deletar usuario."));
                    } else {
                        http_response_code(203);
                        $novo_id = $conexao->insert_id;
                        echo json_encode(array("Mensagem"=>"Usuario deletado."));
                    }
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(array("É necessario informar ID do usuario."));            
        }
        break;
    default:
        # code...
        break;
}
