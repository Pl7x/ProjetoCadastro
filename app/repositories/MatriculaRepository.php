<?php

namespace App\Repositories;

use App\Providers\DatabaseProvider;

class MatriculaRepository
{
    public static function save(array $data, int $userId): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $db = DatabaseProvider::connect();
        $db->begin_transaction();

        try {

            function moeda($valor)
            {
                if (empty($valor)) return 0;

                $valor = str_replace('.', '', $valor);
                $valor = str_replace(',', '.', $valor);

                return (float)$valor;
            }

            // =========================
            // DIAS DA SEMANA
            // =========================
            $diasSemana = isset($data['dias']) && is_array($data['dias'])
                ? implode(',', $data['dias'])
                : '';

            // =========================
            // TIPO PESSOA
            // =========================
            $tipoPessoa = $data['Pessoa'] ?? '';

            // =========================
            // VERIFICA CPF DUPLICADO
            // =========================
            $checkCpf = $db->prepare("SELECT id FROM aluno WHERE cpf = ?");
            $checkCpf->bind_param("s", $data['cpf_aluno']);
            $checkCpf->execute();
            $checkCpf->store_result();

            if ($checkCpf->num_rows > 0) {
                throw new \Exception("Já existe um aluno cadastrado com este CPF.");
            }

            // =========================
            // ALUNO
            // =========================
            $stmtAluno = $db->prepare("
                INSERT INTO aluno (
                    nome, endereco, complemento, cidade, bairro, cep, estado,
                    data_nascimento, profissao, email, whatsapp,
                    escolaridade, sexo, rg, tel_comercial,
                    medicacao, estado_civil, cpf
                )
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
            ");

            $stmtAluno->bind_param(
                "ssssssssssssssssss",
                $data['nome_aluno'],
                $data['endereco_aluno'],
                $data['complemento_aluno'],
                $data['cidade_aluno'],
                $data['bairro_aluno'],
                $data['cep_aluno'],
                $data['estado_aluno'],
                $data['data_nasc_aluno'],
                $data['profissao_aluno'],
                $data['email_aluno'],
                $data['whatsapp'],
                $data['escolaridade_aluno'],
                $data['sexo_aluno'],
                $data['rg_aluno'],
                $data['tel_comercial_aluno'],
                $data['medicacao_aluno'],
                $data['estado_civil_aluno'],
                $data['cpf_aluno']
            );

            if (!$stmtAluno->execute()) {
                throw new \Exception("Erro ao salvar aluno.");
            }

            $idAluno = $db->insert_id;

            // =========================
            // RESPONSÁVEL
            // =========================
            $stmtResp = $db->prepare("
                INSERT INTO responsavel (
                    nome, endereco, complemento, cidade, bairro, cep, estado,
                    data_nascimento, profissao, email, tel_residencial,
                    escolaridade, sexo, rg, tel_comercial,
                    cpf_cnpj, estado_civil, cargo, tipo_pessoa
                )
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
            ");

            $stmtResp->bind_param(
                "sssssssssssssssssss",
                $data['nome_responsavel'],
                $data['endereco_responsavel'],
                $data['complemento_responsavel'],
                $data['cidade_responsavel'],
                $data['bairro_responsavel'],
                $data['cep_responsavel'],
                $data['estado_responsavel'],
                $data['data_nasc_responsavel'],
                $data['profissao_responsavel'],
                $data['email_responsavel'],
                $data['tel_residencial'],
                $data['escolaridade_responsavel'],
                $data['sexo_responsavel'],
                $data['rg_responsavel'],
                $data['tel_comercial_responsavel'],
                $data['cpf_cnpj_responsavel'],
                $data['estado_civil_responsavel'],
                $data['cargo_responsavel'],
                $tipoPessoa
            );

            if (!$stmtResp->execute()) {
                throw new \Exception("Erro ao salvar responsável.");
            }

            $idResponsavel = $db->insert_id;

            // =========================
            // MATRÍCULA
            // =========================
            $taxaMatricula = moeda($data['taxa_matricula']);
            $taxaMaterial = moeda($data['taxa_material'] ?? '');
            $valorParcela = moeda($data['valor_parcela']);

            $stmtMatricula = $db->prepare("
                INSERT INTO matricula (
                    id_aluno, id_responsavel, id_curso, id_usuario,
                    taxa_matricula, taxa_material, valor_parcela,
                    num_parcelas, vencimento, turma, turno, sala,
                    duracao_curso, carga_horaria, hora_inicio, hora_termino,
                    inicio_curso, termino_curso, dias_semana,
                    assinatura_primeira_aula, data_contrato
                )
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
            ");

            $stmtMatricula->bind_param(
                "iiiidddiissssssssssss",
                $idAluno,
                $idResponsavel,
                $data['id_curso'],
                $userId,
                $taxaMatricula,
                $taxaMaterial,
                $valorParcela,
                $data['num_parcelas'],
                $data['vencimento'],
                $data['turma'],
                $data['turno'],
                $data['sala'],
                $data['duracao_curso'],
                $data['carga_horaria'],
                $data['hora_inicio'],
                $data['hora_termino'],
                $data['inicio_curso'],
                $data['termino_curso'],
                $diasSemana,
                $data['assinatura_primeira_aula'],
                $data['data_contrato']
            );

            if (!$stmtMatricula->execute()) {
                throw new \Exception("Erro ao salvar matrícula: " . $stmtMatricula->error);
            }

            $db->commit();

            return true;

        } catch (\Exception $e) {

            $db->rollback();
            $_SESSION['error'] = $e->getMessage();

            return false;
        }
    }
}