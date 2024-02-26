<?php
// Необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Замерим скорость работы
$time_start = microtime(true);

// Пустой массив для данных
$json = array();

// Установим временную зону для правильного отображения даты и времени
date_default_timezone_set('Europe/Moscow');

//Подключимся к базе данных
try {
    $conn = new PDO('mysql:host=localhost;dbname=marmalade', "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    $json['error'] = 'ERROR: ' . $e->getMessage();
    print(json_encode($json));
    exit;
}

// Наш запрос
$query = "SELECT * FROM `katalog`"; 

// // Добавим фильтры к запросу
// if(count($filter)>0){
//     // Если есть фильтры, то добавим WHERE
//     $query .= "WHERE ";
//     // Счетчик количество добавленных фильтров
//     $add_count_filter = 0; 
//     // Если 
//     foreach($filter as $key=>$value){
//         // Если уже не первый фильтр добаляем, то нужно добавить AND
//         if($add_count_filter>0){$query .= " AND ";}

//         // Проверим тип переменной
//         if(is_int($value)){ // Если это число, добавляем с помощью равно
//             $query .= "`{$key}` = {$value}";
//         }elseif(is_string($value)){// Если это строка, то добаляем сраненение LIKE
//             $query .= "`{$key}` LIKE '{$value}'";
//         }

//         // Добавим счетчик количество фильтров
//         $add_count_filter++;
//     }
// }

// $json['query'] = $query; // Покажем нам какой запрос был сформирован

// Выполним запрос
$Q = $conn->prepare($query);
$Q->execute();

// Выгрузим все строки полученные в запросе
$json = array();
$result = $Q->fetchAll();
foreach($result as $row){
    $json[] = array('id'=>$row['id'], 'name'=>$row['name'], 'opi'=>$row['opi'], 'img'=>$row['img'], 'price'=>$row['price'], 'sale'=>$row['sale'], 'weight'=>$row['weight'], 'sum'=>$row['sum']);
}

// $json['time_execution'] = round(microtime(true) - $time_start,4); // Время выполенения скрипта
// $json['time'] = time(); // Текущее время сервера

print(json_encode($json));
?>