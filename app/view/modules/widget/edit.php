<?php use \app\helpers\Html; ?>

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