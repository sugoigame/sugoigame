<?php

class Response
{
    public function send($msg)
    {
        echo $msg;
    }

    public function error($error)
    {
        echo "#$error";
    }

    public function send_loot($itens, $msg)
    {
        global $userDetails;
        $imgUrl = "./Imagens/Backgrounds/realizacao2.php?m=" . base64_encode($msg) . "&id=" . $userDetails->tripulacao["id"];
        echo $this->share_msg("<img style='width:100%' src='$imgUrl' />", $imgUrl);
    }

    public function send_conquista_pers($pers, $msg)
    {
        global $userDetails;
        $imgUrl = "./Imagens/Backgrounds/realizacao.php?cod=" . $pers["cod"] . "&id=" . $userDetails->tripulacao["id"] . "&m=" . base64_encode($msg);
        echo $this->share_msg("<img style='width:100%' src='$imgUrl' />", $imgUrl);
    }

    public function send_share_msg($msg)
    {
        echo $this->get_achiev_msg($msg);
    }

    public function get_achiev_msg($msg)
    {
        global $userDetails;
        $imgUrl = "./Imagens/Backgrounds/realizacao2.php?m=" . base64_encode($msg) . "&id=" . $userDetails->tripulacao["id"];
        return $this->share_msg("<img style='width:100%' src='$imgUrl' />", $imgUrl);
    }

    public function share_msg($msg, $urlToShare = "https://sugoigame.com.br")
    {
        return $msg;
    }
}
