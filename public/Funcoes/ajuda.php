<?php
function ajuda($title, $content, $collapse = true, $id = "help-box")
{
    return "<a data-toggle=\"collapse\" data-trigger=\"focus\" href=\"#$id\">
                <i class=\"fa fa-question\"></i>
            </a>" .
        "<div id=\"$id\" class=\"help-box collapse " . ($collapse ? "out" : "in") . " panel panel-info\">
            <div class=\"panel-heading\">
            <button type=\"button\" data-toggle=\"collapse\" href=\"#$id\" class=\"close\"><span>&times;</span></button>
            $title
            </div>
            <div class=\"panel-body\">
                $content
            </div>
        </div>";
}
