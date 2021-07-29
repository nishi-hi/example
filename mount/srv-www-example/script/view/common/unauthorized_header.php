<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@500&display=swap" rel="stylesheet">
<link href="<?php echo (is_null(CONTEXT_PREFIX)) ? '' : CONTEXT_PREFIX; ?>/styles/base.css" rel="stylesheet">
<title><?php echo $title; ?></title>
</head>
<body class="unauthorized-grid height-100vh margin-t0-r0-b0-l0">
  <header class="bg-black white">
    <div class="width-max margin-t0-rauto-b0-lauto">
      <h1><a href="<?php if (is_null(CONTEXT_PREFIX)) { echo '/'; } else { echo CONTEXT_PREFIX; } ?>" class="text-decoration-none white">Example</a></h1>
    </div>
  </header>
