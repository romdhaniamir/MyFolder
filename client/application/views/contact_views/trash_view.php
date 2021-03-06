<div class="row alert-top"><div class="container "><?php echo validation_errors(); ?></div></div>
<div class="row page-title">
    <div class="container  ">
        <div class="col-xs-9">
            <h2><i class="fa fa-users"></i> Gestion des Contacts  <i class="fa fa-angle-double-right"></i> <?php echo $name_list; ?></h2>
        </div>
        <div class="col-xs-3">
            <a class="btn btn-primary  " value="" data-toggle="modal" data-target="#add-list-modal"> <i class="fa fa-list"> </i>  Ajouter une liste des contacts</a>
        </div>
    </div>
</div>
<div class="row alert-top"><div class="container "><?php echo validation_errors(); ?></div></div>
<div class="row page-content page-contact">
    <div class="row">
        <div class="container">
            <div class="col-lg-3 list-box "> 
                <div class="row top-menu-icons">
                    <a href="<?php echo base_url() . "index.php/lists_contacts/trash" ?> "><i class="fa fa-trash"></i>Corbeille </a>
                    <span id="trash-count"></span>
                </div>
                <div class="row list-table-box">
                    <?php
                    $this->table->set_heading('<label for="all-contacts" class="lab-liste"> <i class="fa fa-list"> </i> Listes des contacts</label> ', '', '', '');
                    $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="list-table  ">'));
                    echo $this->table->generate($names_view);
                    ?>
                </div>
            </div>       
            <div class="col-lg-9 table-div-contacts">
                <div class="rrow tool_bar_table top-menu-icons">
                    <a  class="maximize"><i class="fa fa-expand"></i> Agrandir  </a>
                    <a  class="delete-list"><i class="fa fa-trash-o"></i> Corbeille  </a>
                    <a  class="restore-list"><i class="fa fa-refresh"></i> Déplacer  </a>
                </div>
                <div class="row">
                    <div class="header_tabel_navigation">
                        <form class="form-inline">
                            <labe for="unity_table">Nombres de lignes:</labe> <select class="form-control"  name="unity" id="unity_table" >
                                <option class="option-select"><?php echo set_value('unity') ? set_value('unity') : 10; ?></option>
                                <option >10</option>
                                <option>25</option>
                                <option>100</option>
                                <option>250</option>
                                <option>500</option>
                            </select>
                            <span class="total" >Totale : <span> </span></span>

                            <a class="first"><i class="fa fa-angle-double-left"></i></a><a class="previous"><i class="fa fa-angle-left"></i></a><select class="form-control select-page"></select><label class="current-page"></label><a class="next"><i class="fa fa-angle-right"></i></a><a class="last"><i class="fa fa-angle-double-right"></i></a>
                        </form>
                    </div>
                </div>
                <div class="row table-to-scroll">

                    <div class="canvas-table">
                        <?php
                        if (sizeof($contacts_table) == 0)
                            echo "liste vide";
                        else {
                            $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="table table-striped  ">'));

                            echo $this->table->generate($contacts_table);
                        }
                        ?>

                    </div>

                </div>
            </div>
        </div>
    </div>\\
</div>


<!-- Modal -->
<div class="modal fade" id="popup-action-list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="myModalLabel">Suppression !</h3>
            </div>
            <div class="modal-body">

                <h4>Voulez vous supprimer cette liste définitivement !</h4>


            </div>
            <div class="modal-footer">
                <a id="accepted" class="btn btn-info ">Déplacer</a>
                <button type="button" id="cancel" class="btn btn-default" data-dismiss="modal">Non</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="popup-action" tabindex="2" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="myModalLabel">Suppression !</h3>
            </div>
            <div class="modal-body">
                <h4>Voulez vous supprimer ce contact définitivement !</h4>


            </div>
            <div class="modal-footer">
                <a id="accepted" class="btn btn-info ">Supprimer</a>
                <button type="button" id="cancel" class="btn btn-default" data-dismiss="modal">Non</button>

            </div>
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