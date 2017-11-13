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
    //essa pagina precisa do codigo da triagem no metodo GET para conseguir os dados dessa triagem no banco. Aqui esta sendo feita uma verificaçao pra saber se esse get foi setado e se o valor setado realmente ´e uma triagem existente no banco. Caso contrario, o usuario volta para o index

    if(isset($_GET['cd_triagem']) && $_GET['cd_triagem'] != '')
    {
        //verificando se o valor existe no banco
        require_once('php/model/triagem.Class.php');
        $triagem = new Triagem();

        $triagem -> selecionar_triagem($_GET['cd_triagem']);

        if($triagem -> get_cd_triagem() == '' || $triagem -> get_cd_triagem() == 0)
        {
            unset($triagem);
            header("location: index.php");
        }
    }
    else
    {
        unset($triagem);
        header("location: index.php");
    }
?>

<!DOCTYPE html>
<html>
<head>
  <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
  <title>Dados da Triagem</title>
  <meta charset="utf-8" />
  <link href="css/formulario.css" rel="stylesheet">
  <script src="users/js/jquery.js"></script>
</head>
<body>

  <div>

    <form method="post" class="form-style">
        <h1>Dados da Triagem</h1>
        <fieldset style="border: solid 1px; padding: 15px;">
          <p>Queixa: <?php echo $triagem -> get_ds_queixa(); ?> </p>
          <p>Data: <?php echo $triagem -> get_dt_triagem(); ?> </p>
          <p>Hora: <?php echo $triagem -> get_hr_triagem(); ?> </p>
          <p>Pressão Mínima: <?php echo $triagem -> get_vl_pressao_min(); ?> </p>
          <p>Pressão Máxima: <?php echo $triagem -> get_vl_pressao_max(); ?> </p>
          <p>Pulso: <?php echo $triagem -> get_vl_pulso(); ?> </p>
          <p>Temperatura: <?php echo $triagem -> get_vl_temperatura(); ?> </p>
          <p>Respiração: <?php echo $triagem -> get_vl_respiracao(); ?> </p>
          <p>Saturação: <?php echo $triagem -> get_vl_saturacao(); ?> </p>
          <p>Glicemia: <?php echo $triagem -> get_vl_glicemia(); ?> </p>
          <p>Nível de Consciência: <?php echo $triagem -> get_vl_nivel_consciencia(); ?> </p>
          <p>Escala de Dor: <?php echo $triagem -> get_vl_escala_dor(); ?> </p>
          <p>Alergia a Medicamentos: <?php echo $triagem -> get_ic_alergia(); ?> </p>
          <p>Descrissão das Alergias: <?php echo $triagem -> get_ds_alergia(); ?> </p>
          <p>Observações: <?php echo $triagem -> get_ds_observacao(); ?> </p>
          <p>Classificação de Risco: <?php echo $triagem -> get_vl_classificacao_risco(); ?> </p>
          <p>Linha de Cuidado: <?php echo $triagem -> get_ds_linha_cuidado(); ?> </p>
          <p>Outras condições: <?php echo $triagem -> get_ds_outras_condicoes(); ?> </p>
          <p>CNS do Profissional que Realizou a Triagem: <?php echo $triagem -> get_cd_cns_profissional_triagem(); ?> </p>
        </fieldset>
    </form>
  </div>
</body>
</html>