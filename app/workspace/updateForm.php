                                    <h3>Edit Workspace Configuration</h3>
                                    <div style="width: 50%; float: left">
                                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" accept-charset="UTF-8" method="post" id="node-form">
                                            <fieldset style="height: 220px; padding:15px; margin:10px; border-color:silver">
                                                <legend><strong>Change Workspace State</strong></legend>
                                                Please select the new state of the Workspace:
                                                <br />
                                                <table style="text-align: center">
                                                    <tr>
                                                        <td>
                                                            <strong>The current state is <span style="font-weight: bold; color: <?php echo $color ?>"><?php echo $curState?></span></strong>
                                                            <br /><br />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <select name="state_select">
                                                            <?php
                                                                foreach ($states as $state) {
                                                                    echo "<option value=\"$state\">$state</option>\n";
                                                                }
                                                            ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <br />
                                                            <input name="state_submit" id="edit-submit" value="Submit" class="form-submit" type="submit" />
                                                            <input name="workspace_id" value="<?php echo (isset($_GET['workspace_id']) ? $_GET['workspace_id'] : $_POST['workspace_id']) ?>" type="hidden" />
                                                            <br />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div style="width: 50%; float: left">
                                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" accept-charset="UTF-8" method="post" id="node-form">
                                            <fieldset  style="height: 220px; padding:15px; margin:10px; border-color:silver">
                                                <legend>
                                                    <strong>Edit Workspace Details</strong>
                                                </legend>
                                                Please select the details for the Workspace:
                                                <br /><br />
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <strong>Title:</strong>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="new_title" size="30" maxlength="64" value="<?php echo $curTitle ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Description:</strong>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="new_desc" size="30" maxlength="255" value="<?php echo $curDesc ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr style="text-align: center">
                                                        <td colspan="2">
                                                            <br />
                                                            <input name="clear" id="edit-delete" value="Clear" class="form-submit" type="reset" />
                                                            <input name="profile_submit" id="edit-submit" value="Submit" class="form-submit" type="submit" />
                                                            <input name="workspace_id" value="<?php echo (isset($_GET['workspace_id']) ? $_GET['workspace_id'] : $_POST['workspace_id']) ?>" type="hidden" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </fieldset>
                                        </form>
                                    </div>