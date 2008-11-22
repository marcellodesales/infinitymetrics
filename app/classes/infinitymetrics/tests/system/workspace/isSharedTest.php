<?php

require_once('propel/Propel.php');
Propel::init("infinitymetrics/orm/config/om-conf.php");

require_once('infinitymetrics/controller/MetricsWorkspaceController.php');
require_once('infinitymetrics/model/user/User.class.php');
require_once('infinitymetrics/model/institution/Institution.class.php');
require_once('infinitymetrics/orm/PersistentProject.php');
require_once('infinitymetrics/orm/PersistentWorkspaceXProject.php');

$username = 'johntheteacher';
$title = 'New Title';
$description = 'New Description';
$abbrv = 'FAU';

$theUser = PersistentUserPeer::retrieveByJNUsername($username);

    if ($theUser == NULL )
    {
        $theUser = new User();
        $theUser->setJnUsername($username);
        $theUser->setJnPassword('password');
        $theUser->setFirstName('John');
        $theUser->setLastName('Instructor');
        $theUser->setEmail('johnc@institution.edu');
        $theUser->setType('I');

        $criteria = new Criteria();
        $criteria->add(PersistentInstitutionPeer::ABBREVIATION, $abbrv);

        $institutions = PersistentInstitutionPeer::doSelect($criteria);

        if($institutions == NULL)
        {
            $institution = new Institution();
            $institution->setAbbreviation($abbrv);
            $institution->setCity('Boca Raton');
            $institution->setCountry('USA');
            $institution->setName('Florida Atlantic University');
            $institution->setStateProvince('FL');
            $institution->save();
        }
        else {
            $institution = $institutions[0];
        }

        $theUser->setInstitution($institution);
        $theUser->save();
    }

if (!isset($institution)) {
    $institution = $theUser->getInstitution();
}

for ($i = 0; $i < 5; $i++) {
    $ws = MetricsWorkspaceController::createWorkspace(
        $theUser->getJnUsername(),
        $title.$i,
        $description.$i
    );

    $project = new PersistentProject();
    $project->setProjectJnName("ppm-".rand());
    $project->setSummary("Project $i summary");
    $wxp = new PersistentWorkspaceXProject();
    $wxp->setWorkspace($ws);
    $wxp->setProject($project);
    $wxp->save();

}


print_r ($ws->getProjects());

?>
