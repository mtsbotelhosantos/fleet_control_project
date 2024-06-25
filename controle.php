<?php
require ('valida_sessao.php');
include ("conexao.php");

       date_default_timezone_set('America/Sao_Paulo');
       $agora = new DateTime('now');
       $agora_liquido = $agora->format('Y/m/d H:i:s');
       $usuario = $_SESSION['id_session'];
     

       $sql_code = "SELECT 
                    controle.id as id,
                    carros.marca as marca,
                    carros.modelo as modelo,
                    carros.placa as placa,
                    controle.data_saida as data_saida,
                    controle.km_saida as km_saida,
                    motoristas.nome as nome_motorista

                    
                FROM 
                    controle
                JOIN 
                    carros ON controle.id_carro = carros.id
                JOIN 
                    motoristas ON controle.motorista = motoristas.id
                WHERE 
                    controle.km_entrada IS NULL
                ORDER BY 
                        controle.id DESC
                ;"; 
       $sql_code = $conn->prepare($sql_code);
       $sql_code->execute();
       $results_controle = $sql_code->fetchAll(PDO::FETCH_ASSOC); 

       foreach($results_controle as $controle){
        if(!empty($controle['id'])){
            $existe_dados = true;

        }else{
            $existe_dados = false;
        }
       }

      
      



?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="controle.css">
    <script defer src="monitora_sessao.js"></script> 
    <title>Controle</title>
</head>
<body>

    <div class="master">
        <div class="buttons">
            <a href="inicio.php"><img src="assets/logo_sobre_nos5.png" alt="logo_canastra" srcset="" id="logo"></a>
            
        </div>
        
        <div class="tabela">
                <table class="<?php if($existe_dados){echo"existe_dados";}else{echo"n_existe_dados";}?>">
                <thead>
                    <tr>
                        <th class="mobile">ID</th>
                        <th>Veículo</th>
                        <th>Placa</th>
                        <th>Data Saída</th>
                        <th class="mobile">Hora Saída</th>
                        <th class="mobile">KM Saída</th>
                        <th>Motorista</th>
                    </tr>
                </thead>
                <tbody>
          
                    <?php foreach($results_controle as $controle){ ?>
                        
                            <tr class="linhas_base">
                            
                                    <td class="mobile"><a href="nova_entrada.php?id=<?php echo $controle['id']; ?>"><?php echo $controle['id']; ?></a></td>  
                                    <td><a href="nova_entrada.php?id=<?php echo $controle['id']; ?>"><?php echo $controle['marca']; echo"-"; echo$controle['modelo']; ?></a></td>
                                    <td><a href="nova_entrada.php?id=<?php echo $controle['id']; ?>"><?php echo $controle['placa']; ?></a></td>
                                    <td><a href="nova_entrada.php?id=<?php echo $controle['id']; ?>"><?php echo date('d/m/Y', strtotime($controle['data_saida'])); ?></a></td>
                                    <td class="mobile"><a href="nova_entrada.php?id=<?php echo $controle['id']; ?>"><?php echo date('H:i', strtotime($controle['data_saida'])); ?></a></td>
                                    <td class="mobile"><a href="nova_entrada.php?id=<?php echo $controle['id']; ?>"><?php echo $controle['km_saida']; ?></a></td>
                                    <td><a href="nova_entrada.php?id=<?php echo $controle['id']; ?>"><?php echo $controle['nome_motorista']; ?></a></td>
                           
                            </tr>
                      
                        
                       
                        
                       
                        
                      
                    <?php } ?>
                   
                </tbody>
            </table>
            <a href="nova_saida.php" class="nova_saida"><i class="material-symbols-outlined">add</i>Nova Saída</a>
        </div>
       

    </div>
</body>
</html>