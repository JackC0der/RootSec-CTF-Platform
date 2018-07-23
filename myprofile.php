<?php

    # Fazendo conexão com o banco de dados

    $ctf = mysqli_connect("localhost", "root", "", "ctf");

    # Se não existir, é criada a tabela users no banco de dados ctf

    $sql = "create table if not exists users (
    id integer auto_increment primary key,
    session varchar(30),
    username varchar(30),
    password varchar(30),
    email varchar(40),
    done text not null default '',
    email_verify boolean default false,
    admin boolean default false
    )";

    mysqli_query($ctf, $sql);

    # Função geradora de strings aleatórias

    function generateRandomString($length = 30) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    # Iniciando sessão

    session_start();

    # Atribuindo o valor da sessão a variavel $sess

    $sess = (isset($_SESSION["session"])) ? $_SESSION["session"] : null;

    # Checando se a sessão já existe no banco de dados

    $sql = "select count(1) from users where session = '".$sess."';";
    $res = mysqli_fetch_assoc(mysqli_query($ctf, $sql))["count(1)"];

    if ($sess == null || $res == '0') {

        # Sessão inexistente ou inválida

        header("Location:login.php");

    } else {

        # Sessão existe

        # Pegando o nome do usuário e se ele possui privilégio adiministrativo

        $sql = "select username, admin from users where session = '$sess';";
        $row = mysqli_fetch_assoc(mysqli_query($ctf, $sql));

        $username = $row["username"];
        $admin = $row["admin"];

        # Adicionando o primeiro bloco de código html

        $print = "<html>
        <head>
                <title>Root Security - CTF</title>
                <link rel='stylesheet' type='text/css' href='CSS/style.css'>
                <link href='https://fonts.googleapis.com/css?family=Maven+Pro|Open+Sans' rel='stylesheet'>
                <link rel='shortcut icon' href='favicon.ico' />
            </head>
            <body>
                <header>
                    <nav>
                        <a href='#' id='Logo'>Root Security CTF</a>
                        <ul>";
        
        if ($admin == true) {
            $print .= "<a href='add.php'><li><button class='navBTN'>Add Chall</button></li></a>";
        }

        $print .= "         <a href='see.php'><li><button class='navBTN'>Challenges</button></li></a>
                            <a href='#'><li><button class='navBTN'>Ranking</button></li></a>
                            <a href='myprofile.php'><li><button class='navBTN'>My Profile</button></li></a>
                            <a href='logout.php'><li><button class='navBTN'>Logout</button></li></a>
                        </ul>
                    </nav>
                </header>
                <h1>user interface</h1>";

        $print .= "Welcome $username!<br>";
    }

    $print .= "</body>
    </hmtl>";

    echo $print;
?>