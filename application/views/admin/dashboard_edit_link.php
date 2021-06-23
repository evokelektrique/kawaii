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





        <h5 class="content-title right"><i class="material-icons right">apps</i>لینک جدید</h5>

        <div class="row clear-both">
            <div class="col m5 s12 latest-episodes">
                <a href="<?=base_url('dashboard')?>/links/" class="title col s12 m12 btn right waves-effect cyan lighten-1">مشاهده همه لینک ها</a>
                <div class="progress_container">

                    <div class="progress">
                        <div class="indeterminate"></div>
                    </div>
                </div>

                <div class="links">
                    <table class="highlight">
                        <thead>
                            <tr>
                                <th>نام</th>
                                <th>آیکون</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>

                        <tbody>
                            {links}
                            <tr>
                                <td><a href="{address}">{name}</a></td>
                                <td>{icon}</td>
                                <td>
                                    <a href="<?=base_url('dashboard')?>/edit_link/{id}" class="latest-comments-action waves-effect waves-light btn-small orange white-text"><i class="material-icons right">settings_applications</i>ویرایش</a>
                                    <a href="<?=base_url('dashboard')?>/remove_link/{id}" class="latest-comments-action waves-effect waves-light btn-small red white-text"><i class="material-icons right">remove_circle</i>حذف</a>      
                                </td>
                            </tr>
                            {/links}
                        </tbody>
                    </table>
                </div>


                <ul class="posts-cards">
                    <li class="post-card new-card upload-episodes empty-episodes">
                        <a href="<?=base_url('dashboard')?>/add_link/">
                            <span class="add_button">
                                <i class="material-icons">add</i>
                                اضافه کردن لینک جدید
                            </span>
                        </a>
                    </li>   
                </ul>

            </div>
            <div class="col m7 s12">
                <div class="card">
                    <div class="card-image">
                        <img src="<?=base_url()?>public/img/backgrounds/catgirl.jpg">
                        <span class="card-title">اضافه کردن لینک جدید</span>
                    </div>

                    <div class="card-content black-text upload_form">

                        <div class="row">



                                {link}
                                    <form class="col s12" id="upload_form"> 

                                        <input type="hidden" name="<?=$csrf_name?>" value="<?=$csrf_hash?>">
                                        <div class="input-field col s12 m12 right">
                                            <label for="">زیر شاخه</label>
                                            <select name="parent_id">
                                                <option <?=($link[0]->parent_id == 0) ? 'selected' : ''?> value="0">مادر (اصلی)</option>
                                                <?php foreach($parent_links as $pl): ?>
                                                <option <?=($link[0]->parent_id == $pl->id) ? 'selected' : ''?>  value="<?=$pl->id?>"><?=$pl->name?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="input-field col s12 m12 right">
                                            <label for="">محل قرار گیری</label>
                                            <select name="position">
                                                <option <?=($link[0]->position == 'top') ? 'selected' : ''?> value="top">هدر (بالا)</option>
                                                <option <?=($link[0]->position == 'bottom') ? 'selected' : ''?> value="bottom">فوتر (پایین)</option>
                                            </select>
                                        </div>
                                        <div class="input-field col s12 m12 right">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <input name="name" value="{name}" id="icon_prefix" type="text" placeholder="نام" class="validate">
                                        </div>
                                        <div class="input-field col s12 m12 right">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <input name="icon" value="{icon}" id="icon_prefix" type="text" placeholder="آیکون سفارشی" class="validate">
                                        </div>                                        
                                        <div class="input-field col s12 m12 right">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <input name="address" value="{address}" id="icon_prefix" type="text" placeholder="آدرس" class="validate">
                                        </div>

                                        <button class="right waves-effect waves-light btn green lighten-1">ثبت</button>
                                    </form>
                                {/link}










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
        e.preventDefault();
        var form_data = new FormData(this);
        $.ajax({
            url: "<?=base_url()?>dashboard/do_edit_link/<?=$link[0]->id?>",
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
            url: "<?=base_url()?>dashboard/ajax_latest_links/",
            type: 'get',
            success:function(response) {
                $('.links').html(response);
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