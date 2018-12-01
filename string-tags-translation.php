<?php
/**
 * Perform a language translation replacing string tags.
 * 
 * Used as an alternative to sprintf in language string that requires variables.
 * uses wirePopulateStringTags function for replacing tags.
 *
 * The $vars may also be an object, in which case values will be pulled as properties of the object. 
 *
 * By default, tags are specified in the format: {first_name} where first_name is the name of the
 * variable to pull from $vars, '{' is the opening tag character, and '}' is the closing tag char.
 *
 * The tag parser can also handle subfields and OR tags, if $vars is an object that supports that.
 * For instance {products.title} is a subfield, and {first_name|title|name} is an OR tag. 
 *
 * @param string $text Text for translation.
 * @param WireData|object|array $vars Object or associative array to pull replacement values from.  
 * @param string $context Name of context
 * @param string $textdomain Textdomain for the text, may be class name, filename, or something made up by you. If omitted, a debug backtrace will attempt to determine automatically.
 * @param array $options Array of optional changes to default behavior, including: 
 *  - tagOpen: The required opening tag character(s), default is '{'
 *  - tagClose: The optional closing tag character(s), default is '}'
 *  - recursive: If replacement value contains tags, populate those too? Default=false. 
 *  - removeNullTags: If a tag resolves to a NULL, remove it? If false, tag will remain. Default=true. 
 *  - entityEncode: Entity encode the values pulled from $vars? Default=false. 
 *  - entityDecode: Entity decode the values pulled from $vars? Default=false.
 * @return string Translated text or original text if translation not available.
 *
 */
 
function _st($text, $vars, $context = null, $textdomain = null, array $options = array())
{
  return wirePopulateStringTags(__($text, $textdomain, $context), $vars, $options);
}
