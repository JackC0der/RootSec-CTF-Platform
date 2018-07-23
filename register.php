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
        <form action='#' method='post' align='center' class='formulario'>
        <h1>REGISTER</h1>
        <label align='left' class='label-topper'>Username:</label><input type='text' name='username' class='text-input' placeholder='H4Ck3R'><br>
        <label align='left' class='label-topper'>Password:</label><input type='password' name='password' class='text-input' placeholder='532 TeR4ByT3s de_EnCriPtIoN!'><br>
        <label align='rigth' class='label-topper'>Email:</label><input type='text' name='email' class='text-input' placeholder='hackudo@hackudesa.hack'><br>
        <input class='button' type='submit'>
        <br><br>
        <a href='login.php' id='link'>Already have an account?</a>
        </form>";

        # Recebendo as variáveis username, password e email pelo método POST

        $username = (isset($_POST["username"])) ? $_POST["username"] : null;
        $password = (isset($_POST["password"])) ? $_POST["password"] : null;
        $email = (isset($_POST["email"])) ? $_POST["email"] : null;

        # Aplicando htmlspecialchars nas variáveis para evitar XSS

        $username = htmlspecialchars($username);
        $password = htmlspecialchars($password);
        $email = htmlspecialchars($email);

        if ($username == null || $password == null || $email == null) {

            if ($username == null && $password == null && $email == null) {

                # Acabou de entrar na página

            } else {

                # Esqueceu alguma informação no formulário

                $print .= "<div class='alert'><p>Some information are missing. Try again.</p></div>";

            }

        } else {
            
            # Colocou todas as infos no formulário

            # Verificando se o $username já existia na tabela
            # e armazenando em $res1

            $sql = "select count(1) from users where username = '$username';";
            $res1 = mysqli_fetch_assoc(mysqli_query($ctf, $sql))["count(1)"];

            # Verificando se o $email já existia na tabela
            # e armazenando em $res2

            $sql = "select count(1) from users where email = '$email';";
            $res2 = mysqli_fetch_assoc(mysqli_query($ctf, $sql))["count(1)"];

            if ($res1 == '0' && $res2 == '0') {

                # Se não existe nenhuma das informações no banco de dados

                # Gerando uma sessão e adicionando o usuário na tabela

                $sess = generateRandomString();
                $_SESSION["session"] = $sess;
                $sql = "insert into users (session, username, password, email)
                VALUES ('$sess', '$username', '$password', '$email');";
                mysqli_query($ctf, $sql);

                $print .= "<div class='alert green'><p>Congratulations! Account registered!</p></div>";

            } else {

                # Email ou username já existe na tabela

                $print .= "<div class='alert'><p>Email or username already used. Try again.</p></div>";

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