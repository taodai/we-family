
<script src="https://code.jquery.com/jquery-1.11.3.js"></script>
<script>
// zy_init();
// window.uexOnload=function(type){
//     if(!type){
//     }
//     commentList();
// }
$(document).ready(function(){
    commentList();
});
function commentList(){
    
    $.ajax({
        type:"POST",
        url:'http://www.haqile.net/app/handle/wiki-data',
        //url:'http://hql.com/app/handle/wiki-data',
        // data:{"ispost":1},
        dataType:'json',
        async:false,
        error:function(XMLResponse){
            //alert(22);
            alert(XMLResponse.responseText);
            return false;
        },
        success:function(data){
            
            if(data){
               // alert(data);
            }
                
            
        }
        
    });
}
</script>
<div class="app-default-index">
    <h1><?= $this->context->action->uniqueId ?></h1>
    <p>
        This is the view content for action "<?= $this->context->action->id ?>".
        The action belongs to the controller "<?= get_class($this->context) ?>"
        in the "<?= $this->context->module->id ?>" module.
    </p>
    <p>
        You may customize this page by editing the following file:<br>
        <code><?= __FILE__ ?></code>
    </p>
</div>
<script src='http://static.polyv.net/file/polyvplayer_v2.0.min.js'></script>
<div id='plv_3a01ed11bf85d67d7af7862e1330a76d_3'></div>
<script>
var player = polyvObject('#plv_3a01ed11bf85d67d7af7862e1330a76d_3').videoPlayer({
    //'width':'100%',
    'height':'600',
    'vid' : '3a01ed11bf85d67d7af7862e1330a76d_3'
});
</script>
