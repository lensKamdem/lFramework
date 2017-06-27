<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Helpers;

use LFramework\Base\BaseClass as BaseClass;

/**
 * Description of Html
 *
 * @author Lens
 */
class Html extends BaseClass
{

    public static $voidElements = [
        'area' => 1,
        'base' => 1,
        'br' => 1,
        'col' => 1,
        'command' => 1,
        'embed' => 1,
        'hr' => 1,
        'img' => 1,
        'input' => 1,
        'keygen' => 1,
        'link' => 1,
        'meta' => 1,
        'param' => 1,
        'source' => 1,
        'track' => 1,
        'wbr' => 1,
    ];
    public static $attributeOrder = [
        'type',
        'id',
        'class',
        'name',
        'value',

        'href',
        'src',
        'action',
        'method',

        'selected',
        'checked',
        'readonly',
        'disabled',
        'multiple',

        'size',
        'maxlength',
        'width',
        'height',
        'rows',
        'cols',

        'alt',
        'title',
        'rel',
        'media',
    ];
    
    
    public static function renderTagAttributes($attributes)
    {
        if (count($attributes) > 1) {
            $sorted = [];
            foreach (static::$attributeOrder as $name) {
                if (isset($attributes[$name])) {
                    $sorted[$name] = $attributes[$name];
                }
            }
            $attributes = array_merge($sorted, $attributes);
        }

        $html = "";
        foreach ($attributes as $name => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $html .= " $name";
                }
            } elseif (is_array($value)) {
                if ($name === "class") {
                    if (empty($value)) {
                        continue;
                    }
                    $html .= " $name=\"" . static::encode(implode(' ', $value)) . '"';
                } elseif ($name === "style") {
                    if (empty($value)) {
                        continue;
                    }
                    $html .= " $name=\"" . static::encode(static::cssStyleFromArray($value)) . '"';
                } else {
                     $html .= " $name=\"" . static::encode(implode(' ', $value)) . '"';
                }
            } elseif ($value !== null) {
                $html .= " $name=\"" . static::encode($value) . "\"";
            }
        }

        return $html;
    }
     public static function encode($content, $doubleEncode = true)
    {
        return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 
 \          LFramework::$application ? \LFramework::$application->getCharset() : 'UTF-8', $doubleEncode);
    }
     public static function cssStyleFromArray(array $style)
    {
        $result = '';
        foreach ($style as $name => $value) {
            $result .= "$name: $value; ";
        }
        // return null if empty to avoid rendering the "style" attribute
        return $result === '' ? null : rtrim($result);
    }
    public static function tag($name, $content = "", $options = array())
    {
        if ($name === null || $name === false) {
            return $content;
        }
        $html = "<{$name}" . static::renderTagAttributes($options) . ">";
        return isset(static::$voidElements[strtolower($name)]) ? $html : "{$html}{$content}</$name>";
    }
    public static function cssFile($url, $options = array())
    {
        if (!isset($options['rel'])) {
            $options['rel'] = 'stylesheet';
        }
        $options['href'] = $url;
        
        return static::tag('link', '', $options);
    }
    public static function jsFile($url, $options = array())
    {
        $options['src'] = $url;
        return static::tag('script', '', $options);
    }

}
