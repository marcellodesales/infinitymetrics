<?php
/**
 * $Id: MetricsWorkspaceController.class.php 202 2008-11-10 12:01:40Z
 * Andres Ardila, Marcello de Sales $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITYs, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the Berkeley Software Distribution (BSD).
 * For more information please see <http://ppm-8.dev.java.net>.
 */
require_once 'infinitymetrics/model/workspace/MetricsWorkspace.class.php';
require_once 'infinitymetrics/model/user/UserTypeEnum.class.php';
/**
 * Description of MetricsWorkspaceController
 *
 * @author Andres Ardila <aardilam@gmail.com>
 * @author Marcello de Sales <marcello.sales@gmail.com>
 */
class MetricsWorkspaceController {
    /**
     * Validates the create workspace form
     * @param string $userId is the identification of the user
     * @param string $projectName is the project name
     * @param string $title is the title for the workspace
     * @param string $description is the description for the workspace
     * @throws InfinityMetricsException if any of the given parameters is invalid
     */
    private static function validateWorkspaceForm($userId, $projectName, $title, $description) {

        $error = array();
        if (!isset($userId) || $userId == "") {
            $error["userId"] = "The username identification is empty";
        }
        if (!isset($projectName) || $projectName == "") {
            $error["projectName"] = "The project identification is empty";
        }
        if (!isset($title) || $title == "") {
            $error["title"] = "The title is empty";
        }
        if (!isset($description) || $description == "") {
            $error["description"] = "The description is empty";
        }

        if (count($error) > 0) {
            throw new InfinityMetricsException("Can't create workspace", $error);
        }
    }
    /**
     * Retrieves a Project based on the project name
     * @param string $projectName is the java.net project name
     * @throws InfinityMetricsException if the project name is empty or if the given project name doesn't exist.
     * @return PersistentProject the instance of the project based on the given project name.
     */
    public static function retrieveProject($projectName) {

        if (!isset($projectName) || $projectName == "") {
            $error = array();
            $error["projectName"] = "The project identification is empty";
            throw new InfinityMetricsException("Can't retrive workspace", $error);
        }

        $proj = PersistentProjectPeer::retrieveByPK($projectName);
        if ($proj == null) {
            $errors = array();
            $errors["projectNotFound"] = "The project referred by " . $projectName . " doesn't exist";
            throw new InfinityMetricsException("Can't retrieve Project", $errors);
        }
        return $proj;
    }
    /**
     * Implements the UC100 Create Workspace  
     * @param string $userId is the user identification
     * @param string $projectName is the project name
     * @param string $title is the title of the workspace
     * @param string $description is the description of the workspace
     * @throws InfinityMetricsException if any of the given parameters is invalid. Also if the projectName refers to
     * a non-existing project, and other possible persistence problems.
     * @return PersistentWorkspace the newly created workspaced based on the input
     */
    public static function createWorkspace($userId, $projectName, $title, $description) {

        self::validateWorkspaceForm($userId, $projectName, $title, $description);

        $user = UserManagementController::retrieveUser($userId);
        
        $proj = self::retrieveProject($projectName);

        if ($user->isOwnerOfProject($proj)) {

            try {
                $ws = new PersistentWorkspace();
                $ws->setUserId( $user->getUserId() );
                $ws->setProjectJnName($proj->getProjectJnName());
                $ws->setTitle($title);
                $ws->setDescription($description);
                $ws->save();
                return $ws;

            } catch (Exception $e) {
                $errors = array();
                $errors["errorSavingWorkspace"] = $e->getMessage();
                throw new InfinityMetricsException("Can't create Workspace", $errors);
            }
            
        } else {
            $errors = array();
            $errors["permissionDenied"] = "The user " . $user->getJnUsername() . " is not the owner of project
                                                                                   " . $projectName . " doesn't exist";
            throw new InfinityMetricsException("Can't create Workspace", $errors);
        }
    }
    /**
     * Validates the change state input form
     * @param string $workspace_id the workspace id
     * @param string $newState new state
     * @throws InfinityMetricsException if any of the given parameters is invalid.
     */
    private static function validateChangeStateForm($workspace_id, $newState) {
        $error = array();
        if (!isset($workspace_id)|| $workspace_id == "") {
            $errors = array();
            $errors["workspace_id"] = "The workspace id must be provided";
        }
        if (!isset($newState) || $newState == "") {
            $errors = array();
            $errors["newState"] = "The new state for the workspace must be provided";
        }
        
        $validStates = array('NEW', 'ACTIVE', 'INACTIVE', 'PAUSED');
        if ( array_search($newState, $validStates) === FALSE ) {
            $errors["workspace_state"] = "The new state for the workspace " . $newState . " is not in the valid list (" .
                                                                                       implode(",", $validStates) . ")";
        }
        if (count($error) > 0) {
            throw new InfinityMetricsException("Can't create workspace", $error);
        }
    }
    /**
     * This method implements UC105 Change Workspace State  
     * @param string $workspace_id is the workspace identification
     * @param string $newState is one of the valid states 'NEW', 'ACTIVE', 'INACTIVE', 'PAUSED'
     * @throws InfinityMetricsException if any of the given parameters is invalid. Also if the workspace_id refers to
     * a non-existing workspace, and other possible persistence problems.
     * @return PersistentWorkspace the workspace with the changed state
     */
    public static function changeWorkspaceState($workspace_id, $newState) {
        
        self::validateChangeStateForm($workspace_id, $newState);

        //call the UC101 to retrieve the workspace
        $ws = self::retrieveWorkspace($workspace_id);

        try {
            $ws->setState($newState);
            $ws->save();
            return $ws;

        } catch (Exception $e) {
            $errors = array();
            $errors["errorChangingWorkspaceState"] = $e->getMessage();
            throw new InfinityMetricsException("Can't change Workspace state", $errors);
        }
    }

    /**
     * This method implements UC102 View Workspace Collection  
     * @param string $user_id is the user identification
     * @throws InfinityMetricsException if the user_id is empty. Also if it refers to a non-existing user, and other
     * possible persistence problems.
     * @return workspace['OWN'] = array with owned workspaces
     *         workspace['SHARED'] = array with shared workspaces
     */
    public static function retrieveWorkspaceCollection($user_id) {
        $error = array();
        if (!isset($user_id) || $user_id == "") {
            $error["userId"] = "The username identification is empty";
        }
        if (count($error) > 0) {
            throw new InfinityMetricsException("Can't retrieve workspace collection", $error);
        }

        $user = UserManagementController::retrieveUser($user_id);
        $wsCollection = array( 'OWN' => array(), 'SHARED' => array() );
        try {

            $criteria = new Criteria();
            $criteria->add(PersistentWorkspacePeer::USER_ID, $user->getUserId());
            $wsCollection['OWN'] = PersistentWorkspacePeer::doSelect($criteria);

            $criteria->clear();
            $criteria->add(PersistentWorkspaceSharePeer::USER_ID, $user->getUserId());
            $wsCollection['SHARED'] = PersistentWorkspaceSharePeer::doSelectJoinWorkspace($criteria);
            
            return $wsCollection;

        } catch (Exception $e) {
            $errors = array();
            $errors["errorChangingWorkspaceState"] = $e->getMessage();
            throw new InfinityMetricsException("Can't retrieve workspace collection", $errors);
        }
    }
    /**
     * This method implements UC101 View Workspace Profile
     * @param string $workspace_id is the workspace identification or any other and other possible persistence problems.
     * @throws InfinityMetricsException if the given workspace_id is empty. Also if it refers to a non-existing workspace.
     * @return PersistentWorkspace the instance of the workspace for the given workspace_id
     */
    public static function retrieveWorkspace($workspace_id) {
        if (!isset($workspace_id)|| $workspace_id == "") {
            $errors = array();
            $errors["workspace_id"] = "The workspace id must be provided";
            throw new InfinityMetricsException("Can't retrieve workspace", $errors);
        }

        $ws = PersistentWorkspacePeer::retrieveByPK($workspace_id);
        if ($ws == NULL) {
            $errors = array();
            $errors["workspaceNotFound"] = "The workspace referred by " . $workspace_id . " doesn't exist";
            throw new InfinityMetricsException("Can't retrieve workspace", $errors);
        }
        return $ws;
    }
    /**
     * Validates the share of worspace
     * @param string $workspace_id is the identification of the workspace
     * @param string $jnUsernameWithWhomToShareWorkspace username for the user
     * @throws InfinityMetricsException if any of the given parameters is invalid.
     */
    private static function validateShareWorkspaceForm($workspace_id, $userIdWithWhomToShareWorkspace) {
        $error = array();
        if (!isset($workspace_id)|| $workspace_id == "") {
            $errors["workspace_id"] = "The workspace id must be provided";
        }
        if (!isset($jnUsernameWithWhomToShareWorkspace) || $userIdWithWhomToShareWorkspace == "") {
            $errors["userIdWithWhomToShareWorkspace"] = "The username of the user to be shared with must be provided";
        }
        if (count($error) > 0) {
            throw new InfinityMetricsException("Can't share workspace", $error);
        }
    }

    /**
     * This method implements UC104 Share Metrics Workspace
     * @param string $workspace_id is the identification of the workspace
     * @param string $jnUsernameWithWhomToShareWorkspace the username of the user to share the workspace with
     * @throws InfinityMetricsException if the given parameters are invalid. Also, if either the workspace or the given
     * user identification to be have the workspace shared with doesn't exist, or other related persistence problems.
     * @return PersistentWorkspaceShare the instance of the workspace share as a confirmation
     */
    public static function shareWorkspace($workspace_id, $userIdWithWhomToShareWorkspace) {

        self::validateShareWorkspaceForm($workspace_id, $userIdWithWhomToShareWorkspace);

        //already has validation for the workspace
        $ws = self::retrieveWorkspace($workspace_id);

        $user = UserManagementController::retrieveUser($userIdWithWhomToShareWorkspace);
        
        if ( $ws->isSharedWithUser($user->getUserId()) )
        {
            $errors = array();
            $errors["alreadyShared"] = "The workspace referred by " . $workspace_id . " is already shared with user ".
                                                    "referred by " . $userIdWithWhomToShareWorkspace;
            throw new InfinityMetricsException("Can't share workspace", $errors);
        }
        try {
            $wss = new PersistentWorkspaceShare();
            $wss->setWorkspaceId($workspace_id);
            $wss->setUserId($user->getUserId());
            $wss->save();
            return $wss;

        } catch (Exception $e) {
            $errors = array();
            $errors["errorSharingWorkspace"] = $e->getMessage();
            throw new InfinityMetricsException("Can't share workspace", $errors);
        }
    }
    /**
     * Validates the update workspace form
     * @param string $workspaceId is the identification of the user
     * @param string $projectName is the project name
     * @param string $title is the title for the workspace
     * @param string $description is the description for the workspace
     * @throws InfinityMetricsException if any of the given parameters is invalid
     */
    private static function validateUpdateWorkspaceForm($workspaceId, $title, $description) {

        $error = array();
        if (!isset($workspaceId) || $workspaceId == "") {
            $error["workspaceId"] = "The workspace identification is empty";
        }
        if (!isset($title) || $title == "") {
            $error["title"] = "The title is empty";
        }
        if (!isset($description) || $description == "") {
            $error["description"] = "The description is empty";
        }

        if (count($error) > 0) {
            throw new InfinityMetricsException("Can't update workspace profile", $error);
        }
    }
    /**
     * Implements the UC103 Update Workspace Profile
     * @param string $workspace_id is the identification of the workspace
     * @param string $newTitle is the title of the workspace
     * @param string $newDescription is the description of the workspace
     * @return PersistentWorkspace the udpated version of the workspace
     */
    public static function updateWorkspaceProfile($workspace_id, $newTitle, $newDescription) {

        self::validateUpdateWorkspaceForm($workspace_id, $newTitle, $newDescription);

        $ws = self::retrieveWorkspace($workspace_id);

        try {
            $ws->setTitle($newTitle);
            $ws->setDescription($newDescription);
            $ws->save();
            return $ws;
                
        } catch (Exception $e) {
            $errors = array();
            $errors["errorSharingWorkspace"] = $e->getMessage();
            throw new InfinityMetricsException("Can't update workspace", $errors);
        }
    }
}
?>
