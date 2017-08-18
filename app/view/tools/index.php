<div class="row">
    <div class="col-md-12">
        <?php
            $logs = $this->Get('logs');
            if(count($logs) > 0){
                for($i = count($logs)-1; $i >= 0; $i--) {
                    echo $logs[$i] . '<hr>';
                }
            }
            else{
                echo '<hr>Лог пуст<hr>';
            }
        ?>
    </div>
</div>