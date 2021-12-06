<!DOCTYPE>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    </head>

    <body>
        <div class="container border border-black mb-5 pb-5">
            <h1 class="m-5 pt-5 text-center">Welcome</h1>
            <form method="POST" action="contractor" class="text-center">
                @csrf
                <input type="text" name="username" placeholder="Username">
                @error('username') <div style="color:red">{{ $message }}</div> @enderror
                <input type="password" name="password" placeholder="Password">
                @error('password') <div style="color:red">{{ $message }}</div> @enderror
                <button type="input" class="btn btn-primary">Log In</button>
            </form>

            <div class="text-center">
                <h4 style="text-decoration:underline">Hint:</h4>
                <p>Username 1: Contractor 1</p>
                <p class="mb-0">Password 1: 123</p>
                <p class="m-0">-----------------------------</p>
                <p>Username 2: Contractor 2</p>
                <p>Password 2: 123</p>
            </div>
        </div>
    </body>
</html>