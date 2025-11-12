<?php
require_once __DIR__ . '/lib/auth.php';
start_secure_session();
create_session_for_user(3);
echo 'Session created for user 3';
?>