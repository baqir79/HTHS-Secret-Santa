<?php //echo validation_errors('<p style="color:red;">', '</p>');
$id = $this->session->userdata('id'); //set id for use
$userStats = $this->datamod->userStats($id); //get user stats
?>
<script src="<?php echo base_url("/js/profile.js") ?>"></script>
<script src="<?php echo base_url("/js/memberlist.js") ?>"></script>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <?php if (($this->session->flashdata('result'))) echo $this->session->flashdata('result'); //if there's a result message, show it
                echo form_error('group');
                echo form_error('group_name');
                echo form_error('group_description');
                echo form_error('edit-grp-code');
                echo form_error('edit-grp-name');
                echo form_error('edit-grp-description');
                echo form_error('checkGroupPaired');?>
                <br/>

                <div class="col-md-2 col-sm-12">
                    <div class="row">
                        <h3><?php echo $this->session->userdata("fname") . " " . $this->session->userdata("lname"); ?></h3>
                        <span><?php echo $this->session->userdata("email"); ?></span><br/>
                        <!--<span>Class of <?php echo($userStats->class ? $userStats->class!=null : "???");?></span><br/>-->
                        <br/>
                        <br/>
                        <span><strong>Santa since:</strong> <?php echo($userStats->year_join) ?></span><br/>
                        <span><strong>Gifts
                                Exchanged:</strong> <?php echo($this->datamod->giftsExchanged($id)); ?></span><br/>
                        <span><strong></strong></span>
                    </div>
                </div>

                <div class="col-md-10 col-sm-12">
                    <div class="row">
                        <h3>My groups </h3>

                        <p>You are currently
                            in <?php echo($this->datamod->countPersonGroups($id)); ?>/5
                            groups.</p>

                        <div class="container">
                            <ul id="years" class="nav nav-tabs">
                                <?php
                                $year = $first_year; //don't override first_year variable
                                while ($year <= $current_year) {
                                    if ($year != $current_year) //only add the active class to the most recent year
                                        echo '<li><a href="#' . $year . '" data-toggle="tab">' . $year . '</a></li>';
                                    else echo '<li class="active"><a href="#' . $year . '" data-toggle="tab">' . $year . '</a></li>';
                                    $year++;
                                }?>
                            </ul>
                            <div class="tab-content">
                                <?php
                                $year = $first_year; //reset year variable
                                while ($year <= $current_year){
                                    $count = false; //whether groups exist for the current year
                                    if ($year != $current_year) //only add the active class to the most recent year
                                        echo '<div class="tab-pane fade" id="' . $year . '">';
                                    else echo '<div class="tab-pane fade active in" id="' . $year . '">';?>
                                <table class="table table-hover table-bordered">
                                    <tr>
                                        <th>Group Name</th>
                                        <th width="35px">Group Code</th>
                                        <th width="50px"># of Members</th>
                                        <th>Partner</th>
                                        <th>Description</th>
                                        <th width="170px">Options</th>
                                    </tr>
                                    <?php
                                    foreach ($groups as $group) {
                                        if ($group->year == $year) {
                                        $count = true;
                                        // TODO: clean up profile view echo statements
                                    ?>
                                    <tr>
                                        <td class="groupname"><i><?= $group->name ?></i></td>
                                        <td class="groupcode"><?= $group->code ?></td>
                                        <td><a data-toggle="modal"
                                               href="<?= base_url('group/' . $group->code . '/' . $group->year . '/membersModal') ?>"
                                               data-target="#modal-member-list"><?= $this->datamod->countMembers($group->code, $group->year) ?></a>
                                        </td>
                                        <td><?= $this->datamod->getPair($group->code, $id, $group->year) ?></td>
                                        <td class="description"><?= $group->description ?></td>
                                        <td>
                                            <?php
                                            if ($group->year == $current_year) { //only show buttons if its a current year group
                                                $disabled = $group->owner != $id ? 'disabled' : ''; //disable edit button if not group owner
                                                echo "<button type='button' class='btn btn-warning grp-edit {$disabled}''>Edit</button>";
                                                $disabled = $group->leaveable ? '' : 'disabled'; //disabled leave if group is not leaveable
                                                echo "<a class='btn btn-primary {$disabled}' href='" . base_url('profile/rm/' . $group->code) . "'>Leave</a>";
                                            }
                                            echo '</td></tr>';
                                          }
                                        }
                                        if ($count == false) //if no groups exist
                                            echo "<tr><td colspan='6'>there doesn't seem to be anything here...</td></tr>";
                                        echo '</table></div>';
                                        $year++;
                                      }
                                        ?>
                                </table>
                                <div style="padding: 3px 0 0 0;font-size:9px">*Groups must have at least 5 members to be
                                    valid.
                                </div>
                            </div>
                        </div>

                        <br/>

                        <h3>Join a group</h3>

                        <p>Enter a group code below to join a group.</p>

                        <div class="container">
                            <form role="form" method="post"
                                  action="<?php echo base_url('profile/groupcode'); // @todo base is needed since persistent forms?>">

                                <label for="inputGroupCode">Group Code: </label>
                                <input type="text" class="form-custom" maxlength="4" size="4" name="group"
                                       id="inputGroupCode"
                                       placeholder="Code" required>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <button class="btn btn-default" type="submit">Add Group</button>
                            </form>
                        </div>

                        <br/>
                        <br/>


                        <h3>Create a group</h3>

                        <p>Create a group to gift exchange with some friends.</p>

                        <div class="container">
                            <form method="post"
                                  action="<?php echo base_url('profile/addgroup'); //@todo base is needed since persistent form?>">
                                <label for="inputGroupName">Group Name: </label>
                                <input type="text" class="form-custom" maxlength="50" size="25" name="group_name"
                                       id="inputGroupName"
                                       placeholder="Name" required>
                                &nbsp;&nbsp;
                                <label for="inputGroupName">Group Description: </label>
                                <input type="text" class="form-custom" maxlength="150" size="25"
                                       name="group_description"
                                       id="inputGroupDescription"
                                       placeholder="Description">
                                &nbsp;&nbsp;
                                <button class="btn btn-default" type="submit">Create Group</button>
                            </form>
                            <br/>
                        </div>
                        <div class="row">
                            <!--<h3>Settings</h3>-->
                            <div class="container">

                                <!--settings here-->
                                <br/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal-edit-grp" tabindex="-1" role="dialog" aria-labelledby="modal-edit-grp-label"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Edit Group</h4>
                </div>
                <div class="modal-body">
                    <form role="form" method="post" action="<?php echo base_url('profile/editGroup') ?>">
                        <input type="hidden" name="edit-grp-code" id="modal-edit-grp-code-hidden">

                        <div class="form-group">
                            <label for="modal-edit-grp-code">Group Code</label>
                            <input type="text" class="form-control" placeholder="Group Code" id="modal-edit-grp-code"
                                   disabled>
                        </div>
                        <div class="form-group">
                            <label for="modal-edit-name">Group Name</label>
                            <input type="text" class="form-control" id="modal-edit-grp-name" name="edit-grp-name"
                                   maxlength="50" placeholder="Group Name" required>
                        </div>
                        <div class="form-group">
                            <label for="modal-edit-description">Group Description</label>
                            <input type="text" class="form-control" id="modal-edit-grp-description"
                                   name="edit-grp-description" maxlength="150" placeholder="Group Description">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="modal-edit-grp-btn-save" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="modal-member-list" tabindex="-1" role="dialog" aria-hidden="true"></div>
</div>