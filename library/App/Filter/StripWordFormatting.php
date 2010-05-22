<?php
class App_Filter_StripWordFormatting extends Zend_Filter_StripTags
{

    protected $tagWhiteList = array(
        'p', 'br', 'h2', 'h3', 'h4', 'h5',
        'b','strong','i','emphasis', 'em',
        'ul','ol','li',
        'img' => array('src', 'title', 'alt', 'class'),
        'table' => array('class'),
        'caption', 'thead', 'tbody', 'tr',
        'th' => array('colspan', 'rowspan'), 'td' => array('colspan', 'rowspan'),
        'a' => array('href', 'title', 'target', 'class')
    );

    public function __construct(){

        parent::__construct(array('allowTags' => $this->tagWhiteList));
    }

    public function filter($value)
    {
        $value = preg_replace(
            array(
              // Remove invisible content
                '@<head[^>]*?>.*?</head>@siu',
                '@<style[^>]*?>.*?</style>@siu',
                '@<script[^>]*?.*?</script>@siu',
                '@<object[^>]*?.*?</object>@siu',
                '@<embed[^>]*?.*?</embed>@siu',
                '@<applet[^>]*?.*?</applet>@siu',
                '@<noframes[^>]*?.*?</noframes>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
                '@<noembed[^>]*?.*?</noembed>@siu',
              // Add line breaks before and after blocks
                '@</?((address)|(blockquote)|(center)|(del))@iu',
                '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                '@</?((table)|(th)|(td)|(caption))@iu',
                '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                '@</?((frameset)|(frame)|(iframe))@iu',
            ),
            array(
                ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
                "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
                "\n\$0", "\n\$0",
            ),
            $value
        );

        $value = parent::filter($value);

        // collapse paragraphs and whitespace
        $value = preg_replace(
            array('@<p[^>]*?>(<p[^>]*?>|</p>|&nbsp;|\s)*?</p>@iu', '@[ \t]*[\n\r]+[ \t]*@iu', '@[ \t]+@iu'),
            array(' ', ' ', ' '),
            $value
        );

        return trim($value);
    }

}
?>