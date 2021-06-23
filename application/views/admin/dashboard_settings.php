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



        <h5 class="content-title right"><i class="material-icons right">settings</i>تنظیمات</h5>

        <div class="row clear-both">

            <div class="col m12 s12 settings">
                <div class="card">
                    <div class="card-image">
                        <img src="<?=base_url()?>public/img/backgrounds/settings_bg.jpg">
                        <span class="card-title m12 s12 col">
                            <div class="card-tabs">
                                <ul class="tabs tabs-fixed-width tabs-transparent">
                                    <li class="tab"><a class="active" href="#settings1">تنظیمات اصلی</a></li>
                                    <li class="tab"><a href="#settings2">تنظیمات ظاهری</a></li>
                                    <li class="tab"><a href="#settings3">تنظیمات تبلیغات</a></li>
                                    <li class="tab"><a href="#settings4">تنظیمات صفحات</a></li>
                                </ul>
                            </div>
                        </span>
                    </div>

                    <div class="card-content upload_form">
                    {settings}
                        <div class="row">
                            <div id="settings1">
                                <form id="settings_form1">                                    
                                    <input type="hidden" name="<?=$csrf_name?>" value="<?=$csrf_hash?>">
                                    <div class="input-field col s12 m7 right">
                                        <i class="material-icons prefix">mode_edit</i>
                                        <input class="white-text" name="site_name" value="{site_name}" id="site_name" type="text" placeholder="نام وبسایت" class="validate">
                                    </div>
                                    <div class="white-text input-field col s12 m7 right">
                                        <i class="material-icons prefix">mode_edit</i>
                                        <div class="chips chips-autocomplete chips-placeholder"></div>
                                    </div>
                                    <div class="input-field col s12 m7 right">
                                        <textarea name="site_description"id="site_description" type="text" placeholder="توضیحات وبسایت" class="validate materialize-textarea white-text">{site_description}</textarea>
                                    </div>
                                    <div class="input-field col s12 m7 right">
                                        <textarea disabled name="google_analytics_api" id="google_analytics_api" type="text" placeholder="Google Analytics Api" class="validate materialize-textarea white-text">بزودی</textarea>
                                    </div>
                                    <button class="clear-both right waves-effect waves-light btn blue lighten-1">ثبت تغییرات</button>
                                </form>


                            </div>
                            <div id="settings2">
                                <form id="settings_form2">   
                                    <input type="hidden" name="<?=$csrf_name?>" value="<?=$csrf_hash?>">
                                    <div class="input-field col s12 m7 right">
                                        <select name="site_template">
                                            <option <?php echo ($settings[0]->site_template == 0) ? 'selected' : '' ?> value="" disabled>انتخاب قالب</option>
                                            <option <?php echo ($settings[0]->site_template == 1) ? 'selected' : '' ?> value="1">پیشفرض</option>
                                            <option disabled <?php echo ($settings[0]->site_template == 2) ? 'selected' : '' ?> value="2">تاریک - بزودی</option>
                                        </select>
                                    </div>
                                    <div class="input-field col s12 m7 right">
                                        <textarea  dir="ltr" id="logo_url" name="logo_url" placeholder="آدرس لوگو" class="validate materialize-textarea white-text">{logo_url}</textarea>
                                    </div>
                                    <div class="input-field col s12 m7 right">
                                        <textarea dir="ltr" id="custom_css" name="custom_css" placeholder="کد های css اختصاصی" class="validate materialize-textarea white-text">{custom_css}</textarea>
                                    </div>
                                    <div class="input-field col s12 m7 right">
                                        <textarea dir="ltr" id="custom_js" name="custom_js" placeholder="کد های javascript اختصاصی" class="validate materialize-textarea white-text">{custom_js}</textarea>
                                    </div>
                                    <button class="clear-both right waves-effect waves-light btn blue lighten-1">ثبت تغییرات</button>
                                </form>
                            </div>
                            <div id="settings3">
                                <form id="settings_form3"> 
                                    <input type="hidden" name="<?=$csrf_name?>" value="<?=$csrf_hash?>">
                                    <div class="input-field col s12 m7 right">
                                        <textarea dir="ltr" name="ads1" placeholder="تبلیغ شماره 1" class="white-text validate materialize-textarea">{ads1}</textarea>
                                    </div>
                                     <div class="input-field col s12 m7 right">
                                        <textarea dir="ltr" name="ads2" placeholder="تبلیغ شماره 2" class="white-text validate materialize-textarea">{ads2}</textarea>
                                    </div>
                                    <div class="input-field col s12 m7 right">
                                        <textarea dir="ltr" name="ads3" placeholder="تبلیغ شماره 3" class="white-text validate materialize-textarea">{ads3}</textarea>
                                    </div>
                                    <div class="input-field col s12 m7 right">
                                        <textarea dir="ltr" name="ads4" placeholder="تبلیغ شماره 4" class="white-text validate materialize-textarea">{ads4}</textarea>
                                    </div>
                                    <button class="clear-both right waves-effect waves-light btn blue lighten-1">ثبت تغییرات</button>
                                </form>
                            </div>

                            <div id="settings4">
                                <form id="settings_form4"> 
                                    <input type="hidden" name="<?=$csrf_name?>" value="<?=$csrf_hash?>">
                                    <div class="input-field col s12 m7 right">
                                        <textarea name="about_us_text" placeholder="متن صفحه درباره ما" class="white-text validate materialize-textarea">{about_us_text}</textarea>
                                    </div>

                                    <button class="clear-both right waves-effect waves-light btn blue lighten-1">ثبت تغییرات</button>
                                </form>
                            </div>

                        </div>
                    {/settings}
                    </div>


                </div>
            </div>
        </div>
    </div>

<script>
$(document).ready(function(){

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

    $('#settings_form1').submit(function(e){
        var tags;
        var term_tags = [];
        var instance_tags = M.Chips.getInstance($('.chips')).chipsData
        $.each(instance_tags, function(key, value) {
            term_tags.push(instance_tags[key]['tag']);
        });
        tags = term_tags.join(',');
        console.log(tags);
        var form_data = new FormData(this);
        form_data.append('site_tags', tags);
        e.preventDefault();
        $.ajax({
            url: "<?=base_url()?>dashboard/do_settings?>",
            type: 'post',
            data: form_data,
            processData: false,
            contentType: false,
            cache: false,
            asnyc: false,
            success:function(response) {
                // alert('Upload Image successfull');
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
    var stringdata = "<?=$settings[0]->site_tags?>";
    get_tags(stringdata);

    $('.chips').chips({
        data: tags,
    });


    $('#settings_form2').submit(function(e){
        var form_data = new FormData(this);
        e.preventDefault();
        $.ajax({
            url: "<?=base_url()?>dashboard/do_settings?>",
            type: 'post',
            data: form_data,
            processData: false,
            contentType: false,
            cache: false,
            asnyc: false,
            success:function(response) {
                // alert('Upload Image successfull');
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

    $('#settings_form3').submit(function(e){
        var form_data = new FormData(this);
        e.preventDefault();
        $.ajax({
            url: "<?=base_url()?>dashboard/do_settings?>",
            type: 'post',
            data: form_data,
            processData: false,
            contentType: false,
            cache: false,
            asnyc: false,
            success:function(response) {
                // alert('Upload Image successfull');
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

    $('#settings_form4').submit(function(e){
        var form_data = new FormData(this);
        e.preventDefault();
        $.ajax({
            url: "<?=base_url()?>dashboard/do_settings?>",
            type: 'post',
            data: form_data,
            processData: false,
            contentType: false,
            cache: false,
            asnyc: false,
            success:function(response) {
                // alert('Upload Image successfull');
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
</script>

{footer}