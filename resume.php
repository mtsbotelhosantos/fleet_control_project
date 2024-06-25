<?php
require ('valida_sessao.php');
include ("conexao.php");

       date_default_timezone_set('America/Sao_Paulo');
       $agora = new DateTime('now');
       $agora_liquido = $agora->format('Y/m/d H:i:s');
       $usuario = $_SESSION['id_session'];

       
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
                    carros.marca as marca,
                    carros.modelo as modelo,
                    carros.placa as placa,
                    controle.data_saida as data_saida,
                    controle.data_entrada as data_entrada,
                    controle.km_saida as km_saida,
                    controle.km_entrada as km_entrada,
                    motoristas.nome as nome_motorista,
                    porteiro_saida.nome as nome_porteiro,
                    porteiro_entrada.nome as nome_porteiro_entrada,
                    controle.observacao as nc_saida,
                    controle.observacao_entrada as nc_entrada,
                    controle.destino_cidade as destino_cidade,
                    controle.destino_cliente as destino_cliente,
                    controle.km_entrada - controle.km_saida AS km_percorridos,
                    controle.checklist as checklist_saida,
                    controle.checklist_entrada as checklist_entrada,
                    controle.path1 as path1,
                    controle.path2 as path2,
                    controle.path3 as path3,
                    controle.path4 as path4,
                    controle.path5 as path5,
                    controle.path6 as path6,
                    controle.path7 as path7,
                    controle.path8 as path8,
                    controle.path9 as path9,
                    controle.path10 as path10,
                    TIMESTAMPDIFF(MINUTE, controle.data_saida, controle.data_entrada) AS total_minutes,
                    CONCAT(
                        FLOOR(TIMESTAMPDIFF(MINUTE, controle.data_saida, controle.data_entrada) / 1440), ' dias ',
                        FLOOR((TIMESTAMPDIFF(MINUTE, controle.data_saida, controle.data_entrada) % 1440) / 60), ' horas ',
                        (TIMESTAMPDIFF(MINUTE, controle.data_saida, controle.data_entrada) % 60), ' minutos'
                    ) AS tempo_fora

                    
                FROM 
                    controle
                JOIN 
                    carros ON controle.id_carro = carros.id
                JOIN 
                    motoristas ON controle.motorista = motoristas.id
                JOIN 
                    usuarios porteiro_saida ON controle.id_porteiro = porteiro_saida.id 
                JOIN 
                    usuarios porteiro_entrada ON controle.id_porteiro_entrada = porteiro_entrada.id 
               
                WHERE 
                    controle.id = $id;";  
            $sql_code = $conn->prepare($sql_code);
            $sql_code->execute();
            $results_select = $sql_code->fetchAll(PDO::FETCH_ASSOC); 

         
           
    
        }


    
      
       
        
       



?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script defer src="nova_saida.js"></script> 
    <link rel="stylesheet" href="resume.css">
    <script defer src="monitora_sessao.js"></script> 
    <title>Nova Saída</title>
</head>
<body>
    <main>
    <div class="master">
        <form enctype="multipart/form-data" method="POST" action="" name="contact-form">

        <div class="logo_titulo">
        <a href="inicio.php"><img src="assets/logo_sobre_nos5.png" alt="logo_canastra" srcset="" id="logo"></a>
                            <h2 class="titulo">DETALHES DA VIAGEM</h2>
        </div>
                        <br>
                        <?php  foreach($results_select as $select){ ?>
                                        <div class="info_car">
                                             <p><span class="span">Id: </span><?php echo$select['id']; ?></p> 
                                            <p><span class="span">Marca: </span><?php echo$select['marca']; ?></p>
                                            <p><span class="span">Modelo: </span><?php echo$select['modelo']; ?></p>
                                            <p><span class="span">Placa: </span><?php echo$select['placa']; ?></p>
                                            <p><span class="span">Motorista: </span><?php echo$select['nome_motorista']; ?></p>
                                            <p><span class="span">Km Percorridos: </span><?php echo$select['km_percorridos']; ?></p>
                                            <p><span class="span">Tempo: </span><?php echo$select['tempo_fora']; ?></p>
                                            <p><span class="span">Destino (Cidades): </span><?php echo$select['destino_cidade']; ?></p>
                                            <p><span class="span">Destino (Clientes): </span><?php echo$select['destino_cliente']; ?></p>
                                            
                                            
                                        </div>
                            <div class="saida_entrada">            
                                <div class="saida">
                                        <p class="tit_saida">Dados Saída</p>
                                    <div class="select_view">
                                        
                                            
                                            <p><span class="span">Data: </span><?php echo date('d/m/Y', strtotime($select['data_saida'])); ?></p>
                                            <p><span class="span">Hora: </span><?php echo date('H:i', strtotime($select['data_saida'])); ?></p>
                                            <p><span class="span">KM: </span><?php echo$select['km_saida']; ?></p>
                                            <p><span class="span">Porteiro: </span><?php echo$select['nome_porteiro']; ?></p>
                                            <p><span class="span">Checklist: </span><?php echo$select['checklist_saida']; ?></p>
                                            <p><span class="span" style="<?php if(!empty($select['nc_saida'])){echo"display:block";}else{echo"display:none";}?>">NC Motivo: </span><?php echo$select['nc_saida']; ?></p>
                                            
                                            <div class="arquivos_visu">
                                                
                                                <a href="<?php echo $select['path1']; ?>" style="<?php if(!empty($select['path1'])){echo"display:block";}else{echo"display:none";} ?>" target="_blank"><img src="<?php echo $select['path1']; ?>" alt="foto_veiculo" srcset="" class="miniaturas"></a>
                                                <a href="<?php echo $select['path2']; ?>" style="<?php if(!empty($select['path2'])){echo"display:block";}else{echo"display:none";} ?>" target="_blank"><img src="<?php echo $select['path2']; ?>" alt="foto_veiculo" srcset="" class="miniaturas"></a>
                                                <a href="<?php echo $select['path3']; ?>" style="<?php if(!empty($select['path3'])){echo"display:block";}else{echo"display:none";} ?>" target="_blank"><img src="<?php echo $select['path3']; ?>" alt="foto_veiculo" srcset="" class="miniaturas"></a>
                                                <a href="<?php echo $select['path4']; ?>" style="<?php if(!empty($select['path4'])){echo"display:block";}else{echo"display:none";} ?>" target="_blank"><img src="<?php echo $select['path4']; ?>" alt="foto_veiculo" srcset="" class="miniaturas"></a>
                                                <a href="<?php echo $select['path5']; ?>" style="<?php if(!empty($select['path5'])){echo"display:block";}else{echo"display:none";} ?>" target="_blank"><img src="<?php echo $select['path5']; ?>" alt="foto_veiculo" srcset="" class="miniaturas"></a>
                                            </div>

                                        
                                                    
                                        </div>
                                </div>
                                                <br>
                                <div class="entrada">
                                        <p class="tit_entrada">Dados Retorno</p>
                                    <div class="select_view">
                                    
                                        
                                            <p><span class="span">Data: </span><?php echo date('d/m/Y', strtotime($select['data_entrada'])); ?></p>
                                            <p><span class="span">Hora: </span><?php echo date('H:i', strtotime($select['data_entrada'])); ?></p>
                                            <p><span class="span">KM: </span><?php echo$select['km_entrada']; ?></p>
                                            <p><span class="span">Porteiro: </span><?php echo$select['nome_porteiro_entrada']; ?></p>
                                            <p><span class="span">Checklist: </span><?php echo$select['checklist_entrada']; ?></p>
                                            <p><span class="span" style="<?php if(!empty($select['nc_entrada'])){echo"display:block";}else{echo"display:none";}?>">NC Motivo: </span><?php echo$select['nc_entrada']; ?></p>
                                        
                                            <div class="arquivos_visu">
                                                
                                        <a href="<?php echo $select['path6']; ?>" style="<?php if(!empty($select['path6'])){echo"display:block";}else{echo"display:none";} ?>" target="_blank"><img src="<?php echo $select['path6']; ?>" alt="foto_veiculo" srcset="" class="miniaturas"></a>
                                        <a href="<?php echo $select['path7']; ?>" style="<?php if(!empty($select['path7'])){echo"display:block";}else{echo"display:none";} ?>" target="_blank"><img src="<?php echo $select['path7']; ?>" alt="foto_veiculo" srcset="" class="miniaturas"></a>
                                        <a href="<?php echo $select['path8']; ?>" style="<?php if(!empty($select['path8'])){echo"display:block";}else{echo"display:none";} ?>" target="_blank"><img src="<?php echo $select['path8']; ?>" alt="foto_veiculo" srcset="" class="miniaturas"></a>
                                        <a href="<?php echo $select['path9']; ?>" style="<?php if(!empty($select['path9'])){echo"display:block";}else{echo"display:none";} ?>" target="_blank"><img src="<?php echo $select['path8']; ?>" alt="foto_veiculo" srcset="" class="miniaturas"></a>
                                        <a href="<?php echo $select['path10']; ?>" style="<?php if(!empty($select['path10'])){echo"display:block";}else{echo"display:none";} ?>" target="_blank"><img src="<?php echo $select['path10']; ?>" alt="foto_veiculo" srcset="" class="miniaturas"></a>
                                            </div>

                                            <?php } ?>
                                                    
                                        </div>
                                        
                                </div>
                            </div>

           
                                              
            </div>
        </form>
    </div>
    </main>
</body>
</html>