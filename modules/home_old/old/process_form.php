<?
$email_check = '';
$return_json = '';

function isValidEmail($email){
    return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
}

if(isValidEmail($_POST['email_ajax']) || isValidEmail($_POST['email_post'])) {
   $email_check = 'valid';
}
else {
    $email_check = 'invalid';
}

$return_json = '{"email_check2":"' . $email_check . '",';

if (isset($_POST['email_ajax'])){
    $return_json = $return_json . '"name":"' . $_POST['name_ajax'] . '",';
    $return_json = $return_json . '"email":"' . $_POST['email_ajax'] . '"}';
} else {
    $return_json = $return_json . '"name":"' . $_POST['name_post'] . '",';
    $return_json = $return_json . '"email":"' . $_POST['email_post'] . '"}';
}

echo $return_json;
?>