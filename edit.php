<html>
	<head>
		<meta charset="UTF-8">
        <title>challenge</title>
        <link rel="stylesheet" type="text/css" href="../CSS/style.css">
        <link href="https://fonts.googleapis.com/css?family=Maven+Pro|Open+Sans" rel="stylesheet">
        <link rel="shortcut icon" href="favicon.ico">
	</head>
	<head>
	<body>
        <header>
            <nav>
            <a href="#" id="Logo">Root Security CTF</a>
                <ul>
                    <li></li>
                    <li><a href="see.php" class="navBTN">Challenges</a></li>
                    <li><a href="add.php" class="navBTN">Add Chall</a></li>
                        <li>
                            <input type="checkbox" style="display:none;" id="sub-menu-check"/>
                            <a href="#" class="navBTN subBTN" id="sub-menu-1" onclick="subMenuManager('sub-menu-1');">Admin</a>
                            <ul class="sub-menu">
                                <li><a herf="see.php" class="sub-menuBTN">Exit</a></li>
                            </ul>    
                        </li>
                </ul>
            </nav>
        </header>
        <section>
                <?php
                    $flag = (isset($_POST["flag"])) ? $_POST["flag"] : null;
                    $flag = strtoupper($flag);
                    $id = (isset($_GET["id"])) ? $_GET["id"] : null;
                    $id = htmlspecialchars($id);

                    $ctf = mysqli_connect("localhost", "root", "", "ctf");

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

                    $id = (int) $id;
                    
                    if ($id != null && is_integer($id)) {
                        $sql = "select count(1) from challs where id = " . $id . ";";
                        $res = mysqli_fetch_assoc(mysqli_query($ctf, $sql))["count(1)"];
                    }

                    if ($id != null && $res == '1' && is_integer($id)) {
                        $sql = "select name, enunciation, download, flag from challs where id = " . $id . ";";
                        $rows = mysqli_fetch_assoc(mysqli_query($ctf, $sql));
                    
                        $name = $rows["name"];
                        $enunciation = $rows["enunciation"];
                        $download = $rows["download"];
                        $right_flag = strtoupper($rows["flag"]);
                        echo $name."<br><br>";
                        echo $enunciation."<br><br>";

                        if ($download != null) {
                            echo "Download link: ".$download."<br><br>";
                        }

                        if ($flag != null) {
                            if ($flag == $right_flag) {
                                echo "Congratulations, right flag!";
                            } else {
                                echo "Wrong flag! Sorry.";
                            }
                        }
                        echo '<form action="#" method="post">
                            Flag: <input type="text" name="flag"><br>
                            <input type="submit">
                        </form>';
                    } else {
                        echo "No chall.<br>";
                    }
                    mysqli_close($ctf);
                ?>
        </section>
	</body>
</hmtl>