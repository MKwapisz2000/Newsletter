<?php

session_start();

//sprawdzamy czy zmienna email jest ustawiona - wypelniono formularz
if(isset($_POST['email']))
{
    //sprawdzenie budowy adresu 
    //funkcja filter_input(typ-zrodlo danych wejsciowych, indeks zmiennej, rodzaj filtru)
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    //udana walidacja -> wloz do zmiennje email poprawny email
    //nueudana walidacja -> wloz do zmiennej email fale/NULL

    //czy zmienna jest pusta (false/NULL)
    if(empty($email))
    {
        //by pokazac bledny email uzytkownikowi
        $_SESSION['given_email']=$_POST['email'];
        header('Location: index.php');

    }
    else
    {
        require_once 'database.php';

        //wkladamy email do bazy
        //1 krok zapytanie -> prepare przygotuj
        //2 krok wlozenie do bazy -> bindvalue(gdzie wartosc ma trafic, zmienna, typ wstawianej wartosci) przypisz
        //3 krok wykonaj
        $query = $db->prepare('INSERT INTO users VALUES (NULL, :email)');
        $query->bindvalue(':email', $email, PDO::PARAM_STR);
        $query->execute();
    }
}

else
{
    //nie wypelniono formularza - odsyłamy do niego
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>

    <meta charset="utf-8">
    <title>Zapisanie się do newslettera</title>
    <meta name="description" content="Używanie PDO - zapis do bazy MySQL">
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
                <p>Dziękujemy za zapisanie się na listę mailową naszego newslettera!</p>
            </article>
        </main>

    </div>

</body>
</html>