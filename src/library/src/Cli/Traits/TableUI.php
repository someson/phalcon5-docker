<?php

namespace Library\Cli\Traits;

use Library\Cli\Output;

trait TableUI
{
    public static function mb_str_pad($str, $pad_len, $pad_str = ' ', $dir = STR_PAD_RIGHT, $encoding = null): string
    {
        $encoding = $encoding ?? mb_internal_encoding();
        $padBefore = $dir === STR_PAD_BOTH || $dir === STR_PAD_LEFT;
        $padAfter = $dir === STR_PAD_BOTH || $dir === STR_PAD_RIGHT;
        $pad_len -= mb_strlen($str, $encoding);
        $targetLen = $padBefore && $padAfter ? $pad_len / 2 : $pad_len;
        $strToRepeatLen = mb_strlen($pad_str, $encoding);
        $repeatTimes = ceil($targetLen / $strToRepeatLen);
        $repeatedString = str_repeat($pad_str, max(0, $repeatTimes));
        $before = $padBefore ? mb_substr($repeatedString, 0, floor($targetLen), $encoding) : '';
        $after = $padAfter ? mb_substr($repeatedString, 0, ceil($targetLen), $encoding) : '';
        return $before . $str . $after;
    }

    public function renderTable(array $entries)
    {
        if (! \count($entries)) {
            return false;
        }
        $pads = $head = [];
        $divides = 0;
        /** @var array $example */
        $example = $entries[0];
        foreach ($example as $key => $item) {
            $pads[$key] = mb_strlen($key) + 2;
            foreach ($entries as $entry) {
                $len = mb_strlen($entry[$key] ?? 'NULL');
                if ($len >= $pads[$key]) {
                    $pads[$key] = $len + 1;
                }
            }
            $column = str_pad($key, $pads[$key]);
            $divides += mb_strlen($column);
            $head[] = $column;
        }
        $divider = str_repeat('-', $divides - 1);
        $header = implode('', $head);

        Output::text($divider);
        Output::text($header);
        Output::text($divider);

        /** @var array $entry */
        foreach ($entries as $entry) {
            $row = '';
            foreach ($entry as $key => $value) {
                $row.= self::mb_str_pad($value ?? 'NULL', $pads[$key]);
            }
            Output::text($row);
        }
        Output::text($divider);
    }
}
