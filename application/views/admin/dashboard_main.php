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















            <h5 class="content-title right"><i class="material-icons right">apps</i>آخرین مطالب</h5>
            <ul class="posts-cards">
                <li class="post-card new-card">
                    <a href="<?=base_url('dashboard')?>/upload">
                        <span class="add_button">
                            <i class="material-icons">add</i>
                            اضافه کردن مطلب جدید
                        </span>
                    </a>
                </li>   
                {entries}
                <li>
                    <a class="post-card" href="<?=base_url('dashboard/edit_article/{id}')?>">
                        <div class="post-card-image" style="background-image:url(<?=base_url()?>public/img/post_images/{post_image})">
                            <span class="post-card-overlay">
                                <b><i class="material-icons right">edit</i>{name}</b>
                                <b><i class="material-icons right">remove_red_eye</i>{view_count}</b>
                                <b><i class="material-icons right">date_range</i>{release_date}</b>
                            </span>
                        </div>
                    </a>
                </li> 
                {/entries}

            </ul>




            <h5 class="content-title"><i class="material-icons right">timeline</i>آمار و اطلاعات</h5>
            <div class="row">
                <div class="col s12 m6">
                    <div class="card grey darken-4">
                        <div class="card-content white-text">
                            <span class="card-title">نمودار</span>
                            <canvas id="line-chart" width="400" height="400"></canvas>
<?php 
    $dates = array();
    foreach($chardata as $data) {
        array_push($dates, date('Y-m-d', strtotime($data["created_at"])));
    }
    $vals = array_count_values($dates);
    $uniqe_dates = array_unique($dates);
?>
                <script>
                            var ctx = document.getElementById('line-chart').getContext('2d');
                            var gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
                            gradientStroke.addColorStop(0, "#80b6f4");
                            gradientStroke.addColorStop(1, "#f49080");
                            var gradientFill = ctx.createLinearGradient(500, 0, 100, 0);
                            gradientFill.addColorStop(0, "rgba(128, 182, 244, 0.6)");
                            gradientFill.addColorStop(1, "rgba(244, 144, 128, 0.6)");
                            var myChart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: [
                                        <?php foreach($uniqe_dates as $date): ?>
                                        <?php echo "'$date',"; ?>
                                        <?php endforeach; ?>
                                    ],
                                    datasets: [{
                                        label: 'کاربر',
                                        data: [
                                            <?php foreach($vals as $val): ?>
                                            <?php echo $val.','; ?>
                                            <?php endforeach; ?>
                                        ],
                                        borderColor:               gradientStroke,
                                        pointBorderColor:          gradientStroke,
                                        pointBackgroundColor:      gradientStroke,
                                        pointHoverBackgroundColor: gradientStroke,
                                        pointHoverBorderColor:     gradientStroke,
                                        fill: true,
                                        backgroundColor: gradientFill
                                    }]
                                },
                                options: {
                                    legend: {
                                        display: false,
                                        defaultFontFamily: "Vazir-FD",
                                    },
                                    tooltips: {
                                        callbacks: {
                                            label: function(tooltipItem) {
                                                return tooltipItem.yLabel;
                                            }
                                        }
                                // titleFontFamily: "Vazir-FD"
                                // bodyFontFamily "Vazir-FD"
                            },
                            title: {
                                display: true,
                                text: 'آمار کاربران',
                                fontFamily: "Vazir-FD"
                            },
                            animation: {
                                easing: "easeInOutBack"
                            },
                            scales: {
                                xAxes: [{
                                    gridLines: {
                                        color: "rgba(0, 0, 0, 0)",
                                    },
                                    ticks: {
                                        fontFamily: "Vazir-FD",
                                    }
                                }],
                                yAxes: [{
                                    gridLines: {
                                        color: "rgba(0, 0, 0, 0)",
                                    },
                                    ticks: {
                                        fontFamily: "Vazir-FD",
                                    } 
                                }]
                            }
                        }
                    });
                </script>
                </div>
                <div class="card-action">
                    <a href="<?=base_url('dashboard/users')?>">لیست کاربران</a>
                    <a href="<?=base_url('dashboard/comments')?>">لیست نظرات</a>
                    <a href="<?=base_url('dashboard/archive')?>">لیست مطالب</a>
                    <a href="<?=base_url('dashboard/alerts')?>">لیست گزارش ها</a>
                </div>
            </div>
        </div>

        <div class="col s12 m6">
            <div class="card blue">
                <div class="card-image">
                    <img src="<?=base_url()?>public/img/backgrounds/recents_bg2.jpg">
                </div>
                <div class="card-tabs">
                    <ul class="tabs tabs-fixed-width tabs-transparent">
                        <li class="tab"><a class="active" href="#tab1">آخرین کاربران</a></li>
                        <li class="tab"><a href="#tab2">آخرین گزارش ها</a></li>
                    </ul>
                </div>
                <div class="card-content blue lighten-5">
                    <div id="tab1">
                        <ul class="collection with-header">
                            <li class="collection-header"><span>لیست کاربران</span></li>
                            <?php $increment = 1; foreach($users as $user): ?>
                            <li class="collection-item">
                                <div>
                                    <?= $user->username ?>
                                    <span class="new badge" data-badge-caption="<?=$increment?>"></span>
                                    <a href="<?=base_url('dashboard/edit_user/').$user->id?>" class="secondary-content left">
                                        <i class="material-icons">build</i>
                                    </a>
                                </div>
                            </li>
                            <?php $increment++; endforeach; ?>
                        </ul>
                    </div>

                    <div id="tab2">
                        <ul class="collection with-header">
                            <li class="collection-header"><span>لیست  گزارش ها</span></li>
                            {alerts}
                            <li class="collection-item">
                                <div>
                                    <a href="{link}" class="black-text">{text}</a>
                                    <span class="new badge" data-badge-caption="{type}"></span>
                                    <a href="{edit_link}" class="secondary-content left">
                                        <i class="material-icons">build</i>
                                    </a>
                                </div>

                            </li>
                            {/alerts}
                            <?php if(empty($alerts)): ?>
                            <li class="collection-item">
                                <div>
                                    <b>موردی یافت نشد</b>
                                </div>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
    <h5 class="content-title"><i class="material-icons right">question_answer</i>آخرین نظرات</h5>
    <h5 class="content-title left"><a href="<?=base_url('dashboard/comments')?>">مشاهده همه</a></h5>
    <div class="col m12 s12">
        <div class="card blue-grey latest-comments">
            <table class="responsive-table">
                <thead>
                    <tr>
                        <th><i class="material-icons right">person</i>نام</th>
                        <th><i class="material-icons right">attach_file</i>لینک</th>
                        <th><i class="material-icons right">create</i>عملیات</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($comments as $comment): ?>
                    <?php 
                    $user = $this->users_model->get_users_by_id($comment->comment_user_id);    
                    $article = $this->post_model->get_article_by_id($comment->comment_post_id);
                    ?>
                        <tr>
                        <td><a href="<?= base_url('user/'.$user[0]->username) ?>" class="latest-comments-name"><?= $user[0]->username ?></a></td>
                        <td><a href="<?= base_url($article[0]->url_slug.'/'.$article[0]->id) ?>" class="latest-comments-post-link"><?php
                        if(strlen($comment->comment_text) > 100) {
                            echo substr($comment->comment_text, 0,100).'...';
                        } else {
                            echo($comment->comment_text);
                        }
                        ?></a></td>
                        <td>
                            <a class="latest-comments-action delete modal-trigger" href="<?=base_url('dashboard/remove_comment/'.$comment->id.'/index')?>">
                                حذف</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
{footer}