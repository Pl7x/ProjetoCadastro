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
