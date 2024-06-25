<?php
require ('valida_sessao.php');
include ("conexao.php");

    date_default_timezone_set('America/Sao_Paulo');
    $agora = new DateTime('now');
    $agora_liquido = $agora->format('Y/m/d H:i:s');
    $usuario = $_SESSION['id_session'];
    $agora_data = $agora->format('Y-m-d');
    $agora_hora = $agora->format('H:i');
    $agora_liquido_formatado = $agora_data."T".$agora_hora;

       
       $sql_code = "SELECT * FROM carros;"; 
       $sql_code = $conn->prepare($sql_code);
       $sql_code->execute();
       $results_carro = $sql_code->fetchAll(PDO::FETCH_ASSOC); 

       $sql_code = "SELECT * FROM motoristas;"; 
       $sql_code = $conn->prepare($sql_code);
       $sql_code->execute();
       $results_motorista = $sql_code->fetchAll(PDO::FETCH_ASSOC); 

       if(!empty($_GET['id'])){

        

             $id = $_GET['id'];

             $sql_code = "SELECT 
                    controle.id as id,
                    carros.id as id_carro,
                    carros.marca as marca,
                    carros.modelo as modelo,
                    carros.placa as placa,
                    controle.data_saida as data_saida,
                    controle.data_entrada as data_entrada,
                    controle.km_saida as km_saida,
                    motoristas.nome as nome_motorista,
                    controle.destino_cidade as destino_cidade,
                    controle.destino_cliente as destino_cliente,
                    controle.km_abastecimento as km_abastecimento,
                    controle.path1 as path1,
                    controle.path2 as path2,
                    controle.path3 as path3,
                    controle.path4 as path4,
                    controle.path5 as path5

                    
                FROM 
                    controle
                JOIN 
                    carros ON controle.id_carro = carros.id
                JOIN 
                    motoristas ON controle.motorista = motoristas.id
                WHERE 
                    controle.id = $id;";  
            $sql_code = $conn->prepare($sql_code);
            $sql_code->execute();
            $results_select = $sql_code->fetchAll(PDO::FETCH_ASSOC); 

         
           
    
        }


       $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
       

       if(!empty($dados)){
        $nome_arquivo1 = $_FILES['arquivo1']['name'];
        $nome_arquivo2 = $_FILES['arquivo2']['name'];
        $nome_arquivo3 = $_FILES['arquivo3']['name'];
        $nome_arquivo4 = $_FILES['arquivo4']['name'];
        $nome_arquivo5 = $_FILES['arquivo5']['name'];
        $nulo = NULL;

        


            $arquivo1 = $_FILES['arquivo1'];
            $pasta = "fotos/";
            $nomeDoArquivo1 = $arquivo1['name'];
            $novoNomeDoArquivo1 = uniqid();
            $extensao1 = strtolower(pathinfo($nomeDoArquivo1, PATHINFO_EXTENSION));
            $path1 = $pasta . $novoNomeDoArquivo1 . "." . $extensao1;
            $deu_certo1 = move_uploaded_file($arquivo1["tmp_name"], $path1);

            $arquivo2 = $_FILES['arquivo2'];
            $nomeDoArquivo2 = $arquivo2['name'];
            $novoNomeDoArquivo2 = uniqid();
            $extensao2 = strtolower(pathinfo($nomeDoArquivo2, PATHINFO_EXTENSION));
            $path2 = $pasta . $novoNomeDoArquivo2 . "." . $extensao2;
            $deu_certo2 = move_uploaded_file($arquivo2["tmp_name"], $path2);

            $arquivo3 = $_FILES['arquivo3'];
            $nomeDoArquivo3 = $arquivo3['name'];
            $novoNomeDoArquivo3 = uniqid();
            $extensao3 = strtolower(pathinfo($nomeDoArquivo3, PATHINFO_EXTENSION));
            $path3 = $pasta . $novoNomeDoArquivo3 . "." . $extensao3;
            $deu_certo3 = move_uploaded_file($arquivo3["tmp_name"], $path3);

            $arquivo4 = $_FILES['arquivo4'];
            $nomeDoArquivo4 = $arquivo4['name'];
            $novoNomeDoArquivo4 = uniqid();
            $extensao4 = strtolower(pathinfo($nomeDoArquivo4, PATHINFO_EXTENSION));
            $path4 = $pasta . $novoNomeDoArquivo4 . "." . $extensao4;
            $deu_certo4 = move_uploaded_file($arquivo4["tmp_name"], $path4);

            $arquivo5 = $_FILES['arquivo5'];
            $nomeDoArquivo5 = $arquivo5['name'];
            $novoNomeDoArquivo5 = uniqid();
            $extensao5 = strtolower(pathinfo($nomeDoArquivo5, PATHINFO_EXTENSION));
            $path5 = $pasta . $novoNomeDoArquivo5 . "." . $extensao5;
            $deu_certo5 = move_uploaded_file($arquivo5["tmp_name"], $path5);


            foreach($results_select as $select){ 
                $km_saida = $select['km_saida'];
                $data_de_saida = $select['data_saida'];
                $data_de_entrada = $select['data_entrada'];
             }

             if(empty($dados['data_entrada']) || empty($dados['km_entrada']) || empty($dados['acondicionamento']) || empty($dados['km_abastecimento']) || empty($dados['nivel_tanque'])){
                ?> <script> alert("Todos os campos são obrigatórios!"); </script> <?php
            }elseif(($dados['acondicionamento'] == "Não Conforme" && empty($dados['motivo_nc_acondicionamento'])) || ($dados['acondicionamento'] == "Não Conforme" && ((empty($nome_arquivo1) && empty($nome_arquivo2) && empty($nome_arquivo3) && empty($nome_arquivo4) && empty($nome_arquivo5))))){
                ?> <script> alert("Caso veículo estiver NÃO CONFORME, observação e pelo menos uma foto da não conformidade são obrigatórios!"); </script> <?php
            }elseif($km_saida >= $dados['km_entrada']){
                ?> <script> alert("KM de retorno não pode ser menor ou igual ao KM de saída! Por favor, verifique."); </script> <?php

            }elseif($data_de_saida > $dados['data_entrada']){
                ?> <script> alert("Data de retorno não pode ser inferior a data de saída! Por favor, verifique."); </script> <?php
            }elseif(isset($data_de_entrada)){
                ?> <script> alert("Esse veículo já retornou. Abra uma nova saída!"); </script> <?php
            }else{
                            $sql_form = "UPDATE controle 
                            SET 
                                id_porteiro_entrada = :id_porteiro_entrada,
                                data_entrada = :data_entrada,
                                km_entrada = :km_entrada,
                                checklist_entrada = :checklist_entrada,
                                observacao_entrada = :observacao_entrada,
                                km_abastecimento = :km_abastecimento,
                                nivel_tanque = :nivel_tanque,
                                nome_arquivo6 = :nome_arquivo6,
                                path6 = :path6,
                                nome_arquivo7 = :nome_arquivo7,
                                path7 = :path7,
                                nome_arquivo8 = :nome_arquivo8,
                                path8 = :path8,
                                nome_arquivo9 = :nome_arquivo9,
                                path9 = :path9,
                                nome_arquivo10 = :nome_arquivo10,
                                path10 = :path10
                            WHERE 
                                id = :id; ";
                              
                             
                               

                $sql_form = $conn->prepare($sql_form);

                $sql_form->bindParam(':id_porteiro_entrada', $usuario);
                $sql_form->bindParam(':data_entrada', $dados['data_entrada']);
                $sql_form->bindParam(':km_entrada', $dados['km_entrada']);
                $sql_form->bindParam(':checklist_entrada', $dados['acondicionamento']);
                $sql_form->bindParam(':observacao_entrada', $dados['motivo_nc_acondicionamento']);
                $sql_form->bindParam(':km_abastecimento', $dados['km_abastecimento']);
                $sql_form->bindParam(':nivel_tanque', $dados['nivel_tanque']);
                $sql_form->bindParam(':id', $id);
                if(!empty($nome_arquivo1)){
                    $sql_form->bindParam(':nome_arquivo6', $novoNomeDoArquivo1);
                    $sql_form->bindParam(':path6', $path1);

                }else{
                    $sql_form->bindParam(':nome_arquivo6', $nulo);
                    $sql_form->bindParam(':path6', $nulo);
                }

                if(!empty($nome_arquivo2)){
                    $sql_form->bindParam(':nome_arquivo7', $novoNomeDoArquivo2);
                    $sql_form->bindParam(':path7', $path2);

                }else{
                    $sql_form->bindParam(':nome_arquivo7', $nulo);
                    $sql_form->bindParam(':path7', $nulo);
                }

                if(!empty($nome_arquivo3)){
                    $sql_form->bindParam(':nome_arquivo8', $novoNomeDoArquivo3);
                    $sql_form->bindParam(':path8', $path3);

                }else{
                    $sql_form->bindParam(':nome_arquivo8', $nulo);
                    $sql_form->bindParam(':path8', $nulo);
                }

                if(!empty($nome_arquivo4)){
                    $sql_form->bindParam(':nome_arquivo9', $novoNomeDoArquivo4);
                    $sql_form->bindParam(':path9', $path4);

                }else{
                    $sql_form->bindParam(':nome_arquivo9', $nulo);
                    $sql_form->bindParam(':path9', $nulo);
                }

                if(!empty($nome_arquivo5)){
                    $sql_form->bindParam(':nome_arquivo10', $novoNomeDoArquivo5);
                    $sql_form->bindParam(':path10', $path5);

                }else{
                    $sql_form->bindParam(':nome_arquivo10', $nulo);
                    $sql_form->bindParam(':path10', $nulo);
                }
                $sql_form->execute();

                //EFETUAR O UPDATE DO KM ATUAL DO CADASTRO CARRO

                foreach ($results_select as $carros) {
                   $carro_id =  $carros['id_carro'];
                }
                $sql_carro = "UPDATE carros
                                SET km_atual = :km_entrada
                                    WHERE id = :id; ";

                $sql_carro = $conn->prepare($sql_carro);
                $sql_carro->bindParam(':km_entrada', $dados['km_entrada']);
                $sql_carro->bindParam(':id', $carro_id);
                $sql_carro->execute();


                header("Location: controle.php");
            }

        }
    
      
       
        
       



?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script defer src="nova_saida.js"></script> 
    <link rel="stylesheet" href="nova_saida.css">
    <script defer src="monitora_sessao.js"></script> 
    <title>Nova Saída</title>
</head>
<body>
    <main>
    <div class="master">
        <form enctype="multipart/form-data" method="POST" action="" name="contact-form">

        <div class="logo_titulo">
        <a href="inicio.php"><img src="assets/logo_sobre_nos5.png" alt="logo_canastra" srcset="" id="logo"></a>
                            <h2 class="titulo">RETORNO</h2>
        </div>
                     <div class="separador">
                            
                    </div>
            <div class="preenchimento">
                                      
                                   <div class="select_view">
                                   <?php  foreach($results_select as $select){ ?>
                                    <p><span class="span">Marca: </span><?php echo$select['marca']; ?></p>
                                    <p><span class="span">Modelo: </span><?php echo$select['modelo']; ?></p>
                                    <p><span class="span">Placa: </span><?php echo$select['placa']; ?></p>
                                    <p><span class="span">Data Saída: </span><?php echo date('d/m/Y', strtotime($select['data_saida'])); ?></p>
                                    <p><span class="span">Hora Saída: </span><?php echo date('H:i', strtotime($select['data_saida'])); ?></p>
                                    <p><span class="span">KM Saída: </span><?php echo$select['km_saida']; ?></p>
                                    <p><span class="span">Motorista: </span><?php echo$select['nome_motorista']; ?></p>
                                    <p><span class="span">Destino (Cidades): </span><?php echo$select['destino_cidade']; ?></p>
                                    <p><span class="span">Destino (Clientes): </span><?php echo$select['destino_cliente']; ?></p>
                                    <div class="arquivos_visu">

                                    
                                       
                                        <a href="<?php echo $select['path1']; ?>" style="<?php if(!empty($select['path1'])){echo"display:block";}else{echo"display:none";} ?>" target="_blank"><img src="<?php echo $select['path1']; ?>" alt="foto_veiculo" srcset="" class="miniaturas"></a>
                                        <a href="<?php echo $select['path2']; ?>" style="<?php if(!empty($select['path2'])){echo"display:block";}else{echo"display:none";} ?>" target="_blank"><img src="<?php echo $select['path2']; ?>" alt="foto_veiculo" srcset="" class="miniaturas"></a>
                                        <a href="<?php echo $select['path3']; ?>" style="<?php if(!empty($select['path3'])){echo"display:block";}else{echo"display:none";} ?>" target="_blank"><img src="<?php echo $select['path3']; ?>" alt="foto_veiculo" srcset="" class="miniaturas"></a>
                                        <a href="<?php echo $select['path4']; ?>" style="<?php if(!empty($select['path4'])){echo"display:block";}else{echo"display:none";} ?>" target="_blank"><img src="<?php echo $select['path4']; ?>" alt="foto_veiculo" srcset="" class="miniaturas"></a>
                                        <a href="<?php echo $select['path5']; ?>" style="<?php if(!empty($select['path5'])){echo"display:block";}else{echo"display:none";} ?>" target="_blank"><img src="<?php echo $select['path5']; ?>" alt="foto_veiculo" srcset="" class="miniaturas"></a>
                                    </div>


                                  
                                   


                       

                                          <?php } ?>
                                                
                                   </div>


                                 

                       

                                    <br>

                                    <div class="campos">
                                          <span>Data/Hora Retorno *</span>
                                          <input type="datetime-local" id="data" class="input_cab" name="data_entrada" value="<?php if(!empty($dados['data_entrada'])){echo $dados['data_entrada'];}else{echo$agora_liquido_formatado;} ?>" readonly>
                                   </div>
                                   
                                   <br>
                                    
                                    <div class="campos">
                                          <span>Km Retorno * <i class="material-symbols-outlined" onclick="showTooltip('campo1')">help</i></span>
                                        
                                          <input type="number" id="" class="input_cab" name="km_entrada" value="<?php if(!empty($dados['km_entrada'])){echo $dados['km_entrada'];} ?>">
                                   </div>
                                    <br>
                                    <div class="campos">
                                          <span>Nível do Tanque no Retorno * <i class="material-symbols-outlined"  onclick="showTooltip('campo2')">help</i></span>
                                                <select  id=""  name="nivel_tanque" required>
                                                        <option value=""></option>
                                                        <option value="Reserva">Reserva</option>
                                                        <option value="1/4">1/4</option>
                                                        <option value="1/2">1/2</option>
                                                        <option value="3/4">3/4</option>
                                                        <option value="Cheio">Cheio</option>
                                                  
                                                        
                                                 
                                                </select>
                                   </div>
                                   <br>

                                   <div class="campos">
                                          <span>Km's Abastecimento * <i class="material-symbols-outlined" onclick="showTooltip('campo3')">help</i></span>
                                          <textarea id="comentario"  name="km_abastecimento" rows="4" cols="40" placeholder="Ex: 980, 1000, 1100..."><?php if(!empty($dados['km_abastecimento'])){echo $dados['km_abastecimento'];} ?></textarea>
                                   </div>



                                   <br>

                                   <div class="campos">
                                          <span>Checklist Retorno * <i class="material-symbols-outlined" onclick="showTooltip('campo4')">help</i></span>
                                          <div class="wrap">
                                                 <?php
                                                        echo "<div id='nao_conforme1' class='clickable3' style='background-color: #be0115;'>Não Conforme</div>";
                                                        echo "<div id='conforme1' class='clickable3' style='background-color: #3dc441;'>Conforme</div>";

                                                 
                                                 ?>
                                          </div>
                                          <input type="text" id="selectedDiv3" class="input_colors" name="acondicionamento">
                                          <div id="text_nc_acondicionamento">
                                                 <span>Motivo da Não Conformidade</span><br>
                                                 <textarea id="comentario"  name="motivo_nc_acondicionamento" rows="4" cols="40"><?php if(!empty($dados['motivo_nc_acondicionamento'])){echo $dados['motivo_nc_acondicionamento'];} ?></textarea>
                                          
                                          
                                                 <div class="arquivos">
                                                    <input type="file" name="arquivo1" id="input1" class="inputfile" accept="image/*">
                                                    <div id="preview1" class="preview"></div>
                                                 </div>
                                                   
                                         
                                                <div class="arquivos">
                                                        <input type="file" name="arquivo2" id="input2" class="inputfile" accept="image/*">
                                                        <div id="preview2" class="preview"></div>
                                                </div>
                                            
                                         
                                                <div class="arquivos">
                                                        <input type="file" name="arquivo3" id="input3" class="inputfile" accept="image/*">
                                                        <div id="preview3" class="preview"></div>
                                                </div>

                                                <div class="arquivos">
                                                        <input type="file" name="arquivo4" id="input4" class="inputfile" accept="image/*">
                                                        <div id="preview4" class="preview"></div>
                                                </div>

                                                <div class="arquivos">
                                                        <input type="file" name="arquivo5" id="input5" class="inputfile" accept="image/*">
                                                        <div id="preview5" class="preview"></div>
                                                </div>
                                          
                                          
                                          
                                          
                                          
                                          
                                          </div>
                                        
                                   </div>

                                   
                                    <br>

                                      


                                         

                                
                                               
            </div>
            <input id="submitButton" type="submit" value="Salvar" class="submit">

            
        </form>
        <div id="tooltip" class="tooltip">
                <div class="tooltip-content">
                    <p id="tooltip-text"></p>
                    <button class="botao_aviso" onclick="hideTooltip()">OK</button>
                </div>
            </div>

        
    </div>
    </main>
</body>
</html>