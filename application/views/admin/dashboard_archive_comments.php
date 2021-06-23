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





        <h5 class="content-title right"><i class="material-icons right">comment</i>
            <span class="right">نظرات</span>
        </h5>

        <div class="row clear-both">
            <div class="col m12 s12 card archive ">

                <table class="highlight responsive-table">
                    <thead>
                        <tr>
                            <th>متن نظر</th>
                            <th>آدرس نظر</th>
                            <th>کاربر</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach($comments as $comment): ?>
                    <?php 
                    $user = $this->users_model->get_users_by_id($comment->comment_user_id);    
                    $article = $this->post_model->get_article_by_id($comment->comment_post_id);
                    ?>
                        <tr>
                            <td>
                            <?php
                                if(strlen($comment->comment_text) > 100) {
                                    echo substr($comment->comment_text, 0,100).'...';
                                } else {
                                    echo($comment->comment_text);
                                }
                            ?>
                            </td>
                            <td><a href="<?= base_url($article[0]->url_slug.'/'.$article[0]->id) ?>"><?=$article[0]->name?></a></td>
                            <td><a href="<?= base_url('user/'.$user[0]->username) ?>"><?= $user[0]->username ?></a></td>
                            <td>
                                <a href="<?=base_url('dashboard/remove_comment/').$comment->id?>" class="latest-comments-action waves-effect waves-light btn-small red white-text"><i class="material-icons right">remove_circle</i>حذف</a>


                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?= $links ?>
        </div>
    </div>
</div>


{footer}