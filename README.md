# Better Translatable Strings in ProcessWire

In this short tutorial we explore the benefits of the `wirePopulateStringTags` function.

## sprintf
Normally when translating a string with variables in it you will use the sprintf function like this:

```php
echo sprintf(__('There are %d %s in the %s'), $count, $items, $place));
```

Rendering something similar to `There are 32 apples in the basket`.

But this approach have one drawback. In the translation administration it will show something like `There are %d %s in the %s` meaning that you must rely on comments for giving more context so a translator could create a proper translation.

## wirePopulateStringTags
This function is available in the core of ProcessWire. (https://github.com/processwire/processwire/blob/master/wire/core/Functions.php#L369).

`function wirePopulateStringTags($str, $vars, array $options = array())`

Given a string `$str` and values `$vars`, replace tags in the string with the values. The `$vars` may also be an object, in which case values will be pulled as properties of the object. By default, tags are specified in the format: `{first_name}` where `first_name` is the name of the variable to pull from `$vars`, `‘{‘` is the opening tag character, and `‘}’` is the closing tag char. The tag parser can also handle subfields and OR tags, if `$vars` is an object that supports that. For instance `{products.title}` is a subfield, and `{first_name|title|name}` is an OR tag.

This wonderful function will allow more readable strings in the translation administration.

Using the previous example:

```php
echo wirePopulateStringTags(
    __('There are {count} {items} in the {place}'),
    ['items' => 'apples', 'count' => 32, 'place' => 'basket']
);
```

Will render something similar to `There are 32 apples in the basket`. And the translator will see `There are {count} {items} in the {place}` . Much better in my opinion than using vanilla `sprintf`. The other benefit is that the translator can easily change the order and repeat the value if needed.

## Using both

What about when you need to output formatted values?. For example, using this string `The percentage is %1.1f%%`. Will render something similar to `The percentage is 50.2%`.

Mixing both functions is super easy. Just apply the formatting before sending the params to the `wirePopulateStringTags` function.

```php
echo wirePopulateStringTags(
    __('The percentage is {percent}'), 
    ['percent' => sprintf('%1.1f%%', 50.1994)]
);
```

Using this approach we separate presentation from implementation. 
Now the translator could easily do his job without thinking about what means `%d` or `%s`.

## Shorthand version
This is a special function that combines `wirePopulateStringTags()` and `__()` . 

You could put it inside the `ready.php` file so it’s available site wide.

```php
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
```

### Example Usage

```php
echo _st('There are {count} {items} in the {place}', 
  ['count' => 5, 'items' => 'oranges', 'place' => 'tree']);
```

## Conclusion
[ProcessWire](https://processwire.com) implements a lot of functions that could ease our lives as developers. The `wirePopulateStringTags` function can be used for improving the translation process using named placeholders instead of type validation formats.
  
