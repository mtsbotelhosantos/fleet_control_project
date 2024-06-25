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


       $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
       

       if(!empty($dados)){
        //BUSCA KM ATUAL
        $sql_code = "SELECT * FROM carros WHERE id = :id_carro;"; 
        $sql_code = $conn->prepare($sql_code);
        $sql_code->bindParam(':id_carro', $dados['carro']);
        $sql_code->execute();
        $results_km = $sql_code->fetchAll(PDO::FETCH_ASSOC); 

        foreach ($results_km as $km){

            $km_carro = $km['km_atual'];

        }

        $nome_arquivo1 = $_FILES['arquivo1']['name'];
        $nome_arquivo2 = $_FILES['arquivo2']['name'];
        $nome_arquivo3 = $_FILES['arquivo3']['name'];
        $nome_arquivo4 = $_FILES['arquivo4']['name'];
        $nome_arquivo5 = $_FILES['arquivo5']['name'];
        $nulo = NULL;

        $id_carro = $dados['carro'];
        $sql_code = "SELECT * FROM controle WHERE id_carro = $id_carro AND data_entrada IS NULL;"; 
        $sql_code = $conn->prepare($sql_code);
        $sql_code->execute();
        $results_carro_aberto = $sql_code->fetchAll(PDO::FETCH_ASSOC); 

        if(!empty($results_carro_aberto)){
            ?> <script> alert("Já possui saída em aberto desse veículo! Finalize a saída para abrir uma nova."); </script> <?php
        }elseif(empty($dados['carro']) || empty($dados['motorista']) || empty($dados['data_saida']) || empty($dados['km_saida']) || empty($dados['acondicionamento'])){
            ?> <script> alert("Todos os campos são obrigatórios!"); </script> <?php

        }elseif(($dados['acondicionamento'] == "Não Conforme" && empty($dados['motivo_nc_acondicionamento'])) || ($dados['acondicionamento'] == "Não Conforme" && ((empty($nome_arquivo1) && empty($nome_arquivo2) && empty($nome_arquivo3) && empty($nome_arquivo4) && empty($nome_arquivo5))))){

            ?> <script> alert("Caso veículo estiver NÃO CONFORME, observação e pelo menos uma foto da não conformidade são obrigatórios!"); </script> <?php

        }elseif($km_carro > $dados['km_saida']){

            ?> <script> alert("KM de saída não pode ser menor que KM atual do veículo. Verifique! ÚLTIMO KM APONTADO: <?php echo$km_carro;?>"); </script> <?php
          

        }else{
           


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

                            $sql_form = "INSERT INTO controle (id_porteiro,
                                id_carro,
                                data_saida, 
                                km_saida, 
                                motorista, 
                                checklist, 
                                observacao,
                                destino_cidade,
                                destino_cliente,
                                nome_arquivo1,
                                path1,
                                nome_arquivo2,
                                path2,
                                nome_arquivo3,
                                path3,
                                nome_arquivo4,
                                path4,
                                nome_arquivo5,
                                path5) 

                           
                            VALUES (:id_porteiro,
                                    :id_carro,
                                    :data_saida, 
                                    :km_saida, 
                                    :motorista, 
                                    :checklist, 
                                    :observacao,
                                    :destino_cidade,
                                    :destino_cliente,
                                    :nome_arquivo1,
                                    :path1,
                                    :nome_arquivo2,
                                    :path2,
                                    :nome_arquivo3,
                                    :path3,
                                    :nome_arquivo4,
                                    :path4,
                                    :nome_arquivo5,
                                    :path5)"; 
                              
                             
                               

                $sql_form = $conn->prepare($sql_form);

                $sql_form->bindParam(':id_porteiro', $usuario);
                $sql_form->bindParam(':id_carro', $dados['carro']);
                $sql_form->bindParam(':data_saida', $dados['data_saida']);
                $sql_form->bindParam(':km_saida', $dados['km_saida']);
                $sql_form->bindParam(':motorista', $dados['motorista']);
                $sql_form->bindParam(':checklist', $dados['acondicionamento']);
                $sql_form->bindParam(':observacao', $dados['motivo_nc_acondicionamento']);
                $sql_form->bindParam(':destino_cidade', $dados['destino_cidade']);
                $sql_form->bindParam(':destino_cliente', $dados['destino_cliente']);
                if(!empty($nome_arquivo1)){
                    $sql_form->bindParam(':nome_arquivo1', $novoNomeDoArquivo1);
                    $sql_form->bindParam(':path1', $path1);

                }else{
                    $sql_form->bindParam(':nome_arquivo1', $nulo);
                    $sql_form->bindParam(':path1', $nulo);
                }

                if(!empty($nome_arquivo2)){
                    $sql_form->bindParam(':nome_arquivo2', $novoNomeDoArquivo2);
                    $sql_form->bindParam(':path2', $path2);

                }else{
                    $sql_form->bindParam(':nome_arquivo2', $nulo);
                    $sql_form->bindParam(':path2', $nulo);
                }

                if(!empty($nome_arquivo3)){
                    $sql_form->bindParam(':nome_arquivo3', $novoNomeDoArquivo3);
                    $sql_form->bindParam(':path3', $path3);

                }else{
                    $sql_form->bindParam(':nome_arquivo3', $nulo);
                    $sql_form->bindParam(':path3', $nulo);
                }

                if(!empty($nome_arquivo4)){
                    $sql_form->bindParam(':nome_arquivo4', $novoNomeDoArquivo4);
                    $sql_form->bindParam(':path4', $path4);

                }else{
                    $sql_form->bindParam(':nome_arquivo4', $nulo);
                    $sql_form->bindParam(':path4', $nulo);
                }

                if(!empty($nome_arquivo5)){
                    $sql_form->bindParam(':nome_arquivo5', $novoNomeDoArquivo5);
                    $sql_form->bindParam(':path5', $path5);

                }else{
                    $sql_form->bindParam(':nome_arquivo5', $nulo);
                    $sql_form->bindParam(':path5', $nulo);
                }
                
                
               


                $sql_form->execute();
                
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
    <script defer src="monitora_sessao.js"></script> 
    <link rel="stylesheet" href="nova_saida.css">
    <title>Nova Saída</title>
</head>
<body>
    <main>
    <div class="master">
        <form enctype="multipart/form-data" method="POST" action="" name="contact-form">

        <div class="logo_titulo">
                            <a href="inicio.php"><img src="assets/logo_sobre_nos5.png" alt="logo_canastra" srcset="" id="logo"></a>
                            <h2 class="titulo">NOVA SAÍDA</h2>
        </div>
                     <div class="separador">
                            
                    </div>
            <div class="preenchimento">

                                   <div class="campos">
                                          <span>Selecione o Carro *</span>
                                                <select  id=""  name="carro" required>
                                                        <option value=""></option>
                                                    <?php foreach ($results_carro as $row) { 
                                                        echo '<option value="' . $row["id"] . '">' . $row["marca"] . " " . $row["modelo"] . " - " . $row["placa"] . '</option>';
                                                        
                                                    } ?>
                                                </select>
                                   </div>

                                   <br>

                                  
                                   <div class="campos">
                                          <span>Motorista Responsável *</span>
                                          <select  id=""  name="motorista" required>
                                                        <option value=""></option>
                                                    <?php foreach ($results_motorista as $row) { 
                                                        echo '<option value="' . $row["id"] . '">' . $row["nome"] . " " . $row["sobrenome"] .'</option>';
                                                    } ?>
                                                </select>
                                    </div>

                                    <br>

                                    <div class="campos">
                                          <span>Data/Hora Saída *</span>
                                          <input type="datetime-local" id="data" class="input_cab" name="data_saida" value="<?php if(!empty($dados['data_saida'])){echo $dados['data_saida'];}else{echo $agora_liquido_formatado;}  ?>" readonly>
                                   </div>
                                   
                                   <br>
                                    
                                    <div class="campos">
                                          <span>Km Saída * <i class="material-symbols-outlined" onclick="showTooltip('campo5')">help</i></span>
                                        
                                          <input type="number" id="" class="input_cab" name="km_saida" value="<?php if(!empty($dados['km_saida'])){echo $dados['km_saida'];}?>">
                                   </div>

                                   <br>

                                   <div class="campos">
                                          <span>Destinos (Cidades) * <i class="material-symbols-outlined" onclick="showTooltip('campo6')">help</i></span>
                                        
                                          <textarea id="comentario"  name="destino_cidade" rows="4" cols="40" placeholder="Ex: Mirassol, Bady Bassitt, Araçatuba" required><?php if(!empty($dados['destino_cidade'])){echo $dados['destino_cidade'];} ?></textarea>
                                   </div>

                                   <br>

                                   <div class="campos">
                                          <span>Destinos (Clientes) * <i class="material-symbols-outlined" onclick="showTooltip('campo7')">help</i></span>
                                        
                                          <textarea id="comentario"  name="destino_cliente" rows="4" cols="40" placeholder="Ex: Palheiro Paulistinha, Chiquinho Sorvetes, Marilan" required><?php if(!empty($dados['destino_cliente'])){echo $dados['destino_cliente'];} ?></textarea>
                                   </div>


                                   <br>

                                   <div class="campos">
                                          <span>Checklist Saída *</span>
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