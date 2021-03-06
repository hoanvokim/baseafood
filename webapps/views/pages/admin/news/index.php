<!DOCTYPE html>
<html>
<head>
    <?php $this->load->view('layouts/admin/header'); ?>
</head>
<body class="skin-blue">
<?php $this->load->view('layouts/admin/nav'); ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <?php $this->load->view('layouts/admin/menu'); ?>
    <aside class="right-side">
        <section class="content">
            <div class="row">
                <section class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <i class="ion ion-clipboard"></i>

                            <h3 class="box-title">news List</h3>
                            <div style="margin: 10px;">
                                <?php echo anchor('create-news', '<i class="fa fa-plus"></i> Add new', 'class="btn btn-default pull-right"') ?>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th width="5%">Id</th>
                                    <th width="30%">En Title</th>
                                    <th width="55%">En Content</th>
                                    <th width="10%">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($news_list as $news): ?>
                                    <tr>
                                        <td><?php echo $news['id'] ?></td>
                                        <td><?php echo $news['en_title'] ?></td>
                                        <td><?php
                                            $limited_word = word_limiter($news['en_content'], 10);
                                            echo $limited_word; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url() . "news-manager/update/" . $news['id']; ?>"
                                               class="btn btn-info"><i class="fa fa-edit"></i> Edit</a>
                                            <a href="<?php echo base_url() . "news-manager/delete/" . $news['id']; ?>"
                                               class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="box-footer clearfix no-border">
                            <?php echo anchor('create-news', '<i class="fa fa-plus"></i> Add new', 'class="btn btn-default pull-right"') ?>
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </aside>
</div>
<?php $this->load->view('layouts/admin/footer'); ?>
</body>
</html>