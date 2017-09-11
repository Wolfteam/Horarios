<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="icon" href="favicon.ico" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="lib/materialize/css/materialize.min.css">
    <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="css/site.css" rel="stylesheet" />
</head>

<body>

    <header>
        <nav class="grey darken-4" role="navigation">
            <div class="nav-wrapper container">
                <a href="#!" class="brand-logo">Horarios<i class="fa fa-list-alt" aria-hidden="true"></i></a>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <h2>Iniciar Sesion</h2>
            <div class="row">
                <div clas="col s12 ">
                <form class="login">
                    <div class="row">
                        <div class="input-field col s12">
                            <i class="fa fa-user prefix"></i>
                            <input id="first_name" type="text" class="validate" data-length="10">
                            <label for="first_name">Usermane</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <i class="fa fa-lock prefix"></i>
                            <input id="password" type="password" class="validate" data-length="10">
                            <label for="password">Password</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 center">
                            <button class="btn green waves-effect waves-light" type="submit" name="action">Iniciar Sesion
                            <i class="fa fa-sign-in right"></i>
                        </button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="page-footer grey darken-4">
        <div class="container">
            <div class="row">
                <div class="col l6 s12">
                    <h5 class="white-text">© Copyright 2017</h5>
                    <p class="grey-text text-lighten-4">Wolfteam20. All rights reserved.</p>
                </div>
                <div class="col l4 offset-l2 s12">
                    <h5 class="white-text"><a class="grey-text text-lighten-3" href="https://github.com/Wolfteam">GitHub<i class="fa fa-github" aria-hidden="true"></i></a></h5>
                </div>
            </div>
        </div>
    </footer>

    <!-- ======================== Javascript ======================== -->
    <script src="lib/jquery/dist/jquery.min.js"></script>
    <script src="lib/materialize/js/materialize.min.js"></script>
</body>

</html>