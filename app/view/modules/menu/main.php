<?php use \app\helpers\Html; ?>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><?php echo $this->Get('brand');?></a>
        </div>

        <div class="collapse navbar-collapse" id="main-navbar-collapse">
            <ul class="nav navbar-nav">

                <?php
                    $items = $this->Get('items');
                    foreach($items['root'] as $item){
                        $isDropdown = isset($items[$item['id']]);

                        echo '<li'.($isDropdown ? ' class="dropdown"':'').'>';

                        if($isDropdown){
                            echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$item['title'].' <span class="caret"></span></a><ul class="dropdown-menu">';
                            foreach($items[$item['id']] as $subItem) {
                                echo '<li><a href="' . str_replace('{{root_url}}', \app\core\Config::URL_ROOT .'/', $subItem['url']) . '">' . $subItem['title'] . '</a></li>';
                            }
                            echo '</ul>';
                        }
                        else{
                            echo '<a href="' . str_replace('{{root_url}}', \app\core\Config::URL_ROOT .'/', $item['url']) . '">' . $item['title'] . '</a>';
                        }
                        echo '</li>';
                    }
                ?>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <?php
                    if($this->Get('Type') == 'Client'){
                        echo '<li><a class="paid">Оплачен до: ' . $this->Get('Paid') . '</a></li>';
                    }
                ?>

                <li class="dropdown">
                    <?php
                        $isAuth = $this->Get('IsAuth');
                        $isSuperUser = $this->Get('IsSuperUser');
                        $isAdmin = $this->Get('IsAdmin');
                        $isActive = $this->Get('IsActive');
                    ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <?php
                            if(!$isActive && $isAuth) {
                                echo '<span class="no-active">Не активирован!</span>   ';
                            }
                            echo $isAuth ? $this->Get('AccountName') . ($isSuperUser ? ' ('.$this->Get('SuperAccountName').')' : '') :'Начать работу';
                        ?>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">

                        <?php if($isAdmin || $isSuperUser) { ?>
                            <li><a href="<?php echo $isSuperUser ? Html::ActionPath('account', 'upcast') : Html::ActionPath('admin', 'index');?>">Админка</a></li>
                            <li><a href="<?php echo Html::ActionPath('home', 'index')?>">Лендинг</a></li>
                        <?php } ?>
                        <?php if(!$isAuth){?>
                            <li><a href="#modalAuth" data-toggle="modal">Авторизация</a></li>
                            <li><a href="#modalRegistration" data-toggle="modal">Регистрация</a></li>
                        <?php } else { ?>
                            <li><a href="<?php echo Html::ActionPath('profile', 'index')?>">ЛК</a></li>
                            <li><a href="<?php echo Html::ActionPath('account', 'logout')?>">Выход</a></li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>