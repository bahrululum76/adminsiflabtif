<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#07276e">    
    <link rel="apple-touch-icon" href="<?= base_url() ?>assets/images/icons/icon-96x96.png">
    <meta name="apple-mobile-web-app-status-bar" content="#07276e">
    <link rel="manifest" href="<?=base_url()?>manifest.json">
    <title>SIFLABTIF - Login</title>
    <link href="<?= base_url() ?>assets/images/icons/icon-96x96.png" rel="shortcut icon" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/library/materialize/materialize.min.css">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700&display=swap" rel="stylesheet">    
    <link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/admin.min.css">

    <style>   
        label.active {
            margin:0;
        }

        section {
            height: 100%;
            position: relative;
            display: flex;
            justify-content: center;
            background: url('./assets/images/bg-login.jpg');
            background-attachment: fixed;
            background-size: cover;
        }

        #app-name {
            display:block;
            font-size: 18px;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 12px;
            margin-bottom: 32px;
            /* text-shadow: 2px 2px rgba(150,150,150,0.4); */
        }
        
        #app-det {
            display:block;
            margin-top: 12px;
            font-size: 14px;
        }
        
        .card-login {
            width:100%;
            max-width: 450px;
            height: 100%;            
            position: relative;            
            padding: 16px 24px;
            background-color: #fafafa;
            box-shadow: 0 5px 15px rgba(0,0,0,.4);
            overflow-y:auto;
            z-index:1;            
        }        

        .notifikasi-login {
            width: 100%;
            height: 0;
            transition: .2s;
            line-height: 1.5;
            position: relative;
            font-size: 16px;
            color: white;
            font-weight: 400;
            font-size: 14px;
            padding-left: .9rem;
            z-index: 100 !important;
            overflow: hidden;
            box-shadow: 0 4px 20px 0 rgba(0,0,0,.14);
        }

        .tombol-login {
            background: linear-gradient(to right, #031742, #2b6cb6);
            color: #fff;
            padding: 16px;
            font-size: 18px;
            box-shadow: 0 10px 22px -5px rgba(0, 111, 255, 0.3);
        }
        .role {
            display:flex;
            border:1px solid #cacaca;
            border-radius:5px;
            background-color:#fff;
        }

        .r-role {
            width:100%;
            text-align:center;
            padding: 12px;
            border-radius:5px;
            cursor:pointer;
        }

        .r-active {
            color:#fff;
        }
    </style>
</head>
<body>    
    <section>           
        <div class="card-login">
            <div class="center-align" style="position:relative; z-index:100">
                <span id="app-name">Sistem Informasi Laboratorium<br>Teknik Informatika</span>
                <img src="<?= base_url() ?>assets/images/icons/icon-96x96.png" alt="logo" width="80">
            </div>                                                    
            <br>
            <div class="notifikasi-login valign-wrapper">
                <span style="margin-right: 32px">ini pesan alert</span>
                <button class="right transparent btn-flat close-btn" style="padding: 0; margin: auto 0; position: absolute; right: 12px;"><i class="material-icons white-text">close</i></button>
            </div>                
            <br>
            <div class="role">
                <div class="r-role tombol-info r-active r-asisten">Asisten</div>
                <div class="r-role r-dosen">Dosen</div>
            </div>
            <br>
            <form action="<?=base_url()?>auth/login" method="post" class="form-login">                                 
            <input id="role" type="text" name="role" autocomplete="off" required hidden spellcheck="false" value="asisten">
            <div class="input-field col s12">
                <input id="username" type="text" name="username" autocomplete="off" required spellcheck="false">
                <label for="username" id="r-label">Username</label>
            </div>
            <div class="input-field col s12">
                <input id="password" type="password" name="password" autocomplete="off" required spellcheck="false">
                <label for="password">Password</label>                
            </div>
            <div class="center-align">
            <div class="preloader-wrapper small active hide loader-login">
                <div class="spinner-layer spinner-blue-only">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
                </div>
            </div>
            </div>
            <div class="input-field col s12 left-align">
            <br>
                <button class="waves-effect waves-light tombol tombol-sm tombol-login" type="submit" style="width:100%; margin:0">LOGIN</button>
            </div>
            </form>   
        </div>      
    </section>


    <p style="position: fixed;  bottom: 0; right: 32px; font-size: 12px">Photo by Émile Perron on Unsplash</p>

    <script src="<?= base_url() ?>assets/library/jquery/jquery-3.5.1.min.js"></script>
    <script src="<?= base_url() ?>assets/library/materialize/materialize.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>app.js"></script>
    <script>
        $(document).ready(function () {
            $('.r-role').click(function () {
                $('.r-role').removeClass('tombol-info').removeClass('r-active');
                $(this).addClass('tombol-info').addClass('r-active');
                if ($(this).hasClass('r-dosen')) {
                    $('#r-label').text('NIDN');
                    $('#role').val('dosen');
                } else {
                    $('#r-label').text('Username');
                    $('#role').val('asisten');
                }
            });

            $('.form-login').submit(function () {
                $('.loader-login').removeClass('hide');
                let role = $('#role').val();
                let username = $('#username').val();
                let password = $('#password').val();
                $.ajax({
                    type: 'POST',
                    url: 'auth/login',
                    data: {
                        role: role,
                        username: username,
                        password: password
                    },
                    success: function (data) {
                        let msg = data.split('|');
                        if (msg[0] === 'sukses') {
                            location.href = "<?=base_url()?>"+msg[1];
                        } else if (msg[0] === 'gagal') {
                            $('.loader-login').addClass('hide');
                            $('#password').val("");
                            showAlert('#ef5350', msg[1]);
                        } else {
                            $('.loader-login').addClass('hide');
                            $('#username').val("");
                            $('#password').val("");
                            showAlert('#42a5f5', msg[1]);
                        } 
                    }
                });

                return false;
            });            
            
            $('input, .close-btn').click(function() {
                $('.notifikasi-login').css('height', '0');
            });

        });

        function showAlert(background, message) {
            $('.notifikasi-login').css('background-color', background);
            $('.notifikasi-login span').text(message);
            setTimeout(() => {                
                $('.notifikasi-login').css('height', '54px');
            }, 200);
        }

    </script>
</body>
</html>