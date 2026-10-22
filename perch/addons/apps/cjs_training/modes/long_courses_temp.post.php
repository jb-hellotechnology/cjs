<?php
	echo $HTML->title_panel([
    'heading' => $Lang->get('Long Courses'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/long_courses/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);

    # Main panel

  $HTML->main_panel_start();

  if ($message) echo $message;
  elseif (PerchUtil::count($lcourses)) {

    echo $Form->form_start();

?>
    <table class="d">
        <thead>
            <tr>
                <th>Title</th>
                <th>Start Date</th>
                <th>Category 1</th>
                <th>Category 2</th>
                <th>Category 3</th>
                <th>Location</th>
                <th>Webinar</th>
                <th>County</th>
                <th>Provider</th>
                <th>Advert Telephone</th>
                <th>Advert Email</th>
                <th>Advert Website</th>
                <th>Advert Website Short</th>
                <th>Advert Link</th>
                <th>Logo</th>
                <th>Logo ALT</th>
                <th>Display Ad</th>
                <th>Short</th>
                <th>HND</th>
                <th>Diploma</th>
                <th>NVQ</th>
                <th>HNC</th>
                <th>MSc</th>
                <th>Degree</th>
                <th>BTEC</th>
                <th>Foundation</th>
                <th>PhD</th>
                <th>Apprenticeship</th>
                <th>Office ID</th>
                <th>Office Name</th>
                <th>Contact Name</th>
                <th>Contact Telephone</th>
                <th>Contact Email</th>
                <th>First Online</th>
                <th>Check Date</th>
                <th>Date Updated</th>
                <th>Display Until</th>
                <th>Advert Type</th>
                <th>Advert Paid</th>
                <th>Advert Cost</th>
                <th>Notes</th>
                <th>Free Ad</th>
                <th>Advert Status</th>
                <th>longDynamicFields</th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($lcourses as $Lcourse) {
?>
            <tr>
                <td><?php echo $Lcourse['longTitle']; ?></td>
                <td><?php echo $Lcourse['startDate']; ?></td>
                <td><?php echo $Lcourse['categoryOne']; ?></td>
                <td><?php echo $Lcourse['categoryTwo']; ?></td>
                <td><?php echo $Lcourse['categoryThree']; ?></td>
                <td><?php echo $Lcourse['location']; ?></td>
                <td><?php echo $Lcourse['webinar']; ?></td>
                <td><?php echo $Lcourse['county']; ?></td>
                <td><?php echo $Lcourse['provider']; ?></td>
                <td><?php echo $Lcourse['advertTelephone']; ?></td>
                <td><?php echo $Lcourse['advertEmail']; ?></td>
                <td><?php echo $Lcourse['advertWebsite']; ?></td>
                <td><?php echo $Lcourse['advertWebsiteShort']; ?></td>
                <td><?php echo $Lcourse['advertLink']; ?></td>
                <td><?php echo $Lcourse['logo']; ?></td>
                <td><?php echo $Lcourse['logoAlt']; ?></td>
                <td><?php echo $Lcourse['displayAd']; ?></td>
                <td><?php echo $Lcourse['cShort']; ?></td>
                <td><?php echo $Lcourse['cHnd']; ?></td>
                <td><?php echo $Lcourse['cDiploma']; ?></td>
                <td><?php echo $Lcourse['cNvq']; ?></td>
                <td><?php echo $Lcourse['cHnc']; ?></td>
                <td><?php echo $Lcourse['cMsc']; ?></td>
                <td><?php echo $Lcourse['cDegree']; ?></td>
                <td><?php echo $Lcourse['cBtec']; ?></td>
                <td><?php echo $Lcourse['cFoundation']; ?></td>
                <td><?php echo $Lcourse['cPhd']; ?></td>
                <td><?php echo $Lcourse['cApprenticeship']; ?></td>
                <td><?php echo $Lcourse['organisationID']; ?></td>
                <td><?php echo $Lcourse['organisationName']; ?></td>
                <td><?php echo $Lcourse['contactName']; ?></td>
                <td><?php echo $Lcourse['contactTelephone']; ?></td>
                <td><?php echo $Lcourse['contactEmail']; ?></td>
                <td><?php echo $Lcourse['firstOnline']; ?></td>
                <td><?php echo $Lcourse['checkDate']; ?></td>
                <td><?php echo $Lcourse['dateUpdated']; ?></td>
                <td><?php echo $Lcourse['displayUntil']; ?></td>
                <td><?php echo $Lcourse['advertType']; ?></td>
                <td><?php echo $Lcourse['advertPaid']; ?></td>
                <td><?php echo $Lcourse['advertCost']; ?></td>
                <td><?php echo $Lcourse['notes']; ?></td>
                <td><?php echo $Lcourse['freeAd']; ?></td>
                <td><?php echo $Lcourse['advertStatus']; ?></td>
                <td><?php echo $Lcourse['longDynamicFields']; ?></td>
            </tr>
<?php
    }
?>
        </tbody>
    </table>
<?php
    //Submit Button, date field and select field

    echo $Form->submit_field('btnSubmit', 'Import', '');

    echo $Form->form_end();

    } // if things

	$HTML->main_panel_end();
