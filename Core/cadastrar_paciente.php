<?php

if(file_exists("install/index.php")){
  //perform redirect if installer files exist
  //this if{} block may be deleted once installed
  header("Location: install/index.php");
}

require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';
?>


<?php
	require_once 'users/init.php';
	$db = DB::getInstance();
	if (!securePage($_SERVER['PHP_SELF'])){die();} 
?>

<?php
	if(isset($_POST['btn_cadastrar']))
	{
		require_once('php/model/paciente.Class.php');
		$paciente = new Paciente();

		$paciente -> set_cd_cns_paciente(''.$_POST['cd_cns_paciente']);  
		$paciente -> set_nm_justificativa(''.$_POST['nm_justificativa']);      
		$paciente -> set_nm_paciente(''.$_POST['nm_paciente']);     
		$paciente -> set_nm_mae(''.$_POST['nm_mae']);      
		$paciente -> set_ic_sexo(''.$_POST['ic_sexo']);          
		$paciente -> set_ic_raca(''.$_POST['ic_raca']);       
		$paciente -> set_dt_nascimento(''.$_POST['dt_nascimento']);
		$paciente -> set_nm_pais_nascimento(''.$_POST['nm_pais_nascimento']);       
		$paciente -> set_nm_municipio_nascimento(''.$_POST['nm_municipio_nascimento']);          
		$paciente -> set_nm_pais_residencia(''.$_POST['nm_pais_residencia']);       
		$paciente -> set_nm_municipio_residencia(''.$_POST['nm_municipio_residencia']);       
		$paciente -> set_cd_cep(''.$_POST['cd_cep']);         
		$paciente -> set_nm_logradouro(''.$_POST['nm_logradouro']);      
		$paciente -> set_nm_numero_residencia(''.$_POST['nm_numero_residencia']);   
		$paciente -> set_nm_complemento(''.$_POST['nm_complemento']);   
		$paciente -> set_nm_bairro(''.$_POST['nm_bairro']);        
		$paciente -> set_cd_ubs_referencia('123');     
		$paciente -> set_nm_responsavel(''.$_POST['nm_responsavel']);     
		$paciente -> set_cd_documento_responsavel(''.$_POST['cd_documento_responsavel']);
		$paciente -> set_nm_orgao_emissor(''.$_POST['nm_orgao_emissor']);    
		$paciente -> set_cd_cnes('1234567');      
		$paciente -> set_dt_adesao(''.date("Y-m-d"));       
		$paciente -> set_hr_adesao(''.date("H:i:s"));       
		$paciente -> set_cd_profissional_registro('12345');

		$ok = $paciente -> cadastrar_paciente();
		
			//$mensagem = 'Paciente cadastrado com sucesso';
		if($ok == 0)
		{
			$mensagem = 'Erro ao cadastrar paciente';
		}
		else
		{
			$codigo_paciente = $paciente -> get_cd_paciente();
			//$header = 'cadastrar_triagem.php?cd_paciente='.$codigo_paciente;
			unset($paciente);
			//header('location: '.$header);
			//echo $chave_primaria;
		}   

		unset($paciente); 
	}
				
?>

<!DOCTYPE html>
<html>
<head>
  <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
  <title>Cadastramento paciente</title>
  <meta charset="utf-8" />
  <link href="css/formulario.css" rel="stylesheet">
  <script src="users/js/jquery.js"></script>
</head>
<script>
	function imprimir()
	{           
		window.frames["pdf_etiqueta"].focus();
		window.frames["pdf_etiqueta"].print();
		$("#div_etiqueta").hide();

	}
	function avancar(id)
	{
		$(".field_set").hide();
		$("#"+id).show();
		window.scrollTo(0, 0);
	}
	function voltar(id)
	{
		$(".field_set").hide();
		$("#"+id).show();
		window.scrollTo(0, 0);
	}

	function pesquisar_cep()
	{
		//limpa os campos
		$("#nm_municipio_residencia").val("");
		$("#nm_bairro").val("");
		$("#nm_logradouro").val("");

		//Nova variável "cep" somente com numeros.
		var cep = $("#cd_cep").val().replace(/\D/g, '');

		if(cep != "") 
		{
			//validando o cep
			var validacep = /^[0-9]{8}$/;
			if(validacep.test(cep))
			{
				$("#p_carregando").show();

				$.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
					if (!("erro" in dados)) 
					{
						//Atualiza os campos com os valores da consulta.
						$("#nm_municipio_residencia").val(dados.localidade+" - "+dados.uf);
						$("#nm_bairro").val(dados.bairro);
						$("#nm_logradouro").val(dados.logradouro);
						//$("#uf").val(dados.uf);
						//$("#ibge").val(dados.ibge);
						$("#p_carregando").hide();
					}
					else 
					{
						//CEP pesquisado não foi encontrado.
						alert("CEP não encontrado.");
						$("#p_carregando").hide();
					}
				});
			}
		}
	}

</script>
<body>
  <div>
	<form method="post" class="form-style">
		<h1>SISTEMA DE CADASTRAMENTO SUS</h1>
	<fieldset id="fieldset_1" class="field_set">
		<label for="ncns" class="margem">Número CNS</label>
		<input type="number" name="cd_cns_paciente" id="ncns" /><br />
		<label for="justificativa" class="margem">Justificativa da ausência do CNS</label>
		<select name="nm_justificativa" id="justificativa">
			<option value="" ></option>
			<option value="graves">Pacientes acidentados graves</option>
			<option value="encontrados">Pacientes psiquiátricos encontrados em vias públicas</option>
			<option value="problemas">Pacientes com problemas neurológicos graves ou comatosos</option>
			<option value="incapacitados">Pacientes incapacitados por motivos sociais e/ou culturais</option>
			<option value="doador">Doador de Órgãos Falecido</option>
		</select><br />
		<label for="nomep" class="margem">Nome completo</label>
		<input type="text" name="nm_paciente" id="nomep" /><br />
		<label for="nomem"class="margem">Nome completo da mãe</label>
		<input type="text" name="nm_mae" id="nomem"/><br />
		<label for="sexop"class="margem">Sexo</label>
		<select name="ic_sexo" id="sexop">
			<option value="masculino">Masculino</option>
			<option value="feminino">Feminino</option>
			<option value="ninformado">Não informado</option>
		</select> <br />
		<label for="corp" class="margem">Raça/Cor</label>
		<select name="ic_raca" id="corp">
			<option value="branca">Branca</option>
			<option value="preta">Preta</option>
			<option value="parda">Parda</option>
			<option value="amarela">Amarela</option>
			<option value="indigena">Indígena</option>
			<option value="sinformacao">Sem informação</option>
		</select><br />
		<label for="nascp" class="margem">Data de Nascimento</label>
		<input type="date" name="dt_nascimento" id="nascp" /><br />
		<button type="button" onclick="avancar('fieldset_2');">Avançar</button>
	</fieldset><br />

	<fieldset id="fieldset_2" class="field_set" style="display: none;">
		<label for="paisnasc" class="margem1" >País de nascimento</label>
		<input type="text" name="nm_pais_nascimento" id="paisnasc" /><br />
		<label for=munnasc class="margem1">Município de nascimento</label>
		<input type="text" name="nm_municipio_nascimento" id="munnasc" /><br />
		<label for="paisresd" class="margem1">País de residência</label>
		<input type="text" name="nm_pais_residencia" id="paisresd" /><br />
		<label for="cep" class="margem1">CEP</label>
		<input type="text" name="cd_cep" id="cd_cep" size="9" maxlength="9" onblur="pesquisar_cep();" />
		<p id="p_carregando" hidden>Carregando...</p>
		<label for="munresd" class="margem1">Município de residência</label>
		<input type="text" name="nm_municipio_residencia" id="nm_municipio_residencia" /><br />
		<label for="bairro" class="margem1">Bairro de residência</label>
		<input type="text" name="nm_bairro" id="nm_bairro" /><br />
		<label for="endereco" class="margem1">Logradouro de residência</label>
		<input type="text" name="nm_logradouro" id="nm_logradouro" />
		<label for="numresd" class="margemnumero">Número </label>
		<input type="text" name="nm_numero_residencia" id="numresd" size="05" placeholder="ex: 320" /><br />
		<label for="complemresd" class="margem1">Complemento do endereço</label>
		<input type="text" name="nm_complemento" id="complemresd" /><br />
		<button type="button" onclick="voltar('fieldset_1');">Voltar</button>
		<button type="button" onclick="avancar('fieldset_3');">Avançar</button>
	  </fieldset><br />

	<fieldset id="fieldset_3" class="field_set" style="display: none;">
		<!-- <label for="ubsref" class="margem2">UBS de referência</label> --> 
		<!-- <input type="number" name="cd_ubs_referencia" id="ubsref" /><br /> -->
		<label for="nomeresp" class="margem2">Nome completo do responsável</label>
		<input type="text" name="nm_responsavel" id="nomeresp" /><br />
		<label for="docresp" class="margem2">Documento do responsavel</label>
		<input type="text" name="cd_documento_responsavel" id="docresp" /><br />
		<label for="orgaoresp" class="margem2">Órgão emissor</label>
		<input type="text" name="nm_orgao_emissor" id="orgaoresp" /><br />
		<button type="button" onclick="voltar('fieldset_2');">Voltar</button>
		<input  type="submit" name='btn_cadastrar' value="Cadastrar e Imprimir Etiqueta" />
	</fieldset><br />
	
	  <!-- <fieldset>
		  <label for="idestab" class="margem3" >Identificação do SUS</label>
		  <input type="text" name="cd_cnes" id="idstab"  /><br />
		  <label for="profregist" class="margem3">Identificação do cadastrante </label>
		  <input type="number" name="cd_profissional_registro" id="profregist"  /><br />
	  </fieldset><br />  -->      
	</form>
  </div>
<?php
		if(isset($codigo_paciente) && $codigo_paciente > 0)
		{
?>
			<div id="div_etiqueta">
				<iframe id="pdf_etiqueta" name="pdf_etiqueta" src="php/gerar_etiqueta.php?cd_paciente=<?php echo $codigo_paciente; ?>"></iframe>
				<script>
					imprimir();
					window.location = "index.php";
				</script>
			</div>
<?php
		}
?>
</body>
</html>