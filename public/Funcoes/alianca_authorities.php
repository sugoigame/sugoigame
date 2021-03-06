<?php
function can_convidar($ally, $authority = NULL) {
    return validate_permission_string($ally, 0, $authority);
}

function can_expulsar($ally, $authority = NULL) {
    return validate_permission_string($ally, 1, $authority);
}

function can_alt_cargo($ally, $authority = NULL) {
    return validate_permission_string($ally, 2, $authority);
}

function can_edit_mural($ally, $authority = NULL) {
    return validate_permission_string($ally, 3, $authority);
}

function can_ini_guerra($ally, $authority = NULL) {
    return validate_permission_string($ally, 4, $authority);
}

function can_fin_guerra($ally, $authority = NULL) {
    return validate_permission_string($ally, 5, $authority);
}

function can_ini_missao($ally, $authority = NULL) {
    return validate_permission_string($ally, 6, $authority);
}

function can_fin_missao($ally, $authority = NULL) {
    return validate_permission_string($ally, 7, $authority);
}

function can_guardar_itens($ally, $authority = NULL) {
    return validate_permission_string($ally, 8, $authority);
}

function can_sacar_itens($ally, $authority = NULL) {
    return validate_permission_string($ally, 9, $authority);
}

function can_guardar_berries($ally, $authority = NULL) {
    return validate_permission_string($ally, 10, $authority);
}

function can_sacar_berries($ally, $authority = NULL) {
    return validate_permission_string($ally, 11, $authority);
}

function validate_permission_string($ally, $type, $authority) {
    if (!$authority) {
        $authority = $ally["autoridade"];
    }
    return !!substr($ally[$authority], $type, 1);
}

function get_cargo_name($cargo) {
    switch ($cargo) {
        case 0:
            return "Líder";
        case 1:
            return "Oficial";
        case 2:
            return "Veterano";
        case 3:
            return "Aprendiz";
        case 4:
            return "Novato";
    }
}