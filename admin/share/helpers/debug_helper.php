<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 *
 * 從qeephp中截取的dump函數
 */

function dump($vars, $label = null, $depth = null) {
    $trace = debug_backtrace();

    $last = array_shift($trace);

    $file = htmlspecialchars($last['file']);
    $line = $last['line'];

    $html = ini_get('html_errors');
    $dump = new QDebug_Dump($html, $depth);

    if ($html) {
        $id = 'dump_block_' . md5("{$file}/{$line}");
        $content = <<<EOT
<div style="font-size: 12px; color: #333; background-color: #fff;
            padding: 10px; font-family: 'Courier New', Courier, monospace;">
    dump from:
    <a href="#" onclick="var e = document.getElementById('{$id}'); if (e.style.display == 'none') { e.style.display = 'block'; } else { e.style.display = 'none'; }; return false;" style="color: green;">{$file}</a>
    <span style="color: red;">({$line})</span>

    <div id="{$id}">
        <pre style="margin: 8px; padding: 10px; border: 1px solid #ccc; background-color: #f9f9f9;">

EOT;

        if ($label !== null && $label !== '') {
            $label = htmlspecialchars($label);
            $content .= <<<EOT
<span style="font-size: 18px; font-weight: bold; ">***&nbsp;{$label}&nbsp;***</span>

EOT;
        }
        $content .= $dump->escape($vars);

        $content .= <<<EOT

        </pre>
    </div>
</div>

EOT;
    } else {
        $content = "\ndump form: {$file} ({$line})\n";
        if ($label !== null && $label !== '') {
            $content .= $label . " :\n";
        }
        $content .= $dump->escape($vars) . "\n";
    }

    echo $content;
}

class QDebug_Dump {

    protected $html;
    protected $stack = array();
    protected $max_dump_depth = 3;
    protected $html_array = '<span style="color: #c11; font-weight: bold;">%s</span>';
    protected $html_obj = '<span style="color: #661; font-weight: bold; text-decoration: underline;">%s</span>';
    protected $html_gray = '<span style="color: #999;">%s</span>';
    protected $html_key = '<span style="color: #116;">%s</span>';
    protected $html_prop = '<span style="color: #33f; font-weight: bold;">%s</span>';
    protected $html_number = '<span style="color: #4e9a06;">%s</span>';
    protected $html_bool = '<span style="color: #75507b; font-weight: bold;">%s</span>';
    protected $html_null = '<span style="color: #3465a4; font-weight: bold;">%s</span>';
    protected $html_string = '<span style="color: #f57900;">%s</span>';
    protected $html_warn = '<span style="color: #611;">%s</span>';

    function __construct($html = null, $depth = null) {
        $this->html = (is_null($html)) ? ini_get('html_errors') : $html;
        if ($depth > 0) {
            $this->max_dump_depth = $depth;
        }
    }

    function escape($object, $depth = 1) {
        if (is_array($object)) {
            return $this->escapeArray($object, $depth);
        } elseif (is_resource($object)) {
            return $this->escapeResource($object, $depth);
        } elseif (!is_object($object)) {
            return $this->escapeValue($object);
        }

        foreach ($this->stack as $ref) {
            if ($ref === $object) {
                if ($this->html) {
                    return '** ' . sprintf($this->html_warn, 'Recursion')
                            . ' Object(' . $this->_htmlObjectName($object) . ') **';
                } else {
                    return '** Object(' . get_class($object) . ') **';
                }
            }
        }

        if ($depth > $this->max_dump_depth) {
            if ($this->html) {
                return '** ' . sprintf($this->html_warn, 'Max Dump Depth')
                        . ' Object(' . $this->_htmlObjectName($object) . ') **';
            } else {
                return '** Object(' . get_class($object) . ') **';
            }
        }

        array_push($this->stack, $object);

        if ($object instanceof Exception) {
            return $this->escapeException($object, $depth);
        }

        $class = get_class($object);
        $class_r = new ReflectionClass($class);
        $props = array();
        foreach ($class_r->getProperties() as $prop_r) {
            $props[$prop_r->getName()] = $prop_r;
        }

        $spc = str_repeat('  ', $depth);
        $return = array();
        if ($this->html) {
            $return[] = '<a name="Class_' . htmlentities($class) . '"></a>object('
                    . $this->_htmlObjectName($object) . ') {';
        } else {
            $return[] = "object({$class}) {";
        }

        $members = (array) $object;
        foreach ($props as $raw_name => $prop_r) {
            $name = $raw_name;
            $return[] = $spc . $this->_escapeProp($object, $prop_r, $members, $depth);
        }

        foreach ($members as $raw_name => $value) {
            $name = $raw_name;

            if ($name[0] == "\0") {
                $parts = explode("\0", $name);
                $name = $parts[2];
            }
            if (isset($props[$name]))
                continue;

            try {
                $prop_r = new ReflectionProperty($object, $name);
                $return[] = $spc . $this->_escapeProp($object, $prop_r, $members, $depth);
            } catch (Exception $ex) {
                if ($this->html) {
                    $return[] = $spc . sprintf($this->html_gray, 'private') . ' '
                            . "'" . sprintf($this->html_prop, $name) . "' "
                            . sprintf($this->html_gray, '=&gt;') . ' '
                            . $this->escape($value, $depth + 1);
                } else {
                    $return[] = "{$spc}private '{$name}' => " . $this->escape($value, $depth + 1);
                }
                unset($ex);
            }
        }

        $spc = substr($spc, 2);
        $return[] = "{$spc}}";
        $return[] = '';

        return implode("\n", $return);
    }

    function escapeException(Exception $ex) {
        $out = "exception '" . get_class($ex) . "'";
        if ($ex->getMessage() != '') {
            $out .= " with message '" . $ex->getMessage() . "'";
        }

        $out .= ' in ' . $ex->getFile() . ':' . $ex->getLine() . "\n\n";
        $out .= $ex->getTraceAsString();
        return $out;
    }

    function escapeResource($resource, $depth = 1) {
        if ($this->html) {
            return sprintf($this->html_gray, '**') . ' '
                    . sprintf($this->html_obj, htmlspecialchars((string) $resource))
                    . ' ' . sprintf($this->html_gray, '**');
        } else {
            return '** ' . (string) $resource . ' **';
        }
    }

    function escapeArray(array $arr, $depth = 1) {
        $spc = str_repeat('  ', $depth);
        if ($this->html) {
            $return = sprintf($this->html_array, 'array(');
        } else {
            $return = 'array(';
        }

        if ($depth > $this->max_dump_depth) {
            $return .= ' ** ' . sprintf($this->html_warn, 'Max Dump Depth') . ' ** ';
        } elseif (empty($arr)) {
            if ($this->html) {
                $return .= ' ' . sprintf($this->html_gray, 'empty') . ' ';
            } else {
                $return .= ' empty ';
            }
        } else {
            $return .= "\n";
            foreach ($arr as $key => $value) {
                $return .= $spc;
                if ($this->html) {
                    if (is_int($key) || is_double($key)) {
                        $return .= sprintf($this->html_number, htmlspecialchars($key))
                                . ' ' . sprintf($this->html_gray, '=&gt;') . ' ';
                    } else {
                        $return .= "'" . sprintf($this->html_key, htmlspecialchars($key)) . "'"
                                . ' ' . sprintf($this->html_gray, '=&gt;') . ' ';
                    }
                } else {
                    if (is_int($key) || is_double($key)) {
                        $return .= "{$key} => ";
                    } else {
                        $return .= "'{$key}' => ";
                    }
                }
                $return .= $this->escape($value, $depth + 1) . "\n";
            }
            $spc = substr($spc, 2);
            $return .= "{$spc}";
        }

        if ($this->html) {
            $return .= sprintf($this->html_array, ')');
        } else {
            $return .= ')';
        }

        return $return;
    }

    function escapeValue($value) {
        $return = gettype($value) . ' ';
        switch ($return) {
            case 'boolean ':
                $value = ($value) ? 'TRUE' : 'FALSE';
                $return .= ($this->html ? sprintf($this->html_bool, $value) : $value);
                break;

            case 'integer ':
            case 'double ':
                $return .= ($this->html ? sprintf($this->html_number, $value) : $value);
                break;

            case 'NULL ':
                $return .= ($this->html ? sprintf($this->html_null, $value) : $value);
                break;

            case 'string ':
                $return .= "'" . ($this->html ? sprintf($this->html_string, $value) : $value) . "'";
                $return .= ' (length=' . strlen($value) . ')';
                break;

            default:
                $return .= $value;
                break;
        }

        return $return;
    }

    function _htmlObjectName($object) {
        $class = htmlspecialchars(get_class($object));
        return "<a href=\"#Class_{$class}\">" . sprintf($this->html_obj, $class) . '</a>';
    }

    function _escapeProp($object, $prop_r, $members, $depth) {
        $name = $raw_name = $prop_r->getName();
        $class = get_class($object);
        $prefix = '';
        if ($prop_r->isPublic()) {
            $prefix = 'public';
        } elseif ($prop_r->isPrivate()) {
            $prefix = 'private';
            $raw_name = "\0" . $class . "\0" . $raw_name;
        } elseif ($prop_r->isProtected()) {
            $prefix = 'protected';
            $raw_name = "\0" . '*' . "\0" . $raw_name;
        }
        if ($prop_r->isStatic()) {
            $prefix = 'static ' . $prefix;
        }

        if ($this->html) {
            $name = sprintf($this->html_gray, $prefix) . " '"
                    . sprintf($this->html_prop, htmlspecialchars($name)) . "' ";
        } else {
            $name = "{$prefix} '{$name}'";
        }

        if (array_key_exists($raw_name, $members)) {
            $text = $this->escape($members[$raw_name], $depth + 1);
        } elseif (method_exists($prop_r, 'setAccessible')) {
            $prop_r->setAccessible(true);
            $text = $this->escape($prop_r->getValue($object), $depth + 1);
        } elseif ($prop_r->isPublic()) {
            $text = $this->escape($prop_r->getValue($object), $depth + 1);
        } else {
            $text = '** Need PHP 5.3 to get value **';
        }

        if ($this->html) {
            return "{$name} " . sprintf($this->html_gray, '=&gt;') . " {$text}";
        } else {
            return "{$name} => {$text}";
        }
    }

}
