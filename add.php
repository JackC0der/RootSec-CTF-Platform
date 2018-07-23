<html>
	<head>
		<meta charset="UTF-8">
        <title>add challs</title>
        <script type="text/javascript" src="JS/javascript.js"></script>
        <link rel="stylesheet" type="text/css" href="CSS/style.css">
        <link href="https://fonts.googleapis.com/css?family=Maven+Pro|Open+Sans" rel="stylesheet">
        <link rel="shortcut icon" href="favicon.ico" />
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
        <form action="#" method="post" align="center" class="formulario">
            <h1>Create Challenge</h1>
            <label align='left' class='label-topper'>Name:</label><input type="text" name="name" class="text-input"><br>
            <label align='left' class='label-topper'>Enunciation:</label><input type="comment" name="enunciation" class="text-input"><br>
            <label align='left' class='label-topper'>Download:</label><input type="text" name="download" class="text-input"><br>
            <label align='left' class='label-topper'>Flag:</label><input type="text" name="flag" class="text-input"><br>
            <label align='left' class='label-topper'>Skill:</label><input type="text" name="skill" class="text-input"><br>
            <label align='left' class='label-topper'>Points:</label><input type="number" name="points" class="text-input"><br>

            <input type="submit" aling="center" class="button"><a class='link-shit' href='register.php'><input type='button' class='button red' value='See Challs'></a>
        </form>

            <?php
                $name = (isset($_POST["name"])) ? $_POST["name"] : null;
                $enunciation = (isset($_POST["enunciation"])) ? $_POST["enunciation"] : null;
                $download = (isset($_POST["download"])) ? $_POST["download"] : null;
                $flag = (isset($_POST["flag"])) ? $_POST["flag"] : null;
                $skill = (isset($_POST["skill"])) ? $_POST["skill"] : null;
                $points = (isset($_POST["points"])) ? $_POST["points"] : null;

                $name = htmlspecialchars($name);
                $enunciation = htmlspecialchars($enunciation);
                $download = htmlspecialchars($download);
                $flag = htmlspecialchars($flag);
                $skill = htmlspecialchars($skill);
                $points = htmlspecialchars($points);

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

                $sql = "insert into challs (name, enunciation, download, flag, skill, points)
                VALUES ('" . $name . "', '" . $enunciation . "', '" . $download . "', '" . $flag . "', '" . $skill . "', " . $points . ")";

                mysqli_query($ctf, $sql);

                mysqli_close($ctf);
            ?>
        </section>
	</body>
</hmtl>