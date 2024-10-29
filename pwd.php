<?php

$plainPassword = 'password';
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT, []);

if (password_verify($plainPassword, $hashedPassword)) {
  echo 'Mot de passe valide';
} else {
  echo 'Mot de passe invalide';
}

?>