{header} <!-- Header -->
{sidebar} <!-- Sidebar -->
    <!-- Content -->
    <div class="content">

        <div class="c">

            <h5 class="content-title left">
                <?php foreach($urls as $url): ?>
                    <a href="#!" class="breadcrumb"><?=ucwords($url)?></a>
                <?php endforeach; ?>
            </h5>
            




            <h5 class="content-title right"><i class="material-icons right">apps</i>گزارش</h5>

            <div class="row clear-both">
                <div class="col m5 s12 latest-episodes">
                <a href="<?=base_url('dashboard')?>/alerts" class="title col s12 m12 btn right waves-effect cyan lighten-1">مشاهده همه گزارش  ها</a>
                <div class="progress_container">

                    <div class="progress">
                        <div class="indeterminate"></div>
                    </div>
                </div>
                <div class="alerts">
                    <table class="highlight">
                        <thead>
                            <tr>
                                <th>نام</th>
                                <th>نوع</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>

                        <tbody>
                            {alerts}
                            <tr>
                                <td><a href="{link}">{text}</a></td>
                                <td>{type}</td>
                                <td>
                                    <a href="{edit_link}" class="latest-comments-action edit">مشاهده</a>
                                </td>
                            </tr>
                            {/alerts}
                        </tbody>
                    </table>
                </div>

                </div>
                <div class="col m7 s12">
                    <div class="card">
                        <div class="card-image">
                            <img src="<?=base_url()?>public/img/backgrounds/catgirl.jpg">
                            <span class="card-title">مشاهده گزارش</span>
                        </div>

                        <div class="card-content black-text upload_form">

                                <div class="row">

    
<?php 
$empty_status = null;
if($alert[0]->type == "article") { // Article
    $article = $this->post_model->get_article_by_id($alert[0]->type_id);
    if(!empty($article)) {
        $alert_data = array(
            'text' => substr($alert[0]->text, 0,30).'...',
            'type_id' => $alert[0]->type_id,
            'type' => 'پست',
            'original_type' => 'article',
            'link' => base_url($article[0]->url_slug.'/'.$article[0]->id),
            'edit_link' => base_url('dashboard/alert/'.$alert[0]->id),
            'created_at' => $alert[0]->created_at,
            'approved' => $article[0]->approved,
        );
    } else {
        $empty_status = true;
    }
} else { // Comment
    $comment = $this->comments_model->get_comment_by_id($alert[0]->type_id);
    $article = $this->post_model->get_article_by_id($comment[0]->comment_post_id);
    if(!empty($comment) && !empty($article)) {
        $alert_data = array(
            'text' => substr($alert[0]->text, 0,30).'...',
            'type_id' => $alert[0]->type_id,
            'type' => 'نظر',
            'original_type' => 'comment',
            'link' => base_url($article[0]->url_slug.'/'.$article[0]->id),
            'edit_link' => base_url('dashboard/alert/'.$alert[0]->id),
            'created_at' => $alert[0]->created_at,
            'approved' => $comment[0]->comment_approved,
        );
    } else {
        $empty_status = true;
    }
}
?>
<?php if(!$empty_status): ?>
                                    <form class="col s12" id="upload_form"> 

                                        <input type="hidden" name="<?=$csrf_name?>" value="<?=$csrf_hash?>">
                                        <input type="hidden" id="type_id" name="id" value="<?=$alert_data['type_id']?>">
                                        <div class="input-field col s12 m12 right">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <input disabled value="<?=$alert_data['type']?>" name="type" id="icon_prefix" type="text" placeholder="نوع" class="white-text">
                                        </div>

                                        <div class="input-field col s12 m12  right">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <textarea disabled class="white-text materialize-textarea" name="comment_text" placeholder="متن نظر"><?=$alert_data['text']?></textarea>
                                        </div>
                                        <?php if($alert_data['type'] == 'نظر'): ?>

                                            <?php if($alert_data['approved'] == 'yes'): ?>
                                            <button id="free_content" disabled="disabled" class="right waves-effect waves-light btn green lighten-1">آزاد کردن نظر</button>
                                            <button id="close_content" class="right waves-effect waves-light btn orange lighten-1">مسدود کردن نظر</button>
                                            <?php else: ?>
                                            <button id="free_content" class="right waves-effect waves-light btn green lighten-1">آزاد کردن نظر</button>
                                            <button id="close_content" disabled="disabled" class="right waves-effect waves-light btn orange lighten-1">مسدود کردن نظر</button>
                                            <?php endif; ?>

                                        <?php else: ?>
                                            <?php if($alert_data['approved'] == 'yes'): ?>
                                            <button id="free_content" disabled="disabled" class="right waves-effect waves-light btn green lighten-1">آزاد کردن پست</button>
                                            <button id="close_content" class="right waves-effect waves-light btn orange lighten-1">مسدود کردن پست</button>
                                            <?php else: ?>
                                            <button id="free_content" class="right waves-effect waves-light btn green lighten-1">آزاد کردن پست</button>
                                            <button id="close_content" disabled="disabled" class="right waves-effect waves-light btn orange lighten-1">مسدود کردن پست</button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </form>

<?php else: ?>
    <h6>گزارش مورد نظر پیدا نشد</h6>
<?php endif; ?>


                                </div>
        
                        </div>
                    </div>
                </div>
            </div>
    </div>



<script>
$(document).ready(function(){


    $('#close_content').click(function(e){
        $('.latest-episodes').addClass('reloading');
        var form_data = new FormData(document.getElementById('upload_form'));
        var type_id = $("#type_id").val();
        e.preventDefault();
        $.ajax({
            url: "<?=base_url()?>dashboard/close_content/<?=$alert_data['type_id']?>/<?=$alert_data['original_type']?>",
            type: 'post',
            data: form_data,
            processData: false,
            contentType: false,
            cache: false,
            asnyc: false,
            success:function(response) {
                // $("#upload_form").find("input[type=text], textarea").val("");
                var response = response;
                console.log(response);
                response = response.replace(/\\n/g, "\\n")  
                               .replace(/\\'/g, "\\'")
                               .replace(/\\"/g, '\\"')
                               .replace(/\\&/g, "\\&")
                               .replace(/\\r/g, "\\r")
                               .replace(/\\t/g, "\\t")
                               .replace(/\\b/g, "\\b")
                               .replace(/\\f/g, "\\f");
                response = response.replace(/[\u0000-\u0019]+/g,"");
                var json_decoded = JSON.parse(response);
                json_data = json_decoded.data;
                json_data = json_data.replace(/<[^>]*>?/gm, '');
                if(json_decoded.status == 1) {
                    if(json_decoded.redirect !== '') {
                        var delay = 1000; 
                        setTimeout(function(){ window.location = json_decoded.redirect; }, delay);
                    }
                    toasts = json_data.split('\n').filter(Boolean);
                    toasts.forEach(create_success_toast);
                } else {
                    toasts = json_data.split('\n').filter(Boolean);
                    toasts.forEach(create_toast);

                }

            }
        });
        $.ajax({
            url: "<?=base_url()?>dashboard/ajax_latest_alerts/",
            type: 'get',
            success:function(response) {
                $('.alerts').html(response);
                $('.latest-episodes').removeClass('reloading');
            }
        });

    });    
    $('#free_content').click(function(e){
        $('.latest-episodes').addClass('reloading');
        var form_data = new FormData(document.getElementById('upload_form'));
        var type_id = $("#type_id").val();
        e.preventDefault();
        $.ajax({
            url: "<?=base_url()?>dashboard/free_content/<?=$alert_data['type_id']?>/<?=$alert_data['original_type']?>",
            type: 'post',
            data: form_data,
            processData: false,
            contentType: false,
            cache: false,
            asnyc: false,
            success:function(response) {
                // $("#upload_form").find("input[type=text], textarea").val("");
                var response = response;
                console.log(response);
                response = response.replace(/\\n/g, "\\n")  
                               .replace(/\\'/g, "\\'")
                               .replace(/\\"/g, '\\"')
                               .replace(/\\&/g, "\\&")
                               .replace(/\\r/g, "\\r")
                               .replace(/\\t/g, "\\t")
                               .replace(/\\b/g, "\\b")
                               .replace(/\\f/g, "\\f");
                response = response.replace(/[\u0000-\u0019]+/g,"");
                var json_decoded = JSON.parse(response);
                json_data = json_decoded.data;
                json_data = json_data.replace(/<[^>]*>?/gm, '');
                if(json_decoded.status == 1) {
                    if(json_decoded.redirect !== '') {
                        var delay = 1000; 
                        setTimeout(function(){ window.location = json_decoded.redirect; }, delay);
                    }
                    toasts = json_data.split('\n').filter(Boolean);
                    toasts.forEach(create_success_toast);
                } else {
                    toasts = json_data.split('\n').filter(Boolean);
                    toasts.forEach(create_toast);

                }

            }
        });
        $.ajax({
            url: "<?=base_url()?>dashboard/ajax_latest_alerts/",
            type: 'get',
            success:function(response) {
                $('.alerts').html(response);
                $('.latest-episodes').removeClass('reloading');
            }
        });

    });




});


function create_toast(data) {   
    M.toast({
        html: data,
        classes: 'custom_toast red lighten-1',
    });
}
function create_success_toast(data) {
    M.toast({
        html: data,
        classes: 'custom_toast green lighten-1',
    });
}

</script>    


{footer}