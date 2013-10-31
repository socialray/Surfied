<div class="bppp-stat">

        <span class="bppp-stat-title">
            <?php bppp_title();?>
        </span>
        
        <div class="bppp-stat-block">
                <div class="bppp-bar">
                    <div class="bppp-bar-mask" style="width:<?php echo (int)(100-bppp_get_user_progression_percent());?>%"></div> 
                </div>
                
        </div>
        <span class="bppp-stat-percent"><?php echo bppp_get_user_progression_percent();?>%</span>
</div>