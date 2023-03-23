<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Test</title>
        <link rel="stylesheet" type="text/css" href="<?= URL; ?>public/css/default.css"/>
        <script type="text/javascript" src="<?= URL; ?>public/js/jquery.js"></script>

        <?php
        if (isset($this->js)) {
            foreach ($this->js as $key => $js) {
                echo '<script type="text/javascript" src="' . URL .'views/'. $js . '"></script>';
            }
        }
        ?>
    </head>
    <body>
        <?php Session::init(); ?>
        <div id="header">
            header
            <br>
            <a href="<?= URL; ?>index">Index</a>
            <a href="<?= URL; ?>help">Help</a>
            <?php if (Session::get('loggedIn') == true): ?>
                <a href="<?= URL; ?>dashboard/logout">Logout</a>
            <?php else: ?>
                <a href="<?= URL; ?>login">Login</a>
            <?php endif; ?>
        </div>
        <div id="content">

