<?php
// =========================================
// CONFIGURAÇÕES
// =========================================

$titulo_pagina = "Cursos - Conect Inove";
$pagina_ativa = "curso";

include 'resources/layout/header.php';

use App\Providers\DatabaseProvider;

// =========================================
// BUSCAR CURSOS
// =========================================

try {

    $conn = DatabaseProvider::connect();

    $sql = "
        SELECT 
            id,
            nome,
            status
        FROM curso
        ORDER BY nome ASC
    ";

    $resultado = $conn->query($sql);

    if (!$resultado) {
        throw new Exception($conn->error);
    }

    $lista_cursos = $resultado->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {

    $erro_db = "Erro ao buscar cursos: " . $e->getMessage();

    $lista_cursos = [];
}
?>

<link rel="stylesheet" href="resources/css/curso.css?v=<?php echo time(); ?>">

<div class="content-body">

    <!-- =========================================
         TOPO
    ========================================= -->
    <section class="welcome-section cursos-topo">

        <div>

            <h1>Cursos</h1>

            <p class="subtitle">
                Gerencie os cursos cadastrados no sistema.
            </p>

        </div>

        <a href="cadastrar_curso.php"
           class="btn btn-primary btn-novo-curso">

            <i class="fa-solid fa-plus"></i>

            Novo Curso

        </a>

    </section>

    <!-- =========================================
         ALERTA GLOBAL
    ========================================= -->
    <div id="alertaCurso" class="curso-alerta"></div>

    <!-- =========================================
         ERRO BANCO
    ========================================= -->
    <?php if (isset($erro_db)): ?>

        <div class="msg-alert msg-error">

            <i class="fa-solid fa-triangle-exclamation"></i>

            <?php echo $erro_db; ?>

        </div>

    <?php endif; ?>

    <!-- =========================================
         TABELA
    ========================================= -->
    <section class="table-container">

        <table class="custom-table">

            <thead>

                <tr>

                    <th>CURSO</th>

                    <th>STATUS</th>

                    <th>AÇÕES</th>

                </tr>

            </thead>

            <tbody>

                <?php if (empty($lista_cursos) && !isset($erro_db)): ?>

                    <tr>

                        <td colspan="3" class="empty-table">

                            <div class="empty-state">

                                <div class="empty-icon">

                                    <i class="fa-solid fa-book-open"></i>

                                </div>

                                <h3>Nenhum curso encontrado</h3>

                                <p>
                                    Cadastre um novo curso para começar.
                                </p>

                            </div>

                        </td>

                    </tr>

                <?php else: ?>

                    <?php foreach ($lista_cursos as $curso): ?>

                        <tr id="curso-<?php echo $curso['id']; ?>">

                            <!-- CURSO -->
                            <td>

                                <div class="curso-table-info">

                                    <div class="curso-table-icon">

                                        <i class="fa-solid fa-book"></i>

                                    </div>

                                    <div class="curso-info-text">

                                        <div class="curso-nome">

                                            <?php echo htmlspecialchars($curso['nome']); ?>

                                        </div>

                                        <div class="curso-desc">

                                            (Curso disponível)

                                        </div>

                                    </div>

                                </div>

                            </td>

                            <!-- STATUS -->
                            <td>

                                <span class="status-badge <?php echo $curso['status']; ?>"
                                      id="status-badge-<?php echo $curso['id']; ?>">

                                    <i class="fa-solid fa-circle"></i>

                                    <span id="status-texto-<?php echo $curso['id']; ?>">

                                        <?php echo ucfirst($curso['status']); ?>

                                    </span>

                                </span>

                            </td>

                            <!-- AÇÕES -->
                            <td>

                                <div class="curso-table-actions">



                                    <button type="button"
                                            class="btn btn-secondary btn-table"
                                            onclick="abrirModalStatusCurso(<?php echo $curso['id']; ?>)"
                                            >

                                        <i class="fa-solid fa-power-off"></i>

                                        Status

                                    </button>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>

        </table>

    </section>

</div>

<!-- =========================================
     MODAL STATUS
========================================= -->
<div id="modalStatusCurso" class="modal-overlay">

    <div class="modal-content">

        <!-- TOPO -->
        <div class="modal-header">

            <h2>Alterar Status</h2>

            <button type="button"
                    class="close-modal"
                    onclick="fecharModalStatusCurso()">

                <i class="fa-solid fa-xmark"></i>

            </button>

        </div>

        <!-- SUBTITULO -->
        <p class="modal-subtitle">

            Escolha o novo status do curso.

        </p>

        <!-- ALERTA -->
        <div id="modalMensagemCurso"
             class="modal-alert">

        </div>

        <!-- FORM -->
        <form id="formStatusCurso">

            <!-- ID -->
            <input type="hidden"
                   id="edit_id_curso"
                   name="id_curso">

            <!-- STATUS ATUAL -->
            <input type="hidden"
                   id="status_atual_curso">

            <!-- SELECT -->
            <div class="form-group">

                <label for="edit_status">

                    Estado do Curso

                </label>

                <div class="input-with-icon">

                    <i class="fa-solid fa-power-off"></i>

                    <select id="edit_status"
                            name="status"
                            required>

                        <option value="ativo">

                            Ativo

                        </option>

                        <option value="inativo">

                            Inativo

                        </option>

                    </select>

                </div>

            </div>

            <!-- BOTÃO -->
            <button type="submit"
                    class="btn btn-primary btn-save">

                <i class="fa-solid fa-floppy-disk"></i>

                Confirmar Alteração

            </button>

            <!-- CANCELAR -->
            <button type="button"
                    class="btn-cancel"
                    onclick="fecharModalStatusCurso()">

                Cancelar

            </button>

        </form>

    </div>

</div>

<!-- =========================================
     SCRIPT
========================================= -->
<script src="resources/js/curso.js?v=<?php echo time(); ?>"></script>

<?php include 'resources/layout/footer.php'; ?>