<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Inter,Segoe UI,sans-serif;
        }

        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            overflow:hidden;
            background:#0f172a;
        }

        body::before{
            content:"";
            position:absolute;
            width:500px;
            height:500px;
            background:#4f46e5;
            border-radius:50%;
            top:-150px;
            left:-150px;
            filter:blur(120px);
        }

        body::after{
            content:"";
            position:absolute;
            width:450px;
            height:450px;
            background:#06b6d4;
            border-radius:50%;
            bottom:-150px;
            right:-100px;
            filter:blur(120px);
        }

        .login-box{
            width:420px;
            padding:45px;
            position:relative;
            z-index:10;

            background:rgba(255,255,255,.08);
            border:1px solid rgba(255,255,255,.18);
            backdrop-filter:blur(20px);
            border-radius:25px;

            box-shadow:
            0 20px 60px rgba(0,0,0,.45);
        }

        .logo{
            width:80px;
            height:80px;
            margin:auto;
            border-radius:20px;
            background:linear-gradient(135deg,#6366f1,#06b6d4);
            display:flex;
            align-items:center;
            justify-content:center;
            color:white;
            font-size:32px;
            font-weight:bold;
        }

        h2{
            margin-top:25px;
            color:white;
            text-align:center;
            font-size:34px;
        }

        .subtitle{
            text-align:center;
            color:#cbd5e1;
            margin-top:8px;
            margin-bottom:35px;
        }

        .input-group{
            margin-bottom:22px;
        }

        .input-group label{
            color:white;
            display:block;
            margin-bottom:8px;
            font-size:14px;
        }

        .input-group input{

            width:100%;
            padding:15px 18px;
            border:none;
            outline:none;

            background:rgba(255,255,255,.08);

            border:1px solid rgba(255,255,255,.15);

            color:white;

            border-radius:14px;

            transition:.3s;
        }

        .input-group input::placeholder{
            color:#94a3b8;
        }

        .input-group input:focus{
            border-color:#60a5fa;
            background:rgba(255,255,255,.12);
            box-shadow:0 0 20px rgba(96,165,250,.25);
        }

        .btn{

            width:100%;
            padding:15px;

            margin-top:10px;

            border:none;

            border-radius:14px;

            background:linear-gradient(135deg,#4f46e5,#06b6d4);

            color:white;

            font-size:16px;

            font-weight:600;

            cursor:pointer;

            transition:.3s;
        }

        .btn:hover{

            transform:translateY(-3px);

            box-shadow:0 15px 35px rgba(79,70,229,.45);

        }

        .error{

            background:#dc2626;

            color:white;

            padding:14px;

            border-radius:10px;

            margin-bottom:20px;

            text-align:center;

        }

        .footer{

            text-align:center;

            color:#94a3b8;

            margin-top:25px;

            font-size:13px;

        }

        @media(max-width:500px){

            .login-box{

                width:92%;

                padding:30px;

            }

            h2{

                font-size:28px;

            }

        }

    </style>
</head>

<body>

<div class="login-box">

    <div class="logo">
        AI
    </div>

    <h2>Admin Login</h2>

    <div class="subtitle">
        AI Knowledge Base Administration
    </div>

    @if(session('error'))
        <div class="error">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}">

        @csrf

        <div class="input-group">

            <label>Email Address</label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email">

        </div>

        <div class="input-group">

            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Enter your password">

        </div>

        <button class="btn">
            Login
        </button>

    </form>

    <div class="footer">
        © {{ date('Y') }} AI RAG Assistant
    </div>

</div>

</body>
</html>