<h1>Operator Dashboard</h1>
<p>Ini laman operator</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>