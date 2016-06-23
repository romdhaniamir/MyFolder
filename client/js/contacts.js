$(function ()
{
    
    

    $(".table-to-scroll").niceScroll({cursorcolor: "#B1B1B1", /*touchbehavior:true,*/cursorborder: "0", cursoropacitymax: 1, autohidemode: false, cursorwidth: 10, cursorminheight: 30});


    $("#country-select").append(countries_french_options);
    $(".form-hide").click(function () {

        $(".edit-list").removeClass('show');
        $(".lab-liste").removeClass('hide');
        $("#liste" + this.id).addClass('show');
        $("#lab" + this.id).addClass('hide');
    });

    $(".close_update").click(function () {

        $(".edit-list").removeClass('show');
        $(".lab-liste").removeClass('hide');
    });


    $(".hide-delete").click(function () {

        $(".delete-" + this.id).addClass('show');

    });

    $(".close-delete").click(function () {
        $(".action-delete").removeClass('show');
    });

    $(".popup-delete-close").click(function () {
        $(".popup-delete").removeClass("popup-show");
    });

    ///////////////////////////////////////////////animation table
    var indice = 1;

    $(".maximize").click(function () {
        if (indice % 2 !== 0) {
            $(".list-box").addClass('hide');

            $(".maximize").html('<i class="fa fa-compress"></i>Minimiser ');
            $(".table-div-contacts").addClass("table_change_width");
            $(".table-to-scroll").getNiceScroll().resize();
            setTimeout(function () {
                $(".table-to-scroll").getNiceScroll().resize();
            }, 750);
        } else {

            $(".maximize").html('<i class="fa fa-expand"></i>Agrandir ');
            $(".table-div-contacts").removeClass("table_change_width");


            setTimeout(function () {
                $(".list-box").removeClass('hide');
                $(".table-to-scroll").getNiceScroll().resize();
            }, 750);
        }
        indice++;
    });

///filter table
    $("#searchInput").keyup(function () {
        //split the current value of searchInput
        var data = this.value.split(" ");
        //create a jquery object of the rows
        var jo = $(".canvas-table table tbody").find("tr");
        if (this.value == "") {
            jo.show();
            return;
        }
        //hide all the rows
        jo.hide();

        //Recusively filter the jquery object to get results.
        jo.filter(function (i, v) {
            var $t = $(this);
            for (var d = 0; d < data.length; ++d) {
                if ($t.is(":contains('" + data[d] + "')")) {
                    return true;
                }
            }
            return false;
        })
                //show the rows that match.
                .show();
    }).focus(function () {
        this.value = "";
        $(this).css({
            "color": "black"
        });
        $(this).unbind('focus');
    }).css({
        "color": "#C0C0C0"
    });

});
