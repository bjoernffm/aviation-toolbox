<head>
    <title>Hallo Mama</title>
    <style>
        .active {
            background-color: #c23;
            color: #fff;
            padding: 5px;
        }
    </style>
</head>
<body>
    <a href="#">Home</a> |
    <a href="#" class="@yield('nav_tshirts_classes')">T-Shirts</a> |
    <a href="#" class="@yield('nav_socken_classes')">Socken</a> |
    <a href="#">Impressum</a>

    <h1>Willkommen auf der Shop-Seite</h1>
    <hr />

    @yield('content')
</body>
