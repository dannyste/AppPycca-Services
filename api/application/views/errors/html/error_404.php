<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="robots" content="noindex">
        <meta name="googlebot" content="noindex">
        <title>404 Page Not Found</title>
        <style type="text/css">
            @font-face {
                font-family: 'robotomedium';
                src: url('../../fonts/Roboto-Regular.ttf') format('truetype');
            }
            * {
                box-sizing: border-box;
                margin: 0;
                list-style: none;
                padding: 0;
                text-decoration: none;
                font-family: "robotomedium", sans-serif, arial;
            }
            h1 {
                color: #303030;
                font-size: 140px;
                margin: 100px auto 0px;
                max-width: 400px;
                text-align: center;
            }
            .container {
                max-width: 75%;
                margin: 0px auto;
            }
            .primary-error {
                text-align: center;
            }
            .error-message {
                margin-top: -10px;
                font-size: 20px;
            }
            .primary {
                font-size: 18px;
                margin: 100px auto 0px;
                max-width: 350px;
                text-align: center;
            }
            .button{
                transition: background-color 0.3s, color 0.3s, box-shadow 0.3s;
                border: 2px solid lightblue;
                border-radius: 3px;
                padding: 10px 15px;
                text-decoration: none;
                display: inline-block;
                color: #303030;
                box-shadow: 2px 2px 2px 0px rgba(168,168,168,1);
                font-size: 18px;
            }
            .button:hover{
                background-color: lightblue;
                color: white;
                box-shadow: 4px 4px 6px 0px rgba(168,168,168,1);
            }
            .button-row {
                margin: 30px auto 0px;
                max-width: 200px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="primary-error">
                <h1>404<?php //echo $heading;    ?></h1>
                <p class="error-message"><?php echo $message; ?></p>
            </div>
    </body>
</html>