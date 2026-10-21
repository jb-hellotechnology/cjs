<?php

  echo $HTML->title_panel([
    'heading' => $Lang->get('Volunteer Import - Do You Want To Import These Records?'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/volunteers/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  #Main Panel Start

  $HTML->main_panel_start();

  #Some Content Here
  //if (PerchUtil::count($volunteers)) {

      echo $Form->form_start();
?>
    <table class="d">
        <thead>
            <tr>                
                <th>Title</th>
                <th>Employer</th>
                <th>EmployerID</th>
                <th>Closing Date</th>             
                <th>Closing Date Text</th> 
                <th>Int. Date</th>
                <th>Location</th>
                <th>Pay/Benefits</th>
                <th>Occasional</th>
                <th>Once/Twice/Week</th>             
                <th>Part Time</th>
                <th>Full Time</th>
                <th>Six Months</th>
                <th>Six Months - Year</th>
                <th>One Year or More</th>
                <th>Video</th>
                <th>Role</th>
                <th>Admin/IT</th>
                <th>Arboriculture</th>
                <th>Community</th>
                <th>Countryside</th>
                <th>Ecology</th>
                <th>Education</th>
                <th>Horticulture</th>
                <th>Publicity</th>
                <th>Rights of Way</th>
                <th>Visitors</th>
                <th>Wildlife</th>
                <th>National</th>
                <th>International</th>
                <th>Scotland</th>
                <th>Scotland North</th>
                <th>Scotland South</th>
                <th>Wales</th>
                <th>Northern Ireland</th>
                <th>England</th>
                <th>Anglia</th>
                <th>Midlands</th>
                <th>North East</th>
                <th>North West</th>
                <th>South</th>
                <th>South West</th>
                <th>County</th>
                <th>Accommodation</th>
                <th>Expenses</th>
                <th>Training</th>
                <th>Logo</th>
                <th>ALT</th>
                <th>Long Running</th>
                <th>Delete Date</th>
                <th>Display Until</th>
                <th>Free Linage</th>
                <th>Display From</th>
                <th>Archive</th>
                <th>Advert Status</th>
                <th>volunteerDynamicFields</th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($volunteers as $Volunteer) {
?>
            <tr>
                <td><?php echo $Volunteer['title']; ?></a></td>
                <td><?php echo $Volunteer['employerDisplay']; ?></a></td>
                <td><?php echo $Volunteer['employerID']; ?></a></td>
                <td><?php echo $Volunteer['closingDate']; ?></a></td>
                <td><?php echo $Volunteer['closingDateT']; ?></a></td>
                <td><?php echo $Volunteer['intDate']; ?></a></td>
                <td><?php echo $Volunteer['location']; ?></a></td>
                <td><?php echo $Volunteer['payBenefits']; ?></a></td>
                <td><?php echo $Volunteer['tcOccasional']; ?></a></td>
                <td><?php echo $Volunteer['tcOnceorTwiceWeek']; ?></a></td>
                <td><?php echo $Volunteer['tcPartTime']; ?></a></td>
                <td><?php echo $Volunteer['tcFullTime']; ?></a></td>
                <td><?php echo $Volunteer['tcSixMonths']; ?></a></td>
                <td><?php echo $Volunteer['tcSixMonthYear']; ?></a></td>
                <td><?php echo $Volunteer['tcOneYearMore']; ?></a></td>
                <td><?php echo $Volunteer['vidEmbedC']; ?></a></td>
                <td><?php echo $Volunteer['typeRole']; ?></a></td>
                <td><?php echo $Volunteer['sAdminIT']; ?></a></td>
                <td><?php echo $Volunteer['sArbo']; ?></a></td>
                <td><?php echo $Volunteer['sComVol']; ?></a></td>
                <td><?php echo $Volunteer['sCountry']; ?></a></td>
                <td><?php echo $Volunteer['sEco']; ?></a></td>
                <td><?php echo $Volunteer['sEnvOE']; ?></a></td>
                <td><?php echo $Volunteer['sHorti']; ?></a></td>
                <td><?php echo $Volunteer['sPubF']; ?></a></td>
                <td><?php echo $Volunteer['sRoW']; ?></a></td>
                <td><?php echo $Volunteer['sVisMan']; ?></a></td>
                <td><?php echo $Volunteer['sWiWoAnC']; ?></a></td>
                <td><?php echo $Volunteer['rNational']; ?></a></td>
                <td><?php echo $Volunteer['rInternational']; ?></a></td>
                <td><?php echo $Volunteer['rScotland']; ?></a></td>
                <td><?php echo $Volunteer['rScotlandNorth']; ?></a></td>
                <td><?php echo $Volunteer['rScotlandSouth']; ?></a></td>
                <td><?php echo $Volunteer['rWales']; ?></a></td>
                <td><?php echo $Volunteer['rNorthernIreland']; ?></a></td>
                <td><?php echo $Volunteer['rEngland']; ?></a></td>
                <td><?php echo $Volunteer['rAnglia']; ?></a></td>
                <td><?php echo $Volunteer['rMidlands']; ?></a></td>
                <td><?php echo $Volunteer['rNorthEast']; ?></a></td>
                <td><?php echo $Volunteer['rNorthWest']; ?></a></td>
                <td><?php echo $Volunteer['rSouth']; ?></a></td>
                <td><?php echo $Volunteer['rSouthWest']; ?></a></td>
                <td><?php echo $Volunteer['rCounty']; ?></a></td>
                <td><?php echo $Volunteer['accommodation']; ?></a></td>
                <td><?php echo $Volunteer['expenses']; ?></a></td>
                <td><?php echo $Volunteer['fTraining']; ?></a></td>
                <td><?php echo $Volunteer['imageLogo']; ?></a></td>
                <td><?php echo $Volunteer['imageAlt']; ?></a></td>
                <td><?php echo $Volunteer['longRun']; ?></a></td>
                <td><?php echo $Volunteer['deleteDate']; ?></a></td>
                <td><?php echo $Volunteer['displayUntil']; ?></a></td>
                <td><?php echo $Volunteer['freeLin']; ?></a></td>
                <td><?php echo $Volunteer['displayFrom']; ?></a></td>
                <td><?php echo $Volunteer['archive']; ?></a></td>
                <td><?php echo $Volunteer['advertStatus']; ?></a></td>
                <td><?php echo $Volunteer['volunteerDynamicFields']; ?></a></td>
            </tr>
<?php
    }
?>
        </tbody>
    </table>

<?php    

    //Submit Button
    echo $Form->submit_field('btnSubmit', 'Import', '');

    echo $Form->form_end();	       


  #Main Panel End

  $HTML->main_panel_end();