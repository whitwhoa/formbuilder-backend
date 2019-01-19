<?php require __DIR__.'/../vendor/autoload.php';

use whitwhoa\FormBuilderBackend\ValidateFormBuilder;
use whitwhoa\SimpleMySQL\MySQLCon;


//echo var_export($_POST, true);die;

$fv = new ValidateFormBuilder($_POST['fields']);
if($fv->hasErrors()){
    echo var_export($fv->getErrors(), true);die;
}



//header('Content-Type: application/json');
//echo json_encode(json_decode($_POST['fields']));