<?php $this->load->view("components/page_header"); ?>
<?php $this->load->view("components/page_topbar"); ?>
<?php $this->load->view("components/page_menu"); ?>

        <aside class="right-side">
            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <?php $this->load->view($subview); ?>
                    </div>
                </div>
            </section>
        </aside>

        <footer class="main-footer">
          	<strong><?=$siteinfos->footer?></strong>
            <a class="pull-right" target="_blank" href="https://www.metadigital.ec/"><i class="fa fa-globe"></i> Copyright &copy;Metadigital</a>
        </footer>
<?php $this->load->view("components/page_footer"); ?>


