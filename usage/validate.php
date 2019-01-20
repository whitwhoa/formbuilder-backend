<?php require __DIR__.'/../vendor/autoload.php';

use whitwhoa\FormBuilderBackend\ValidateFormBuilder;
use whitwhoa\SimpleMySQL\MySQLCon;


$fv = new ValidateFormBuilder($_POST['fields']);
if($fv->hasErrors()){
    http_response_code(422);
    $response = [
        'status' => 422,
        'errors' => $fv->getErrors()
    ];
} else {
    $response = [
        'status'    => 200
    ];
}

header('Content-Type: application/json');
echo json_encode($response);