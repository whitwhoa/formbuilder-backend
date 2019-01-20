<?php require __DIR__.'/../vendor/autoload.php';

use whitwhoa\FormBuilderBackend\FormBuilderValidator;
use whitwhoa\FormBuilderBackend\PrepareJSON;
use whitwhoa\SimpleMySQL\MySQLCon;



$fv = new FormBuilderValidator($_POST['fields']);
if($fv->hasErrors()){
    http_response_code(422);
    $response = [
        'status' => 422,
        'errors' => $fv->getErrors()
    ];
} else {
    $pj = new PrepareJSON($_POST['fields']);
    echo var_export($pj->getFields(), true);die;
    $response = [
        'status'    => 200
    ];
}

header('Content-Type: application/json');
echo json_encode($response);