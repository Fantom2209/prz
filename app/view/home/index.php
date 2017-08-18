<h1><?php echo $this->Get('content');?></h1>

<div class="row tblock" id="block1">
    <div class="col-md-12">
        Блок 1
    </div>
</div>

<div class="row tblock" id="block2">
    <div class="col-md-6">
        Блок 2.1
    </div>
    <div class="col-md-6">
        Блок 2.2
    </div>
</div>

<div class="row tblock" id="block8">
    <div class="col-md-4">
        Блок 3.1
    </div>
    <div class="col-md-4">
        Блок 3.2
    </div>
    <div class="col-md-4">
        Блок 3.3
    </div>
</div>
<div class="row tblock" id="block4">
    <div class="col-md-12">
        Блок 4
    </div>
</div>

<div class="row tblock" id="block5">
    <div class="col-md-12">
        Блок 5
    </div>
</div>

<div id="modalRegistration" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Создания учетной записи</h4>
            </div>

            <div class="modal-body">
                <form action="<?php echo \app\helpers\Html::ActionPath('Account', 'Create')?>" method="POST" class="ajax-form">
                    <div class="form-group">
                        <label>Email:</label>
                        <input class="form-control" type="text" name="UserData[Email:email]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Телефон:</label>
                        <input class="form-control" type="text" name="UserData[Phone:phone]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>ФИО:</label>
                        <input class="form-control" type="text" name="UserData[DefaultText:name]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Пароль:</label>
                        <input class="form-control" type="password" name="UserData[Password:password]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Пароль еще раз:</label>
                        <input class="form-control" type="password" name="UserData[confirmPass]">
                        <div class="error-box"></div>
                    </div>
                    <button class="btn btn-primary">Регистрация</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modalAuth" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Вход в систему</h4>
            </div>

            <div class="modal-body">
                <form action="<?php echo \app\helpers\Html::ActionPath('Account', 'Login')?>" method="POST" class="ajax-form recaptcha">
                    <div class="form-group">
                        <label>Email:</label>
                        <input class="form-control" type="text" name="UserData[Email:email]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Password:</label>
                        <input class="form-control" type="password" name="UserData[Password:password]">
                        <div class="error-box"></div>
                    </div>

                    <div class="g-recaptcha" data-sitekey="<?php echo \app\core\Config::RECAPTCHA_CODE; ?>"></div>
                    <div class="error-box recaptcha"></div>

                    <button class="btn btn-primary">Войти</button>
                </form>
            </div>
        </div>
    </div>
</div>