<?php
require ('valida_sessao.php');
include ("conexao.php");

       date_default_timezone_set('America/Sao_Paulo');
       $agora = new DateTime('now');
       $agora_liquido = $agora->format('Y/m/d H:i:s');
       $usuario = $_SESSION['id_session'];

       $motorista = "";

        
       $sql_code = "SELECT * FROM carros;"; 
       $sql_code = $conn->prepare($sql_code);
       $sql_code->execute();
       $results_carro = $sql_code->fetchAll(PDO::FETCH_ASSOC); 

       $sql_code = "SELECT * FROM motoristas;"; 
       $sql_code = $conn->prepare($sql_code);
       $sql_code->execute();
       $results_motorista = $sql_code->fetchAll(PDO::FETCH_ASSOC); 

       $sql_code = "SELECT 
                    controle.id as id,
                    carros.marca as marca,
                    carros.modelo as modelo,
                    carros.placa as placa,
                    controle.data_saida as data_saida,
                    controle.km_saida as km_saida,
                    controle.data_entrada as data_entrada,
                    controle.km_entrada as km_entrada,
                    motoristas.nome as nome_motorista,
                    usuarios.nome as nome_porteiro,
                    controle.km_entrada - controle.km_saida AS km_percorridos,
                    controle.checklist as checklist_saida,
                    controle.checklist_entrada as checklist_entrada

                    
                FROM 
                    controle
                JOIN 
                    carros ON controle.id_carro = carros.id
                JOIN 
                    motoristas ON controle.motorista = motoristas.id
                JOIN 
                    usuarios ON controle.id_porteiro = usuarios.id
                WHERE 
                    controle.km_entrada IS NOT NULL 
                    ORDER BY 
                        controle.data_entrada  DESC
                    LIMIT 10
                ;"; 
       $sql_code = $conn->prepare($sql_code);
       $sql_code->execute();
       $results_controle = $sql_code->fetchAll(PDO::FETCH_ASSOC); 


       $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
       

       if(!empty($dados)){
            
        //     if(!empty($dados['motorista'])){

        //         $motorista = $dados['motorista'];

        //         $query_mot = "controle.motorista = :motorista";

        // }else{
        //         $query_mot = "1=1";
        // }

                    $sql_code = "SELECT 
                    controle.id as id,
                    carros.marca as marca,
                    carros.modelo as modelo,
                    carros.placa as placa,
                    controle.data_saida as data_saida,
                    controle.km_saida as km_saida,
                    controle.data_entrada as data_entrada,
                    controle.km_entrada as km_entrada,
                    motoristas.nome as nome_motorista,
                    usuarios.nome as nome_porteiro,
                    controle.km_entrada - controle.km_saida AS km_percorridos,
                    controle.checklist as checklist_saida,
                    controle.checklist_entrada as checklist_entrada

                    
                FROM 
                    controle
                JOIN 
                    carros ON controle.id_carro = carros.id
                JOIN 
                    motoristas ON controle.motorista = motoristas.id
                JOIN 
                    usuarios ON controle.id_porteiro = usuarios.id
                WHERE 
                    controle.km_entrada IS NOT NULL 
                        AND (:motorista = '' OR controle.motorista = :motorista)
                        AND (:carro = '' OR controle.id_carro = :carro)
                        AND controle.data_saida >= :data_ini
                        AND controle.data_saida <= :data_fim
                    ORDER BY 
                        controle.data_entrada  DESC
                ;"; 
            $sql_code = $conn->prepare($sql_code);
            $sql_code->bindParam(':carro', $dados['carro']);
            $sql_code->bindParam(':motorista', $dados['motorista']);
            $sql_code->bindParam(':data_ini', $dados['data_ini']);
            $sql_code->bindParam(':data_fim', $dados['data_fim']);

            $sql_code->execute();
            $results_controle = $sql_code->fetchAll(PDO::FETCH_ASSOC); 

        }


      
      



?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="historico.css">
    <script defer src="monitora_sessao.js"></script> 
    <title>Controle</title>
</head>
<body>

    <div class="master">
       
            
            
           
     
        
        <div class="tabela">
        <div class="a_head"><a href="inicio.php" ><i class="material-symbols-outlined">arrow_back_ios</i>Voltar</a></div>
        <form  method="POST" id="abriros_form" action="">
                <div class="filtro_dia">
                    <div>
                        <legend>Saída Inicial</legend>
                        <input type="datetime-local" id="data" class="input_cab" name="data_ini" value="<?php if(!empty($dados['data_ini'])){echo $dados['data_ini'];} ?>">
                    </div>

                    <div>
                        <legend>Saída Final</legend>
                        <input type="datetime-local" id="data" class="input_cab" name="data_fim" value="<?php if(!empty($dados['data_fim'])){echo $dados['data_fim'];} ?>">
                

                    
                    </div>
                 </div>

                 <div class="filtro_dia">
                 <legend>Veículo</legend>
                 <select  id=""  name="carro">
                                                        <option value=""></option>
                                                    <?php foreach ($results_carro as $row) { 
                                                        echo '<option value="' . $row["id"] . '">' . $row["marca"] . " - " . $row["modelo"] . " - " . $row["placa"] .'</option>';
                                                    } ?>
                                                </select>
                 </div>
                 <div  class="filtro_dia">
                 <legend>Motorista</legend>
                 <select  id=""  name="motorista">
                                                        <option value=""></option>
                                                    <?php foreach ($results_motorista as $row) { 
                                                        echo '<option value="' . $row["id"] . '">' . $row["nome"] . " " . $row["sobrenome"] .'</option>';
                                                    } ?>
                                                </select>
                 </div>
                   <input type="submit" id="abrir" value="Filtrar"> 
        </form>
        <br>
                <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Veículo</th>
                        <th>Placa</th>
                        <th>Saída</th>
                        <th>Entrada</th>
                        <th>KM Saída</th>
                        <th>KM Entrada</th>
                        <th>KM Percorrido</th>
                        <th>Motorista</th>
                        <th>Checklist Saída</th>
                        <th>Checklist Entrada</th>
                        
                    </tr>
                </thead>
                <tbody>
          
                    <?php foreach($results_controle as $controle){ ?>
                        
                            <tr class="linhas_base">
                            
                                    <td><a href="resume.php?id=<?php echo $controle['id']; ?>"><?php echo $controle['id']; ?></a></td>  
                                    <td><a href="resume.php?id=<?php echo $controle['id']; ?>"><?php echo $controle['marca']; echo"-"; echo$controle['modelo']; ?></a></td>
                                    <td><a href="resume.php?id=<?php echo $controle['id']; ?>"><?php echo $controle['placa']; ?></a></td>
                                    <td><a href="resume.php?id=<?php echo $controle['id']; ?>"><?php echo date('d/m/Y H:i', strtotime($controle['data_saida'])); ?></a></td>
                                    <td><a href="resume.php?id=<?php echo $controle['id']; ?>"><?php echo date('d/m/Y H:i', strtotime($controle['data_entrada'])); ?></a></td>
                                    <td><a href="resume.php?id=<?php echo $controle['id']; ?>"><?php echo $controle['km_saida']; ?></a></td>
                                    <td><a href="resume.php?id=<?php echo $controle['id']; ?>"><?php echo $controle['km_entrada']; ?></a></td>
                                    <td><a href="resume.php?id=<?php echo $controle['id']; ?>"><?php echo $controle['km_percorridos']; ?></a></td>
                                    <td><a href="resume.php?id=<?php echo $controle['id']; ?>"><?php echo $controle['nome_motorista']; ?></a></td>
                                    <td><a href="resume.php?id=<?php echo $controle['id']; ?>"><?php echo $controle['checklist_saida']; ?></a></td>
                                    <td><a href="resume.php?id=<?php echo $controle['id']; ?>"><?php echo $controle['checklist_entrada']; ?></a></td>
                           
                            </tr>
                      
                        
                       
                        
                       
                        
                      
                    <?php } ?>
                   
                </tbody>
            </table>
           
        </div>
       
    </div>
    
</body>
</html>