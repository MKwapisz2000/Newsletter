<?php
session_start();

//polaczenie sie z baza by sprawdzic czy taki uzytkownik istnieje
require_once 'database.php';

//jezeli nie jestes juz zalogowany
if(!isset($_SESSION['logged_id']))
{
    //sprawdzamy czy zmienna login istnieje -> czy uzytkownik wypelnil pola
    if(isset($_POST['login']))
    {
        //odczytanie danych podanych przez uzytkownika
        $login = filter_input(INPUT_POST, 'login');
        $password = filter_input(INPUT_POST, 'pass');

        //echo $login." ".$password;
        

        //3 kroki
        $userquery = $db->prepare('SELECT id, password FROM admins WHERE login = :login');
        $userquery->bindvalue(':login', $login, PDO::PARAM_STR);
        $userquery->execute();

        //sprawdzenie ile wynikow otrzymalismy
        //echo $userquery->rowCount();

        //przypisanie do tablicy wynikow wyszukiwania
        $user = $userquery->fetch();
        echo $user['id'] . " " . $user['password'];

        //jezeli tablica nie jest pusta i haslo zgadza sie z tym z bazy
        if($user && password_verify($password, $user['password']))
        {
            //udane logowanie 
            $_SESSION['logged_id'] = $user['id'];
            unset( $_SESSION['bad_attempt']);

        }
        else
        {
            //nieudane logowanie
            $_SESSION['bad_attempt']=true;
            header('Location: admin.php');
            exit();
        }
    }
    else
    {
        header('Location: admin.php');
        exit();
    }
}

//wyjmowanie z tabeli adresow
//nie obawiamy sie wstrzykiwania SQL bo nic nie podaje uzytkownik
$usersquery = $db->query('SELECT * FROM users');

//przyniesie / przypisanie wynikow do tablicy
$users = $usersquery->fetchALL();

//drukuj rekursywnie
//print_r($users);

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Panel administracyjny</title>
    <meta name="description" content="Używanie PDO - odczyt z bazy MySQL">
    <meta name="keywords" content="php, kurs, PDO, połączenie, MySQL">
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">

    <link rel="stylesheet" href="main.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster|Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
    <!--[if lt IE 9]>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->
</head>

<body>

    <div class="container">

        <header>
            <h1>Newsletter</h1>
        </header>

        <main>
            <article>
                <table>
                    <thead>
                        <tr><th colspane="2">Łącznie rekordów: <?php $usersquery->rowCount() ?></th></tr>
                        <tr><th>ID</th><th>E-mail</th></tr>
                    </thead>
                    <body>
                        <?php 
                        //wypisanie
                        foreach($users as $user)
                        {
                            echo "<tr><td>{$user['id']}</td><td>{$user['email']}</td></tr>";
                        }
                        ?>
                    </body>
                </table>
                <p><a href="logout.php">Wyloguj się!</a></p>
  
            </article>
        </main>

    </div>

</body>
</html>