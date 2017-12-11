<?php
    use \app\core\Config;
    use \app\helpers\Html;
?>

<div id="perezvonok_widget" class="window_wrapper">
    <div class="window_bg"></div>
    <div class="window_panel window_type_1">
        <div class="btn_exit">
            <i class="fa fa-times"></i>
        </div>
        <div class="logo">
            <img src="<?php echo Config::URL_ROOT . $this->Get('logo'); ?>">
        </div>
        <div class="panels_list">
            <?php if($this->Get('isWork')) { ?>
                <div id="instant_call" class="panels active_panel">
                    <form id="instant_call_form">
                        <input type="hidden" name="action" value="<?php echo Config::URL_ROOT . Html::ActionPath('widget', 'instantcall');?>">
                        <input type="hidden" name="hash" value="<?php echo $this->Get('siteHash');?>">
                        <div class="worktime">
                            <div class="title_block"><?php echo $this->Get('worktimeTitle');?></div>
                        </div>
                        <div class="phone_block">
                            <div class="data_control">
                                <input name="nn_Phone:clientPhone" class="template_control" value="+7 (___) ___-__-__" style="padding:0 8px !important;" data-e="blur">
                                <div class="error_box"></div>
                            </div>
                            <button class="trigger dataWorker__set" data-f="instant_call_form" data-p="select_branch_instant_call" style="margin-left:5px"><?php echo $this->Get('btnText');?></button>
                        </div>

                        <div class="switch trigger active" data-p="call_on_time">
                            <i class="fa fa-clock-o"></i>
                            <div class="title">
                                <a>Выбрать удобное время для звонка</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="timer" class="panels">
                    <div class="title_block">
                        Мы уже звоним!
                    </div>
                    <div class="timer">
                        <p>00:<?php echo $this->Get('timerValue');?></p>
                    </div>

                    <div style="width: 15%;margin: 20px auto 0;"><img src="<?php echo Config::URL_IMG . '/call_now.gif'?>" style="width: 100%;"></div>
                </div>

                <div id="rating" class="panels">
                    <div class="title_block">
                        Помогите нам стать лучше!
                    </div>

                    <div style="width: 15%;margin: 20px auto 0;"><img src="<?php echo Config::URL_IMG . '/manager.png'?>" style="width: 100%;"></div>

                    <div class="manager_evaluation">
                        <form id="rating_form">
                            <input type="hidden" name="action" value="<?php echo Config::URL_ROOT . Html::ActionPath('widget', 'managerevaluation');?>">
                            <input type="hidden" name="id">
                            <input type="hidden" name="mark" class="mark">
                            <div class="msg" style="font-size:16px!important;">Оцените ответ менеджера</div>
                            <ul class="stars_list">
                                <li data-vote="1" data-e="click,mouseenter,mouseleave" data-f="rating_form" class="dataWorker__set"><i class="fa fa-star"></i></li>
                                <li data-vote="2" data-e="click,mouseenter,mouseleave" data-f="rating_form" class="dataWorker__set"><i class="fa fa-star"></i></li>
                                <li data-vote="3" data-e="click,mouseenter,mouseleave" data-f="rating_form" class="dataWorker__set"><i class="fa fa-star"></i></li>
                                <li data-vote="4" data-e="click,mouseenter,mouseleave" data-f="rating_form" class="dataWorker__set"><i class="fa fa-star"></i></li>
                                <li data-vote="5" data-e="click,mouseenter,mouseleave" data-f="rating_form" class="dataWorker__set"><i class="fa fa-star"></i></li>
                            </ul>
                        </form>
                    </div>
                </div >

                <div id="select_branch_instant_call" class="panels">
                    <form id="select_branch_instant_call_form">
                        <h2>Выбор филиала</h2>
                        <select name="branch">
                            <?php
                            $branches = $this->Get('workBranches');
                            foreach($branches as $branch){
                                foreach($branch as $key => $item){
                                    echo '<option value="' . $key . '">'. $item['name'] . '('.$item['time'].')'  .'</option>';
                                }
                            }
                            ?>
                        </select><br>
                        <button class="trigger prev">Назад</button>
                        <button class="ajax_btn dataWorker__add" data-f="select_branch_instant_call_form">Отправить</button>
                    </form>
                </div>

            <?php } ?>

            <div id="call_on_time" class="panels<?php echo !$this->Get('isWork') ? ' active_panel' : ''; ?>">
                <form id="call_on_time_form">
                    <input type="hidden" name="action" value="<?php echo Config::URL_ROOT . Html::ActionPath('widget', 'calltime');?>">
                    <input type="hidden" name="hash" value="<?php echo $this->Get('siteHash');?>">


                    <div class="title_block">
                        <?php
                            if($this->Get('isWork')){
                                echo 'Выберите удобное время:';
                            }
                            else{
                                echo $this->Get('notWorktimeTitle');
                            }
                        ?>
                    </div>
                    <div class="time_block">
                        <select name="day" class="day_selector" data-e="change">
                            <?php
                            $days = $this->Get('dayList');

                            foreach($days as $item){
                                echo '<option value="'.$item['val'].'">'. $item['title'] .'</option>';
                            }
                            ?>
                        </select>
                        <span style="color:#333333">в</span>
                        <select name="time" class="time_selector">
                            <?php
                            $timeLine = $this->Get('timeList');
                            foreach($timeLine as $item){
                                echo '<option value="'.$item['val'].'">'. $item['title'] .'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="phone_block">
                        <div class="data_control">
                            <input name="nn_Phone:clientPhone" class="template_control" value="+7 (___) ___-__-__" style="padding:0 8px !important;" data-e="blur">
                            <button class="trigger dataWorker__set" data-f="call_on_time_form" data-p="select_branch" style="margin-left:5px !important;"><?php echo $this->Get('btnText');?></button>
                            <div class="error_box"></div>
                        </div>

                    </div>
                    <div class="result_block"></div>

                    <?php if($this->Get('isWork')) { ?>

                        <div class="switch trigger" data-p="instant_call">
                            <i class="fa fa-phone"></i>
                            <div class="title">
                                <a>Либо мы позвоним прямо сейчас</a>
                            </div>
                        </div>

                    <?php } ?>

                </form>
            </div>

            <div id="select_branch" class="panels">
                <form id="select_branch_form">
                    <h2>Выбор филиала</h2>
                    <select name="branch">
                        <?php

                        $branches = $this->Get('workBranches');
                        foreach($branches as $branch){
                            foreach($branch as $key => $item){
                                echo '<option value="' . $key . '">'. $item['name'] . '('.$item['time'].')'  .'</option>';
                            }
                        }

                        $branches = $this->Get('notWorkBranches');
                        foreach($branches as $branch){
                            foreach($branch as $key => $item){
                                echo '<option value="' . $key . '">'. $item['name'] . '('.$item['time'].')'  .'</option>';
                            }
                        }

                        ?>
                    </select><br>
                    <button class="trigger<?php echo $this->Get('isWork') ? ' prev' : ' active';?>"<?php echo !$this->Get('isWork') ? 'data-p="call_on_time"' : '';?>>Назад</button>
                    <button class="ajax_btn dataWorker__add" data-f="select_branch_form">Отправить</button>
                </form>
            </div>

            <div id="preloder" class="panels">
                <div style="width: 15%;margin: 20px auto 0;"><img src="<?php echo Config::URL_IMG . '/preloader.gif'?>" style="width: 100%;"></div>
            </div>

            <div id="result_success" class="panels ">
                <div class="title_block">Операция завершенна успешно!</div>
                <div style="width: 15%;margin: 20px auto 0;"><img src="<?php echo Config::URL_IMG . '/success.png'?>" style="width: 100%;"></div>
            </div>
        </div>
        <div class="footer">
            <label><img src="https://perezvonok.ru/checked.jpg"><a class="pz_agr_link" href="https://perezvonok.ru/?a=agreement" rel="nofollow" target="_blank">Я согласен на обработку персональных данных</a></label>
            <a class="copyright_link" href="https://perezvonok.ru/" target="blank">Сервис обратного звонка PereZvonok</a>
        </div>
    </div>
</div>