<?php

    $dbHost ='localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'teste-login';

    $conn = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);

    //if ($conn->connect_errno){ 
       //echo "erro";
    //} else{
       // echo "conexao efetuada";
    //}