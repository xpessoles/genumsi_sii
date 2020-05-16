<?php
include("connexionbdd.php");

$file_name = 'exports_questions_' . date('y-m-d') . '.sql';
$file_handle = fopen($file_name, 'w+');


$create_query = $bdd->prepare("SHOW CREATE TABLE `questions`");
$create_query->execute();
$create_query = $create_query->fetch();

fwrite($file_handle, $create_query['Create Table'] . ";\n\n");
fwrite($file_handle, "/*!40000 ALTER TABLE `questions` DISABLE KEYS */;\n");
fwrite($file_handle, "REPLACE INTO questions VALUES\n");
$select_query = "SELECT * FROM questions";
$statement = $bdd->prepare($select_query);
$statement->execute();

$total_output = "";

while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    $row['num_util'] = 1;
    foreach ($row as $key => $value) {
        $row[$key] = str_replace('"', "'", $row[$key]);
        $row[$key] = str_replace("\r\n", "<br>", $row[$key]);
        $row[$key] = str_replace(chr(9), "    ", $row[$key]);
    }
    $output = '("' . implode('","', $row) . "\"),\n";
    $output = str_replace('""', "NULL", $output);
    $total_output .= $output;
}
$total_output = substr($total_output, 0, -2);
$total_output .= ";\n";
fwrite($file_handle, $total_output);
fwrite($file_handle, "/*!40000 ALTER TABLE `questions` ENABLE KEYS */;\n");


fclose($file_handle);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . basename($file_name));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file_name));
ob_clean();
flush();
readfile($file_name);
unlink($file_name);

$file_name = 'exports_questions_' . date('y-m-d') . '.sql';
$file_handle = fopen($file_name, 'w+');
fwrite($file_handle, $output);
fclose($file_handle);
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . basename($file_name));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file_name));
ob_clean();
flush();
readfile($file_name);
unlink($file_name);
