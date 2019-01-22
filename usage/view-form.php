<?php require __DIR__.'/../vendor/autoload.php';

use whitwhoa\FormBuilderBackend\FormRenderer;
use whitwhoa\FormBuilderBackend\SubmissionValidator;
use whitwhoa\SimpleMySQL\MySQLCon;

// Insure form id is present in query string
if(!isset($_GET['f'])){
    echo 'Form id must be passed as get parameter `f`';
    die;
}
$db = new MySQLCon(['localhost', 'root', 'root', 'formbuilder_backend'], true);
$f = $db->query("SELECT * FROM forms WHERE id = ?", [$_GET['f']])->singleAsObj()->exec();
if(!$f){
    echo 'Form id does not exist';
    die;
}

/**
 * If POST:
 *  > validate user $_POST data
 *      > if validation fails re-generate form and display to user
 *      > if validation passes redirect to complete.php and show success message
 */
if($_POST){

    $fsv = new SubmissionValidator($f->form_json, $_POST);
    if($fsv->passes()){
        // Save form input HERE
        $db->query("INSERT INTO submissions(form_id, `data`) VALUES(?,?)", [$_POST['formId'], json_encode($_POST)])->exec();
        header('Location: success.html');
        exit;
    }
    //echo var_export($fsv->getErrors(), true);die;
    $rf = new FormRenderer($f->form_json, $_POST, $fsv->getErrors());
    $formElements = $rf->getRenderedHtml();

}
// Not POST so we simply need to retrieve the form json data from our persistence layer, render, and display
else {

    $rf = new FormRenderer($f->form_json);
    $formElements = $rf->getRenderedHtml();

}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Render Form</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</head>
<body>


<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-body">
                    <form id="myForm" action="" method="POST">
                        <?php echo $formElements; ?>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<!--
<form action="test.php" method="POST">
    <div class="fb-checkbox-group form-group field-checkbox-group-1547960452522-preview">
    <label for="checkbox-group-1547960452522-preview" class="fb-checkbox-group-label">Checkbox Group</label>
    <div class="checkbox-group"><div class="fb-checkbox"><input class="" name="checkbox-group-1547960452522-preview[]"
    id="checkbox-group-1547960452522-preview-0" value="1" type="checkbox"><label for="checkbox-group-1547960452522-preview-0">
    One</label></div><div class="fb-checkbox"><input class="" name="checkbox-group-1547960452522-preview[]"
    id="checkbox-group-1547960452522-preview-1" value="2" type="checkbox"><label for="checkbox-group-1547960452522-preview-1">Two</label>
    </div><div class="fb-checkbox"><input class="" name="checkbox-group-1547960452522-preview[]"
    id="checkbox-group-1547960452522-preview-2" value="3" type="checkbox">
    <label for="checkbox-group-1547960452522-preview-2">Three</label></div></div></div>
    <button type="submit">Yep</button>
</form>
-->





</body>
</html>