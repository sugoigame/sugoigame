<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>One Piece Sugoi Game</title>
	<link rel="shortcut icon" href="Imagens/favicon.ico" type="image/x-icon" />
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<link rel="stylesheet" href="../CSS/pirata.css" type="text/css" />
</head>

<style type="text/css">
	.mini_selected{
		border-radius: 5px;
		padding:10px;
		top: 0px;
		text-align: left;
		font-size: 14px;
	}
	body{
		background: url('../Imagens/1/Cabecalho/rodape.jpg');
	}
</style>
<body>
	<div class="mini_selected" style="background: url('../Imagens/1/Conteudo/back.jpg'); border: 1px solid #9d6f31">
		<? 
		include "../Funcoes/habilidade_random.php"; 
		$habilidade=habilidade_random();
		?>
		Nome: <input style="border: none;" type="text" value="<? echo $habilidade["nome"] ?>"><br>
		Descrição: <textarea style="width:450px; height: 50px; border: none;"><? echo $habilidade["descricao"] ?></textarea><br><br>
		<button onclick="window.location.reload()">Nova</button>
	</div>
</body>