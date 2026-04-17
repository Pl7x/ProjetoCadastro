function mascaraCPF(el) {
    let v = el.value.replace(/\D/g, "");
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    el.value = v;
}

function mascaraTelefone(el) {
    let v = el.value.replace(/\D/g, "");
    v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
    v = v.replace(/(\d{5})(\d)/, "$1-$2");
    el.value = v;
}

function mascaraCEP(el) {
    let v = el.value.replace(/\D/g, "");
    v = v.replace(/(\d{5})(\d)/, "$1-$2");
    el.value = v;
}

function mascaraRG(el) {
    let v = el.value.replace(/\D/g, "");
    v = v.replace(/(\d{2})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d)/, "$1-$2");
    el.value = v;
}