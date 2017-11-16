<html>
<head>


</head>
<body>

<form action="/search" method="GET">
    <input type="text" name="searchText">
    <input type="submit" value="検索">
</form>

<div>
    <h2>メッセージ一覧</h2>
    <ul>
        @if (!empty($users))
            @foreach($users as $user)
                <li>{{ $user->name }}</li>
            @endforeach
        @endif
    </ul>
</div>

<div>
    <h2>メッセージ詳細</h2>
    <ul>
        @if (!empty($users))
            @foreach($messages as $message)
                <li>{{ $message->message }}</li>
            @endforeach
        @endif
    </ul>
</div>

</body>
</html>