<?php

    # Se não existir, é criada a tabela users no banco de dados ctf

    $ctf = mysqli_connect("localhost", "root", "", "ctf");

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

    $sql = "select count(1) from users where session = '$sess';";
    $res = mysqli_fetch_assoc(mysqli_query($ctf, $sql))["count(1)"];

    if ($sess == null || $res == '0') {

        # Sessão inexistente ou inválida

        # Adicionando o primeiro bloco de código html

        $print = "<html>
        <head>
        <title>Root Security - CTF</title>
        <link rel='stylesheet' type='text/css' href='CSS/style.css'>
        <link href='https://fonts.googleapis.com/css?family=Maven+Pro|Open+Sans' rel='stylesheet'>
        <link rel='shortcut icon' href='favicon.ico' />
        </head>
        <body>
        <section>
        <form action='#' method='post' align='center' class='formulario'>
        <h1>Login</h1>
        <label align='left' class='label-topper'>Username:</label><input type='text' name='username' class='text-input'><br>
        <label align='left' class='label-topper'>Password:</label><input type='password' name='password' class='text-input'><br>
        <input type='submit' class='button' value='Login'><a class='link-shit' href='register.php'><input type='button' class='button red' value='Register'></a>
        </form>";

        # Recebendo as variáveis username e password pelo método POST

        $username = (isset($_POST["username"])) ? $_POST["username"] : null;
        $password = (isset($_POST["password"])) ? $_POST["password"] : null;

        # Aplicando htmlspecialchars nas variáveis para evitar XSS

        $username = htmlspecialchars($username);
        $password = htmlspecialchars($password);

        if ($username == null xor $password == null) {

            # Esqueceu alguma informação no formulário

            $print .= "Some information are missing. Try again. <br>";

        } elseif ($username == null and $password == null) {

            # Acabou de entrar na página

        } else {

            # Colocou todas as infos no formulário

            # Procurando username e password onde o username bate
            # e armazenando isso em $rows1

            $sql = "select username, password from users where username = '$username';";
            $rows1 = mysqli_fetch_assoc(mysqli_query($ctf, $sql));

            # Procurando username e password onde o email bate
            # e armazenando isso em $rows2

            $sql = "select email, password from users where email = '$username';";
            $rows2 = mysqli_fetch_assoc(mysqli_query($ctf, $sql));

            if ($password == $rows1["password"] && $username == $rows1["username"]) {

                # Se o $rows1 bater com as nossas variáveis

                # Atualizando o banco de dados com a nossa sessão

                $sess = generateRandomString();
                $_SESSION["session"] = $sess;
                mysqli_query($ctf, "update users set session = '$sess' where username = '$username';");

                # Redirecionando para a myprofile.php

                header("Location:/myprofile.php");

            } elseif ($password == $rows2["password"] && $username == $rows2["email"]) {

                # Se o $rows2 bater com as nossas variáveis

                # Atualizando o banco de dados com a nossa sessão

                $sess = generateRandomString();
                $_SESSION["session"] = $sess;
                mysqli_query($ctf, "update users set session = '$sess' where email = '$username';");

                # Redirecionando para a myprofile.php

                header("Location:/myprofile.php");

            } else {

                # Username ou password inválida

                $print .= "<div class='alert'><p>Invalid username or password. Try again.</p></div>";

            }

        }

    } else {

        # Sessão existente. Redirecionado para myprofile.php

        header("Location:myprofile.php");

    }

    $print .= "</section>
    </body>
    </hmtl>";

    echo $print;
?>