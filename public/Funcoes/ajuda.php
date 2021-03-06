<?php
function ajuda($title, $content, $collapse = true, $id = "help-box") {
    return "<p>" . ($collapse
            ? "<div class=\"text-right\">
            <a class=\"btn btn-info\" data-toggle=\"collapse\" href=\"#$id\">
                <i class=\"fa fa-question\"></i> Ajuda
            </a>
        </div>" : "") .
        "<div id=\"$id\" class=\"collapse " . ($collapse ? "out" : "in") . " panel panel-info\">
            <div class=\"panel-heading\">
            <button type=\"button\" data-toggle=\"collapse\" href=\"#$id\" class=\"close\"><span>&times;</span></button>
            $title
            </div>
            <div class=\"panel-body\">
                $content
            </div>
        </div>
    </p>";
}