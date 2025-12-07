<?php
$MAIL_USERNAME = getenv('JANANI_MAIL_USERNAME') ?: '';
$MAIL_PASSWORD = preg_replace('/\s+/', '', getenv('JANANI_MAIL_APP_PASSWORD') ?: '');
$MAIL_FROM_NAME = getenv('JANANI_MAIL_FROM_NAME') ?: 'JANANI';
$MAIL_HOST = getenv('JANANI_MAIL_HOST') ?: 'smtp.gmail.com';
$MAIL_PORT = (int)(getenv('JANANI_MAIL_PORT') ?: 587);
$MAIL_SECURE = getenv('JANANI_MAIL_SECURE') ?: 'tls';
if (!$MAIL_USERNAME) { $MAIL_USERNAME = 'km208337@gmail.com'; }
if (!$MAIL_PASSWORD) { $MAIL_PASSWORD = 'vheqdgpmzcwftxbi'; }
