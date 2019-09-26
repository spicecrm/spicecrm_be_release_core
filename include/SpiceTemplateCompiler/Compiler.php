<?php

namespace SpiceCRM\includes\SpiceTemplateCompiler;

/*
 * a class to compile templates. used in EmailTemplates and OutputTemplates...
 *
 * this allows logic in html templates to be parsed.
 *
 * parse variables:
 *   <p>this is the account {bean.name} with contact {bean.contacts.first_name}</p>
 *   typical starting point is bean but can also be current_user or system for system functions
 *   on the bean the value can be an object value or a method to be called
 *
 * loop through arrays with data-spicefor
 *      <table style="height: 64px;" width="100%">
 *          <tbody>
 *              <tr data-spicefor="bean.contacts as contact">
 *                  <td style="width: 33%;">{contact.first_name}</td>
 *                  <td style="width: 33%;">{contact.last_name}</td>
 *                  <td style="width: 33%;">{contact.email1}</td>
 *              </tr>
 *          </tbody>
 *      </table>
 *
 *      add spoicefor as an attribute to add a loop
 *
 * add conditions with data-spiceif
 *      <p data-spiceif="bean.industry == 'Chemicals'">a chemicals customer</p>
 *
 */

class Compiler
{
    var $additonal_values;
    var $doc;
    var $root;
    var $lang;

    public function __construct()
    {
        $this->initialize();
    }

    private function initialize(){
        $this->doc = new \DOMDocument('1.0');
        $this->root = $this->doc->appendChild( $this->doc->createElement('html') );
    }

    public function compile($txt, \SugarBean $bean = null, $lang = 'de_DE', array $additionalValues = null)
    {
        $this->initialize();

        $this->additonal_values = $additionalValues;
        $this->lang = $lang;

        $dom = new \DOMDocument();
        $html = preg_replace("/\n|\r|\t/", "", html_entity_decode($txt, ENT_QUOTES));
        $dom->loadHTML('<?xml encoding="utf-8"?>' . $html );

        $dummy = $dom->getElementsByTagName('html');
        foreach( $this->parseDom( $dummy[0], ['bean' => $bean] ) as $newElement ){
            $this->root->appendChild($newElement);
        };
        return $this->doc->saveHTML();
    }

    private function parseDom($thisNode, $beans = []){
        $elements = [];
        foreach ($thisNode->childNodes as $node)
        {
            switch(get_class($node)){
                case 'DOMDocumentType':
                    $this->parseDom($node, $beans);
                    break;
                case 'DOMElement':
                    $newElement = $this->doc->createElement($node->tagName);

                    // check spiceif, spicefor
                    if($node->getAttribute('data-spiceif')){
                        $spiceif = $node->getAttribute('data-spiceif');
                        if ($this->processCondition($spiceif, $beans)) {
                            $elements[] = $this->createNewElement($node, $beans);
                        }
                    } else if($node->getAttribute('data-spicefor')){
                        $spicefor = $node->getAttribute('data-spicefor');
                        $forArray = explode(' as ', $spicefor);
                        $linkedBeans = $this->getLinkedBeans($forArray[0], NULL, $beans);
                        foreach ($linkedBeans as $linkedBean) {
                            $elements[] = $this->createNewElement($node, array_merge($beans, [$forArray[1] => $linkedBean]));
                            // $response .= $this->processBlocks($this->getBlocks($contentString), array_merge($beans, [$forArray[1] => $linkedBean]), $lang);
                        }
                        break;
                    } else {
                        $elements[] = $this->createNewElement($node, $beans);
                    }
                    break;
                case 'DOMText':
                    $elementcontent = $this->compileblock($node->textContent, $beans, $this->lang);

                    // check if we have embedded HTML that is returned from the replaceing functions
                    // ToDo: check if there is not a nice way to do this
                    if(strip_tags($elementcontent) != $elementcontent) {
                        $elementdom = new \DOMDocument();
                        $elementhtml = preg_replace("/\n|\r|\t/", "", html_entity_decode($elementcontent, ENT_QUOTES));
                        $elementdom->loadHTML('<?xml encoding="utf-8"?><embedded>'.$elementhtml.'</embedded>');
                        $embeddednode = $elementdom->getElementsByTagName('embedded');
                        $elements[] = $this->createNewElement($embeddednode[0], $beans);
                    } else {
                        $newElement = $this->doc->createTextNode($elementcontent);
                        $elements[] = $newElement;
                    }
                    break;
            }
        }
        return $elements;
    }

    private function createNewElement($thisElement, $beans){
        $newElement = $this->doc->createElement($thisElement->tagName);
        if($thisElement->hasAttributes()){
            foreach($thisElement->attributes as $attribute){
                switch($attribute->nodeName){
                    case 'data-spicefor':
                    case 'data-spiceif':
                        break;
                    default:
                        $newAttribute = $this->doc->createAttribute($attribute->nodeName);
                        $newAttribute->value = $attribute->nodeValue;
                        $newElement->appendChild($newAttribute);
                }
            }
        }
        foreach($this->parseDom($thisElement, $beans) as $newChild){
            $newElement->appendChild($newChild);
        }
        return $newElement;
    }

    /**
     * recursive function to explode a locator string and return an aray of beans following the path
     *
     * @param $locator the string to find the link e.g. bean.contacts.calls
     * @param $beans the current set of beans in teh scope of the locator string
     * @return array of beans
     */
    private function getLinkedBeans($locator, $obj = NULL, $beans = [])
    {
        $parts = explode('.', $locator);

        // if we do not have an object we try to resolve it
        if (!$obj) {
            $obj = $this->getObject($parts[0], $beans);
        }
        // if we do not find it return an empty object
        if (!$obj) return [];

        // check that the field is a link
        if ($obj->field_defs[$parts[1]]['type'] != 'link') return [];

        $obj->load_relationship($parts[1]);
        $relModule = $obj->{$parts[1]}->getRelatedModuleName();
        $linkedBeans = $obj->get_linked_beans($parts[1], $relModule);

        if (count($parts) > 2) {
            $deepLinkedBeans = [];
            foreach ($linkedBeans as $linkedBean) {
                $deepLinkedBeans = array_merge($deepLinkedBeans, $this->getLinkedBeans(implode('.', array_shift($parts)), $linkedBean));
            }
            return $deepLinkedBeans;
        } else {
            return $linkedBeans;
        }
    }

    private function processCondition($condition, $beans)
    {

        // match regular operators
        // preg_match_all('/[\!<>=\/\*]+/', html_entity_decode($condition), $operators);

        // if we match none or more than one operator this cannot be true and return false
        //if(count($operators) != 1) return false;

        $conditionparts = explode(' ', $condition);
        switch ($conditionparts[1]) {
            case '==':
                return $this->getValue($conditionparts[0], $beans) == trim($conditionparts[2], "'");
                break;
            case '!=':
                return $this->getValue($conditionparts[0], $beans) != trim($conditionparts[2], "'");
                break;
        }
        return false;

    }

    private function getValue($locator, $beans)
    {
        $parts = explode('.', $locator);
        $part = $parts[0];

        // get the object
        $obj = $this->getObject($part, $beans);
        if (!$obj) return '';

        /**
         * loop recursively through the parts to load relations and return the last part of it
         * {bean.product.publisher.name}
         *  bean ->
         *      product = link -> load product ->
         *          publisher = link -> load publisher ->
         *              name = attribute -> return value;
         */
        $loopThroughParts = function ($obj, $level = 0) use (&$parts, &$loopThroughParts) {
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
                        foreach ($values as &$value) {
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

        return $loopThroughParts($obj, 1);
    }

    private function getObject($object, $beans)
    {

        switch ($object) {
            case 'current_user':
                $obj = $GLOBALS['current_user'];
                break;
            case 'system':
                $obj = new System();
                break;
            case 'value':
                $obj = (object)$this->additionalValues;
                break;
            default:
                $obj = $beans[$object];
        }

        return $obj ?: false;
    }

    public function compileblock($txt, $beans = [], $lang = 'de_DE', array $additionalValues = null)
    {
        global $current_user, $current_language, $app_list_strings;
        // overwrite the current app_list_strings to the language of the template...
        $app_list_strings = return_app_list_strings_language($lang);

        if (preg_match_all("#\{([a-zA-Z\.\_0-9]+)\}#", $txt, $matches)) {
            for ($i = 0; $i < count($matches[1]); $i++) {
                $m = $matches[1][$i];
                $parts = explode('.', $m);
                $part = $parts[0];

                // get the object
                $obj = $this->getObject($part, $beans);
                if (!$obj) continue(1);

                /**
                 * loop recursively through the parts to load relations and return the last part of it
                 * {bean.product.publisher.name}
                 *  bean ->
                 *      product = link -> load product ->
                 *          publisher = link -> load publisher ->
                 *              name = attribute -> return value;
                 */
                $loopThroughParts = function ($obj, $level = 0) use (&$parts, &$loopThroughParts) {
                    global $app_list_strings, $userDateFormat, $userTimeFormat, $userDateTimeFormat;
                    //get user preferences for date and time format
                    $userDateFormat = $GLOBALS['current_user']->getPreference("datef");
                    $userTimeFormat = $GLOBALS['current_user']->getPreference("timef");
                    $userDateTimeFormat = $userDateFormat." ".$userTimeFormat;

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
                                foreach ($values as &$value) {
                                    $value = trim($value, '^');
                                    $value = $app_list_strings[$obj->field_defs[$part]['options']][$value];
                                }
                                $value = implode(', ', $values);
                                // unencodeMultienum can't be used because of a different language...
                                //$value = implode(', ', unencodeMultienum($obj->{$parts[$level]}));
                                break;
                            case 'date':
                                //set to user preferences format
                                $value = date($userDateFormat, strtotime($obj->{$part}));
                                break;
                            case 'datetime':
                            case 'datetimecombo':
                                //set to user preferences format
                                $value = date($userDateTimeFormat, strtotime($obj->{$part}));
                                break;
                            case 'time':
                                //set to user preferences format
                                $value = date($userTimeFormat, strtotime($obj->{$part}));
                                break;
                            case 'currency':
                                // $currency = \BeanFactory::getBean('Currencies');
                                $value = currency_format_number($obj->{$part});
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
        // set the current app_list_strings back to the current language...
        $app_list_strings = return_app_list_strings_language($current_language);

        return nl2br($txt);
    }
}
