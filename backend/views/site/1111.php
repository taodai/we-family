            <?php
                foreach($menus as $key=>$value){
                    if(!isset($modules) || in_array($value['sm_menu_title'],$modules)){
            ?>
                <div title="<?php echo $value['sm_menu_title'];?>" iconCls="<?php echo $value['sm_menu_icon'];?>"  class="t_center" style="padding-left:10px;">
                <?php
                    if(isset($value['children']) && !empty($value['children'])){ 
                    foreach($value['children'] as $k => $v){
                        if($uid==1 || in_array($v['sm_menu_url'], $permissions)){
                ?>
                    <p class="menubutton" style="background:url(<?php echo base_url().'theme/easyui131/themes/icons/'.$v['sm_menu_png'];?>) no-repeat left center;">
                        <a href="javascript:void(0);" style="margin:10px;" onclick="addMaintab('<?php echo $v["sm_menu_title"]?>','<?php echo site_url($v["sm_menu_url"]); ?>','<?php echo $v['sm_menu_icon'];?>');"><?php echo $v["sm_menu_title"]?></a>
                    </p> 
                <?php
                  } } }
                ?>
                </div>
            <?php 
               } }
            ?>   

            <?php
                foreach ($notices as $notice) {
            ?>
            <div title="<?php echo $notice['n_title'];?>" style="overflow:auto;padding:10px;">
                <h3 style="color:#0099FF;text-align:center;"><?php echo $notice['n_title'];?></h3>
                <?php echo $notice['n_content'];?>
            </div>
            <?php
                }
            ?>