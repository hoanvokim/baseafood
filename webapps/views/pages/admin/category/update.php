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
                <section class="col-lg-6">
                    <div class="box box-primary">
                        <?php echo form_open('update-category-submit'); ?>
                        <div class="box-body">
                            <div class="text-red text-center">
                                <?php echo validation_errors(); ?>
                            </div>
                            <div class="form-group">
                                <input type="hidden" id="hide" name="did" value="<?php echo $category['id']; ?>">
                                <label for="en_name">English name</label>
                                <input type="text" id="en_name" name="en_name" class="form-control"
                                       value="<?php echo $category['en_name']; ?>" placeholder="Category name" autofocus>
                            </div>
                            <div class="form-group">
                                <label for="vi_name">Vietnamese name</label>
                                <input type="text" id="vi_name" name="vi_name" class="form-control"
                                       value="<?php echo $category['vi_name']; ?>" placeholder="Category name" autofocus>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="<?php echo site_url('category-manager'); ?>" type="submit" class="btn">Cancel</a>
                        </div>
                        </form>
                    </div>
                </section>
            </div>
        </section>
    </aside>

</div>
<?php $this->load->view('layouts/admin/footer'); ?>
</body>
</html>