<?php
namespace selvinortiz\doxter\common\parsers;

use Craft;

use function selvinortiz\doxter\doxter;
use selvinortiz\doxter\models\ShortcodeModel;

/**
 * Class Shortcode
 *
 * @package Craft
 */
class Shortcode extends BaseParser
{
    /**
     * @var Shortcode
     */
    protected static $_instance;

    /**
     * The method that should be called if a class name is provided as the callback
     *
     * @var string
     */
    protected static $defaultMethod = 'parse';

    /**
     * @var array
     */
    protected $registeredClasses = [];

    /**
     * Registered shortcodes
     *
     * @var array
     */
    protected $shortcodes = [];

    /**
     * Registers an array of shortcodes with Doxter
     *
     * @param array $shortcodes
     */
    public function registerShortcodes(array $shortcodes)
    {
        if (count($shortcodes))
        {
            foreach ($shortcodes as $shortcode => $callback)
            {
                $this->registerShortcode($shortcode, $callback);
            }
        }
    }

    /**
     * Registers a new shortcode and its associated callback|class
     *
     * @note
     * Supported shortcode registration syntax
     * shortcode            |   callback
     * ----                 |   --------
     * 'shortcode'          |   'function'
     * 'shortcode:another'  |   function(DoxterShortcodeModel $code) {}
     *                      |   'Namespace\\Class'
     *                      |   'Namespace\\Class@method'
     *
     * @param string $shortcode
     * @param mixed  $callback
     *
     * @return void
     */
    public function registerShortcode($shortcode, $callback)
    {
        if (is_string($shortcode))
        {
            if (strpos($shortcode, ':') !== false)
            {
                $shortcodes = array_filter(array_map('trim', explode(':', $shortcode)));

                foreach ($shortcodes as $code)
                {
                    $this->registerShortcode($code, $callback);
                }
            }
            else
            {
                $this->shortcodes[$shortcode] = $callback;
            }
        }
    }

    /**
     * Unregisters the specified shortcode by given name
     *
     * @param string $name
     *
     * @return void
     */
    public function unregisterShortcode($name)
    {
        if ($this->exists($name))
        {
            unset($this->shortcodes[$name]);
        }
    }

    public function parse($source, array $options = [])
    {
        if (!$this->canBeSafelyParsed($source))
        {
            return $source;
        }

        return $this->compile($source);
    }

    /**
     * Compiles the shortcodes in content provided
     *
     * @param  string $content
     *
     * @return string
     */
    public function compile($content)
    {
        $pattern = $this->getRegex();

        return preg_replace_callback("/{$pattern}/s", [&$this, 'render'], $content);
    }

    /**
     * @param $matches
     *
     * @return mixed|string
     *
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function render($matches)
    {
        $shortcode          = new ShortcodeModel();
        $shortcode->name    = $matches[2];
        $shortcode->params  = $this->getParameters($matches);
        $shortcode->content = $matches[5];

        if (isset($shortcode->params['verbatim']))
        {
            return str_replace(' verbatim', '', $matches[0]);
        }

        $variables = array_merge(
            $shortcode->params,
            [
                'content'   => $shortcode->content,
                'shortcode' => $shortcode,
            ]
        );

        $tags = doxter()->getSettings()->getRegisteredShortcodeTags();

        $template = $tags[$shortcode->name] ?? '';

        if (empty($template))
        {
            doxter()->warning(sprintf('Shortcode "%s" has not been registered', $shortcode->name));

            return '';
        }

        if (!Craft::$app->view->doesTemplateExist($template))
        {
            doxter()->warning(sprintf('Missing template for Shortcode "%s"', $shortcode->name));

            return '';
        }

        return Craft::$app->view->renderTemplate($template, $variables);
    }

    /**
     * @return string
     */
    protected function getRegex()
    {
        return
            '\\['
            .'(\\[?)'
            ."([a-z]{3,})"
            .'(?![\\w-])'
            .'('
            .'[^\\]\\/]*'
            .'(?:'
            .'\\/(?!\\])'
            .'[^\\]\\/]*'
            .')*?'
            .')'
            .'(?:'
            .'(\\/)'
            .'\\]'
            .'|'
            .'\\]'
            .'(?:'
            .'('
            .'[^\\[]*+'
            .'(?:'
            .'\\[(?!\\/\\2\\])'
            .'[^\\[]*+'
            .')*+'
            .')'
            .'\\[\\/\\2\\]'
            .')?'
            .')'
            .'(\\]?)';
    }

    /**
     * Parses shortcode string to an attributes array
     *
     * @param string $text
     *
     * @return array
     */
    protected function parseAttributes($text)
    {
        $attributes = [];
        $pattern    = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text       = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);

        if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER))
        {
            foreach ($match as $m)
            {
                if (!empty($m[1]))
                {
                    $attributes[strtolower($m[1])] = stripcslashes($m[2]);
                }
                elseif (!empty($m[3]))
                {
                    $attributes[strtolower($m[3])] = stripcslashes($m[4]);
                }
                elseif (!empty($m[5]))
                {
                    $attributes[strtolower($m[5])] = stripcslashes($m[6]);
                }
                elseif (isset($m[7]) and strlen($m[7]))
                {
                    $attributes[] = stripcslashes($m[7]);
                }
                elseif (isset($m[8]))
                {
                    $attributes[] = stripcslashes($m[8]);
                }
            }
        }
        else
        {
            $attributes = ltrim($text);
        }

        return $attributes;
    }

    /**
     * Strips any shortcodes from content provided
     *
     * @param  string $content
     *
     * @return string
     */
    public function strip($content)
    {
        if (empty($this->shortcodes))
        {
            return $content;
        }

        $pattern = $this->getRegex();

        return preg_replace_callback("/{$pattern}/s", function($m) {
            if ($m[1] == '[' && $m[6] == ']')
            {
                return substr($m[0], 1, -1);
            }

            return $m[1].$m[6];
        }, $content);
    }

    /**
     * Returns the total count of registered shortcodes
     *
     * @return int
     */
    public function getShortcodeCount()
    {
        return count($this->shortcodes);
    }

    /**
     * Return true is the given name exist in shortcodes array.
     *
     * @param  string $name
     *
     * @return boolean
     */
    public function exists($name)
    {
        return array_key_exists($name, $this->shortcodes);
    }

    /**
     * Return true is the given content contains the given name shortcode.
     *
     * @param  string $content
     * @param  string $shortcode
     *
     * @return boolean
     */
    public function contains($content, $shortcode)
    {
        if ($this->exists($shortcode))
        {
            preg_match_all('/'.$this->getRegex().'/s', $content, $matches, PREG_SET_ORDER);

            if (empty($matches))
            {
                return false;
            }

            foreach ($matches as $shortcode)
            {
                if ($shortcode === $shortcode[2])
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Returns parameters found in the shortcodes
     *
     * @param  array $matches
     *
     * @return array
     */
    protected function getParameters($matches)
    {
        $params = $this->parseAttributes($matches[3]);

        if (!is_array($params))
        {
            $params = [$params];
        }

        foreach ($params as $param => $value)
        {
            // Handles attributes without values ([shortcode attribute])
            if (is_numeric($param) && is_string($value))
            {
                $params[$value] = true;

                unset($params[$param]);
            }
        }

        return $params;
    }

    /**
     * Resolves the callback for a given shortcode and returns it
     *
     * @param string $name
     *
     * @return \closure
     */
    protected function getCallback($name)
    {
        $callback = $this->shortcodes[$name];

        if (is_string($callback))
        {
            $instance = null;

            if (isset($this->registeredClasses[$callback]))
            {
                $instance = $this->registeredClasses[$callback];
            }

            if (stripos($callback, '@') !== false)
            {
                $parts  = explode('@', $callback);
                $name   = $parts[0];
                $method = $parts[1];

                if (!$instance)
                {
                    $instance = new $name();

                    $this->registeredClasses[$callback] = $instance;
                }

                return [$instance, $method];
            }

            if (class_exists($callback))
            {
                if (!$instance)
                {
                    $instance = new $callback();

                    $this->registeredClasses[$callback] = $instance;
                }

                return [$instance, static::$defaultMethod];
            }
        }

        return $callback;
    }
}
