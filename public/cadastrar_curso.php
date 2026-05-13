<?php
$titulo_pagina = "Cadastrar Curso - Conect Inove";
$pagina_ativa = "curso";

include 'resources/layout/header.php';

use App\Providers\DatabaseProvider;

$mensagem = "";
$tipoMensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $status = trim($_POST['status'] ?? 'ativo');

    if (!empty($nome)) {

        try {

            $conn = DatabaseProvider::connect();

            $verifica = $conn->prepare("SELECT id FROM curso WHERE nome = ?");
            $verifica->bind_param("s", $nome);
            $verifica->execute();

            $resultado = $verifica->get_result();

            if ($resultado->num_rows > 0) {

                $mensagem = "Já existe um curso com esse nome.";
                $tipoMensagem = "erro";

            } else {

                $sql = $conn->prepare("
                    INSERT INTO curso (nome, status)
                    VALUES (?, ?)
                ");

                $sql->bind_param("ss", $nome, $status);

                if ($sql->execute()) {

                    $mensagem = "Curso cadastrado com sucesso!";
                    $tipoMensagem = "sucesso";

                } else {

                    $mensagem = "Erro ao cadastrar curso.";
                    $tipoMensagem = "erro";
                }
            }

        } catch (Exception $e) {

            $mensagem = "Erro: " . $e->getMessage();
            $tipoMensagem = "erro";
        }

    } else {

        $mensagem = "Preencha o nome do curso.";
        $tipoMensagem = "erro";
    }
}
?>
<link rel="stylesheet" href="resources/css/curso.css?v=<?php echo time(); ?>">

<div class="content-body">


    <?php if (!empty($mensagem)): ?>
        <div class="msg-alert <?php echo $tipoMensagem === 'sucesso' ? 'msg-success' : 'msg-error'; ?>" style="display:block; margin-bottom:20px;">
            <i class="fa-solid <?php echo $tipoMensagem === 'sucesso' ? 'fa-circle-check' : 'fa-triangle-exclamation'; ?>"></i>
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>

    <div class="curso-layout">

        <div class="curso-form-card">

            <div class="curso-card-top">
                <div class="curso-icon">
                    <i class="fa-solid fa-book-open"></i>
                </div>

                <div>
                    <h2>Novo Curso</h2>
                    <p>
                        Preencha as informações abaixo para cadastrar um novo curso.
                    </p>
                </div>
            </div>

            <form method="POST">

                <div class="form-group">
                    <label>NOME DO CURSO</label>

                    <div class="input-with-icon">
                        <i class="fa-solid fa-book"></i>

                        <input
                            type="text"
                            name="nome"
                            placeholder="Digite o nome do curso"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label>STATUS</label>

                    <div class="input-with-icon">
                        <i class="fa-solid fa-power-off"></i>

                        <select name="status" required>
                            <option value="ativo">Ativo</option>
                            <option value="inativo">Inativo</option>
                        </select>
                    </div>
                </div>

                <div class="curso-info-box">

                    <div class="curso-info-item">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>
                            Cursos ativos poderão ser vinculados às matrículas.
                        </span>
                    </div>

                    <div class="curso-info-item">
                        <i class="fa-solid fa-pen"></i>
                        <span>
                            O nome do curso poderá ser alterado futuramente.
                        </span>
                    </div>

                    <div class="curso-info-item">
                        <i class="fa-solid fa-layer-group"></i>
                        <span>
                            Evite criar cursos duplicados no sistema.
                        </span>
                    </div>

                </div>

                <div class="curso-actions">

                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Cadastrar Curso
                    </button>

                    <a href="curso.php" class="btn btn-secondary btn-lg">
                        <i class="fa-solid fa-arrow-left"></i>
                        Voltar
                    </a>

                </div>

            </form>

        </div>

        <div class="curso-side-card">

            <div class="side-icon">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>

            <h3>Organização dos Cursos</h3>

            <p>
                Mantenha os cursos organizados para facilitar o gerenciamento
                das matrículas e melhorar o controle acadêmico do sistema.
            </p>

            <div class="side-divider"></div>

            <div class="side-mini-info">
                <i class="fa-solid fa-book"></i>
                <span>Cadastro rápido e intuitivo</span>
            </div>

            <div class="side-mini-info">
                <i class="fa-solid fa-users"></i>
                <span>Vinculação com alunos</span>
            </div>

            <div class="side-mini-info">
                <i class="fa-solid fa-chart-line"></i>
                <span>Controle completo dos cursos</span>
            </div>

        </div>

    </div>

</div>



<?php include 'resources/layout/footer.php'; ?>