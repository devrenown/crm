<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Happy Birthday</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f4f6f8;
            font-family: Arial, Helvetica, sans-serif;
        }
        .wrapper {
            max-width: 620px;
            margin: auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #1e88e5, #0d47a1);
            color: #ffffff;
            text-align: center;
            padding: 35px 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            letter-spacing: .5px;
        }
        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid #ffffff;
            margin: 20px auto 0;
            display: block;
            object-fit: cover;
            background: #fff;
        }
        .content {
            padding: 30px;
            color: #444;
            line-height: 1.7;
            font-size: 15px;
        }
        .content p {
            margin-bottom: 15px;
        }
        .highlight {
            background: #f1f8ff;
            border-left: 4px solid #1e88e5;
            padding: 15px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .footer {
            background: #fafafa;
            text-align: center;
            padding: 20px;
            font-size: 13px;
            color: #777;
        }
        .footer strong {
            color: #1e88e5;
        }
    </style>
</head>
<body>

<div class="wrapper">

    <!-- HEADER -->
    <div class="header">
        <h1>Happy Birthday {{ $user->firstname }}!</h1>
        <img
            src="{{ $user->avatar ? asset('storage/users/'.$user->avatar) : asset('images/default-avatar.png') }}"
            alt="{{ $user->fullname }}"
            class="avatar"
        >
    </div>

    <!-- CONTENT -->
    <div class="content">
        <p>
            Dear <strong>{{ $user->fullname }}</strong>,
        </p>

        <p>
            On this special day, we want to take a moment to celebrate <strong>you</strong> —
            not just your birthday, but the positivity, dedication, and energy you bring to our team every day.
        </p>

        <p>
            Birthdays are a reminder of how far we’ve come and an opportunity to look forward to
            new goals, new achievements, and new memories. May this year bring you
            continued success, good health, happiness, and countless reasons to smile.
        </p>

        <div class="highlight">
            🎂 May your day be filled with joy, laughter, and cake! <br>
            🌟 May the year ahead open doors to new opportunities and accomplishments. <br>
            🎁 May every challenge turn into a success story.
        </div>

        <p>
            We truly appreciate your contribution and look forward to celebrating many more milestones together.
            Enjoy your special day and make the most of the year ahead!
        </p>

        <p>
            Once again, <strong>Happy Birthday</strong>! 🥳
        </p>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Warm wishes,<br>
        <strong>Team {{ $tenant->name }}</strong>
    </div>

</div>

</body>
</html>
