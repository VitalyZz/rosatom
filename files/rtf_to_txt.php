<?php/*
$dir    = '../data';
$files1 = scandir($dir);
for($i = 2; $i < count($files1) - 1; $i++){
    $fp = fopen((mb_substr($files1[$i], 0, -4) . '.txt'),'w');
    fwrite($fp, rtf2text('../data/'.$files1[$i]));
    fclose($fp);
}*/

function rtf_isPlainText($s) {
$failAt = array("*", "fonttbl", "colortbl", "datastore", "themedata", "stylesheet", "info", "picw", "pich");
for ($i = 0; $i < count($failAt); $i++)
if (!empty($s[$failAt[$i]])) return false;
return true;
}

function from_macRoman($c) {
$table = array(
0x83 => 0x00c9, 0x84 => 0x00d1, 0x87 => 0x00e1, 0x8e => 0x00e9, 0x92 => 0x00ed,
0x96 => 0x00f1, 0x97 => 0x00f3, 0x9c => 0x00fa, 0xe7 => 0x00c1, 0xea => 0x00cd,
0xee => 0x00d3, 0xf2 => 0x00da
);
if (isset($table[$c]))
$c = "&#x".sprintf("%04x", $table[$c]).";";
return $c;
}

function rtf2text($filename) {
$text = file_get_contents($filename);
if (!strlen($text))
return "";


if (strlen($text) > 1024 * 1024) {
$text = preg_replace("#[\r\n]#", "", $text);
$text = preg_replace("#[0-9a-f]{128,}#is", "", $text);
}

# For Unicode escaping
$text = str_replace("\\'3f", "?", $text);
$text = str_replace("\\'3F", "?", $text);

$document = "";
$stack = array();
$j = -1;

$fonts = array();

for ($i = 0, $len = strlen($text); $i < $len; $i++) {
$c = $text[$i];

switch ($c) {
case "\\":
$nc = $text[$i + 1];

if ($nc == '\\' && rtf_isPlainText($stack[$j])) $document .= '\\';
elseif ($nc == '~' && rtf_isPlainText($stack[$j])) $document .= ' ';
elseif ($nc == '_' && rtf_isPlainText($stack[$j])) $document .= '-';
elseif ($nc == '*') $stack[$j]["*"] = true;
elseif ($nc == "'") {
$hex = substr($text, $i + 2, 2);
if (rtf_isPlainText($stack[$j])) {
if (!empty($stack[$j]["mac"]) || @$fonts[$stack[$j]["f"]] == 77)
$document .= from_macRoman(hexdec($hex));
elseif (@$stack[$j]["ansicpg"] == "1251" || @$stack[$j]["lang"] == "1029")
$document .= chr(hexdec($hex));
else
$document .= "&#".hexdec($hex).";";
}
$i += 2;
} elseif ($nc >= 'a' && $nc <= 'z' || $nc >= 'A' && $nc <= 'Z') {
$word = "";
$param = null;

for ($k = $i + 1, $m = 0; $k < strlen($text); $k++, $m++) {
$nc = $text[$k];
if ($nc >= 'a' && $nc <= 'z' || $nc >= 'A' && $nc <= 'Z') {
if (empty($param))
$word .= $nc;
else
break;
} elseif ($nc >= '0' && $nc <= '9')
$param .= $nc;
elseif ($nc == '-') {
if (empty($param))
$param .= $nc;
else
break;
} else
break;
}
$i += $m - 1;

$toText = "";
switch (strtolower($word)) {
case "u":
$toText .= html_entity_decode("&#x".sprintf("%04x", $param).";");
$ucDelta = !empty($stack[$j]["uc"]) ? @$stack[$j]["uc"] : 1;
if ($ucDelta > 0)
$i += $ucDelta;
break;
case "par": case "page": case "column": case "line": case "lbr":
$toText .= "\n";
break;
case "emspace": case "enspace": case "qmspace":
$toText .= " ";
break;
case "tab": $toText .= "\t"; break;
case "chdate": $toText .= date("m.d.Y"); break;
case "chdpl": $toText .= date("l, j F Y"); break;
case "chdpa": $toText .= date("D, j M Y"); break;
case "chtime": $toText .= date("H:i:s"); break;
case "emdash": $toText .= html_entity_decode("&mdash;"); break;
case "endash": $toText .= html_entity_decode("&ndash;"); break;
case "bullet": $toText .= html_entity_decode("&#149;"); break;
case "lquote": $toText .= html_entity_decode("&lsquo;"); break;
case "rquote": $toText .= html_entity_decode("&rsquo;"); break;
case "ldblquote": $toText .= html_entity_decode("&laquo;"); break;
case "rdblquote": $toText .= html_entity_decode("&raquo;"); break;

case "bin":
$i += $param;
break;

case "fcharset":
$fonts[@$stack[$j]["f"]] = $param;
break;

default:
$stack[$j][strtolower($word)] = empty($param) ? true : $param;
break;
}
if (rtf_isPlainText($stack[$j]))
$document .= $toText;
} else $document .= " ";

$i++;
break;
case "{":
if ($j == -1)
$stack[++$j] = array();
else
array_push($stack, $stack[$j++]);
break;
case "}":
array_pop($stack);
$j--;
break;
case "\0": case "\r": case "\f": case "\b": case "\t": break;
case "\n":
$document .= " ";
break;
default:
if (rtf_isPlainText($stack[$j]))
$document .= $c;
break;
}
}
return html_entity_decode(iconv("windows-1251", "utf-8", $document), ENT_QUOTES, "UTF-8");
}