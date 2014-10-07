<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;

class Driver
{
    const EOL = "\x0a"; // "\n" char
    const DELIMITER = "\x09"; // "\t" char
    const NULL = "\0";

    private static $encodeMap = array(
        // A character in the range [0x00 - 0x0f] is prefixed by 0x01 and shifted by 0x40
        "\x00" => "\x01\x40",
        "\x01" => "\x01\x41",
        "\x02" => "\x01\x42",
        "\x03" => "\x01\x43",
        "\x04" => "\x01\x44",
        "\x05" => "\x01\x45",
        "\x06" => "\x01\x46",
        "\x07" => "\x01\x47",
        "\x08" => "\x01\x48",
        "\x09" => "\x01\x49",
        "\x0A" => "\x01\x4A",
        "\x0B" => "\x01\x4B",
        "\x0C" => "\x01\x4C",
        "\x0D" => "\x01\x4D",
        "\x0E" => "\x01\x4E",
        "\x0F" => "\x01\x4F"
    );

    private static $decodeMap = array(
        self::NULL => null,
        "\x01\x40" => "\x00",
        "\x01\x41" => "\x01",
        "\x01\x42" => "\x02",
        "\x01\x43" => "\x03",
        "\x01\x44" => "\x04",
        "\x01\x45" => "\x05",
        "\x01\x46" => "\x06",
        "\x01\x47" => "\x07",
        "\x01\x48" => "\x08",
        "\x01\x49" => "\x09",
        "\x01\x4A" => "\x0A",
        "\x01\x4B" => "\x0B",
        "\x01\x4C" => "\x0C",
        "\x01\x4D" => "\x0D",
        "\x01\x4E" => "\x0E",
        "\x01\x4F" => "\x0F"
    );

    /**
     * @param $data
     *
     * @return string
     */
    public static function prepareSendDataStatic($data)
    {
        $encodedData = array_map('self::encodeData', $data);

        return implode(self::DELIMITER, $encodedData);
    }

    /**
     * @param string $data
     *
     * @return array
     */
    public static function prepareReceiveDataStatic($data)
    {
        $dataList = explode(self::DELIMITER, $data);

        return array_map('self::decodeData', $dataList);
    }

    /**
     * @param string $data
     *
     * @return string
     */
    public static function decodeData($data)
    {
        return strtr($data, self::$decodeMap);
    }

    /**
     * @param string $data
     *
     * @return string
     */
    public static function encodeData($data)
    {
        // NULL is expressed as a single NUL(0x00).
        // null => "\x00",
        if ($data === null) {
            return "\x00";
        }

        return strtr($data, self::$encodeMap);
    }
}