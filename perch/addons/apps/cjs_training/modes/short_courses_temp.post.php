<?php
	echo $HTML->title_panel([
    'heading' => $Lang->get('Short Courses'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/short_courses/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);

    # Main panel

  $HTML->main_panel_start();

  if ($message) echo $message;    
  elseif (PerchUtil::count($scourses)) {

    echo $Form->form_start();

?>
    <table class="d">
        <thead>
            <tr>
                <th>Title</th>
                <th>Start Date</th>
                <th>Start Text</th>
                <th>Category 1</th>
                <th>Category 2</th>
                <th>Category 3</th>
                <th>Days</th>
                <th>Location</th>
                <th>Webinar</th>
                <th>Online</th>
                <th>County</th>
                <th>Provider</th>
                <th>Advert Telephone</th>
                <th>Advert Email</th>
                <th>Advert Website</th>
                <th>Advert Website Short</th>
                <th>Advert Link</th>
                <th>Free Course</th>
                <th>Logo</th>
                <th>Logo ALT</th>
                <th>Display Ad</th>
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
                <th>Free Ad</th>
                <th>Course Cost</th>
                <th>Concessions</th>
                <th>Advert Status</th>
                <th>shortDynamicFields</th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($scourses as $Scourse) {
?>
            <tr>
                <td><?php echo $Scourse['shortTitle']; ?></td>
                <td><?php echo $Scourse['startDate']; ?></td>
                <td><?php echo $Scourse['startText']; ?></td>
                <td><?php echo $Scourse['categoryOne']; ?></td>
                <td><?php echo $Scourse['categoryTwo']; ?></td>
                <td><?php echo $Scourse['categoryThree']; ?></td>
                <td><?php echo $Scourse['numberDays']; ?></td>
                <td><?php echo $Scourse['location']; ?></td>
                <td><?php echo $Scourse['webinar']; ?></td>
                <td><?php echo $Scourse['online']; ?></td>
                <td><?php echo $Scourse['county']; ?></td>
                <td><?php echo $Scourse['provider']; ?></td>
                <td><?php echo $Scourse['advertTelephone']; ?></td>
                <td><?php echo $Scourse['advertEmail']; ?></td>
                <td><?php echo $Scourse['advertWebsite']; ?></td>
                <td><?php echo $Scourse['advertWebsiteShort']; ?></td>
                <td><?php echo $Scourse['advertLink']; ?></td>
                <td><?php echo $Scourse['freeCourse']; ?></td>
                <td><?php echo $Scourse['logo']; ?></td>
                <td><?php echo $Scourse['logoAlt']; ?></td>
                <td><?php echo $Scourse['displayAd']; ?></td>
                <td><?php echo $Scourse['organisationID']; ?></td>
                <td><?php echo $Scourse['organisationName']; ?></td>
                <td><?php echo $Scourse['contactName']; ?></td>
                <td><?php echo $Scourse['contactTelephone']; ?></td>
                <td><?php echo $Scourse['contactEmail']; ?></td>
                <td><?php echo $Scourse['firstOnline']; ?></td>
                <td><?php echo $Scourse['checkDate']; ?></td>
                <td><?php echo $Scourse['dateUpdated']; ?></td>
                <td><?php echo $Scourse['displayUntil']; ?></td>
                <td><?php echo $Scourse['advertType']; ?></td>
                <td><?php echo $Scourse['advertPaid']; ?></td>
                <td><?php echo $Scourse['advertCost']; ?></td>
                <td><?php echo $Scourse['freeAd']; ?></td>
                <td><?php echo $Scourse['courseCost']; ?></td>
                <td><?php echo $Scourse['concessionsAvailable']; ?></td>
                <td><?php echo $Scourse['advertStatus']; ?></td>
                <td><?php echo $Scourse['shortDynamicFields']; ?></td>
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