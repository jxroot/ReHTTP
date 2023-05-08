var options =
    [
        {
            icon: '<i class="fa fa-briefcase" aria-hidden="true"></i>',
            label: 'Module',
            callback: function () {
                document.location.hash = ""
                $('#moduleModal').modal('show');
                $("#module_res").css("display", "none")
                $(".module_exec").css("display", "none")
                $(".module_exec_res").css("display", "none")


            }
        },
        {
            icon: '<i class="fa fa-microchip" aria-hidden="true"></i>',
            label: 'Variable',
            callback: function () {
                document.location.hash = ""

                $('#variableModal').modal('show');

            }
        },
        {
            icon: '<i class="fa fa-bolt" aria-hidden="true"></i>',

            label: 'Event',
            callback: function () {
                document.location.hash = ""
                $('#eventModal').modal('show');

            }

        }
    ];

var context = new Bubbler(options);
