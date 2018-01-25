<?php
require 'OneHashSaltedPassword.class.php';

$a = new OneHashSaltedPassword('testkey');

$pw = $a->GenerateSaltedPassword('test'); //we generating a new hash for the password "test"
echo $pw . '<hr>';
var_dump($a->CheckPassword('test',$pw)); //we pretend someone typed in the correct passoword. Retuns bool(ture)
var_dump($a->CheckPassword('test2',$pw)); //we pretend someone typed in the wrong password. Returns bool(false)
?>