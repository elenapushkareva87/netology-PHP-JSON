<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (empty($argv[1])) {
 echo "Ошибка! Укажите название страны для получения информации";
 exit;
}

//Получаем данные с внешнего ресурса
$filecontent = file_get_contents('https://www.googleapis.com/books/v1/volumes?q='.urlencode($argv[1]));
$books = json_decode($filecontent, true);


$report = array();

//Считаем, сколько всего книг
$f = count($books['items']['0']);


// Создаем массив с нужными значениями id, title, authors
$row = 0;
while ($row <= $f) {
    $report[$row] = [
        'id' => !empty($books['items'][$row]['id']) ? $books['items'][$row]['id'] : '',
        'title' => !empty($books['items'][$row]['volumeInfo']['title']) ? $books['items'][$row]['volumeInfo']['title'] : '',
        'authors' => !empty($books['items'][$row]['volumeInfo']['authors']) ? implode(',',$books['items'][$row]['volumeInfo']['authors']) : ''
    ];
    $row++;
}

//Открываем / создаем файл для записи books.csv 
$resource = fopen(__DIR__ . DIRECTORY_SEPARATOR . 'books.csv', 'w+');

// Записываем данные
foreach ($report as $line) {
	fputcsv($resource, $line);
	# code...
}


// Выводим файл для проверки
$fp = file(__DIR__ . DIRECTORY_SEPARATOR . 'books.csv');
print_r($fp);

if (!empty($resource)) fclose($resource);

?>