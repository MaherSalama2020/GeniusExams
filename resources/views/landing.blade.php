<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Genius Exams</title>
    <link href=" {{ mix('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <!--Google Font-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic|Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:100,300,400,500,700,900">
    <link href="https://fonts.googleapis.com/css2?family=Krona+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oxygen:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Shadows+Into+Light&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <style>
    .myFont {
        /* font-family: 'Krona One', sans-serif !important;  */
        /* font-family: 'Indie Flower', cursive !important; */
        /* font-family: 'Oxygen', sans-serif !important; */
        /* font-family: 'Shadows Into Light', cursive !important; */
        font-family: 'Karla', sans-serif !important;
    }
    label{
        display: inline;
        margin-bottom: 0 !important;
    }
        /* Scrollbar */
        /* width */
        ::-webkit-scrollbar {
        width: 15px !important;
        }

        /* Track */
        ::-webkit-scrollbar-track {
        box-shadow: inset 0 0 5px grey !important;
        border-radius: 10px !important;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
        background: orange !important;
        border-radius: 10px !important;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
        background: #ff9800 !important;
        }
    </style>
</head>
<body>
    <div id="app">
        <app  id="inspire" class="myFont"></app>
    </div>
    <script src="{{ mix('js/bootstrap.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
