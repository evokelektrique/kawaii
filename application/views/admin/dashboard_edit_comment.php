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
            




            <h5 class="content-title right"><i class="material-icons right">apps</i>ویرایش  نظر</h5>

            <div class="row clear-both">
                <div class="col m5 s12 latest-episodes">
                <a href="<?=base_url('dashboard')?>/comments/" class="title col s12 m12 btn right waves-effect cyan lighten-1">مشاهده همه نظرات</a>
                <div class="progress_container">

                    <div class="progress">
                        <div class="indeterminate"></div>
                    </div>
                </div>
                <div class="comments">
                    <table class="highlight">
                        <thead>
                            <tr>
                                <th>اسم فصل</th>
                                <th>نام کامل</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>

                        <tbody>
                            {comments}
                            <tr>
                                <td><a href="<?=base_url("dashboard/edit_comment")?>/{id}">{text}</a></td>
                                <td><a href="<?=base_url("user")?>/{user_id}">{username}</a></td>
                                <td>
                                    <a href="<?=base_url("dashboard/edit_comment")?>/{id}" class="latest-comments-action edit">ویرایش</a>
                                </td>
                            </tr>
                            {/comments}
                        </tbody>
                    </table>
                </div>


                </div>
                <div class="col m7 s12">
                    <div class="card">
                        <div class="card-content black-text upload_form">
                            {comment}
                                <span class="card-title white-text">ویرایش نظر </span>
                                <ul>
                                    <li class="grey-text">آی پی ارسال کننده: <b class="white-text">{comment_ip}</b></li>
                                </ul>
                                <div class="row">



                                    <form class="col s12" id="upload_form"> 
										<input type="hidden" name="comment_id" value="{id}">
										<input type="hidden" name="<?=$csrf_name?>" value="<?=$csrf_hash?>">

                                        <div class="input-field col s12 m12  right">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <textarea class="white-text materialize-textarea" name="comment_text" placeholder="متن نظر">{comment_text}</textarea>
                                        </div>


                                        <button class="right waves-effect waves-light btn green lighten-1">ثبت</button>
                                    </form>










                                </div>
        
                            {/comment}
                        </div>
                    </div>
                </div>
            </div>
    </div>



<script>
$(document).ready(function(){


    $('#upload_form').submit(function(e){
        $('.latest-episodes').addClass('reloading');
        var form_data = new FormData(this);
        e.preventDefault();
        $.ajax({
            url: "<?=base_url()?>dashboard/update_comment/<?=$comment[0]->id?>",
            type: 'post',
            data: form_data,
            processData: false,
            contentType: false,
            cache: false,
            asnyc: false,
            success:function(response) {
                $("#upload_form").find("input[type=text], textarea").val("");
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
            url: "<?=base_url()?>dashboard/ajax_latest_comments",
            type: 'get',
            success:function(response) {
                $('.comments').html(response);
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