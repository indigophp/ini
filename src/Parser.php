<?php

namespace Indigo\Ini;

use Indigo\Ini\Exception\ParserException;

/**
 * Parses an INI string.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Parser
{
    /**
     * Parses an INI string.
     *
     * @param string $ini
     *
     * @return array
     */
    public function parse($ini)
    {
        if (!is_string($ini)) {
            throw new ParserException('Cannot parse non-string INI data');
        }

        $scannerMode = defined('INI_SCANNER_TYPED') ? INI_SCANNER_TYPED : INI_SCANNER_NORMAL;

        $parsedIni = @parse_ini_string($ini, true, $scannerMode);

        if (false === $parsedIni) {
            $e = error_get_last();
            throw new ParserException('Error during parsing INI: '.$e['message']);
        }

        // Prior to 5.6.1 we have to do some internal parsing as well
        if (false === defined('INI_SCANNER_TYPED')) {
            // We cannot use INI_SCANNER_RAW by default because it is buggy under PHP 5.3.14 and 5.4.4
            // http://3v4l.org/m24cT
            $rawIni = @parse_ini_string($ini, true, INI_SCANNER_RAW);

            $parsedIni = $this->normalize($parsedIni, $rawIni);
        }

        return $parsedIni;
    }

    /**
     * Normalizes INI and array values.
     *
     * @param $value
     * @param $rawValue
     *
     * @return bool|int|null|string|array
     */
    protected function normalize($value, $rawValue)
    {
        // Normalize array values
        if (is_array($value)) {
            foreach ($value as $i => &$subValue) {
                $subValue = $this->normalize($subValue, $rawValue[$i]);
            }

            return $value;
        }

        // Don't normalize non-string value
        if (!is_string($value)) {
            return $value;
        }

        // Normalize true boolean value
        if ($value === '1'
            && (strcasecmp($rawValue, 'true') === 0
                || strcasecmp($rawValue, 'yes') === 0
                || strcasecmp($rawValue, 'on') === 0)
        ) {
            return true;
        }

        // Normalize false boolean value
        if ($value === ''
            && (strcasecmp($rawValue, 'false') === 0
                || strcasecmp($rawValue, 'no') === 0
                || strcasecmp($rawValue, 'off') === 0)
        ) {
            return false;
        }

        // Normalize null value
        if ($value === '' && strcasecmp($rawValue, 'null') === 0) {
            return;
        }

        // Normalize numeric value
        if (is_numeric($value)) {
            $numericValue = $value + 0;

            // Typed ini parsing does not support negative doubles
            // https://3v4l.org/ujDo1
            if ((is_int($numericValue) && (int) $value === $numericValue)
                || (is_float($numericValue) && (float) $value === $numericValue && $numericValue > 0)
            ) {
                $value = $numericValue;
            }
        }

        return $value;
    }
}
