<?php //echo validation_errors('<p style="color:red;">', '</p>');
$id = $this->session->userdata('id'); //set id for use
?>

<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <?php if (($this->session->flashdata('result'))) echo $this->session->flashdata('result'); //if there's a result message, show it
                ?>
                <br/>


                <div class="col-md-12 col-sm-12">
                    <div class="row">
                        <h3>Messages</h3>
                        <p>Send messages anonymously to your group partners to determine their interests! This feature is only available when partners are assigned.</p>

                        <div class="container">
                            <div class="row">
                                <a class="btn btn-primary" href="<?=base_url("messages/compose")?>">Compose Message</a>
                            </div>
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
                                $count = false; //whether messages exist for the current year
                                if ($year != $current_year) //only add the active class to the most recent year
                                    echo '<div class="tab-pane fade" id="' . $year . '">';
                                else echo '<div class="tab-pane fade active in" id="' . $year . '">';?>
                                <table class="table table-hover table-bordered">
                                    <tr>
                                        <th width="35px">Group Code</th>
                                        <th>Group Name</th>
                                        <th>With</th>
                                        <th>Role</th>
                                        <th>Newest Message</th>
                                        <th>Date</th>
                                        <th>Options</th>
                                    </tr>
                                    <?php
                                    foreach ($threads as $thread) {
                                    if ($thread->year == $year) {
                                    $count = true;
                                    // TODO: clean up message view echo statements
                                    ?>
                                    <tr class="<?php echo $thread->is_read==false ? "unread" : "" ?>">
                                        <td class="groupname"><i><?= $group_name=""//lookup group name ?></i></td>
                                        <td class="groupcode"><?= $thread->group_code ?></td>
                                        <td class="with"></td>
                                        <td class="role"></td>
                                        <td class="preview"><?= $thread->message?></td>
                                        <td class="timestamp"><?= $thread->timestamp?></td>
                                        <td class="options">
                                            <a class="btn btn-primary" href="<?=base_url("messsages/view/{$thread->group_code}")?>">View Thread</a>
                                            <a class="btn btn-primary" href="<?=base_url("messages/markRead/{$thread->group_code}")?>">Mark As Read</a>
                                        </td>
                                            <?
                                            echo '</tr>';
                                            }
                                            }
                                            if ($count == false) {//if no groups exist
                                                echo "<tr><td colspan='7'>there doesn't seem to be anything here...</td></tr>";
                                                echo '</table></div>';
                                            }
                                            $year++;
                                            }
                                            ?>
                                </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>