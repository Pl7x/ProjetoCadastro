<?php

namespace App\Repositories;

use App\Providers\DatabaseProvider;

class MatriculaRepository
{
    public static function save(array $data, int $userId): bool
    {
        $db = DatabaseProvider::connect();

        $alunoNome = $db->real_escape_string($data['nome_aluno'] ?? '');
        $alunoEndereco = $db->real_escape_string($data['endereco_aluno'] ?? '');
        $alunoCidade = $db->real_escape_string($data['cidade_aluno'] ?? '');
        $alunoCpf = $db->real_escape_string($data['cpf_aluno'] ?? '');

        $sqlAluno = "INSERT INTO aluno (nome, endereco, cidade, cpf) VALUES ('{$alunoNome}', '{$alunoEndereco}', '{$alunoCidade}', '{$alunoCpf}')";
        if (! $db->query($sqlAluno)) {
            return false;
        }
        $idAluno = $db->insert_id;

        $respNome = $db->real_escape_string($data['nome_responsavel'] ?? '');
        $respCpf = $db->real_escape_string($data['cpf_cnpj_responsavel'] ?? '');

        $sqlResp = "INSERT INTO responsavel (nome, cpf_cnpj) VALUES ('{$respNome}', '{$respCpf}')";
        if (! $db->query($sqlResp)) {
            return false;
        }
        $idResponsavel = $db->insert_id;

        $cursoNome = $db->real_escape_string($data['nome_curso'] ?? '');
        $cursoTurma = $db->real_escape_string($data['turma'] ?? '');

        $sqlCurso = "INSERT INTO curso (nome, turma) VALUES ('{$cursoNome}', '{$cursoTurma}')";
        if (! $db->query($sqlCurso)) {
            return false;
        }
        $idCurso = $db->insert_id;

        $taxa = $db->real_escape_string($data['taxa_matricula'] ?? '');
        $parcela = $db->real_escape_string($data['valor_parcela'] ?? '');
        $numParcelas = (int) ($data['num_parcelas'] ?? 0);

        $sqlMatricula = "INSERT INTO matricula (id_aluno, id_responsavel, id_curso, id_usuario, taxa_matricula, valor_parcela, num_parcelas) VALUES ({$idAluno}, {$idResponsavel}, {$idCurso}, {$userId}, '{$taxa}', '{$parcela}', {$numParcelas})";
        return $db->query($sqlMatricula);
    }
}
