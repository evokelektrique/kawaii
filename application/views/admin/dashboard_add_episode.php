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
                <a href="<?=base_url('dashboard')?>/episodes/<?=$article[0]->id?>" class="title col s12 m12 btn right waves-effect cyan lighten-1">مشاهده همه قسمت ها</a>
                <div class="progress_container">

                    <div class="progress">
                        <div class="indeterminate"></div>
                    </div>
                </div>

                <div class="episodes">
                    <table class="highlight">
                        <thead>
                            <tr>
                                <th>عکس</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>

                        <tbody>
                            {episodes}
                            <tr>
                                <td><a href="<?=base_url()?>public/img/episodes_images/{image_name}">لینک عکس</a></td>
                                <td>
                                  <a href="<?=base_url('dashboard')?>/edit_chapter/<?=$article[0]->id?>/{chapter_id}/" class="latest-comments-action waves-effect waves-light btn-small grey white-text"><i class="material-icons right">book</i>چپتر</a>
                                  <a href="<?=base_url('dashboard')?>/remove_chapter/<?=$article[0]->id?>/{id}" class="latest-comments-action waves-effect waves-light btn-small red white-text"><i class="material-icons right">remove_circle</i>حذف</a>
                                </td>
                            </tr>
                            {/episodes}

                        </tbody>
                    </table>
                </div>


                <ul class="posts-cards">
                    <li class="post-card new-card upload-episodes empty-episodes">
                        <a href="<?=base_url('dashboard')?>/add_episode/<?=$article[0]->id?>/<?=$this->uri->segment(4)?>">
                            <span class="add_button">
                                <i class="material-icons">add</i>
                                اضافه کردن قسمت جدید
                            </span>
                        </a>
                    </li>   
                </ul>

            </div>
            <div class="col m7 s12">
                <div class="card">
                    <div class="card-image">
                        <img src="<?=base_url()?>public/img/post_covers/<?=$article[0]->post_cover?>">
                        <span class="card-title">اضافه کردن قسمت جدید</span>
                    </div>

                    <div class="card-content black-text upload_form">

                        <div class="row">

                            <form class="col s12" id="upload_form"> 

                                <input type="hidden" name="<?=$csrf_name?>" value="<?=$csrf_hash?>">
                                <div class="input-field col s12 m12 right">
                                    <input type="file" data-height="100" data-show-remove="false" class="dropify" name="image_name">
                                    <!-- <input type="file" class="dropify" data-default-file="url_of_your_file" /> -->
                                </div>
                                <div class="input-field col s12 m12 right">
		                            <button class="right waves-effect waves-light btn m12 s12 col green lighten-1">آپلود قسمت</button>
		                        </div>
                            </form>


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
$(document).ready(function(){
	$('.dropify').dropify({
	    messages: {
	        'default': 'بکشید و بیاندازید یا کلیک کنید',
	        'replace': 'بکشید و بیاندازید یا کلیک کنید برای جایگذاری',
	        'remove':  'حذف',
	        'error':   'اوخ، به مشکل بر خوردیم'
	    },
		tpl: {
		    wrap:            '<div class="dropify-wrapper"></div>',
		    loader:          '<div class="dropify-loader"></div>',
		    message:         '<div class="dropify-message"><i class="material-icons">attach_file</i> <p>{{ default }}</p></div>',
		    preview:         '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message">{{ replace }}</p></div></div></div>',
		    filename:        '<p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p>',
		    clearButton:     '<button type="button" class="dropify-clear">{{ remove }}</button>',
		    errorLine:       '<p class="dropify-error">{{ error }}</p>',
		    errorsContainer: '<div class="dropify-errors-container"><ul></ul></div>'
		}
	});



    $('#upload_form').submit(function(e){
        $('.latest-episodes').addClass('reloading');
        e.preventDefault();
        var form_data = new FormData(this);
        $.ajax({
            url: "<?=base_url()?>dashboard/create_episode/<?=$article[0]->id?>/<?=$chapter[0]->id?>",
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
        setTimeout(function() {

	        $.ajax({
	            url: "<?=base_url()?>dashboard/ajax_latest_episodes/<?=$article[0]->id?>/<?=$chapter[0]->id?>",
	            type: 'get',
	            success:function(response) {
	                $('.episodes').html(response);
	                $('.latest-episodes').removeClass('reloading');
	            }
	        });
        }, 500);
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