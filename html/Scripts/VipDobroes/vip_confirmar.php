<?php
	$destinatarios = "vip_sugoigame@yahoo.com.br";
	
	/*abaixo as veriaveis principais, que devem conter em seu formulario*/
	$nomeRemetente = $_POST["nome"];
	$resposta = $_POST["email"];
	$assunto = "VIP - ".$_POST["nome"]." - ".$_POST["email"];
	$headers = "From: $nomeRemetente <$resposta>\r\n";
	$msg="";
	
	foreach ($_POST as $dados['me1'] => $dados['me2']){
		$msg .= $dados['me1'].': '.$dados['me2']."\n";
	}
	
	mail($destinatarios,$assunto,$msg,$headers) or die(header("location:../../?sessao=vipComprar&msg2=Não foi possível confirmar compra.<br>Aguarde alguns instantes e tente novamente, caso o erro persista entre em contato com o suporte"));
	
	header("location:../../?sessao=vipComprar&msg2=Obrigado por colaborar com Sugoi Game!<br>Dentro de no máximo 3 dias as moedas serão liberadas na sua conta!");
?>