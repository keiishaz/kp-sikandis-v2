<!DOCTYPE html>
<html>
<head>
    <title>Login — Step 1</title>
</head>
<body>

<h2>Login — Step 1</h2>

<form method="POST" action="/login-nip">
@csrf
<input name="nip" placeholder="NIP">
<button>Lanjut</button>
</form>

@error('nip') <div>{{ $message }}</div> @enderror

</body>
</html>
