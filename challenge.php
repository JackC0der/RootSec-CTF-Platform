<?php

    # Fazendo conexão com o banco de dados

    $ctf = mysqli_connect("localhost", "root", "", "ctf");

    # Se não existir, é criada a tabela challs no banco de dados ctf

    $sql = "create table if not exists challs (
    id integer auto_increment primary key,
    name varchar(30),
    enunciation text,
    download varchar(50),
    flag varchar(50),
    skill varchar(30),
    points integer,
    solved integer default 0
    )";

    mysqli_query($ctf, $sql);

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

        # Adicionando o primeiro bloco de código html

        $print = "<html>
        <head>
            <meta charset='UTF-8'>
            <title>challenge</title>
            <link rel='stylesheet' type='text/css' href='CSS/style.css'>
            <link href='https://fonts.googleapis.com/css?family=Maven+Pro|Open+Sans' rel='stylesheet'>
            <link rel='shortcut icon' href='favicon.ico'>
        </head>
        <head>
        <body>
            <header>
                <nav>
                <a href='#' id='Logo'>Root Security CTF</a>
                    <ul>
                        <li></li>
                        <li><a href='see.php' class='navBTN'>Challenges</a></li>
                        <li><a href='#' class='navBTN'>Ranking</a></li>
                        <li><a href=''myprofile.php' class='navBTN'>My Profile</a></li>
                    </ul>
                </nav>
            </header>
            <section>
                <div class='chall'>";

        # Recebendo a variável $flag
        
        $flag = (isset($_POST["flag"])) ? $_POST["flag"] : null;
        $flag = strtoupper($flag);

        # Recebendo a variável $id

        $id = (isset($_GET["id"])) ? $_GET["id"] : null;
        $id = htmlspecialchars($id);
        $id = (int) $id;
        
        # Verificando existencia do chall

        $sql = "select count(1) from challs where id = $id;";
        $res1 = mysqli_fetch_assoc(mysqli_query($ctf, $sql))["count(1)"];

        if ($id != null && $res1 == '1') {

            # Desafio existe

            # Pegando informações sobre o desafio no banco de dados

            $sql = "select name, enunciation, download, flag, solved from challs where id = $id;";
            $rows = mysqli_fetch_assoc(mysqli_query($ctf, $sql));
            
            # Alocando as informações em variaveis

            $name = $rows["name"];
            $enunciation = $rows["enunciation"];
            $download = $rows["download"];
            $solved = $rows["solved"];
            $right_flag = strtoupper($rows["flag"]);

            # Adicionando o nome e o enunciado na tela

            $print .= "<h1>$name:</h1>";
            $print .= "<p>$enunciation</p><br><br><br>";

            if ($download != null) {

                # Se o desafio necessita de download de algum arquivo,
                # colocar o Download Link na tela

                $print .= "<p>Download link: <a href=$download id='link'>$download</a></p><br><br><br>";
            }

            if ($flag != null) {

                # Se uma flag foi submetida

                if ($flag == $right_flag) {

                    # Se a flag está correta,
                    # green alert

                    $print .= "<div class='alert green'><p>Congratulations, right flag!</p></div>";

                    $sql = "select done from users where session = '$sess'";
                    $done = mysqli_fetch_assoc(mysqli_query($ctf, $sql))["done"];

                    $alreadydone = strpos($done, $id.',');

                    if ($alreadydone === false) {

                        # O usuário ainda não tinha resolvido

                        # Adicionando 1 ao contador de players que resolveram
                    
                        $sql = "update challs set solved = solved + 1 where id=$id";
                        mysqli_query($ctf, $sql);

                        # Concatenando o novo desafio ao usuário

                        $sql = "update users set done = concat(done, '$id', ',') where session = '$sess'";
                        mysqli_query($ctf, $sql);

                    } else {

                        # O usuário já tinha resolvido

                    }

                } else {

                    # Se a flag está errada,
                    # red alerd

                    $print .= "<div class='alert'><p>Wrong flag! Sorry.</p></div>";

                }
            }

            $print .= '<form action="#" method="post">
            <input type="text" name="flag" placeholder="FL4G" class="text-input" id="text-top">
            <input type="submit" class="button" id="input-top">
            </form>';

        } else {

            # Desafio não existe

            $print .= "No chall.<br>";
        }
    }

    $print .= "</div>
    </section>
    </body>
    </hmtl>";

    echo $print;
?>