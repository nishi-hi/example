<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@500&display=swap" rel="stylesheet">
<link href="<?php echo URL['HOME']; ?>styles/base.css" rel="stylesheet">
<title><?php echo $title; ?></title>
</head>
<?php
$menu_top = new Anchor();
$menu_top->text = 'Top';
$menu_top->href = URL['HOME'];
$menu_run_job = new Anchor();
$menu_run_job->text = 'Run&nbsp;Job';
$menu_run_job->href = URL['JOB_HOME'];
$menu_list_job = new Anchor();
$menu_list_job->text = 'List&nbsp;Job';
$menu_list_job->href = URL['JOB_LIST'];
$menu_logout = new Anchor();
$menu_logout->text = 'Logout';
$menu_logout->href = ACTION_URL['LOGOUT'];
$mobile_menus = [ $menu_top, $menu_run_job, $menu_list_job, $menu_logout ];
$desktop_menus = [ $menu_top, $menu_run_job, $menu_list_job ];
if ($this->url === URL['HOME']){
  $mobile_menus[0]->color = 'black';  $desktop_menus[0]->color = 'black';
} else if ($this->url === URL['JOB_HOME']) {
  $mobile_menus[1]->color = 'black';  $desktop_menus[1]->color = 'black';
} else if ($this->url === URL['JOB_LIST']) {
  $mobile_menus[2]->color = 'black';  $desktop_menus[2]->color = 'black';
}
$embed_to_li_element_mobile = function($anchor) { return str_repeat(' ', 16)."<li class=\"menu\"><a href=\"{$anchor->href}\" class=\"text-decoration-none {$anchor->color} font-size-0_8rem\">{$anchor->text}</a></li>"; };
$mobile_menu_lines = implode(PHP_EOL, array_map($embed_to_li_element_mobile, $mobile_menus));
$embed_to_li_element_desktop = function($anchor) { return str_repeat(' ', 10)."<li class=\"menu\"><a href=\"{$anchor->href}\" class=\"text-decoration-none {$anchor->color} font-size-0_8rem\">{$anchor->text}</a></li>"; };
$desktop_menu_lines = implode(PHP_EOL, array_map($embed_to_li_element_desktop, $desktop_menus));
?>
<body class="authorized-grid height-100vh margin-t0-r0-b0-l0">
  <header class="bg-black white">
    <div class="mobile width-max margin-t0-rauto-b0-lauto">
      <div class="mobile-grid">
        <h1 class="title"><a href="<?php echo URL['HOME']; ?>" class="text-decoration-none white">Example</a></h1>
        <input type="checkbox" id="menu-button">
        <div class="button">
          <label for="menu-button" class="button-label width-1_6rem height-1_6rem border-radius-5px bg-blue cursor-pointer">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
          </label>
        </div>
        <label for="menu-button" class="menu margin-t0-r0-b0_5rem-l0 bg-blue">
          <div class="content-center margin-t0_5rem-rauto-b0_5rem-lauto">Username&nbsp;<span class="italic"><?php echo $_SESSION['user_name']; ?></span></div>
          <nav class="bg-blue">
            <div class="list-align-center margin-t0-r0-b0_5rem-l0">
              <ul class="margin-t0-r0-b0-l0 padding-t0-r0-b0-l1rem list-style-type-circle">
<?php echo $mobile_menu_lines; ?>
              </ul>
            </div>
          </nav>
        </label>
      </div>
    </div>
    <div class="desktop width-max margin-t0-rauto-b0-lauto">
      <div class="desktop-grid">
        <h1 class="title"><a href="<?php echo URL['HOME']; ?>" class="text-decoration-none white">Example</a></h1>
        <div class="button"><a href="<?php echo ACTION_URL['LOGOUT']; ?>" class="border-radius-5px padding-t0_2rem-r0_8rem-b0_2rem-l0_8rem text-decoration-none font-size-0_8rem bg-blue white">Logout&nbsp;<span class="italic"><?php echo $_SESSION['user_name']; ?><span></a></div>
      </div>
    </div>
    <nav class="desktop bg-blue white">
      <div class="width-max margin-t0-rauto-b0-lauto">
        <ul class="margin-t0-r0-b0-l0 padding-t0-r0-b0-l0 display-in-a-row list-style-type-none">
<?php echo $desktop_menu_lines; ?>
        </ul>
      </div>
    </nav>
  </header>
