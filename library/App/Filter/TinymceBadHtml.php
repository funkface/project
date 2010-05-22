<?php

class App_Filter_TinymceBadHtml implements Zend_Filter_Interface
{
    public function filter($value)
    {
        // If <li> has no class of it's own, and contains a span, move any classes or style associated with the span to the li and remove the span
        $value = preg_replace('/\<li>\<span(.*)(>.*)\<\/span>\<\/li>/', '<li$1$2</li>', $value);

        // convert trades to code
        $value = str_replace('&trade;', '&#8482;', $value);

        // remove trailing br
        $value = preg_replace('/\<br \/>$/', '', $value);

        // Remove comments from word
        $comments = array(
            '/<!.*>/',
            '/&gt;!--.*&lt;/');
        $value = preg_replace($comments, '', $value);

        // Remove divs + p's
        //$value = str_replace(array('<div>', '</div>', '<p>', '</p>'), '', $value);

        // Remove br's at end of h
        //$value = str_replace('<br /></h', '</h', $value);

        // strip slashes
        $value = stripslashes($value);

        // Remove oft used dodgy word chars
        $wordCharsSearch = array(
            '&bdquo;',
            '&ldquo;',
            '&rdquo;',
            '&uml;',
            '&lsquo;',
            '&rsquo;',
            '&acute;',
            '&ndash;',
            '&mdash;',
            '&sbquo;'
            );
        $wordCharsReplace = array(
            '"',
            '"',
            '"',
            '"',
            "'",
            "'",
            "'",
            '-',
            '-',
            ','
            );
        $value = str_replace($wordCharsSearch, $wordCharsReplace, $value);

        return $value;
    }
}