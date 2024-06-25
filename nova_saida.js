//Adiciona valor ao INPUT
document.querySelectorAll('.clickable3').forEach(function(div) {
    div.addEventListener('click', function() {
        // Obtém o texto da div clicada
        var divText = this.textContent || this.innerText;
        
        // Procura o campo de texto pela ID e insere o texto
        var selectedDiv = document.getElementById('selectedDiv3');
        if (selectedDiv) {
            selectedDiv.value = divText;
        }
    });
});

//Altera classe da DIV
document.querySelectorAll('.clickable3').forEach(function(div) {
    div.addEventListener('click', function() {
        // Adiciona uma classe diferente ('others-selected') às demais divs
        document.querySelectorAll('.clickable3').forEach(function(otherDiv) {
            if (otherDiv !== div) {
                otherDiv.classList.add('others-selected');
                otherDiv.classList.remove('selected');
            }
        });

        // Adiciona a classe 'selected' à div clicada
        div.classList.add('selected');
        // Remove a classe 'others-selected' da div clicada, se presente
        div.classList.remove('others-selected');
    });
});

//Altera classe text acondicionamento

document.getElementById('nao_conforme1').addEventListener('click', function() {
    document.getElementById('text_nc_acondicionamento').style.display = 'block';
});

document.getElementById('conforme1').addEventListener('click', function() {
    document.getElementById('text_nc_acondicionamento').style.display = 'none';
});



// VISUALIZAÇÃO PREVIA DOS ARQUIVOS

document.getElementById('input1').addEventListener('change', function(event) {
    showPreview(event, 'preview1');
});

document.getElementById('input2').addEventListener('change', function(event) {
    showPreview(event, 'preview2');
});

document.getElementById('input3').addEventListener('change', function(event) {
    showPreview(event, 'preview3');
});

document.getElementById('input4').addEventListener('change', function(event) {
    showPreview(event, 'preview4');
});

document.getElementById('input5').addEventListener('change', function(event) {
    showPreview(event, 'preview5');
});

function showPreview(event, previewId) {
    const files = event.target.files;
    const preview = document.getElementById(previewId);
    preview.innerHTML = ''; // Limpar qualquer conteúdo existente

    for (const file of files) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const url = e.target.result;
            const element = document.createElement(file.type.startsWith('image/') ? 'img' : 'video');
            element.src = url;

            if (file.type.startsWith('video/')) {
                element.controls = true;
            }

            preview.appendChild(element);
        };
        reader.readAsDataURL(file);
    }
}

// PARA ABRIR TELA DE AVISO

const tooltips = {
    campo1: "Inserir o KM do momento da entrada do veículo na empresa.",
    campo2: "Inserir o NÍVEL de combustível no tanque, na entrada do veículo na empresa.",
    campo3: "Digitar os KMs de abastecimentos da viagem (caso houver), o motorista coloca na notinhas de abastecimentos, digitar esses km separados por vírgula.",
    campo4: "Efetuar o checklist ocular, e casou houver avarias será obrigatório digitar o motivo e inserir pelo menos uma foto da mesma.",
    campo5: "Inserir o KM do momento da saída do veículo na empresa.",
    campo6: "Digitar o nome das cidades onde o motorista irá visitar clientes.",
    campo7: "Digitar o nome dos clientes onde o motorista irá efetuar a visita.",

};

function showTooltip(id) {
    const tooltipText = tooltips[id];
    if (tooltipText) {
        document.getElementById('tooltip-text').innerText = tooltipText;
        document.getElementById('tooltip').style.display = 'flex';
    }
}

function hideTooltip() {
    document.getElementById('tooltip').style.display = 'none';
}

