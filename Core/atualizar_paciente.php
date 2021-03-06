<?php
//-------------------CÓDIGO DO USERSPICE
if (file_exists("install/index.php")) {
    //perform redirect if installer files exist
    //thiis if{} block may be deleted once installed
    header("Location: install/index.php");
}
require_once 'users/init.php';
require_once $abs_us_root . $us_url_root . 'users/includes/header.php';
//require_once $abs_us_root . $us_url_root . 'users/includes/navigation.php';
require_once 'users/init.php';
$db = DB::getInstance();
if (!securePage($_SERVER['PHP_SELF'])) {
    die();
}
?>

<?php
//essa pagina precisa do codigo do paciente no metodo GET para fazer o insert na chave estrangeira do banco, aqui esta sendo feita uma verificaçao pra saber se esse get foi setado e se o valor setado realmente existe como um usuario. Caso contrario, o usuario volta pra pagina de pesquisar_paciente.php
if (isset($_GET['cd_paciente']) && $_GET['cd_paciente'] != '') {
    //verificando se o valor existe no banco
    require_once('php/classes/paciente.Class.php');
    $paciente = new Paciente();
    $paciente->selecionar($_GET['cd_paciente']);
    if ($paciente->getCdPaciente() == '' || $paciente->getCdPaciente() == 0) {
        unset($paciente);
        header("location: index.php");
    }
} else {
    unset($paciente);
    header("location: index.php");
}
?>

<html>
    <head>
        
        <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <title>Cadastramento paciente</title>
        <meta charset="utf-8" />
        <link href="css/formulario2.css" rel="stylesheet">
        <script src="users/js/jquery.js"></script>
        <script src="users/js/buscaCEP.js"></script>
        <script src="users/js/ubs_referencia.js"></script>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <script src="users/js/validacao_cadastrar_paciente.js"></script>
    </head>
    <body>
        <?php require_once 'php/div_header.php'; ?>
        <div id="div_corpo">
            <form method="post" action="php/actions/action_atualizar_paciente.php" class="form-style" id="cadastro_paciente">
                <h1>Verificar Cadastro - <?php echo $paciente->getNmPaciente(); ?></h1>
                <label for="ncns" class="margem">Número CNS</label>
                <input value="<?php echo $paciente->getCdCnsPaciente(); ?>" type="number" name="cd_cns_paciente" id="cd_cns_paciente" onblur="validar_cd_cns_paciente()"/>

                <label for="justificativa" class="margem">Justificativa da ausência do CNS</label>
                <input value="<?php echo $paciente->getNmJustificativa(); ?>" type="text" name="nm_justificativa" id="nm_justificativa" onblur="validar_nm_justificativa()" />

                <label for="nomep" class="margem">Nome completo</label>
                <input value="<?php echo $paciente->getNmPaciente(); ?>" type="text" name="nm_paciente" id="nm_paciente" onblur="validar_nm_paciente()" />

                <label for="nomem"class="margem">Nome completo da mãe</label>
                <input value="<?php echo $paciente->getNmMae(); ?>" type="text" name="nm_mae" id="nm_mae" onblur="validar_nm_mae()"/>

                <label for="sexop"class="margem">Sexo</label>
                <input value="<?php echo $paciente->getIcSexo(); ?>" type="text" name="ic_sexo" id="ic_sexo" onblur="validar_ic_sexo()" />

                <label for="corp" class="margem">Raça/Cor</label>
                <input value="<?php echo $paciente->getIcRaca(); ?>" type="text" name="ic_raca" id="ic_raca" onblur="validar_ic_raca()" />

                <label for="nascp" class="margem">Data de Nascimento</label>
                <input value="<?php echo date_format(new DateTime($paciente->getDtNascimento()), 'd/m/Y'); ?>" type="text" maxlength="10" name="dt_nascimento" id="dt_nascimento" onkeypress="mascarar_data()" onblur="validar_dt_nascimento()"/>

                <label for="paisnasc" class="margem1" >País de nascimento</label>
                <input value="<?php echo $paciente->getNmPaisNascimento(); ?>" type="text" name="nm_pais_nascimento" id="nm_pais_nascimento" onblur="validar_nm_pais_nascimento()" />

                <label for=munnasc class="margem1">Município de nascimento</label>
                <input value="<?php echo $paciente->getNmMunicipioNascimento(); ?>" type="text" name="nm_municipio_nascimento" id="nm_municipio_nascimento" onblur="validar_nm_municipio_nascimento();" />

                <label for="paisresd" class="margem1">País de residência</label>
                <input value="<?php echo $paciente->getNmPaisResidencia(); ?>" type="text" name="nm_pais_residencia" id="nm_pais_residencia" onblur="validar_nm_pais_residencia();" />

                <label for="cep" class="margem1">CEP</label>
                <input  value="<?php echo $paciente->getCdCep(); ?>" type="text" name="cd_cep" id="cd_cep" size="9" maxlength="9" onblur="validar_cd_cep();" />
                <div id="div_ubs_referencia"><input type="number" name="cd_ubs_referencia" id="cd_ubs_referencia" value="4" hidden/></div>

                <label for="munresd" class="margem1">Município de residência</label>
                <input value="<?php echo $paciente->getNmMunicipioResidencia(); ?>" type="text" name="nm_municipio_residencia" id="nm_municipio_residencia" onblur="validar_nm_municipio_residencia();" />

                <label for="bairro" class="margem1">Bairro de residência</label>
                <input value="<?php echo $paciente->getNmBairro(); ?>" type="text" name="nm_bairro" id="nm_bairro" onblur="validar_nm_bairro();" />

                <label for="endereco" class="margem1">Logradouro de residência</label>
                <input value="<?php echo $paciente->getNmLogradouro(); ?>" type="text" name="nm_logradouro" id="nm_logradouro" onblur="validar_nm_logradouro();" />

                <label for="numresd" class="margemnumero">Número </label>
                <input value="<?php echo $paciente->getNmNumeroResidencia(); ?>" type="text" name="nm_numero_residencia" id="nm_numero_residencia" size="05" placeholder="ex: 320" onblur="validar_nm_numero_residencia();" />

                <label for="complemresd" class="margem1">Complemento do endereço</label>
                <input value="<?php echo $paciente->getNmComplemento(); ?>" type="text" name="nm_complemento" id="nm_complemento" onblur="validar_nm_complemento();" />

                <label for="nomeresp" class="margem2">Nome completo do responsável</label>
                <input value="<?php echo $paciente->getNmResponsavel(); ?>" type="text" name="nm_responsavel" id="nm_responsavel" onblur="validar_nm_responsavel()" />

                <label for="docresp" class="margem2">Documento do responsavel</label>
                <input value="<?php echo $paciente->getCdDocumentoResponsavel(); ?>" type="text" maxlength="12" name="cd_documento_responsavel" id="cd_documento_responsavel" onkeypress="mascarar_rg()" onblur="validar_cd_documento_responsavel()" />

                <label for="orgaoresp" class="margem2">Órgão emissor</label>
                <input value="<?php echo $paciente->getNmOrgaoEmissor(); ?>" type="text" name="nm_orgao_emissor" id="nm_orgao_emissor"  onblur="validar_nm_orgao_emissor()" />

                <input type="number" id="cd_paciente" name="cd_paciente" value="<?php echo $paciente->getCdPaciente(); ?>" hidden/>
                

                <input value="Atualizar Cadastro" type="submit" name='btn_atualizar' id="btn_cadastrar" />

                <button type="button" onclick="javascript:history.back()">Voltar</button> 

            </form>
        </div>        
    </body>
</html>
</html>
