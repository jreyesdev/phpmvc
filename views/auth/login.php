<?php
namespace App\Views\Auth;

use App\Core\Form\Form;

?>
<h1>Login</h1>

<?php 
    $form = Form::begin('/login','post');
    echo $form->field($model,'email','email');
    echo $form->field($model,'password','password');
?>
    <button type="submit">Submit</button>
<?= Form::end() ?>