<?php

function get_skill_table($tipo)
{
    switch ($tipo) {
        case TIPO_SKILL_ATAQUE_CLASSE:
        case TIPO_SKILL_ATAQUE_PROFISSAO:
            return "skil_atk";
        case TIPO_SKILL_BUFF_CLASSE:
        case TIPO_SKILL_BUFF_PROFISSAO:
            return "skil_buff";
        case TIPO_SKILL_ATAQUE_AKUMA:
        case TIPO_SKILL_BUFF_AKUMA:
            return "skil_akuma";
        default:
            return null;
    }
}
