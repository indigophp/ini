<?php

namespace Indigo\Ini;

use Indigo\Ini\Exception\RendererException;

/**
 * Renders an INI array.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Renderer
{
    /**
     * Constants determining how the library should handle array values
     */
    const ARRAY_MODE_ARRAY = 1;
    const ARRAY_MODE_CONCAT = 2;

    /**
     * Constants determining how the library should handle boolean values
     */
    const BOOLEAN_MODE_INTEGER = 1;
    const BOOLEAN_MODE_BOOL_STRING = 2;
    const BOOLEAN_MODE_STRING = 3;

    /**
     * @var int
     */
    protected $arrayMode = self::ARRAY_MODE_ARRAY;

    /**
     * @var int
     */
    protected $booleanMode = self::BOOLEAN_MODE_INTEGER;

    /**
     * @param int $arrayMode
     * @param int $booleanMode
     */
    public function __construct($arrayMode = self::ARRAY_MODE_ARRAY, $booleanMode = self::BOOLEAN_MODE_INTEGER)
    {
        $this->arrayMode = $arrayMode;
        $this->booleanMode = $booleanMode;
    }

    /**
     * Renders an INI configuration.
     *
     * @param array $ini
     *
     * @return string
     */
    public function render(array $ini)
    {
        $output = [];

        foreach ($ini as $sectionName => $section) {
            $sectionOutput = [];

            if (!is_array($section)) {
                throw new RendererException('The section must contain an array of key-value pairs');
            }

            // Values without keys are stored in this temporary array
            $sectionIni = [];

            foreach ($section as $key => $value) {
                if (is_numeric($key)) {
                    if (!is_array($value)) {
                        $value = [$value];
                    }

                    $sectionIni = array_merge($sectionIni, $value);
                    continue;
                }

                $sectionOutput = array_merge($sectionOutput, $this->renderKeyValuePair($key, $value));
            }

            if (count($sectionIni) > 0) {
                $sectionOutput = array_merge($this->renderKeyValuePair($sectionName, $sectionIni), $sectionOutput);
            }

            array_unshift($sectionOutput, sprintf('[%s]', $sectionName));

            // Write a linefeed after sections
            $sectionOutput[] = "\n";

            $output = array_merge($output, $sectionOutput);
        }

        return implode("\n", $output);
    }

    /**
     * Renders a key-value pair.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return array
     */
    protected function renderKeyValuePair($key, $value)
    {
        $output = [];
        $value = $this->normalizeValue($value);

        if (is_array($value)) {
            foreach ($value as $v) {
                $output[] = sprintf('%s[] = %s', $key, $v);
            }
        } else {
            $output[] = sprintf('%s = %s', $key, $value);
        }

        return $output;
    }

    /**
     * Normalize value to valid INI format.
     *
     * @param mixed $value
     *
     * @return string
     */
    protected function normalizeValue($value)
    {
        if (is_array($value)) {
            $value = $this->normalizeArray($value);
        } elseif (is_bool($value)) {
            $value = $this->normalizeBoolean($value);
        } elseif (is_null($value)) {
            $value = 'null';
        }

        if (is_string($value)) {
            $value = sprintf('"%s"', $value);
        }

        return $value;
    }

    /**
     * Normalizes arrays.
     *
     * @param array $value
     *
     * @return array|string
     */
    protected function normalizeArray($value)
    {
        switch ($this->arrayMode) {
            case self::ARRAY_MODE_CONCAT:
                foreach ($value as &$v) {
                    $v = trim($this->normalizeValue($v), '"'); // We don't want string normalization here
                }

                return implode(",", $value);

                break;
            case self::ARRAY_MODE_ARRAY:
            default:
                foreach ($value as &$v) {
                    if (is_array($v)) {
                        throw new RendererException('Multi-dimensional arrays are not supported by this array mode');
                    }

                    $v = $this->normalizeValue($v);
                }

                return $value;

                break;
        }
    }

    /**
     * Normalizes a boolean value;
     *
     * @param bool $value
     *
     * @return int|string
     */
    protected function normalizeBoolean($value)
    {
        switch ($this->booleanMode) {
            case self::BOOLEAN_MODE_BOOL_STRING:
                return $value === true ? 'true' : 'false';

                break;

            case self::BOOLEAN_MODE_STRING:
                return $value === true ? 'On' : 'Off';

                break;

            case self::BOOLEAN_MODE_INTEGER:
            default:
                return (int) $value;

                break;
        }
    }
}
