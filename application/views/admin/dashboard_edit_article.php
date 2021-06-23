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
            




            <h5 class="content-title right"><i class="material-icons right">apps</i>مطلب جدید</h5>

            <div class="row clear-both">
                <div class="col m5 s12 latest-episodes">
                <a href="<?=base_url('dashboard')?>/chapters/<?=$article[0]->id?>" class="title col s12 m12 btn right waves-effect cyan lighten-1">مشاهده همه فصل ها</a>
                <div class="progress_container">

                    <div class="progress">
                        <div class="indeterminate"></div>
                    </div>
                </div>
                <table class="highlight">
                    <thead>
                        <tr>
                            <th>اسم فصل</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                        {chapters}
                        <tr>
                            <td><a href="<?=base_url()?><?=$article[0]->id?>/<?=$article[0]->url_slug?>/chapter/{id}">{name}</a></td>
                            <td>
                                <a href="<?=base_url('dashboard')?>/edit_chapter/{article_id}/{id}" class="latest-comments-action waves-effect waves-light btn-small orange white-text"><i class="material-icons right">settings_applications</i>ویرایش</a>
                                <a href="<?=base_url('dashboard')?>/add_episode/<?=$article[0]->id?>/{id}" class="latest-comments-action waves-effect waves-light btn-small lime white-text"><i class="material-icons right">add_box</i>اضافه قسمت</a>
                            </td>
                        </tr>
                        {/chapters}

                    </tbody>
                </table>
                 <ul class="posts-cards">
                    <li class="post-card new-card upload-episodes empty-episodes">
                        <a href="<?=base_url('dashboard')?>/add_chapter/<?=$article[0]->id?>">
                            <span class="add_button">
                                <i class="material-icons">add</i>
                                اضافه کردن فصل جدید
                            </span>
                        </a>
                    </li>   
                </ul>

                </div>
                <div class="col m7 s12">
                    <div class="card">
                        <div class="card-image">
                            <img src="<?=base_url()?>public/img/post_covers/<?=$article[0]->post_cover?>">
                            <span class="card-title">ویرایش مطلب</span>
                        </div>

                        <div class="card-content black-text upload_form">

                                <div class="row">


                                {article}

                                    <form class="col s12" id="upload_form"> 
                                        <input type="hidden" name="<?=$csrf_name?>" value="<?=$csrf_hash?>">

                                        <div class="input-field col s12 m12 right">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <input value="{name}" name="name" id="icon_prefix" type="text" placeholder="نام" class="validate">
                                        </div>
                                        <div class="input-field col s12 m6 right">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <input value="{author}" name="author" id="icon_prefix" type="text" placeholder="نویسنده" class="validate">
                                        </div>
                                        <div class="input-field col s12 m6 right">
                                            <select name="status">
                                                <option value="" disabled>وضعیت مطلب</option>
                                                <option <?php echo ($article[0]->status == 1) ? "selected" : "" ?> value="1">نا معلوم</option>
                                                <option <?php echo ($article[0]->status == 2) ? "selected" : "" ?> value="2">درحال پخش</option>
                                                <option <?php echo ($article[0]->status == 3) ? "selected" : "" ?> value="3">متوقف شده</option>
                                            </select>
                                        </div>
                                        <div class="input-field col s12 m7 right">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <div class="chips chips-autocomplete chips-placeholder"></div>
                                        </div>
                                        <div class="input-field col s12 m5 right">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <input value="{release_date}" id="release_date" name="release_date" class="validate" type="text" placeholder="تاریخ انتشار" >
                                        </div>

                                        <div class="input-field col s12 m12  right">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <textarea class="white-text materialize-textarea" name="description" placeholder="توضیحات">{description}</textarea>
                                        </div>
                                        <div class="file-field col s12 m6 right">
                                            <div class="btn right blue waves-effect waves-light lighten-1">
                                                <span>انتخاب عکس مطلب</span>
                                                <input type="file" value="{post_image}" name="post_image">
                                            </div>
                                            <div class="file-path-wrapper">
                                                <input class="file-path validate" name="post_image_file_path" value="{post_image}" type="text">
                                            </div>
                                        </div>
                                        <div class="file-field col s12 m6 right">
                                            <div class="btn right blue waves-effect waves-light lighten-1">
                                                <span>انتخاب عکس پس زمینه</span>
                                                <input type="file" value="{post_cover}" name="post_cover">
                                            </div>
                                            <div class="file-path-wrapper">
                                                <input class="file-path validate" name="post_cover_file_path" value="{post_cover}" type="text">
                                            </div>
                                        </div>
                                        <div class="switch col right m12 s12">
                                            <b>وضعیت نظرات</b>
                                            <label class="left">
                                                روشن
                                                <?php if($article[0]->comments_status == 'on'): ?>
                                                <input type="checkbox" name="comments_status" checked>
                                                <?php else: ?>
                                                <input type="checkbox" name="comments_status">
                                                <?php endif; ?>
                                                <span class="lever"></span>
                                                خاموش
                                            </label>
                                        </div>
                                        <button class="right waves-effect waves-light btn green lighten-1">ثبت</button>
                                    </form>



                                {/article}






                                </div>
        
                        </div>
                    </div>
                </div>
            </div>
    </div>



<script>
$(document).ready(function(){
    $('.datepicker').datepicker({
        showClearBtn: true,
        i18n:{
            monthsFull: [ 'ژانویه', 'فوریه', 'مارس', 'آوریل', 'مه', 'ژوئن', 'ژوئیه', 'اوت', 'سپتامبر', 'اکتبر', 'نوامبر', 'دسامبر'],
            monthsShort: [ 'ژانویه', 'فوریه', 'مارس', 'آوریل', 'مه', 'ژوئن', 'ژوئیه', 'اوت', 'سپتامبر', 'اکتبر', 'نوامبر', 'دسامبر' ],
            weekdaysFull: [ 'یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه', 'شنبه' ],
            weekdaysShort: [ 'یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه', 'شنبه' ],
            today: 'امروز',
            clear: 'پاک کردن',
            close: 'بستن',
            cancel: 'لغو',
            done: 'تایید',
            format: 'yyyy mmmm dd',
            formatSubmit: 'yyyy/mm/dd',
            labelMonthNext: 'ماه بعدی',
            labelMonthPrev: 'ماه قبلی'
        }

    });
    $('.chips-autocomplete').chips({
        placeholder: 'یک دسته بنویسید',
        secondaryPlaceholder: '+دسته',
        autocompleteOptions: {
            data: {
                'Apple': null,
                'Microsoft': null,
                'Google': null
            },
            limit: Infinity,
            minLength: 1,
        }
    });

    $('#upload_form').submit(function(e){
        var tags;
        var term_tags = [];
        var instance_tags = M.Chips.getInstance($('.chips')).chipsData
        $.each(instance_tags, function(key, value) {
            term_tags.push(instance_tags[key]['tag']);
        });
        console.log(tags);
        tags = term_tags.join(',');
        var form_data = new FormData(this);
        form_data.append('tags', tags);
        e.preventDefault();
        $.ajax({
            url: "<?=base_url()?>dashboard/do_edit_article/<?=$article[0]->id?>",
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
                    // console.log('status 1');
                    // console.log('status: ' + json_decoded.status);
                } else {
                    // console.log('status not 1');
                    // console.log('status: ' + json_decoded.status);
                    toasts = json_data.split('\n').filter(Boolean);
                    toasts.forEach(create_toast);

                }

            }
        });

    });
    var tags = [];

    function get_tags(stringdata)
    {
        var tagstate = stringdata; 
        var tagstatearray = tagstate.split(",");
        console.log(tagstatearray);
        $.each(tagstatearray, function(i, tagstate) {
            var obj = { tag: tagstate };
            tags.push(obj);
        });
    }
    var stringdata = "<?=$article[0]->tags?>"; // Pass your string data from database/JSON here
    get_tags(stringdata);

  $('.chips').chips({
    data: tags,
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