// ==========================================
// Máscara Dinâmica Integrada (CPF/CNPJ + Radios)
// ==========================================
function mascaraDinamica(i) {
    let v = i.value.replace(/\D/g, ""); 
    
    const radioFisica = document.getElementById('radioFisica');
    const radioJuridica = document.getElementById('radioJuridica');

    if (v.length > 0 && v.length <= 11) { 
        if (radioFisica && !radioFisica.checked) {
            radioFisica.checked = true; 
            i.placeholder = "CPF:";
        }
        v = v.replace(/(\d{3})(\d)/, "$1.$2");
        v = v.replace(/(\d{3})(\d)/, "$1.$2");
        v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    } else if (v.length > 11) { 
        if (radioJuridica && !radioJuridica.checked) {
            radioJuridica.checked = true;
            i.placeholder = "CNPJ:";
        }
        v = v.replace(/^(\d{2})(\d)/, "$1.$2");
        v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
        v = v.replace(/\.(\d{3})(\d)/, ".$1/$2");
        v = v.replace(/(\d{4})(\d)/, "$1-$2");
    }
    
    i.value = v;
}

// Configurações executadas ao carregar a página
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Configuração dos botões CPF/CNPJ manuais
    const radioFisica = document.getElementById('radioFisica');
    const radioJuridica = document.getElementById('radioJuridica');
    const campo = document.getElementById('campoCpfCnpj');

    if(radioFisica && radioJuridica && campo) {
        radioFisica.addEventListener('change', function() {
            campo.placeholder = "CPF:";
            campo.maxLength = 14;
            mascaraDinamica(campo);
        });

        radioJuridica.addEventListener('change', function() {
            campo.placeholder = "CNPJ:";
            campo.maxLength = 18;
            mascaraDinamica(campo);
        });
    }

    // 2. Validador de Dias da Semana (Exigir ao menos 1)
    const checkboxesDias = document.querySelectorAll('input[name="dias"]');
    
    if(checkboxesDias.length > 0) {
        function validarDias() {
            const checados = document.querySelectorAll('input[name="dias"]:checked');
            // Se nenhum estiver marcado, emite um alerta de campo obrigatório no primeiro checkbox
            if (checados.length === 0) {
                checkboxesDias[0].setCustomValidity("Por favor, selecione ao menos um dia da semana.");
            } else {
                checkboxesDias[0].setCustomValidity(""); // Limpa o erro
            }
        }
        
        // Toda vez que clicar em um checkbox, ele recalcula se é válido
        checkboxesDias.forEach(cb => cb.addEventListener('change', validarDias));
        
        // Roda a verificação uma vez quando a página abre para já começar bloqueado
        validarDias();
    }
});

// ==========================================
// Outras Máscaras do Sistema
// ==========================================
function mascaraCPF(i) {
    let v = i.value.replace(/\D/g, "");
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    i.value = v;
}

function mascaraTelefone(i) {
    let v = i.value.replace(/\D/g, "");
    v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
    v = v.replace(/(\d)(\d{4})$/, "$1-$2");
    i.value = v;
}

function mascaraCEP(i) {
    let v = i.value.replace(/\D/g, "");
    v = v.replace(/^(\d{5})(\d)/, "$1-$2");
    i.value = v;
}

function mascaraRG(i) {
    let v = i.value.replace(/\D/g, "");
    v = v.replace(/(\d{2})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    i.value = v;
}

function mascaraMoeda(i) {
    let v = i.value.replace(/\D/g, "");
    if (v === "") {
        i.value = "";
        return;
    }
    v = (parseInt(v, 10) / 100).toFixed(2).replace(".", ",");
    v = v.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
    i.value = "R$ " + v;
}