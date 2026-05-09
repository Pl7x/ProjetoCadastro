// =========================================
// Máscara Dinâmica Integrada (CPF/CNPJ + Radios)
// =========================================
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

function mascaraCEP(i) {
    let v = i.value.replace(/\D/g, "");
    v = v.replace(/(\d{5})(\d)/, "$1-$2");
    i.value = v;
}

function mascaraTelefone(i) {
    let v = i.value.replace(/\D/g, "");
    v = v.replace(/(\d{2})(\d)/, "($1) $2");
    v = v.replace(/(\d{5})(\d)/, "$1-$2");
    i.value = v;
}

function mascaraRG(i) {
    let v = i.value.replace(/\D/g, "");
    v = v.replace(/(\d{2})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d)/, "$1-$2");
    i.value = v;
}

function mascaraCPF(i) {
    let v = i.value.replace(/\D/g, "");
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    i.value = v;
}

function mascaraMoeda(i) {
    let v = i.value.replace(/[^\d]/g, "");
    v = (v / 100).toFixed(2) + "";
    v = v.replace('.', ',');
    i.value = v;
}

// Função para abrir/fechar a sidebar
function toggleSidebar() {
    document.getElementById('layout').classList.toggle('collapsed');
}

// Função do relógio
function atualizarRelogio() {
    const agora = new Date();
    const opcoesData = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const dataFormatada = agora.toLocaleDateString('pt-BR', opcoesData);
    const horaFormatada = agora.toLocaleTimeString('pt-BR');
    
    // Capitaliza a primeira letra do dia da semana
    const dataFinal = dataFormatada.charAt(0).toUpperCase() + dataFormatada.slice(1);
    
    // Verifica se o elemento do relógio existe antes de tentar atualizar (evita erros em outras páginas)
    const relogioElemento = document.getElementById('relogio');
    if (relogioElemento) {
        relogioElemento.innerHTML = `<i class="fa-regular fa-clock"></i> ${dataFinal} - ${horaFormatada}`;
    }
}

// Inicia o relógio
setInterval(atualizarRelogio, 1000);
atualizarRelogio();



//salvar funcionario

document.addEventListener('DOMContentLoaded', function() {
    
    const formCadastro = document.getElementById('formCadastro');
    const msgRetorno = document.getElementById('mensagemRetorno'); // Captura a div que fica debaixo da senha
    
    if (formCadastro) {
        formCadastro.addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o ecrã de recarregar

            // Limpa mensagens anteriores antes de enviar
            msgRetorno.className = 'msg-alert';
            msgRetorno.innerHTML = '';

            const formData = new FormData(this);

            fetch('api/salvar_funcionario.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // SUCESSO: Aparece a caixa verde! (Sem alert chato do navegador)
                    msgRetorno.classList.add('msg-success');
                    msgRetorno.innerHTML = '<i class="fa-solid fa-circle-check"></i> ' + data.message;
                    
                    // Aguarda 2 segundos para o utilizador ler, e depois redireciona para a página CORRETA
                    setTimeout(() => {
                        window.location.href = 'funcionario.php'; // Corrigido para singular!
                    }, 2000);

                } else {
                    // ERRO: Aparece a caixa vermelha (ex: email já existe)
                    msgRetorno.classList.add('msg-error');
                    msgRetorno.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> ' + data.message;
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                msgRetorno.classList.add('msg-error');
                msgRetorno.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Falha ao conectar com o servidor.';
            });
        });
    }
});



// ==========================================
// FUNÇÕES DO MODAL DE STATUS
// ==========================================

// Abre o modal e preenche os campos invisíveis
function abrirModalStatus(id, statusAtual) {
    document.getElementById('edit_id_funcionario').value = id;
    document.getElementById('edit_status').value = statusAtual;
    
    // Limpa a caixa de mensagens antiga se houver
    document.getElementById('msgModal').className = 'msg-alert';
    document.getElementById('msgModal').innerHTML = '';

    // Exibe o modal na tela
    document.getElementById('modalStatus').classList.add('active');
}

// Fecha o modal
function fecharModalStatus() {
    document.getElementById('modalStatus').classList.remove('active');
}

// Lida com o envio do Formulário do Modal
document.addEventListener('DOMContentLoaded', function() {
    const formAtualizarStatus = document.getElementById('formAtualizarStatus');
    
    if (formAtualizarStatus) {
        formAtualizarStatus.addEventListener('submit', function(event) {
            event.preventDefault();
            const msgModal = document.getElementById('msgModal');
            
            msgModal.className = 'msg-alert';
            msgModal.innerHTML = '';

            const formData = new FormData(this);

            fetch('api/atualizar_status.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    msgModal.classList.add('msg-success');
                    msgModal.innerHTML = '<i class="fa-solid fa-circle-check"></i> ' + data.message;
                    
                    // Aguarda 1.5s e recarrega a página para atualizar a tabela
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    msgModal.classList.add('msg-error');
                    msgModal.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> ' + data.message;
                }
            })
            .catch(error => {
                msgModal.classList.add('msg-error');
                msgModal.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Falha ao salvar.';
            });
        });
    }
});