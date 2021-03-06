<?php
function get_adversario_incursao($alvo_id, $incursao) {
    foreach ($incursao["niveis"] as $nivel) {
        foreach ($nivel as $adversario_id => $adversario) {
            if ($alvo_id == $adversario_id) {
                return $adversario;
            }
        }
    }
    return null;
}