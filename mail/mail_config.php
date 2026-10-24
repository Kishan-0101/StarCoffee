<?php
return [
  'host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
  'port' => (int) (getenv('SMTP_PORT') ?: 587),
  'secure' => getenv('SMTP_SECURE') ?: 'tls',
  'user' => getenv('SMTP_USERNAME') ?: '',
  'pass' => getenv('SMTP_PASSWORD') ?: '',
  'from_email' => getenv('SMTP_FROM_EMAIL') ?: (getenv('SMTP_USERNAME') ?: ''),
  'from_name' => getenv('SMTP_FROM_NAME') ?: 'StarCoffee',
];