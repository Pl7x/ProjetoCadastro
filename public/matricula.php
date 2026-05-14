<?php
$titulo_pagina = "Ficha de Matrícula";
$pagina_ativa = "matricula";

include 'resources/layout/header.php';

use App\Providers\DatabaseProvider;

$usuarioLogadoId = $user['id'] ?? null;

$usuarios = [];
$cursos = [];

try {

    $conn = DatabaseProvider::connect();

    // =========================================
    // USUÁRIOS ATIVOS
    // =========================================
    $queryUsuarios = $conn->query("
        SELECT id, nome 
        FROM usuario 
        WHERE status = 'ativo' 
        ORDER BY nome ASC
    ");

    if ($queryUsuarios) {

        while ($row = $queryUsuarios->fetch_assoc()) {
            $usuarios[] = $row;
        }
    }

    // =========================================
    // CURSOS ATIVOS
    // =========================================
    $queryCursos = $conn->query("
        SELECT id, nome
        FROM curso
        WHERE status = 'ativo'
        ORDER BY nome ASC
    ");

    if ($queryCursos) {

        while ($row = $queryCursos->fetch_assoc()) {
            $cursos[] = $row;
        }
    }

} catch (Exception $e) {

    echo "Erro ao carregar dados: " . $e->getMessage();
    exit;
}
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert-success">
        <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert-error">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<link rel="stylesheet" href="resources/css/style.css?v=<?php echo time(); ?>">

<form name="matricula"
      class="form-moderno"
      method="POST"
      action="salvar.php">

    <div class="form-header">

        <h2>Ficha de Matrícula</h2>

        <p>
            Preencha os dados abaixo com atenção para realizar o cadastro.
        </p>

    </div>

    <div class="container-colunas">

        <!-- =====================================================
             COLUNA 1
        ====================================================== -->
        <div class="coluna">

            <!-- =====================================================
                 DADOS DO ALUNO
            ====================================================== -->
            <div class="sessao">

                <h3 class="titulo-sessao">
                    Dados do Aluno
                </h3>

                <div class="linha">

                    <input type="text"
                           name="nome_aluno"
                           placeholder="Nome Completo:"
                           style="flex: 1;"
                           required>

                </div>

                <div class="linha">

                    <input type="text"
                           name="endereco_aluno"
                           placeholder="Endereço:"
                           style="flex: 3;"
                           required>

                    <input type="text"
                           name="complemento_aluno"
                           placeholder="Complemento (Opcional):"
                           style="flex: 1;">

                </div>

                <div class="linha">

                    <input type="text"
                           name="cidade_aluno"
                           placeholder="Cidade:"
                           style="flex: 2;"
                           required>

                    <input type="text"
                           name="bairro_aluno"
                           placeholder="Bairro:"
                           style="flex: 2;"
                           required>

                    <input type="text"
                           name="cep_aluno"
                           placeholder="CEP:"
                           style="flex: 1.5;"
                           maxlength="9"
                           oninput="mascaraCEP(this)">

                    <input type="text"
                           name="estado_aluno"
                           placeholder="Estado:"
                           style="flex: 1;">

                    <div style="flex: 1.8; display: flex; align-items: center; gap: 8px;">

                        <strong class="label-destaque">
                            Nasc:
                        </strong>

                        <input type="date"
                               name="data_nasc_aluno"
                               id="data_nasc_aluno"
                               style="flex: 1;"
                               title="Data de Nascimento"
                               required>

                    </div>

                </div>

                <div class="linha">

                    <input type="text"
                           name="profissao_aluno"
                           placeholder="Profissão (Opcional):"
                           style="flex: 2;">

                    <input type="email"
                           name="email_aluno"
                           placeholder="Email (Opcional):"
                           style="flex: 2;">

                    <input type="text"
                           name="whatsapp"
                           placeholder="Whatsapp:"
                           style="flex: 1.5;"
                           maxlength="15"
                           oninput="mascaraTelefone(this)"
                           required>

                </div>

                <div class="linha">

                    <input type="text"
                           name="escolaridade_aluno"
                           placeholder="Escolaridade:"
                           style="flex: 2;"
                           required>

                    <div class="opcoes-inline" style="flex: 1.5;">

                        <strong>Sexo:</strong>

                        <label>
                            <input type="radio"
                                   name="sexo_aluno"
                                   value="M"
                                   required> M
                        </label>

                        <label>
                            <input type="radio"
                                   name="sexo_aluno"
                                   value="F"> F
                        </label>

                    </div>

                    <input type="text"
                           name="rg_aluno"
                           placeholder="RG:"
                           style="flex: 1.5;"
                           maxlength="12"
                           oninput="mascaraRG(this)"
                           required>

                    <input type="text"
                           name="tel_comercial_aluno"
                           placeholder="Telefone Comercial (Opcional):"
                           style="flex: 2;"
                           maxlength="15"
                           oninput="mascaraTelefone(this)">

                </div>

                <div class="linha">

                    <input type="text"
                           name="medicacao_aluno"
                           placeholder="Medicação (Opcional):"
                           style="flex: 2;">

                    <select name="estado_civil_aluno"
                            style="flex: 1.5;"
                            required>

                        <option value="" disabled selected >
                            Estado Civil
                        </option>

                        <option value="Solteiro(a)">
                            Solteiro(a)
                        </option>

                        <option value="Casado(a)">
                            Casado(a)
                        </option>

                        <option value="Divorciado(a)">
                            Divorciado(a)
                        </option>

                        <option value="Viúvo(a)">
                            Viúvo(a)
                        </option>

                        <option value="União Estável">
                            União Estável
                        </option>

                    </select>

                    <input type="text"
                           name="cpf_aluno"
                           placeholder="CPF:"
                           style="flex: 1.5;"
                           maxlength="14"
                           oninput="mascaraCPF(this)"
                           required>

                </div>

            </div>

            <div class="linha" style="margin-bottom: 15px;">
                <label style="display: flex; align-items: center; gap: 10px; font-weight: 600; cursor: pointer;">
                    <input type="checkbox" id="alunoResponsavel">
                    O aluno é o próprio responsável financeiro
                </label>
            </div>

            <!-- =====================================================
                 RESPONSÁVEL FINANCEIRO
            ====================================================== -->
            <div class="sessao" id="sessaoResponsavel">

                <h3 class="titulo-sessao">
                    Responsável Financeiro
                </h3>

                <div class="linha">

                    <input type="text"
                           name="nome_responsavel"
                           class="campo-responsavel"
                           placeholder="Nome Completo:"
                           style="flex: 1;">

                </div>

                <div class="linha">

                    <input type="text"
                           name="endereco_responsavel"
                           class="campo-responsavel"
                           placeholder="Endereço:"
                           style="flex: 3;">

                    <input type="text"
                           name="complemento_responsavel"
                           placeholder="Complemento (Opcional):"
                           style="flex: 1;">

                </div>

                <div class="linha">

                    <input type="text"
                           name="cidade_responsavel"
                           class="campo-responsavel"
                           placeholder="Cidade:"
                           style="flex: 2;">

                    <input type="text"
                           name="bairro_responsavel"
                           class="campo-responsavel"
                           placeholder="Bairro:"
                           style="flex: 2;">

                    <input type="text"
                           name="cep_responsavel"
                           placeholder="CEP:"
                           style="flex: 1.5;"
                           maxlength="9"
                           oninput="mascaraCEP(this)">

                    <input type="text"
                           name="estado_responsavel"
                           placeholder="Estado:"
                           style="flex: 1;">

                    <div style="flex: 1.8; display: flex; align-items: center; gap: 8px;">

                        <strong class="label-destaque">
                            Nasc:
                        </strong>

                        <input type="date"
                               name="data_nasc_responsavel"
                               class="campo-responsavel"
                               style="flex: 1;"
                               title="Data de Nascimento">

                    </div>

                </div>

                <div class="linha">

                    <input type="text"
                           name="profissao_responsavel"
                           placeholder="Profissão (Opcional):"
                           style="flex: 2;">

                    <input type="email"
                           name="email_responsavel"
                           placeholder="Email (Opcional):"
                           style="flex: 2;">

                    <input type="text"
                           name="tel_residencial"
                           class="campo-responsavel"
                           placeholder="Telefone Residencial:"
                           style="flex: 1.5;"
                           maxlength="15"
                           oninput="mascaraTelefone(this)">

                </div>

                <div class="linha">

                    <input type="text"
                           name="escolaridade_responsavel"
                           class="campo-responsavel"
                           placeholder="Escolaridade:"
                           style="flex: 2;">

                    <div class="opcoes-inline" style="flex: 1.5;">

                        <strong>Sexo:</strong>

                        <label>
                            <input type="radio"
                                   name="sexo_responsavel"
                                   value="M"
                                   class="campo-responsavel-radio"> M
                        </label>

                        <label>
                            <input type="radio"
                                   name="sexo_responsavel"
                                   value="F"> F
                        </label>

                    </div>

                    <input type="text"
                           name="rg_responsavel"
                           class="campo-responsavel"
                           placeholder="RG:"
                           style="flex: 1.5;"
                           maxlength="12"
                           oninput="mascaraRG(this)">

                    <input type="text"
                           name="tel_comercial_responsavel"
                           placeholder="Telefone Comercial (Opcional):"
                           style="flex: 2;"
                           maxlength="15"
                           oninput="mascaraTelefone(this)">

                </div>

                <div class="linha">

                    <input type="text"
                           name="cpf_cnpj_responsavel"
                           id="campoCpfCnpj"
                           class="campo-responsavel"
                           placeholder="CPF/CNPJ:"
                           style="flex: 2;"
                           maxlength="18"
                           oninput="mascaraDinamica(this)">

                    <select name="estado_civil_responsavel"
                            class="campo-responsavel"
                            style="flex: 1.5;">

                        <option value="" disabled selected>
                            Estado Civil
                        </option>

                        <option value="Solteiro(a)">
                            Solteiro(a)
                        </option>

                        <option value="Casado(a)">
                            Casado(a)
                        </option>

                        <option value="Divorciado(a)">
                            Divorciado(a)
                        </option>

                        <option value="Viúvo(a)">
                            Viúvo(a)
                        </option>

                        <option value="União Estável">
                            União Estável
                        </option>

                    </select>

                    <input type="text"
                           name="cargo_responsavel"
                           placeholder="Cargo (Opcional):"
                           style="flex: 2;">

                </div>

                <div class="linha">

                    <div class="opcoes-inline">

                        <strong>
                            Tipo de Pessoa:
                        </strong>

                        <label>
                            <input type="radio"
                                   name="Pessoa"
                                   value="Fisica"
                                   id="radioFisica"> Física
                        </label>

                        <label>
                            <input type="radio"
                                   name="Pessoa"
                                   value="Juridica"
                                   id="radioJuridica"> Jurídica
                        </label>

                    </div>

                </div>

            </div>

        </div>

        <!-- =====================================================
             COLUNA 2
        ====================================================== -->
        <div class="coluna">

            <!-- =====================================================
                 INVESTIMENTO EDUCACIONAL
            ====================================================== -->
            <div class="sessao">

                <h3 class="titulo-sessao">
                    Investimento Educacional
                </h3>

                <div class="linha">

                    <input type="text"
                           name="taxa_matricula"
                           inputmode="numeric"
                           placeholder="Taxa de Matrícula:"
                           style="flex: 1;"
                           oninput="mascaraMoeda(this)"
                           onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                           required>

                    <input type="text"
                           name="taxa_material"
                           inputmode="numeric"
                           placeholder="Taxa do Material WEB (Opcional):"
                           style="flex: 1.5;"
                           oninput="mascaraMoeda(this)"
                           onkeypress="return event.charCode >= 48 && event.charCode <= 57">

                </div>

                <div class="linha">

                    <input type="text"
                           name="valor_parcela"
                           inputmode="numeric"
                           placeholder="Valor das Parcelas:"
                           style="flex: 1;"
                           oninput="mascaraMoeda(this)"
                           onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                           required>

                    <input type="number"
                           name="num_parcelas"
                           placeholder="Nº de Parcelas:"
                           style="flex: 1;"
                           required>

                </div>

                <div class="linha">

                    <div class="opcoes-inline" style="flex: 1;">

                        <strong>Vencimento:</strong>

                        <label>
                            <input type="radio"
                                   name="vencimento"
                                   value="10"
                                   required> 10
                        </label>

                        <label>
                            <input type="radio"
                                   name="vencimento"
                                   value="20"> 20
                        </label>

                    </div>

                    <input type="text"
                           name="duracao_curso"
                           placeholder="Duração do Curso:"
                           style="flex: 2;"
                           required>

                </div>

            </div>

            <!-- =====================================================
                 DADOS DO CURSO
            ====================================================== -->
            <div class="sessao">

                <h3 class="titulo-sessao">
                    Dados do Curso
                </h3>

                <div class="linha alinha-topo">

                    <div class="bloco-label" style="flex: 2;">

                        <label>Curso</label>

                        <select name="id_curso" required>

                            <option value="" disabled selected>
                                Selecione o curso
                            </option>

                            <?php foreach ($cursos as $curso): ?>

                                <option value="<?= $curso['id']; ?>">

                                    <?= htmlspecialchars($curso['nome']); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="bloco-label" style="flex: 2;">

                        <label>Turma</label>

                        <input type="text"
                               name="turma"
                               placeholder="Ex: Turma A (Opcional)">

                    </div>

                </div>

                <div class="linha alinha-topo">

                    <div class="bloco-label" style="flex: 1.5;">

                        <label>Turno</label>

                        <select name="turno" required>

                            <option value="" disabled selected>
                                Selecione
                            </option>

                            <option value="Manhã">
                                Manhã
                            </option>

                            <option value="Tarde">
                                Tarde
                            </option>

                            <option value="Noite">
                                Noite
                            </option>

                        </select>

                    </div>

                    <div class="bloco-label" style="flex: 1;">

                        <label>Sala</label>

                        <input type="text"
                               name="sala"
                               placeholder="Ex: Lab 1 (Opcional)">

                    </div>

                </div>

                <div class="linha alinha-topo">

                    <div class="bloco-label" style="flex: 1.5;">

                        <label>Início do Curso</label>

                        <input type="date"
                               name="inicio_curso"
                               required>

                    </div>

                    <div class="bloco-label" style="flex: 1.5;">

                        <label>Término do Curso</label>

                        <input type="date"
                               name="termino_curso"
                               required>

                    </div>

                </div>

                <div class="linha">

                    <div class="bloco-label" style="flex: 1;">

                        <label>Carga Horária</label>

                        <input type="text"
                               name="carga_horaria"
                               placeholder="Ex: 120 horas (Opcional)">

                    </div>

                    <div class="bloco-label bloco-horario"
                         style="flex: 1;">

                        <label>Horário</label>

                        <div class="opcoes-inline"
                             style="height: 30px;">

                            <input type="time"
                                   name="hora_inicio"
                                   style="width: auto;"
                                   required>

                            <span>às</span>

                            <input type="time"
                                   name="hora_termino"
                                   style="width: auto;"
                                   required>

                        </div>

                    </div>

                </div>

                <div class="linha">

                    <div class="opcoes-inline check-dias">

                        <strong>Dias:</strong>

                        <label>
                            <input type="checkbox"
                                   name="dias[]"
                                   value="Seg"> Seg
                        </label>

                        <label>
                            <input type="checkbox"
                                   name="dias[]"
                                   value="Ter"> Ter
                        </label>

                        <label>
                            <input type="checkbox"
                                   name="dias[]"
                                   value="Qua"> Qua
                        </label>

                        <label>
                            <input type="checkbox"
                                   name="dias[]"
                                   value="Qui"> Qui
                        </label>

                        <label>
                            <input type="checkbox"
                                   name="dias[]"
                                   value="Sex"> Sex
                        </label>

                        <label>
                            <input type="checkbox"
                                   name="dias[]"
                                   value="Sab"> Sab
                        </label>

                    </div>

                </div>

                <div class="linha alinha-topo"
                     style="margin-top: 15px;">

                    <div class="bloco-label" style="flex: 2;">

                        <label>Contratante</label>

                        <select name="contratante_id" required>

                            <option value=""
                                    disabled
                                    <?= (!$usuarioLogadoId) ? 'selected' : ''; ?>>

                                Selecione o contratante

                            </option>

                            <?php foreach ($usuarios as $usuario): ?>

                                <option value="<?= $usuario['id']; ?>"
                                    <?= ($usuario['id'] == $usuarioLogadoId) ? 'selected' : ''; ?>>

                                    <?= htmlspecialchars($usuario['nome']); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="bloco-label" style="flex: 1.5;">

                        <label>Data Contrato</label>

                        <input type="date"
                               name="data_contrato"
                               title="Data do Contrato"
                               required>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="buttons">

        <button type="submit"
                class="btn-enviar">

            Confirmar Matrícula

        </button>


        <button type="reset"
                class="btn-limpar">

            Limpar Dados

        </button>

    </div>

</form>

<script src="resources/js/script.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const checkbox = document.getElementById("alunoResponsavel");
    const sessaoResponsavel = document.getElementById("sessaoResponsavel");

    checkbox.addEventListener("change", function () {

        if (this.checked) {

            sessaoResponsavel.style.display = "none";

        } else {

            sessaoResponsavel.style.display = "block";

        }

    });

});
</script>

<?php include 'resources/layout/footer.php'; ?>