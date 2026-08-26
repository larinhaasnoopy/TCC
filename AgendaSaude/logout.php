<?php
session_start();
session_unset();
session_destroy();

header("Location: login.php?sucesso=" . urlencode("Sessão encerrada com sucesso."));
exit;
?>
