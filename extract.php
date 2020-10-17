<?php
/**	Import articles from JSON table (from Joomla web site: MySQL => JSON => this PHP)

DB "joo". Table "d0_content". Fields:

id			Порядковый номер
title			Полное название
alias			Название для URL (латиницей, без пробелов)
introtext		Текст
state			1 = опубликовано
catid			Папка №
created			Дата, время
created_by_alias	Автор (если не пусто)
modified		Дата, время
metakey
metadesc
access			1 или ?
featured		0
language		'*', иногда ru_RU

Итак:

for (let a of articles) {
    o.d = catName[a.catid];		// folder
    o.f = a.id + '-' + a.alias;		// file name
    if (typeof a.state !== 'undefined' && a.state !== 0) o.d = '_' + a.state + '_' + o.d;
}
*/
// Joomla Categories in my particular site: "2" = uncategorised, "3" = not for contents, etc.
// 8 = texts, 9 = blog etc.
$catName = ['0', '1', '', '3', '4', '5', '6', '7', 'texts', 'blog', 'video', 'politics', 'teachers', 'qa', 'site', 'teachers', 'quote', 'ddm'];
$defaultAuthor = 'Постоянное Озарение';
$articles = file(__DIR__.'/articles.json');		// input file name
$dir = str_replace('\\', '/', __DIR__).'/zen-do';	// output directory for converted files

// Read the table file, string by string

// Parse JSON

// Put contents into folders & files

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Load JSON table, write multiple files</title>
  <meta name="description" content="Save dynamic web site files">
  <meta name="author" content="Chang Zhao (https://github.com/chang-zhao/)">
</head>
<body>
<table>
<?php
foreach($articles as $jsonString) {
    // The file should consist of strings, each with JSON object of an article
    // Delete other strings like '[' or ']' manually
    $a = json_decode($jsonString);
    // For files other than "published" (state == "1") - special folders (like 0/texts or 2/texts).
    if (isset($a->state) && (strcmp($a->state, '1') !== 0)) $b = $a->state;
    else $b = 'in';			// else - output folder root is "in" => ./in/texts/<filename>
    if (!$b) $b = '0';
    $currDir = $dir.'/'.$b;
    // Create folder if necessary
    if (!file_exists($currDir)) {	//  || is_file($currDir)
	if (!mkdir($currDir)) die('</table> Failed to create folder '.$currDir.'</body></html>');
    }
    // Folder name:
    if ($catName[$a->catid]) {
	$currDir .= '/'.$catName[$a->catid];		// e.g. __DIR__.'/in/texts'
	if (!file_exists($currDir)) {	//  || is_file($currDir)
	    if (!mkdir($currDir)) die('</table> Failed to create folder '.$currDir.'</body></html>');
	}
    }
    // File name:					// e.g. "2-about"
    $f = $a->id .'-'. $a->alias;
    echo '<tr><td>'.$currDir.'/'.$f.'</td><td>'.$a->title.'</td></tr>'.PHP_EOL;
    $fp = fopen($currDir.'/'.$f, 'w');
    // Articles with the default website language would have no language mark
    if ($a->language === '*' || $a->language === 'ru-RU') $a->language = '';
    $c = '<main><span id="language">'.$a->language.'</span> <span id="modified">v. '.substr($a->modified, 0, 10).'</span>'.PHP_EOL;
    if (!$a->created_by_alias) $a->created_by_alias = $defaultAuthor;
    $c .= '<h2 id="author">'.$a->created_by_alias.'</h2>'.PHP_EOL;
    $c .= '<h1 id="title">'.$a->title.'</h1>'.PHP_EOL;
    fwrite($fp, $c.$a->introtext.'</main>');
    fclose($fp);
}
?></table></body></html>
