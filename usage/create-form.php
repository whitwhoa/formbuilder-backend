<?php require __DIR__.'/../vendor/autoload.php';

use whitwhoa\FormBuilderBackend\ElementValidator;
use whitwhoa\FormBuilderBackend\ElementPreper;
use whitwhoa\SimpleMySQL\MySQLCon;


$fv = new ElementValidator($_POST['fields']);
if($fv->hasErrors()){
    http_response_code(422);
    $response = [
        'status' => 422,
        'errors' => $fv->getErrors()
    ];
} else {
    $pj = new ElementPreper($_POST['fields']);
    $db = new MySQLCon(['localhost', 'root', 'root', 'formbuilder_backend'], true);
    $id = $db->query("INSERT INTO forms(id, form_json) VALUES(?,?)", [((function() use($pj){
        foreach($pj->getFields() as $e){
            if($e->type === "meta"){
                return $e->data->id;
            }
        }
    })()), $pj->getFieldsAsJSON()])->exec();
    $response = [
        'status'    => 200,
        'formId'    => $id
    ];
}

header('Content-Type: application/json');
echo json_encode($response);