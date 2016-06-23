<?php

$menu_item_default = '#';
$menu_items = array(
  '#' => array(
    'label' => 'Accueil',
    'desc' => 'Login',
  ),
);

// Determine the current menu item.
$menu_current = $menu_item_default;
// If there is a query for a specific menu item and that menu item exists...
if (@array_key_exists($this->uri->segment(2), $menu_items)) {
  $menu_current = $this->uri->segment(2);
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Mania sms | <?php html_escape($menu_items[$menu_current]['label']); ?></title>
        <meta name="description" content="<?php html_escape($menu_items[$menu_current]['desc']); ?>">
        <meta name="viewport" content="width=device-width">

       
        <style>
            body {
             /*   padding-top: 60px;
                  padding-bottom: 40px;*/
            }
        </style>
        <link href='https://fonts.googleapis.com/css?family=Poiret+One|Roboto+Slab|Abel|Lato|Abril+Fatface|Vollkorn|Open+Sans|Arvo' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href=<?php echo base_url() . "/css/libs/animate.css" ?>/>
        <link rel="stylesheet" href=<?php echo base_url() . "/css/main.css" ?>>

    </head>
    <body>
        <!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->
        <header>
       
            <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse">
                        <span class="sr-only">Toggle navigation</span>

                    </button>
                    <a class="navbar-brand .form-inline" href="<?php echo base_url() ?>"><img src='<?php echo base_url() ."/img/logo.png" ?>' alt="logo"></a>
                </div>

                    <div class="collapse navbar-collapse" >

                                         <ul class="nav navbar-nav">
                                            
                                         </ul>

                    </div>
           
            </div>
            </nav>
     
        </header>
        <div class="container-fluid login-page">
    