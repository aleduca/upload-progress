<?php

$uploadDirectory = __DIR__ . '/uploads';

// verifica se diretorio existe, se não, cria.
if (!is_dir($uploadDirectory)) {
  mkdir($uploadDirectory, 0777, true);
}


// se escolheu um arquivo
if (!isset($_FILES['file'])) {
  http_response_code(400);
  echo '🔴Escolha um arquivo';
  return;
}

$file = $_FILES['file'];
$fileName = $file['name'];
$fileTmpName = $file['tmp_name'];


// verificar a extensão
$extension = pathinfo($fileName, PATHINFO_EXTENSION);
if (!in_array($extension, ['jpeg', 'jpg', 'png', 'pdf'])) {
  http_response_code(400);
  echo '🔴Extensão não aceita';
  return;
}

// verifica tamanho do arquivo
if ($file['size'] > 40 * 1024 * 1024) {
  http_response_code(400);
  echo '🔴Arquivo muito grande';
  return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $target = $uploadDirectory . DIRECTORY_SEPARATOR . $fileName;
  move_uploaded_file($fileTmpName, $target);
  http_response_code(200);
  echo '🟢Upload feito com sucesso';
  return;
}
