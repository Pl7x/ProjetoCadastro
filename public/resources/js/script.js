function mascaraDinamica(i) {

    let v = i.value.replace(/\D/g, "");

    const radioFisica = document.getElementById('radioFisica');
    const radioJuridica = document.getElementById('radioJuridica');

    // =====================================
    // QUANDO CPF ESTIVER SELECIONADO
    // =====================================

    if (radioFisica.checked) {

        // limita CPF
        v = v.substring(0, 11);

        i.placeholder = "CPF:";

        v = v.replace(/(\d{3})(\d)/, "$1.$2");
        v = v.replace(/(\d{3})(\d)/, "$1.$2");
        v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

        i.value = v;

        return;
    }

    // =====================================
    // QUANDO CNPJ ESTIVER SELECIONADO
    // =====================================

    if (radioJuridica.checked) {

        // limita CNPJ
        v = v.substring(0, 14);

        i.placeholder = "CNPJ:";

        v = v.replace(/^(\d{2})(\d)/, "$1.$2");
        v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
        v = v.replace(/\.(\d{3})(\d)/, ".$1/$2");
        v = v.replace(/(\d{4})(\d{1,2})$/, "$1-$2");

        i.value = v;

        return;
    }

    // =====================================
    // AUTO DETECÇÃO (sem radio marcado)
    // =====================================

    if (v.length <= 11) {

        radioFisica.checked = true;

        i.placeholder = "CPF:";

        v = v.substring(0, 11);

        v = v.replace(/(\d{3})(\d)/, "$1.$2");
        v = v.replace(/(\d{3})(\d)/, "$1.$2");
        v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

    } else {

        radioJuridica.checked = true;

        i.placeholder = "CNPJ:";

        v = v.substring(0, 14);

        v = v.replace(/^(\d{2})(\d)/, "$1.$2");
        v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
        v = v.replace(/\.(\d{3})(\d)/, ".$1/$2");
        v = v.replace(/(\d{4})(\d{1,2})$/, "$1-$2");
    }

    i.value = v;
}

// CEP
function mascaraCEP(i) {

    let v = i.value.replace(/\D/g, "");

    // Limita 8 números
    v = v.substring(0, 8);

    v = v.replace(/(\d{5})(\d)/, "$1-$2");

    i.value = v;
}

// Telefone
function mascaraTelefone(i) {

    let v = i.value.replace(/\D/g, "");

    // Limita 11 números
    v = v.substring(0, 11);

    if (v.length <= 10) {

        // Telefone fixo
        v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
        v = v.replace(/(\d{4})(\d)/, "$1-$2");

    } else {

        // Celular
        v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
        v = v.replace(/(\d{5})(\d)/, "$1-$2");
    }

    i.value = v;
}

// RG
function mascaraRG(i) {

    let v = i.value.replace(/\D/g, "");

    // Limita 9 números
    v = v.substring(0, 9);

    v = v.replace(/(\d{2})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d{1})$/, "$1-$2");

    i.value = v;
}

// CPF fixo
function mascaraCPF(i) {

    let v = i.value.replace(/\D/g, "");

    // Limita 11 números
    v = v.substring(0, 11);

    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

    i.value = v;
}

// Moeda
function mascaraMoeda(i) {

    let v = i.value.replace(/\D/g, "");

    if (v === "") {
        i.value = "";
        return;
    }

    v = (parseInt(v, 10) / 100).toFixed(2) + "";

    v = v.replace(".", ",");

    // Adiciona pontos de milhar
    v = v.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    i.value = "R$ " + v;
}

// =========================================
// Sidebar
// =========================================

function toggleSidebar() {
    document.getElementById('layout').classList.toggle('collapsed');
}

// =========================================
// Relógio
// =========================================

function atualizarRelogio() {

    const agora = new Date();

    const opcoesData = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };

    const dataFormatada = agora.toLocaleDateString('pt-BR', opcoesData);

    const horaFormatada = agora.toLocaleTimeString('pt-BR');

    const dataFinal =
        dataFormatada.charAt(0).toUpperCase() +
        dataFormatada.slice(1);

    const relogioElemento = document.getElementById('relogio');

    if (relogioElemento) {

        relogioElemento.innerHTML =
            `<i class="fa-regular fa-clock"></i> ${dataFinal} - ${horaFormatada}`;
    }
}

setInterval(atualizarRelogio, 1000);

atualizarRelogio();

// =========================================
// SALVAR FUNCIONÁRIO
// =========================================

document.addEventListener('DOMContentLoaded', function () {

    const formCadastro = document.getElementById('formCadastro');

    const msgRetorno =
        document.getElementById('mensagemRetorno');

    if (formCadastro) {

        formCadastro.addEventListener('submit', function (event) {

            event.preventDefault();

            msgRetorno.className = 'msg-alert';

            msgRetorno.innerHTML = '';

            const formData = new FormData(this);

            fetch('api/salvar_funcionario.php', {
                method: 'POST',
                body: formData
            })

            .then(response => response.json())

            .then(data => {

                if (data.success) {

                    msgRetorno.classList.add('msg-success');

                    msgRetorno.innerHTML =
                        '<i class="fa-solid fa-circle-check"></i> ' +
                        data.message;

                    setTimeout(() => {

                        window.location.href = 'funcionario.php';

                    }, 2000);

                } else {

                    msgRetorno.classList.add('msg-error');

                    msgRetorno.innerHTML =
                        '<i class="fa-solid fa-triangle-exclamation"></i> ' +
                        data.message;
                }
            })

            .catch(error => {

                console.error('Erro:', error);

                msgRetorno.classList.add('msg-error');

                msgRetorno.innerHTML =
                    '<i class="fa-solid fa-triangle-exclamation"></i> Falha ao conectar com o servidor.';
            });
        });
    }
});

// =========================================
// MODAL STATUS
// =========================================

// Abrir modal
/* =========================================
   ABRIR MODAL
========================================= */

function abrirModalStatus(id, statusAtual) {

    document.getElementById(
        'edit_id_funcionario'
    ).value = id;

    document.getElementById(
        'edit_status'
    ).value = statusAtual;

    // salva status atual
    document.getElementById(
        'formAtualizarStatus'
    ).dataset.statusAtual = statusAtual;

    const msgModal =
        document.getElementById('msgModal');

    msgModal.className = 'msg-alert';

    msgModal.innerHTML = '';

    document.getElementById('modalStatus')
        .classList.add('active');
}

/* =========================================
   FECHAR MODAL
========================================= */

function fecharModalStatus() {

    document.getElementById('modalStatus')
        .classList.remove('active');
}

/* =========================================
   SUBMIT
========================================= */

document.addEventListener('DOMContentLoaded', function () {

    const formAtualizarStatus =
        document.getElementById('formAtualizarStatus');

    if (!formAtualizarStatus) return;

    formAtualizarStatus.addEventListener(
        'submit',
        function (event) {

            event.preventDefault();

            const msgModal =
                document.getElementById('msgModal');

            msgModal.className = 'msg-alert';

            msgModal.innerHTML = '';

            /* =====================================
               STATUS
            ===================================== */

            const statusAtual =
                formAtualizarStatus.dataset.statusAtual;

            const novoStatus =
                document.getElementById('edit_status').value;

            /* =====================================
               BLOQUEIA STATUS IGUAL
            ===================================== */

            if (statusAtual === novoStatus) {

                msgModal.classList.add('msg-error');

                msgModal.innerHTML =
                    '<i class="fa-solid fa-triangle-exclamation"></i> ' +
                    'O funcionário já está com esse status.';

                return;
            }

            /* =====================================
               ENVIO
            ===================================== */

            const formData = new FormData(this);

            fetch('api/atualizar_status.php', {

                method: 'POST',

                body: formData
            })

            .then(response => response.json())

            .then(data => {

                if (data.success) {

                    msgModal.classList.add('msg-success');

                    msgModal.innerHTML =
                        '<i class="fa-solid fa-circle-check"></i> ' +
                        data.message;

                    // atualiza status salvo
                    formAtualizarStatus.dataset.statusAtual =
                        novoStatus;

                    setTimeout(() => {

                        window.location.reload();

                    }, 1200);

                } else {

                    msgModal.classList.add('msg-error');

                    msgModal.innerHTML =
                        '<i class="fa-solid fa-triangle-exclamation"></i> ' +
                        data.message;
                }
            })

            .catch(error => {

                console.error(error);

                msgModal.classList.add('msg-error');

                msgModal.innerHTML =
                    '<i class="fa-solid fa-triangle-exclamation"></i> ' +
                    'Falha ao salvar.';
            });
        }
    );
});

/* =========================================
   FECHAR AO CLICAR FORA
========================================= */

window.addEventListener('click', function(e){

    const modal =
        document.getElementById('modalStatus');

    if(e.target === modal){

        fecharModalStatus();
    }
});



/* =========================
   MODAL STATUS Curso
========================= */






let cursoAtual = null;

const modal = document.getElementById("modalStatusCurso");

const form = document.getElementById("formStatusCurso");

const mensagem = document.getElementById("modalMensagemCurso");

function abrirModalStatusCurso(id){

    cursoAtual = id;

    const badge = document.getElementById(
        `status-badge-${id}`
    );

    let status = "ativo";

    if(badge.classList.contains("inativo")){

        status = "inativo";
    }

    document.getElementById("edit_id_curso").value = id;

    document.getElementById("edit_status").value = status;

    limparMensagem();

    modal.classList.add("active");
}

function fecharModalStatusCurso(){

    modal.classList.remove("active");

    limparMensagem();
}

function limparMensagem(){

    mensagem.className = "modal-alert";

    mensagem.innerHTML = "";
}

function mostrarMensagem(tipo,texto){

    mensagem.className =
        `modal-alert active ${tipo}`;

    mensagem.innerHTML = `
        <i class="fa-solid fa-circle-exclamation"></i>
        ${texto}
    `;
}

if(form){

    form.addEventListener("submit", async e => {

    e.preventDefault();

    const novoStatus =
        document.getElementById("edit_status").value;

    const badge = document.getElementById(
        `status-badge-${cursoAtual}`
    );

    const statusAtual =
        badge.classList.contains("inativo")
        ? "inativo"
        : "ativo";

    if(novoStatus === statusAtual){

        mostrarMensagem(
            "warning",
            "O curso já está com esse status."
        );

        return;
    }

    try{

        const response = await fetch(
            "./api/atualizar_status_curso.php",
            {
                method:"POST",

                body:new URLSearchParams({
                    id_curso: cursoAtual,
                    status: novoStatus
                }),

                headers:{
                    "Content-Type":
                    "application/x-www-form-urlencoded"
                }
            }
        );

        const data = await response.json();

        if(data.success){

            badge.classList.remove(
                "ativo",
                "inativo"
            );

            badge.classList.add(novoStatus);

            document.getElementById(
                `status-texto-${cursoAtual}`
            ).innerText =
                novoStatus.charAt(0).toUpperCase() +
                novoStatus.slice(1);

            mostrarMensagem(
                "success",
                data.message
            );

            setTimeout(() => {

                fecharModalStatusCurso();

            },1200);

        }else{

            mostrarMensagem(
                "error",
                data.message
            );
        }

    }catch(error){

        console.error(error);

        mostrarMensagem(
            "error",
            "Erro ao atualizar."
        );
    }
});

window.addEventListener("click",e=>{

    if(e.target === modal){

        fecharModalStatusCurso();
    }
});
}


