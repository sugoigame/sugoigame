<?php
function bbcode_to_html($bbtext) {
    $bbtags = array(
        '[heading1]' => '<h1>', '[/heading1]' => '</h1>',
        '[heading2]' => '<h2>', '[/heading2]' => '</h2>',
        '[heading3]' => '<h3>', '[/heading3]' => '</h3>',
        '[h1]' => '<h1>', '[/h1]' => '</h1>',
        '[h2]' => '<h2>', '[/h2]' => '</h2>',
        '[h3]' => '<h3>', '[/h3]' => '</h3>',

        '[paragraph]' => '<p>', '[/paragraph]' => '</p>',
        '[para]' => '<p>', '[/para]' => '</p>',
        '[p]' => '<p>', '[/p]' => '</p>',
        '[left]' => '<p style="text-align:left;">', '[/left]' => '</p>',
        '[right]' => '<p style="text-align:right;">', '[/right]' => '</p>',
        '[center]' => '<p style="text-align:center;">', '[/center]' => '</p>',
        '[justify]' => '<p style="text-align:justify;">', '[/justify]' => '</p>',

        '[bold]' => '<strong>', '[/bold]' => '</strong>',
        '[italic]' => '<i>', '[/italic]' => '</i>',
        '[underline]' => '<span style="text-decoration:underline;">', '[/underline]' => '</span>',
        '[b]' => '<strong>', '[/b]' => '</strong>',
        '[i]' => '<i>', '[/i]' => '</i>',
        '[u]' => '<span style="text-decoration:underline;">', '[/u]' => '</span>',
        '[break]' => '<br>',
        '[br]' => '<br>',
        '[newline]' => '<br>',
        '[nl]' => '<br>',

        '[unordered_list]' => '<ul>', '[/unordered_list]' => '</ul>',
        '[list]' => '<ul>', '[/list]' => '</ul>',
        '[ul]' => '<ul>', '[/ul]' => '</ul>',

        '[ordered_list]' => '<ol>', '[/ordered_list]' => '</ol>',
        '[ol]' => '<ol>', '[/ol]' => '</ol>',
        '[list_item]' => '<li>', '[/list_item]' => '</li>',
        '[li]' => '<li>', '[/li]' => '</li>',

        '[*]' => '<li>', '[/*]' => '</li>',
        '[code]' => '<code>', '[/code]' => '</code>',
        '[preformatted]' => '<pre>', '[/preformatted]' => '</pre>',
        '[pre]' => '<pre>', '[/pre]' => '</pre>',
        '[quote]' => '<blockquote>', '[/quote]' => '</blockquote>',
    );

    $bbtext = str_ireplace(array_keys($bbtags), array_values($bbtags), $bbtext);

    $bbextended = array(
        "/\[url](.*?)\[\/url]/i" => "<a href=\"http://$1\" title=\"$1\">$1</a>",
        "/\[url=(.*?)\](.*?)\[\/url\]/i" => "<a href=\"$1\" title=\"$1\">$2</a>",
        "/\[email=(.*?)\](.*?)\[\/email\]/i" => "<a href=\"mailto:$1\">$2</a>",
        "/\[mail=(.*?)\](.*?)\[\/mail\]/i" => "<a href=\"mailto:$1\">$2</a>",
        "/\[img\]([^[]*)\[\/img\]/i" => "<img src=\"$1\" alt=\" \" />",
        "/\[image\]([^[]*)\[\/image\]/i" => "<img src=\"$1\" alt=\" \" />",
        "/\[image_left\]([^[]*)\[\/image_left\]/i" => "<img src=\"$1\" alt=\" \" class=\"img_left\" />",
        "/\[image_right\]([^[]*)\[\/image_right\]/i" => "<img src=\"$1\" alt=\" \" class=\"img_right\" />",
        "/\[size=(.*?)\](.*?)\[\/size\]/i" => "<span style='font-size: $1%;'>$2</span>",
        '/\\n/i' => '<br/>'
    );

    foreach ($bbextended as $match => $replacement) {
        $bbtext = preg_replace($match, $replacement, $bbtext);
    }
    return $bbtext;
}