<!-- Modal -->
<div class="modal fade" id="systemInfoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">System Info</h5>
                </button>
            </div>
            <div class="modal-body" id="systemInfoModalBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createVariableModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-layer-two">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Variable</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="text-center">
                        For Define Variable Name '$example' and write code inline PHP
                    </div>


                </div>

                <input type="text" id="variable_name" placeholder="Variable Name">

                <textarea id="variable_code" cols="30" rows="10" class="form-control"></textarea>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><br>
                <button type="button" class="btn btn-primary" onclick="variable_create_btn()">Create</button>

            </div>
        </div>
    </div>

</div>




<div class="modal fade" id="editEvent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-layer-two">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="text-center">
                        Put Your Code Before Return Or Remove
                    </div>


                </div>

                <input type="text" id="event_name_edit" placeholder="Event Name" disabled>

                <textarea id="event_code_edit" cols="30" rows="10" class="form-control"></textarea>

            </div>






            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><br>
                <button type="button" class="btn btn-primary" onclick="update_event_btn()">Update</button>

            </div>
        </div>
    </div>
</div>


































<div class="modal fade" id="variableEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-layer-two">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Variable</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="text-center">
                        For Define Variable Name '$example' and write code inline PHP
                    </div>


                </div>

                <input type="text" id="variable_name_edit" placeholder="Module Name">
                <input type="text" id="variable_name_old" style="display: none;">

                <textarea id="variable_code_edit" cols="30" rows="10" class="form-control"></textarea>

            </div>






            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><br>
                <button type="button" class="btn btn-primary" onclick="variable_update_btn()">Update</button>

            </div>
        </div>
    </div>
</div>






<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog  modal-xl" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">SHELL</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <textarea type="text" name="" id="shell_cmd" placeholder="Command" class="form-control"></textarea>


                <textarea name="" id="shell_res" cols="30" rows="20" readonly class="form-control"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><br>
                <button type="button" class="btn btn-primary" onclick="shell_exec()">Send</button>
                <button type="button" class="btn btn-primary" onclick="shell_exec_res()">Get</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade modal-lg" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <section class="ftco-section">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6 text-center mb-5">
                            <h2 class="heading-section">Command History</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-wrap">
                                <table class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ID no.</th>
                                            <th>Command</th>
                                            <th>result</th>
                                            <th>cmd_uid</th>
                                            <th>time</th>
                                            <th>status</th>
                                            <th>action</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="history_data">


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<div class="modal fade" id="variableModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Variable</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="text-center">
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#createVariableModal">Add-Variable</button>
                        <button type="button" class="btn btn-primary" onclick="variable_edit()">Edit-Variable</button>
                        <button type="button" class="btn btn-primary"
                            onclick="variable_remove()">Remove-Variable</button>
                    </div>
                    <br>
                    <select class="form-control" id="variable_option">

                    </select>
                </div>
                <div id="variable_input">

                </div>



                <textarea name="" id="variable_res" cols="30" rows="10" readonly class="form-control"></textarea>
            </div>






            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><br>
                <button type="button" class="btn btn-primary" onclick="variable_exec()">Run</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="moduleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Module</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="text-center">
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#moduleCreateModal">Add-Module</button>
                        <button type="button" class="btn btn-primary" onclick="module_edit()">Edit-Module</button>
                        <button type="button" class="btn btn-primary" onclick="module_remove()">Remove-Module</button>
                    </div>
                    <br>
                    <select class="form-control" id="module_option">

                    </select>
                </div>
                <div id="module_input">

                </div>



                <textarea name="" id="module_res" cols="30" rows="10" readonly class="form-control"></textarea>
                <img id="screen" class="img-fluid">
            </div>






            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><br>
                <button type="button" class="btn btn-primary module_exec" onclick="module_exec()">Run</button>
                <button type="button" class="btn btn-primary module_exec_res" onclick="module_exec_res()">Get</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="text-center">
                        <button type="button" class="btn btn-primary  btn-block"
                            onclick="showEvent()">Edit-Event</button>
                    </div>
                    <br>
                    <select class="form-control" id="event_option">
                        <option value="init">init</option>
                        <option value="up">up</option>
                    </select>
                </div>




            </div>






            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><br>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="labelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Label</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="text-center">
                    <input type="text"  id="label_name" placeholder="Test">

                    </div>

                </div>




            </div>






            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="set_label()">Set</button><br>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><br>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="moduleCreateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-layer-two">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Module</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="text-center">
                        For Define Variable Use '$a1' increase number every Variable
                    </div>


                </div>

                <input type="text" id="module_name" placeholder="Module Name">

                <textarea id="module_code" cols="30" rows="10" class="form-control"></textarea>
                <button class="btn btn-warning" onclick="create_args()">
                    Add
                </button>
                <button class="btn btn-danger" onclick="delete_args()">
                    Delete
                </button>
                <div id="args_list">

                </div>
            </div>






            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><br>
                <button type="button" class="btn btn-primary" onclick="module_create_btn()">Create</button>

            </div>
        </div>
    </div>
</div>






<div class="modal fade" id="moduleEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-layer-two">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Module</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="text-center">
                        For Define Variable Use '$a1' increase number every Variable
                    </div>


                </div>

                <input type="text" id="module_name_edit" placeholder="Module Name">
                <input type="text" id="module_name_old" style="display: none;">

                <textarea id="module_code_edit" cols="30" rows="10" class="form-control"></textarea>
                <button class="btn btn-warning" onclick="create_args_edit()">
                    Add
                </button>
                <button class="btn btn-danger" onclick="delete_args()">
                    Delete
                </button>
                <div id="args_list_edit">

                </div>
            </div>






            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><br>
                <button type="button" class="btn btn-primary" onclick="module_update_btn()">Update</button>

            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" data-dismiss="modal">
        <div class="modal-content">
            <div class="modal-body">
                <img src="" class="imagepreview" style="width: 100%;">
            </div>

        </div>
    </div>
