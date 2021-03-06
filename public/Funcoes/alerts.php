<?php
function get_alert($class = "") {
    global $userDetails;
    return $userDetails->alerts->get_alert($class);
}