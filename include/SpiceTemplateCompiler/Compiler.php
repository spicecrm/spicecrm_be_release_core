<?php
namespace SpiceCRM\includes\SpiceTemplateCompiler;

/*
 * a class to compile templates. used in EmailTemplates and OutputTemplates...
 * @author Sebastian Franz
 */
class Compiler
{

    public static function compile($txt, \SugarBean $bean = null, $lang = 'de_DE')
    {
        global $current_user;
        $app_list_strings = return_app_list_strings_language($lang);

        if (preg_match_all("#\{([a-zA-Z\.\_0-9]+)\}#", $txt, $matches))
        {
            for ($i = 0; $i < count($matches[1]); $i++)
            {
                $m = $matches[1][$i];
                $parts = explode('.', $m);
                $part = $parts[0];
                switch ($part)
                {
                    case 'bean':
                        $obj = $bean;
                        break;
                    case 'current_user':
                        $obj = $current_user;
                        break;
                    case 'system':
                        $obj = new System();
                        break;
                    default:
                        //echo "$part is unknowen...";
                        continue(2);
                }

                /**
                 * loop recursively through the parts to load relations and return the last part of it
                 * {bean.product.publisher.name}
                 *  bean ->
                 *      product = link -> load product ->
                 *          publisher = link -> load publisher ->
                 *              name = attribute -> return value;
                 */
                $loopThroughParts = function($obj, $level = 0) use (&$parts, &$loopThroughParts) {
                    global $app_list_strings;

                    $part = $parts[$level];
                    if (is_callable([$obj, $part])) {
                        $value = $obj->{$part}();
                    } else {
                        $field = $obj->field_defs[$part];
                        switch ($field['type']) {
                            case 'link':
                                $next_bean = $obj->get_linked_beans($field['name'], $field['bean_name'])[0];
                                if ($next_bean) {
                                    $level++;
                                    return $loopThroughParts($next_bean, $level);
                                } else {
                                    $value = '';
                                }
                                break;
                            case 'enum':
                                $value = $app_list_strings[$obj->field_defs[$part]['options']][$obj->{$part}];
                                break;
                            case 'multienum':
                                $values = explode(',', $obj->{$part});
                                foreach($values as &$value){
                                    $value = trim($value, '^');
                                    $value = $app_list_strings[$obj->field_defs[$part]['options']][$value];
                                }
                                $value = implode(', ', $values);
                                // unencodeMultienum can't be used because of a different language...
                                //$value = implode(', ', unencodeMultienum($obj->{$parts[$level]}));
                                break;
                            default:
                                $value = $obj->{$part};
                                break;
                        }
                    }
                    return $value;
                };

                $value = $loopThroughParts($obj, 1);
                $txt = str_replace($matches[0][$i], $value, $txt);
            }
        }
        // remove unresolved placeholders...
        //$txt = preg_replace("#\{([a-z\.\_0-9]+)\}#", "", $txt);
        $txt = preg_replace("#='(.*?)'#", '="$1"', $txt);
        return $txt;
    }
}