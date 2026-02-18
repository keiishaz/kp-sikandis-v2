<!DOCTYPE html>
<html>
<head>
    <title>Login — Step 2</title>
</head>
<body>

<h2>Login — Step 2</h2>

<p>NIP: {{ session('login_nip') }}</p>

<form method="POST" action="{{ route('login.password.submit') }}">
    @csrf
    <input type="password" name="password" placeholder="Masukkan password">
    <button>Login</button>
</form>

@error('password')
<div id="msg">{{ $message }}</div>
@enderror

@if(session('locked_until'))
<div id="timer"></div>
@endif

@if(session('locked_until'))
<div id="timer"></div>
<script>
const timerDiv = document.getElementById('timer');
let lockedUntil = new Date("{{ session('locked_until') }}").getTime();

const interval = setInterval(() => {
    let now = new Date().getTime();
    let sec = Math.floor((lockedUntil - now) / 1000); // integer
    if(sec > 0){
        timerDiv.textContent = "Tunggu " + sec + " detik sebelum mencoba lagi";
    } else {
        timerDiv.textContent = "";
        clearInterval(interval);
    }
}, 1000);
</script>
@endif


</body>
</html>
