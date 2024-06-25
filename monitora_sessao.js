
        let tempo_inatividade;
        

        function resetarTempo() {
            clearTimeout(tempo_inatividade);
            tempo_inatividade = setTimeout(expirarSessao, 900000); // 1 minutos
        }

        function expirarSessao() {
            // Faça uma requisição ao servidor para destruir a sessão
            fetch('logout.php').then(response => {
                window.location.href = 'inicio.php'; // Redireciona para a página de login
            });
        }


        // Eventos para resetar o tempo de inatividade
        window.onload = resetarTempo;
        document.onmousemove = resetarTempo;
        document.onclick = resetarTempo;
       
   