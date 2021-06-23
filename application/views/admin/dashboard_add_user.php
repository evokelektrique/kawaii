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
            




            <h5 class="content-title right"><i class="material-icons right">apps</i>کاربر جدید</h5>

            <div class="row clear-both">
                <div class="col m5 s12 latest-episodes">
                <a href="<?=base_url('dashboard')?>/users/" class="title col s12 m12 btn right waves-effect cyan lighten-1">مشاهده همه کاربران</a>
                <div class="progress_container">

                    <div class="progress">
                        <div class="indeterminate"></div>
                    </div>
                </div>
                <div class="users">
                    <table class="highlight responsive-table">
                        <thead>
                            <tr>
                                <th>نام کاربری</th>
                                <th>ایمیل</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>

                        <tbody>
                            {users}
                            <tr>
                                <td><a href="<?=base_url('user/')?>{username}">{username}</a></td>
                                <td>{email}</td>
                                <td>
                                    <a href="<?=base_url('dashboard/edit_user/')?>{id}" class="latest-comments-action waves-effect waves-light btn-small orange white-text"><i class="material-icons right">settings_applications</i>ویرایش</a>
                                </td>
                            </tr>
                            {/users}
                        </tbody>
                    </table>
                </div>
                 <ul class="posts-cards">
                    <li class="post-card new-card upload-episodes empty-episodes">
                        <a href="<?=base_url('dashboard')?>/create_user">
                            <span class="add_button">
                                <i class="material-icons">add</i>
                                اضافه کردن کاربر جدید
                            </span>
                        </a>
                    </li>   
                </ul>

                </div>
                <div class="col m7 s12">
                    <div class="card">
                        <div class="card-content black-text upload_form">
                            <span class="card-title white-text">ایجاد کاربر جدید</span>

                                <div class="row">


                                

                                    <form class="col s12" id="upload_form"> 
                                        <input type="hidden" name="user_id" value="{id}">
                                        <input type="hidden" name="<?=$csrf_name?>" value="<?=$csrf_hash?>">
                                        <div class="input-field col s12 m6 right">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <input name="username" id="icon_prefix" type="text" placeholder="نام کاربری" class="validate">
                                        </div>
                                        <div class="input-field col s12 m6 right">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <input name="email" id="icon_prefix" type="text" placeholder="ایمیل" class="validate">
                                        </div>
                                        <div class="input-field col s12 m6 right">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <input value="" name="password" id="icon_prefix" type="text" placeholder="رمز عبور" class="validate">
                                        </div>
                                        <div class="input-field col s12 m6 right">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <input name="firstname" id="icon_prefix" type="text" placeholder="نام" class="validate">
                                        </div>
                                        <div class="input-field col s12 m6 right">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <input name="lastname" id="icon_prefix" type="text" placeholder="نام خانوادگی" class="validate">
                                        </div>
                                        <div class="input-field col s12 m6 right">
                                            <select name="role">
                                                <option value="" disabled>نقش کاربر</option>
                                                <option value="1">کاربر معمولی</option>
                                                <option value="2">کاربر مدیر</option>
                                                <option value="3">کاربر مسدود</option>
                                            </select>
                                        </div>
                                        <div class="file-field col s12 m12 right">
                                            <div class="btn right blue waves-effect waves-light lighten-1">
                                                <span>انتخاب عکس پروفایل </span>
                                                <input type="file" name="profile_picture">
                                            </div>
                                            <div class="file-path-wrapper">
                                                <input class="file-path validate" name="profile_picture_url" type="text">
                                            </div>
                                        </div>
                                        <div class="file-field col s12 m12 right">
                                            <div class="btn right blue waves-effect waves-light lighten-1">
                                                <span>انتخاب عکس پس زمینه</span>
                                                <input type="file" name="profile_cover">
                                            </div>
                                            <div class="file-path-wrapper">
                                                <input class="file-path validate" name="profile_cover_url" type="text">
                                            </div>
                                        </div>

                                        <button class="right waves-effect waves-light btn green lighten-1">ثبت</button>
                                    </form>








                                </div>
        
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
            url: "<?=base_url()?>dashboard/do_create_user",
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
            url: "<?=base_url()?>dashboard/ajax_latest_users",
            type: 'get',
            success:function(response) {
                $('.users').html(response);
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