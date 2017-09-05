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

                        <div class="panel-group" id="accordion">

                            <?php
                            $i = 0;
                            foreach ($this->Get('Properties') as $title => $group){
                                $fieldsHtml = '';
                                foreach($group as $item){
                                    if($item['system'] == '1' && !($this->Get('IsSuperUser') || $this->Get('IsAdmin'))){
                                        var_dump($this->Get('IsSuperUser'));
                                        continue;
                                    }

                                    $snippet = 'Field'. (!empty($item['dop']['snippet']) ? $item['dop']['snippet'] : $item['typeName']);
                                    $validator = (!empty($item['dop']['validator']) ? $item['dop']['validator'] : $item['typeName']).':';

                                    switch($item['typeName']){
                                        case 'Select':
                                            $val = !empty($item['dop']['value']) ? $item['dop']['value'] : '';
                                            $elem = explode('|',$val);
                                            $options = '';
                                            foreach($elem as $val){
                                                $options .= '<option>'.$val.'</option>';
                                            }
                                            $data = array(
                                                $item['name'], $item['id'], $options
                                            );
                                            break;
                                        case 'Number':
                                            $max = !empty($item['dop']['max']) ? $item['dop']['max'] : 255;
                                            $min = !empty($item['dop']['min']) ? $item['dop']['min'] : 0;
                                            $val = !empty($item['dop']['value']) ? $item['dop']['value'] : '';
                                            $step = !empty($item['dop']['step']) ? $item['dop']['step'] : '';
                                            $data = array(
                                                $item['name'], $validator, $item['id'], $min, $max, $step, $val
                                            );
                                            break;
                                        default:
                                            $data = array(
                                                $item['name'], $validator, $item['id']
                                            );
                                    }

                                    /*if($item['typeName'] == 'Select'){
                                        $val = !empty($item['dop']['value']) ? $item['dop']['value'] : '';
                                        $elem = explode('|',$val);
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
                                            $item['name'], $validator, $item['id']
                                        );
                                    }*/

                                    $fieldsHtml .= Html::Snipet($snippet,$data);
                                }

                                if(!empty($fieldsHtml)){
                                    echo Html::Snipet('AccordionPanel',array(
                                        'accordion', 'accordion-panel-' . ++$i, $title, $i == 1 ? ' in' : '', $fieldsHtml
                                    ));
                                }
                            }
                            ?>

                        </div>



                        <?php
                        /*$group = '';
                        foreach ($this->Get('Properties') as $item){

                            if($item['system'] == '1' && !($this->Get('IsSuperUser') || $this->Get('IsAdmin'))){
                                continue;
                            }

                            if($group != $item['group']){
                                $group = $item['group'];
                                echo '<h4 class="text-center text-primary">'.$group.'</h4><hr>';
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
                        }*/
                        ?>

                        <button class="btn btn-primary">Сохранить</button>
                    </form>

                <?php } ?>

            </div>
        </div>
    </div>
</div>