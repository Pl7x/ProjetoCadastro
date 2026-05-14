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

            // =====================================
            // ALUNO É O PRÓPRIO RESPONSÁVEL
            // =====================================

            if (isset($data['aluno_responsavel'])) {

                $data['nome_responsavel'] =
                    $data['nome_aluno'];

                $data['endereco_responsavel'] =
                    $data['endereco_aluno'];

                $data['complemento_responsavel'] =
                    $data['complemento_aluno'];

                $data['cidade_responsavel'] =
                    $data['cidade_aluno'];

                $data['bairro_responsavel'] =
                    $data['bairro_aluno'];

                $data['cep_responsavel'] =
                    $data['cep_aluno'];

                $data['estado_responsavel'] =
                    $data['estado_aluno'];

                $data['data_nasc_responsavel'] =
                    $data['data_nasc_aluno'];

                $data['profissao_responsavel'] =
                    $data['profissao_aluno'];

                $data['email_responsavel'] =
                    $data['email_aluno'];

                $data['tel_residencial'] =
                    $data['whatsapp'];

                $data['escolaridade_responsavel'] =
                    $data['escolaridade_aluno'];

                $data['sexo_responsavel'] =
                    $data['sexo_aluno'];

                $data['rg_responsavel'] =
                    $data['rg_aluno'];

                $data['cpf_cnpj_responsavel'] =
                    $data['cpf_aluno'];

                $data['estado_civil_responsavel'] =
                    $data['estado_civil_aluno'];

                $data['cargo_responsavel'] =
                    $data['profissao_aluno'];

                $data['Pessoa'] = 'Fisica';
            }

            // =====================================
            // FUNÇÃO MOEDA
            // =====================================

            function moeda($valor)
            {
                if (empty($valor)) {
                    return 0;
                }

                $valor = str_replace('R$', '', $valor);

                $valor = trim($valor);

                $valor = str_replace('.', '', $valor);

                $valor = str_replace(',', '.', $valor);

                return (float)$valor;
            }

            // =====================================
            // CONVERTER DATA
            // =====================================

            function converterData($data)
            {
                if (empty($data)) {
                    return null;
                }

                if (strpos($data, '/') !== false) {

                    $partes = explode('/', $data);

                    if (count($partes) === 3) {

                        return $partes[2] . '-' .
                               $partes[1] . '-' .
                               $partes[0];
                    }
                }

                return $data;
            }

            // =====================================
            // DIAS DA SEMANA
            // =====================================

            $diasSemana = isset($data['dias']) && is_array($data['dias'])
                ? implode(',', $data['dias'])
                : null;

            // =====================================
            // TIPO PESSOA
            // =====================================

            $tipoPessoa = !empty($data['Pessoa'])
                ? $data['Pessoa']
                : 'Fisica';

            // =====================================
            // VERIFICA ALUNO EXISTENTE
            // =====================================

            $stmtBuscaAluno = $db->prepare("
                SELECT id
                FROM aluno
                WHERE cpf = ?
                LIMIT 1
            ");

            $stmtBuscaAluno->bind_param(
                "s",
                $data['cpf_aluno']
            );

            $stmtBuscaAluno->execute();

            $resultAluno = $stmtBuscaAluno->get_result();

            // =====================================
            // ALUNO EXISTENTE
            // =====================================

            if ($resultAluno->num_rows > 0) {

                $alunoExistente = $resultAluno->fetch_assoc();

                $idAluno = $alunoExistente['id'];

            } else {

                // =====================================
                // SALVA ALUNO
                // =====================================

                $stmtAluno = $db->prepare("
                    INSERT INTO aluno (
                        nome,
                        endereco,
                        complemento,
                        cidade,
                        bairro,
                        cep,
                        estado,
                        data_nascimento,
                        profissao,
                        email,
                        whatsapp,
                        escolaridade,
                        sexo,
                        rg,
                        tel_comercial,
                        medicacao,
                        estado_civil,
                        cpf
                    )
                    VALUES (
                        ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
                    )
                ");

                $dataNascAluno =
                    converterData($data['data_nasc_aluno']);

                $stmtAluno->bind_param(
                    "ssssssssssssssssss",
                    $data['nome_aluno'],
                    $data['endereco_aluno'],
                    $data['complemento_aluno'],
                    $data['cidade_aluno'],
                    $data['bairro_aluno'],
                    $data['cep_aluno'],
                    $data['estado_aluno'],
                    $dataNascAluno,
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

                    throw new \Exception(
                        "Erro ao salvar aluno: " .
                        $stmtAluno->error
                    );
                }

                $idAluno = $db->insert_id;
            }

            // =====================================
            // RESPONSÁVEL
            // =====================================

            $idResponsavel = null;

            if (!empty($data['cpf_cnpj_responsavel'])) {

                $stmtBuscaResp = $db->prepare("
                    SELECT id
                    FROM responsavel
                    WHERE cpf_cnpj = ?
                    LIMIT 1
                ");

                $stmtBuscaResp->bind_param(
                    "s",
                    $data['cpf_cnpj_responsavel']
                );

                $stmtBuscaResp->execute();

                $resultResp =
                    $stmtBuscaResp->get_result();

                if ($resultResp->num_rows > 0) {

                    $respExistente =
                        $resultResp->fetch_assoc();

                    $idResponsavel =
                        $respExistente['id'];

                } else {

                    $stmtResp = $db->prepare("
                        INSERT INTO responsavel (
                            nome,
                            endereco,
                            complemento,
                            cidade,
                            bairro,
                            cep,
                            estado,
                            data_nascimento,
                            profissao,
                            email,
                            tel_residencial,
                            escolaridade,
                            sexo,
                            rg,
                            tel_comercial,
                            cpf_cnpj,
                            estado_civil,
                            cargo,
                            tipo_pessoa
                        )
                        VALUES (
                            ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
                        )
                    ");

                    $dataNascResp =
                        converterData(
                            $data['data_nasc_responsavel']
                        );

                    $stmtResp->bind_param(
                        "sssssssssssssssssss",
                        $data['nome_responsavel'],
                        $data['endereco_responsavel'],
                        $data['complemento_responsavel'],
                        $data['cidade_responsavel'],
                        $data['bairro_responsavel'],
                        $data['cep_responsavel'],
                        $data['estado_responsavel'],
                        $dataNascResp,
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

                        throw new \Exception(
                            "Erro ao salvar responsável: " .
                            $stmtResp->error
                        );
                    }

                    $idResponsavel = $db->insert_id;
                }
            }

            // =====================================
            // VALORES
            // =====================================

            $taxaMatricula =
                moeda($data['taxa_matricula'] ?? '');

            $taxaMaterial =
                moeda($data['taxa_material'] ?? '');

            $valorParcela =
                moeda($data['valor_parcela'] ?? '');

            // =====================================
            // DATA CONTRATO
            // =====================================

            $dataContrato =
                converterData($data['data_contrato']);

            // =====================================
            // MATRÍCULA
            // =====================================

            $stmtMatricula = $db->prepare("
                INSERT INTO matricula (
                    id_aluno,
                    id_responsavel,
                    id_curso,
                    id_usuario,
                    taxa_matricula,
                    taxa_material,
                    valor_parcela,
                    num_parcelas,
                    vencimento,
                    turma,
                    turno,
                    sala,
                    duracao_curso,
                    carga_horaria,
                    hora_inicio,
                    hora_termino,
                    inicio_curso,
                    termino_curso,
                    dias_semana,
                    data_contrato
                )
                VALUES (
                    ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
                )
            ");

            $inicioCurso =
                converterData($data['inicio_curso']);

            $terminoCurso =
                converterData($data['termino_curso']);

            $vencimento =
                (int)$data['vencimento'];

            $stmtMatricula->bind_param(
                "iiiidddissssssssssss",
                $idAluno,
                $idResponsavel,
                $data['id_curso'],
                $userId,
                $taxaMatricula,
                $taxaMaterial,
                $valorParcela,
                $data['num_parcelas'],
                $vencimento,
                $data['turma'],
                $data['turno'],
                $data['sala'],
                $data['duracao_curso'],
                $data['carga_horaria'],
                $data['hora_inicio'],
                $data['hora_termino'],
                $inicioCurso,
                $terminoCurso,
                $diasSemana,
                $dataContrato
            );

            if (!$stmtMatricula->execute()) {

                throw new \Exception(
                    "Erro ao salvar matrícula: " .
                    $stmtMatricula->error
                );
            }

            $idMatricula = $db->insert_id;

            // =====================================
            // GERA PARCELAS
            // =====================================

            if (
                !empty($data['num_parcelas']) &&
                $valorParcela > 0
            ) {

                $quantidadeParcelas =
                    (int)$data['num_parcelas'];

                $diaVencimento =
                    (int)$vencimento;

                $dataBase = new \DateTime(
                    $dataContrato
                );

                for (
                    $i = 1;
                    $i <= $quantidadeParcelas;
                    $i++
                ) {

                    $dataParcela = clone $dataBase;

                    $dataParcela->modify(
                        '+' . ($i - 1) . ' month'
                    );

                    $ano =
                        $dataParcela->format('Y');

                    $mes =
                        $dataParcela->format('m');

                    $ultimoDiaMes =
                        cal_days_in_month(
                            CAL_GREGORIAN,
                            $mes,
                            $ano
                        );

                    $diaParcela = $diaVencimento;

                    if (
                        $diaParcela >
                        $ultimoDiaMes
                    ) {

                        $diaParcela =
                            $ultimoDiaMes;
                    }

                    $vencimentoParcela =
                        $ano . '-' .
                        $mes . '-' .
                        str_pad(
                            $diaParcela,
                            2,
                            '0',
                            STR_PAD_LEFT
                        );

                    $stmtParcela = $db->prepare("
                        INSERT INTO parcela (
                            id_matricula,
                            numero_parcela,
                            valor,
                            vencimento,
                            status
                        )
                        VALUES (
                            ?,?,?,?,?
                        )
                    ");

                    $statusParcela = 'Pendente';

                    $stmtParcela->bind_param(
                        "iidss",
                        $idMatricula,
                        $i,
                        $valorParcela,
                        $vencimentoParcela,
                        $statusParcela
                    );

                    if (!$stmtParcela->execute()) {

                        throw new \Exception(
                            "Erro ao gerar parcelas: " .
                            $stmtParcela->error
                        );
                    }
                }
            }

            // =====================================
            // COMMIT
            // =====================================

            $db->commit();

            return true;

        } catch (\Exception $e) {

            $db->rollback();

            $_SESSION['error'] = $e->getMessage();

            return false;
        }
    }
}