<?php

namespace App\Repositories;

use App\Providers\DatabaseProvider;

class MatriculaRepository
{
    public static function save(array $data, int $userId): bool
    {
        $db = DatabaseProvider::connect();

        // ======================
        // ALUNO
        // ======================
        $alunoNome = $db->real_escape_string($data['nome_aluno'] ?? '');
        $alunoEndereco = $db->real_escape_string($data['endereco_aluno'] ?? '');
        $alunoCidade = $db->real_escape_string($data['cidade_aluno'] ?? '');
        $alunoBairro = $db->real_escape_string($data['bairro_aluno'] ?? '');
        $alunoCep = $db->real_escape_string($data['cep_aluno'] ?? '');
        $alunoEstado = $db->real_escape_string($data['estado_aluno'] ?? '');
        $alunoCpf = $db->real_escape_string($data['cpf_aluno'] ?? '');
        $alunoWhatsapp = $db->real_escape_string($data['whatsapp_aluno'] ?? '');
        $alunoRg = $db->real_escape_string($data['rg_aluno'] ?? '');
        $alunoNascimento = $db->real_escape_string($data['data_nascimento_aluno'] ?? '2000-01-01');
        $alunoEscolaridade = $db->real_escape_string($data['escolaridade_aluno'] ?? '');
        $alunoSexo = $db->real_escape_string($data['sexo_aluno'] ?? 'M');
        $alunoEstadoCivil = $db->real_escape_string($data['estado_civil_aluno'] ?? '');
        $alunoProfissao = $db->real_escape_string($data['profissao_aluno'] ?? '');
        $alunoEmail = $db->real_escape_string($data['email_aluno'] ?? '');

        $checkCpf = "SELECT id FROM aluno WHERE cpf = '{$alunoCpf}'";
        $result = $db->query($checkCpf);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $idAluno = $row['id'];
        } else {
            $sqlAluno = "
                INSERT INTO aluno (
                    nome, endereco, cidade, bairro, cep, estado,
                    cpf, whatsapp, rg, data_nascimento,
                    escolaridade, sexo, estado_civil,
                    profissao, email
                ) VALUES (
                    '{$alunoNome}',
                    '{$alunoEndereco}',
                    '{$alunoCidade}',
                    '{$alunoBairro}',
                    '{$alunoCep}',
                    '{$alunoEstado}',
                    '{$alunoCpf}',
                    '{$alunoWhatsapp}',
                    '{$alunoRg}',
                    '{$alunoNascimento}',
                    '{$alunoEscolaridade}',
                    '{$alunoSexo}',
                    '{$alunoEstadoCivil}',
                    '{$alunoProfissao}',
                    '{$alunoEmail}'
                )
            ";

            if (!$db->query($sqlAluno)) {
                die("Erro aluno: " . $db->error);
            }

            $idAluno = $db->insert_id;
        }

        // ======================
        // RESPONSÁVEL
        // ======================
        $respNome = $db->real_escape_string($data['nome_responsavel'] ?? '');
        $respEndereco = $db->real_escape_string($data['endereco_responsavel'] ?? '');
        $respCidade = $db->real_escape_string($data['cidade_responsavel'] ?? '');
        $respBairro = $db->real_escape_string($data['bairro_responsavel'] ?? '');
        $respCep = $db->real_escape_string($data['cep_responsavel'] ?? '');
        $respEstado = $db->real_escape_string($data['estado_responsavel'] ?? '');
        $respCpf = $db->real_escape_string($data['cpf_cnpj_responsavel'] ?? '');
        $respTelefone = $db->real_escape_string($data['tel_residencial'] ?? '');
        $respRg = $db->real_escape_string($data['rg_responsavel'] ?? '');
        $respNascimento = $db->real_escape_string($data['data_nascimento_responsavel'] ?? '2000-01-01');
        $respEscolaridade = $db->real_escape_string($data['escolaridade_responsavel'] ?? '');
        $respSexo = $db->real_escape_string($data['sexo_responsavel'] ?? 'M');
        $respEstadoCivil = $db->real_escape_string($data['estado_civil_responsavel'] ?? '');
        $respProfissao = $db->real_escape_string($data['profissao_responsavel'] ?? '');
        $respEmail = $db->real_escape_string($data['email_responsavel'] ?? '');
        $respCargo = $db->real_escape_string($data['cargo_responsavel'] ?? '');
        $respTipoPessoa = $db->real_escape_string($data['tipo_pessoa'] ?? '');

        $sqlResp = "
            INSERT INTO responsavel (
                nome, endereco, cidade, bairro, cep, estado,
                cpf_cnpj, tel_residencial, rg, data_nascimento,
                escolaridade, sexo, estado_civil,
                profissao, email, cargo, tipo_pessoa
            ) VALUES (
                '{$respNome}',
                '{$respEndereco}',
                '{$respCidade}',
                '{$respBairro}',
                '{$respCep}',
                '{$respEstado}',
                '{$respCpf}',
                '{$respTelefone}',
                '{$respRg}',
                '{$respNascimento}',
                '{$respEscolaridade}',
                '{$respSexo}',
                '{$respEstadoCivil}',
                '{$respProfissao}',
                '{$respEmail}',
                '{$respCargo}',
                '{$respTipoPessoa}'
            )
        ";

        if (!$db->query($sqlResp)) {
            die("Erro responsável: " . $db->error);
        }

        $idResponsavel = $db->insert_id;

        // ======================
        // CURSO EXISTENTE
        // ======================
        $idCurso = (int) ($data['id_curso'] ?? 0);

        if ($idCurso <= 0) {
            die("Selecione um curso.");
        }

        // ======================
        // MATRÍCULA
        // ======================
        $taxa = $db->real_escape_string($data['taxa_matricula'] ?? 0);
        $parcela = $db->real_escape_string($data['valor_parcela'] ?? 0);
        $numParcelas = (int) ($data['num_parcelas'] ?? 1);

        $sqlMatricula = "
            INSERT INTO matricula (
                id_aluno,
                id_responsavel,
                id_curso,
                id_usuario,
                taxa_matricula,
                valor_parcela,
                num_parcelas
            ) VALUES (
                {$idAluno},
                {$idResponsavel},
                {$idCurso},
                {$userId},
                '{$taxa}',
                '{$parcela}',
                {$numParcelas}
            )
        ";

        return $db->query($sqlMatricula);
    }
}