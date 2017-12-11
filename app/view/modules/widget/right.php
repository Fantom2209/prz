<?php
use \app\core\Config;
use \app\helpers\Html;
?>

<div id="perezvonok_widget" class="window_wrapper">
    <div class="window_bg"></div>
    <div class="window_panel window_type_2">
        <div class="btn_exit"></div>
        <div class="icons_panel">
            <ul class="icon_list">
                <?php if($this->Get('isWork')) { ?>
                <li class="icon_list_item">
                    <a class="item_link_call trigger active" data-p="instant_call">
                        <div class=""><i class="fa fa-phone"></i></div>
                        <span>Позвоним<br>сейчас</span>
                    </a>
                </li>
                <?php } ?>
                <li class="icon_list_item">
                    <a class="item_link_delay trigger<?php echo (!$this->Get('isWork') ? ' active' :'');?>" data-p="call_on_time">
                        <div class=""><i class="fa fa-clock-o"></i></div>
                        <span>Позвоним<br>позже</span>
                    </a>
                </li>
                <?php if($this->Get('emailPanel')) { ?>
                    <li class="icon_list_item">
                        <a class="item_link_application trigger" data-p="send_email">
                            <div><i class="fa fa-envelope"></i></div>
                            <span>Ответим<br>по почте</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($this->Get('isVK')) { ?>
                <li class="icon_list_item">
                    <a target="_blank" class="item_link_consultant_vk" onclick="window.open('<?php echo $this->Get('activeVK'); ?>', 'Консультант', 'height=600,width=980,menubar=no,location=yes,resizable=yes,scrollbars=yes,status=no')">
                        <div><i class="fa fa-vk"></i></div>
                        <span>Ответим<br>Вконтакте</span>
                    </a>
                </li>
                <?php } ?>
            </ul>
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

                        <div class="title_block title_widget"><?php echo $this->Get('worktimeTitle');?></div>
                        <div class="text_block"><?php echo $this->Get('worktimeText');?></div>

                        <div class="phone_block">
                            <div class="data_control">
                                <input name="nn_Phone:clientPhone" class="template_control" value="+7 (___) ___-__-__" style="padding:0 8px !important;" data-e="blur">
                                <div class="error_box"></div>
                            </div>
                            <button class="trigger dataWorker__set" data-f="instant_call_form" data-p="select_branch_instant_call" style="margin-left:5px"><?php echo $this->Get('btnText');?></button>
                            <br>
                            <label class="assent">
                                <img src="https://perezvonok.ru/checked.jpg"><a class="copyright_link" href="https://perezvonok.ru/?a=agreement" rel="nofollow" target="_blank">Я согласен на обработку персональных данных</a>
                            </label>
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

                    <div style="width: 70%;margin: 48px auto 0;"><img src="<?php echo Config::URL_IMG . '/call_now.gif'?>" style="width: 100%;"></div>
                </div>

                <div id="rating" class="panels">
                    <div class="title_block">
                        Помогите нам стать лучше!
                    </div>

                    <div style="width: 70%;margin: 48px auto 0;"><img src="<?php echo Config::URL_IMG . '/manager.png'?>" style="width: 100%;"></div>

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
                                        echo '<option value="' . $key . '">'. $item['name'] . '('.$item['time'].')' .'</option>';
                                    }
                                }
                            ?>
                        </select><br>
                        <button class="trigger prev">Назад</button>
                        <button class="ajax_btn dataWorker__add" data-f="select_branch_instant_call_form">Отправить</button>
                    </form>
                </div>

            <?php } ?>

            <div id="call_on_time" class="panels<?php echo (!$this->Get('isWork') ? ' active_panel' :'');?>">
                <form id="call_on_time_form">
                    <input type="hidden" name="action" value="<?php echo Config::URL_ROOT . Html::ActionPath('widget', 'calltime');?>">
                    <input type="hidden" name="hash" value="<?php echo $this->Get('siteHash');?>">
                    <div class="title_block title_widget">
                        <?php
                            if($this->Get('isWork')){
                                echo 'Выберите удобное время:';
                            }
                            else{
                                echo $this->Get('notWorktimeTitle');
                            }
                        ?>
                    </div>
                    <div class="text_block">
                        <?php
                            if(!$this->Get('isWork')){
                                $this->Get('notWorktimeText');
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

                            foreach($timeLine as $key => $item){
                                echo '<option value="'.$item.'">'. $key .'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="phone_block">
                        <div class="data_control">
                            <input name="nn_Phone:clientPhone" class="template_control" value="+7 (___) ___-__-__" style="padding:0 8px !important;" data-e="blur">
                            <div class="error_box"></div>
                        </div>
                        <button class="trigger dataWorker__set" data-f="call_on_time_form" data-p="select_branch" style="margin-left:5px !important;"><?php echo $this->Get('btnText');?></button>
                        <br>
                        <label class="assent">
                            <img src="https://perezvonok.ru/checked.jpg"><a class="copyright_link" href="https://perezvonok.ru/?a=agreement" rel="nofollow" target="_blank">Я согласен на обработку персональных данных</a>
                        </label>
                    </div>

                </form>
            </div>

            <div id="result_success" class="panels ">
                <div class="title_block">Операция завершенна успешно!</div>
                <div style="width: 70%;margin: 48px auto 0;"><img src="<?php echo Config::URL_IMG . '/success.png'?>" style="width: 100%;"></div>
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

            <?php if($this->Get('emailPanel')) { ?>

                <div id="send_email" class="panels">
                    <form id="send_email_form">
                        <input type="hidden" name="action" value="<?php echo Config::URL_ROOT . Html::ActionPath('widget', 'sendemail');?>">
                        <input type="hidden" name="hash" value="<?php echo $this->Get('siteHash');?>">

                        <div class="title_block">Напишите нам!</div>
                        <div class="text_block">Мы обязательно ответим в самое ближайшее время</div>

                        <div class="data_control">
                            <textarea placeholder="Сообщение" name="nn_Multiline:client_comment" data-e="blur"></textarea>
                            <div class="error_box"></div>
                        </div>

                        <div class="data_control">
                            <input placeholder="Имя" type="text" name="nn_String:client_name" data-e="blur">
                            <div class="error_box"></div>
                        </div>

                        <div class="data_control">
                            <input placeholder="Email" type="text" name="nn_Email:client_email" data-e="blur">
                            <div class="error_box"></div>
                        </div>

                        <button class="trigger dataWorker__set" data-f="send_email_form" data-p="select_branch_send_email" style="margin-left:5px !important;">ЖДУ ОТВЕТА</button>
                        <br>
                        <label class="assent">
                            <img src="https://perezvonok.ru/checked.jpg"><a class="copyright_link" href="https://perezvonok.ru/?a=agreement" rel="nofollow" target="_blank">Я согласен на обработку персональных данных</a>
                        </label>
                    </form>

                </div>

                <div id="select_branch_send_email" class="panels">
                    <form id="select_branch_send_email_form">
                        <h2>Выбор филиала</h2>
                        <select name="branch">
                            <?php
                                $branches = $this->Get('emailBranches');
                                foreach($branches as $branch){
                                    foreach($branch as $key => $item){
                                        echo '<option value="' . $key . '">'. $item .'</option>';
                                    }
                                }
                            ?>
                        </select><br>
                        <button class="trigger<?php echo $this->Get('isWork') ? ' prev' : ' active';?>"<?php echo !$this->Get('isWork') ? 'data-p="call_on_time"' : '';?>>Назад</button>
                        <button class="ajax_btn dataWorker__add" data-f="select_branch_send_email_form">Отправить</button>
                    </form>
                </div>

            <?php } ?>

            <div id="preloder" class="panels">
                <div style="width: 70%;margin: 48px auto 0;"><img src="<?php echo Config::URL_IMG . '/preloader.gif'?>" style="width: 100%;"></div>
            </div>

        </div>
        <div class="footer">
            <a class="copyright_link" href="https://perezvonok.ru/" target="blank">Сервис обратного звонка PereZvonok</a>
        </div>
    </div>
</div>