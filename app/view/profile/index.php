<?php use \app\helpers\Html;?>

<div class="row">
    <div class="col-md-2">
        <a href="#modalAddSite" data-toggle="modal" class="btn btn-primary ajax-link" data-href="<?php echo Html::ActionPath('site','add');?>">Добавить сайт</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if($this->Get('sitesEmpty')) {
                $classModif = ' hidden';
            ?>

            <div class="panel panel-default empty-table">Пока ничего нет...</div>

        <?php } ?>

        <table class="table table-view<?php echo isset($classModif) ? $classModif :'';?>">
            <tr>
                <th>Добавлен</th>
                <th>URL</th>
                <th>Email</th>
                <th colspan="3">Операции</th>
            </tr>
            <?php foreach($this->Get('Data') as $item):
                $isActive = $item['active'] == '1';
                echo Html::Snipet('SiteLine', array(
                    $item['date_added'], $item['url'], $item['email'],
                    Html::ActionPath('site', 'update', array($item['id'])),
                    Html::ActionPath('site', 'property', array($item['id'])),
                    Html::ActionPath('site', 'delete', array($item['id'])),
                    !$isActive ? 'disable-site' : '',
                    $isActive ? Html::ActionPath('site', 'enable', array($item['id'], '0')) : Html::ActionPath('site', 'enable', array($item['id'], '1')),
                    $isActive ? 'Выключить' : 'Включить'
                ));
                ?>
            <?php endforeach;?>
        </table>

    </div>
</div>


<div id="modalAddSite" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Добавить сайт</h4>
            </div>

            <div class="modal-body">
                <form action="<?php echo \app\helpers\Html::ActionPath('site', 'add')?>" method="POST" class="ajax-form">
                    <input type="hidden" id="field_id_user" name="UserData[id_user]" value="<?php echo $this->Get('UserId');?>">
                    <div class="form-group">
                        <label>Email (администратора):</label>
                        <input class="form-control" type="text" id="field_email" name="UserData[Email:email]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>URL:</label>
                        <input class="form-control" type="text" id="field_url" name="UserData[Link:url]">
                        <div class="error-box"></div>
                    </div>
                    <button class="btn btn-primary">Добавить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modalUpdateSite" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Обновить сайт</h4>
            </div>

            <div class="modal-body">
                <form action="<?php echo \app\helpers\Html::ActionPath('site', 'update')?>" method="POST" class="ajax-form">
                    <input type="hidden" id="field_id" name="UserData[id]">
                    <div class="form-group">
                        <label>Email:</label>
                        <input class="form-control" type="text" id="field_email" name="UserData[Email:email]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>URL:</label>
                        <input class="form-control" type="text" id="field_url" name="UserData[Link:url]">
                        <div class="error-box"></div>
                    </div>
                    <button class="btn btn-primary">Обновить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modalUpdateProperties" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Свойства сайта</h4>
            </div>

            <div class="modal-body">

                <?php if($this->Get('propertiesEmpty')) { ?>

                    <p>Доступных свойств нет!!!</p>

                <?php } else { ?>


                    <form action="<?php echo \app\helpers\Html::ActionPath('site', 'property')?>" method="POST" class="ajax-form">
                        <input type="hidden" id="field_id" name="UserData[id]">
                        <?php
                        foreach ($this->Get('Properties') as $item){

                            if($item['system'] == '1' && !($this->Get('IsSuperUser') || $this->Get('IsAdmin'))){
                                continue;
                            }

                            if($item['typeName'] == 'Select'){
                                $elem = explode('|',$item['dop']);
                                $options = '';
                                foreach($elem as $val){
                                    $options .= '<option>'.$val.'</option>';
                                }
                                $data = array(
                                    $item['name'], $item['id'], $options
                                );
                            }
                            else{
                                $data = array(
                                    $item['name'], $item['typeName'].':', $item['id']
                                );
                            }

                            echo Html::Snipet('Field'.$item['typeName'],$data);
                        }
                        ?>

                        <button class="btn btn-primary">Сохранить</button>
                    </form>

                <?php } ?>

            </div>
        </div>
    </div>
</div>