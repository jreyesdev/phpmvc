<?php
namespace App\Views\Auth;

use App\Core\Form\Form;

?>
<h1>Register</h1>

<?php 
    $form = Form::begin('/register','post');
    echo $form->field($model,'firstname');
    echo $form->field($model,'lastname');
    echo $form->field($model,'email','email');
    echo $form->field($model,'password','password');
    echo $form->field($model,'password_confirm','password');
?>
    <button type="submit">Submit</button>
<?= Form::end() ?>