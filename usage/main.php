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
    $db = new MySQLCon(['localhost', 'root', 'root', 'formbuilder_backend'], true);
    $id = $db->query("INSERT INTO forms(id, form_json) VALUES(?,?)", [((function() use($pj){
        foreach($pj->getFields() as $e){
            if($e->type === "meta"){
                return $e->data->id;
            }
        }
    })()), $pj->getFieldsAsJSON()])->exec();
    $response = [
        'status'    => 200
    ];
}

header('Content-Type: application/json');
echo json_encode($response);