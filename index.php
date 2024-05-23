<?php 
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getPartsFromFullname ($fullName) {
    $workpiece = ['surname','name','patronomyc'];  
    $fromFullName = explode(' ', $fullName);

return array_combine($workpiece, $fromFullName);
}

function getFullnameFromParts($surname = 'Иванов', $name = 'Иван', $patronomyc = 'Иванович') {
return $surname.' '.$name.' '.$patronomyc;
}

function getShortName($fullName) {
    $division = getPartsFromFullname ($fullName);
    return $division['name'].' '.mb_substr($division['surname'], 0, 1).'.';
}

function getGenderFromName($fullName = 'Иванов Иван Иванович') {
    $partsName = getPartsFromFullname($fullName);
    $count = 0;
    if (mb_substr($partsName['surname'], -1) == 'в'){
        $count++;
    } elseif (mb_substr($partsName['surname'], -2) == 'ва'){
        $count--;  
    }
    if (mb_substr($partsName['name'], -1) == 'н'){
        $count++;
    } elseif (mb_substr($partsName['name'], -1) == 'а'){
        $count--;  
    }
    if (mb_substr($partsName['patronomyc'], -2) == 'ич'){
        $count++;
    } elseif (mb_substr($partsName['patronomyc'], -3) == 'вна'){
        $count--;   
    }
    if ($count > 0) {
        return 1;
    } elseif ($count < 0) {
        return -1;
    } else {
        return 0;
    }
}

function getGenderDescription ($namesArray) {

$maleCount = count(array_filter($namesArray, function($array){
    $simile = getGenderFromName($array['fullname']);
    if ($simile == 1){
        return $array['fullname'];
    }
}));

$femaleCount = count(array_filter($namesArray, function($array){
    $simile = getGenderFromName($array['fullname']);
    if ($simile == -1){
        return $array['fullname'];
    }
}));

$xxxCount = count(array_filter($namesArray, function($array){
    $simile = getGenderFromName($array['fullname']);
    if ($simile == 0){
        return $array['fullname'];
    }
}));

$fullArrayCount = count($namesArray);

header("Content-Type: text/plain; charset=utf-8");

echo 'Гендарный состав аудитории:'."\n";
echo '-------------------------------------'."\n";
echo 'Мужчины - ';
print_r(round($maleCount/$fullArrayCount*100, 1)."\n");
echo 'Женщины - ';
print_r(round($femaleCount/$fullArrayCount*100, 1)."\n");
echo 'Не удалось определить - ';
print_r(round($xxxCount/$fullArrayCount*100, 1)."\n\n\n");
}

getGenderDescription($example_persons_array);

function opposite ($array, $gender) {
    $chosen = $array[array_rand($array, 1)]['fullname'];
    $genderChosen = getGenderFromName($chosen);
    if ($gender == $genderChosen || $genderChosen==0){
        opposite ($array, $gender);
    }else{ echo getShortName($chosen);
    } 
    }

function getPerfectPartner($surname, $name, $patronomyc, $originalArray) {
    $surname = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
    $name = mb_convert_case($name, MB_CASE_TITLE_SIMPLE);
    $patronomyc = mb_convert_case($patronomyc, MB_CASE_TITLE_SIMPLE);
    $fullName = getFullnameFromParts($surname, $name, $patronomyc);
    echo getShortName($fullName).' + ';
    $gender = getGenderFromName($fullName);
    opposite ($originalArray, $gender);
    echo " =\n".' Идеально на '.rand(50, 100).'% ';
}

getPerfectPartner('Хохлов','Юра','Иванович',$example_persons_array);
?>
