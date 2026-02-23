<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* watermark CSS here */
    </style>
</head>
<body>

<div class="watermark">
    {{ $tenant->name }} | {{ $user->name }} | {{ now()->format('d-m-Y H:i') }}
</div>

</body>
</html>
