<div class="row alert-top"><div class="container "><?php echo validation_errors(); ?></div></div>
<div class="row page-title">
    <div class="container  ">
        <div class="col-xs-9">
            <h2><i class="fa fa-users"></i> Gestion des Contacts</h2>
        </div>
        <div class="col-xs-3">
            <a class="btn btn-primary " value="" data-toggle="modal" data-target="#add-list-modal"><i class="fa fa-list"> </i>  Ajouter une liste des contacts</a>
        </div>
    </div>
</div>
<div class="page-content page-list-contact">

    <div class="row top-menu-icons">
        <div class="container">
            <ul>
                <li>
                    <a href="<?php echo base_url() . "index.php/lists_contacts/trash" ?> "><i class="fa fa-trash"></i>Corbeille </a>
                    <span id="trash-count"></span>
                </li>
                <?php if($session_admin):?>
                <li>
                    <a id="export-all"> <i class="fa fa-download"></i> Exporter en Excel(xls)</a> <i id="export-all-load-icon" class="fa fa-refresh fa-spin fa-2x hidden" ></i>
                </li>
                 <?php endif;?>
            </ul>

        </div>
    </div>
    <div class="row list-table-box contact_table">
        <div class="container">
            <?php
            $this->table->set_heading('<label for="all-contacts" class="lab-liste"> <i class="fa fa-list"> </i> ' . $count_list . ' Listes </label> ', $count . ' Contacts','', '', '', '');
            $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="list-table  ">'));
            echo $this->table->generate($names_view);
            ?>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="popup-move-all-list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="myModalLabel">Déplacer des contacts</h3>
            </div>
            <div class="modal-body">
                <div class="row center-block">
                    <h4 class="">Choisissez une liste   !</h4>
                    <select class="form-control select-lists-all" ></select><br>
                </div>
            </div>
            <div class="modal-footer">
                <a id="accepted" class="btn btn-info ">Déplacer</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>  
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="add-list-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus-square"></i> Ajouter une liste des contacts</h4>
            </div>
            <form class=" form-add-list" method="post">
                <div class="modal-body">

                    <input type="text" class="form-control name-list " name="name_list_add" value="" placeholder="Nom de La liste">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>