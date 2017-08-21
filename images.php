<?php
# Images tag from BIONICLEsector01
# Made by Steven Wert
# Modified for use on Brickimedia
# Patched by Shawn M. Dickinson

$wgExtensionFunctions[] = "wfEemageExtension";

$wgExtensionCredits[ 'parserhook' ][] = array(
        'path' => __FILE__,
        'name' => 'Image Tags',
        'author' => '[[User:Swert|Steven Wert]]',
        'url' => 'http://www.biosector01.com',
        'description' => 'A simple extension for embedding external images without compromising security, made by Steven Wert, patched by Shawn M. Dickinson',
        'version' => '1.5',
);

# img_url_escape
# Discards all text that follows a quotation mark, including the quotation mark.
# E.g. 'foo.com/bar.png" onload="evil js" /><arbitrary HTML>' => 'foo.com/bar.png'
# Note: works for both single and double quotation marks

function img_url_escape($input) {
    if(strpos($input, '"') !== false) return substr($input, 0, strpos($input, '"'));
    elseif(strpos($input, "'") !== false) return substr($input, 0, strpos($input, "'"));
    else return $input;
}

function wfEemageExtension() {
    global $wgParser;

    # register the extension with the WikiText parser
    # the first parameter is the name of the new tag.
    # In this case it defines the tag <example> ... </example>
    # the second parameter is the callback function for
    # processing the text between the tags
    
    $wgParser->setHook( "img", "renderEemage" );
}

# The callback function for converting the input text to HTML output
function renderEemage( $input, $argv, $parser ) {
# $argv is an array containing any arguments passed to the
# extension like <example argument="foo" bar>..
# Put this on the sandbox page:  (works in MediaWiki 1.5.5)
#   <example argument="foo" argument2="bar">Testing text **example** in
# between the new tags</example>

    $parsed_input = img_url_escape($input);
    preg_match("/width=\"+([^\"]+|[^\z]+)/", $input, $width_matches);
    preg_match("/height=\"+([^\"]+|[^\z]+)/", $input, $height_matches);
    preg_match("/style=\"+([^\"]+|[^\z]+)/", $input, $style_matches);

    $output = "<img src=\"$parsed_input\" alt=\"External Image\" ";

    if($width_matches[1]){
      $output .= "width=\"$width_matches[1]\" ";
    }
    if($height_matches[1]){
      $output .= "height=\"$height_matches[1]\" ";
    }
    if($style_matches[1]){
      $output .= "style=\"$style_matches[1]\" ";
    }

    $output .= "/>";

    return $output;

}

?>
