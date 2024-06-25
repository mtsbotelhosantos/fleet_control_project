<?php
require ('valida_sessao.php');
include ("conexao.php");

       date_default_timezone_set('America/Sao_Paulo');
       $agora = new DateTime('now');
       $agora_liquido = $agora->format('Y/m/d H:i:s');
       $usuario = $_SESSION['id_session'];
      

       $sql_code = "SELECT nome FROM usuarios WHERE id = $usuario;"; 
       $sql_code = $conn->prepare($sql_code);
       $sql_code->execute();
       $results_user = $sql_code->fetchAll(PDO::FETCH_ASSOC); 

       foreach($results_user as $user){
        $nome = $user['nome'];
       }

?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="inicio.css">
    <script defer src="monitora_sessao.js"></script> 
    <title>Inicio</title>
</head>
<body>
    
    <div class="master"> 

    <img src="assets/logo_sobre_nos5.png" alt="logo_canastra" srcset="" id="logo">
        <p class="nome">Olá <?php echo$nome;?>!</p>
        

      
  

      

        <a href="controle.php">
            <div class="chose"> 
                <i class="material-symbols-outlined">directions_car</i>
                
                <p>Controle de Saída</p>

            </div>
        </a>

        <a href="historico.php">
        <div class="chose">
        <i class="material-symbols-outlined">schedule</i>
            <p>Histórico</p>
        </div>
       </a>
        
        <a href="logout.php">
            <div class="exit"><i class="material-symbols-outlined">logout</i><p> Sair</p></div>
        </a>
        

        
    </div>

    
    
</body>
</html>