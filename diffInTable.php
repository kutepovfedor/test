<?php
$diff = [
    "c1" => [
        "upd" => [
            "aa" => [
                "old" => "AA",
                "new" => "11",
            ],
            "ab" => [
                "old" => "AB",
            ],
            "af" => [
                "new" => "AE",
            ],
        ],
    ],
    "c2" => [
        "upd" => [
            "ba" => ["old" => "BA",],
            "bb" => ["old" => "BB",],
            "bc" => ["old" => "BC",],
            "bd" => ["old" => "BD",],
            "be" => ["old" => "BE",],
            "bf" => ["new" => "26",],
            "bg" => [
                "new" => [
                    "bga" => "221",
                    "bgb" => "222",
                    "bgc" => [
                        "bgca" => "2231",
                        "bgcb" => "2232",
                        "bgcv" => "2233",
                    ],
                ],
            ],
        ],
    ],
    "c3" => [
        "old" => [
            "ca" => "CA",
            "cb" => "CB",
            "cc" => "CC",
            "cd" => "CD",
            "ce" => "CE",
        ],
    ],
    "c4" => ["new" => ["da" => "41", "db" => "42", "dc" => "43", "dd" => "44", "de" => "45",],],
];

function diffInTable($diff, $path = "")
{
    $sep = "#";
    //    $date = '10.01.18';
    $table = [];
    foreach ($diff as $k1 => $v1) {
        if (is_array($v1)) {
            $skep_new = false;
            foreach ($v1 as $k2 => $v2) {
                if ($k2 == "old" || $k2 == "new") {
                    if ($k2 == "new" && $skep_new) { //для избужания дублей если есть и neew и old
                        continue;
                    }
                    $skep_new = true;
                    if (is_array($v1['new']) or is_array($v1['old'])) {
                        $table = array_merge($table, addArrayToHistory($v1, $path. $sep . $k1));
                    } else {
                        $table[] = [
                            ///$date,
                            "kay" => $path . $sep . $k1,
                            "old" => $v1['old'],
                            "new" => $v1['new'],
                        ];
                    }
                } else {
                    $table = array_merge($table, diffInTable($v2, $path . $sep . $k1));
                }

            }
        }
    }
    return $table;
}

function addArrayToHistory($array, $path)
{
    $table = [];

    if (is_array($array) && key_exists('new', $array) && is_array($array['new'])) {
        foreach ($array['new'] as $k => $v) {
            $table = array_merge($table, addArrayToHistory($v, $path . "#" . $k));
        }
    } elseif(is_array($array) && key_exists('old', $array) && is_array($array['old'])) {
        foreach ($array['old'] as $k => $v) {
            $table = array_merge($table, addArrayToHistory($v, $path . "#" . $k));
        }
    } elseif (is_array($array)) {
        foreach ($array as $k => $v) {
            $table = array_merge($table, addArrayToHistory($v, $path . "#" . $k));
        }
    } else {
        $table [] = [
            ///$date,
            "kay" => $path,
            "old" => null,
            "new" => $array,
        ];
    }
    return $table;
}

var_dump(diffInTable($diff));
