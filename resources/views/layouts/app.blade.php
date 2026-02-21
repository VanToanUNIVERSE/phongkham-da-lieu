<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @if (Auth::check())
            Chào mừng {{ Auth::user()->role->name }}
        @endif
    </title>
    <style>
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 30px;
        }

        ul {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            list-style-type: none;
        }

        li a {
            display: block;
            padding: 20px;
            background-color: blue;
            cursor: pointer;
        }

        li a:hover {

            color: rgb(150, 150, 209);
        }

        #logout {
            display: block;
            padding: 20px;
            background-color: red;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header>
        <img src="{{ asset('images/logophongkham.png') }}" width="100px">
        {{-- Sidebar --}}
        @include('partials.sidebar')
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Đăng xuất</button>
        </form>
    </header>


    {{-- Nội dung từng trang --}}
    <div class="content">
        @yield('content')
    </div>
</body>

</html>
