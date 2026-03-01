<?php
$home = 'main:forms:stepbystep';
$navbar->addOption(_M('MStepByStepForm Example', $module), $module, $home);

$steps = array( 1 => 'frmStepByStep1', 2 => 'frmStepByStep2', 3 => 'frmStepByStep3' );
$stepsDescription = array( 1 => _M('Car'), 2 => _M('Motorcycle'), 3 => _M('Review Data') );

// Uncomment the next line if you want image on buttons
//MStepByStepForm::setShowImageOnButtons(true);

$step = MStepByStepForm::getCurrentStep();
$form = $step ? $steps[$step] : array_shift($steps);

// don't forget to inform $stepsDescription, otherwise it will not show the steps on the top
$content = $MIOLO->getUI()->getForm($module, $form, $stepsDescription);

$theme->clearContent();
$theme->insertContent($content);
?>
