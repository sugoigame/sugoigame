<?php

class Response {
		public function send($msg) {
				echo $msg;
		}

		public function error($error) {
				echo "#$error";
		}

		public function send_loot($itens, $msg) {
				global $userDetails;
				$imgUrl = "https://sugoigame.com.br/Imagens/Backgrounds/realizacao2.php?m=" . base64_encode($msg) . "&id=" . $userDetails->tripulacao["id"];
				echo $this->share_msg("<img style='width:100%' src='$imgUrl' />", $imgUrl);
		}

		public function send_conquista_pers($pers, $msg) {
				global $userDetails;
				$imgUrl = "https://sugoigame.com.br/Imagens/Backgrounds/realizacao.php?cod=" . $pers["cod"] . "&id=" . $userDetails->tripulacao["id"] . "&m=" . base64_encode($msg);
				echo $this->share_msg("<img style='width:100%' src='$imgUrl' />", $imgUrl);
		}

		public function send_share_msg($msg) {
				echo $this->get_achiev_msg($msg);
		}

		public function get_achiev_msg($msg) {
				global $userDetails;
				$imgUrl = "https://sugoigame.com.br/Imagens/Backgrounds/realizacao2.php?m=" . base64_encode($msg) . "&id=" . $userDetails->tripulacao["id"];
				return $this->share_msg("<img style='width:100%' src='$imgUrl' />", $imgUrl);
		}

		public function share_msg($msg, $urlToShare = "https://sugoigame.com.br") {

				return $msg . "<style type='text/css'>
								ul.share-buttons{
									list-style: none;
									padding: 0;
									margin-top: 2em;
								}
								
								ul.share-buttons li{
									display: inline;
								}
								
								ul.share-buttons .sr-only{
									position: absolute;
									clip: rect(1px 1px 1px 1px);
									clip: rect(1px, 1px, 1px, 1px);
									padding: 0;
									border: 0;
									height: 1px;
									width: 1px;
									overflow: hidden;
								}
								
								ul.share-buttons img{
									width: 32px;
								}
							</style>

							<ul class=\"share-buttons\">
									<li><a href=\"https://www.facebook.com/sharer/sharer.php?u=" . urlencode($urlToShare) . "&t=" . urlencode($msg) . "\" title=\"Compartilhe no Facebook\" target=\"_blank\"><img alt=\"Share on Facebook\" src=\"Imagens/Social/Facebook.svg\" /></a></li>
									<li><a href=\"https://twitter.com/intent/tweet?original_referer=" . urlencode($urlToShare) . "&text=Sugoi Game - " . urlencode($urlToShare) . "\" target=\"_blank\" title=\"Tweet\"><img alt=\"Tweet\" src=\"Imagens/Social/Twitter.svg\" /></a></li>
							</ul>";
		}
}