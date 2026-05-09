<?php
// Define as variáveis de navegação
$titulo_pagina = "Funcionários - Conect Inove";
$pagina_ativa = "funcionarios"; 

include 'resources/layout/header.php';

use App\Providers\DatabaseProvider;
use App\Providers\AuthProvider;

// Garante que a sessão está ativa para verificar quem é o utilizador logado
AuthProvider::startSession();
$id_logado = $_SESSION['id_usuario'] ?? 0;

try {
    $conn = DatabaseProvider::connect();
    
    // Consulta para listar todos os funcionários
    $sql = "SELECT id, nome, email, tipo AS cargo, status, ultimo_login FROM usuario ORDER BY nome ASC";
    $resultado = $conn->query($sql); 
    
    if (!$resultado) {
        throw new Exception("Erro na consulta: " . $conn->error);
    }
    
    $lista_funcionarios = $resultado->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    $erro_db = "Erro ao buscar dados: " . $e->getMessage();
    $lista_funcionarios = [];
}
?>

<div class="content-body">
    <section class="welcome-section" style="display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h1>Funcionários</h1>
            <p class="subtitle">Gerencie os acessos e visualize o status da equipe em tempo real.</p>
        </div>
        
        <a href="cadastrar_funcionario.php" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Novo Funcionário
        </a>
    </section>

    <?php if (isset($erro_db)): ?>
        <div class="msg-alert msg-error" style="display: block; margin-bottom: 20px;">
            <i class="fa-solid fa-triangle-exclamation"></i> <?php echo $erro_db; ?>
        </div>
    <?php endif; ?>

    <section class="table-container">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Nome / E-mail</th>
                    <th>Cargo</th>
                    <th>Status</th> 
                    <th>Último Acesso</th>
                    <th style="text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($lista_funcionarios) && !isset($erro_db)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-secondary);">
                            <i class="fa-solid fa-users-slash" style="display: block; font-size: 30px; margin-bottom: 10px; opacity: 0.3;"></i>
                            Nenhum funcionário encontrado.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($lista_funcionarios as $func): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 35px; height: 35px; background: var(--hover-color); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fa-solid fa-user" style="color: var(--primary-accent); font-size: 16px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: var(--text-primary);">
                                            <?php echo htmlspecialchars($func['nome'], ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                        <div style="font-size: 12px; color: var(--text-secondary);">
                                            <?php echo htmlspecialchars($func['email'], ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($func['cargo'] === 'admin'): ?>
                                    <span style="color: var(--primary-accent); font-weight: 600; font-size: 14px;">
                                        <i class="fa-solid fa-shield-halved"></i> Administrador
                                    </span>
                                <?php else: ?>
                                    <span style="color: var(--text-secondary); font-size: 14px;">
                                        <i class="fa-solid fa-briefcase"></i> Funcionário
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($func['status'] === 'ativo'): ?>
                                    <span style="color: var(--success-color); font-weight: 600; font-size: 13px; background: rgba(46, 204, 113, 0.1); padding: 4px 10px; border-radius: 20px; border: 1px solid var(--success-color);">
                                        <i class="fa-solid fa-circle" style="font-size: 8px; vertical-align: middle; margin-right: 5px;"></i> Ativo
                                    </span>
                                <?php else: ?>
                                    <span style="color: #EF4444; font-weight: 600; font-size: 13px; background: rgba(239, 68, 68, 0.1); padding: 4px 10px; border-radius: 20px; border: 1px solid #EF4444;">
                                        <i class="fa-solid fa-circle" style="font-size: 8px; vertical-align: middle; margin-right: 5px;"></i> Inativo
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($func['ultimo_login'])): ?>
                                    <span style="font-size: 13px; color: var(--text-secondary);">
                                        <i class="fa-regular fa-clock"></i> <?php echo date('d/m/Y H:i', strtotime($func['ultimo_login'])); ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: var(--text-secondary); font-size: 12px; font-style: italic; opacity: 0.6;">Nunca acessou</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($func['id'] == $id_logado): ?>
                                    <button type="button" class="btn btn-secondary" style="opacity: 0.5; cursor: not-allowed; border-style: dashed;" title="Você não pode alterar o seu próprio status" disabled>
                                        <i class="fa-solid fa-user-lock"></i> Seu Status
                                    </button>
                                <?php else: ?>
                                    <button type="button" onclick="abrirModalStatus(<?php echo $func['id']; ?>, '<?php echo $func['status']; ?>')" class="btn btn-secondary" style="padding: 6px 15px; font-size: 13px;">
                                        <i class="fa-solid fa-pen-to-square"></i> Status
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>

<div id="modalStatus" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Alterar Status</h2>
            <button type="button" class="close-modal" onclick="fecharModalStatus()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        
        <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 20px;">
            Selecione a permissão de acesso para este funcionário.
        </p>

        <form id="formAtualizarStatus">
            <input type="hidden" id="edit_id_funcionario" name="id_funcionario">
            
            <div class="form-group">
                <label for="edit_status">Estado da Conta</label>
                <div class="input-with-icon">
                    <i class="fa-solid fa-power-off"></i>
                    <select id="edit_status" name="status" required>
                        <option value="ativo">Ativo (Permite Login)</option>
                        <option value="inativo">Inativo (Bloqueia Login)</option>
                    </select>
                </div>
            </div>

            <div id="msgModal" class="msg-alert" style="margin-top: -10px; margin-bottom: 20px;"></div>

            <button type="submit" class="btn btn-primary btn-save" style="margin-top: 0; width: 100%;">
                <i class="fa-solid fa-floppy-disk"></i> Confirmar Alteração
            </button>
            
            <button type="button" class="btn-cancel" onclick="fecharModalStatus()">
                Cancelar
            </button>
        </form>
    </div>
</div>

<?php include 'resources/layout/footer.php'; ?>