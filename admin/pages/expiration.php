<div class="container">

        <h1>กะรุนา จ่ายค่า server</h1>
        
            <div class="alert">
                Your account expired on <strong><?php echo $row['expiration_date']; ?></strong>.  
                กรุณาต่ออายุการสมัครเพื่อใช้บริการของเราต่อไป.
            </div><br>
            <a href="?page=login" class="button">Renew Account</a>   
       
    </div>


    <style>
       
       .container {
            margin-top: 50px;
            padding: 20px;
            
        }
        .alert {
            display: inline-block;
            padding: 15px;
            background-color: #ff4f4f;
            color: white;
            border-radius: 5px;
            margin: 10px 0;
        }
        .success {
            display: inline-block;
            padding: 15px;
            background-color: #4caf50;
            color: white;
            border-radius: 5px;
            margin: 10px 0;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 16px;
            color: white;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>