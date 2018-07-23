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

    $sql = "select count(1) from users where session = '$sess';";
    $res = mysqli_fetch_assoc(mysqli_query($ctf, $sql))["count(1)"];

    if ($sess == null || $res == '0') {

        # Sessão inexistente ou inválida

        header("Location:login.php");

    } else {

        # Sessão existe

        # Verificando privilégio administrativo

        $sql = "select admin from users where session = '$sess';";
        $admin = mysqli_fetch_assoc(mysqli_query($ctf, $sql))["admin"];

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
        
        $print .= "     <a href='see.php'><li><button class='navBTN'>Challenges</button></li></a>
                        <a href='#'><li><button class='navBTN'>Ranking</button></li></a>
                        <a href='myprofile.php'><li><button class='navBTN'>My Profile</button></li></a>
                        <a href='logout.php'><li><button class='navBTN'>Logout</button></li></a>
                    </ul>
                </nav>
            </header>
            <section>
                    <table id='tabela' align='center'>
                        <tr>";
        
        if ($admin == true) {

            # Colunas se o usuário for adiminstrador

            $print .= "<th>Name</th>
            <th>Download</th>
            <th>Flag</th>
            <th>Skills</th>
            <th>Points</th>
            <th>Solved</th>
            <th>Edit</th>
            </tr>
            <tr>";

        } else {

            # Colunas se o usuário não for adiminstrador

            $print .= "<th>Name</th>
            <th>Skills</th>
            <th>Points</th>
            <th>Solved</th>
            <th>Play</th>
            </tr>
            <tr>";
        
        }

        # Pegando todos os desafios do banco de dados

        $sql = "select * from challs";
        $res = mysqli_query($ctf, $sql);
        
        # Adicionando as linhas no html

        if (mysqli_num_rows($res) > 0) {
                
            # Liberando os dados de cada coluna nas linhas

            while($row = mysqli_fetch_assoc($res)) {

                if ($admin == true) {

                    # Colunas se o usuário for adiminstrador

                    $print .= "<td>" . $row["name"]. "</td><td>" . $row["download"] . "</td><td>" . $row["flag"] . "</td><td>" . $row["skill"] . "</td><td>" . $row["points"] . "</td><td>" . $row["solved"] . "</td><td><a href='edit.php?id=" . $row["id"] . "'><button class='play'>Edit!</button></a></td></tr>";

                } else {

                    # Colunas se o usuário não for adiminstrador

                    $print .= "<td>" . $row["name"]. "</td><td>" . $row["skill"] . "</td><td>" . $row["points"] . "</td><td>" . $row["solved"] . "</td><td><a href='challenge.php?id=" . $row["id"] . "'><button class='play'>Play!</button></a></td></tr>";

                }

            }
        } else {
            $print .= "<td>Null</td><td>Null</td><td>Null</td><td>Null</td><td>Null</td>";
        }

        $print .= "</tr>
        </table>
        </section>
        </body>
        </html>";

        echo $print;
    }
?>