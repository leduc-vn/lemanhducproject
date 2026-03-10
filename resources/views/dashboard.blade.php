<!DOCTYPE html>

<html>
<head>
    <title>AI Dashboard</title>

    ```
    <style>
        body{
            font-family: Arial;
            background:#f4f4f4;
            text-align:center;
            margin-top:40px;
        }

        .container{
            width:800px;
            margin:auto;
        }

        .card{
            display:inline-block;
            width:250px;
            margin:20px;
            padding:30px;
            background:white;
            border-radius:10px;
            box-shadow:0 3px 10px rgba(0,0,0,0.1);
        }

        .card h2{
            margin:0;
            font-size:40px;
            color:#007bff;
        }

        .card p{
            font-size:18px;
            color:#555;
        }

        a{
            display:inline-block;
            margin-top:30px;
            text-decoration:none;
            background:#007bff;
            color:white;
            padding:10px 20px;
            border-radius:6px;
        }

        a:hover{
            background:#0056b3;
        }
    </style>
    ```

</head>

<body>

<div class="container">

    ```
    <h1>AI Face Detection Dashboard</h1>

    <div class="card">
        <h2>{{ $totalImages }}</h2>
        <p>Total Images</p>
    </div>

    <div class="card">
        <h2>{{ $totalFaces }}</h2>
        <p>Total Faces Detected</p>
    </div>

    <br>

    <a href="/upload">Upload Image</a>
    <a href="/history">View History</a>
    ```

</div>

</body>
</html>
