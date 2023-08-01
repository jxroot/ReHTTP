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

        },   {
            icon: '<i class="fa fa-rss " aria-hidden="true"></i>',
            label: 'Multi EXEC',
            callback: function () {
                var client_list=document.location.hash.split("&") 
                client_list.pop() 
                if(client_list.length>=2){
                    $('#variableModal').modal('show');
                }
                else{
            
                    swal({
                        title: "Fail",
                        text: "Need Add 2 Client And More",
                        icon: "error",
                        dangerMode: true,
                    })
                }
             

            }
        },
    ];

var context = new Bubbler(options);
